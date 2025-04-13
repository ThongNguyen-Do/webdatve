<?php
class Auth {
    private $user;

    public function __construct($conn) {
        $this->user = new User($conn);
    }

    public function register($username, $password, $email, $full_name) {
        $this->user->createUser($username, $password, $email, $full_name);
    }

    public function login($username, $password) {
        return $this->user->authenticate($username, $password);
    }
}
?>
