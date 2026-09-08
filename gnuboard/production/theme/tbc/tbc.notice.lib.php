<?php
/**
 * TBC 공지사항 관리
 */
if (!defined('_GNUBOARD_')) exit;

if (!function_exists('tbc_media_url')) {
    include_once(G5_THEME_PATH . '/tbc.lib.php');
}

function tbc_notice_table()
{
    return G5_TABLE_PREFIX . 'tbc_notice';
}

function tbc_notice_file_path()
{
    return G5_DATA_PATH . '/tbc/notice';
}

function tbc_notice_file_url($filename)
{
    return tbc_media_url('notice', $filename);
}

function tbc_notice_ensure_file_dir()
{
    $dir = tbc_notice_file_path();
    if (!is_dir($dir)) {
        @mkdir($dir, G5_DIR_PERMISSION, true);
        @chmod($dir, G5_DIR_PERMISSION);
    }

    $index = $dir . '/index.php';
    if (!is_file($index)) {
        @file_put_contents($index, '');
        @chmod($index, G5_FILE_PERMISSION);
    }
}

function tbc_notice_table_exists()
{
    $row = sql_fetch(" show tables like '" . tbc_notice_table() . "' ");
    return (bool) $row;
}

function tbc_notice_ensure_tables()
{
    if (tbc_notice_table_exists()) {
        return true;
    }

    $charset = (defined('G5_DB_CHARSET') && G5_DB_CHARSET) ? G5_DB_CHARSET : 'utf8';

    sql_query("
        CREATE TABLE IF NOT EXISTS `" . tbc_notice_table() . "` (
            `nt_id` int(11) NOT NULL AUTO_INCREMENT,
            `nt_subject` varchar(255) NOT NULL DEFAULT '',
            `nt_content` mediumtext NOT NULL,
            `nt_author` varchar(80) NOT NULL DEFAULT '',
            `nt_file` varchar(255) NOT NULL DEFAULT '',
            `nt_file_source` varchar(255) NOT NULL DEFAULT '',
            `nt_is_notice` tinyint(4) NOT NULL DEFAULT 0,
            `nt_use` tinyint(4) NOT NULL DEFAULT 1,
            `nt_hit` int(11) NOT NULL DEFAULT 0,
            `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY (`nt_id`),
            KEY `nt_use` (`nt_use`),
            KEY `nt_is_notice` (`nt_is_notice`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
    ", false);

    return tbc_notice_table_exists();
}

function tbc_notice_seed_defaults()
{
    if (!tbc_notice_ensure_tables()) {
        return false;
    }

    $count = sql_fetch(" select count(*) as cnt from `" . tbc_notice_table() . "` ");
    if ((int) $count['cnt'] > 0) {
        return true;
    }

    $samples = array(
        array(
            'nt_subject' => '더브레인코어 2026학년도 입학 상담 안내',
            'nt_content' => "안녕하세요. 더브레인코어입니다.\n\n2026학년도 신·재원생 입학 상담을 진행하고 있습니다.\n홈페이지 상담신청 또는 대표번호로 문의해 주세요.\n\n감사합니다.",
            'nt_author' => '관리자',
            'nt_is_notice' => 1,
        ),
        array(
            'nt_subject' => '봄학기 개강 일정 및 강좌 안내',
            'nt_content' => "봄학기 개강 일정과 강좌 안내를 게시합니다.\n자세한 내용은 시간표 메뉴에서 확인해 주세요.",
            'nt_author' => '관리자',
            'nt_is_notice' => 0,
        ),
    );

    $now = G5_TIME_YMDHIS;
    foreach ($samples as $sample) {
        sql_query("
            insert into `" . tbc_notice_table() . "` set
                nt_subject = '" . sql_real_escape_string($sample['nt_subject']) . "',
                nt_content = '" . sql_real_escape_string($sample['nt_content']) . "',
                nt_author = '" . sql_real_escape_string($sample['nt_author']) . "',
                nt_is_notice = '" . (int) $sample['nt_is_notice'] . "',
                nt_use = 1,
                created_at = '{$now}',
                updated_at = '{$now}'
        ");
    }

    return true;
}

function tbc_notice_allowed_image_ext($filename)
{
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $allowed = array('jpg', 'jpeg', 'png', 'webp', 'gif', 'pdf', 'hwp', 'doc', 'docx', 'xls', 'xlsx', 'zip');
    return in_array($ext, $allowed, true) ? ($ext === 'jpeg' ? 'jpg' : $ext) : '';
}

function tbc_notice_validate_upload_file($file)
{
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return array('ok' => false, 'message' => '업로드된 파일이 없습니다.');
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return array('ok' => false, 'message' => '파일 업로드 중 오류가 발생했습니다.');
    }

    if ($file['size'] > 10 * 1024 * 1024) {
        return array('ok' => false, 'message' => '파일 크기는 10MB 이하여야 합니다.');
    }

    $ext = tbc_notice_allowed_image_ext($file['name']);
    if (!$ext) {
        return array('ok' => false, 'message' => '지원하지 않는 파일 형식입니다.');
    }

    return array('ok' => true, 'ext' => $ext, 'source' => $file['name']);
}

function tbc_notice_store_file($file)
{
    $check = tbc_notice_validate_upload_file($file);
    if (!$check['ok']) {
        return $check;
    }

    tbc_notice_ensure_file_dir();

    $filename = 'notice_' . date('YmdHis') . '_' . substr(md5(uniqid('', true)), 0, 8) . '.' . $check['ext'];
    $dest = tbc_notice_file_path() . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return array('ok' => false, 'message' => '파일 저장에 실패했습니다.');
    }

    @chmod($dest, G5_FILE_PERMISSION);

    return array(
        'ok' => true,
        'filename' => $filename,
        'source' => $check['source'],
    );
}

function tbc_notice_delete_file($filename)
{
    if (!$filename) {
        return;
    }

    $path = tbc_notice_file_path() . '/' . $filename;
    if (is_file($path)) {
        @unlink($path);
    }
}

function tbc_notice_build_where($filters = array(), $public_only = false)
{
    $where = array('1=1');

    if ($public_only) {
        $where[] = 'nt_use = 1';
    }

    if (!empty($filters['q'])) {
        $q = sql_real_escape_string($filters['q']);
        $where[] = "(nt_subject like '%{$q}%' or nt_content like '%{$q}%' or nt_author like '%{$q}%')";
    }

    if (!empty($filters['sfl']) && !empty($filters['stx'])) {
        $stx = sql_real_escape_string($filters['stx']);
        $sfl = $filters['sfl'];
        if ($sfl === 'subject') {
            $where[] = "nt_subject like '%{$stx}%'";
        } elseif ($sfl === 'content') {
            $where[] = "nt_content like '%{$stx}%'";
        } elseif ($sfl === 'subject_content') {
            $where[] = "(nt_subject like '%{$stx}%' or nt_content like '%{$stx}%')";
        } elseif ($sfl === 'author') {
            $where[] = "nt_author like '%{$stx}%'";
        }
    }

    return $where;
}

function tbc_notice_count($filters = array(), $public_only = false)
{
    tbc_notice_ensure_tables();
    $where = tbc_notice_build_where($filters, $public_only);
    $row = sql_fetch(" select count(*) as cnt from `" . tbc_notice_table() . "` where " . implode(' and ', $where));
    return (int) $row['cnt'];
}

function tbc_notice_get_list($filters = array(), $public_only = false)
{
    tbc_notice_ensure_tables();

    $where = tbc_notice_build_where($filters, $public_only);
    $page = isset($filters['page']) ? max(1, (int) $filters['page']) : 1;
    $per_page = isset($filters['per_page']) ? max(1, (int) $filters['per_page']) : 10;
    $offset = ($page - 1) * $per_page;

    $rows = array();
    $sql = "
        select * from `" . tbc_notice_table() . "`
        where " . implode(' and ', $where) . "
        order by nt_is_notice desc, nt_id desc
        limit {$offset}, {$per_page}
    ";
    $result = sql_query($sql);
    while ($row = sql_fetch_array($result)) {
        $rows[] = $row;
    }

    return $rows;
}

function tbc_notice_get($nt_id)
{
    tbc_notice_ensure_tables();
    $nt_id = (int) $nt_id;
    if (!$nt_id) {
        return null;
    }

    return sql_fetch(" select * from `" . tbc_notice_table() . "` where nt_id = '{$nt_id}' ");
}

function tbc_notice_get_public($nt_id)
{
    $row = tbc_notice_get($nt_id);
    if (!$row || !$row['nt_use']) {
        return null;
    }

    return $row;
}

function tbc_notice_increase_hit($nt_id)
{
    $nt_id = (int) $nt_id;
    if (!$nt_id) {
        return;
    }

    sql_query(" update `" . tbc_notice_table() . "` set nt_hit = nt_hit + 1 where nt_id = '{$nt_id}' ");
}

function tbc_notice_save($data, $nt_id = 0)
{
    tbc_notice_ensure_tables();

    $subject = trim($data['nt_subject']);
    $content = trim($data['nt_content']);
    $author = trim($data['nt_author']);
    $is_notice = !empty($data['nt_is_notice']) ? 1 : 0;
    $use = !empty($data['nt_use']) ? 1 : 0;

    if ($subject === '' || $content === '') {
        return array('ok' => false, 'message' => '제목과 내용은 필수입니다.');
    }

    if ($author === '') {
        $author = '관리자';
    }

    $subject_sql = sql_real_escape_string($subject);
    $content_sql = sql_real_escape_string($content);
    $author_sql = sql_real_escape_string($author);
    $now = G5_TIME_YMDHIS;

    if ($nt_id) {
        sql_query("
            update `" . tbc_notice_table() . "` set
                nt_subject = '{$subject_sql}',
                nt_content = '{$content_sql}',
                nt_author = '{$author_sql}',
                nt_is_notice = '{$is_notice}',
                nt_use = '{$use}',
                updated_at = '{$now}'
            where nt_id = '" . (int) $nt_id . "'
        ");
    } else {
        sql_query("
            insert into `" . tbc_notice_table() . "` set
                nt_subject = '{$subject_sql}',
                nt_content = '{$content_sql}',
                nt_author = '{$author_sql}',
                nt_is_notice = '{$is_notice}',
                nt_use = '{$use}',
                created_at = '{$now}',
                updated_at = '{$now}'
        ");
        $nt_id = sql_insert_id();
    }

    return array('ok' => true, 'nt_id' => (int) $nt_id);
}

function tbc_notice_update_file($nt_id, $file)
{
    $notice = tbc_notice_get($nt_id);
    if (!$notice) {
        return array('ok' => false, 'message' => '공지를 찾을 수 없습니다.');
    }

    $stored = tbc_notice_store_file($file);
    if (!$stored['ok']) {
        return $stored;
    }

    if ($notice['nt_file']) {
        tbc_notice_delete_file($notice['nt_file']);
    }

    $filename = sql_real_escape_string($stored['filename']);
    $source = sql_real_escape_string($stored['source']);

    sql_query("
        update `" . tbc_notice_table() . "` set
            nt_file = '{$filename}',
            nt_file_source = '{$source}',
            updated_at = '" . G5_TIME_YMDHIS . "'
        where nt_id = '" . (int) $nt_id . "'
    ");

    return array('ok' => true);
}

function tbc_notice_delete($nt_id)
{
    $notice = tbc_notice_get($nt_id);
    if (!$notice) {
        return array('ok' => false, 'message' => '공지를 찾을 수 없습니다.');
    }

    if ($notice['nt_file']) {
        tbc_notice_delete_file($notice['nt_file']);
    }

    sql_query(" delete from `" . tbc_notice_table() . "` where nt_id = '" . (int) $nt_id . "' ");
    return array('ok' => true);
}

function tbc_notice_format_date($datetime)
{
    if (!$datetime || $datetime === '0000-00-00 00:00:00') {
        return '';
    }

    return date('m-d', strtotime($datetime));
}

function tbc_notice_is_new($datetime, $days = 7)
{
    if (!$datetime) {
        return false;
    }

    $ts = strtotime($datetime);
    if (!$ts) {
        return false;
    }

    return (time() - $ts) <= ($days * 86400);
}

function tbc_notice_view_url($nt_id)
{
    return tbc_page_url('notice_view') . '&nt_id=' . (int) $nt_id;
}

function tbc_notice_list_url($params = array())
{
    $query = array('p' => 'notice');
    foreach ($params as $key => $value) {
        if ($value !== '' && $value !== null) {
            $query[$key] = $value;
        }
    }

    return G5_BBS_URL . '/page.php?' . http_build_query($query);
}

function tbc_notice_paging($total, $page, $per_page, $base_params = array())
{
    $total_pages = max(1, (int) ceil($total / $per_page));
    $page = max(1, min($page, $total_pages));

    return array(
        'total' => $total,
        'page' => $page,
        'per_page' => $per_page,
        'total_pages' => $total_pages,
        'base_params' => $base_params,
    );
}

function tbc_notice_render_paging($paging)
{
    if ($paging['total_pages'] <= 1) {
        return '';
    }

    $page = $paging['page'];
    $total_pages = $paging['total_pages'];
    $params = $paging['base_params'];

    $html = '<nav class="pg_wrap"><span class="pg">';

    if ($page > 1) {
        $html .= '<a href="' . htmlspecialchars(tbc_notice_list_url(array_merge($params, array('page' => $page - 1))), ENT_QUOTES, 'UTF-8') . '" class="pg_page pg_prev">이전</a>';
    }

    $start = max(1, $page - 2);
    $end = min($total_pages, $page + 2);
    for ($i = $start; $i <= $end; $i++) {
        if ($i === $page) {
            $html .= '<strong class="pg_current">' . $i . '<span class="sound_only">페이지</span></strong>';
        } else {
            $html .= '<a href="' . htmlspecialchars(tbc_notice_list_url(array_merge($params, array('page' => $i))), ENT_QUOTES, 'UTF-8') . '" class="pg_page">' . $i . '<span class="sound_only">페이지</span></a>';
        }
    }

    if ($page < $total_pages) {
        $html .= '<a href="' . htmlspecialchars(tbc_notice_list_url(array_merge($params, array('page' => $page + 1))), ENT_QUOTES, 'UTF-8') . '" class="pg_page pg_next">다음</a>';
        $html .= '<a href="' . htmlspecialchars(tbc_notice_list_url(array_merge($params, array('page' => $total_pages))), ENT_QUOTES, 'UTF-8') . '" class="pg_page pg_end">맨끝</a>';
    }

    $html .= '</span></nav>';
    return $html;
}

function tbc_notice_latest($limit = 3)
{
    return tbc_notice_get_list(array('per_page' => $limit, 'page' => 1), true);
}

function tbc_notice_strip_text($content)
{
    $text = strip_tags((string) $content);
    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    $text = str_replace(array("\r\n", "\r", "\n"), ' ', $text);
    $text = preg_replace('/\s+/u', ' ', trim($text));

    return $text;
}

function tbc_notice_excerpt($content, $length = 80)
{
    $text = tbc_notice_strip_text($content);
    if ($text === '') {
        return '';
    }

    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        if (mb_strlen($text, 'UTF-8') <= $length) {
            return $text;
        }

        return rtrim(mb_substr($text, 0, $length, 'UTF-8')) . '...';
    }

    if (strlen($text) <= $length) {
        return $text;
    }

    return rtrim(substr($text, 0, $length)) . '...';
}

function tbc_notice_subject_short($subject, $length = 28)
{
    $text = trim((string) $subject);
    if ($text === '') {
        return '';
    }

    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        if (mb_strlen($text, 'UTF-8') <= $length) {
            return $text;
        }

        return rtrim(mb_substr($text, 0, $length, 'UTF-8')) . '...';
    }

    if (strlen($text) <= $length) {
        return $text;
    }

    return rtrim(substr($text, 0, $length)) . '...';
}
