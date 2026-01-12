<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\libraries;

/**
 * 
 */
class Admin {
    private mixed $_db;
    private array $_data = [];
    private string $_sessionAdmin;
    private string $_cookieName;
    private bool $_isLoggedIn;

    public function __construct($db = null, $admin = null)
    {
        $this->_db = $db;
        $this->_sessionAdmin = Config::get('session/session_admin');
        $this->_cookieName = Config::get('remember/cookie_name');

        // Initialize _isLoggedIn to false by default
        $this->_isLoggedIn = false;

        if (!$admin) {
            if (Session::exists($this->_sessionAdmin)) {
                $admin = Session::get($this->_sessionAdmin);
                if ($this->find($admin)) {
                    // Set _isLoggedIn to true only when a user is found
                    $this->_isLoggedIn = true;
                }
            }
        } else {
            $this->find($admin);
        }
    }


    public function find($admin = null): bool
    {
        if ($admin) {
            $field = (is_numeric($admin)) ? 'adminid' : 'email';
            $datas = $this->_db->select("admin", "*", [$field => $admin]);

            foreach ($datas as $data) {
                $this->_data = $data;
                return true;
            }
        }
        return false;
    }

    public function login($email = null, $password = null, $remember = false): bool
    {
        if (!$email && !$password && $this->exists()) {
            Session::put($this->_sessionAdmin, $this->data()["adminid"]);

        } else {

            $admin = $this->find($email);
            if ($admin) {              

                if (password_verify($password, $this->data()["password"])) {
                    Session::put($this->_sessionAdmin, $this->data()["adminid"]);

                    if ($remember) {
                        $hashCheck = $this->_db->has("users_session", ["user_id" => $this->data()["adminid"]]);

                        if (!$hashCheck) {
                            $hash = Hash::unique();
                            $this->_db->insert('users_session', [
                                'user_id' => $this->data()["adminid"],
                                'hash' => $hash
                            ]); 
                        } else {
                            $hashMade = $this->_db->get("users_session", ["hash"], ["user_id" => $this->data()["adminid"]]);
                            $hash = $hashMade["hash"];
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
        return !empty($this->_sessionAdmin);
    }

    public function logout(): void
    {
        $this->_db->delete("users_session", ["user_id" => $this->data()["adminid"]]);

        Session::delete($this->_sessionAdmin);
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

