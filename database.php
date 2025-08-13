<?php
require_once 'config.php';

class Database {
    private $connection;

    public function __construct() {
        $this->connect();
        $this->createTables();
    }

    private function connect() {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }

        $this->connection->set_charset("utf8mb4");
    }

    private function createTables() {
        $sql = "CREATE TABLE IF NOT EXISTS notes (
            id VARCHAR(32) PRIMARY KEY,
            content TEXT NOT NULL,
            language VARCHAR(20) DEFAULT 'text',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            expires_at TIMESTAMP NULL,
            is_viewed BOOLEAN DEFAULT FALSE
        )";

        if (!$this->connection->query($sql)) {
            die("Error creating table: " . $this->connection->error);
        }
    }

    public function createNote($id, $content, $language = 'text', $expiresAt = null) {
        $stmt = $this->connection->prepare("INSERT INTO notes (id, content, language, expires_at) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $id, $content, $language, $expiresAt);
        return $stmt->execute();
    }

    public function getNote($id) {
        $stmt = $this->connection->prepare("SELECT * FROM notes WHERE id = ? AND is_viewed = FALSE AND (expires_at IS NULL OR expires_at > NOW())");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function markNoteAsUsed($id) {
        $stmt = $this->connection->prepare("UPDATE notes SET is_viewed = TRUE WHERE id = ?");
        $stmt->bind_param("s", $id);
        return $stmt->execute();
    }

    public function deleteExpiredNotes() {
        $sql = "DELETE FROM notes WHERE (expires_at IS NOT NULL AND expires_at < NOW()) OR is_viewed = TRUE";
        return $this->connection->query($sql);
    }

    public function close() {
        $this->connection->close();
    }
}
?>
