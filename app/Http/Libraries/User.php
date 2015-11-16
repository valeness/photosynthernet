<?php namespace App\Http\Libraries;

use Session;
use Auth;
use DB;


class User {
    public function __construct() {

    }

    /*
     * Log the User In
     */
    public function login($username, $password) {
        $user = DB::select('SELECT * FROM users WHERE username = ?', [$username]);
        $error = '';
        if(!empty($user) && count($user) == 1) {
            $user = $user[0];
        } else {
            $error = 'User Not Found';
        }
        if(!$error) {
            if(Auth::attempt(['username'=>$user['username'], 'password'=>$password])) {
                Session::put('user', $user);
            } else {
                $error = 'Invalid Password';
            }
        }

        $retval['error'] = $error;
        return $retval;
    }

    /*
     * Log the user out
     */
    public function logout($redirect=True) {
        Session::forget('user');
        Auth::logout();
        if($redirect) {
            return redirect()->to('/boars');
        }
    }
}