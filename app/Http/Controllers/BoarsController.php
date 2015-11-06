<?php

    namespace App\Http\Controllers;

    use View;
    use DB;
    use Request;
    use Hash;
    use Redirect;
    use Session;

    class BoarsController extends Controller {

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

        public function login_api(){
            $request = Request::all();
            $retval = [];
            $res = $this->login($request['username'], $request['pass']);
            if(empty($res['error'])) {
                $retval['validated'] = 1;
            } else {
                $retval['validated'] = 0;
            }
            return json_encode($retval);
        }

        public function login($username, $password){
            $user = DB::select('SELECT * FROM users WHERE username = ?', [$username]);
            $error = '';
            if(!empty($user) && count($user) == 1) {
                $user = $user[0];
                $db_pass = $user['password'];
                $sess_user = Session::get('user');
                if(!empty($sess_user)) {
                    Session::forget('user');
                }
            } else {
                $error = 'User Not Found';
            }
            if(!$error) {
                if(Hash::check($password, $db_pass)) {
                    // Login Successfully
                    unset($user['password']);
                    Session::put('user', $user);
                }
            }

            $retval['error'] = $error;
            return $retval;
        }

        public function logout() {
            Session::forget('user');
            Session::destroy();
        }

        private function createUser($request) {
            $retval = [];
            $required = ['username', 'email', 'pass', 'confirm_pass'];
            $error = '';
            foreach($required as $v) {
                if(empty($request[$v])) {
                    $error .= $v . ' is required';
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
                        $login_data = $this->login($request['username'], $request['pass']);
                        if($login_data['error'] != '') {
                            var_dump('What the literal fuck?');
                            exit;
                        } else {
                            return Redirect::to('/');
                        }
                    } else {
                        $error .= 'Your Passwords do not match \n';
                    }

                } else {
                    $error .= 'Account Already Exists \n';
                }
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
            if(empty($user)) {
                exit;
            }
            $data= [];
            $bookmark_id = $user['bookmark_id'];
            $bookmarks = DB::select('SELECT * FROM bookmarks WHERE bookmark_id = ?', [$bookmark_id]);
            $data['bookmarks'] = $bookmarks;
            $data['user'] = $user;
            return View::make('bookmarks')->with($data);
        }

        public function add($auth) {
            $ref = $_SERVER['HTTP_REFERER'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $ref);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if($http_code != 200) {
                exit;
            } else {
                $title = explode('<title>', $output);
                if(!empty($title[1])) {
                    $title = explode('</title>', $title[1])[0];
                } else {
                    $title = $ref;
                }
                $url = $ref;
                $tags = '';
                $curr_marks = DB::select('SELECT url from bookmarks WHERE bookmark_id = ?', [$auth]);
                $ret = '';
                foreach($curr_marks as $mark) {
                    if($mark['url'] == $url) {
                        $ret = 'Url already bookmarked';
                    }
                }
                $ins = DB::insert('INSERT INTO bookmarks (name, url, tags, bookmark_id) VALUES (?, ?, ?, ?)', [$title, $url, $tags, $auth]);
                if($ins) {
                    $ret = 'Success';
                }
                echo $ret;
            }
        }
    }
