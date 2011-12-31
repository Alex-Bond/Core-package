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
        <p align="center"><img src="/images/core_logo.png" /></p> 
        <table width="710" border="0" align="center" cellpadding="0" cellspacing="0"> 
            <tr> 
                <td> 

                    <div class="content"> 

                        <table width="700" border="0" align="center" cellpadding="10" cellspacing="0"> 
                            <tr> 
                                <td>
                                    <h2 align="center">Відновлення паролю</h2> 
                                    <p align="center">Введіть відповідь на секретне питання вказане при реєстрації:</p>

                                    <?php
                                    if (isset($error))
                                        echo '<div id="error" style="height: 20px; width: 100% inherit; padding: 5px; text-align: center; color: #ff3333; font-weight: bold;">' . $error . '</div>';
                                    ?>

                                    <h2 align="center"><?php echo $settings['questions'][$user->fields['question']]; ?></h2>
                                    <form method="POST" action="/?do=lostpass&step=3">
                                        <p align="center"><input name="answer" type="text" id="answer" class="inputText" maxlength="255" /></p>
                                        <p align="center"><input type="submit" name="submit" id="submit" value="Принять ответ" style="font-size: 16px; padding: 5px;" /></p>
                                        <input type="hidden" name="id" value="<?php echo $user->fields['id']; ?>" />
                                    </form>
                                    <p align="center">
                                            Якщо Ви не пам'ятаєте відповідь на секретне питання зверніться до адмітістратора для генерування коду збросу.
                                        <br />
                                    </p>
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