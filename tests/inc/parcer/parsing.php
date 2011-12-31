<?php

class pars {

    var $questions = array();
    var $now = array();
    var $sym;

    function __construct($out) {
        foreach ($out as $val) {
            $this->newLine($val);
        }
    }

    private function newLine($line) {
        $val = $line;
        if ($this->find($val, '@') OR $this->find($val, '!') OR $this->find($val, '&') OR $this->find($val, '/')) {
            if (count($this->now) > 0) {
                if ($this->now['type'] == '@') {
                    if ($this->now['a_count'] > 1)
                        $this->now['type'] = '@@';
                    $this->questions[] = $this->now;
                    $this->now = array();
                } else {
                    $this->questions[] = $this->now;
                    $this->now = array();
                }
                $this->now['question'] = substr($val, 1);
                $this->now['type'] = $this->sym;
            } else {
                $this->now['question'] = substr($val, 1);
                $this->now['type'] = $this->sym;
            }
        } else {
            if ($this->now['type'] == '!') {
                if ($this->check($val, '+')) {
                    ++$this->now['a_count'];
                    $this->now['answers'][] = array(
                        'correct' => TRUE,
                        'answer' => substr($val, 1)
                    );
                } else {
                    if (strlen($val) != 0) {
                        ++$this->now['a_count'];
                        $this->now['answers'][] = array(
                            'correct' => TRUE,
                            'answer' => $val
                        );
                    }
                }
            } else {
                if (substr($val, 0, 1) == '+') {
                    ++$this->now['a_count'];
                    $this->now['answers'][] = array(
                        'correct' => TRUE,
                        'answer' => substr($val, 1)
                    );
                } else {
                    if (strlen($val) != 0) {
                        $this->now['answers'][] = array(
                            'correct' => FALSE,
                            'answer' => $val
                        );
                    }
                }
            }
        }
    }

    function find($text, $sym) {
        if (strrpos(substr($text, 0, 1), $sym) === FALSE) {
            return FALSE;
        } else {
            $this->sym = $sym;
            return TRUE;
        }
    }

    function check($text, $sym) {
        if (strrpos(substr($text, 0, 1), $sym) === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function out() {
        return $this->questions;
    }

}