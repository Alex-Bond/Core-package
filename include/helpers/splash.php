<?php

class SplashHelper {

    var $errors = array();
    var $infos = array();

    function addError($code, $msg) {
        $this->errors[] = array(
            'code' => $code,
            'msg' => $msg
        );
        return true;
    }

    function addInfo($msg) {
        $this->infos[] = array(
            'msg' => $msg
        );
        return true;
    }

    function isInfos() {
        if (count($this->infos) > 0) {
            return true;
        } else {
            return false;
        }
    }

    function isErrors() {
        if (count($this->errors) > 0) {
            return true;
        } else {
            return false;
        }
    }

    function printInfos($header = null, $footer = null) {
        if ($header === NULL) {
            $header = '<table width="100%" border="0" cellspacing="0" cellpadding="5"><tr><td align="center" bgcolor="#90EE90">';
        }
        if ($footer === NULL) {
            $footer = '</td></tr></table>';
        }
        foreach ($this->infos as $item) {
            echo $header . $item['msg'] . $footer;
        }
    }

    function printErrors($header = null, $footer = null) {
        if ($header === NULL) {
            $header = '<table width="100%" border="0" cellspacing="0" cellpadding="5"><tr><td align="center" bgcolor="#CC0033" style="color:#fff">';
        }
        if ($footer === NULL) {
            $footer = '</td></tr></table>';
        }
        foreach ($this->errors as $item) {
            if (strlen($item['code']) == 0 || $item['code'] == 0)
                echo $header . $item['msg'] . $footer;
            else
                echo $header . 'Помилка ' .$item['code'] . ' - ' . $item['msg'] . $footer;
        }
    }
    function printAll(){
        $this->printErrors();
        $this->printInfos();
    }

}
