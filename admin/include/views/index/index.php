<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>ККЗ Core</title>
        <meta name="description" content="Система автоматизації ВНЗ." /> 
        <link rel="icon" href="/favicon.ico" sizes="32x32" /> 
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" /> 
        <style type="text/css">
            <!--
            body,td,th {
                font-family: segoe UI;
            }

            a:link {
                text-decoration: none;
            }

            a:visited {
                text-decoration: none;
            }

            a:hover {
                text-decoration: none;
            }

            a:active {
                text-decoration: none;
            }

            body {
                background-image: url(/images/bg.jpg);
            }

            .content {
                width: 796px;
                position: absolute;
                top: 50%;
                left: 50%;
                height: 458px;
                margin-top: -229px;
                margin-left: -398px;
            }
            .logo{
                text-align: center;
                height: 43px;
            }
            .info_error{
                position: absolute;
                width: 747px;
                top: 90px;
                padding-left: 24px;
                padding-right: 24px;
            }
            .login_form{
                background: url(/images/login_bg.png) no-repeat;
                height: 371px;
            }
            .login_form form{
                position: absolute;
                top: 120px;
                left: 0px;
                width: 100%;
            }
            .login_form form table{
                position: relative;
                width: 600px;
                left: 70px;
            }
            .register_lostpass{
                position: relative;
                top: 300px;
                width: 100%;
                text-align: center;
            }
            .register_lostpass a{
                text-decoration: none;
                cursor: pointer;
                color: #000;
                /*font-weight: bold;*/
                font-size: 16px;
            }
            -->
        </style>
        <script src="/js/jquery-1.7.1.min.js" language="javascript"></script>

    </head>

    <body>
        <div class="content">
            <div class="logo"><img src="/images/core_logo.png" width="149" height="43" /></div>
            <div class="login_form">
                <div class="info_error"><?php $splash->printAll(); ?></div>
                <form id="loginform" name="loginform" method="post" action="" onkeypress="if ( event.keyCode == 13 ) this.submit();"><br>
                        <table width="100%" border="0" cellspacing="0" cellpadding="10">
                            <tr>
                                <td width="30%" align="center">Логін</td>
                                <td width="70%">
                                    <input name="login" type="text" id="login" style="width: 400px; height: 40px; font-size: 24px;" />
                                </td>
                            </tr>
                            <tr>

                                <td align="center">Пароль</td>
                                <td>
                                    <input name="pass" type="password" id="pass" style="width: 400px; height: 40px; font-size: 24px; vertical-align: middle" />
                                </td>
                            </tr>
                        </table>
                        <input name="do" type="hidden" value="core" />
                        <input name="f" type="hidden" value="login" />

                        <p align="center" style="font-size: 24px; text-decoration: underline; cursor: pointer;" onclick="document.loginform.submit();">Вхід</p>

                </form>
                <div class="register_lostpass"><a href="/?do=register">Реєстрація</a> | <a href="/?do=lostpass">Вiдновлення паролю</a></div>
            </div>
        </div>
    </body>
</html>