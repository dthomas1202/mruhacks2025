<?php
class linkDB {
    private $pdo;

    /**
     * Constructor — opens SQLite connection
     */
    public function __construct($dbPath) {
        try {
            $this->pdo = new PDO("sqlite:" . $dbPath);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
/* HANDLING ACTIONS!
 *  <?php
    require_once __DIR__ . '/linkDB.php';
    $dbPath = __DIR__ . '/link.db';
    $db = new linkDB($dbPath);
    $message = '';
    $action = $_POST['action'] ?? null;


if ($action === 'create') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $result = $db->createUser($name, $email);
    $message = isset($result['error']) ? "❌ Error: " . $result['error'] : "✅        User created (ID: " . $result['id'] . ")";
    }

 *
 *
 *
 *
 *
 *
 * */
    /**
     *
     *
     * CREATE — Insert a new user
     * specify :
     * $userName - string
     * $userEmail - string
     * $userPassword - string
     * $userSubject - string
     * $userSkills - string
     * $preferredTraffic - int
     * $preferredTimes - int
     */
    public function createUser($userName, $userEmail, $userPassword, $userSubject, $userSkills, $preferredTraffic, $preferredTimes) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO users (
            userID,
            userEmail,
            userPassword,
            userSubject,
            userSkills,
            preferredTraffic,
            preferredTimes,
            currentSession)
            VALUES (
                :userName,
                :userPassword,
                :userSubject,
                :userSkills,
                :preferredTraffic,
                :preferredTimes,
                :session)");
            $stmt->execute([
                ':userName' => $userName,
                ':userEmail' => $userEmail,
                ':userPassword' => $userPassword,
                ':userSubject' => $userSubject,
                ':userSkills' => $userSkills,
                ':preferredTraffic' => $preferredTraffic,
                ':preferredTimes' => $preferredTimes
                ':session' => 0]);
            return ["success" => true, "id" => $this->pdo->lastInsertId()];
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }

    /**
     * READ — Get all users or a specific one
     */
    public function getUsers($id = null) {
        try {
            if ($id !== null) {
                $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
                $stmt->execute([':id' => intval($id)]);
                return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
            } else {
                $stmt = $this->pdo->query("SELECT * FROM users ORDER BY id ASC");
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }

    /**
     * UPDATE — Modify a user
     */
    public function updateUser($id, $name, $email, $password, $subject, $skills, $traffic, $times, $session) {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET
            userName = :name,
            userEmail = :email,
            userPassword = :password,
            userSubject = :subject,
            userSkills = :skills ,
            preferredTraffic = :traffic,
            preferredTimes = :times,
            currentSession = :session
            WHERE id = :id)";
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $password,
                ':subject' => $subject,
                ':skills' => $skills,
                ':traffic' => $traffic,
                ':times' => $times,
                ':session' => $session]);
            return ["success" => $stmt->rowCount() > 0];
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }

    /**
     * DELETE — Remove a user
     */
    public function deleteUser($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute([':id' => intval($id)]);
            return ["success" => $stmt->rowCount() > 0];
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }

    /** Optional: close connection manually */
    public function close() {
        $this->pdo = null;
    }
}
?>
