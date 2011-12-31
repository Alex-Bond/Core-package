<?php

$i_dns = sprintf($g_db_connectionsettings[$srv_settings['db_driver']]['dns'], $srv_settings['db_driver'], $srv_settings['db_host'], $srv_settings['db_user'], $srv_settings['db_password'], $srv_settings['db_db']);
$i_server = sprintf($g_db_connectionsettings[$srv_settings['db_driver']]['server'], $srv_settings['db_driver'], $srv_settings['db_host'], $srv_settings['db_user'], $srv_settings['db_password'], $srv_settings['db_db']);
$g_db = ADONewConnection($i_dns);
$g_db->debug = IGT_DB_DEBUG_MODE;
$g_db->PConnect($i_server, $srv_settings['db_user'], $srv_settings['db_password'], $srv_settings['db_db']);
$g_db->SetFetchMode(ADODB_FETCH_BOTH);
if ($srv_settings['db_driver'] == DB_DRIVER_MYSQL) {
    @$g_db->Execute("SET character_set_client = " . $lngstr['sql_charset'] . ";");
    @$g_db->Execute("SET character_set_results = " . $lngstr['sql_charset'] . ";");
    @$g_db->Execute("SET character_set_connection = " . $lngstr['sql_charset'] . ";");
}
@$g_db->Execute("SET NAMES utf8;");

$options = array(
    'namespace' => 'kkztest_',
    'servers' => array(
        array('host' => '127.0.0.1', 'port' => 6379)
    )
);

$rediska = new Rediska($options);

if (!isset($G_SESSION ['userid']) OR $G_SESSION ['userid'] == 0) {
    if (isset($_GET['key'])) {
        signinUserById($_GET['key']);
        if (!isset($G_SESSION ['userid']) OR $G_SESSION ['userid'] == 0) {
            header('HTTP/1.1 403 Forbidden');
            header('Location: http://kkzcore.pp.ua/');
            die();
        }
    } else {
        header('HTTP/1.1 403 Forbidden');
        header('Location: http://kkzcore.pp.ua/');
        die();
    }
}

if (isset($_GET['key'])) {
    header('Location: http://kkztest.pp.ua/');
}
?>
