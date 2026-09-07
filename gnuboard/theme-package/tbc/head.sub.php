<?php
if (!defined('_GNUBOARD_')) exit;

$g5['title'] = isset($g5['title']) ? $g5['title'] : $config['cf_title'];
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
    <meta property="og:title" content="<?php echo $g5['title']; ?>">
    <meta property="og:description" content="대전·세종을 대표하는 교육 브랜드 더브레인코어">
    <meta property="og:image" content="<?php echo G5_THEME_URL; ?>/img/open/open.png">
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
    <link rel="stylesheet" href="<?php echo G5_THEME_URL; ?>/css/tbc_custom.css">
    <?php if (tbc_is_page('academies') || tbc_is_page('academies_main') || tbc_is_page('academies_branch')) { ?>
    <link rel="stylesheet" href="<?php echo G5_THEME_URL; ?>/css/location1001.css">
    <?php } ?>
    <?php if (strpos(isset($_GET['p']) ? $_GET['p'] : '', 'teachers') !== false) { ?>
    <link rel="stylesheet" href="<?php echo G5_THEME_URL; ?>/css/members1005.css">
    <script src="<?php echo G5_THEME_URL; ?>/js/teacher-modal.js"></script>
    <?php } ?>
    <?php if (strpos(isset($_GET['p']) ? $_GET['p'] : '', 'schedule') !== false) { ?>
    <link rel="stylesheet" href="<?php echo G5_THEME_URL; ?>/css/schedule.css">
    <script src="<?php echo G5_THEME_URL; ?>/js/schedule.js"></script>
    <?php } ?>
    <?php
    if ($config['cf_add_script']) {
        echo $config['cf_add_script'] . PHP_EOL;
    }
    ?>
</head>
<body>
