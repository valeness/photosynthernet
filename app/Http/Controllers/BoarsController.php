<?php

    namespace App\Http\Controllers;

    use App\Http\Libraries\User;
    use View;
    use Auth;
    use DB;
    use Request;
    use Hash;
    use Redirect;
    use Session;
    use App\Jobs\TakeScreen;

    class BoarsController extends Controller {

        public function __construct() {
            $this->user = new User();
        }

        public function index() {
            $data = [];
            $bookmarks = DB::select('SELECT * FROM bookmarks');
            $data['bookmarks'] = $bookmarks;

            $user = Session::get('user');
            $expected = ['id', 'username', 'bookmark_id', 'email'];
            foreach($expected as $k => $v) {
                if(empty($user[$v])) {
                    $user[$v] = '';
                }
            }

            $data['user'] = $user;

            return View::make('boars')->with($data);
        }

        public function login_view(){
            $data = [];
            $data['verified'] = Session::get('user')['id'] ? True : False;
            return View::make('login')->with($data);
        }

        public function login_api($request = []){
            if(empty($request)) {
                $request = Request::all();
            }
            $retval = [];
            $res = $this->user->login($request['username'], $request['pass']);
            if(empty($res['error'])) {
                $retval['validated'] = 1;
            } else {
                $retval['validated'] = 0;
            }
            return json_encode($retval);
        }

        public function logout() {
            $this->user->logout();
        }

        private function createUser($request) {
            $retval = [
                'status' => 1
            ];
            $required = [
                'username' => [
                    'human' => 'Username'
                ],
                'email' => [
                    'human' => 'E-Mail'
                ],
                'pass' => [
                    'human' => 'Password'
                ],
                'confirm_pass' => [
                    'human' => 'Confirm Password'
                ]
            ];
            $error = [];
            foreach($required as $k => $v) {
                if(empty($request[$k])) {
                    $error[] = $v['human'] . ' is required';
                }
            }

            if(!$error) {
                // Make sure the user doesn't exist
                $existing = DB::select('SELECT * FROM users WHERE email = ? OR username = ?', array($request['email'], $request['username']));

                if (empty($existing)) {
                    // Password Hash / Verification
                    if ($request['pass'] == $request['confirm_pass']) {
                        $password = Hash::make($request['pass']);
                        $bookmark_id = 'mark_' . md5(uniqid());
                        $ins = DB::insert('INSERT INTO users (username, password, email, bookmark_id) VALUES (?, ?, ?, ?)', [$request['username'], $password, $request['email'], $bookmark_id]);
                        $login_data = $this->user->login($request['username'], $request['pass']);
                        if($login_data['error'] != '') {
                            var_dump('What the literal fuck?');
                            exit;
                        }
                    } else {
                        $error[] = 'Your Passwords do not match';
                    }

                } else {
                    $error[] = 'Account Already Exists';
                }
            }

            if(!empty($error)) {
                $retval['status'] = 0;
            }
            $retval['error'] = $error;
            return $retval;

        }

        public function register_view(){
            return View::make('register');
        }

        public function register() {
            $request = Request::all();
            $retval = $this->createUser($request);
            return $retval;
        }

        public function get_bookmarks() {
            $user = Session::get('user');
            if(!Auth::check()) {
                return redirect()->to('/boars/login');
            }
            $data= [];
            $bookmark_id = $user['bookmark_id'];
            $bookmarks = DB::select('SELECT * FROM bookmarks WHERE bookmark_id = ?', [$bookmark_id]);
            $data['bookmarks'] = $bookmarks;
            $data['user'] = $user;
            return View::make('bookmarks')->with($data);
        }

        public function delete() {
            $request = Request::all();
            $id = $request['id'];

            if(!is_numeric($id)) {
                exit;
            } else {
                $del = DB::delete('DELETE FROM bookmarks WHERE id = ?', [$id]);
            }
        }

        public function add($auth) {
            $ref = $_SERVER['HTTP_REFERER'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $ref);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $allowed_http = [200, 302, 403];
            if(!in_array($http_code, $allowed_http)) {
                exit;
            }
            else {
                if($http_code == 302) {
                    $title = 'Need Authentication | Was Redirected';
                } elseif($http_code == 403) {
                    $ret = 'Access Denied | Probably by Firewall. In the future we will emulate a browser with something like selenium to bypass this';
                    echo $ret;
                    return false;
                }
                else {
                    $title = explode('<title>', $output);
                    if(!empty($title[1])) {
                        $title = explode('</title>', $title[1])[0];
                    } else {
                        $title = $ref;
                    }
                }
                $url = $ref;
                $tags = '';
                $curr_marks = DB::select('SELECT url from bookmarks WHERE bookmark_id = ?', [$auth]);
                $ret = '';
                foreach($curr_marks as $mark) {
                    if($mark['url'] == $url) {
                        $ret = 'Page already bookmarked!';
                        $error = 4;
                    }
                }
                if(empty($error)) {
//                    $this->dispatch(new TakeScreen($url));
                    $ins = DB::insert('INSERT INTO bookmarks (name, url, tags, bookmark_id) VALUES (?, ?, ?, ?)', [$title, $url, $tags, $auth]);
                    if($ins) {
                        $ret = 'Success';
                    }
                }
                echo $ret;
            }
        }
    }
