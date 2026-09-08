<?php
if (!defined('_GNUBOARD_')) exit;

$main_config = tbc_main_get_config();
$main_banners = tbc_main_get_banners();
$band_links = tbc_band_get_public_list();
$band_single_url = count($band_links) === 1 ? $band_links[0]['bd_url'] : '';
?>
<div class="m_txt">
    <h1 class="ko_tit"><?php echo tbc_main_format_title_html($main_config['main_title']); ?></h1>
    <?php echo htmlspecialchars($main_config['main_subtitle'], ENT_QUOTES, 'UTF-8'); ?>
    <?php if (count($band_links) === 1 && $band_single_url) { ?>
    <a href="<?php echo htmlspecialchars($band_single_url, ENT_QUOTES, 'UTF-8'); ?>" class="more" target="_blank" rel="noopener noreferrer">BAND 바로가기 <img src="<?php echo G5_THEME_URL; ?>/img/main/main_deco.png" alt="메인데코"></a>
    <?php } elseif (count($band_links) > 1) { ?>
    <a href="#" class="more js-tbc-band-open" role="button">BAND 바로가기 <img src="<?php echo G5_THEME_URL; ?>/img/main/main_deco.png" alt="메인데코"></a>
    <?php } elseif (defined('TBC_BAND_URL') && TBC_BAND_URL) { ?>
    <a href="<?php echo TBC_BAND_URL; ?>" class="more" target="_blank" rel="noopener noreferrer">BAND 바로가기 <img src="<?php echo G5_THEME_URL; ?>/img/main/main_deco.png" alt="메인데코"></a>
    <?php } ?>
</div><div class="slide_wrap">
    <div class="swiper main_slide">
        <ul class="main_slide_box swiper-wrapper">
            <?php foreach ($main_banners as $banner) { ?>
            <li class="swiper-slide" style="background-image:url('<?php echo htmlspecialchars($banner['image_url'], ENT_QUOTES, 'UTF-8'); ?>')"></li>
            <?php } ?>
        </ul>
        <div class="arrow_btn">
            <div class="page_num"></div>
            <div class="controls">
                <div class="btn_pager bnr-prev"><img src="<?php echo G5_THEME_URL; ?>/img/main/arr_left.png" alt="prev"/></div>
                <div class="play">
                    <div class="swiper-pause"><span class="material-symbols-outlined"><img src="<?php echo G5_THEME_URL; ?>/img/main/stop_icon.png" alt="stop"/></span></div>
                    <div class="swiper-play"><span class="material-symbols-outlined"><img src="<?php echo G5_THEME_URL; ?>/img/main/start_icon.png" alt="start" /></span></div>
                </div>
                <div class="btn_pager bnr-next"><img src="<?php echo G5_THEME_URL; ?>/img/main/arr_right.png" alt="next"/></div>
            </div>
        </div>
    </div>
</div>
