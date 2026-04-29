<?php
/**
 * Database configuration and connection helper.
 *
 * Edit the constants below to match your XAMPP / MySQL setup.
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'ecommerce');
define('DB_USER', 'root');
define('DB_PASS', '');          // XAMPP default is empty
define('DB_CHARSET', 'utf8mb4');

/**
 * Returns a PDO instance (singleton per request).
 *
 * @return PDO
 * @throws RuntimeException on connection failure
 */
function get_db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_HOST,
            DB_NAME,
            DB_CHARSET
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // In production you would log the real message and show a generic error.
            throw new RuntimeException('Database connection failed: ' . $e->getMessage());
        }
    }

    return $pdo;
}
