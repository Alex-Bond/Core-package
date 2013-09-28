

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
    <head> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <title>ККЗ Core :: Кабiнет</title> 
        <style type="text/css"> 
            body {
                background: url('/images/bg.jpg');
            }
            body,td,th,input,select {
                font-family: calibri, "segoe UI", arial;
            }

            .content {
                display: block;
                background-color:#ececec;
                padding: 10px;
                margin-left: 19px;
                margin-right: 19px;
                box-shadow: 0 2px 5px #000;
                -moz-box-shadow: 0 2px 5px #000;
                -webkit-box-shadow: 0 2px 5px #000;	
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
            function answer_check(){
                login = $('#answer').val().length;
                if(login > 2){
                    if(error == 'answer'){
                        $('#error').animate({ backgroundColor: "#fff", height: 0, opacity: 0 }, 800);
                        error = '';
			
                    }
                    return true;
                } else {
                    $('#error_js').html('Помилка! Відповідь меньше 3х символів!');
                    $('#error_js').animate({ backgroundColor: "#ff3333", height: 20, opacity: 100 }, 800);
                    $('#error_js').animate({ backgroundColor: "#fff" }, 1000);
                    $('#error_js').animate({ backgroundColor: "#ff3333" }, 1000);
                    error = 'answer';
                    return false;
                }

            }
            function check(){
                if(answer_check()===true){
                    return true;
                } else {
                    window.scrollTo(0,0);
                    return false;
                }
            }

        
        </script>
        <script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script> 
        <script type="text/javascript" src="/js/jquery.corner.new.js"></script> 
        <script type="text/javascript" src="/js/jquery-ui-1.8.4.custom.min.js"></script>
        <script type="text/javascript">  
            function uAct(){
                $.getJSON("/?do=ajax&cron=1");
                setTimeout("uAct()",15000);
            }
            uAct();
        </script>
    </head> 

    <body> 
        <p align="center"><a href="/"><img src="/images/core_logo.png" border="0" /></a></p> 
        <table width="710" border="0" align="center" cellpadding="0" cellspacing="0"> 
            <tr> 
                <td> 

                    <div class="content"> 

                        <table width="700" border="0" align="center" cellpadding="10" cellspacing="0"> 
                            <tr> 
                                <td>


                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td width="25%" align="center"><a href="/?do=cabinet&p=email">Email</a></td>
                                            <td width="25%" align="center"><a href="/?do=cabinet&p=pass">Пароль</a></td>
                                            <td width="25%" align="center"><a href="/?do=cabinet&p=question">Секретне питання</a></td>
                                            <td width="25%" align="center"><a href="/?do=logout"><strong>Вихiд</strong></a></td>
                                        </tr>
                                    </table>

                                    <h2 align="center">Змінення секретного питання</h2> 

                                    <?php
                                    if (isset($error))
                                        echo '<div id="error" style="height: 20px; width: 100% inherit; padding: 5px; text-align: center; color: #000; font-weight: bold;">' . $error . '</div>';
                                    $splash->printAll();
                                    ?>

                                    <form method="POST" onsubmit="return check();">
                                        <div id="error_js" style="height: 0px; width: 100% inherit; padding: 5px; display: none; text-align: center; color: #000; font-weight: bold;"></div>
                                        <table width="700" border="0" align="center" cellpadding="10"
                                               cellspacing="0">
                                            <tr>
                                                <td>

                                                    <tr>
                                                        <th scope="row">Зараз у Вас:</th>
                                                        <td>
                                                            <?php echo $settings['questions'][$_SESSION['user']['question']]; ?>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th scope="row">Секретне питання<br />(оберiть зi списку):</th>
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
                                                            <input name="answer" type="text" id="answer" class="inputText" maxlength="255" onblur="answer_check();"   <?php echo (isset($_POST['answer'])) ? 'value=' . $db->qstr($_POST['answer']) : ''; ?>/>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th scope="row">Пароль<br/>(який використовується зараз):</th>
                                                        <td>
                                                            <input name="old_pass" type="password" id="old_pass" class="inputText" maxlength="255"/>
                                                        </td>
                                                    </tr>

                                                </td>
                                            </tr>
                                        </table>

                                        <center><input type="submit" name="submit" id="submit" value="Зберегти" style="font-size: 16px; padding: 5px;" /></center>

                                    </form>
                                </td>
                            </tr>
                        </table>
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