<?php
/**
 * TBC 강사진 관리
 */
if (!defined('_GNUBOARD_')) exit;

if (!function_exists('tbc_media_url')) {
    include_once(G5_THEME_PATH . '/tbc.lib.php');
}

function tbc_teacher_table()
{
    return G5_TABLE_PREFIX . 'tbc_teacher';
}

function tbc_teacher_image_table()
{
    return G5_TABLE_PREFIX . 'tbc_teacher_image';
}

function tbc_teacher_file_path()
{
    return G5_DATA_PATH . '/tbc/teacher';
}

function tbc_teacher_file_url($filename)
{
    return tbc_media_url('teacher', $filename);
}

function tbc_teacher_subjects()
{
    return array(
        'korean' => '국어',
        'math' => '수학',
        'science' => '과학',
        'english' => '영어',
        'social' => '사회',
    );
}

function tbc_teacher_subject_label($code)
{
    $subjects = tbc_teacher_subjects();
    return isset($subjects[$code]) ? $subjects[$code] : $code;
}

function tbc_teacher_subject_page($subject_code)
{
    $map = array(
        'korean' => 'teachers_korean',
        'math' => 'teachers_math',
        'science' => 'teachers_science',
        'english' => 'teachers_english',
        'social' => 'teachers_social',
    );

    return isset($map[$subject_code]) ? $map[$subject_code] : 'teachers';
}

function tbc_teacher_short_name($name)
{
    $name = trim((string) $name);
    if (preg_match('/^(.*)\s+강사$/u', $name, $matches)) {
        return trim($matches[1]);
    }

    return $name;
}

function tbc_teacher_get_main_featured($limit = 4)
{
    $limit = max(1, (int) $limit);
    $teachers = tbc_teacher_get_public_list();

    return array_slice($teachers, 0, $limit);
}

function tbc_teacher_ensure_dir()
{
    $dir = tbc_teacher_file_path();
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

function tbc_teacher_tables_exist()
{
    $teacher = sql_fetch(" show tables like '" . tbc_teacher_table() . "' ");
    $image = sql_fetch(" show tables like '" . tbc_teacher_image_table() . "' ");
    return $teacher && $image;
}

function tbc_teacher_ensure_tables()
{
    if (tbc_teacher_tables_exist()) {
        return true;
    }

    $charset = (defined('G5_DB_CHARSET') && G5_DB_CHARSET) ? G5_DB_CHARSET : 'utf8';

    sql_query("
        CREATE TABLE IF NOT EXISTS `" . tbc_teacher_table() . "` (
            `tc_id` int(11) NOT NULL AUTO_INCREMENT,
            `tc_subject` varchar(20) NOT NULL DEFAULT '',
            `tc_name` varchar(100) NOT NULL DEFAULT '',
            `tc_slug` varchar(100) NOT NULL DEFAULT '',
            `tc_subject_label` varchar(120) NOT NULL DEFAULT '',
            `tc_tagline` varchar(255) NOT NULL DEFAULT '',
            `tc_bg_tags` varchar(255) NOT NULL DEFAULT '',
            `tc_round_tags` varchar(255) NOT NULL DEFAULT '',
            `tc_expertise` text NOT NULL,
            `tc_career` text NOT NULL,
            `tc_profile_image` varchar(255) NOT NULL DEFAULT '',
            `tc_order` int(11) NOT NULL DEFAULT 0,
            `tc_use` tinyint(4) NOT NULL DEFAULT 1,
            `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY (`tc_id`),
            KEY `tc_subject` (`tc_subject`),
            KEY `tc_order` (`tc_order`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
    ", false);

    sql_query("
        CREATE TABLE IF NOT EXISTS `" . tbc_teacher_image_table() . "` (
            `ti_id` int(11) NOT NULL AUTO_INCREMENT,
            `tc_id` int(11) NOT NULL DEFAULT 0,
            `ti_type` varchar(20) NOT NULL DEFAULT '',
            `ti_image` varchar(255) NOT NULL DEFAULT '',
            `ti_order` int(11) NOT NULL DEFAULT 0,
            `ti_alt` varchar(255) NOT NULL DEFAULT '',
            PRIMARY KEY (`ti_id`),
            KEY `tc_id` (`tc_id`),
            KEY `ti_type` (`ti_type`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
    ", false);

    return tbc_teacher_tables_exist();
}

function tbc_teacher_allowed_image_ext($filename)
{
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $allowed = array('jpg', 'jpeg', 'png', 'webp', 'gif');
    return in_array($ext, $allowed, true) ? ($ext === 'jpeg' ? 'jpg' : $ext) : '';
}

function tbc_teacher_validate_upload_file($file)
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

    $ext = tbc_teacher_allowed_image_ext($file['name']);
    if (!$ext) {
        return array('ok' => false, 'message' => 'jpg, png, webp, gif 파일만 업로드할 수 있습니다.');
    }

    return array('ok' => true, 'ext' => $ext);
}

function tbc_teacher_store_file($prefix, $file)
{
    $check = tbc_teacher_validate_upload_file($file);
    if (!$check['ok']) {
        return $check;
    }

    tbc_teacher_ensure_dir();

    $filename = $prefix . '_' . date('YmdHis') . '_' . substr(md5(uniqid('', true)), 0, 8) . '.' . $check['ext'];
    $dest = tbc_teacher_file_path() . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return array('ok' => false, 'message' => '파일 저장에 실패했습니다.');
    }

    @chmod($dest, G5_FILE_PERMISSION);

    return array('ok' => true, 'filename' => $filename);
}

function tbc_teacher_delete_file($filename)
{
    if (!$filename) {
        return;
    }

    $path = tbc_teacher_file_path() . '/' . $filename;
    if (is_file($path)) {
        @unlink($path);
    }
}

function tbc_teacher_parse_lines($text)
{
    $lines = preg_split("/\r\n|\r|\n/", (string) $text);
    $items = array();

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line !== '') {
            $items[] = $line;
        }
    }

    return $items;
}

function tbc_teacher_parse_tags($text)
{
    $text = str_replace(',', "\n", (string) $text);
    return tbc_teacher_parse_lines($text);
}

function tbc_teacher_profile_url($filename)
{
    return tbc_media_resolve_url('teacher', $filename, G5_THEME_URL . '/img/sub/teacher_pr_sample.jpg');
}

function tbc_teacher_modal_image_url($filename)
{
    return tbc_media_resolve_url('teacher', $filename, G5_THEME_URL . '/img/sub/teacher_pr_sample.jpg');
}

function tbc_teacher_get_images($tc_id, $type = '')
{
    if (!tbc_teacher_tables_exist()) {
        return array();
    }

    $tc_id = (int) $tc_id;
    $where = " where tc_id = {$tc_id} ";
    if ($type !== '') {
        $type = sql_real_escape_string($type);
        $where .= " and ti_type = '{$type}' ";
    }

    $result = sql_query("
        select *
        from " . tbc_teacher_image_table() . "
        {$where}
        order by ti_order asc, ti_id asc
    ");

    $rows = array();
    while ($row = sql_fetch_array($result)) {
        $row['image_url'] = tbc_teacher_modal_image_url($row['ti_image']);
        $rows[] = $row;
    }

    return $rows;
}

function tbc_teacher_get($tc_id)
{
    if (!tbc_teacher_tables_exist()) {
        return null;
    }

    $tc_id = (int) $tc_id;
    $row = sql_fetch(" select * from " . tbc_teacher_table() . " where tc_id = {$tc_id} ");
    if (!$row) {
        return null;
    }

    $row['profile_url'] = tbc_teacher_profile_url($row['tc_profile_image']);
    $row['curriculum_images'] = tbc_teacher_get_images($tc_id, 'curriculum');
    $row['intro_images'] = tbc_teacher_get_images($tc_id, 'intro');

    return $row;
}

function tbc_teacher_get_admin_list($subject = '', $keyword = '')
{
    if (!tbc_teacher_tables_exist()) {
        return array();
    }

    $where = ' where 1=1 ';
    if ($subject !== '') {
        $subject = sql_real_escape_string($subject);
        $where .= " and tc_subject = '{$subject}' ";
    }

    if ($keyword !== '') {
        $keyword = sql_real_escape_string($keyword);
        $where .= " and (tc_name like '%{$keyword}%' or tc_subject_label like '%{$keyword}%' or tc_tagline like '%{$keyword}%') ";
    }

    $result = sql_query("
        select *
        from " . tbc_teacher_table() . "
        {$where}
        order by tc_order asc, tc_id asc
    ");

    $rows = array();
    while ($row = sql_fetch_array($result)) {
        $row['profile_url'] = tbc_teacher_profile_url($row['tc_profile_image']);
        $row['subject_name'] = tbc_teacher_subject_label($row['tc_subject']);
        $row['curriculum_count'] = count(tbc_teacher_get_images($row['tc_id'], 'curriculum'));
        $row['intro_count'] = count(tbc_teacher_get_images($row['tc_id'], 'intro'));
        $rows[] = $row;
    }

    return $rows;
}

function tbc_teacher_get_public_list($subject = '')
{
    if (!tbc_teacher_tables_exist()) {
        return array();
    }

    $where = " where tc_use = 1 ";
    if ($subject !== '') {
        $subject = sql_real_escape_string($subject);
        $where .= " and tc_subject = '{$subject}' ";
    }

    $result = sql_query("
        select *
        from " . tbc_teacher_table() . "
        {$where}
        order by tc_order asc, tc_id asc
    ");

    $rows = array();
    $index = 0;
    while ($row = sql_fetch_array($result)) {
        $row['layout_class'] = ($index % 2 === 0) ? 'list01' : 'list02';
        $row['profile_url'] = tbc_teacher_profile_url($row['tc_profile_image']);
        $row['bg_tags'] = tbc_teacher_parse_tags($row['tc_bg_tags']);
        $row['round_tags'] = tbc_teacher_parse_tags($row['tc_round_tags']);
        $row['expertise_items'] = tbc_teacher_parse_lines($row['tc_expertise']);
        $row['career_items'] = tbc_teacher_parse_lines($row['tc_career']);
        $row['curriculum_images'] = tbc_teacher_get_images($row['tc_id'], 'curriculum');
        $row['intro_images'] = tbc_teacher_get_images($row['tc_id'], 'intro');
        $rows[] = $row;
        $index++;
    }

    return $rows;
}

function tbc_teacher_make_slug($name, $tc_id = 0)
{
    $slug = strtolower(preg_replace('/[^a-z0-9\-]+/i', '-', $name));
    $slug = trim($slug, '-');
    if ($slug === '') {
        $slug = 'teacher-' . ($tc_id ? $tc_id : time());
    }
    return $slug;
}

function tbc_teacher_next_order()
{
    $row = sql_fetch(" select ifnull(max(tc_order), 0) as max_order from " . tbc_teacher_table() . " ");
    return (int) $row['max_order'] + 1;
}

function tbc_teacher_save($data, $tc_id = 0)
{
    tbc_teacher_ensure_tables();

    $tc_id = (int) $tc_id;
    $fields = array(
        'tc_subject' => isset($data['tc_subject']) ? $data['tc_subject'] : '',
        'tc_name' => isset($data['tc_name']) ? $data['tc_name'] : '',
        'tc_slug' => isset($data['tc_slug']) ? $data['tc_slug'] : '',
        'tc_subject_label' => isset($data['tc_subject_label']) ? $data['tc_subject_label'] : '',
        'tc_tagline' => isset($data['tc_tagline']) ? $data['tc_tagline'] : '',
        'tc_bg_tags' => isset($data['tc_bg_tags']) ? $data['tc_bg_tags'] : '',
        'tc_round_tags' => isset($data['tc_round_tags']) ? $data['tc_round_tags'] : '',
        'tc_expertise' => isset($data['tc_expertise']) ? $data['tc_expertise'] : '',
        'tc_career' => isset($data['tc_career']) ? $data['tc_career'] : '',
        'tc_use' => !empty($data['tc_use']) ? 1 : 0,
    );

    if ($fields['tc_slug'] === '') {
        $fields['tc_slug'] = tbc_teacher_make_slug($fields['tc_name'], $tc_id);
    }

    $sets = array();
    foreach ($fields as $key => $value) {
        $sets[] = "`{$key}` = '" . sql_real_escape_string($value) . "'";
    }
    $sets[] = "`updated_at` = '" . G5_TIME_YMDHIS . "'";

    if ($tc_id > 0) {
        sql_query(" update " . tbc_teacher_table() . " set " . implode(', ', $sets) . " where tc_id = {$tc_id} ");
        return array('ok' => true, 'tc_id' => $tc_id);
    }

    $order = tbc_teacher_next_order();
    $sets[] = "`tc_order` = {$order}";

    sql_query(" insert into " . tbc_teacher_table() . " set " . implode(', ', $sets));
    $new_id = sql_insert_id();

    if ($fields['tc_slug'] === '' || strpos($fields['tc_slug'], 'teacher-') === 0) {
        $slug = tbc_teacher_make_slug($fields['tc_name'], $new_id);
        sql_query(" update " . tbc_teacher_table() . " set tc_slug = '" . sql_real_escape_string($slug) . "' where tc_id = {$new_id} ");
    }

    return array('ok' => true, 'tc_id' => (int) $new_id);
}

function tbc_teacher_update_profile_image($tc_id, $file)
{
    $teacher = tbc_teacher_get($tc_id);
    if (!$teacher) {
        return array('ok' => false, 'message' => '강사를 찾을 수 없습니다.');
    }

    $result = tbc_teacher_store_file('profile', $file);
    if (!$result['ok']) {
        return $result;
    }

    if ($teacher['tc_profile_image']) {
        tbc_teacher_delete_file($teacher['tc_profile_image']);
    }

    $filename = sql_real_escape_string($result['filename']);
    sql_query("
        update " . tbc_teacher_table() . "
        set tc_profile_image = '{$filename}', updated_at = '" . G5_TIME_YMDHIS . "'
        where tc_id = " . (int) $tc_id . "
    ");

    return array('ok' => true, 'filename' => $result['filename']);
}

function tbc_teacher_add_images($tc_id, $type, $files)
{
    $teacher = tbc_teacher_get($tc_id);
    if (!$teacher) {
        return array('ok' => false, 'message' => '강사를 찾을 수 없습니다.');
    }

    if (!in_array($type, array('curriculum', 'intro'), true)) {
        return array('ok' => false, 'message' => '잘못된 이미지 유형입니다.');
    }

    $max = sql_fetch("
        select ifnull(max(ti_order), 0) as max_order
        from " . tbc_teacher_image_table() . "
        where tc_id = " . (int) $tc_id . " and ti_type = '" . sql_real_escape_string($type) . "'
    ");
    $order = (int) $max['max_order'];

    $added = 0;
    $errors = array();

    if (!isset($files['name']) || !is_array($files['name'])) {
        return array('ok' => true, 'added' => 0);
    }

    foreach ($files['name'] as $i => $name) {
        if (!$name) {
            continue;
        }

        $file = array(
            'name' => $files['name'][$i],
            'type' => $files['type'][$i],
            'tmp_name' => $files['tmp_name'][$i],
            'error' => $files['error'][$i],
            'size' => $files['size'][$i],
        );

        if (!is_uploaded_file($file['tmp_name'])) {
            continue;
        }

        $result = tbc_teacher_store_file($type, $file);
        if (!$result['ok']) {
            $errors[] = $result['message'];
            continue;
        }

        $order++;
        $filename = sql_real_escape_string($result['filename']);
        $type_sql = sql_real_escape_string($type);
        sql_query("
            insert into " . tbc_teacher_image_table() . "
            set tc_id = " . (int) $tc_id . ",
                ti_type = '{$type_sql}',
                ti_image = '{$filename}',
                ti_order = {$order},
                ti_alt = ''
        ");
        $added++;
    }

    if ($errors) {
        return array('ok' => false, 'message' => implode("\n", $errors), 'added' => $added);
    }

    return array('ok' => true, 'added' => $added);
}

function tbc_teacher_delete_image($ti_id)
{
    $ti_id = (int) $ti_id;
    $row = sql_fetch(" select * from " . tbc_teacher_image_table() . " where ti_id = {$ti_id} ");
    if (!$row) {
        return array('ok' => false, 'message' => '이미지를 찾을 수 없습니다.');
    }

    tbc_teacher_delete_file($row['ti_image']);
    sql_query(" delete from " . tbc_teacher_image_table() . " where ti_id = {$ti_id} ");
    tbc_teacher_normalize_image_orders($row['tc_id'], $row['ti_type']);

    return array('ok' => true);
}

function tbc_teacher_normalize_image_orders($tc_id, $type)
{
    $images = tbc_teacher_get_images($tc_id, $type);
    $order = 0;
    foreach ($images as $image) {
        $order++;
        sql_query(" update " . tbc_teacher_image_table() . " set ti_order = {$order} where ti_id = " . (int) $image['ti_id'] . " ");
    }
}

function tbc_teacher_move_image($ti_id, $direction)
{
    $ti_id = (int) $ti_id;
    $row = sql_fetch(" select * from " . tbc_teacher_image_table() . " where ti_id = {$ti_id} ");
    if (!$row) {
        return array('ok' => false, 'message' => '이미지를 찾을 수 없습니다.');
    }

    $images = tbc_teacher_get_images($row['tc_id'], $row['ti_type']);
    $current_index = -1;
    foreach ($images as $i => $image) {
        if ((int) $image['ti_id'] === $ti_id) {
            $current_index = $i;
            break;
        }
    }

    if ($current_index < 0) {
        return array('ok' => false, 'message' => '이미지 순서를 찾을 수 없습니다.');
    }

    $swap = ($direction === 'up') ? $current_index - 1 : $current_index + 1;
    if ($swap < 0 || $swap >= count($images)) {
        return array('ok' => false, 'message' => '더 이상 이동할 수 없습니다.');
    }

    $current_order = (int) $images[$current_index]['ti_order'];
    $target_order = (int) $images[$swap]['ti_order'];

    sql_query(" update " . tbc_teacher_image_table() . " set ti_order = {$target_order} where ti_id = {$ti_id} ");
    sql_query(" update " . tbc_teacher_image_table() . " set ti_order = {$current_order} where ti_id = " . (int) $images[$swap]['ti_id'] . " ");
    tbc_teacher_normalize_image_orders($row['tc_id'], $row['ti_type']);

    return array('ok' => true, 'images' => tbc_teacher_format_images_for_admin($row['tc_id'], $row['ti_type']));
}

function tbc_teacher_format_images_for_admin($tc_id, $type)
{
    $images = tbc_teacher_get_images($tc_id, $type);
    $items = array();

    foreach ($images as $i => $image) {
        $items[] = array(
            'ti_id' => (int) $image['ti_id'],
            'image_url' => $image['image_url'],
            'position' => $i + 1,
        );
    }

    return $items;
}

function tbc_teacher_normalize_orders()
{
    $teachers = tbc_teacher_get_admin_list();
    $order = 0;
    foreach ($teachers as $teacher) {
        $order++;
        sql_query(" update " . tbc_teacher_table() . " set tc_order = {$order} where tc_id = " . (int) $teacher['tc_id'] . " ");
    }
}

function tbc_teacher_repair_global_orders()
{
    if (!tbc_teacher_tables_exist()) {
        return;
    }

    $count_row = sql_fetch(" select count(*) as cnt from " . tbc_teacher_table() . " ");
    $total = (int) $count_row['cnt'];
    if ($total < 1) {
        return;
    }

    $dup_row = sql_fetch("
        select count(*) as cnt
        from (
            select tc_order
            from " . tbc_teacher_table() . "
            group by tc_order
            having count(*) > 1
        ) duplicated
    ");
    $max_row = sql_fetch(" select ifnull(max(tc_order), 0) as max_order from " . tbc_teacher_table() . " ");

    if ((int) $dup_row['cnt'] > 0 || (int) $max_row['max_order'] !== $total) {
        $result = sql_query("
            select tc_id
            from " . tbc_teacher_table() . "
            order by tc_subject asc, tc_order asc, tc_id asc
        ");
        $order = 0;
        while ($row = sql_fetch_array($result)) {
            $order++;
            sql_query(" update " . tbc_teacher_table() . " set tc_order = {$order} where tc_id = " . (int) $row['tc_id'] . " ");
        }
    }
}

function tbc_teacher_move($tc_id, $direction, $scope = '')
{
    $tc_id = (int) $tc_id;
    $teacher = tbc_teacher_get($tc_id);
    if (!$teacher) {
        return array('ok' => false, 'message' => '강사를 찾을 수 없습니다.');
    }

    $list = tbc_teacher_get_admin_list($scope);
    $current_index = -1;
    foreach ($list as $i => $row) {
        if ((int) $row['tc_id'] === $tc_id) {
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

    $current_order = (int) $list[$current_index]['tc_order'];
    $target_order = (int) $list[$swap]['tc_order'];

    sql_query(" update " . tbc_teacher_table() . " set tc_order = {$target_order} where tc_id = {$tc_id} ");
    sql_query(" update " . tbc_teacher_table() . " set tc_order = {$current_order} where tc_id = " . (int) $list[$swap]['tc_id'] . " ");
    tbc_teacher_normalize_orders();

    return array('ok' => true);
}

function tbc_teacher_delete($tc_id)
{
    $tc_id = (int) $tc_id;
    $teacher = tbc_teacher_get($tc_id);
    if (!$teacher) {
        return array('ok' => false, 'message' => '강사를 찾을 수 없습니다.');
    }

    if ($teacher['tc_profile_image']) {
        tbc_teacher_delete_file($teacher['tc_profile_image']);
    }

    $images = tbc_teacher_get_images($tc_id);
    foreach ($images as $image) {
        tbc_teacher_delete_file($image['ti_image']);
    }

    sql_query(" delete from " . tbc_teacher_image_table() . " where tc_id = {$tc_id} ");
    sql_query(" delete from " . tbc_teacher_table() . " where tc_id = {$tc_id} ");
    tbc_teacher_normalize_orders();

    return array('ok' => true);
}

function tbc_teacher_copy_theme_file($source_name, $dest_name)
{
    $source = G5_THEME_PATH . '/img/sub/' . $source_name;
    $dest = tbc_teacher_file_path() . '/' . $dest_name;
    if (is_file($source) && !is_file($dest)) {
        if (@copy($source, $dest)) {
            @chmod($dest, G5_FILE_PERMISSION);
            return true;
        }
    }
    return is_file($dest);
}

function tbc_teacher_seed_defaults()
{
    if (!tbc_teacher_ensure_tables()) {
        return false;
    }

    tbc_teacher_ensure_dir();

    $count = sql_fetch(" select count(*) as cnt from " . tbc_teacher_table() . " ");
    if ((int) $count['cnt'] > 0) {
        return true;
    }

    $sample = 'teacher_pr_sample.jpg';
    tbc_teacher_copy_theme_file('teacher_pr_sample.jpg', $sample);

    $teachers = array(
        array(
            'tc_subject' => 'science',
            'tc_name' => '윤호진 강사',
            'tc_slug' => 'teacher-yun',
            'tc_subject_label' => '과학 / 중등·고등',
            'tc_tagline' => '과학, 개념부터 심화까지',
            'tc_bg_tags' => '과학,개념',
            'tc_round_tags' => '과학,중등,고등',
            'tc_profile_image' => '',
            'profile_src' => 'teacher1.png',
            'tc_order' => 1,
        ),
        array(
            'tc_subject' => 'math',
            'tc_name' => '송형주 강사',
            'tc_slug' => 'teacher-song',
            'tc_subject_label' => '수학 / 중등·고등',
            'tc_tagline' => '막히던 수학이 풀리는 순간',
            'tc_bg_tags' => '수학,입시',
            'tc_round_tags' => '수학,중등,고등',
            'tc_profile_image' => '',
            'profile_src' => 'teacher2.png',
            'tc_order' => 2,
        ),
        array(
            'tc_subject' => 'math',
            'tc_name' => '김나영 강사',
            'tc_slug' => 'teacher-kim',
            'tc_subject_label' => '수학 / 중등·고등',
            'tc_tagline' => '내신과 수능을 한 번에',
            'tc_bg_tags' => '수학,내신',
            'tc_round_tags' => '수학,중등,고등',
            'tc_profile_image' => '',
            'profile_src' => 'teacher3.png',
            'tc_order' => 3,
        ),
        array(
            'tc_subject' => 'english',
            'tc_name' => '박노준 강사',
            'tc_slug' => 'teacher-park',
            'tc_subject_label' => '영어 / 중등·고등',
            'tc_tagline' => '영어, 이제 이해하고 풀자!',
            'tc_bg_tags' => '영어,독해',
            'tc_round_tags' => '영어,중등,고등',
            'tc_profile_image' => '',
            'profile_src' => 'teacher4.png',
            'tc_order' => 4,
        ),
    );

    $expertise = "내신 대비 심화·개념 완성\n수능 고난도 문제 해결 전략\n학생 수준별 맞춤 지도";
    $career = "해당 과목 전문 지도\n중등·고등 강의 경력\n학생 맞춤형 학습 설계\n더브레인코어 전문 강사";

    foreach ($teachers as $teacher) {
        $profile_dest = 'profile_' . $teacher['tc_slug'] . '.png';
        if (tbc_teacher_copy_theme_file($teacher['profile_src'], $profile_dest)) {
            $teacher['tc_profile_image'] = $profile_dest;
        }

        $sets = array(
            "tc_subject = '" . sql_real_escape_string($teacher['tc_subject']) . "'",
            "tc_name = '" . sql_real_escape_string($teacher['tc_name']) . "'",
            "tc_slug = '" . sql_real_escape_string($teacher['tc_slug']) . "'",
            "tc_subject_label = '" . sql_real_escape_string($teacher['tc_subject_label']) . "'",
            "tc_tagline = '" . sql_real_escape_string($teacher['tc_tagline']) . "'",
            "tc_bg_tags = '" . sql_real_escape_string($teacher['tc_bg_tags']) . "'",
            "tc_round_tags = '" . sql_real_escape_string($teacher['tc_round_tags']) . "'",
            "tc_expertise = '" . sql_real_escape_string($expertise) . "'",
            "tc_career = '" . sql_real_escape_string($career) . "'",
            "tc_profile_image = '" . sql_real_escape_string($teacher['tc_profile_image']) . "'",
            "tc_order = " . (int) $teacher['tc_order'],
            "tc_use = 1",
            "updated_at = '" . G5_TIME_YMDHIS . "'",
        );

        sql_query(" insert into " . tbc_teacher_table() . " set " . implode(', ', $sets));
        $tc_id = sql_insert_id();

        foreach (array('curriculum', 'intro') as $type) {
            sql_query("
                insert into " . tbc_teacher_image_table() . "
                set tc_id = {$tc_id},
                    ti_type = '{$type}',
                    ti_image = '" . sql_real_escape_string($sample) . "',
                    ti_order = 1,
                    ti_alt = ''
            ");
        }
    }

    return true;
}

function tbc_teacher_modal_images_attr($images)
{
    $urls = array();
    foreach ($images as $image) {
        $urls[] = $image['image_url'];
    }
    if (!$urls) {
        $urls[] = G5_THEME_URL . '/img/sub/teacher_pr_sample.jpg';
    }
    return htmlspecialchars(json_encode($urls, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), ENT_QUOTES, 'UTF-8');
}
