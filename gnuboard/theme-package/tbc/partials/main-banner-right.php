<?php
if (!defined('_GNUBOARD_')) exit;

$main_config = tbc_main_get_config();
?>
<div class="top_cont">
    <div class="tit">
        <img src="<?php echo G5_THEME_URL; ?>/img/main/main_shine.png" alt="메인전구">
        <?php echo htmlspecialchars($main_config['right_tagline'], ENT_QUOTES, 'UTF-8'); ?>
    </div>
    <h2 class="txt"><?php echo tbc_main_format_title_html($main_config['right_title']); ?></h2>
</div>
