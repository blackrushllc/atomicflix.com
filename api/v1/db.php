<?php
/**
 * Simple .env file parser
 */
function loadEnv($path) {
    if (!file_exists($path)) {
        return false;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
    return true;
}

// Load .env from project root
loadEnv(__DIR__ . '/../../.env');

/**
 * Get a database connection using PDO
 */
function getDbConnection() {
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $db   = $_ENV['DB_NAME'] ?? 'atomicflix';
    $user = $_ENV['DB_USER'] ?? 'root';
    $pass = $_ENV['DB_PASS'] ?? '';
    $port = $_ENV['DB_PORT'] ?? '3306';
    $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port;";
    
    // Check for DB_SSL_CA in env, otherwise use default relative path
    $caPathEnv = $_ENV['DB_SSL_CA'] ?? (__DIR__ . '/../../ca-certificate.crt');
    $caPath = realpath($caPathEnv);

    if (!$caPath || !is_readable($caPath)) {
        throw new \PDOException("SSL CA certificate not found or not readable at: " . $caPathEnv);
    }

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::MYSQL_ATTR_SSL_CA       => $caPath,
    ];

    try {
        return new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        // In a real app, you'd log this and show a generic error
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
}
?>
