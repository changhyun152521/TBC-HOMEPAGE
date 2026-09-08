<?php
/**
 * schedule.html → schedule
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
                            <a href="<?php echo G5_URL; ?>"><i data-feather="home"></i></a>시간표 <i data-feather="chevron-right"></i>전체</p>
                        <p class="tit" data-aos="fade-up" data-aos-delay="600">전체 시간표</p>
                    </div>
                    <div id="mainImg" data-aos="fade-in" style="background-image:url(<?php echo G5_THEME_URL; ?>/img/sub/bg_10.jpg);"></div>
                </div>
            </div>
            <!-- shSubBnr [e] -->

            <!-- sh_aside [s] -->
            <div id="sh_aside">
				<div id="sh_aside_wrapper">
                    <ul id="shSnb">
                        <li class="on">
                            <a href="<?php echo tbc_page_url('schedule'); ?>">전체</a>
                        </li>
                        <li>
                            <a href="<?php echo tbc_page_url('schedule_main'); ?>">본원</a>
                        </li>
                        <li>
                            <a href="<?php echo tbc_page_url('schedule_branch'); ?>">분원</a>
                        </li>
                    </ul>
                </div>
			</div>
            <!-- sh_aside [e] -->
            
            <!-- sh_content [s] -->
            <div id="sh_content">
                <!-- 서브페이지 [s] -->
                <?php
                $tbc_schedule_title = '전체 시간표';
                $tbc_schedule_desc = '전체 강좌 목록을 확인하고, 관·학년·과목 필터로 원하는 강좌를 찾을 수 있습니다.';
                include_once(G5_THEME_PATH . '/partials/schedule-content.php');
                ?>
                <!-- 서브페이지 [e] -->
                
            </div>
            <!-- sh_content [e] -->            
            
        </div>
        <!-- sh_container_wrapper [e] -->
    </main>