<?php
if (!defined('_GNUBOARD_')) exit;
?>
<main id="sh_container">
    <div id="sh_container_wrapper">
        <div id="sub_main_banner">
            <div id="shSubBnr">
                <div class="sub_nav">
                    <p class="crumb" data-aos="fade-up" data-aos-delay="300">
                        <a href="<?php echo G5_URL; ?>"><i data-feather="home"></i></a>입학안내 <i data-feather="chevron-right"></i>FAQ</p>
                    <p class="tit" data-aos="fade-up" data-aos-delay="600">FAQ</p>
                </div>
                <div id="mainImg" data-aos="fade-in" style="background-image:url(<?php echo G5_THEME_URL; ?>/img/sub/bg_10.jpg);"></div>
            </div>
        </div>

        <?php
        $tbc_admission_nav = 'faq';
        include_once(G5_THEME_PATH . '/partials/admission-aside.php');
        ?>

        <div id="sh_content">
            <div class="pagecommon">
                <div class="tit_area">
                    <p>자주 묻는 질문</p>
                </div>
                <div class="cont">
                    <p>FAQ 게시판을 만들어 연동하거나, 관리자 &gt; 내용관리에서 이 페이지 내용을 수정할 수 있습니다.</p>
                </div>
            </div>
        </div>
    </div>
</main>
