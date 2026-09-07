<?php
/**
 * TBC 교육정보 (갤러리 목록 + 밴드형 상세)
 */
if (!defined('_GNUBOARD_')) exit;

if (!function_exists('tbc_media_url')) {
    include_once(G5_THEME_PATH . '/tbc.lib.php');
}

function tbc_edu_table()
{
    return G5_TABLE_PREFIX . 'tbc_edu';
}

function tbc_edu_file_path()
{
    return G5_DATA_PATH . '/tbc/edu';
}

function tbc_edu_file_url($filename)
{
    return tbc_media_url('edu', $filename);
}

function tbc_edu_ensure_file_dir()
{
    $dir = tbc_edu_file_path();
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

function tbc_edu_table_exists()
{
    $row = sql_fetch(" show tables like '" . tbc_edu_table() . "' ");
    return (bool) $row;
}

function tbc_edu_ensure_tables()
{
    if (tbc_edu_table_exists()) {
        return true;
    }

    $charset = (defined('G5_DB_CHARSET') && G5_DB_CHARSET) ? G5_DB_CHARSET : 'utf8';

    sql_query("
        CREATE TABLE IF NOT EXISTS `" . tbc_edu_table() . "` (
            `ed_id` int(11) NOT NULL AUTO_INCREMENT,
            `ed_subject` varchar(255) NOT NULL DEFAULT '',
            `ed_content` mediumtext NOT NULL,
            `ed_author` varchar(80) NOT NULL DEFAULT '',
            `ed_thumb` varchar(255) NOT NULL DEFAULT '',
            `ed_meta1_dt` varchar(80) NOT NULL DEFAULT '',
            `ed_meta1_dd` varchar(255) NOT NULL DEFAULT '',
            `ed_meta2_dt` varchar(80) NOT NULL DEFAULT '',
            `ed_meta2_dd` varchar(255) NOT NULL DEFAULT '',
            `ed_use` tinyint(4) NOT NULL DEFAULT 1,
            `ed_hit` int(11) NOT NULL DEFAULT 0,
            `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY (`ed_id`),
            KEY `ed_use` (`ed_use`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset}
    ", false);

    return tbc_edu_table_exists();
}

function tbc_edu_seed_defaults()
{
    if (!tbc_edu_ensure_tables()) {
        return false;
    }

    $count = sql_fetch(" select count(*) as cnt from `" . tbc_edu_table() . "` ");
    if ((int) $count['cnt'] > 0) {
        return true;
    }

    $samples = array(
        array(
            'ed_subject' => '모의고사, 이렇게 풀어야 진짜다!',
            'ed_content' => '<p>단순한 해설은 NO! 출제 의도부터 고득점 비법까지, 모의고사 완벽 정복 가이드를 안내합니다.</p>',
            'ed_author' => '더브레인코어',
        ),
        array(
            'ed_subject' => '최신 입시 동향 파헤치기!',
            'ed_content' => '<p>매일 바뀌는 입시 정보, 놓치지 않으셨죠? 최신 입시 동향과 유리한 전략을 준비해 드립니다.</p>',
            'ed_author' => '더브레인코어',
        ),
    );

    $now = G5_TIME_YMDHIS;
    foreach ($samples as $sample) {
        sql_query("
            insert into `" . tbc_edu_table() . "` set
                ed_subject = '" . sql_real_escape_string($sample['ed_subject']) . "',
                ed_content = '" . sql_real_escape_string($sample['ed_content']) . "',
                ed_author = '" . sql_real_escape_string($sample['ed_author']) . "',
                ed_use = 1,
                created_at = '{$now}',
                updated_at = '{$now}'
        ");
    }

    return true;
}

function tbc_edu_allowed_image_ext($filename)
{
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $allowed = array('jpg', 'jpeg', 'png', 'webp', 'gif');
    return in_array($ext, $allowed, true) ? $ext : '';
}

function tbc_edu_validate_upload_file($file)
{
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return array('ok' => false, 'message' => '업로드된 파일이 없습니다.');
    }

    $ext = tbc_edu_allowed_image_ext($file['name']);
    if (!$ext) {
        return array('ok' => false, 'message' => 'jpg, png, webp, gif 이미지만 업로드할 수 있습니다.');
    }

    $info = @getimagesize($file['tmp_name']);
    if (!$info) {
        return array('ok' => false, 'message' => '이미지 파일이 아닙니다.');
    }

    return array('ok' => true, 'ext' => $ext);
}

function tbc_edu_store_file($file)
{
    $check = tbc_edu_validate_upload_file($file);
    if (!$check['ok']) {
        return $check;
    }

    tbc_edu_ensure_file_dir();

    $filename = 'edu_' . date('YmdHis') . '_' . substr(md5(uniqid('', true)), 0, 8) . '.' . $check['ext'];
    $dest = tbc_edu_file_path() . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return array('ok' => false, 'message' => '파일 저장에 실패했습니다.');
    }

    @chmod($dest, G5_FILE_PERMISSION);

    return array(
        'ok' => true,
        'filename' => $filename,
        'url' => tbc_edu_file_url($filename),
    );
}

function tbc_edu_delete_file($filename)
{
    if (!$filename) {
        return;
    }

    $path = tbc_edu_file_path() . '/' . basename($filename);
    if (is_file($path)) {
        @unlink($path);
    }
}

function tbc_edu_sanitize_html($html)
{
    $html = (string) $html;
    if ($html === '') {
        return '';
    }

    $allowed = '<p><br><strong><b><em><i><u><a><img><ul><ol><li><h2><h3><h4><blockquote><div><span><figure><figcaption>';
    $html = strip_tags($html, $allowed);

    $html = preg_replace_callback('/<a\s+([^>]*?)>/iu', function ($matches) {
        $attrs = $matches[1];
        $href = '';
        if (preg_match('/href\s*=\s*("|\')([^"\']*)("|\')/iu', $attrs, $m)) {
            $href = html_entity_decode($m[2], ENT_QUOTES, 'UTF-8');
        }
        if ($href && !preg_match('/^(https?:\/\/|mailto:|tel:|#|\/)/iu', $href)) {
            return '<a>';
        }
        return '<a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener">';
    }, $html);

    $html = preg_replace_callback('/<img\s+([^>]*?)>/iu', function ($matches) {
        $attrs = $matches[1];
        $src = '';
        $alt = '';
        if (preg_match('/src\s*=\s*("|\')([^"\']*)("|\')/iu', $attrs, $m)) {
            $src = $m[2];
        }
        if (preg_match('/alt\s*=\s*("|\')([^"\']*)("|\')/iu', $attrs, $m)) {
            $alt = html_entity_decode($m[2], ENT_QUOTES, 'UTF-8');
        }

        $canonical = tbc_edu_canonical_media_url($src);
        if ($canonical === '') {
            return '';
        }

        return '<img src="' . htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($alt, ENT_QUOTES, 'UTF-8') . '">';
    }, $html);

    return trim($html);
}

function tbc_edu_canonical_media_url($src)
{
    $src = html_entity_decode(trim((string) $src), ENT_QUOTES, 'UTF-8');
    if ($src === '') {
        return '';
    }

    if (!preg_match('/tbc_media\.php\?(.+)$/iu', $src, $matches)) {
        return '';
    }

    $query = str_replace('&amp;', '&', $matches[1]);
    $params = array();
    parse_str($query, $params);

    if (!isset($params['group']) || $params['group'] !== 'edu' || empty($params['name'])) {
        return '';
    }

    $filename = basename($params['name']);
    if ($filename === '') {
        return '';
    }

    return tbc_edu_file_url($filename);
}

function tbc_edu_filename_from_url($url)
{
    $canonical = tbc_edu_canonical_media_url($url);
    if ($canonical === '' || !preg_match('/\?(.+)$/u', $canonical, $matches)) {
        return '';
    }

    $params = array();
    parse_str($matches[1], $params);

    return !empty($params['name']) ? basename($params['name']) : '';
}

function tbc_edu_collect_filenames_from_html($html)
{
    $files = array();
    $html = html_entity_decode((string) $html, ENT_QUOTES, 'UTF-8');

    if (preg_match_all('/<img[^>]+src=(["\'])([^"\']+)\1/iu', $html, $matches)) {
        foreach ($matches[2] as $src) {
            $canonical = tbc_edu_canonical_media_url($src);
            if ($canonical !== '' && preg_match('/\?(.+)$/u', $canonical, $query_match)) {
                $params = array();
                parse_str($query_match[1], $params);
                if (!empty($params['name'])) {
                    $files[] = basename($params['name']);
                }
            }
        }
    }

    return array_values(array_unique($files));
}

function tbc_edu_resolve_thumb($row)
{
    if (!empty($row['ed_thumb'])) {
        return tbc_edu_file_url($row['ed_thumb']);
    }

    $files = tbc_edu_collect_filenames_from_html($row['ed_content']);
    if ($files) {
        return tbc_edu_file_url($files[0]);
    }

    return '';
}

function tbc_edu_build_where($filters = array(), $public_only = false)
{
    $where = array('1=1');

    if ($public_only) {
        $where[] = 'ed_use = 1';
    }

    if (!empty($filters['q'])) {
        $q = sql_real_escape_string($filters['q']);
        $where[] = "(ed_subject like '%{$q}%' or ed_content like '%{$q}%' or ed_author like '%{$q}%')";
    }

    if (!empty($filters['sfl']) && !empty($filters['stx'])) {
        $stx = sql_real_escape_string($filters['stx']);
        $sfl = $filters['sfl'];
        if ($sfl === 'subject') {
            $where[] = "ed_subject like '%{$stx}%'";
        } elseif ($sfl === 'content') {
            $where[] = "ed_content like '%{$stx}%'";
        } elseif ($sfl === 'subject_content') {
            $where[] = "(ed_subject like '%{$stx}%' or ed_content like '%{$stx}%')";
        } elseif ($sfl === 'author') {
            $where[] = "ed_author like '%{$stx}%'";
        }
    }

    return $where;
}

function tbc_edu_count($filters = array(), $public_only = false)
{
    tbc_edu_ensure_tables();
    $where = tbc_edu_build_where($filters, $public_only);
    $row = sql_fetch(" select count(*) as cnt from `" . tbc_edu_table() . "` where " . implode(' and ', $where));
    return (int) $row['cnt'];
}

function tbc_edu_get_list($filters = array(), $public_only = false)
{
    tbc_edu_ensure_tables();

    $where = tbc_edu_build_where($filters, $public_only);
    $page = isset($filters['page']) ? max(1, (int) $filters['page']) : 1;
    $per_page = isset($filters['per_page']) ? max(1, (int) $filters['per_page']) : 10;
    $offset = ($page - 1) * $per_page;

    $rows = array();
    $sql = "
        select * from `" . tbc_edu_table() . "`
        where " . implode(' and ', $where) . "
        order by ed_id desc
        limit {$offset}, {$per_page}
    ";
    $result = sql_query($sql);
    while ($row = sql_fetch_array($result)) {
        $rows[] = $row;
    }

    return $rows;
}

function tbc_edu_get($ed_id)
{
    tbc_edu_ensure_tables();
    $ed_id = (int) $ed_id;
    if (!$ed_id) {
        return null;
    }

    return sql_fetch(" select * from `" . tbc_edu_table() . "` where ed_id = '{$ed_id}' ");
}

function tbc_edu_get_public($ed_id)
{
    $row = tbc_edu_get($ed_id);
    if (!$row || !$row['ed_use']) {
        return null;
    }

    return $row;
}

function tbc_edu_increase_hit($ed_id)
{
    $ed_id = (int) $ed_id;
    if (!$ed_id) {
        return;
    }

    sql_query(" update `" . tbc_edu_table() . "` set ed_hit = ed_hit + 1 where ed_id = '{$ed_id}' ");
}

function tbc_edu_save($data, $ed_id = 0)
{
    tbc_edu_ensure_tables();

    $subject = trim($data['ed_subject']);
    $content = tbc_edu_sanitize_html($data['ed_content']);
    $author = trim($data['ed_author']);
    $use = !empty($data['ed_use']) ? 1 : 0;
    $thumb = isset($data['ed_thumb']) ? trim($data['ed_thumb']) : '';

    $has_media = (bool) preg_match('/<img\b/i', $content);
    $plain = trim(strip_tags($content));

    if ($subject === '' || ($plain === '' && !$has_media)) {
        return array('ok' => false, 'message' => '제목과 내용은 필수입니다.');
    }

    if ($author === '') {
        $author = '관리자';
    }

    if ($thumb === '') {
        $files = tbc_edu_collect_filenames_from_html($content);
        if ($files) {
            $thumb = $files[0];
        }
    }

    $subject_sql = sql_real_escape_string($subject);
    $content_sql = sql_real_escape_string($content);
    $author_sql = sql_real_escape_string($author);
    $thumb_sql = sql_real_escape_string($thumb);
    $now = G5_TIME_YMDHIS;

    if ($ed_id) {
        sql_query("
            update `" . tbc_edu_table() . "` set
                ed_subject = '{$subject_sql}',
                ed_content = '{$content_sql}',
                ed_author = '{$author_sql}',
                ed_thumb = '{$thumb_sql}',
                ed_use = '{$use}',
                updated_at = '{$now}'
            where ed_id = '" . (int) $ed_id . "'
        ");
    } else {
        sql_query("
            insert into `" . tbc_edu_table() . "` set
                ed_subject = '{$subject_sql}',
                ed_content = '{$content_sql}',
                ed_author = '{$author_sql}',
                ed_thumb = '{$thumb_sql}',
                ed_use = '{$use}',
                created_at = '{$now}',
                updated_at = '{$now}'
        ");
        $ed_id = sql_insert_id();
    }

    return array('ok' => true, 'ed_id' => (int) $ed_id);
}

function tbc_edu_update_thumb($ed_id, $file, $remove_current = false)
{
    $edu = tbc_edu_get($ed_id);
    if (!$edu) {
        return array('ok' => false, 'message' => '게시글을 찾을 수 없습니다.');
    }

    if ($remove_current && $edu['ed_thumb']) {
        tbc_edu_delete_file($edu['ed_thumb']);
        sql_query(" update `" . tbc_edu_table() . "` set ed_thumb = '', updated_at = '" . G5_TIME_YMDHIS . "' where ed_id = '" . (int) $ed_id . "' ");
        return array('ok' => true, 'filename' => '');
    }

    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return array('ok' => false, 'message' => '업로드된 파일이 없습니다.');
    }

    $stored = tbc_edu_store_file($file);
    if (!$stored['ok']) {
        return $stored;
    }

    if ($edu['ed_thumb']) {
        tbc_edu_delete_file($edu['ed_thumb']);
    }

    $filename = sql_real_escape_string($stored['filename']);
    sql_query("
        update `" . tbc_edu_table() . "` set
            ed_thumb = '{$filename}',
            updated_at = '" . G5_TIME_YMDHIS . "'
        where ed_id = '" . (int) $ed_id . "'
    ");

    return array('ok' => true, 'filename' => $stored['filename'], 'url' => $stored['url']);
}

function tbc_edu_delete($ed_id)
{
    $edu = tbc_edu_get($ed_id);
    if (!$edu) {
        return array('ok' => false, 'message' => '게시글을 찾을 수 없습니다.');
    }

    $files = tbc_edu_collect_filenames_from_html($edu['ed_content']);
    if ($edu['ed_thumb']) {
        $files[] = $edu['ed_thumb'];
    }
    $files = array_unique($files);
    foreach ($files as $filename) {
        tbc_edu_delete_file($filename);
    }

    sql_query(" delete from `" . tbc_edu_table() . "` where ed_id = '" . (int) $ed_id . "' ");
    return array('ok' => true);
}

function tbc_edu_strip_text($content)
{
    $text = strip_tags((string) $content);
    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    $text = str_replace(array("\r\n", "\r", "\n"), ' ', $text);
    $text = preg_replace('/\s+/u', ' ', trim($text));

    return $text;
}

function tbc_edu_excerpt($content, $length = 80)
{
    $text = tbc_edu_strip_text($content);
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

function tbc_edu_is_new($datetime, $days = 7)
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

function tbc_edu_view_url($ed_id)
{
    return tbc_page_url('edu_view') . '&ed_id=' . (int) $ed_id;
}

function tbc_edu_list_url($params = array())
{
    $query = array('p' => 'edu');
    foreach ($params as $key => $value) {
        if ($value !== '' && $value !== null) {
            $query[$key] = $value;
        }
    }

    return G5_BBS_URL . '/page.php?' . http_build_query($query);
}

function tbc_edu_paging($total, $page, $per_page, $base_params = array())
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

function tbc_edu_render_paging($paging)
{
    if ($paging['total_pages'] <= 1) {
        return '';
    }

    $page = $paging['page'];
    $total_pages = $paging['total_pages'];
    $params = $paging['base_params'];

    $html = '<nav class="pg_wrap"><span class="pg">';

    if ($page > 1) {
        $html .= '<a href="' . htmlspecialchars(tbc_edu_list_url(array_merge($params, array('page' => $page - 1))), ENT_QUOTES, 'UTF-8') . '" class="pg_page pg_prev">이전</a>';
    }

    $start = max(1, $page - 2);
    $end = min($total_pages, $page + 2);
    for ($i = $start; $i <= $end; $i++) {
        if ($i === $page) {
            $html .= '<strong class="pg_current">' . $i . '<span class="sound_only">페이지</span></strong>';
        } else {
            $html .= '<a href="' . htmlspecialchars(tbc_edu_list_url(array_merge($params, array('page' => $i))), ENT_QUOTES, 'UTF-8') . '" class="pg_page">' . $i . '<span class="sound_only">페이지</span></a>';
        }
    }

    if ($page < $total_pages) {
        $html .= '<a href="' . htmlspecialchars(tbc_edu_list_url(array_merge($params, array('page' => $page + 1))), ENT_QUOTES, 'UTF-8') . '" class="pg_page pg_next">다음</a>';
        $html .= '<a href="' . htmlspecialchars(tbc_edu_list_url(array_merge($params, array('page' => $total_pages))), ENT_QUOTES, 'UTF-8') . '" class="pg_page pg_end">맨끝</a>';
    }

    $html .= '</span></nav>';
    return $html;
}

function tbc_edu_latest($limit = 4)
{
    return tbc_edu_get_list(array('per_page' => $limit, 'page' => 1), true);
}

function tbc_edu_render_list_summary($row)
{
    $text = tbc_edu_strip_text($row['ed_content']);
    if ($text === '') {
        return '';
    }

    return '<p class="edu_summary">' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</p>';
}

function tbc_edu_render_content($html)
{
    return tbc_edu_sanitize_html($html);
}
