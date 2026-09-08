<?php
/**
 * TBC 분원(본원·분원) 관리
 */
if (!defined('_GNUBOARD_')) exit;

if (!function_exists('tbc_media_url')) {
    include_once(G5_THEME_PATH . '/tbc.lib.php');
}

function tbc_academy_table()
{
    return G5_TABLE_PREFIX . 'tbc_academy';
}

function tbc_academy_file_path()
{
    return G5_DATA_PATH . '/tbc/academy';
}

function tbc_academy_types()
{
    return array(
        'main' => '본원',
        'branch' => '분원',
    );
}

function tbc_academy_type_label($type)
{
    $types = tbc_academy_types();
    return isset($types[$type]) ? $types[$type] : $type;
}

function tbc_academy_ensure_dir()
{
    $dir = tbc_academy_file_path();
    if (!is_dir($dir)) {
        @mkdir($dir, G5_DIR_PERMISSION, true);
        @chmod($dir, G5_DIR_PERMISSION);
    }

    $index_file = $dir . '/index.php';
    if (!is_file($index_file)) {
        @file_put_contents($index_file, '');
        @chmod($index_file, G5_FILE_PERMISSION);
    }
}

function tbc_academy_tables_exist()
{
    $row = sql_fetch(" show tables like '" . tbc_academy_table() . "' ");
    return (bool) $row;
}

function tbc_academy_ensure_tables()
{
    if (!tbc_academy_tables_exist()) {
        $charset = (defined('G5_DB_CHARSET') && G5_DB_CHARSET) ? G5_DB_CHARSET : 'utf8';

        sql_query("
            CREATE TABLE IF NOT EXISTS `" . tbc_academy_table() . "` (
                `ac_id` int(11) NOT NULL AUTO_INCREMENT,
                `ac_type` varchar(20) NOT NULL DEFAULT 'branch',
                `ac_name` varchar(120) NOT NULL DEFAULT '',
                `ac_region` varchar(120) NOT NULL DEFAULT '',
                `ac_address` varchar(255) NOT NULL DEFAULT '',
                `ac_phone` varchar(40) NOT NULL DEFAULT '',
                `ac_consult_phone` varchar(40) NOT NULL DEFAULT '',
                `ac_consult_hours` varchar(255) NOT NULL DEFAULT '',
                `ac_concept` text NOT NULL,
                `ac_map_url` varchar(500) NOT NULL DEFAULT '',
                `ac_image` varchar(255) NOT NULL DEFAULT '',
                `ac_order` int(11) NOT NULL DEFAULT 0,
                `ac_use` tinyint(4) NOT NULL DEFAULT 1,
                `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                PRIMARY KEY (`ac_id`),
                KEY `ac_type` (`ac_type`),
                KEY `ac_order` (`ac_order`)
            ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
        ", false);
    }

    tbc_academy_upgrade_schema();

    return tbc_academy_tables_exist();
}

function tbc_academy_column_exists($column)
{
    $column = preg_replace('/[^a-z_]/', '', $column);
    $row = sql_fetch(" show columns from `" . tbc_academy_table() . "` like '{$column}' ");
    return (bool) $row;
}

function tbc_academy_upgrade_schema()
{
    if (!tbc_academy_tables_exist()) {
        return;
    }

    $table = tbc_academy_table();
    $default_hours = '평일 14:00~22:00 · 주말 및 공휴일 10:00~22:00';

    if (!tbc_academy_column_exists('ac_consult_phone')) {
        sql_query(" alter table `{$table}` add `ac_consult_phone` varchar(40) not null default '' after `ac_phone` ", false);
    }
    if (!tbc_academy_column_exists('ac_consult_hours')) {
        sql_query(" alter table `{$table}` add `ac_consult_hours` varchar(255) not null default '' after `ac_consult_phone` ", false);
    }

    sql_query(" update `{$table}` set ac_consult_phone = ac_phone where ac_consult_phone = '' and ac_phone != '' ");
    sql_query(" update `{$table}` set ac_consult_hours = '" . sql_real_escape_string($default_hours) . "' where ac_consult_hours = '' ");
}

function tbc_academy_allowed_image_ext($filename)
{
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $allowed = array('jpg', 'jpeg', 'png', 'webp', 'gif');
    return in_array($ext, $allowed, true) ? ($ext === 'jpeg' ? 'jpg' : $ext) : '';
}

function tbc_academy_validate_upload_file($file)
{
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return array('ok' => false, 'message' => '업로드된 파일이 없습니다.');
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return array('ok' => false, 'message' => '파일 업로드 중 오류가 발생했습니다.');
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        return array('ok' => false, 'message' => '파일 크기는 5MB 이하여야 합니다.');
    }

    $ext = tbc_academy_allowed_image_ext($file['name']);
    if (!$ext) {
        return array('ok' => false, 'message' => 'jpg, png, webp, gif 파일만 업로드할 수 있습니다.');
    }

    return array('ok' => true, 'ext' => $ext);
}

function tbc_academy_store_file($file)
{
    $check = tbc_academy_validate_upload_file($file);
    if (!$check['ok']) {
        return $check;
    }

    tbc_academy_ensure_dir();

    $filename = 'academy_' . date('YmdHis') . '_' . substr(md5(uniqid('', true)), 0, 8) . '.' . $check['ext'];
    $dest = tbc_academy_file_path() . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return array('ok' => false, 'message' => '파일 저장에 실패했습니다.');
    }

    @chmod($dest, G5_FILE_PERMISSION);

    return array('ok' => true, 'filename' => $filename);
}

function tbc_academy_delete_file($filename)
{
    if (!$filename) {
        return;
    }

    $path = tbc_academy_file_path() . '/' . $filename;
    if (is_file($path)) {
        @unlink($path);
    }
}

function tbc_academy_theme_fallback_image($ac_id)
{
    $images = array('1.jpg', '2.jpg', '3.jpg', '4.jpg');
    $index = max(0, ((int) $ac_id - 1) % count($images));
    return G5_THEME_URL . '/img/sub/' . $images[$index];
}

function tbc_academy_image_url($filename, $ac_id = 0)
{
    $fallback = tbc_academy_theme_fallback_image($ac_id);
    return tbc_media_resolve_url('academy', $filename, $fallback);
}

function tbc_academy_copy_theme_file($source_name, $dest_name)
{
    $source = G5_THEME_PATH . '/img/sub/' . $source_name;
    $dest = tbc_academy_file_path() . '/' . $dest_name;
    if (is_file($source) && !is_file($dest)) {
        if (@copy($source, $dest)) {
            @chmod($dest, G5_FILE_PERMISSION);
            return true;
        }
    }
    return is_file($dest);
}

function tbc_academy_get($ac_id)
{
    if (!tbc_academy_tables_exist()) {
        return null;
    }

    $ac_id = (int) $ac_id;
    $row = sql_fetch(" select * from " . tbc_academy_table() . " where ac_id = {$ac_id} ");
    if (!$row) {
        return null;
    }

    $row['type_name'] = tbc_academy_type_label($row['ac_type']);
    $row['image_url'] = tbc_academy_image_url($row['ac_image'], $row['ac_id']);

    return $row;
}

function tbc_academy_get_list($type = '', $keyword = '', $public_only = false)
{
    if (!tbc_academy_tables_exist()) {
        return array();
    }

    $where = ' where 1=1 ';
    if ($public_only) {
        $where .= ' and ac_use = 1 ';
    }
    if ($type === 'main' || $type === 'branch') {
        $type = sql_real_escape_string($type);
        $where .= " and ac_type = '{$type}' ";
    }
    if ($keyword !== '') {
        $keyword = sql_real_escape_string($keyword);
        $where .= " and (ac_name like '%{$keyword}%' or ac_region like '%{$keyword}%' or ac_address like '%{$keyword}%') ";
    }

    $result = sql_query("
        select *
        from " . tbc_academy_table() . "
        {$where}
        order by ac_order asc, ac_id asc
    ");

    $rows = array();
    while ($row = sql_fetch_array($result)) {
        $row['type_name'] = tbc_academy_type_label($row['ac_type']);
        $row['image_url'] = tbc_academy_image_url($row['ac_image'], $row['ac_id']);
        $rows[] = $row;
    }

    return $rows;
}

function tbc_academy_next_order()
{
    $row = sql_fetch(" select ifnull(max(ac_order), 0) as max_order from " . tbc_academy_table() . " ");
    return (int) $row['max_order'] + 1;
}

function tbc_academy_normalize_orders()
{
    $items = tbc_academy_get_list();
    $order = 0;
    foreach ($items as $item) {
        $order++;
        sql_query(" update " . tbc_academy_table() . " set ac_order = {$order} where ac_id = " . (int) $item['ac_id'] . " ");
    }
}

function tbc_academy_save($data, $ac_id = 0)
{
    tbc_academy_ensure_tables();

    $ac_id = (int) $ac_id;
    $type = isset($data['ac_type']) ? $data['ac_type'] : 'branch';
    if (!in_array($type, array('main', 'branch'), true)) {
        $type = 'branch';
    }

    $fields = array(
        'ac_type' => $type,
        'ac_name' => isset($data['ac_name']) ? $data['ac_name'] : '',
        'ac_region' => isset($data['ac_region']) ? $data['ac_region'] : '',
        'ac_address' => isset($data['ac_address']) ? $data['ac_address'] : '',
        'ac_phone' => isset($data['ac_phone']) ? $data['ac_phone'] : '',
        'ac_consult_phone' => isset($data['ac_consult_phone']) ? $data['ac_consult_phone'] : '',
        'ac_consult_hours' => isset($data['ac_consult_hours']) ? $data['ac_consult_hours'] : '',
        'ac_concept' => isset($data['ac_concept']) ? $data['ac_concept'] : '',
        'ac_map_url' => isset($data['ac_map_url']) ? $data['ac_map_url'] : '',
        'ac_use' => !empty($data['ac_use']) ? 1 : 0,
    );

    $sets = array();
    foreach ($fields as $key => $value) {
        $sets[] = "`{$key}` = '" . sql_real_escape_string($value) . "'";
    }
    $sets[] = "`updated_at` = '" . G5_TIME_YMDHIS . "'";

    if ($ac_id > 0) {
        sql_query(" update " . tbc_academy_table() . " set " . implode(', ', $sets) . " where ac_id = {$ac_id} ");
        return array('ok' => true, 'ac_id' => $ac_id);
    }

    $order = tbc_academy_next_order();
    $sets[] = "`ac_order` = {$order}";

    sql_query(" insert into " . tbc_academy_table() . " set " . implode(', ', $sets));
    return array('ok' => true, 'ac_id' => (int) sql_insert_id());
}

function tbc_academy_update_image($ac_id, $file)
{
    $item = tbc_academy_get($ac_id);
    if (!$item) {
        return array('ok' => false, 'message' => '분원 정보를 찾을 수 없습니다.');
    }

    $result = tbc_academy_store_file($file);
    if (!$result['ok']) {
        return $result;
    }

    if ($item['ac_image']) {
        tbc_academy_delete_file($item['ac_image']);
    }

    $filename = sql_real_escape_string($result['filename']);
    sql_query("
        update " . tbc_academy_table() . "
        set ac_image = '{$filename}', updated_at = '" . G5_TIME_YMDHIS . "'
        where ac_id = " . (int) $ac_id . "
    ");

    return array('ok' => true, 'filename' => $result['filename']);
}

function tbc_academy_move($ac_id, $direction, $scope = '')
{
    $ac_id = (int) $ac_id;
    $item = tbc_academy_get($ac_id);
    if (!$item) {
        return array('ok' => false, 'message' => '분원 정보를 찾을 수 없습니다.');
    }

    $list = tbc_academy_get_list($scope);
    $current_index = -1;
    foreach ($list as $i => $row) {
        if ((int) $row['ac_id'] === $ac_id) {
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

    $current_order = (int) $list[$current_index]['ac_order'];
    $target_order = (int) $list[$swap]['ac_order'];

    sql_query(" update " . tbc_academy_table() . " set ac_order = {$target_order} where ac_id = {$ac_id} ");
    sql_query(" update " . tbc_academy_table() . " set ac_order = {$current_order} where ac_id = " . (int) $list[$swap]['ac_id'] . " ");
    tbc_academy_normalize_orders();

    return array('ok' => true);
}

function tbc_academy_delete($ac_id)
{
    $ac_id = (int) $ac_id;
    $item = tbc_academy_get($ac_id);
    if (!$item) {
        return array('ok' => false, 'message' => '분원 정보를 찾을 수 없습니다.');
    }

    if ($item['ac_image']) {
        tbc_academy_delete_file($item['ac_image']);
    }

    sql_query(" delete from " . tbc_academy_table() . " where ac_id = {$ac_id} ");
    tbc_academy_normalize_orders();

    return array('ok' => true);
}

function tbc_academy_short_name($name)
{
    $name = trim((string) $name);
    return preg_replace('/^더브코\s*/u', '', $name);
}

function tbc_academy_public_names($type = '')
{
    $items = tbc_academy_get_list($type, '', true);
    $names = array();
    foreach ($items as $item) {
        $names[] = tbc_academy_short_name($item['ac_name']);
    }
    return $names;
}

function tbc_academy_format_names_html($names)
{
    if (!$names) {
        return '';
    }

    $count = count($names);
    if ($count <= 3) {
        return htmlspecialchars(implode(' · ', $names), ENT_QUOTES, 'UTF-8');
    }

    $mid = (int) ceil($count / 2);
    $line1 = implode(' · ', array_slice($names, 0, $mid));
    $line2 = implode(' · ', array_slice($names, $mid));

    return htmlspecialchars($line1, ENT_QUOTES, 'UTF-8') . '<br>' . htmlspecialchars($line2, ENT_QUOTES, 'UTF-8');
}

function tbc_academy_main_summary()
{
    $names = tbc_academy_public_names('main');
    return array(
        'count' => count($names),
        'names_html' => tbc_academy_format_names_html($names),
    );
}

function tbc_academy_branch_summary()
{
    $names = tbc_academy_public_names('branch');
    return array(
        'count' => count($names),
        'names_html' => tbc_academy_format_names_html($names),
    );
}

function tbc_academy_total_public_count()
{
    return count(tbc_academy_get_list('', '', true));
}

function tbc_academy_get_consult_phone($row)
{
    $phone = trim((string) $row['ac_consult_phone']);
    if ($phone === '') {
        $phone = trim((string) $row['ac_phone']);
    }
    return $phone;
}

function tbc_academy_get_consult_slides()
{
    tbc_academy_ensure_tables();

    $items = tbc_academy_get_list('', '', true);
    $slides = array();

    foreach ($items as $item) {
        $phone = tbc_academy_get_consult_phone($item);
        $hours = trim((string) $item['ac_consult_hours']);
        if ($phone === '' && $hours === '') {
            continue;
        }

        $slides[] = array(
            'name' => tbc_academy_short_name($item['ac_name']),
            'phone' => $phone,
            'hours' => $hours,
        );
    }

    return $slides;
}

function tbc_academy_seed_defaults()
{
    if (!tbc_academy_ensure_tables()) {
        return false;
    }

    tbc_academy_ensure_dir();

    $count = sql_fetch(" select count(*) as cnt from " . tbc_academy_table() . " ");
    if ((int) $count['cnt'] > 0) {
        return true;
    }

    $items = array(
        array('type' => 'main', 'name' => '더브코 초등관', 'region' => '대전 둔산동', 'address' => '대전광역시 서구 둔산동', 'phone' => '042-000-0000', 'concept' => '본원 초등부 수업을 담당합니다.', 'map' => 'https://map.naver.com/p/search/%EB%8C%80%EC%A0%84%20%EB%91%94%EC%82%B0%EB%8F%99', 'img' => '1.jpg'),
        array('type' => 'main', 'name' => '더브코 중등관', 'region' => '대전 둔산동', 'address' => '대전광역시 서구 둔산동', 'phone' => '042-000-0000', 'concept' => '본원 중등부 수업을 담당합니다.', 'map' => 'https://map.naver.com/p/search/%EB%8C%80%EC%A0%84%20%EB%91%94%EC%82%B0%EB%8F%99', 'img' => '2.jpg'),
        array('type' => 'main', 'name' => '더브코 고등관', 'region' => '대전 둔산동', 'address' => '대전광역시 서구 둔산동', 'phone' => '042-000-0000', 'concept' => '본원 고등부 수업을 담당합니다.', 'map' => 'https://map.naver.com/p/search/%EB%8C%80%EC%A0%84%20%EB%91%94%EC%82%B0%EB%8F%99', 'img' => '3.jpg'),
        array('type' => 'main', 'name' => '더브코 과학관', 'region' => '대전 둔산동', 'address' => '대전광역시 서구 둔산동', 'phone' => '042-000-0000', 'concept' => '본원 과학 전문 수업을 담당합니다.', 'map' => 'https://map.naver.com/p/search/%EB%8C%80%EC%A0%84%20%EB%91%94%EC%82%B0%EB%8F%99', 'img' => '4.jpg'),
        array('type' => 'main', 'name' => '더브코 알파', 'region' => '대전 둔산동', 'address' => '대전광역시 서구 둔산동', 'phone' => '042-000-0000', 'concept' => '더브코 단과 선생님들이 수업하는 공간입니다.', 'map' => 'https://map.naver.com/p/search/%EB%8C%80%EC%A0%84%20%EB%91%94%EC%82%B0%EB%8F%99', 'img' => '1.jpg'),
        array('type' => 'main', 'name' => '더브코 풀스토리', 'region' => '대전 둔산동', 'address' => '대전광역시 서구 둔산동', 'phone' => '042-000-0000', 'concept' => '영재고·특목고 대비를 중심으로 운영합니다.', 'map' => 'https://map.naver.com/p/search/%EB%8C%80%EC%A0%84%20%EB%91%94%EC%82%B0%EB%8F%99', 'img' => '2.jpg'),
        array('type' => 'branch', 'name' => '더브코 노은관', 'region' => '대전 노은동', 'address' => '대전광역시 유성구 노은동', 'phone' => '042-000-0000', 'concept' => '대전 노은 지역 학생을 위한 더브레인코어 분원입니다.', 'map' => 'https://map.naver.com/p/search/%EB%8C%80%EC%A0%84%20%EB%85%B8%EC%9D%80%EB%8F%99', 'img' => '3.jpg'),
        array('type' => 'branch', 'name' => '더브코 관평관', 'region' => '대전 관평동', 'address' => '대전광역시 유성구 관평동', 'phone' => '042-000-0000', 'concept' => '대전 관평 지역 학생을 위한 더브레인코어 분원입니다.', 'map' => 'https://map.naver.com/p/search/%EB%8C%80%EC%A0%84%20%EA%B4%80%ED%8F%89%EB%8F%99', 'img' => '4.jpg'),
        array('type' => 'branch', 'name' => '더브코 관저관', 'region' => '대전 관저동', 'address' => '대전광역시 서구 관저동', 'phone' => '042-000-0000', 'concept' => '대전 관저 지역 학생을 위한 더브레인코어 분원입니다.', 'map' => 'https://map.naver.com/p/search/%EB%8C%80%EC%A0%84%20%EA%B4%80%EC%A0%80%EB%8F%99', 'img' => '1.jpg'),
        array('type' => 'branch', 'name' => '더브코 세종아름관', 'region' => '세종 아름동', 'address' => '세종특별자치시 아름동', 'phone' => '042-000-0000', 'concept' => '세종 아름 지역 학생을 위한 더브레인코어 분원입니다.', 'map' => 'https://map.naver.com/p/search/%EC%84%B8%EC%A2%85%20%EC%95%84%EB%A6%84%EB%8F%99', 'img' => '2.jpg'),
        array('type' => 'branch', 'name' => '더브코 세종새롬관', 'region' => '세종 새롬동', 'address' => '세종특별자치시 새롬동', 'phone' => '042-000-0000', 'concept' => '세종 새롬 지역 학생을 위한 더브레인코어 분원입니다.', 'map' => 'https://map.naver.com/p/search/%EC%84%B8%EC%A2%85%20%EC%83%88%EB%A1%AC%EB%8F%99', 'img' => '3.jpg'),
    );

    $default_hours = '평일 14:00~22:00 · 주말 및 공휴일 10:00~22:00';
    $order = 0;
    foreach ($items as $item) {
        $order++;
        $image = '';
        $dest = 'seed_' . $order . '_' . basename($item['img']);
        if (tbc_academy_copy_theme_file($item['img'], $dest)) {
            $image = $dest;
        }

        $sets = array(
            "ac_type = '" . sql_real_escape_string($item['type']) . "'",
            "ac_name = '" . sql_real_escape_string($item['name']) . "'",
            "ac_region = '" . sql_real_escape_string($item['region']) . "'",
            "ac_address = '" . sql_real_escape_string($item['address']) . "'",
            "ac_phone = '" . sql_real_escape_string($item['phone']) . "'",
            "ac_consult_phone = '" . sql_real_escape_string($item['phone']) . "'",
            "ac_consult_hours = '" . sql_real_escape_string($default_hours) . "'",
            "ac_concept = '" . sql_real_escape_string($item['concept']) . "'",
            "ac_map_url = '" . sql_real_escape_string($item['map']) . "'",
            "ac_image = '" . sql_real_escape_string($image) . "'",
            "ac_order = {$order}",
            "ac_use = 1",
            "updated_at = '" . G5_TIME_YMDHIS . "'",
        );

        sql_query(" insert into " . tbc_academy_table() . " set " . implode(', ', $sets));
    }

    return true;
}
