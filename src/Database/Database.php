<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use PDOException;

class Database
{
    private $pdo;

    public function __construct()
    {
        $dbhost = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $dbuser = $_ENV['DB_USER'];
        $dbpass = $_ENV['DB_PASS'];
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$dbhost;dbname=$dbname;charset=$charset";
        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false];

        try {
            $this->pdo = new PDO($dsn, $dbuser, $dbpass, $options);
        } catch (PDOException $e) {
            die('Database error: ' . $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    public function createUserTable()
    {
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL
        )');
    }

    public function createTrainingTable()
    {
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS training (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            user_id INT,
            CONSTRAINT fk_user_training
            FOREIGN KEY (user_id) 
            REFERENCES users(id) 
            ON DELETE CASCADE
        )');
    }

    public function createExercisesTable()
    {
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS exercises (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            training_id INT,
            CONSTRAINT fk_training_exercises
            FOREIGN KEY (training_id) 
            REFERENCES training(id) 
            ON DELETE CASCADE
        )');
    }

    public function createExercisesDataTable()
    {
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS exercises_data (
            id INT AUTO_INCREMENT PRIMARY KEY,
            sets INT NOT NULL,
            weight INT,
            reps INT NOT NULL,
            created_at DATE NOT NULL,
            exercise_id INT,
            CONSTRAINT fk_exercises_data
            FOREIGN KEY (exercise_id) 
            REFERENCES exercises(id) 
            ON DELETE CASCADE
        )');
    }
}
