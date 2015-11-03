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
            return View::make('boars')->with($data);
        }

        public function login_view(){
            return View::make('login');
        }

        public function login_api(){
            $request = Request::all();

        }

        public function login($username, $password){
            $user = DB::select('SELECT * FROM users WHERE username = ?', [$username]);
            $error = '';
            if(!empty($user) && count($user) == 1) {
                $user = $user[0];
                $db_pass = $user['password'];
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

        public function add() {
            echo 'Thing';
        }
    }
