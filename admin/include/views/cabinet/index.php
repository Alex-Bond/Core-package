<?php

function ip_vs_net($ip, $network, $mask) {
    if (((ip2long($ip)) & (ip2long($mask))) == ip2long($network)) {
        return 1;
    } else {
        return 0;
    }
}
?>
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
            <?php
            if ($_SESSION['user']['sys_g_admin'] == 1) {
                ?>
                .tableOnsite th {
                    border-bottom:thin dotted #999999;
                }
                .tableOnsite td {
                    border-bottom:thin dotted #999999;
                }
                <?php
            }
            ?>

        </style> 

        <script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script> 
        <script type="text/javascript" src="/js/jquery.corner.new.js"></script>
        <script type="text/javascript">  
            function uAct(){
                $.getJSON("/?do=ajax&cron=1");
                setTimeout("uAct()",15000);
            }
            uAct();
        </script>
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
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td width="25%" align="center"><a href="/?do=cabinet&p=email">Email</a></td>
                                            <td width="25%" align="center"><a href="/?do=cabinet&p=pass">Пароль</a></td>
                                            <td width="25%" align="center"><a href="/?do=cabinet&p=question">Секретне питання</a></td>
                                            <td width="25%" align="center"><a href="/?do=logout"><strong>Вихiд</strong></a></td>
                                        </tr>
                                    </table>

                                    <h2 align="center">Особистий кабiнет</h2> 

                                    <?php
                                    if (isset($error))
                                        echo '<div id="error" style="height: 20px; width: 100% inherit; padding: 5px; text-align: center; color: #ff3333; font-weight: bold;">' . $error . '</div>';
                                    $splash->printAll();
                                    ?>

                                    <p align="center">Ласкаво просимо до особистого кабінету.<br />Тут Ви можете змінити особисті дані або ж перейти в інші системи.</p>

                                    <?php
                                    if ($_SESSION['user']['sys_g_admin'] == 1) {
                                        echo '<center><a href="/?do=cabinet&p=go&site=admin"><strong>До панелі керування</strong></a></center><br />';
                                    }
                                    ?>

                                    <br />

                                    <center>
                                        <?php if (ip_vs_net($_SERVER['REMOTE_ADDR'], "11.1.1.0", "255.255.255.0") OR ip_vs_net($_SERVER['REMOTE_ADDR'], "192.168.0.0", "255.255.255.0") OR $_SESSION['user']['sys_g_admin'] == 1) { ?>
                                            <a href="/?do=cabinet&p=go&site=1"><img src="/images/logos_01.png" border="0" /></a><br /><br />
                                            <a href="/?do=cabinet&p=go&site=2"><img src="/images/logos_02.png" border="0" /></a><br /><br />
                                            <a href="/?do=cabinet&p=go&site=3"><img src="/images/logos_04.png" border="0" /></a><br /><br />
                                        <?php } else { ?>
                                            <a href="/?do=cabinet&p=go&site=2"><img src="/images/logos_02.png" border="0" /></a><br /><br />
                                        <?php } ?>
                                    </center>

                                    <br />

                                    <?php
                                    if ($_SESSION['user']['sys_g_admin'] == 1) {
                                        ?>
                                        <h3>Зараз у системi:</h3>
                                        <h4>Адміністратори:</h4>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="5" class="tableOnsite" id="onsite_admin">
                                            <tr>
                                                <th width="40" scope="col">ID</th>
                                                <th scope="col">ПIП</th>
                                                <th scope="col">Де знаходиться</th>
                                            </tr>
                                        </table>

                                        <h4>Користувачi:</h4>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="5" class="tableOnsite" id="onsite_users">
                                            <tr>
                                                <th width="40" scope="col">ID</th>
                                                <th scope="col">ПIП</th>
                                                <th scope="col">Де знаходиться</th>
                                            </tr>

                                        </table>
                                        <script type="text/javascript">
                                            function onsite_update_admin(){
                                                $.getJSON("/?do=ajax&onsite_admin=1",
                                                function(html){
                                                    $('#onsite_admin tr:not(:first)').remove();
                                                    if(html!=null){
                                                        $.each(html, function(key, val) {
                                                            addRowAdmin(val.id, val.fio, val.lact, val.where);
                                                        });
                                                    }
                                                }
                                            );
                                                setTimeout("onsite_update_admin()",10000);
                                            }
                                            onsite_update_admin();
                                                                    
                                            function addRowAdmin(id, fio, last_act, where){
                                                var html = '<tr><td align="center">'+id+'</td><td>'+fio+'</td><td title="'+last_act+'">'+where+'</td></tr>';
                                                $('#onsite_admin tr:first').after(html);
                            
                                            }
                                                    
                                            function onsite_update_users(){
                                                $.getJSON("/?do=ajax&onsite_users=1",
                                                function(html){
                                                    $('#onsite_users tr:not(:first)').remove();
                                                    if(html!=null){
                                                        $.each(html, function(key, val) {
                                                            addRowUser(val.id, val.fio, val.lact, val.where);
                                                        });
                                                    }
                                                }
                                            );
                                                setTimeout("onsite_update_users()",10000);
                                            }
                                            onsite_update_users();
                                                                    
                                            function addRowUser(id, fio, last_act, where){
                                                var html = '<tr><td align="center">'+id+'</td><td>'+fio+'</td><td title="'+last_act+'">'+where+'</td></tr>';
                                                $('#onsite_users tr:first').after(html);
                            
                                            }
                                        </script>
                                        <br />
                                        <?php
                                    }
                                    ?>

                                    <center><a href="/?do=logout"><strong>Вихiд</strong></a></center>
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