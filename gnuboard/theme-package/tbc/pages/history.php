<?php
/**
 * about-history.html → history
 * apps/web 에서 자동 생성 — 직접 수정 시 build 스크립트 재실행 시 덮어씌워집니다.
 * 레이아웃·CSS 변경은 apps/web 에서 하고 npm run gnuboard:build 실행하세요.
 */
if (!defined('_GNUBOARD_')) exit;
?>
<main id="sh_container">
            
        <!-- sh_container_wrapper [s] -->
        <div id="sh_container_wrapper">

            <!-- shSubBnr [s] -->
            <div id="sub_main_banner">
                <div id="shSubBnr">
                    <div class="sub_nav">
                        <p class="crumb" data-aos="fade-up" data-aos-delay="300">
                            <a href="<?php echo G5_URL; ?>"><i data-feather="home"></i></a>더브코 <i data-feather="chevron-right"></i>연혁</p>
                        <p class="tit" data-aos="fade-up" data-aos-delay="600">연혁</p>
                    </div>
                    <div id="mainImg" data-aos="fade-in" style="background-image:url(<?php echo G5_THEME_URL; ?>/img/sub/bg_10.jpg);"></div>
                </div>
            </div>
            <!-- shSubBnr [e] -->

            <!-- sh_aside [s] -->
            <div id="sh_aside">
				<div id="sh_aside_wrapper">
                    <ul id="shSnb">
                        <li>
                            <a href="<?php echo tbc_page_url('greeting'); ?>">인사말</a>
                        </li>
                        <li>
                            <a href="<?php echo tbc_page_url('philosophy'); ?>">교육철학</a>
                        </li>
                        <li class="on">
                            <a href="<?php echo tbc_page_url('history'); ?>">연혁</a>
                        </li>
                    </ul>
                </div>
			</div>
            <!-- sh_aside [e] -->
            
            <!-- sh_content [s] -->
            <div id="sh_content">
                <!-- 서브페이지 [s] -->
                <div id="greeting" class="pagecommon">
                    <div class="tit_area">
                        더브레인코어 연혁<br />
                        <p>함께 걸어온 길을 소개합니다.</p>
                    </div>
                    <div class="img"></div>
                    <div class="cont">
                        <p class="st">연혁 페이지는 준비 중입니다.</p>
                        <div class="pl">대전·세종을 기반으로 본원 6개 전문관과 5개 분원을 운영하며
                        하나의 교육 브랜드로 성장해 온 더브레인코어의 발자취를 곧 업데이트하겠습니다.</div>
                        <p class="sign">더브레인코어 <span>홍민호 대표</span></p>
                    </div>
                </div>
                <!-- 서브페이지 [e] -->
                
            </div>
            <!-- sh_content [e] -->            
            
        </div>
        <!-- sh_container_wrapper [e] -->
    </main>