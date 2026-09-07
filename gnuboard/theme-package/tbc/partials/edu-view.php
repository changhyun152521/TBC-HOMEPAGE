<?php
if (!defined('_GNUBOARD_')) exit;

if (!function_exists('tbc_edu_get_public')) {
    include_once(G5_THEME_PATH . '/tbc.edu.lib.php');
}

$ed_id = isset($_GET['ed_id']) ? (int) $_GET['ed_id'] : 0;
$edu = $ed_id ? tbc_edu_get_public($ed_id) : null;

if (!$edu) {
    echo '<div id="board1006"><p class="edu_view_empty">교육정보를 찾을 수 없습니다.</p></div>';
    return;
}

tbc_edu_increase_hit($ed_id);
$author_initial = function_exists('mb_substr') ? mb_substr($edu['ed_author'], 0, 1, 'UTF-8') : substr($edu['ed_author'], 0, 1);
?>
<div id="board1006" class="edu_view_wrap">
    <div class="edu_band_card">
        <header class="edu_band_head">
            <div class="edu_band_avatar" aria-hidden="true"><?php echo htmlspecialchars($author_initial, ENT_QUOTES, 'UTF-8'); ?></div>
            <div class="edu_band_meta">
                <strong><?php echo htmlspecialchars($edu['ed_author'], ENT_QUOTES, 'UTF-8'); ?></strong>
                <span><?php echo htmlspecialchars(date('Y.m.d H:i', strtotime($edu['created_at'])), ENT_QUOTES, 'UTF-8'); ?> · 조회 <?php echo number_format((int) $edu['ed_hit']); ?></span>
            </div>
        </header>

        <h1 class="sound_only"><?php echo htmlspecialchars($edu['ed_subject'], ENT_QUOTES, 'UTF-8'); ?></h1>

        <div class="edu_band_body">
            <?php echo tbc_edu_render_content($edu['ed_content']); ?>
        </div>
    </div>

    <div class="btn_area">
        <ul class="right">
            <li><a href="<?php echo tbc_edu_list_url(); ?>" class="btn_type01 w_btn">목록</a></li>
        </ul>
    </div>
</div>
