<?php
/**
 * TBC 커스텀 페이지 라우터
 * 예: /bbs/page.php?p=greeting
 */
include_once('./_common.php');

$p = isset($_GET['p']) ? preg_replace('/[^a-z0-9_]/', '', $_GET['p']) : '';

$titles = array(
    'greeting' => '더브코-인사말',
    'philosophy' => '더브코-교육철학',
    'history' => '더브코-연혁',
    'academies' => '분원',
    'academies_main' => '본원 · 대전 둔산',
    'academies_branch' => '분원 안내',
    'teachers' => '전체 강사진',
    'teachers_korean' => '국어 강사진',
    'teachers_math' => '수학 강사진',
    'teachers_science' => '과학 강사진',
    'teachers_english' => '영어 강사진',
    'teachers_social' => '사회 강사진',
    'schedule' => '전체 시간표',
    'schedule_main' => '본원 시간표',
    'schedule_branch' => '분원 시간표',
    'notice' => '공지사항',
    'notice_view' => '공지사항',
    'edu' => '교육정보',
    'edu_view' => '교육정보',
    'admission' => '입학절차',
    'faq' => 'FAQ',
    'consult' => '상담신청',
);

$page_file = G5_THEME_PATH . '/pages/' . $p . '.php';

if (!$p || !isset($titles[$p]) || !is_file($page_file)) {
    alert('페이지를 찾을 수 없습니다.', G5_URL);
}

$g5['title'] = $titles[$p];

include_once(G5_THEME_PATH . '/head.php');
include_once($page_file);
include_once(G5_THEME_PATH . '/tail.php');
