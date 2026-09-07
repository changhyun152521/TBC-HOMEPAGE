<?php
/**
 * about-philosophy.html → philosophy
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
                            <a href="<?php echo G5_URL; ?>"><i data-feather="home"></i></a>더브코 <i data-feather="chevron-right"></i>교육철학</p>
                        <p class="tit" data-aos="fade-up" data-aos-delay="600">교육철학</p>
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
                        <li class="on">
                            <a href="<?php echo tbc_page_url('philosophy'); ?>">교육철학</a>
                        </li>
                        <li>
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
                        더브레인코어의 교육철학<br />
                        <p><b>성장</b>과 <b>신뢰</b>를 중심으로 한 교육</p>
                    </div>
                    <div class="img"></div>
                    <div class="cont">
                        <p class="st">교육철학 페이지는 준비 중입니다.</p>
                        <div class="pl">더브레인코어는 단기 성적보다 학습 습관과 전인적 성장을 중시하며,
                        본원 전문관과 지역 분원이 하나의 브랜드로 연결되어 일관된 교육을 제공합니다.</div>
                        <p class="sign">더브레인코어 <span>홍민호 대표</span></p>
                    </div>
                </div>
                <!-- 서브페이지 [e] -->
                
            </div>
            <!-- sh_content [e] -->            
            
        </div>
        <!-- sh_container_wrapper [e] -->
    </main>