<?php
class sign extends BaseClass
{
    private static function createUnitsOutJSON($param)
    {
        $input = array();
        foreach ($param as $item) 
           $input[] = array("id" => $item[0], "nameUnit" => $item[1], "nameTest" => $item[2]);
        return $input;
    }
    //http://localhost/?sign.check={"fullname":"Куликов Денис Ильич","party":"ПИЭ-18-2","password":"root"}
    function check(&$result, $params)
    {
        if (isset($params->fullname) && isset($params->password) && isset($params->party)) {
            $fullname = $params->fullname;
            $password = $params->password;
            $party = $params->party;
            try {
                if (count(DB::prepare("SELECT id FROM person WHERE fullname=? AND party=?", "ss", $fullname, $party)) == 1) {
                    if (count($person = DB::prepare("SELECT id FROM person WHERE fullname=? AND password=?", "ss", $fullname, $password)) == 1) {
                        $id = $person[0][0];
                        if (count($units = DB::prepare("SELECT test.id, unit.name, test.name  FROM unit INNER JOIN unitperson ON unit.id=unitperson.unit INNER JOIN test ON unit.id=test.unit INNER JOIN testperson ON test.id=testperson.test WHERE unitperson.person=? AND testperson.complete=0", "i", $id)) > 0) {
                            $result->id = $id;
                            $result->units = self::createUnitsOutJSON($units);
                            $result->action = true;
                        } else {
                            $result->error =  "Data not found";
                            $result->errno =  204;
                        }
                    } else {
                        $result->error =  "Password not valid";
                        $result->errno =  102;
                    }
                } else {
                    $result->error =  "Person not found";
                    $result->errno =  101;
                }
            } catch (Exception $ex) {
                $result->error = $ex->getMessage();
                $result->errno = 100;
            }
        } else {
            $result->error =  "Params not found";
            $result->errno =  202;
        }
    }
}
