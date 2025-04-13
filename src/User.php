<?php
class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function createUser($username, $password, $email, $full_name) {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (username, password, email, full_name) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$username, $passwordHash, $email, $full_name]);
    }

    public function authenticate($username, $password) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return null;
    }
}
?>
