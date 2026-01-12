<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\libraries;

/**
 * Class User
 */
class User {
    private mixed $_db;
    private array $_data = [];
    private string $_sessionName;
    private string $_cookieName;
    private bool $_isLoggedIn;

    public function __construct($db = null, $user = null) 
    {
        $this->_db = $db;

        $this->_sessionName = Config::get('session/session_name');        
        $this->_cookieName = Config::get('remember/cookie_name');

        // Initialize _isLoggedIn to false by default
        $this->_isLoggedIn = false;

        if (!$user) {
            if (Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);
                if ($this->find($user)) {
                    $this->_isLoggedIn = true;
                }
            } 
        } else {
            $this->find($user);
            // Validate session if user ID is provided
            $this->validateSession();
        }
    }

    private function validateSession(): void
    {
        if ($this->_isLoggedIn) {
            // Check if users_session entry exists
            $usersSessionExists = $this->_db->has("users_session", ["user_id" => $this->data()["userid"]]);
            if (!$usersSessionExists) {
                // If users_session entry doesn't exist, log out the user
                $this->logout();
            }
        }
    }
    
    public function find($user = null): bool
    {
        if ($user) {
            $field = (is_numeric($user)) ? 'userid' : 'email';
            $datas = $this->_db->select("user", "*", [$field => $user]);

            if ($datas) {
                foreach ($datas as $data) {
                    if ($data) {
                        $this->_data = $data;
                        return true;  
                    }
                }
            }
        }
        return false;    
    }

    public function login($email = null, $password = null, $remember = false): bool
    {
        if (!$email && !$password && $this->exists()) {
            Session::put($this->_sessionName, $this->data()["userid"]);
            return true;
        } else {

            $user = $this->find($email);
            
            if ($user) {

                if (password_verify($password, $this->data()["password"])) {
                    Session::put($this->_sessionName, $this->data()["userid"]);

                    if ($remember) {
                        $hash = Hash::unique();

                        $hashChecks = $this->_db->select("users_session", "*", ["user_id" => $this->data()["userid"]]);
                        foreach ($hashChecks as $hashCheck) {
                            $hash = $hashCheck["hash"];
                        }

                        if (!$this->_db->has("users_session", ["user_id" => $this->data()["userid"]])) {
                            $this->_db->insert('users_session', [
                                'user_id' => $this->data()["userid"],
                                'hash' => $hash
                            ]);
                        }
                        Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                    }

                    return true;
                }
            }
        }
        return false;
    }

    public function exists(): bool
    {
        return !empty($this->_sessionName);
    }

    public function logout(): void
    {
        $this->_db->delete("users_session", ["user_id" => $this->data()["userid"]]);

        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }

    public function data(): array
    {  
        return $this->_data;
    }


    public function isLoggedIn(): bool
    {
        return $this->_isLoggedIn;
    }
}
