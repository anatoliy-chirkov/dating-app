<?php

// Before start be sure which auth plugin MySQL used now!
// ALTER USER 'root' IDENTIFIED WITH mysql_native_password BY 'fOgtNImUFWT00gqw';
// or resolve this in my.cnf

const ENV_FILE_PATH   = __DIR__ .'/../.env';
const MIGRATIONS_PATH = __DIR__ . '/../migrations';
const ENV_PARSER_PATH = __DIR__ . '/../app/Core/DotEnv.php';
const DB_TYPE         = 'mysql';

function getMigrationsFiles() {
    return array_diff(scandir(MIGRATIONS_PATH), ['.', '..']);
}

function getEnvFile() {
    require_once ENV_PARSER_PATH;
    return new \Core\DotEnv(ENV_FILE_PATH);
}

function getPdoConnection() {
    $env = getEnvFile();
    $dsn = DB_TYPE . ":host={$env->get('DB_HOST')}:{$env->get('DB_PORT')}";
    return new PDO($dsn, $env->get('DB_USERNAME'), $env->get('DB_PASSWORD'));
}

function isDbExists(PDO $pdo) {
    $dbName = getEnvFile()->get('DB_NAME');
    $sql = "SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$dbName}'";
    $stmt = $pdo->query($sql);
    return (bool) $stmt->fetchColumn();
}

function createDb(PDO $pdo) {
    $dbName = getEnvFile()->get('DB_NAME');
    $sql = "CREATE DATABASE {$dbName}";
    $pdo->exec($sql);
}

function useDb(PDO &$pdo) {
    $dbName = getEnvFile()->get('DB_NAME');
    $sql = "USE {$dbName}";
    $pdo->exec($sql);
}

function getPdoConnectionUseDb() {
    $pdo = getPdoConnection();

    if (!isDbExists($pdo)) {
        createDb($pdo);
    }

    useDb($pdo);

    return $pdo;
}

function applyMigrations() {
    $pdo = getPdoConnectionUseDb();

    foreach (getMigrationsFiles() as $migration) {
        $sql = file_get_contents(MIGRATIONS_PATH . '/' . $migration);

        $sth = $pdo->prepare($sql);
        $sth->execute();

        $error = $sth->errorInfo();

        if (!empty($error[2])) {
            echo 'Error: ' . $error[2] . "\n";
        }
    }
}

applyMigrations();
