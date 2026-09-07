<?php
if (!defined('_GNUBOARD_')) exit;

$greeting = tbc_greeting_get_config();
$greeting_image = tbc_greeting_resolve_image_url($greeting['gr_image']);
?>
<div id="greeting" class="pagecommon">
    <div class="tit_area">
        <?php echo tbc_greeting_format_html($greeting['heading']); ?><br />
        <p><b><?php echo htmlspecialchars($greeting['subheading_bold'], ENT_QUOTES, 'UTF-8'); ?></b><?php echo htmlspecialchars($greeting['subheading'], ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
    <div class="img" style="background-image:url(<?php echo $greeting_image; ?>);"></div>
    <div class="cont">
        <?php echo tbc_greeting_render_body($greeting['body']); ?>
        <p class="sign"><?php echo htmlspecialchars($greeting['sign_org'], ENT_QUOTES, 'UTF-8'); ?> <span><?php echo htmlspecialchars($greeting['sign_name'], ENT_QUOTES, 'UTF-8'); ?></span></p>
    </div>
</div>
