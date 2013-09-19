<?php

$f_testid = (int) readGetVar('testid');

$f_import_document = readPostVar('import_document');
$f_import_document = stripslashes($f_import_document);
$f_strQSep = 'Question: ';
$f_strA1Sep = 'Answer: ';
$f_strA2Sep = 'Answer 2: ';
$f_strPreQSep = 'Description: ';
$f_strExplainQSep = 'Explanation: ';
$f_strCorrectASep = 'Correct: ';
$f_strPointsSep = 'Points: ';
$f_strQTypeSep = 'Type: ';
$f_strSectionSep = 'Section: ';
$i_nQuestion = 0;
$i_nAnswerCount1 = 0;
$i_nAnswerCount2 = 0;

FindNearestSep($i_nCurrNearest, $i_nCurrNearestPos);

if ($i_nCurrNearestPos > 0)
    $f_import_document = substr($f_import_document, $i_nCurrNearestPos, strlen($f_import_document) - $i_nCurrNearestPos);
while (strlen($f_import_document) > 0 || $i_nCurrNearest >= 0) {

    switch ($i_nCurrNearest) {
        case 1: $f_import_document = substr($f_import_document, strlen($f_strQSep), strlen($f_import_document) - strlen($f_strQSep));
            break;
        case 2: $f_import_document = substr($f_import_document, strlen($f_strPreQSep), strlen($f_import_document) - strlen($f_strPreQSep));
            break;
        case 3: $f_import_document = substr($f_import_document, strlen($f_strExplainQSep), strlen($f_import_document) - strlen($f_strExplainQSep));
            break;
        case 4: $f_import_document = substr($f_import_document, strlen($f_strA1Sep), strlen($f_import_document) - strlen($f_strA1Sep));
            break;
        case 8: $f_import_document = substr($f_import_document, strlen($f_strA2Sep), strlen($f_import_document) - strlen($f_strA2Sep));
            break;
        case 5: $f_import_document = substr($f_import_document, strlen($f_strCorrectASep), strlen($f_import_document) - strlen($f_strCorrectASep));
            break;
        case 6: $f_import_document = substr($f_import_document, strlen($f_strPointsSep), strlen($f_import_document) - strlen($f_strPointsSep));
            break;
        case 7: $f_import_document = substr($f_import_document, strlen($f_strQTypeSep), strlen($f_import_document) - strlen($f_strQTypeSep));
            break;
        case 9: $f_import_document = substr($f_import_document, strlen($f_strSectionSep), strlen($f_import_document) - strlen($f_strSectionSep));
            break;
    }

    FindNearestSep($i_nNextNearest, $i_nNextNearestPos);

    if ($i_nNextNearestPos >= 0) {

        $i_strData = substr($f_import_document, 0, $i_nNextNearestPos);
        $f_import_document = substr($f_import_document, $i_nNextNearestPos, strlen($f_import_document) - $i_nNextNearestPos);
    } else {

        $i_strData = $f_import_document;
        $f_import_document = '';
    }

    switch ($i_nCurrNearest) {
        case 1:

            if ($i_nQuestion > 0)
                insertQuestion();
            $f_question_text = prepareText('<b>' . $i_strData . '</b>');
            $i_nAnswerCount1 = 0;
            $i_nAnswerCount2 = 0;
            $i_nQuestion++;
            break;
        case 2:

            break;
        case 3:

            break;
        case 8:

            break;
        case 4:

            $i_nAnswerCount1++;
            $i_nField = $i_nAnswerCount1;
            $f_answer_text[$i_nField] = prepareText($i_strData);
            break;
        case 5:
            ImportApplyAnswer($i_strData);
            break;
        case 6:
            $i_strData = str_replace("\n", '', $i_strData);
            $i_strData = str_replace("\r", '', $i_strData);
            $i_strData = str_replace(' ', '', $i_strData);
            $i_nData = (int) $i_strData;
            if ($i_nData < 0)
                $i_nData = 1;
            $f_question_points = $i_nData;
            break;
        case 7:
            $i_strData = str_replace("\n", '', $i_strData);
            $i_strData = str_replace("\r", '', $i_strData);
            $i_strData = str_replace(' ', '', $i_strData);
            $i_nData = (int) $i_strData;
            if ($i_nData < 0)
                $i_nData = 0;
            $i_nData = $i_nData - 1;
            if ($i_nData >= 0 && $i_nData <= 5) {
                switch ($i_nData) {
                    case 1: $f_question_type = QUESTION_TYPE_MULTIPLEANSWER;
                    case 4: $f_question_type = QUESTION_TYPE_FILLINTHEBLANK;
                    default: $f_question_type = QUESTION_TYPE_MULTIPLECHOICE;
                }
            }
            break;
        case 9:

            break;
    }
    $i_nCurrNearest = $i_nNextNearest;
    $i_nCurrNearestPos = $i_nNextNearestPos;
}
if (!empty($f_question_text))
    insertQuestion();
gotoLocation('test-manager.php' . getURLAddon('?action=editt', array('action')));

function NewValueApply($i_nAnswerType, $i_strValue) {
    global $f_answer_correct, $f_answer_percents;
    switch ($i_nAnswerType) {
        case QUESTION_TYPE_MULTIPLECHOICE:
        case QUESTION_TYPE_TRUEFALSE:
        case QUESTION_TYPE_MULTIPLEANSWER:
            if (!is_array($f_answer_correct))
                $f_answer_correct = array();
            if (!is_array($f_answer_percents))
                $f_answer_percents = array();
            $i_nValue = (int) $i_strValue;
            if (($i_nValue > 0) and ($i_nValue <= MAX_ANSWER_COUNT)) {
                $f_answer_correct[$i_nValue] = 'checked';
                $f_answer_percents[$i_nValue] = '100';
            }
            break;
    }
}

function ImportApplyAnswerType0123($i_nAnswerType, $i_strString) {
    $i_nValue1 = 0;
    $i_strTmp = '';
    for ($i = 0; $i < strlen($i_strString); $i++) {
        switch ($i_strString{$i}) {
            case '0':
            case '1':
            case '2':
            case '3':
            case '4':
            case '5':
            case '6':
            case '7':
            case '8':
            case '9':
                $i_strTmp .= $i_strString{$i};
                break;
            case '+':
            case ',':
            case ';':
                NewValueApply($i_nAnswerType, $i_strTmp);
                $i_strTmp = '';
                break;
            case '-':
                $i_nValue1 = (int) $i_strTmp;
                $i_strTmp = '';
                break;
        }
    }
    NewValueApply($i_nAnswerType, $i_strTmp);
    $i_strTmp = '';
}

function ImportApplyAnswer($i_strString) {
    global $f_question_type;
    $i_nType1Found = strpos($i_strString, '+');
    $i_nType2Found = strpos($i_strString, ',');
    $i_strString = str_replace('-', '-', $i_strString);
    $i_nType3Found = strpos($i_strString, '-');
    if ($i_nType3Found > 0) {
        
    } else if ($i_nType2Found > 0) {
        
    } else if ($i_nType1Found > 0) {

        $f_question_type = QUESTION_TYPE_MULTIPLEANSWER;
        ImportApplyAnswerType0123(QUESTION_TYPE_MULTIPLEANSWER, $i_strString);
    } else {

        $f_question_type = QUESTION_TYPE_MULTIPLECHOICE;
        ImportApplyAnswerType0123(QUESTION_TYPE_MULTIPLECHOICE, $i_strString);
    }
}

function prepareText($i_text) {
    global $g_db;
    return $g_db->qstr(convertTextAreaHTML(true, rtrim(ltrim($i_text))), 0);
}

function insertQuestion() {
    global $g_db, $f_question_time_donotuse, $f_question_time, $f_answercount, $f_subjectid, $f_questionid, $f_question_type, $f_question_text, $f_answer_correct, $f_answer_percents, $f_answer_text, $f_question_points, $i_nAnswerCount1, $f_testid, $sql_link, $DOCUMENT_PAGES, $srv_settings;

    $f_question_time_donotuse = true;
    $f_question_time = 0;
    $f_answercount = $i_nAnswerCount1;
    if (!isset($f_question_type))
        $f_question_type = QUESTION_TYPE_MULTIPLECHOICE;
    switch ($f_question_type) {
        case QUESTION_TYPE_MULTIPLECHOICE:
        case QUESTION_TYPE_MULTIPLEANSWER:
            for ($i = 1; $i <= $f_answercount; $i++) {
                if (!isset($f_answer_correct[$i]))
                    $f_answer_correct[$i] = '';
                if (!isset($f_answer_percents[$i]))
                    $f_answer_percents[$i] = '';
            }
            break;
    }
    if (!isset($f_question_points))
        $f_question_points = 1;

    $f_subjectid = 0;
    $i_rSet1 = $g_db->Execute("SELECT subjectid FROM " . $srv_settings['table_prefix'] . "tests WHERE testid=" . $f_testid);
    if (!$i_rSet1) {
        showDBError(__FILE__, 1);
    } else {
        if (!$i_rSet1->EOF)
            $f_subjectid = (int) $i_rSet1->fields["subjectid"];
        $i_rSet1->Close();
    }

    if ($g_db->Execute("INSERT INTO " . $srv_settings['table_prefix'] . "questions (subjectid) VALUES(" . $f_subjectid . ")") === false)
        showDBError(__FILE__, 2);
    $f_questionid = (int) $g_db->Insert_ID($sql_link);

    createQuestionLink($f_testid, $f_questionid);

    include($DOCUMENT_PAGES . "edit_questions-3-int.inc.php");
    unset($f_question_time_donotuse);
    unset($f_question_time);
    unset($f_answercount);
    unset($f_subjectid);
    unset($f_questionid);
    unset($f_question_type);
    unset($f_question_text);
    if (is_array($f_answer_correct)) {
        foreach ($f_answer_correct as $key => $val)
            unset($f_answer_correct[$key]);
    }
    unset($f_answer_correct);
    if (is_array($f_answer_percents)) {
        foreach ($f_answer_percents as $key => $val)
            unset($f_answer_percents[$key]);
    }
    unset($f_answer_percents);
    foreach ($f_answer_text as $key => $val)
        unset($f_answer_text[$key]);
    unset($f_answer_text);
    unset($f_question_points);
}

function FindNearestSep(&$i_nNearest, &$i_nNearestPos) {
    global $f_import_document, $f_strQSep, $f_strA1Sep, $f_strA2Sep, $f_strPreQSep, $f_strExplainQSep, $f_strCorrectASep, $f_strPointsSep, $f_strQTypeSep, $f_strSectionSep;

    $i_nQSepPos = strpos($f_import_document, $f_strQSep);
    $i_nPreQSep = strpos($f_import_document, $f_strPreQSep);
    $i_nExplainQSep = strpos($f_import_document, $f_strExplainQSep);
    $i_nA1SepPos = strpos($f_import_document, $f_strA1Sep);
    $i_nA2SepPos = strpos($f_import_document, $f_strA2Sep);
    $i_nCorrectASep = strpos($f_import_document, $f_strCorrectASep);
    $i_nPointsSep = strpos($f_import_document, $f_strPointsSep);
    $i_nQTypeSep = strpos($f_import_document, $f_strQTypeSep);
    $i_nSectionSep = strpos($f_import_document, $f_strSectionSep);

    $i_nNearestTmp = -1;
    $i_nNearestPosTmp = MAX_UNSIGNED_INT;
    if (($i_nQSepPos !== false) && ($i_nNearestPosTmp > $i_nQSepPos)) {
        $i_nNearestPosTmp = $i_nQSepPos;
        $i_nNearestTmp = 1;
    }
    if (($i_nPreQSep !== false) && ($i_nNearestPosTmp > $i_nPreQSep)) {
        $i_nNearestPosTmp = $i_nPreQSep;
        $i_nNearestTmp = 2;
    }
    if (($i_nExplainQSep !== false) && ($i_nNearestPosTmp > $i_nExplainQSep)) {
        $i_nNearestPosTmp = $i_nExplainQSep;
        $i_nNearestTmp = 3;
    }
    if (($i_nA1SepPos !== false) && ($i_nNearestPosTmp > $i_nA1SepPos)) {
        $i_nNearestPosTmp = $i_nA1SepPos;
        $i_nNearestTmp = 4;
    }
    if (($i_nA2SepPos !== false) && ($i_nNearestPosTmp > $i_nA2SepPos)) {
        $i_nNearestPosTmp = $i_nA2SepPos;
        $i_nNearestTmp = 8;
    }
    if (($i_nCorrectASep !== false) && ($i_nNearestPosTmp > $i_nCorrectASep)) {
        $i_nNearestPosTmp = $i_nCorrectASep;
        $i_nNearestTmp = 5;
    }
    if (($i_nPointsSep !== false) && ($i_nNearestPosTmp > $i_nPointsSep)) {
        $i_nNearestPosTmp = $i_nPointsSep;
        $i_nNearestTmp = 6;
    }
    if (($i_nQTypeSep !== false) && ($i_nNearestPosTmp > $i_nQTypeSep)) {
        $i_nNearestPosTmp = $i_nQTypeSep;
        $i_nNearestTmp = 7;
    }
    if (($i_nSectionSep !== false) && ($i_nNearestPosTmp > $i_nSectionSep)) {
        $i_nNearestPosTmp = $i_nSectionSep;
        $i_nNearestTmp = 9;
    }
    if (($i_nNearestTmp == -1))
        $i_nNearestPosTmp = -1;
    $i_nNearestPos = $i_nNearestPosTmp;
    $i_nNearest = $i_nNearestTmp;
}

?>
