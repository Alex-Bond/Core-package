<?php

/**
 * @version 1.1
 * @author Alex Bond
 * @copyright 2010
 * @link http://kkzcore.pp.ua
 */

//$rand = mt_rand(0, date("U"));
//$checksum = md5($rand . 'd8d24b0677036b0bf675aaef90016e55');
$kkzapiurl = "http://11.1.1.245/api/?";

class kkzapi
{
   	function login($key)
    {
        global $kkzapiurl;
        $url = $kkzapiurl . 'do=login&key=' . $key;
        $xml = simplexml_load_file($url);
        if (strlen($xml->user->id) == 0) {
            return false;
        } else {
            $mass['userid'] = (int)$xml->user->id;
            $mass['username'] = (string )$xml->user->login;
            $mass['firstname'] = (string )$xml->user->name;
            $mass['lastname'] = (string )$xml->user->lastname;
            $mass['middlename'] = (string )$xml->user->fathername;
            $mass['usergroup'] = (int)$xml->user->group;
            $mass['usercom'] = (int)$xml->user->commission;
            $mass['access'] = (int)$xml->user->access;
            return $mass;
        }
    }
    function getuser($id)
    {
        global $kkzapiurl, $adodb;
        $url = $kkzapiurl . 'do=user2&id=' . $id;
        $xml = simplexml_load_file($url);
        if (strlen($xml->user->name) == 0) {
            return false;
        } else {
            $mass['userid'] = (int)$xml->user->id;
            $mass['username'] = (string )$xml->user->login;
            $mass['firstname'] = (string )$xml->user->name;
            $mass['lastname'] = (string )$xml->user->lastname;
            $mass['middlename'] = (string )$xml->user->fathername;
            $mass['usercom'] = (int)$xml->user->commission;
            $mass['usergroup'] = (int)$xml->user->group;
            $mass['access'] = (int)$xml->user->access;
            if($adodb->debug){
            print_r($xml);
            }
            return $mass;
        }
    }
    function getgroup($id)
    {
        global $kkzapiurl;
        $url = $kkzapiurl . 'do=group&id=' . $id;
        $xml = simplexml_load_file($url);
        $output[id] = $xml->group->id;
        $output[name] = $xml->group->name;
        return $output;
    }
    function getgroups()
    {
        global $kkzapiurl;
        $url = $kkzapiurl . 'do=groups&id=' . $id;
        $xml = simplexml_load_file($url);
        $i = 0;
        foreach ($xml->group as $group) {
            $output[$i][id] = $group->id;
            $output[$i][name] = $group->name;
            $i++;
        }
        return $output;
    }
    function getgroupswithstudents()
    {
        global $kkzapiurl;
        $url = $kkzapiurl . 'do=user2&id=' . $id;
        $xml = simplexml_load_file($url);
        $i = 0;
        foreach ($xml->group as $group) {
            $output[$i][id] = $group->id;
            $output[$i][name] = $group->name;
            $i1 = 0;
            $url1 = $kkzapiurl . 'do=users2&id=' . $group->id;
            $xml1 = simplexml_load_file($url1);
            foreach ($xml1->user as $user) {
                $output[$i][users][$i1][id] = $user->id;
                $output[$i][users][$i1][name] = $user->name;
                $output[$i][users][$i1][lastname] = $user->lastname;
                $output[$i][users][$i1][fathername] = $user->fathername;
                $output[$i][users][$i1][group] = $user->group;
                $output[$i][users][$i1][lastvisit] = $user->lastvisit;
                $output[$i][users][$i1][access] = $user->access;
                $i1++;
            }
            $i++;
        }
        return $output;
    }
    function getstudentsingroup($id)
    {
        global $kkzapiurl;
        $url = $kkzapiurl . 'do=users2&id=' . $id;
        $xml = simplexml_load_file($url);
        $i = 0;
        foreach ($xml->user as $user) {
            $output[$i][id] = $user->id;
            $output[$i][name] = $user->name;
            $output[$i][lastname] = $user->lastname;
            $output[$i][fathername] = $user->fathername;
            $output[$i][group] = $user->group;
            $output[$i][lastvisit] = $user->lastvisit;
            $output[$i][access] = $user->access;
            $i++;
        }
        return $output;
    }
    function getcommissions()
    {
        global $kkzapiurl;
        $url = $kkzapiurl . 'do=commissions';
        $xml = simplexml_load_file($url);
        $i = 0;
        foreach ($xml->commission as $commission) {
            $output[$i][id] = $commission->id[0];
            $output[$i][name] = $commission->name[0];
            $i++;
        }
        return $output;
    }
    function getcourses_id($id)
    {
        global $kkzapiurl;
        $url = $kkzapiurl . 'do=courses_id&id=' . $id;
        $xml = simplexml_load_file($url);
        $i = 0;
        foreach ($xml->course as $course) {
            $output[$i][id] = $course->id[0];
            $output[$i][name] = $course->name[0];
            $i++;
        }
        return $output;
    }
    function getcourse($id)
    {
        global $kkzapiurl;
        $url = $kkzapiurl . 'do=course_id&id=' . $id;
        $xml = simplexml_load_file($url);
        $output[id] = $xml->course->id;
        $output[name] = $xml->course->name;
        return $output;
    }
}


?>