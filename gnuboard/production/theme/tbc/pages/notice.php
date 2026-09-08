<?php
if (!defined('_GNUBOARD_')) exit;
?>
<main id="sh_container">
    <div id="sh_container_wrapper">
        <div id="sub_main_banner">
            <div id="shSubBnr">
                <div class="sub_nav">
                    <p class="crumb" data-aos="fade-up" data-aos-delay="300">
                        <a href="<?php echo G5_URL; ?>"><i data-feather="home"></i></a>더브코 소식 <i data-feather="chevron-right"></i>공지사항
                    </p>
                    <p class="tit" data-aos="fade-up" data-aos-delay="600">공지사항</p>
                </div>
                <div id="mainImg" data-aos="fade-in" style="background-image:url(<?php echo G5_THEME_URL; ?>/img/sub/bg_10.jpg);"></div>
            </div>
        </div>

        <?php
        $tbc_news_nav = 'notice';
        include_once(G5_THEME_PATH . '/partials/news-aside.php');
        ?>

        <div id="sh_content">
            <?php include_once(G5_THEME_PATH . '/partials/notice-list.php'); ?>
        </div>
    </div>
</main>
