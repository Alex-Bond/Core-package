<?php

/**
 * @version 1.1
 * @author Alex Bond
 * @copyright 2010
 * @link http://kkzcore.pp.ua
 */
class core_api {

    private $url;
    private $users = array();

    function __construct($url) {
        $this->url = $url;
    }

    function login($key) {
        $url = $this->url . 'do=login&key=' . $key;
        $xml = simplexml_load_file($url);
        if (strlen($xml->user->id) == 0) {
            return false;
        } else {
            $mass['userid'] = '' . $xml->user->id;
            $mass['username'] = '' . $xml->user->login;
            $mass['firstname'] = '' . $xml->user->name;
            $mass['lastname'] = '' . $xml->user->lastname;
            $mass['middlename'] = '' . $xml->user->fathername;
            $mass['usergroup'] = '' . $xml->user->group;
            $mass['usercom'] = '' . $xml->user->commission;
            $mass['access'] = '' . $xml->user->access;
            $mass['allcoms'] = '' . $xml->user->allcoms;
            return $mass;
        }
    }

    //Для тестов без core
    /* 	function login($key)
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
      }
     */
    function getuser($id) {
        if (count($this->users) == 0) {
            $this->users = (array) $this->getusers_act();
        }
        if (isset($this->users[$id])) {
            return $this->users[$id];
        } else {
            $url = $this->url . 'do=user2&id=' . $id;
            $xml = simplexml_load_file($url);
            if (strlen($xml->user->name) == 0) {
                return false;
            } else {
                $mass['userid'] = '' . $xml->user->id;
                $mass['username'] = '' . $xml->user->login;
                $mass['name'] = $mass['firstname'] = '' . $xml->user->name;
                $mass['lastname'] = '' . $xml->user->lastname;
                $mass['middlename'] = '' . $xml->user->fathername;
                $mass['usercom'] = '' . $xml->user->commission;
                $mass['group'] = $mass['usergroup'] = '' . $xml->user->group;
                $mass['access'] = '' . $xml->user->access;
                $mass['allcoms'] = '' . $xml->user->allcoms;
                return $mass;
            }
        }
    }

    function getusers_act() {
        $url = $this->url . 'do=users';
        $json = file_get_contents($url);
        $output = json_decode($json);
        return $output;
    }

    function getgroup($id) {
        $url = $this->url . 'do=group&id=' . $id;
        $xml = simplexml_load_file($url);
        $output['id'] = (int) $xml->group->id;
        $output['name'] = (string) $xml->group->name;
        return $output;
    }

    function getgroups() {
        $url = $this->url . 'do=groups';
        $xml = simplexml_load_file($url);
        foreach ($xml->group as $group) {
            $output[(int) $group->id[0]] = (string) $group->name[0];
        }
        return $output;
    }

    function getgroupswithstudents() {
        $url = $this->url . 'do=groups_users';
        $json = file_get_contents($url);
        $output = (array) json_decode($json);
        return $output;
    }

    function getstudentsingroup($id) {
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

    function getcommissions() {
        $url = $this->url . 'do=commissions';
        $xml = simplexml_load_file($url);
        $i = 0;
        foreach ($xml->commission as $commission) {
            $output[$i]['id'] = $commission->id[0];
            $output[$i]['name'] = '' . $commission->name[0];
            $i++;
        }
        return $output;
    }

    function getcommission($id) {
        $url = $this->url . 'do=commission_id&id=' . $id;
        $xml = simplexml_load_file($url);
        $output['id'] = '' . $xml->commission->id[0];
        $output['name'] = '' . $xml->commission->name[0];
        return $output;
    }

    function getcourses_id($id) {
        $url = $this->url . 'do=courses_id&id=' . $id;
        $xml = simplexml_load_file($url);
        $i = 0;
        foreach ($xml->course as $course) {
            $id = $course->id[0];
            $output['' . $id]['id'] = '' . $course->id[0];
            $output['' . $id]['name'] = '' . $course->name[0];
            $i++;
        }
        return $output;
    }

    function getcourses_all() {
        $url = $this->url . 'do=courses_all';
        $xml = simplexml_load_file($url);
        foreach ($xml->course as $course) {
            $id = (int) $course->id[0];
            $output[$id]['id'] = (int) $course->id;
            $output[$id]['name'] = (string) $course->name;
        }
        return $output;
    }

    function getcourse($id) {
        $url = $this->url . 'do=course_id&id=' . $id;
        $xml = simplexml_load_file($url);
        $output['id'] = '' . $xml->course->id;
        $output['name'] = '' . $xml->course->name;
        $output['com'] = '' . $xml->course->com;
        return $output;
    }

}

?>