<?php

namespace Klever\Auth;


use Klever\Models\User;

class Auth
{

    /**
     * Process authentication request
     *
     * @param $username
     * @param $password
     * @return bool
     */
    function attempt($username, $password)
    {
        // try to get user from DB
        $user = User::where('username', $username)->first();

        // not found
        if ( ! $user) {
            return false;
        }

        // we have a match
        if (password_verify($password, $user->password)) {

            // generate a new session id for logged in user
            session()->regenerate();

            // session var with user id to do stuff with
            session()->set('user', $user->id);

            // fingerprint user session to prevent session hijacking
            session()->set('fingerprint', $this->fingerprint());

            return true;
        }

        return false;
    }


    /**
     * Verifies users session variables
     *
     * @return bool
     */
    function verifySession()
    {
        $fingerprint = $this->fingerprint();

        // if no user session is set we can return immediately
        if ( ! session()->exists('user'))
        {
            return false;
        }

        /**
         * check if fingerprint is valid to keep user logged in
         */
        if (session()->exists('fingerprint')
            && session()->get('fingerprint') == $fingerprint) {
            return true;
        }

        return false;
    }

    /**
     * Destroy user session
     */
    function logout()
    {
        session()->destroy();
    }

    /**
     * Generate fingerprint from User Agent & Remote IP
     *
     * @return string
     */
    private function fingerprint()
    {
        $ua = request()->getServerParam('HTTP_USER_AGENT');
        $ip = request()->getServerParam('REMOTE_ADDR');
        return hash_hmac('sha512', $ua, hash('sha512', $ip, true));
    }
}