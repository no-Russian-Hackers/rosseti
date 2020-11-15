<?php
header('Content-type: application/json; charset=UTF-8');
if (count($_REQUEST) > 0) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/API/main.php';
    foreach ($_REQUEST as $name => $params) {
        echo (new APIMain($name, $params))->callClass();
        break;
    }
} else {
    echo json_encode(array("action" => false, "error" => "No request", "errno" => 504));
}


class sign extends BaseClass
{
    private static function createUnitsOutJSON($param)
    {
        $input = array();
        foreach ($param as $item)
            $input[] = array("id" => $item[0], "nameUnit" => $item[1], "nameTest" => $item[2]);
        return $input;
    }
    //http://localhost/?home.get={"id":1,"password":"root"}
    function check(&$result, $params)
    {
        if (isset($params->id) && isset($params->password)) {
            $id = $params->id;
            if (parent::verification($result, $id, $params->password)) {
                try {
                    if (count($units = DB::prepare("SELECT test.id, unit.name, test.name  FROM unit INNER JOIN unitperson ON unit.id=unitperson.unit INNER JOIN test ON unit.id=test.unit INNER JOIN testperson ON test.id=testperson.test WHERE unitperson.person=? AND testperson.complete=0", "i", $id)) > 0) {
                        $result->id = $id;
                        $result->units = self::createUnitsOutJSON($units);
                        $result->action = true;
                    } else {
                        $result->error =  "Свободных заданий нет";
                        $result->errno =  204;
                    }
                } catch (Exception $ex) {
                    $result->error = $ex->getMessage();
                    $result->errno = 100;
                }
            } else {
                $result->error =  "Введеная информация не верна";
                $result->errno =  101;
            }
        } else {
            $result->error =  "Нет параметров";
            $result->errno =  202;
        }
    }
}
