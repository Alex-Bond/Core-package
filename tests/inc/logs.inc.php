<?php

if (!isset($G_SESSION['config_storesessionlogs'])) {
    $G_SESSION['config_storesessionlogs'] = getConfigItem(CONFIG_store_logs) ? true : false;
}

if ($G_SESSION['config_storesessionlogs']) {

    $i_scripturl = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '';
    if (!empty($_SERVER['QUERY_STRING']))
        $i_scripturl .= '?' . $_SERVER['QUERY_STRING'];

    if (!isset($G_SESSION['visitorid'])) {
        $i_startdate = time();
        $i_userid = isset($G_SESSION['userid']) ? $G_SESSION['userid'] : 0;
        $i_ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        if (!$i_ip)
            $i_ip = getenv('REMOTE_ADDR');
        if (!$i_ip)
            $i_ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : '';
        if (!$i_ip)
            $i_ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? preg_replace('/,.*/', '', $_SERVER['HTTP_X_FORWARDED_FOR']) : '';
        if (!$i_ip) {
            $i_ip_tmp = getenv('HTTP_X_FORWARDED_FOR');
            $i_ip = preg_replace('/,.*/', '', $i_ip_tmp);
        }
        $i_ip_nets = explode('.', $i_ip);
        $i_ip1 = isset($i_ip_nets[0]) ? $i_ip_nets[0] : 0;
        $i_ip2 = isset($i_ip_nets[1]) ? $i_ip_nets[1] : 0;
        $i_ip3 = isset($i_ip_nets[2]) ? $i_ip_nets[2] : 0;
        $i_ip4 = isset($i_ip_nets[3]) ? $i_ip_nets[3] : 0;
        $i_host = gethostbyaddr($i_ip);
        $i_host = $g_db->qstr($i_host, 0);
        $i_referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $i_referer = $g_db->qstr($i_referer, 0);
        $i_useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $i_useragent = $g_db->qstr($i_useragent, 0);
        $i_inurl = $i_scripturl;
        $i_inurl = $g_db->qstr($i_inurl, 0);
        $i_outurl = $i_scripturl;
        $i_outurl = $g_db->qstr($i_outurl, 0);
        if ($g_db->Execute("INSERT INTO " . $srv_settings['table_prefix'] . "visitors (startdate, userid, ip1, ip2, ip3, ip4, host, referer, useragent, inurl, outurl) VALUES ($i_startdate, $i_userid, $i_ip1, $i_ip2, $i_ip3, $i_ip4, " . $i_host . ", " . $i_referer . ", " . $i_useragent . ", " . $i_inurl . ", " . $i_outurl . ")") === false) {
            
        } else {
            $i_visitorid = (int) $g_db->Insert_ID();
            $G_SESSION['visitorid'] = $i_visitorid;
        }
    }

    $i_time = time();
    $i_outurl = $i_scripturl;
    $i_outurl = $g_db->qstr($i_outurl, 0);
    $g_db->Execute("UPDATE " . $srv_settings['table_prefix'] . "visitors SET enddate=" . $i_time . ", hits=hits+1, outurl=" . $i_outurl . " WHERE visitorid=" . $G_SESSION['visitorid']);
}
?>
