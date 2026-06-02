<?php
// Database configuration for Ekspedisi API
// Edit these values to match your Hostinger database credentials

// Example Hostinger full names are often prefixed with account id, e.g. u568526216_notesfinances
// Set DB_HOST to 'localhost' unless Hostinger tells you otherwise.

define('DB_HOST', 'localhost');
define('DB_NAME', 'u568526216_notesfinances');
define('DB_USER', 'u568526216_ramhateu');
define('DB_PASS', 'REPLACE_WITH_DB_PASSWORD');

// change the above values before deploying

function getPDO(){
    static $pdo = null;
    if($pdo) return $pdo;
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    try{
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    }catch(PDOException $e){
        http_response_code(500);
        echo json_encode(['error' => 'DB connection failed', 'detail' => $e->getMessage()]);
        exit;
    }
}
