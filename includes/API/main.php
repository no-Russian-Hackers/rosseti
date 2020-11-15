<?php
class APIMain
{
    private $name;
    private $params;

    function __construct($name, $params)
    {
        $this->params = stripcslashes($params);
        $this->name = explode('_', $name);
    }

    static function getClassByName($fileName)
    {
        try {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/API/baseClass.php';
            require_once $_SERVER['DOCUMENT_ROOT'] . '/api/' . $fileName . '.php';
            return new $fileName();
        } catch (Exception $ex) {
            $result->error = $ex->getMessage();
            $result->errno = 300;
        }
    }

    function createDefaultJson()
    {
        $outObj = new stdClass();
        $outObj->action = false;
        return $outObj;
    }

    function callClass()
    {
        $result = $this->createDefaultJson();
        $fileName = strtolower($this->name[0]);
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/api/' . $fileName . '.php')) {
            $className = APIMain::getClassByName($fileName);
            try {
                $functionName = $this->name[1];
                (new ReflectionClass($fileName))->getMethod($functionName);
                $jsonParams = json_decode($this->params);
                if ($jsonParams) {
                    if (isset($jsonParams->binary)) {
                        return $className->$functionName($jsonParams);
                    } else {
                        $className->$functionName($result, $jsonParams);
                    }
                } else {
                    $result->error = 'Error given params';
                    $result->errno = 301;
                }
            } catch (Exception $ex) {
                $result->error = $ex->getMessage();
                $result->errno = 300;
            }
        } else {
            $result->error = 'File not found';
            $result->errno = 404;
        }
        return json_encode($result);
    }
}
