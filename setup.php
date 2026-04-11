<?php

use App\Database\Database;
use Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/Database/Database.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$db = new Database();
$db->createUserTable();
$db->createUserDataTable();
$db->createTrainingTable();
$db->createExercisesTable();
$db->createExercisesDataTable();
$db->createTrainingHistoryTable();
$db->createExercisesHistoryTable();
$db->createExercisesHistoryDataTable();
