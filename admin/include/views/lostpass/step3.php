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
            function check(){
                if(pass_check()===true){
                    return true;
                } else {
                    window.scrollTo(0,0);
                    return false;
                }
            }

        </script>
        <script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script> 
        <script type="text/javascript" src="/js/jquery.corner.new.js"></script> 
    </head> 

    <body> 
        <p align="center"><img src="/images/core_logo.png" /></p> 
        <table width="710" border="0" align="center" cellpadding="0" cellspacing="0"> 
            <tr> 
                <td> 

                    <div class="content"> 

                        <table width="700" border="0" align="center" cellpadding="10" cellspacing="0"> 
                            <tr> 
                                <td>
                                    <h2 align="center">Відновлення паролю</h2> 
                                    <p align="center">Вітаємо! Тепер Ви можете ввести новий пароль:</p>

                                    <?php
                                    if (isset($error))
                                        echo '<div id="error" style="height: 20px; width: 100% inherit; padding: 5px; text-align: center; color: #ff3333; font-weight: bold;">' . $error . '</div>';
                                    ?>

                                    <form id="user" name="user" method="post" action="/?do=lostpass&step=save" onsubmit="return check();">

                                        <div id="error"
                                             style="height: 0px; width: 100% inherit; padding: 5px; display: none; text-align: center; color: #FFF; font-weight: bold;"></div>

                                        <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                            <tr>
                                                <th scope="row">Ваш логін:</th>
                                                <td><?php echo $user->fields['login']; ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Новий пароль:</th>
                                                <td><input name="pass" type="password" id="pass" class="inputText" maxlength="255" onfocus="help('help_pass'); " onblur="help_out('help_pass');" />
                                                    <div id="help_pass" style="height: 0px; width: 395px; padding: 5px; display: none;">
                                                        Тільки латинські літери, цифри та знаки - та _<br> Від 6-ти символів 
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Повторити пароль:</th>
                                                <td><input name="pass2" type="password" id="pass2"
                                                           class="inputText"
                                                           maxlength="255" onfocus="help('help_pass2'); "
                                                           onblur="help_out('help_pass2'); pass_check();" />
                                                    <div id="help_pass2" style="height: 0px; width: 395px; padding: 5px; display: none;">
                                                        Введіть пароль ще раз.
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                        <div id='sys'></div>
                                        <br />
                                        <center>
                                            <input type="hidden" name="hash" value="<?php echo $_SESSION['lostpass']['hash']; ?>" />
                                            <input type="submit" name="submit" id="submit" value="Оновити пароль!" style="font-size: 18px; padding: 10px;" />
                                        </center>
                                    </form>


                                    <p align="center">
                                        <a href="/">
                                            Прейти до головної сторінки
                                        </a>
                                        <br />
                                    </p>

                                    </div> 
                                </td> 
                            </tr> 
                        </table> 
                        <script type="text/javascript"> 
                            jQuery(document).ready(function(){
                                $('.content').corner();
                            });
                        </script> 

                        </body> 
                        </html> 