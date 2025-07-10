<?php
class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findByEmail($email)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM usuario WHERE correo = :email AND activo = 1 LIMIT 1");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("User find error: " . $e->getMessage());
            return false;
        }
    }

    public function recordLoginAttempt($userId, $success)
    {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO login_attempts (usuario_id, fecha, exito, ip_address, user_agent) 
                 VALUES (:user_id, NOW(), :success, :ip, :ua)"
            );

            $ip = $_SERVER['REMOTE_ADDR'];
            $ua = $_SERVER['HTTP_USER_AGENT'];

            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':success', $success, PDO::PARAM_BOOL);
            $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
            $stmt->bindParam(':ua', $ua, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Login attempt recording error: " . $e->getMessage());
            return false;
        }
    }

    public function getFailedAttempts($email, $hours = 1)
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) as attempts 
                 FROM login_attempts la 
                 JOIN usuario u ON la.usuario_id = u.id 
                 WHERE u.correo = :email AND la.exito = 0 AND la.fecha > DATE_SUB(NOW(), INTERVAL :hours HOUR)"
            );

            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':hours', $hours, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['attempts'];
        } catch (PDOException $e) {
            error_log("Failed attempts check error: " . $e->getMessage());
            return 0;
        }
    }
}