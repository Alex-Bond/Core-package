<?php

/**
 * @version 1.1
 * @author Alex Bond
 * @copyright 2010
 * @link http://kkzcore.pp.ua
 */

class core_api
{
	private $url;
	
	function __construct($url){
		$this->url = $url;
	}
	
   	function login($key)
    {
        $url = $this->url . 'do=login&key=' . $key;
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
    //Для тестов без core
    /*	function login($key)
    {
        //global $kkzapiurl;
        //$url = $kkzapiurl . 'do=login&key=' . $key;
        //$xml = simplexml_load_file($url);
        //if (strlen($xml->user->id) == 0) {
        //    return false;
        //} else {
            $mass['userid'] = 1;
            $mass['username'] = "root";
            $mass['firstname'] = "Alex";
            $mass['lastname'] = "Bond";
            $mass['middlename'] = "Bond2";
            $mass['usergroup'] = "";
            $mass['usercom'] = "";
            $mass['access'] = 99;
			return $mass;
        //}
    }*/
    function getuser($id)
    {
        $url = $this->url . 'do=user2&id=' . $id;
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
            return $mass;
        }
    }
    function getgroup($id)
    {
        $url = $this->url . 'do=group&id=' . $id;
        $xml = simplexml_load_file($url);
        $output['id'] = $xml->group->id;
        $output['name'] = $xml->group->name;
        return $output;
    }
    function getgroups()
    {
        $url = $this->url . 'do=groups';
        $xml = simplexml_load_file($url);
        $i = 0;
        foreach ($xml->group as $group) {
            $output[$i]['id'] = $group->id;
            $output[$i]['name'] = $group->name;
            $i++;
        }
        return $output;
    }
    function getgroupswithstudents()
    {
        $url = $this->url . 'do=groups';
        $xml = simplexml_load_file($url);
        $i = 0;
        foreach ($xml->group as $group) {
            $output[$i]['id'] = $group->id;
            $output[$i]['name'] = $group->name;
            $i1 = 0;
            $url1 = $this->url . 'do=users2&id=' . $group->id;
            $xml1 = simplexml_load_file($url1);
            foreach ($xml1->user as $user) {
                $output[$i]['users'][$i1]['id'] = $user->id;
                $output[$i]['users'][$i1]['name'] = $user->name;
                $output[$i]['users'][$i1]['lastname'] = $user->lastname;
                $output[$i]['users'][$i1]['fathername'] = $user->fathername;
                $output[$i]['users'][$i1]['group'] = $user->group;
                $output[$i]['users'][$i1]['lastvisit'] = $user->lastvisit;
                $output[$i]['users'][$i1]['access'] = $user->access;
                $i1++;
            }
            $i++;
        }
        return $output;
    }
    function getstudentsingroup($id)
    {
        $url = $this->url . 'do=users2&id=' . $id;
        $xml = simplexml_load_file($url);
        $i = 0;
        foreach ($xml->user as $user) {
            $output[$i]['id'] = $user->id;
            $output[$i]['name'] = $user->name;
            $output[$i]['lastname'] = $user->lastname;
            $output[$i]['fathername'] = $user->fathername;
            $output[$i]['group'] = $user->group;
            $output[$i]['lastvisit'] = $user->lastvisit;
            $output[$i]['access'] = $user->access;
            $i++;
        }
        return $output;
    }
    function getcommissions()
    {
        $url = $this->url . 'do=commissions';
        $xml = simplexml_load_file($url);
        $i = 0;
        foreach ($xml->commission as $commission) {
            $output[$i]['id'] = $commission->id[0];
            $output[$i]['name'] = $commission->name[0];
            $i++;
        }
        return $output;
    }
	function getcommission($id)
    {
        $url = $this->url . 'do=commission_id&id=' . $id;
        $xml = simplexml_load_file($url);
        $output['id'] = $xml->commission->id;
        $output['name'] = $xml->commission->name;
        return $output;
    }
    function getcourses_id($id)
    {
        $url = $this->url . 'do=courses_id&id=' . $id;
        $xml = simplexml_load_file($url);
        $i = 0;  
        foreach ($xml->course as $course) {
        	$id = $course->id[0];
            $output[''.$id]['id'] = $course->id[0];
            $output[''.$id]['name'] = $course->name[0];
            $i++;
        }
        return $output;
    }
	function getcourses_all()
    {
        $url = $this->url . 'do=courses_all';
        $xml = simplexml_load_file($url);
        $i = 0;  
        foreach ($xml->course as $course) {
        	$id = $course->id[0];
            $output[''.$id]['id'] = $course->id[0];
            $output[''.$id]['name'] = $course->name[0];
            $i++;
        }
        return $output;
    }
    function getcourse($id)
    {
        $url = $this->url . 'do=course_id&id=' . $id;
        $xml = simplexml_load_file($url);
        $output['id'] = $xml->course->id;
        $output['name'] = $xml->course->name;
        $output['com'] = $xml->course->com;
        return $output;
    }
}


?>