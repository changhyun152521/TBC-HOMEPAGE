<?php
if (!defined('_GNUBOARD_')) exit;

$main_config = tbc_main_get_config();
?>
<div class="ko_box">
    <p><?php echo htmlspecialchars($main_config['section01_tagline'], ENT_QUOTES, 'UTF-8'); ?></p>
    <h2 class="tit"><?php echo tbc_main_format_title_html($main_config['section01_title']); ?></h2>
</div>
