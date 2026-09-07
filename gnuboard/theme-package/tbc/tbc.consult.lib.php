<?php
/**
 * TBC 상담신청 — 관별 구글폼 링크
 */
if (!defined('_GNUBOARD_')) exit;

if (!function_exists('tbc_academy_get')) {
    include_once(G5_THEME_PATH . '/tbc.academy.lib.php');
}

function tbc_consult_form_table()
{
    return G5_TABLE_PREFIX . 'tbc_consult_form';
}

function tbc_consult_tables_exist()
{
    $row = sql_fetch(" show tables like '" . tbc_consult_form_table() . "' ");
    return (bool) $row;
}

function tbc_consult_ensure_tables()
{
    if (tbc_consult_tables_exist()) {
        return true;
    }

    $charset = (defined('G5_DB_CHARSET') && G5_DB_CHARSET) ? G5_DB_CHARSET : 'utf8';

    sql_query("
        CREATE TABLE IF NOT EXISTS `" . tbc_consult_form_table() . "` (
            `cf_id` int(11) NOT NULL AUTO_INCREMENT,
            `ac_id` int(11) NOT NULL DEFAULT 0,
            `cf_title` varchar(255) NOT NULL DEFAULT '',
            `cf_url` varchar(500) NOT NULL DEFAULT '',
            `cf_order` int(11) NOT NULL DEFAULT 0,
            `cf_use` tinyint(4) NOT NULL DEFAULT 1,
            `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY (`cf_id`),
            KEY `ac_id` (`ac_id`),
            KEY `cf_order` (`cf_order`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
    ", false);

    return tbc_consult_tables_exist();
}

function tbc_consult_normalize_url($url)
{
    $url = trim((string) $url);
    if ($url === '') {
        return '';
    }
    if (!preg_match('#^https?://#i', $url)) {
        $url = 'https://' . $url;
    }
    return $url;
}

function tbc_consult_is_valid_url($url)
{
    $url = tbc_consult_normalize_url($url);
    if ($url === '' || !filter_var($url, FILTER_VALIDATE_URL)) {
        return false;
    }

    return (bool) preg_match('#^https?://#i', $url);
}

function tbc_consult_get($cf_id)
{
    if (!tbc_consult_tables_exist()) {
        return null;
    }

    $cf_id = (int) $cf_id;
    return sql_fetch(" select * from " . tbc_consult_form_table() . " where cf_id = {$cf_id} ");
}

function tbc_consult_get_list($filters = array())
{
    if (!tbc_consult_tables_exist()) {
        return array();
    }

    $where = ' where 1=1 ';
    $ac_id = isset($filters['ac_id']) ? (int) $filters['ac_id'] : 0;
    $keyword = isset($filters['q']) ? trim($filters['q']) : '';
    $public_only = !empty($filters['public_only']);

    if ($ac_id > 0) {
        $where .= " and cf.ac_id = {$ac_id} ";
    }
    if ($public_only) {
        $where .= ' and cf.cf_use = 1 ';
    }
    if ($keyword !== '') {
        $keyword = sql_real_escape_string($keyword);
        $where .= " and (cf.cf_title like '%{$keyword}%' or cf.cf_url like '%{$keyword}%' or a.ac_name like '%{$keyword}%') ";
    }

    $result = sql_query("
        select cf.*, a.ac_name, a.ac_type, a.ac_region
        from " . tbc_consult_form_table() . " cf
        left join " . tbc_academy_table() . " a on a.ac_id = cf.ac_id
        {$where}
        order by a.ac_order asc, a.ac_id asc, cf.cf_order asc, cf.cf_id asc
    ");

    $rows = array();
    while ($row = sql_fetch_array($result)) {
        $rows[] = $row;
    }

    return $rows;
}

function tbc_consult_get_by_academy($ac_id, $public_only = true)
{
    return tbc_consult_get_list(array(
        'ac_id' => (int) $ac_id,
        'public_only' => $public_only,
    ));
}

function tbc_consult_grouped_by_academy($public_only = true)
{
    $academies = tbc_academy_get_list('', '', $public_only);
    $groups = array();

    foreach ($academies as $academy) {
        $forms = tbc_consult_get_by_academy($academy['ac_id'], $public_only);
        $groups[] = array(
            'academy' => $academy,
            'forms' => $forms,
        );
    }

    return $groups;
}

function tbc_consult_next_order($ac_id)
{
    $ac_id = (int) $ac_id;
    $row = sql_fetch(" select ifnull(max(cf_order), 0) as max_order from " . tbc_consult_form_table() . " where ac_id = {$ac_id} ");
    return (int) $row['max_order'] + 1;
}

function tbc_consult_normalize_orders($ac_id = 0)
{
    $ac_id = (int) $ac_id;
    $where = $ac_id > 0 ? " where ac_id = {$ac_id} " : '';
    $result = sql_query(" select cf_id, ac_id from " . tbc_consult_form_table() . " {$where} order by ac_id asc, cf_order asc, cf_id asc ");

    $current_ac = 0;
    $order = 0;
    while ($row = sql_fetch_array($result)) {
        if ($current_ac !== (int) $row['ac_id']) {
            $current_ac = (int) $row['ac_id'];
            $order = 0;
        }
        $order++;
        sql_query(" update " . tbc_consult_form_table() . " set cf_order = {$order} where cf_id = " . (int) $row['cf_id'] . " ");
    }
}

function tbc_consult_save($data, $cf_id = 0)
{
    tbc_consult_ensure_tables();
    tbc_academy_ensure_tables();

    $cf_id = (int) $cf_id;
    $ac_id = isset($data['ac_id']) ? (int) $data['ac_id'] : 0;
    $title = isset($data['cf_title']) ? trim($data['cf_title']) : '';
    $url = tbc_consult_normalize_url(isset($data['cf_url']) ? $data['cf_url'] : '');
    $use = !empty($data['cf_use']) ? 1 : 0;

    if ($ac_id <= 0 || !tbc_academy_get($ac_id)) {
        return array('ok' => false, 'message' => '분원을 선택해 주세요.');
    }
    if ($title === '') {
        return array('ok' => false, 'message' => '폼 제목을 입력해 주세요.');
    }
    if (!tbc_consult_is_valid_url($url)) {
        return array('ok' => false, 'message' => '올바른 구글폼 URL을 입력해 주세요.');
    }

    $title_sql = sql_real_escape_string($title);
    $url_sql = sql_real_escape_string($url);

    if ($cf_id > 0) {
        sql_query("
            update " . tbc_consult_form_table() . " set
                ac_id = {$ac_id},
                cf_title = '{$title_sql}',
                cf_url = '{$url_sql}',
                cf_use = {$use},
                updated_at = '" . G5_TIME_YMDHIS . "'
            where cf_id = {$cf_id}
        ");
        tbc_consult_normalize_orders($ac_id);
        return array('ok' => true, 'cf_id' => $cf_id);
    }

    $order = tbc_consult_next_order($ac_id);
    sql_query("
        insert into " . tbc_consult_form_table() . " set
            ac_id = {$ac_id},
            cf_title = '{$title_sql}',
            cf_url = '{$url_sql}',
            cf_order = {$order},
            cf_use = {$use},
            updated_at = '" . G5_TIME_YMDHIS . "'
    ");

    return array('ok' => true, 'cf_id' => (int) sql_insert_id());
}

function tbc_consult_delete($cf_id)
{
    if (!tbc_consult_tables_exist()) {
        return array('ok' => false, 'message' => '테이블이 없습니다.');
    }

    $cf_id = (int) $cf_id;
    $row = tbc_consult_get($cf_id);
    if (!$row) {
        return array('ok' => false, 'message' => '항목을 찾을 수 없습니다.');
    }

    sql_query(" delete from " . tbc_consult_form_table() . " where cf_id = {$cf_id} ");
    tbc_consult_normalize_orders((int) $row['ac_id']);

    return array('ok' => true);
}

function tbc_consult_move($cf_id, $direction)
{
    if (!tbc_consult_tables_exist()) {
        return array('ok' => false, 'message' => '테이블이 없습니다.');
    }

    $cf_id = (int) $cf_id;
    $row = tbc_consult_get($cf_id);
    if (!$row) {
        return array('ok' => false, 'message' => '항목을 찾을 수 없습니다.');
    }

    $ac_id = (int) $row['ac_id'];
    $direction = $direction === 'down' ? 'down' : 'up';

    $items = tbc_consult_get_by_academy($ac_id, false);
    $index = -1;
    foreach ($items as $i => $item) {
        if ((int) $item['cf_id'] === $cf_id) {
            $index = $i;
            break;
        }
    }

    if ($index < 0) {
        return array('ok' => false, 'message' => '순서를 변경할 수 없습니다.');
    }

    $swap = $direction === 'up' ? $index - 1 : $index + 1;
    if ($swap < 0 || $swap >= count($items)) {
        return array('ok' => false, 'message' => '더 이상 이동할 수 없습니다.');
    }

    $a = (int) $items[$index]['cf_id'];
    $b = (int) $items[$swap]['cf_id'];
    $order_a = (int) $items[$index]['cf_order'];
    $order_b = (int) $items[$swap]['cf_order'];

    sql_query(" update " . tbc_consult_form_table() . " set cf_order = {$order_b} where cf_id = {$a} ");
    sql_query(" update " . tbc_consult_form_table() . " set cf_order = {$order_a} where cf_id = {$b} ");
    tbc_consult_normalize_orders($ac_id);

    return array('ok' => true);
}

function tbc_consult_seed_defaults()
{
    return tbc_consult_ensure_tables();
}

function tbc_consult_phone($academy)
{
    if (!$academy) {
        return '';
    }
    return tbc_academy_get_consult_phone($academy);
}
