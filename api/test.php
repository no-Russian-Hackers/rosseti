<?php
class test extends BaseClass
{
    //http://localhost/?test.get={"id":1,"password":"root","test":1}
    function get(&$result, $params)
    {
        if (isset($params->id) && isset($params->password) && isset($params->test)) {
            $id = $params->id;
            $test = $params->test;
            if (parent::verification($result, $id, $params->password)) {
                try {
                    if (count($data = DB::prepare("SELECT test.code,testperson.module FROM test INNER JOIN testperson ON test.id=testperson.test WHERE testperson.person=? AND test.id=? AND testperson.complete=0", "ii", $id, $test)) == 1) {
                        $code = $data[0][0];
                        $dir = $_SERVER['DOCUMENT_ROOT'] . '/tests/' . substr($code, 0, 2) . "/" . substr($code, 2, 2) . "/" . substr($code, 4, 2) . '/' . $data[0][1] . ".json";
                        if (!is_dir($dir)) {
                            $tmp = json_decode(file_get_contents($dir));
                            unset($tmp->trueAnswer);
                            $result->output = $tmp;
                            $result->action = true;
                        } else {
                            $result->error =  "File of test not found";
                            $result->errno =  205;
                        }
                    } else {
                        $result->error =  "Data not found";
                        $result->errno =  204;
                    }
                } catch (Exception $ex) {
                    $result->error = $ex->getMessage();
                    $result->errno = 100;
                }
            } else {
                $result->error =  "Data verification not valid";
                $result->errno =  203;
            }
        } else {
            $result->error =  "Params not found";
            $result->errno =  202;
        }
    }
    //http://localhost/?test.check={"id":1,"password":"root","test":1,"answer":1}
    function check(&$result, $params)
    {
        if (isset($params->id) && isset($params->password) && isset($params->test) && isset($params->answer)) {
            $id = $params->id;
            $test = $params->test;
            $answer = $params->answer;
            if (parent::verification($result, $id, $params->password)) {
                try {
                    if (count($data = DB::prepare("SELECT test.code,testperson.module,testperson.count,test.count, FROM test INNER JOIN testperson ON test.id=testperson.test WHERE testperson.person=? AND test.id=? AND testperson.complete=0", "ii", $id, $test)) == 1) {
                        $code = $data[0][0];
                        $index = $data[0][1];
                        $dir = $_SERVER['DOCUMENT_ROOT'] . '/tests/' . substr($code, 0, 2) . "/" . substr($code, 2, 2) . "/" . substr($code, 4, 2) . '/' . $index;
                        if (!is_dir($dir)) {
                            $input = json_decode(file_get_contents($dir . ".json"));
                            if ($input->practice) {
                                require $dir . ".php";
                                $bool = check($answer);
                            } else {
                                $bool = $input->trueAnswer == $answer;
                            }
                            if ($index == $data[0][3])
                                DB::prepare_notreturn("UPDATE testperson SET count=?, complete=1 WHERE person=? AND test=?", "iiii", $data[0][2] + ($bool ? 1 : 0), $id, $test);
                            else
                                DB::prepare_notreturn("UPDATE testperson SET count=?, module=? WHERE person=? AND test=?", "iiii", $data[0][2] + ($bool ? 1 : 0), $index + 1, $id, $test);
                            $result->output = $bool;
                            $result->action = true;
                        } else {
                            $result->error =  "File of test not found";
                            $result->errno =  205;
                        }
                    } else {
                        $result->error =  "Data not found";
                        $result->errno =  204;
                    }
                } catch (Exception $ex) {
                    $result->error = $ex->getMessage();
                    $result->errno = 100;
                }
            } else {
                $result->error =  "Data verification not valid";
                $result->errno =  203;
            }
        } else {
            $result->error =  "Params not found";
            $result->errno =  202;
        }
    }
}
