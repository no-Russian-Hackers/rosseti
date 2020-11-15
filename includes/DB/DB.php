<?php
class DB
{
    protected static $mysqli;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public static function getInstance()
    {
        if (is_null(self::$mysqli)) {
            require_once __DIR__ . '/configDB.php';
            $mysqli = new mysqli($host, $login, $password, $dbname);
            if ($mysqli->connect_error) {
                die("$mysqli->connect_errno: $mysqli->connect_error");
            } else {
                self::$mysqli = $mysqli;
            }
            self::$mysqli->query("SET NAMES utf8");
        }
        return self::$mysqli;
    }
    public static function query($sql)
    {
        return self::fetch(self::$mysqli->query($sql));
    }
    public static function prepare($sql, $types, ...$gen)
    {
        if (!$stmt = self::$mysqli->prepare($sql)) {
            print "Ошибка подготовки запроса\n";
        } else {
            $stmt->bind_param($types, ...$gen);
            $stmt->execute();
            return self::fetch($stmt->get_result());
        }
        $stmt->close();
    }
    public static function prepare_notreturn($sql, $types, ...$gen)
    {
        if (!$stmt = self::$mysqli->prepare($sql)) {
            print "Ошибка подготовки запроса\n";
        } else {
            $stmt->bind_param($types, ...$gen);
            $stmt->execute();
            return $stmt->get_result();
        }
        $stmt->close();
    }
    public static function fetch($result)
    {
        return $result->fetch_all();
    }
    public static function close()
    {
        self::$mysqli->close();
    }

    public static function lastID()
    {
        return mysqli_insert_id(self::$mysqli);
    }
}
