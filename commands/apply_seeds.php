<?php

// Before start be sure which auth plugin MySQL used now!
// ALTER USER 'root' IDENTIFIED WITH mysql_native_password BY 'fOgtNImUFWT00gqw';
// or resolve this in my.cnf

const ENV_FILE_PATH   = __DIR__ .'/../.env';
const SEEDS_PATH      = __DIR__ . '/../seeds';
const ENV_PARSER_PATH = __DIR__ . '/../app/Core/DotEnv.php';
const DB_TYPE         = 'mysql';

function getSeedsFiles() {
    return array_diff(scandir(SEEDS_PATH), ['.', '..']);
}

function getEnvFile() {
    require_once ENV_PARSER_PATH;
    return new \Core\DotEnv(ENV_FILE_PATH);
}

function getPdoConnection() {
    $env = getEnvFile();
    $dsn = DB_TYPE . ":dbname={$env->get('DB_NAME')};host={$env->get('DB_HOST')}:{$env->get('DB_PORT')}";
    return new PDO($dsn, $env->get('DB_USERNAME'), $env->get('DB_PASSWORD'));
}

function applySeeds() {
    $pdo = getPdoConnection();

    if (isset($_SERVER['argv'][1])) {
        $files = [$_SERVER['argv'][1] . '.php'];
    } else {
        $files = getSeedsFiles();
    }

    foreach ($files as $seedsFileName) {
        $data = require SEEDS_PATH . '/' . $seedsFileName;
        $tableName = str_replace('.php', '', $seedsFileName);

        foreach ($data as $row) {
            $keys = array_keys($row);
            $values = array_values($row);

            $keysRow = implode(', ', $keys);
            $valuesRow = '\'' . implode('\', \'', $values) . '\'';

            $sql = "INSERT INTO {$tableName} ({$keysRow}) VALUES ({$valuesRow})";
            $sth = $pdo->prepare($sql);
            $sth->execute();
            $error = $sth->errorInfo();

            if (!empty($error[2])) {
                echo 'Error: ' . $error[2] . "\n";
            }
        }
    }
}

applySeeds();
