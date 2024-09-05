<?php
namespace Database;

use PDO;
use PDOException;

class Database
{
    private $host = 'localhost';
    private $db_name = 'petrol';
    private $username = 'root';
    private $password = '';
    private $conn;

    // Constructor to initialize the connection
    public function __construct()
    {
        $this->connect();
    }

    // Method to establish a database connection
   private function connect()
    {
        $this->conn = null;

        $host = getenv('DB_HOST');
        $db_name = getenv('DB_NAME');
        $username = getenv('DB_USER');
        $password = getenv('DB_PASS');

        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }
    }

    // Method to prepare a query
    public function prepare($query)
    {
        return $this->conn->prepare($query);
    }

    // Method to read data from the database
    public function read($query, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Read error: " . $e->getMessage();
            return false;
        }
    }

    // Method to execute a write (INSERT, UPDATE, DELETE) query
    public function save($query, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            echo "Save error: " . $e->getMessage();
            return false;
        }
    }

    // Method to get the last inserted ID
    public function lastInsertId()
    {
        return $this->conn->lastInsertId();
    }

    // Method to begin a transaction
    public function beginTransaction()
    {
        return $this->conn->beginTransaction();
    }

    // Method to commit a transaction
    public function commit()
    {
        return $this->conn->commit();
    }

    // Method to roll back a transaction
    public function rollBack()
    {
        return $this->conn->rollBack();
    }
}
