

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
        <script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script> 
        <script type="text/javascript" src="/js/jquery.corner.new.js"></script> 
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
                                    <h2 align="center">Відновлення паролю</h2> 
                                    <p>Для відновлення паролю Ви повинні ввести номер студентського (без букв) або логін та відповісті на секретне питання.</p>
                                    <p>Якщо ж Ви забули відповідь на секретне питання, зверніться до адміністратора для генерування коду збросу.</p>

                                    <table width="700" border="0" align="center" cellpadding="10"
                                           cellspacing="0">
                                        <tr>
                                            <td>
                                                <?php
                                                if (isset($error))
                                                    echo '<div id="error" style="height: 20px; width: 100% inherit; padding: 5px; text-align: center; color: #ff3333; font-weight: bold;">' . $error . '</div>';
                                                ?>

                                                <form method="GET" action="/?do=lostpass&step=2">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                        <tr>
                                                            <th width="250" scope="row">Номер студентческого:</th>
                                                            <td>
                                                                <input name="stud" type="text" id="stud" class="inputText" maxlength="8" <?php echo (isset($_POST['stud'])) ? 'value=' . $db->qstr($_POST['stud']) : ''; ?> />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th width="250" scope="row">або логін:</th>
                                                            <td>
                                                                <input name="login" type="text" id="login" class="inputText" maxlength="255" <?php echo (isset($_POST['login'])) ? 'value=' . $db->qstr($_POST['login']) : ''; ?> />
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <p align="center">
                                                        <input type="submit" name="submit" id="submit" value="Напомнить пароль!" style="font-size: 16px; padding: 5px;" />
                                                    </p>
                                                    <hr />
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                        <tr>
                                                            <th width="250" scope="row">Код збросу:</th>
                                                            <td>
                                                                <input name="flush" type="text" id="flush" class="inputText" maxlength="8" <?php echo (isset($_POST['flush'])) ? 'value=' . $db->qstr($_POST['flush']) : ''; ?> />
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <p align="center">
                                                        <input type="hidden" name="step" value="2" />
                                                        <input type="hidden" name="do" value="lostpass" />
                                                        <input type="submit" name="submit" id="submit" value="Відновити пароль" style="font-size: 16px; padding: 5px;" />
                                                    </p>
                                                </form>


                                                <p align="center">
                                                    <a href="/">Перейти до головної сторінки</a>
                                                    <br />
                                                </p>
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