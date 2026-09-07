<?php
/**
 * TBC 더브코 연혁 관리
 */
if (!defined('_GNUBOARD_')) exit;

define('TBC_HISTORY_CONFIG_ID', 1);

function tbc_history_config_table()
{
    return G5_TABLE_PREFIX . 'tbc_history_config';
}

function tbc_history_entry_table()
{
    return G5_TABLE_PREFIX . 'tbc_history_entry';
}

function tbc_history_tables_exist()
{
    $config = sql_fetch(" show tables like '" . tbc_history_config_table() . "' ");
    $entry = sql_fetch(" show tables like '" . tbc_history_entry_table() . "' ");
    return (bool) $config && (bool) $entry;
}

function tbc_history_ensure_tables()
{
    if (tbc_history_tables_exist()) {
        return true;
    }

    $charset = (defined('G5_DB_CHARSET') && G5_DB_CHARSET) ? G5_DB_CHARSET : 'utf8';

    sql_query("
        CREATE TABLE IF NOT EXISTS `" . tbc_history_config_table() . "` (
            `hc_id` int(11) NOT NULL AUTO_INCREMENT,
            `hc_label_en` varchar(120) NOT NULL DEFAULT '',
            `hc_title_bold` varchar(255) NOT NULL DEFAULT '',
            `hc_title_text` varchar(255) NOT NULL DEFAULT '',
            `hc_desc` text NOT NULL,
            `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY (`hc_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
    ", false);

    sql_query("
        CREATE TABLE IF NOT EXISTS `" . tbc_history_entry_table() . "` (
            `he_id` int(11) NOT NULL AUTO_INCREMENT,
            `he_year` varchar(10) NOT NULL DEFAULT '',
            `he_items` text NOT NULL,
            `he_order` int(11) NOT NULL DEFAULT 0,
            `he_use` tinyint(4) NOT NULL DEFAULT 1,
            `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY (`he_id`),
            KEY `he_order` (`he_order`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
    ", false);

    return tbc_history_tables_exist();
}

function tbc_history_default_config()
{
    return array(
        'hc_label_en' => 'HISTORY',
        'hc_title_bold' => '더브레인코어',
        'hc_title_text' => '가 걸어온 길',
        'hc_desc' => "대전·세종을 기반으로 본원 6개 전문관과 5개 분원을 운영하며\n하나의 교육 브랜드로 성장해 온 더브레인코어의 발자취입니다.",
    );
}

function tbc_history_get_config()
{
    if (!tbc_history_tables_exist()) {
        return tbc_history_default_config();
    }

    $row = sql_fetch(" select * from " . tbc_history_config_table() . " where hc_id = " . (int) TBC_HISTORY_CONFIG_ID . " ");
    if (!$row) {
        return tbc_history_default_config();
    }

    return $row;
}

function tbc_history_save_config($data)
{
    tbc_history_ensure_tables();

    $defaults = tbc_history_default_config();
    $fields = array(
        'hc_label_en' => isset($data['hc_label_en']) ? $data['hc_label_en'] : $defaults['hc_label_en'],
        'hc_title_bold' => isset($data['hc_title_bold']) ? $data['hc_title_bold'] : $defaults['hc_title_bold'],
        'hc_title_text' => isset($data['hc_title_text']) ? $data['hc_title_text'] : $defaults['hc_title_text'],
        'hc_desc' => isset($data['hc_desc']) ? $data['hc_desc'] : $defaults['hc_desc'],
    );

    $sets = array();
    foreach ($fields as $key => $value) {
        $sets[] = "`{$key}` = '" . sql_real_escape_string($value) . "'";
    }
    $sets[] = "`updated_at` = '" . G5_TIME_YMDHIS . "'";

    $exists = sql_fetch(" select hc_id from " . tbc_history_config_table() . " where hc_id = " . (int) TBC_HISTORY_CONFIG_ID . " ");
    if ($exists) {
        sql_query(" update " . tbc_history_config_table() . " set " . implode(', ', $sets) . " where hc_id = " . (int) TBC_HISTORY_CONFIG_ID . " ");
    } else {
        sql_query(" insert into " . tbc_history_config_table() . " set hc_id = " . (int) TBC_HISTORY_CONFIG_ID . ", " . implode(', ', $sets));
    }

    return array('ok' => true);
}

function tbc_history_parse_items($text)
{
    $lines = preg_split('/\r\n|\r|\n/', (string) $text);
    $items = array();

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line !== '') {
            $items[] = $line;
        }
    }

    return $items;
}

function tbc_history_get_list($keyword = '', $public_only = false)
{
    if (!tbc_history_tables_exist()) {
        return array();
    }

    $where = ' where 1=1 ';
    if ($public_only) {
        $where .= ' and he_use = 1 ';
    }
    if ($keyword !== '') {
        $keyword = sql_real_escape_string($keyword);
        $where .= " and (he_year like '%{$keyword}%' or he_items like '%{$keyword}%') ";
    }

    $result = sql_query("
        select *
        from " . tbc_history_entry_table() . "
        {$where}
        order by he_order asc, he_id asc
    ");

    $rows = array();
    while ($row = sql_fetch_array($result)) {
        $row['items'] = tbc_history_parse_items($row['he_items']);
        $rows[] = $row;
    }

    return $rows;
}

function tbc_history_get_public_entries()
{
    return tbc_history_get_list('', true);
}

function tbc_history_get($he_id)
{
    if (!tbc_history_tables_exist()) {
        return null;
    }

    $he_id = (int) $he_id;
    $row = sql_fetch(" select * from " . tbc_history_entry_table() . " where he_id = {$he_id} ");
    if (!$row) {
        return null;
    }

    $row['items'] = tbc_history_parse_items($row['he_items']);
    return $row;
}

function tbc_history_next_order()
{
    $row = sql_fetch(" select ifnull(max(he_order), 0) as max_order from " . tbc_history_entry_table() . " ");
    return (int) $row['max_order'] + 1;
}

function tbc_history_normalize_orders()
{
    $items = tbc_history_get_list();
    $order = 0;
    foreach ($items as $item) {
        $order++;
        sql_query(" update " . tbc_history_entry_table() . " set he_order = {$order} where he_id = " . (int) $item['he_id'] . " ");
    }
}

function tbc_history_save_entry($data, $he_id = 0)
{
    tbc_history_ensure_tables();

    $he_id = (int) $he_id;
    $fields = array(
        'he_year' => isset($data['he_year']) ? trim($data['he_year']) : '',
        'he_items' => isset($data['he_items']) ? $data['he_items'] : '',
        'he_use' => !empty($data['he_use']) ? 1 : 0,
    );

    if ($fields['he_year'] === '') {
        return array('ok' => false, 'message' => '연도를 입력해 주세요.');
    }

    if (!tbc_history_parse_items($fields['he_items'])) {
        return array('ok' => false, 'message' => '연혁 내용을 한 줄에 하나씩 입력해 주세요.');
    }

    $sets = array();
    foreach ($fields as $key => $value) {
        $sets[] = "`{$key}` = '" . sql_real_escape_string($value) . "'";
    }
    $sets[] = "`updated_at` = '" . G5_TIME_YMDHIS . "'";

    if ($he_id > 0) {
        sql_query(" update " . tbc_history_entry_table() . " set " . implode(', ', $sets) . " where he_id = {$he_id} ");
        return array('ok' => true, 'he_id' => $he_id);
    }

    $order = tbc_history_next_order();
    $sets[] = "`he_order` = {$order}";
    sql_query(" insert into " . tbc_history_entry_table() . " set " . implode(', ', $sets));

    return array('ok' => true, 'he_id' => (int) sql_insert_id());
}

function tbc_history_move($he_id, $direction)
{
    $he_id = (int) $he_id;
    $item = tbc_history_get($he_id);
    if (!$item) {
        return array('ok' => false, 'message' => '연혁 항목을 찾을 수 없습니다.');
    }

    $list = tbc_history_get_list();
    $current_index = -1;
    foreach ($list as $i => $row) {
        if ((int) $row['he_id'] === $he_id) {
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

    $current_order = (int) $list[$current_index]['he_order'];
    $target_order = (int) $list[$swap]['he_order'];

    sql_query(" update " . tbc_history_entry_table() . " set he_order = {$target_order} where he_id = {$he_id} ");
    sql_query(" update " . tbc_history_entry_table() . " set he_order = {$current_order} where he_id = " . (int) $list[$swap]['he_id'] . " ");
    tbc_history_normalize_orders();

    return array('ok' => true);
}

function tbc_history_delete($he_id)
{
    $he_id = (int) $he_id;
    if (!tbc_history_get($he_id)) {
        return array('ok' => false, 'message' => '연혁 항목을 찾을 수 없습니다.');
    }

    sql_query(" delete from " . tbc_history_entry_table() . " where he_id = {$he_id} ");
    tbc_history_normalize_orders();

    return array('ok' => true);
}

function tbc_history_seed_defaults()
{
    if (!tbc_history_ensure_tables()) {
        return false;
    }

    $config = sql_fetch(" select hc_id from " . tbc_history_config_table() . " where hc_id = " . (int) TBC_HISTORY_CONFIG_ID . " ");
    if (!$config) {
        $defaults = tbc_history_default_config();
        tbc_history_save_config($defaults);
    }

    $count = sql_fetch(" select count(*) as cnt from " . tbc_history_entry_table() . " ");
    if ((int) $count['cnt'] > 0) {
        return true;
    }

    $samples = array(
        array('year' => '2024', 'items' => "대전·세종 지역 분원 확장 운영\n본원 6개 전문관 통합 브랜드 운영 강화"),
        array('year' => '2023', 'items' => "세종 아름·새롬 분원 오픈\n강사진·교육과정 시스템 고도화"),
        array('year' => '2022', 'items' => "노은·관평·관저 분원 오픈\n과학관·알파·풀스토리 전문관 운영"),
        array('year' => '2021', 'items' => "본원 초등·중등·고등 전문관 체계 정립"),
        array('year' => '2020', 'items' => "더브레인코어 브랜드 론칭\n둔산 본원 교육 시스템 구축"),
    );

    foreach ($samples as $sample) {
        tbc_history_save_entry(array(
            'he_year' => $sample['year'],
            'he_items' => $sample['items'],
            'he_use' => 1,
        ));
    }

    return true;
}
