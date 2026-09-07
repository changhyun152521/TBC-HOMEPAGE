<?php
if (!defined('_GNUBOARD_')) exit;

if (!function_exists('tbc_edu_latest')) {
    include_once(G5_THEME_PATH . '/tbc.edu.lib.php');
}

$tbc_edu_banner_posts = tbc_edu_latest(6);
$tbc_edu_banner_slides = array();
$fallback_banners = array('banner01.png', 'banner02.png');

foreach ($tbc_edu_banner_posts as $tbc_edu_banner_row) {
    $thumb_url = tbc_edu_resolve_thumb($tbc_edu_banner_row);
    if (!$thumb_url) {
        continue;
    }

    $tag = trim($tbc_edu_banner_row['ed_meta2_dt']);
    if ($tag === '') {
        $tag = '교육정보';
    }

    $author = trim($tbc_edu_banner_row['ed_author']);
    $meta_label = $author;
    if ($author && $tag !== '교육정보') {
        $meta_label = $author . ' ' . $tag;
    } elseif ($author === '') {
        $meta_label = $tag;
    }

    $tbc_edu_banner_slides[] = array(
        'ed_id' => $tbc_edu_banner_row['ed_id'],
        'subject' => $tbc_edu_banner_row['ed_subject'],
        'summary' => tbc_edu_strip_text($tbc_edu_banner_row['ed_content']),
        'meta_label' => $meta_label,
        'tag' => $tag,
        'thumb_url' => $thumb_url,
        'view_url' => tbc_edu_view_url($tbc_edu_banner_row['ed_id']),
    );
}

if (!$tbc_edu_banner_slides) {
    foreach ($fallback_banners as $banner_file) {
        $tbc_edu_banner_slides[] = array(
            'ed_id' => 0,
            'subject' => '기초부터 상위권까지, 성적이 오르는 수학 루틴!',
            'summary' => '',
            'meta_label' => '더브레인코어 고등관 수학',
            'tag' => '고등관 수학',
            'thumb_url' => G5_THEME_URL . '/img/main/inc03/' . $banner_file,
            'view_url' => tbc_page_url('edu'),
        );
    }
}

$tbc_edu_banner_first = $tbc_edu_banner_slides[0];
?>
<div class="swiper edu_banner_slide" id="tbcEduBannerSlide">
    <ul class="swiper-wrapper">
        <?php foreach ($tbc_edu_banner_slides as $tbc_edu_banner_slide) { ?>
        <li class="swiper-slide"
            data-url="<?php echo htmlspecialchars($tbc_edu_banner_slide['view_url'], ENT_QUOTES, 'UTF-8'); ?>"
            data-subject="<?php echo htmlspecialchars($tbc_edu_banner_slide['subject'], ENT_QUOTES, 'UTF-8'); ?>"
            data-summary="<?php echo htmlspecialchars($tbc_edu_banner_slide['summary'], ENT_QUOTES, 'UTF-8'); ?>"
            data-meta="<?php echo htmlspecialchars($tbc_edu_banner_slide['meta_label'], ENT_QUOTES, 'UTF-8'); ?>"
            data-tag="<?php echo htmlspecialchars($tbc_edu_banner_slide['tag'], ENT_QUOTES, 'UTF-8'); ?>">
            <a href="<?php echo htmlspecialchars($tbc_edu_banner_slide['view_url'], ENT_QUOTES, 'UTF-8'); ?>">
                <img src="<?php echo htmlspecialchars($tbc_edu_banner_slide['thumb_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($tbc_edu_banner_slide['subject'], ENT_QUOTES, 'UTF-8'); ?>">
            </a>
        </li>
        <?php } ?>
    </ul>
</div>
<div class="bot_txt">
    <div class="left">
        <a href="<?php echo htmlspecialchars($tbc_edu_banner_first['view_url'], ENT_QUOTES, 'UTF-8'); ?>" id="tbcEduBannerLink">
            <p id="tbcEduBannerSubject"><?php echo htmlspecialchars($tbc_edu_banner_first['subject'], ENT_QUOTES, 'UTF-8'); ?></p>
            <div class="date" id="tbcEduBannerMeta"><?php echo htmlspecialchars($tbc_edu_banner_first['meta_label'], ENT_QUOTES, 'UTF-8'); ?></div>
        </a>
    </div>
    <div class="right">
        <div class="video_area">
            <ul>
                <li>
                    <p class="gall_img_info">
                        <span id="tbcEduBannerTag"><?php echo htmlspecialchars($tbc_edu_banner_first['tag'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </p>
                </li>
            </ul>
        </div>
    </div>
</div>
