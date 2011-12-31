<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ККЗ Library :: <?=$document['title']?></title>
<link rel="stylesheet" type="text/css" href="/css/corelib.css" media="all" /> 
<link type="text/css" href="/css/south-street/jquery-ui-1.8.7.custom.css" rel="stylesheet" />	

<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.7.custom.min.js"></script>
<script type="text/javascript" src="/js/jquery.ui.datepicker-uk.js"></script>
<script type="text/javascript" src="/js/jquery.corner.js?v2.01"></script>
<script type="text/javascript" src="/js/swfobject.js"></script>
<script type="text/javascript" src="/js/uppod.js"></script>  
<script type="text/javascript" src="/js/corelib.js"></script>
<script type="text/javascript">  
function uAct(){
    $.getJSON("http://kkzcore.pp.ua/?do=ajax&cron=1&callback=?");
    setTimeout("uAct()",15000);
}
uAct();
</script>
</head>

<body>
<div class="video_bg"></div>
<div id="videoplayer1" style="position: absolute; top:50%; left: 50%; margin-top: -237px; margin-left: -250px;">
<table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="right" style="cursor:pointer;" onclick="video_close()"><img src="/images/close.gif" border="0" /></td>
  </tr>
  <tr>
    <td bgcolor="#000"><div id="videoplayer"></div></td>
  </tr>
</table>
</div>

<div id="temp_abs" style="position: absolute;"></div>
<div id="temp"></div>
<table width="1200" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <td height="138" valign="top" style="background:url(images/gead_bg.png) no-repeat center top; margin-top:10px;" ><table width="1130" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td  style="font-size:10px; font-family: Tahoma, Geneva, sans-serif;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50%" style="font-size:10px; font-family: Tahoma, Geneva, sans-serif;">Вітаємо, <?=

$user['name']

?>!</td>
            <td align="right" style="font-size:10px; font-family: Tahoma, Geneva, sans-serif;"><img src="images/printer.png" width="12" height="12" hspace="5" align="texttop" /><a onclick="onflash()" style="cursor: pointer;">На Flash</a>&nbsp; &nbsp; &nbsp; &nbsp;<img src="images/exit.png" width="12" height="12" hspace="5" align="texttop" /><a href="?do=base&f=logout">Вихід</a></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><a href="?"><img src="images/logo.png" width="162" height="26" vspace="30" border="0" /></a></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="1169" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td>
        
        <div class="content">