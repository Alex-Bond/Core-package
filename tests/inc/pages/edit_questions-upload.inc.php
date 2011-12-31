<?php
require_once ($DOCUMENT_INC . "top.inc.php");
$f_testid = (int) readGetVar('testid');
$i_items = array();
if ($f_testid) {
    array_push($i_items, array(0 => '<a class=bar2 href="test-manager.php">' . $lngstr['page_header_edittests'] .
        '</a>', 0));
    array_push($i_items, array(0 => '<a class=bar2 href="grades.php">' . $lngstr['page_header_grades'] .
        '</a>', 0));
    array_push($i_items, array(0 =>
        '<a class=bar2 href="test-manager.php?action=editt&testid=' . $f_testid . '">' .
        $lngstr['page_header_test_questions'] . '</a>', 0));
} else {
    array_push($i_items, array(0 => '<a class=bar2 href="question-bank.php">' . $lngstr['page_header_questionbank'] .
        '</a>', 0));
    // array_push($i_items, array(0 => '<a class=bar2 href="subjects.php">' . $lngstr['page_header_subjects'] .
    //   '</a>', 0));
}
writePanel2($i_items);
echo '<h2>' . $lngstr['page_header_edit_question'] . '</h2>';
writeErrorsWarningsBar();

function addQuestion($question, $type, $subj) {
    global $g_db;
    $quest = $g_db->Execute("INSERT INTO kkztquestions (`subjectid`,`question_text`,`question_type`) VALUES (" . $g_db->qstr($subj) . "," . $g_db->qstr($question) . "," . $g_db->qstr($type) . ")");
    if (!$quest) {
        showDBError(__file__, 1);
    } else {
        return $g_db->Insert_ID();
    }
}

function addAnswer($answer, $question, $perc, $correct) {
    global $g_db;
    $quest = $g_db->Execute("SELECT max(`answerid`) as one  FROM `kkztanswers` WHERE `questionid` =" . $g_db->qstr($question));
    if (!$quest) {
        showDBError(__file__, 1);
    } else {
        $answerid = $quest->fields['one'] + 1;

        $answer = $g_db->Execute("INSERT INTO kkztanswers (`answerid`,`questionid`,`answer_text`,`answer_correct`,`answer_percents`) VALUES (" . $g_db->qstr($answerid) . "," . $g_db->qstr($question) . "," . $g_db->qstr($answer) . "," . $g_db->qstr($correct) . "," . $g_db->qstr($perc) . ")");
        if (!$answer) {
            showDBError(__file__, 1);
        } else {
            return true;
        }
    }
}

function addQuestionToTest($question, $test) {
    global $g_db;
    $quest = $g_db->Execute("SELECT max(`test_questionid`) as one  FROM `kkzttests_questions` WHERE `testid` =" . $g_db->qstr($test));
    if (!$quest) {
        showDBError(__file__, 1);
    } else {
        $test_questionid = $quest->fields['one'] + 1;

        $add = $g_db->Execute("INSERT INTO kkzttests_questions (`test_questionid`,`testid`,`questionid`) VALUES (" . $g_db->qstr($test_questionid) . "," . $g_db->qstr($test) . "," . $g_db->qstr($question) . ")");
        if (!$add) {
            showDBError(__file__, 1);
        } else {
            return true;
        }
    }
}

$quest = $g_db->Execute("SELECT * FROM `" . $srv_settings['table_prefix'] . "tests` WHERE `testid`=" . $g_db->qstr($f_testid));
if (!$quest) {
    showDBError(__file__, 1);
} else {
    $f_subjectid = $quest->fields['subjectid'];
}
?>
<center>
    Теперь Вы можете загрузить свои вопросы из документа Word. Предупреждаем что загрузка возможна только из Word 98-2003.<br /><br />
</center>
<p>Коды для документов:</p>
<p><strong>@</strong> - Множественный выбор или много ответов (выбирается автоматически в зависимости от колличества правильный ответов)<br />
    <strong>!</strong> - Прямой ввод<br />
    <strong>&amp;</strong> - Истина/ложь<br />
    <strong>/</strong> - Эссе</p>
<p>Правильные овтеты помечаются заком <strong>+</strong></p>
<p><strong><em>ПРОБЕЛЫ ПОСЛЕ КЛЮЧЕВЫХ ЗНАКОВ СТАТИЛЬ НЕ НАДО.</em></strong></p>
<p>При прямом вводе + не обязательный, а при эссе вариантов ответа быть не должно.<br />
    Оформление из документа не сохранится.<br />
    Каждая новая строка - вопрос или ответ.<br />
    Пустые строки игнорируются.
<p>&nbsp;</p>
<p>Пример:</p>
<p>@Очень хороший вопрос?<br />
    Не верный ответ 1<br />
    +Верный ответ<br />
    Не вервый ответ 2
</p>
<br />
<center>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="doc" size="50" /><br /><br />
        <input type="submit" name="upload" value="Загрузить" /> <input type="submit" value="Отменить" name="cancel" />
    </form>
</center>
<br /><br />
<?php
if (isset($_FILES['doc']) && isset($_POST['upload'])) {
    if ($_FILES['doc']['type'] == 'application/msword') {
        include_once './inc/parcer/doc.php';
        include_once './inc/parcer/parsing.php';
        $var = html_entity_decode(doc2text($_FILES['doc']['tmp_name']), ENT_NOQUOTES, 'UTF-8');
        $var = $var . "/END\n";
        preg_match_all("|(.*)\n|U", $var, $out, PREG_PATTERN_ORDER);
        $pars = new pars($out[1]);
        $_SESSION['doc'] = $out = $pars->out();
        echo '<p align="center">Результат обработки документа:</p>
            <form action="" method="POST">
            <table width="100%" border="0" cellspacing="0" cellpadding="5">';
        foreach ($out as $key => $item) {

            $quest = $g_db->Execute("SELECT * FROM " . $srv_settings['table_prefix'] . "questions WHERE question_text LIKE " . $g_db->qstr("%" . $item['question'] . "%") . " AND subjectid=" . $g_db->qstr($f_subjectid));
            if (!$quest) {
                showDBError(__file__, 1);
            } else {
                if ($quest->RecordCount() > 0) {
                    $input_add = '';
                    $td_add = 'bgcolor="#CC0000"';
                } else {
                    $input_add = 'checked="checked"';
                    $td_add = 'bgcolor="#CCCCCC"';
                }

                echo '<tr><td width="100%" ' . $td_add . '>
                <input name="question_on[' . $key . ']" type="checkbox" id="question_on[' . $key . ']" ' . $input_add . ' />
                    <b>' . $item['question'] . '</b> - ';
                switch ($item['type']) {
                    case '@':
                        echo 'Множинний вибір';
                        break;
                    case '@@':
                        echo 'Багато відповідей';
                        break;
                    case '!':
                        echo 'Прямий ввід';
                        break;
                    case '&':
                        echo 'Істина/помилка';
                        break;
                    case '/':
                        echo 'Есе (довільна відовідь)';
                        break;
                    default:
                        echo 'Error!';
                        break;
                }

                echo '</td></tr>';
                foreach ($item['answers'] as $key_a => $item_a) {
                    $add = ($item_a['correct'] == 1) ? '-2' : '-0';
                    echo '<tr>
            <td>&nbsp;&nbsp;<img src="/images/button-checkbox' . $add . '.gif" /> ' . $item_a['answer'] . '</td>
        </tr>';
                }
            }
        }
        ?>
        </table>
        <p align="center"><input type="submit" value="Сохранить" name="submit" /></p>
        </form>
        <?php
    } else {
        echo '<p align="center"><b><font color="#cc0000">Загруженный файл не поддерживается.</font></b>';
    }
} elseif (isset($_POST['submit'])) {
    foreach ($_POST['question_on'] as $key => $item) {
        if ($item == 'on') {
            switch ($_SESSION['doc'][$key]['type']) {
                case '@':
                    $type = 0;
                    break;
                case '@@':
                    $type = 2;
                    break;
                case '!':
                    $type = 3;
                    break;
                case '&':
                    $type = 1;
                    break;
                case '/':
                    $type = 4;
                    break;
                default:
                    echo 'Error!';
                    break;
            }
            $questionid = addQuestion($_SESSION['doc'][$key]['question'], $type, $f_subjectid);
            foreach ($_SESSION['doc'][$key]['answers'] as $a_key => $a_item) {
                if ($a_item['correct'])
                    $perc = 100 / $_SESSION['doc'][$key]['a_count'];
                else
                    $perc = 0;
                addAnswer($a_item['answer'], $questionid, $perc, $a_item['correct']);
            }
            if (addQuestionToTest($questionid, $f_testid))
                ;
        }
    }
    ob_clean();
    gotoLocation('test-manager.php?testid=' . $f_testid . '&action=editt');
    die();
}elseif (isset($_POST['cancel'])) {
    ob_clean();
    gotoLocation('test-manager.php?testid=' . $f_testid . '&action=editt');
    die();
}

require_once ($DOCUMENT_INC . "btm.inc.php");
?>
