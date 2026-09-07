<?php
if (!defined('_GNUBOARD_')) exit;

if (!function_exists('tbc_notice_get_public')) {
    include_once(G5_THEME_PATH . '/tbc.notice.lib.php');
}

$nt_id = isset($_GET['nt_id']) ? (int) $_GET['nt_id'] : 0;
$notice = $nt_id ? tbc_notice_get_public($nt_id) : null;

if (!$notice) {
    echo '<div id="board1001"><p class="notice_view_empty">공지사항을 찾을 수 없습니다.</p></div>';
    return;
}

tbc_notice_increase_hit($nt_id);
$file_url = $notice['nt_file'] ? tbc_notice_file_url($notice['nt_file']) : '';
?>
<div id="board1001" class="notice_view_wrap">
    <div class="notice_view_head">
        <h1><?php echo htmlspecialchars($notice['nt_subject'], ENT_QUOTES, 'UTF-8'); ?></h1>
        <div class="notice_view_meta">
            <span>작성자 <?php echo htmlspecialchars($notice['nt_author'], ENT_QUOTES, 'UTF-8'); ?></span>
            <span>등록일 <?php echo htmlspecialchars(date('Y-m-d', strtotime($notice['created_at'])), ENT_QUOTES, 'UTF-8'); ?></span>
            <span>조회 <?php echo number_format((int) $notice['nt_hit']); ?></span>
        </div>
    </div>

    <div class="notice_view_body">
        <?php echo nl2br(htmlspecialchars($notice['nt_content'], ENT_QUOTES, 'UTF-8')); ?>
    </div>

    <?php if ($file_url) { ?>
    <div class="notice_view_file">
        <strong>첨부파일</strong>
        <a href="<?php echo htmlspecialchars($file_url, ENT_QUOTES, 'UTF-8'); ?>" download="<?php echo htmlspecialchars($notice['nt_file_source'] ?: $notice['nt_file'], ENT_QUOTES, 'UTF-8'); ?>">
            <?php echo htmlspecialchars($notice['nt_file_source'] ?: $notice['nt_file'], ENT_QUOTES, 'UTF-8'); ?>
        </a>
    </div>
    <?php } ?>

    <div class="btn_area">
        <ul class="right">
            <li><a href="<?php echo tbc_notice_list_url(); ?>" class="btn_type01 w_btn">목록</a></li>
        </ul>
    </div>
</div>
