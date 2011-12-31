<?php

$f_groupid = (int) readGetVar('groupid');
$f_testids = explode(SYSTEM_ARRAY_ITEM_SEPARATOR, readGetVar('testids'));

if ($_GET["set"]) {
    foreach ($f_testids as $i_testid) {
        $g_db->Execute("INSERT INTO " . $srv_settings['table_prefix'] . "groups_tests (groupid, testid) VALUES ($f_groupid, $i_testid)");
    }
} else {
    reset($f_testids);
    if (list(, $val) = each($f_testids))
        $i_sql_where_addon .= "testid=" . (int) $val;
    while (list(, $val) = each($f_testids)) {
        $i_sql_where_addon .= " OR testid=" . (int) $val;
    }
    if ($i_sql_where_addon)
        $i_sql_where_addon = ' AND (' . $i_sql_where_addon . ')';
    if ($g_db->Execute("DELETE FROM " . $srv_settings['table_prefix'] . "groups_tests WHERE groupid=$f_groupid" . $i_sql_where_addon) === false)
        showDBError(__FILE__, 2);
}

gotoLocation('test-manager.php' . getURLAddon('?action=groups', array('action', 'groupid', 'set')));
?>
