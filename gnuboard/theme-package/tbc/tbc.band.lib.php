<?php
/**
 * TBC BAND 링크 관리
 */
if (!defined('_GNUBOARD_')) exit;

function tbc_band_table()
{
    return G5_TABLE_PREFIX . 'tbc_band';
}

function tbc_band_tables_exist()
{
    $row = sql_fetch(" show tables like '" . tbc_band_table() . "' ");
    return (bool) $row;
}

function tbc_band_ensure_tables()
{
    if (!tbc_band_tables_exist()) {
        $charset = (defined('G5_DB_CHARSET') && G5_DB_CHARSET) ? G5_DB_CHARSET : 'utf8';
        sql_query("
            CREATE TABLE IF NOT EXISTS `" . tbc_band_table() . "` (
                `bd_id` int(11) NOT NULL AUTO_INCREMENT,
                `bd_title` varchar(120) NOT NULL DEFAULT '',
                `bd_url` varchar(500) NOT NULL DEFAULT '',
                `bd_order` int(11) NOT NULL DEFAULT 0,
                `bd_use` tinyint(4) NOT NULL DEFAULT 1,
                `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                PRIMARY KEY (`bd_id`),
                KEY `bd_order` (`bd_order`)
            ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
        ", false);
    }

    return tbc_band_tables_exist();
}

function tbc_band_get_list($keyword = '')
{
    if (!tbc_band_tables_exist()) {
        return array();
    }

    $where = ' where 1=1 ';
    if ($keyword !== '') {
        $keyword = sql_real_escape_string($keyword);
        $where .= " and (bd_title like '%{$keyword}%' or bd_url like '%{$keyword}%') ";
    }

    $result = sql_query("
        select *
        from " . tbc_band_table() . "
        {$where}
        order by bd_order asc, bd_id asc
    ");

    $rows = array();
    while ($row = sql_fetch_array($result)) {
        $rows[] = $row;
    }

    return $rows;
}

function tbc_band_get_public_list()
{
    tbc_band_ensure_tables();

    if (!tbc_band_tables_exist()) {
        return array();
    }

    $result = sql_query("
        select *
        from " . tbc_band_table() . "
        where bd_use = 1
        order by bd_order asc, bd_id asc
    ");

    $rows = array();
    while ($row = sql_fetch_array($result)) {
        $rows[] = $row;
    }

    return $rows;
}

function tbc_band_get($bd_id)
{
    if (!tbc_band_tables_exist()) {
        return null;
    }

    $bd_id = (int) $bd_id;
    return sql_fetch(" select * from " . tbc_band_table() . " where bd_id = {$bd_id} ");
}

function tbc_band_next_order()
{
    $row = sql_fetch(" select ifnull(max(bd_order), 0) as max_order from " . tbc_band_table() . " ");
    return (int) $row['max_order'] + 1;
}

function tbc_band_normalize_orders()
{
    $items = tbc_band_get_list();
    $order = 0;
    foreach ($items as $item) {
        $order++;
        sql_query(" update " . tbc_band_table() . " set bd_order = {$order} where bd_id = " . (int) $item['bd_id'] . " ");
    }
}

function tbc_band_save($data, $bd_id = 0)
{
    tbc_band_ensure_tables();

    $bd_id = (int) $bd_id;
    $fields = array(
        'bd_title' => isset($data['bd_title']) ? $data['bd_title'] : '',
        'bd_url' => isset($data['bd_url']) ? $data['bd_url'] : '',
        'bd_use' => !empty($data['bd_use']) ? 1 : 0,
    );

    $sets = array();
    foreach ($fields as $key => $value) {
        $sets[] = "`{$key}` = '" . sql_real_escape_string($value) . "'";
    }
    $sets[] = "`updated_at` = '" . G5_TIME_YMDHIS . "'";

    if ($bd_id > 0) {
        sql_query(" update " . tbc_band_table() . " set " . implode(', ', $sets) . " where bd_id = {$bd_id} ");
        return array('ok' => true, 'bd_id' => $bd_id);
    }

    $order = tbc_band_next_order();
    $sets[] = "`bd_order` = {$order}";
    sql_query(" insert into " . tbc_band_table() . " set " . implode(', ', $sets));

    return array('ok' => true, 'bd_id' => (int) sql_insert_id());
}

function tbc_band_move($bd_id, $direction)
{
    $bd_id = (int) $bd_id;
    $item = tbc_band_get($bd_id);
    if (!$item) {
        return array('ok' => false, 'message' => 'BAND 링크를 찾을 수 없습니다.');
    }

    $list = tbc_band_get_list();
    $current_index = -1;
    foreach ($list as $i => $row) {
        if ((int) $row['bd_id'] === $bd_id) {
            $current_index = $i;
            break;
        }
    }

    if ($current_index < 0) {
        return array('ok' => false, 'message' => '순서를 찾을 수 없습니다.');
    }

    $swap = ($direction === 'up') ? $current_index - 1 : $current_index + 1;
    if ($swap < 0 || $swap >= count($list)) {
        return array('ok' => false, 'message' => '더 이상 이동할 수 없습니다.');
    }

    $current_order = (int) $list[$current_index]['bd_order'];
    $target_order = (int) $list[$swap]['bd_order'];

    sql_query(" update " . tbc_band_table() . " set bd_order = {$target_order} where bd_id = {$bd_id} ");
    sql_query(" update " . tbc_band_table() . " set bd_order = {$current_order} where bd_id = " . (int) $list[$swap]['bd_id'] . " ");
    tbc_band_normalize_orders();

    return array('ok' => true);
}

function tbc_band_delete($bd_id)
{
    $bd_id = (int) $bd_id;
    if (!tbc_band_get($bd_id)) {
        return array('ok' => false, 'message' => 'BAND 링크를 찾을 수 없습니다.');
    }

    sql_query(" delete from " . tbc_band_table() . " where bd_id = {$bd_id} ");
    tbc_band_normalize_orders();

    return array('ok' => true);
}

function tbc_band_seed_defaults()
{
    if (!tbc_band_ensure_tables()) {
        return false;
    }

    $count = sql_fetch(" select count(*) as cnt from " . tbc_band_table() . " ");
    if ((int) $count['cnt'] > 0) {
        return true;
    }

    $default_url = defined('TBC_BAND_URL') ? TBC_BAND_URL : 'https://www.band.us/';
    sql_query("
        insert into " . tbc_band_table() . " set
            bd_title = '더브레인코어 공식 BAND',
            bd_url = '" . sql_real_escape_string($default_url) . "',
            bd_order = 1,
            bd_use = 1,
            updated_at = '" . G5_TIME_YMDHIS . "'
    ");

    return true;
}

function tbc_band_primary_url()
{
    $bands = tbc_band_get_public_list();
    if ($bands) {
        return $bands[0]['bd_url'];
    }

    return defined('TBC_BAND_URL') ? TBC_BAND_URL : '';
}
