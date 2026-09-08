<?php
if (!defined('_GNUBOARD_')) exit;

$tbc_academy_nav = 'all';
$tbc_academy_crumb = '전체';
$tbc_academy_title = '전체 분원';
?>
<main id="sh_container">
    <div id="sh_container_wrapper">
        <div id="sub_main_banner">
            <div id="shSubBnr">
                <div class="sub_nav">
                    <p class="crumb" data-aos="fade-up" data-aos-delay="300">
                        <a href="<?php echo G5_URL; ?>"><i data-feather="home"></i></a>분원 <i data-feather="chevron-right"></i><?php echo $tbc_academy_crumb; ?>
                    </p>
                    <p class="tit" data-aos="fade-up" data-aos-delay="600"><?php echo $tbc_academy_title; ?></p>
                </div>
                <div id="mainImg" data-aos="fade-in" style="background-image:url(<?php echo G5_THEME_URL; ?>/img/sub/bg_10.jpg);"></div>
            </div>
        </div>

        <?php include G5_THEME_PATH . '/partials/academies-aside.php'; ?>

        <div id="sh_content">
            <div id="location1001" class="pagecommon">
                <?php
                $tbc_academy_type = '';
                include G5_THEME_PATH . '/partials/academies-list.php';
                ?>
            </div>
        </div>
    </div>
</main>
