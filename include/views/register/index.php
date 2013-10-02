
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>ККЗ Core</title>
        <style type="text/css">
            body {
                background: url('/images/bg.jpg');
            }

            body,td,th,input,select {
                font-family: calibri, "segoe UI", arial;
            }

            .content {
                width: 100% auto;
                background-color: #ececec;
                padding: 10px;
            }
            .inputText {
                width: 400px; height: 40px; font-size: 24px; vertical-align: middle;
            }
        </style>
        <script type="text/javascript"
        src="/js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript"
        src="/js/jquery-ui-1.8.4.custom.min.js"></script>
        <script type="text/javascript"
        src="js/jquery.corner.new.js"></script>
        <script>
            var error = ""; 
            function help(diva){
                $('#'+diva).animate({ backgroundColor: "#a7bf51", height: 40, opacity: 100 }, 800);
            }
            function help_out(diva){
                $('#'+diva).animate({ backgroundColor: "transparent", height: 0, opacity: 0 }, 800);
            }
            function pass_check(){
                pass1 = $('#pass').val();
                pass2 = $('#pass2').val();
                if(pass1 == pass2){
                    if(error == 'pass'){
                        $('#error').animate({ backgroundColor: "#fff", height: 0, opacity: 0 }, 800);
                        error = '';
			
                    }
                    if(pass_check2()===true){
                        return true;
                    }else{
                        return false;
                    }
                } else {
                    $('#error').html('Помилка! Паролі не співпадають!');
                    $('#error').animate({ backgroundColor: "#ff3333", height: 20, opacity: 100 }, 800);
                    $('#error').animate({ backgroundColor: "#fff" }, 1000);
                    $('#error').animate({ backgroundColor: "#ff3333" }, 1000);
                    error = 'pass';
                    return false;
                }
            }
            function pass_check2(){
                pass = $('#pass').val().length;
                if(pass > 5){
                    if(error == 'pass2'){
                        $('#error').animate({ backgroundColor: "#fff", height: 0, opacity: 0 }, 800);
                        error = '';
			
                    }
                    return true;
                } else {
                    $('#error').html('Помилка! Пароль меньше 6ти символів!');
                    $('#error').animate({ backgroundColor: "#ff3333", height: 20, opacity: 100 }, 800);
                    $('#error').animate({ backgroundColor: "#fff" }, 1000);
                    $('#error').animate({ backgroundColor: "#ff3333" }, 1000);
                    error = 'pass2';
                    return false;
                }

            }
            function login_check(){
                login = $('#login').val().length;
                if(login > 5){
                    if(error == 'login'){
                        $('#error').animate({ backgroundColor: "#fff", height: 0, opacity: 0 }, 800);
                        error = '';
			
                    }
                    return true;
                } else {
                    $('#error').html('Помилка! Логін меньше 6ти символів!');
                    $('#error').animate({ backgroundColor: "#ff3333", height: 20, opacity: 100 }, 800);
                    $('#error').animate({ backgroundColor: "#fff" }, 1000);
                    $('#error').animate({ backgroundColor: "#ff3333" }, 1000);
                    error = 'login';
                    return false;
                }

            }
            function answer_check(){
                login = $('#answer').val().length;
                if(login > 2){
                    if(error == 'answer'){
                        $('#error').animate({ backgroundColor: "#fff", height: 0, opacity: 0 }, 800);
                        error = '';
			
                    }
                    return true;
                } else {
                    $('#error').html('Помилка! Відповідь меньше 6ти символів!');
                    $('#error').animate({ backgroundColor: "#ff3333", height: 20, opacity: 100 }, 800);
                    $('#error').animate({ backgroundColor: "#fff" }, 1000);
                    $('#error').animate({ backgroundColor: "#ff3333" }, 1000);
                    error = 'answer';
                    return false;
                }

            }
            function email_check(){
                email = $('#email').val();
                var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
                if(reg.test(email) == false) {
                    $('#error').html('Помилка! Email не правильный.');
                    $('#error').animate({ backgroundColor: "#ff3333", height: 20, opacity: 100 }, 800);
                    $('#error').animate({ backgroundColor: "#fff" }, 1000);
                    $('#error').animate({ backgroundColor: "#ff3333" }, 1000);
                    error = 'email';
                    return false;
                } else {
                    if(error == 'email'){
                        $('#error').animate({ backgroundColor: "#fff", height: 0, opacity: 0 }, 800);
                        error = ''; 
                    }
                    return true;
                }
            }
            function check(){
                if(login_check()===true & pass_check()===true  & email_check()===true){
                    return true;
                } else {
                    window.scrollTo(0,0);
                    return false;
                }
            }
            function sys_change(val){
                $.post("?do=ajax", {prenad: ""+val+"", does: "prenad"}, function(data) { // Do an AJAX call
                    $('#sys').html(data);
                });

            }
        </script>
    </head>

    <body>
        <p align="center"><a href="/"><img src="/images/core_logo.png" border=0 /></a></p>
        <table width="710" border="0" align="center" cellpadding="0"
               cellspacing="0">
            <tr>
                <td>

                    <div class="content">
                        <form id="user" name="user" method="post" action="" onsubmit="return check();">
                            <table width="700" border="0" align="center" cellpadding="10"
                                   cellspacing="0">
                                <tr>
                                    <td>
                                        <h3 align="center">Реєстрація</h3>
                                        <br />
                                        <?php
                                        if (isset($error))
                                            echo '<div id="error" style="height: 20px; width: 100% inherit; padding: 5px; text-align: center; color: #ff3333; font-weight: bold;">' . $error . '</div>';
                                        ?>

                                        <div id="error"
                                             style="height: 0px; width: 100% inherit; padding: 5px; display: none; text-align: center; color: #FFF; font-weight: bold;"></div>
                                        <h3>Базові дані:</h3>
                                        <hr />
                                        <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                            <tr>
                                                <th width="250" scope="row">Логін:</th>
                                                <td><label for="login"></label> <input name="login" type="text"
                                                                                       id="login"
                                                                                       class="inputText"
                                                                                       onfocus="help('help_about'); "
                                                                                       onblur="help_out('help_about'); login_check();" maxlength="255" <?php
                                        if (isset($_POST['login']))
                                            echo 'value=' . $db->qstr($_POST['login']);
                                        ?>/>
                                                    <br>
                                                        <div id="help_about"
                                                             style="height: 0px; width: 395px; padding: 5px; display: none;">Тільки латинські літери, цифри та знак _<br> Від 6-ти символів 

                                                        </div>

                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Пароль:</th>
                                                <td><input name="pass" type="password" id="pass"
                                                           class="inputText"
                                                           maxlength="255" onfocus="help('help_pass'); "
                                                           onblur="help_out('help_pass');" />
                                                    <div id="help_pass"
                                                         style="height: 0px; width: 395px; padding: 5px; display: none;">Тільки
                                                        латинські літери, цифри та знаки - та _<br> Від 6-ти символів 

                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Повторити пароль:</th>
                                                <td><input name="pass2" type="password" id="pass2"
                                                           class="inputText"
                                                           maxlength="255" onfocus="help('help_pass2'); "
                                                           onblur="help_out('help_pass2'); pass_check();" />
                                                    <div id="help_pass2"
                                                         style="height: 0px; width: 395px; padding: 5px; display: none;">Введіть
                                                        пароль ще раз.</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">&nbsp;</th>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Прізвище:</th>
                                                <td><input name="lastname" type="text" id="lastname"
                                                           class="inputText"
                                                           maxlength="255" <?php
                                                                                       if (isset($_POST['login']))
                                                                                           echo 'value=' . $db->qstr($_POST['lastname']);
                                        ?>/></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Ім'я:</th>
                                                <td><input name="firstname" type="text" id="firstname"
                                                           class="inputText"
                                                           maxlength="255" <?php
                                                           if (isset($_POST['login']))
                                                               echo 'value=' . $db->qstr($_POST['firstname']);
                                        ?>/></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">По батькові:</th>
                                                <td><input name="fathername" type="text" id="fathername"
                                                           class="inputText"
                                                           maxlength="255" <?php
                                                           if (isset($_POST['login']))
                                                               echo 'value=' . $db->qstr($_POST['fathername']);
                                        ?>/></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">&nbsp;</th>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Email:</th>
                                                <td>
                                                    <input name="email" type="text" id="email" class="inputText" maxlength="255" onblur="email_check();" <?php echo (isset($_POST['email'])) ? 'value=' . $db->qstr($_POST['email']) : ''; ?>/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Секретне питання:</th>
                                                <td>
                                                    <select name="question" id="question" class="inputText">
                                                        <?php
                                                        foreach ($settings['questions'] as $key => $item) {
                                                            if (isset($_POST['question']) && $_POST['question'] == $key) {
                                                                echo '<option value="' . $key . '" selected="selected">' . $item . '</option>';
                                                            } else {
                                                                echo '<option value="' . $key . '">' . $item . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Відповідь:</th>
                                                <td>
                                                    <input name="answer" type="text" id="answer" class="inputText" maxlength="255" onfocus="help('help_answer'); " onblur="help_out('help_answer'); answer_check();"   <?php echo (isset($_POST['answer'])) ? 'value=' . $db->qstr($_POST['answer']) : ''; ?>/>
                                                    <div id="help_answer" style="height: 0px; width: 395px; padding: 5px; display: none;">Вона знадобиться для відновлення пароля в разі його втрати.</div>
                                                </td>
                                            </tr>
                                        </table>
                                        <h3>Група та унікальні дані:</h3>
                                        <hr />
                                        <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                            <tr>
                                                <th width="250" scope="row">Принадлежність:</th>
                                                <td><select name="sys_group" id="sys_group"
                                                            onchange="sys_change(this.value)"
                                                            class="inputText">
                                                                <?php
                                                                $groups = $db->Execute('select * from `sys_groups` where `sys_g_register`=1 order by `sys_g_level`');
                                                                if ($groups->RecordCount() > 0) {
                                                                    while (!$groups->EOF) {
                                                                        echo '<option value="' . $groups->fields ['id'] . '">' . $groups->fields ['sys_g_name'] . '</option>';
                                                                        $groups->MoveNext();
                                                                    }
                                                                } else {
                                                                    echo 'Реєстрація не працює.';
                                                                }
                                                                ?>
                                                    </select></td>
                                            </tr>
                                        </table>
                                        <div id='sys'></div>
                                        <br />
                                        <center><input type="submit" name="submit" id="submit"
                                                       value="Зареєструватися!" style="font-size: 18px; padding: 10px;"
                                                       /></center>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </td>
            </tr>
        </table>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                $('.content').corner();
                sys_change($('#sys_group').val());
            });
        </script>

    </body>
</html>
