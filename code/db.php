<?php
// Project 1 db file
// Referenced GitHub - ase230/module1/code/6_RESTAPI/api/database.php

declare(strict_types=1);

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: '3306';
    $name = getenv('DB_NAME') ?: 'project1';
    $user = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : 'samplepassword123'; //password can be changed as needed
    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $e) {
        // Fallback to root if default user failed to authenticate
        if (!getenv('DB_USER') && $user === 'root') {
            try {
                $user = 'root';
                $rootPassword = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '';
                $pdo = new PDO($dsn, $user, $rootPassword, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
                return $pdo;
            } catch (PDOException $fallbackEx) {
                // Ignore fallback and continue to normal database handling
            }
        }

        // Auto-create database and tables if database does not exist yet
        if ((int)$e->getCode() === 1049 || str_contains($e->getMessage(), 'Unknown database')) {
            $serverDsn = "mysql:host={$host};port={$port};charset=utf8mb4";
            $serverPdo = new PDO($serverDsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            $serverPdo->exec("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            $serverPdo->exec("USE `{$name}`;");
            $schemaFile = __DIR__ . '/schema.sql';
            if (file_exists($schemaFile)) {
                $sql = file_get_contents($schemaFile);
                $serverPdo->exec($sql);
            }
            $pdo = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } else {
            throw $e;
        }
    }
    return $pdo;
}
?>