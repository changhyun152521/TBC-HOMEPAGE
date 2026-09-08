<?php
/**
 * TBC 시간표(강좌) 관리
 */
if (!defined('_GNUBOARD_')) exit;

if (!function_exists('tbc_media_url')) {
    include_once(G5_THEME_PATH . '/tbc.lib.php');
}

function tbc_schedule_course_table()
{
    return G5_TABLE_PREFIX . 'tbc_schedule_course';
}

function tbc_schedule_slot_table()
{
    return G5_TABLE_PREFIX . 'tbc_schedule_slot';
}

function tbc_schedule_file_path()
{
    return G5_DATA_PATH . '/tbc/schedule';
}

function tbc_schedule_file_url($filename)
{
    return tbc_media_url('schedule', $filename);
}

function tbc_schedule_academy_order()
{
    return array(
        'elementary', 'middle', 'high', 'science', 'alpha', 'fullstory',
        'noeun', 'gwanpyeong', 'gwanjeo', 'areum', 'saerom',
    );
}

function tbc_schedule_grades_for_academy($slug)
{
    if ($slug === 'elementary') {
        return array('e1', 'e2', 'e3', 'e4', 'e5', 'e6');
    }
    if ($slug === 'middle') {
        return array('m1', 'm2', 'm3');
    }

    return array('h1', 'h2', 'h3', 'n');
}

function tbc_schedule_subjects_for_grade()
{
    return array_keys(tbc_schedule_subjects());
}

function tbc_schedule_academy_catalog_static()
{
    return array(
        'elementary' => array('name' => '초등관', 'group' => 'main'),
        'middle' => array('name' => '중등관', 'group' => 'main'),
        'high' => array('name' => '고등관', 'group' => 'main'),
        'science' => array('name' => '과학관', 'group' => 'main'),
        'alpha' => array('name' => '알파', 'group' => 'main'),
        'fullstory' => array('name' => '풀스토리', 'group' => 'main'),
        'noeun' => array('name' => '노은관', 'group' => 'branch'),
        'gwanpyeong' => array('name' => '관평관', 'group' => 'branch'),
        'gwanjeo' => array('name' => '관저관', 'group' => 'branch'),
        'areum' => array('name' => '세종아름관', 'group' => 'branch'),
        'saerom' => array('name' => '세종새롬관', 'group' => 'branch'),
    );
}

function tbc_schedule_academy_slug_keywords()
{
    return array(
        'elementary' => array('초등'),
        'middle' => array('중등'),
        'high' => array('고등'),
        'science' => array('과학'),
        'alpha' => array('알파'),
        'fullstory' => array('풀스토리'),
        'noeun' => array('노은'),
        'gwanpyeong' => array('관평'),
        'gwanjeo' => array('관저'),
        'areum' => array('아름'),
        'saerom' => array('새롬'),
    );
}

function tbc_schedule_find_academy_for_slug($slug, $items)
{
    $keywords = tbc_schedule_academy_slug_keywords();
    if (!isset($keywords[$slug])) {
        return null;
    }

    foreach ($items as $item) {
        foreach ($keywords[$slug] as $keyword) {
            if (mb_strpos($item['ac_name'], $keyword) !== false) {
                return $item;
            }
        }
    }

    return null;
}

function tbc_schedule_academy_catalog()
{
    static $catalog = null;
    if ($catalog !== null) {
        return $catalog;
    }

    if (!function_exists('tbc_academy_short_name')) {
        include_once(G5_THEME_PATH . '/tbc.academy.lib.php');
    }

    $static = tbc_schedule_academy_catalog_static();
    $main_items = array();
    $branch_items = array();

    if (function_exists('tbc_academy_get_list') && tbc_academy_tables_exist()) {
        $main_items = tbc_academy_get_list('main', '', false);
        $branch_items = tbc_academy_get_list('branch', '', false);
    }

    $catalog = array();

    foreach (tbc_schedule_academy_order() as $slug) {
        if (!isset($static[$slug])) {
            continue;
        }

        $meta = $static[$slug];
        $pool = ($meta['group'] === 'main') ? $main_items : $branch_items;
        $matched = tbc_schedule_find_academy_for_slug($slug, $pool);

        $name = $matched ? tbc_academy_short_name($matched['ac_name']) : $meta['name'];
        if ($meta['group'] === 'main') {
            $name .= ' (둔산)';
        }
        $catalog[$slug] = array(
            'name' => $name,
            'group' => $meta['group'],
            'ac_id' => $matched ? (int) $matched['ac_id'] : 0,
        );
    }

    return $catalog;
}

function tbc_schedule_academy_label($slug)
{
    $catalog = tbc_schedule_academy_catalog();
    return isset($catalog[$slug]) ? $catalog[$slug]['name'] : $slug;
}

function tbc_schedule_grades()
{
    return array(
        'e1' => '초1', 'e2' => '초2', 'e3' => '초3', 'e4' => '초4', 'e5' => '초5', 'e6' => '초6',
        'm1' => '중1', 'm2' => '중2', 'm3' => '중3',
        'h1' => '고1', 'h2' => '고2', 'h3' => '고3',
        'n' => 'N수',
    );
}

function tbc_schedule_grade_label($code)
{
    $grades = tbc_schedule_grades();
    return isset($grades[$code]) ? $grades[$code] : $code;
}

function tbc_schedule_subjects()
{
    return array(
        'korean' => '국어',
        'math' => '수학',
        'english' => '영어',
        'science' => '과학',
        'social' => '사회',
        'integrated' => '통합',
        'reading' => '독서·논술',
        'other' => '기타',
    );
}

function tbc_schedule_subject_label($code)
{
    $subjects = tbc_schedule_subjects();
    return isset($subjects[$code]) ? $subjects[$code] : $code;
}

function tbc_schedule_sort_index($value, $order)
{
    $index = array_search($value, $order, true);
    return $index === false ? 999 : $index;
}

function tbc_schedule_compare_courses($a, $b)
{
    $grade_order = array_keys(tbc_schedule_grades());
    $subject_order = array_keys(tbc_schedule_subjects());
    $academy_order = tbc_schedule_academy_order();

    $a_grade = isset($a['grade']) ? $a['grade'] : (isset($a['sc_grade']) ? $a['sc_grade'] : '');
    $b_grade = isset($b['grade']) ? $b['grade'] : (isset($b['sc_grade']) ? $b['sc_grade'] : '');
    $a_subject = isset($a['subject']) ? $a['subject'] : (isset($a['sc_subject']) ? $a['sc_subject'] : '');
    $b_subject = isset($b['subject']) ? $b['subject'] : (isset($b['sc_subject']) ? $b['sc_subject'] : '');
    $a_academy = isset($a['academy']) ? $a['academy'] : (isset($a['sc_academy']) ? $a['sc_academy'] : '');
    $b_academy = isset($b['academy']) ? $b['academy'] : (isset($b['sc_academy']) ? $b['sc_academy'] : '');
    $a_name = isset($a['name']) ? $a['name'] : (isset($a['sc_name']) ? $a['sc_name'] : '');
    $b_name = isset($b['name']) ? $b['name'] : (isset($b['sc_name']) ? $b['sc_name'] : '');

    $grade_diff = tbc_schedule_sort_index($a_grade, $grade_order) - tbc_schedule_sort_index($b_grade, $grade_order);
    if ($grade_diff !== 0) {
        return $grade_diff;
    }

    $subject_diff = tbc_schedule_sort_index($a_subject, $subject_order) - tbc_schedule_sort_index($b_subject, $subject_order);
    if ($subject_diff !== 0) {
        return $subject_diff;
    }

    $academy_diff = tbc_schedule_sort_index($a_academy, $academy_order) - tbc_schedule_sort_index($b_academy, $academy_order);
    if ($academy_diff !== 0) {
        return $academy_diff;
    }

    $a_order = isset($a['order']) ? (int) $a['order'] : (isset($a['sc_order']) ? (int) $a['sc_order'] : 0);
    $b_order = isset($b['order']) ? (int) $b['order'] : (isset($b['sc_order']) ? (int) $b['sc_order'] : 0);
    if ($a_order !== $b_order) {
        return $a_order - $b_order;
    }

    return strcmp($a_name, $b_name);
}

function tbc_schedule_sort_courses($rows)
{
    usort($rows, 'tbc_schedule_compare_courses');
    return $rows;
}

function tbc_schedule_days()
{
    return array(
        'mon' => '월',
        'tue' => '화',
        'wed' => '수',
        'thu' => '목',
        'fri' => '금',
        'sat' => '토',
        'sun' => '일',
    );
}

function tbc_schedule_day_label($code)
{
    $days = tbc_schedule_days();
    return isset($days[$code]) ? $days[$code] : $code;
}

function tbc_schedule_ensure_dir()
{
    $dir = tbc_schedule_file_path();
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

function tbc_schedule_tables_exist()
{
    $course = sql_fetch(" show tables like '" . tbc_schedule_course_table() . "' ");
    $slot = sql_fetch(" show tables like '" . tbc_schedule_slot_table() . "' ");
    return $course && $slot;
}

function tbc_schedule_ensure_tables()
{
    if (tbc_schedule_tables_exist()) {
        return true;
    }

    $charset = (defined('G5_DB_CHARSET') && G5_DB_CHARSET) ? G5_DB_CHARSET : 'utf8';

    sql_query("
        CREATE TABLE IF NOT EXISTS `" . tbc_schedule_course_table() . "` (
            `sc_id` int(11) NOT NULL AUTO_INCREMENT,
            `sc_academy` varchar(40) NOT NULL DEFAULT '',
            `sc_grade` varchar(20) NOT NULL DEFAULT '',
            `sc_subject` varchar(20) NOT NULL DEFAULT '',
            `sc_name` varchar(200) NOT NULL DEFAULT '',
            `sc_teacher` varchar(120) NOT NULL DEFAULT '',
            `sc_fee` varchar(80) NOT NULL DEFAULT '',
            `sc_intro_image` varchar(255) NOT NULL DEFAULT '',
            `sc_order` int(11) NOT NULL DEFAULT 0,
            `sc_use` tinyint(4) NOT NULL DEFAULT 1,
            `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY (`sc_id`),
            KEY `sc_academy` (`sc_academy`),
            KEY `sc_grade` (`sc_grade`),
            KEY `sc_subject` (`sc_subject`),
            KEY `sc_order` (`sc_order`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
    ", false);

    sql_query("
        CREATE TABLE IF NOT EXISTS `" . tbc_schedule_slot_table() . "` (
            `sl_id` int(11) NOT NULL AUTO_INCREMENT,
            `sc_id` int(11) NOT NULL DEFAULT 0,
            `sl_day` varchar(10) NOT NULL DEFAULT '',
            `sl_start` varchar(10) NOT NULL DEFAULT '',
            `sl_end` varchar(10) NOT NULL DEFAULT '',
            `sl_order` int(11) NOT NULL DEFAULT 0,
            PRIMARY KEY (`sl_id`),
            KEY `sc_id` (`sc_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
    ", false);

    return tbc_schedule_tables_exist();
}

function tbc_schedule_allowed_image_ext($filename)
{
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $allowed = array('jpg', 'jpeg', 'png', 'webp', 'gif');
    return in_array($ext, $allowed, true) ? ($ext === 'jpeg' ? 'jpg' : $ext) : '';
}

function tbc_schedule_validate_upload_file($file)
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

    $ext = tbc_schedule_allowed_image_ext($file['name']);
    if (!$ext) {
        return array('ok' => false, 'message' => 'jpg, png, webp, gif 파일만 업로드할 수 있습니다.');
    }

    return array('ok' => true, 'ext' => $ext);
}

function tbc_schedule_store_file($file)
{
    $check = tbc_schedule_validate_upload_file($file);
    if (!$check['ok']) {
        return $check;
    }

    tbc_schedule_ensure_dir();

    $filename = 'schedule_' . date('YmdHis') . '_' . substr(md5(uniqid('', true)), 0, 8) . '.' . $check['ext'];
    $dest = tbc_schedule_file_path() . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return array('ok' => false, 'message' => '파일 저장에 실패했습니다.');
    }

    @chmod($dest, G5_FILE_PERMISSION);

    return array('ok' => true, 'filename' => $filename);
}

function tbc_schedule_delete_file($filename)
{
    if (!$filename) {
        return;
    }

    $path = tbc_schedule_file_path() . '/' . $filename;
    if (is_file($path)) {
        @unlink($path);
    }
}

function tbc_schedule_get_slots($sc_id)
{
    $sc_id = (int) $sc_id;
    $rows = array();
    $result = sql_query(" select * from `" . tbc_schedule_slot_table() . "` where sc_id = '{$sc_id}' order by sl_order asc, sl_id asc ");
    while ($row = sql_fetch_array($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function tbc_schedule_format_slot_label($slot)
{
    $day = tbc_schedule_day_label($slot['sl_day']);
    $start = trim($slot['sl_start']);
    $end = trim($slot['sl_end']);
    if ($start && $end) {
        return $day . ' ' . $start . '~' . $end;
    }
    if ($start) {
        return $day . ' ' . $start;
    }
    return $day;
}

function tbc_schedule_format_slots_text($slots)
{
    $labels = array();
    foreach ($slots as $slot) {
        $labels[] = tbc_schedule_format_slot_label($slot);
    }
    return implode(' · ', $labels);
}

function tbc_schedule_get($sc_id)
{
    $sc_id = (int) $sc_id;
    $row = sql_fetch(" select * from `" . tbc_schedule_course_table() . "` where sc_id = '{$sc_id}' ");
    if (!$row) {
        return null;
    }

    $row['slots'] = tbc_schedule_get_slots($sc_id);
    return $row;
}

function tbc_schedule_get_admin_list($filters = array())
{
    $where = array('1=1');

    if (!empty($filters['academy'])) {
        $academy = sql_real_escape_string(preg_replace('/[^a-z_]/', '', $filters['academy']));
        $where[] = "sc_academy = '{$academy}'";
    }
    if (!empty($filters['grade'])) {
        $grade = sql_real_escape_string(preg_replace('/[^a-z0-9_]/', '', $filters['grade']));
        $where[] = "sc_grade = '{$grade}'";
    }
    if (!empty($filters['subject'])) {
        $subject = sql_real_escape_string(preg_replace('/[^a-z_]/', '', $filters['subject']));
        $where[] = "sc_subject = '{$subject}'";
    }
    if (!empty($filters['q'])) {
        $q = sql_real_escape_string($filters['q']);
        $where[] = "(sc_name like '%{$q}%' or sc_teacher like '%{$q}%')";
    }

    $rows = array();
    $sql = " select * from `" . tbc_schedule_course_table() . "` where " . implode(' and ', $where) . " order by sc_order asc, sc_id asc ";
    $result = sql_query($sql);
    while ($row = sql_fetch_array($result)) {
        $row['slots'] = tbc_schedule_get_slots($row['sc_id']);
        $rows[] = $row;
    }

    return $rows;
}

function tbc_schedule_get_admin_grouped_list($filters = array())
{
    $rows = tbc_schedule_get_admin_list($filters);
    $grouped = array();

    foreach ($rows as $row) {
        $slug = $row['sc_academy'];
        if (!isset($grouped[$slug])) {
            $grouped[$slug] = array();
        }
        $grouped[$slug][] = $row;
    }

    $catalog = tbc_schedule_academy_catalog();
    $sections = array();
    $seen = array();

    foreach (tbc_schedule_academy_order() as $slug) {
        if (empty($grouped[$slug])) {
            continue;
        }
        $seen[$slug] = true;
        $sections[] = array(
            'slug' => $slug,
            'name' => isset($catalog[$slug]) ? $catalog[$slug]['name'] : $slug,
            'courses' => $grouped[$slug],
        );
    }

    foreach ($grouped as $slug => $courses) {
        if (!empty($seen[$slug])) {
            continue;
        }
        $sections[] = array(
            'slug' => $slug,
            'name' => isset($catalog[$slug]) ? $catalog[$slug]['name'] : $slug,
            'courses' => $courses,
        );
    }

    return $sections;
}

function tbc_schedule_get_public_courses($academy = '', $grade = '', $subject = '')
{
    $where = array("sc_use = 1");

    if ($academy) {
        $academy = sql_real_escape_string(preg_replace('/[^a-z_]/', '', $academy));
        $where[] = "sc_academy = '{$academy}'";
    }
    if ($grade) {
        $grade = sql_real_escape_string(preg_replace('/[^a-z0-9_]/', '', $grade));
        $where[] = "sc_grade = '{$grade}'";
    }
    if ($subject) {
        $subject = sql_real_escape_string(preg_replace('/[^a-z_]/', '', $subject));
        $where[] = "sc_subject = '{$subject}'";
    }

    $rows = array();
    $sql = " select * from `" . tbc_schedule_course_table() . "` where " . implode(' and ', $where) . " order by sc_order asc, sc_id asc ";
    $result = sql_query($sql);
    while ($row = sql_fetch_array($result)) {
        $slots = tbc_schedule_get_slots($row['sc_id']);
        $slot_payload = array();
        foreach ($slots as $slot) {
            $slot_payload[] = array(
                'day' => $slot['sl_day'],
                'dayLabel' => tbc_schedule_day_label($slot['sl_day']),
                'start' => $slot['sl_start'],
                'end' => $slot['sl_end'],
                'label' => tbc_schedule_format_slot_label($slot),
            );
        }

        $rows[] = array(
            'id' => (int) $row['sc_id'],
            'academy' => $row['sc_academy'],
            'grade' => $row['sc_grade'],
            'subject' => $row['sc_subject'],
            'name' => $row['sc_name'],
            'teacher' => $row['sc_teacher'],
            'fee' => $row['sc_fee'],
            'order' => (int) $row['sc_order'],
            'introImage' => $row['sc_intro_image'] ? tbc_schedule_file_url($row['sc_intro_image']) : '',
            'slots' => $slot_payload,
            'scheduleText' => tbc_schedule_format_slots_text($slots),
        );
    }

    return tbc_schedule_sort_courses($rows);
}

function tbc_schedule_build_public_payload($group = '')
{
    $catalog = tbc_schedule_academy_catalog();
    $order = tbc_schedule_academy_order();

    if ($group === 'main' || $group === 'branch') {
        $order = array_values(array_filter($order, function ($slug) use ($catalog, $group) {
            return isset($catalog[$slug]) && $catalog[$slug]['group'] === $group;
        }));
    }

    $courses = tbc_schedule_get_public_courses();
    if ($group === 'main' || $group === 'branch') {
        $allowed = array_flip($order);
        $courses = array_values(array_filter($courses, function ($course) use ($allowed) {
            return isset($allowed[$course['academy']]);
        }));
    }
    $grade_order = array_keys(tbc_schedule_grades());
    $subject_order = array_keys(tbc_schedule_subjects());
    $academy_meta = array();

    foreach ($order as $slug) {
        if (!isset($catalog[$slug])) {
            continue;
        }

        $academy_meta[$slug] = array(
            'name' => $catalog[$slug]['name'],
            'grades' => array(),
            'subjects' => array(),
        );
    }

    foreach ($courses as $course) {
        $slug = $course['academy'];
        if (!isset($academy_meta[$slug])) {
            continue;
        }

        if ($course['grade'] && !in_array($course['grade'], $academy_meta[$slug]['grades'], true)) {
            $academy_meta[$slug]['grades'][] = $course['grade'];
        }

        $grade = $course['grade'];
        if ($grade && $course['subject']) {
            if (!isset($academy_meta[$slug]['subjects'][$grade])) {
                $academy_meta[$slug]['subjects'][$grade] = array();
            }
            if (!in_array($course['subject'], $academy_meta[$slug]['subjects'][$grade], true)) {
                $academy_meta[$slug]['subjects'][$grade][] = $course['subject'];
            }
        }
    }

    $active_order = array();
    $active_meta = array();

    foreach ($order as $slug) {
        if (!isset($academy_meta[$slug]) || empty($academy_meta[$slug]['grades'])) {
            continue;
        }

        usort($academy_meta[$slug]['grades'], function ($a, $b) use ($grade_order) {
            $ia = array_search($a, $grade_order, true);
            $ib = array_search($b, $grade_order, true);
            return ($ia === false ? 999 : $ia) - ($ib === false ? 999 : $ib);
        });

        foreach ($academy_meta[$slug]['subjects'] as $grade => $subjects) {
            usort($academy_meta[$slug]['subjects'][$grade], function ($a, $b) use ($subject_order) {
                $ia = array_search($a, $subject_order, true);
                $ib = array_search($b, $subject_order, true);
                return ($ia === false ? 999 : $ia) - ($ib === false ? 999 : $ib);
            });
        }

        $active_order[] = $slug;
        $active_meta[$slug] = $academy_meta[$slug];
    }

    return array(
        'academyOrder' => $active_order,
        'academies' => $active_meta,
        'gradeOrder' => $grade_order,
        'subjectOrder' => $subject_order,
        'grades' => tbc_schedule_grades(),
        'subjects' => tbc_schedule_subjects(),
        'courses' => $courses,
    );
}

function tbc_schedule_save_slots($sc_id, $slots)
{
    $sc_id = (int) $sc_id;
    sql_query(" delete from `" . tbc_schedule_slot_table() . "` where sc_id = '{$sc_id}' ");

    if (!is_array($slots)) {
        return;
    }

    $order = 0;
    foreach ($slots as $slot) {
        if (empty($slot['day'])) {
            continue;
        }

        $day = sql_real_escape_string(preg_replace('/[^a-z]/', '', $slot['day']));
        $start = sql_real_escape_string(trim((string) $slot['start']));
        $end = sql_real_escape_string(trim((string) $slot['end']));

        sql_query("
            insert into `" . tbc_schedule_slot_table() . "`
            set sc_id = '{$sc_id}',
                sl_day = '{$day}',
                sl_start = '{$start}',
                sl_end = '{$end}',
                sl_order = '{$order}'
        ");

        $order++;
    }
}

function tbc_schedule_save($data, $sc_id = 0)
{
    tbc_schedule_ensure_tables();

    $academy = preg_replace('/[^a-z_]/', '', $data['sc_academy']);
    $grade = preg_replace('/[^a-z0-9_]/', '', $data['sc_grade']);
    $subject = preg_replace('/[^a-z_]/', '', $data['sc_subject']);
    $name = trim($data['sc_name']);
    $teacher = trim($data['sc_teacher']);
    $fee = trim($data['sc_fee']);
    $use = !empty($data['sc_use']) ? 1 : 0;
    $order = isset($data['sc_order']) ? (int) $data['sc_order'] : 0;

    if (!$academy || !$grade || !$subject || $name === '') {
        return array('ok' => false, 'message' => '관·학년·과목·강좌명은 필수입니다.');
    }

    $academy_sql = sql_real_escape_string($academy);
    $grade_sql = sql_real_escape_string($grade);
    $subject_sql = sql_real_escape_string($subject);
    $name_sql = sql_real_escape_string($name);
    $teacher_sql = sql_real_escape_string($teacher);
    $fee_sql = sql_real_escape_string($fee);
    $now = G5_TIME_YMDHIS;

    if ($sc_id) {
        sql_query("
            update `" . tbc_schedule_course_table() . "` set
                sc_academy = '{$academy_sql}',
                sc_grade = '{$grade_sql}',
                sc_subject = '{$subject_sql}',
                sc_name = '{$name_sql}',
                sc_teacher = '{$teacher_sql}',
                sc_fee = '{$fee_sql}',
                sc_use = '{$use}',
                sc_order = '{$order}',
                updated_at = '{$now}'
            where sc_id = '" . (int) $sc_id . "'
        ");
    } else {
        if (!$order) {
            $row = sql_fetch(" select max(sc_order) as max_order from `" . tbc_schedule_course_table() . "` where sc_academy = '{$academy_sql}' ");
            $order = (int) $row['max_order'] + 1;
        }

        sql_query("
            insert into `" . tbc_schedule_course_table() . "` set
                sc_academy = '{$academy_sql}',
                sc_grade = '{$grade_sql}',
                sc_subject = '{$subject_sql}',
                sc_name = '{$name_sql}',
                sc_teacher = '{$teacher_sql}',
                sc_fee = '{$fee_sql}',
                sc_use = '{$use}',
                sc_order = '{$order}',
                updated_at = '{$now}'
        ");
        $sc_id = sql_insert_id();
    }

    if (!empty($data['slots']) && is_array($data['slots'])) {
        tbc_schedule_save_slots($sc_id, $data['slots']);
    }

    return array('ok' => true, 'sc_id' => (int) $sc_id);
}

function tbc_schedule_update_image($sc_id, $file)
{
    $course = tbc_schedule_get($sc_id);
    if (!$course) {
        return array('ok' => false, 'message' => '강좌를 찾을 수 없습니다.');
    }

    $stored = tbc_schedule_store_file($file);
    if (!$stored['ok']) {
        return $stored;
    }

    if ($course['sc_intro_image']) {
        tbc_schedule_delete_file($course['sc_intro_image']);
    }

    $filename = sql_real_escape_string($stored['filename']);
    sql_query(" update `" . tbc_schedule_course_table() . "` set sc_intro_image = '{$filename}', updated_at = '" . G5_TIME_YMDHIS . "' where sc_id = '" . (int) $sc_id . "' ");

    return array('ok' => true, 'filename' => $stored['filename']);
}

function tbc_schedule_delete($sc_id)
{
    $course = tbc_schedule_get($sc_id);
    if (!$course) {
        return array('ok' => false, 'message' => '강좌를 찾을 수 없습니다.');
    }

    if ($course['sc_intro_image']) {
        tbc_schedule_delete_file($course['sc_intro_image']);
    }

    sql_query(" delete from `" . tbc_schedule_slot_table() . "` where sc_id = '" . (int) $sc_id . "' ");
    sql_query(" delete from `" . tbc_schedule_course_table() . "` where sc_id = '" . (int) $sc_id . "' ");

    return array('ok' => true);
}

function tbc_schedule_move_order($sc_id, $direction)
{
    $course = tbc_schedule_get($sc_id);
    if (!$course) {
        return array('ok' => false, 'message' => '강좌를 찾을 수 없습니다.');
    }

    $operator = $direction === 'up' ? '<' : '>';
    $sort = $direction === 'up' ? 'desc' : 'asc';
    $academy_sql = sql_real_escape_string($course['sc_academy']);
    $neighbor = sql_fetch("
        select sc_id, sc_order from `" . tbc_schedule_course_table() . "`
        where sc_academy = '{$academy_sql}'
          and sc_order {$operator} '" . (int) $course['sc_order'] . "'
        order by sc_order {$sort}
        limit 1
    ");

    if (!$neighbor) {
        return array('ok' => false, 'message' => '더 이동할 수 없습니다.');
    }

    sql_query(" update `" . tbc_schedule_course_table() . "` set sc_order = '" . (int) $neighbor['sc_order'] . "' where sc_id = '" . (int) $course['sc_id'] . "' ");
    sql_query(" update `" . tbc_schedule_course_table() . "` set sc_order = '" . (int) $course['sc_order'] . "' where sc_id = '" . (int) $neighbor['sc_id'] . "' ");

    return array('ok' => true);
}

function tbc_schedule_copy_theme_image($theme_relative_path)
{
    $source = G5_THEME_PATH . '/' . ltrim($theme_relative_path, '/');
    if (!is_file($source)) {
        return '';
    }

    tbc_schedule_ensure_dir();

    $ext = tbc_schedule_allowed_image_ext($source);
    if (!$ext) {
        return '';
    }

    $filename = 'seed_' . date('YmdHis') . '_' . substr(md5($theme_relative_path), 0, 8) . '.' . $ext;
    $dest = tbc_schedule_file_path() . '/' . $filename;

    if (!@copy($source, $dest)) {
        return '';
    }

    @chmod($dest, G5_FILE_PERMISSION);
    return $filename;
}

function tbc_schedule_seed_defaults()
{
    if (!tbc_schedule_ensure_tables()) {
        return false;
    }

    $count_row = sql_fetch(" select count(*) as cnt from `" . tbc_schedule_course_table() . "` ");
    if ((int) $count_row['cnt'] > 0) {
        return true;
    }

    $samples = array(
        array(
            'sc_academy' => 'high',
            'sc_grade' => 'h1',
            'sc_subject' => 'math',
            'sc_name' => '고1 수학 정규반',
            'sc_teacher' => '김나영',
            'sc_fee' => '월 350,000원',
            'sc_use' => 1,
            'sc_order' => 1,
            'slots' => array(
                array('day' => 'mon', 'start' => '18:00', 'end' => '21:00'),
                array('day' => 'wed', 'start' => '18:00', 'end' => '21:00'),
            ),
            'image' => 'img/schedule/high1-1.png',
        ),
        array(
            'sc_academy' => 'high',
            'sc_grade' => 'h1',
            'sc_subject' => 'english',
            'sc_name' => '고1 영어 심화반',
            'sc_teacher' => '박노준',
            'sc_fee' => '월 320,000원',
            'sc_use' => 1,
            'sc_order' => 2,
            'slots' => array(
                array('day' => 'tue', 'start' => '18:30', 'end' => '21:30'),
                array('day' => 'thu', 'start' => '18:30', 'end' => '21:30'),
            ),
            'image' => 'img/schedule/high1-2.png',
        ),
        array(
            'sc_academy' => 'high',
            'sc_grade' => 'h2',
            'sc_subject' => 'math',
            'sc_name' => '고2 수학 상위권반',
            'sc_teacher' => '송형주',
            'sc_fee' => '월 380,000원',
            'sc_use' => 1,
            'sc_order' => 3,
            'slots' => array(
                array('day' => 'mon', 'start' => '19:00', 'end' => '22:00'),
                array('day' => 'fri', 'start' => '19:00', 'end' => '22:00'),
            ),
            'image' => 'img/schedule/high2-1.png',
        ),
        array(
            'sc_academy' => 'high',
            'sc_grade' => 'h2',
            'sc_subject' => 'science',
            'sc_name' => '고2 통합과학 집중반',
            'sc_teacher' => '윤호진',
            'sc_fee' => '월 360,000원',
            'sc_use' => 1,
            'sc_order' => 4,
            'slots' => array(
                array('day' => 'sat', 'start' => '10:00', 'end' => '13:00'),
            ),
            'image' => 'img/schedule/high2-2.png',
        ),
    );

    foreach ($samples as $sample) {
        $image = tbc_schedule_copy_theme_image($sample['image']);
        $result = tbc_schedule_save($sample);
        if ($result['ok'] && $image) {
            $filename = sql_real_escape_string($image);
            sql_query(" update `" . tbc_schedule_course_table() . "` set sc_intro_image = '{$filename}' where sc_id = '" . (int) $result['sc_id'] . "' ");
        }
    }

    return true;
}
