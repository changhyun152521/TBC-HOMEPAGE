<?php
if (!defined('_GNUBOARD_')) exit;

include_once(dirname(__FILE__) . '/_common.php');

$default_title = defined('TBC_SITE_TITLE') ? TBC_SITE_TITLE : $config['cf_title'];
$raw_title = isset($g5['title']) ? $g5['title'] : $default_title;
$g5['title'] = tbc_site_title($raw_title);
$og_title = (defined('_INDEX_') && _INDEX_) ? TBC_SITE_TITLE : $g5['title'];
?>
<!doctype html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="imagetoolbar" content="no">
    <meta name="description" content="대전·세종을 대표하는 교육 브랜드 더브레인코어">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?php echo TBC_SITE_TITLE; ?>">
    <meta property="og:title" content="<?php echo $og_title; ?>">
    <meta property="og:description" content="대전·세종을 대표하는 교육 브랜드 더브레인코어">
    <meta property="og:image" content="<?php echo G5_THEME_URL; ?>/img/open/open.png">
    <meta property="og:image:secure_url" content="<?php echo G5_THEME_URL; ?>/img/open/open.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1280">
    <meta property="og:image:height" content="720">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="<?php echo G5_THEME_URL; ?>/img/open/open.png">
    <meta property="og:url" content="<?php echo G5_URL; ?>">

    <link rel="icon" href="<?php echo G5_THEME_URL; ?>/favicon.ico" sizes="any">
    <link rel="icon" type="image/png" href="<?php echo G5_THEME_URL; ?>/img/open/favicon-32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="<?php echo G5_THEME_URL; ?>/img/open/favicon-48.png" sizes="48x48">
    <link rel="apple-touch-icon" href="<?php echo G5_THEME_URL; ?>/img/open/apple-touch-icon.png">
    <title><?php echo $g5['title']; ?></title>

    <script src="<?php echo G5_THEME_URL; ?>/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo G5_THEME_URL; ?>/js/jquery-ui.js"></script>
    <script src="<?php echo G5_THEME_URL; ?>/js/topmenu_script.js"></script>
    <script src="<?php echo G5_THEME_URL; ?>/js/swiper.min.js"></script>
    <script src="<?php echo G5_THEME_URL; ?>/js/aos.js"></script>
    <script src="<?php echo G5_THEME_URL; ?>/js/feather.min.js"></script>
    <link rel="stylesheet" href="<?php echo G5_THEME_URL; ?>/css/swiper.min.css">
    <link rel="stylesheet" href="<?php echo G5_THEME_URL; ?>/css/sh_common.css">
    <?php if (defined('_INDEX_')) { ?>
    <link rel="stylesheet" href="<?php echo G5_THEME_URL; ?>/css/sh_main.css">
    <?php } else { ?>
    <link rel="stylesheet" href="<?php echo G5_THEME_URL; ?>/css/sh_sub.css">
    <?php } ?>
    <link rel="stylesheet" href="<?php echo G5_THEME_URL; ?>/css/aos.css">
    <link rel="stylesheet" href="<?php echo G5_THEME_URL; ?>/css/tbc_custom.css?v=<?php echo @filemtime(G5_THEME_PATH . '/css/tbc_custom.css'); ?>">
    <?php if (tbc_is_page('academies') || tbc_is_page('academies_main') || tbc_is_page('academies_branch')) { ?>
    <link rel="stylesheet" href="<?php echo G5_THEME_URL; ?>/css/location1001.css">
    <?php } ?>
    <?php if (strpos(isset($_GET['p']) ? $_GET['p'] : '', 'teachers') !== false) { ?>
    <link rel="stylesheet" href="<?php echo G5_THEME_URL; ?>/css/members1005.css">
    <script>window.TBC_THEME_URL = <?php echo json_encode(G5_THEME_URL); ?>;</script>
    <script src="<?php echo G5_THEME_URL; ?>/js/teacher-modal.js"></script>
    <?php } ?>
    <?php if (strpos(isset($_GET['p']) ? $_GET['p'] : '', 'schedule') !== false) { ?>
    <link rel="stylesheet" href="<?php echo G5_THEME_URL; ?>/css/schedule.css?v=<?php echo @filemtime(G5_THEME_PATH . '/css/schedule.css'); ?>">
    <?php } ?>
    <?php if (tbc_is_page('history')) { ?>
    <link rel="stylesheet" href="<?php echo G5_THEME_URL; ?>/css/history.css">
    <?php } ?>
    <?php if (tbc_is_page('notice') || tbc_is_page('notice_view') || tbc_is_page('edu') || tbc_is_page('edu_view')) { ?>
    <link rel="stylesheet" href="<?php echo G5_THEME_URL; ?>/css/board-search.css?v=<?php echo @filemtime(G5_THEME_PATH . '/css/board-search.css'); ?>">
    <?php } ?>
    <?php if (tbc_is_page('notice') || tbc_is_page('notice_view')) { ?>
    <link rel="stylesheet" href="<?php echo G5_THEME_URL; ?>/css/board1001.css?v=<?php echo @filemtime(G5_THEME_PATH . '/css/board1001.css'); ?>">
    <?php } ?>
    <?php if (tbc_is_page('edu') || tbc_is_page('edu_view')) { ?>
    <link rel="stylesheet" href="<?php echo G5_THEME_URL; ?>/css/board1006.css?v=<?php echo @filemtime(G5_THEME_PATH . '/css/board1006.css'); ?>">
    <?php } ?>
    <?php if (tbc_is_page('admission')) { ?>
    <link rel="stylesheet" href="<?php echo G5_THEME_URL; ?>/css/service1003.css?v=<?php echo @filemtime(G5_THEME_PATH . '/css/service1003.css'); ?>">
    <?php } ?>
    <?php if (tbc_is_page('consult')) { ?>
    <link rel="stylesheet" href="<?php echo G5_THEME_URL; ?>/css/consult.css?v=<?php echo @filemtime(G5_THEME_PATH . '/css/consult.css'); ?>">
    <?php } ?>
    <?php if (!defined('_INDEX_') && function_exists('tbc_menu_sub_banner_url')) {
        $tbc_sub_banner_url = tbc_menu_sub_banner_url();
        if ($tbc_sub_banner_url) { ?>
    <style>#shSubBnr #mainImg{background-image:url('<?php echo htmlspecialchars($tbc_sub_banner_url, ENT_QUOTES, 'UTF-8'); ?>') !important;}</style>
    <?php } } ?>
    <?php
    if ($config['cf_add_script']) {
        echo $config['cf_add_script'] . PHP_EOL;
    }
    ?>
</head>
<body>
<?php
$__tbc_nav = dirname(__FILE__) . '/nav.php';
if (!is_file($__tbc_nav)) {
    $__tbc_nav = dirname(__FILE__) . '/nav.inc.php';
}
if (is_file($__tbc_nav)) {
    include_once($__tbc_nav);
}
