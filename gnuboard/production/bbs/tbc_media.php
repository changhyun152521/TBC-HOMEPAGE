<?php
/**
 * data/tbc/* 업로드 파일 프록시 (카페24 data 직접 접근 500 방지)
 */
include_once('./_common.php');

$group = isset($_GET['group']) ? preg_replace('/[^a-z_]/', '', $_GET['group']) : '';
$name = isset($_GET['name']) ? basename($_GET['name']) : '';

$groups = array(
    'teacher' => G5_DATA_PATH . '/tbc/teacher',
    'greeting' => G5_DATA_PATH . '/tbc/greeting',
    'banner' => G5_DATA_PATH . '/tbc/banner',
    'academy' => G5_DATA_PATH . '/tbc/academy',
    'schedule' => G5_DATA_PATH . '/tbc/schedule',
    'menu' => G5_DATA_PATH . '/tbc/menu',
    'notice' => G5_DATA_PATH . '/tbc/notice',
    'edu' => G5_DATA_PATH . '/tbc/edu',
    'admission' => G5_DATA_PATH . '/tbc/admission',
);

if (!isset($groups[$group]) || $name === '') {
    http_response_code(404);
    exit;
}

$file = $groups[$group] . '/' . $name;

if (!is_file($file)) {
    http_response_code(404);
    exit;
}

$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
$mimes = array(
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
    'webp' => 'image/webp',
    'pdf' => 'application/pdf',
    'hwp' => 'application/x-hwp',
    'doc' => 'application/msword',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'xls' => 'application/vnd.ms-excel',
    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'zip' => 'application/zip',
);

if ($group === 'admission' && $ext === 'svg') {
    $mimes['svg'] = 'image/svg+xml';
}

if ($group !== 'notice') {
    unset($mimes['pdf'], $mimes['hwp'], $mimes['doc'], $mimes['docx'], $mimes['xls'], $mimes['xlsx'], $mimes['zip']);
}

if (!isset($mimes[$ext])) {
    http_response_code(403);
    exit;
}

header('Content-Type: ' . $mimes[$ext]);
header('Content-Length: ' . filesize($file));
header('Cache-Control: public, max-age=86400');
readfile($file);
exit;
