<?php
if (!defined('_GNUBOARD_')) exit;
?>
<main id="sh_container">
    <div id="sh_container_wrapper">
        <div id="sub_main_banner">
            <div id="shSubBnr">
                <div class="sub_nav">
                    <p class="crumb" data-aos="fade-up" data-aos-delay="300">
                        <a href="<?php echo G5_URL; ?>"><i data-feather="home"></i></a>입학안내 <i data-feather="chevron-right"></i>입학절차
                    </p>
                    <p class="tit" data-aos="fade-up" data-aos-delay="600">입학절차</p>
                </div>
                <div id="mainImg" data-aos="fade-in" style="background-image:url(<?php echo G5_THEME_URL; ?>/img/sub/bg_10.jpg);"></div>
            </div>
        </div>

        <?php
        $tbc_admission_nav = 'admission';
        include_once(G5_THEME_PATH . '/partials/admission-aside.php');
        ?>

        <div id="sh_content">
            <?php include_once(G5_THEME_PATH . '/partials/admission-content.php'); ?>
        </div>
    </div>
</main>
