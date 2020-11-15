<?php
class BaseClass
{
    function __construct()
    {
        require $_SERVER['DOCUMENT_ROOT'] . '/includes/DB/DB.php';
        DB::getInstance();
    }

    function __destruct()
    {
        DB::close();
    }

    function verification(&$result, $id, $password)
    {
        try {
            if (count(DB::prepare("SELECT id FROM person WHERE id=? AND password=?", "is", $id, $password)) == 1) {
                return true;
            } else {
                $result->error =  "Data not valid";
                $result->errno =  105;
            }
        } catch (Exception $ex) {
            $result->error = $ex->getMessage();
            $result->errno = 100;
        }
        return false;
    }
}
