<?php
/**
 * 메인 페이지 본문
 * apps/web 에서 자동 생성 — 직접 수정 시 build 스크립트 재실행 시 덮어씌워집니다.
 * 레이아웃·CSS 변경은 apps/web 에서 하고 npm run gnuboard:build 실행하세요.
 */
if (!defined('_GNUBOARD_')) exit;
?>
<main id="sh_container">
        <!-- sh_container_wrapper [s] -->
		<div id="sh_container_wrapper">

            <!-- main_banner [s] -->
            <div id="main_banner">
                <div id="main_banner_wrap" data-aos="fade-up">
                    <div class="cont_box">
                        <div class="left">
                            <div class="m_txt">
                                <h1 class="ko_tit">
                                    대전·세종을 대표하는<br>
                                    교육 브랜드 더브레인코어
                                </h1>
                                교육철학과 전문관 시스템으로 학생의 성장을 함께합니다.
                                <a href="https://www.band.us/band/90118402/post" class="more" target="_blank" rel="noopener noreferrer">BAND 바로가기 <img src="<?php echo G5_THEME_URL; ?>/img/main/main_deco.png" alt="메인데코"></a>
                            </div>
                            <div class="slide_wrap">
                                <div class="swiper main_slide">
                                    <ul class="main_slide_box swiper-wrapper">
                                        <li class="swiper-slide slide1"></li>
                                        <li class="swiper-slide slide2"></li>
                                        <li class="swiper-slide slide3"></li>
                                    </ul> 
                                    <div class="arrow_btn">
                                        <div class="page_num"></div>
                                        <div class="controls">
                                            <div class="btn_pager bnr-prev"><img src="<?php echo G5_THEME_URL; ?>/img/main/arr_left.png" alt="prev"/></div>
                                            <div class="play">
                                                <div class="swiper-pause"><span class="material-symbols-outlined"><img src="<?php echo G5_THEME_URL; ?>/img/main/stop_icon.png" alt="stop"/></span></div>
                                                <div class="swiper-play"><span class="material-symbols-outlined"><img src="<?php echo G5_THEME_URL; ?>/img/main/start_icon.png" alt="start" /></span></div>
                                            </div> 
                                            <div class="btn_pager bnr-next"><img src="<?php echo G5_THEME_URL; ?>/img/main/arr_right.png" alt="next"/></div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="right">
                            <div class="top_box">
                                <div class="top_cont">
                                    <div class="tit">
                                        <img src="<?php echo G5_THEME_URL; ?>/img/main/main_shine.png" alt="메인전구"> 하나의 브랜드, 여러 전문관
                                    </div>
                                    <h2 class="txt">
                                        대전·세종을 대표하는<br>
                                        교육 브랜드 더브레인코어
                                    </h2>
                                </div>
                                <ul>
                                    <li>
                                        <a href="<?php echo tbc_page_url('academies'); ?>">
                                            <div class="icon">
                                                <img src="<?php echo G5_THEME_URL; ?>/img/main/icon01.png" alt="관·분원">
                                                <img src="<?php echo G5_THEME_URL; ?>/img/main/icon01_on.png" class="img_on" alt="관·분원">
                                            </div>
                                            <p>관·분원</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo tbc_page_url('teachers'); ?>">
                                            <div class="icon">
                                                <img src="<?php echo G5_THEME_URL; ?>/img/main/icon02.png" alt="강사진">
                                                <img src="<?php echo G5_THEME_URL; ?>/img/main/icon02_on.png" class="img_on" alt="강사진">
                                            </div>
                                            <p>강사진</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo tbc_page_url('schedule'); ?>">
                                            <div class="icon">
                                                <img src="<?php echo G5_THEME_URL; ?>/img/main/icon03.png" alt="시간표">
                                                <img src="<?php echo G5_THEME_URL; ?>/img/main/icon03_on.png" class="img_on" alt="시간표">
                                            </div>
                                            <p>시간표</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo tbc_board_url('consult'); ?>">
                                            <div class="icon">
                                                <img src="<?php echo G5_THEME_URL; ?>/img/main/icon04.png" alt="입학안내">
                                                <img src="<?php echo G5_THEME_URL; ?>/img/main/icon04_on.png" class="img_on" alt="입학안내">
                                            </div>
                                            <p>입학안내</p>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="bot_box">
                                <div class="contact">
                                    <div class="top">
                                        <div class="s_tit"><div class="phone"><i data-feather="phone"></i></div>상담문의</div>
                                        <div class="tel">
                                            <p>042-000-0000</p>
                                            언제나 친절한 상담을 약속드립니다.
                                        </div>
                                    </div>
                                    <div class="time_box">
                                        평일 14:00~22:00 · 주말 및 공휴일 10:00~22:00
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="notice_box">
                        <div id="sh_index_latest_id" class="sh_index_latest">
                            <p class="tit"><i data-feather="volume-2"></i> 공지사항</p>
                            <div id="index_btm">  
                                <div class="swiper index_btm_slide">
                                    <ul class="swiper-wrapper">
                                        <li class="swiper-slide">
                                            <span class="sh_notice">
                                                <a href="<?php echo tbc_board_url('consult'); ?>">더브레인코어 홈페이지가 새롭게 오픈하였습니다 !</a>
                                                <p class="date">2025.02.25</p>
                                            </span>
                                        </li>
                                        <li class="swiper-slide">
                                            <span class="sh_notice">
                                                <a href="<?php echo tbc_board_url('consult'); ?>">더브레인코어 홈페이지가 새롭게 오픈하였습니다 !</a>
                                                <p class="date">2025.02.25</p>
                                            </span>
                                        </li>
                                        <li class="swiper-slide">
                                            <span class="sh_notice">
                                                <a href="<?php echo tbc_board_url('consult'); ?>">더브레인코어 홈페이지가 새롭게 오픈하였습니다 !</a>
                                                <p class="date">2025.02.21</p>
                                            </span>
                                        </li>
                                    </ul>
                                    <div class="index_btm_pager">
                                        <div class="prev"><i class="fa fa-regular fa-angle-up" aria-hidden="true"></i></div>
                                        <div class="next"><i class="fa fa-regular fa-angle-down" aria-hidden="true"></i></div>
                                    </div>
                                </div>
                            </div>
                            <script>
                                var lateroll = new Swiper("#index_btm .index_btm_slide", {
                                  direction : 'vertical',
                                  loop : true,
                                  speed:800,
                                  autoplay: {
                                    delay: 2500,
                                    disableOnInteraction: false,
                                  },
                                  navigation: {
                                    nextEl: ".index_btm_pager .next",
                                    prevEl: ".index_btm_pager .prev",
                                  },
                                });
                              </script>
                            <a href="<?php echo tbc_board_url('consult'); ?>" class="more">더보기 <i data-feather="plus"></i></a>
                        </div> 
                    </div>    
                </div>
                
                <script>
                var mainSwiper = new Swiper(".main_slide", {
                    effect: "fade",
                    spaceBetween: 0,
                    speed:1000,
                    loop : true,
                    autoplay: {
                    delay: 2500,
                    disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: '.bnr-next',
                        prevEl: '.bnr-prev',
                    },
                    pagination: {
                        el: '.page_num',
                        type: 'fraction',
                    },
                });
                $('.swiper-pause').click(function(){
                    mainSwiper.autoplay.stop();
                });
                $('.swiper-play').click(function(){
                    mainSwiper.autoplay.start();
                });
                $(function() { 
                    $(".play").click(function() {
                        $(this).toggleClass("on");
                    });
                });
                </script>
                
            </div>
            <!-- main_banner [e] -->

            <!-- sh_section [s] -->
            <section id="sh_section">
                <!-- inc01 [s] -->
                <article id="atc01">
                    <div class="inner">
                        <div class="top_box" data-aos="fade-down">
                            <img src="<?php echo G5_THEME_URL; ?>/img/main/inc01/img01.png" alt="메인이미지">
                            <div class="ko_box">
                                <p>하나의 교육 브랜드, 더브레인코어</p>
                                <h2 class="tit">
                                    전문관과 분원이 연결된<br>
                                    대전·세종 대표 교육 브랜드
                                </h2>   
                            </div>
                        </div>
                        <div class="cont_inner">
                            <div class="center_box">
                                <div class="top_cont">
                                    <div class="tit_box">
                                        <h3 class="left" data-aos="fade-right">전문 강사진</h3>
                                        <div class="right" data-aos="fade-left"><a href="<?php echo tbc_page_url('teachers'); ?>">강사진 전체 보기 <em><i data-feather="arrow-up-right"></i></em></a></div>
                                    </div>
                                </div>
                                <div class="gall_box">
                                    <ul>
                                        <li>
                                            <a href="teachers-science.html#teacher-yun">
                                                <div class="img" style="background-image:url(<?php echo G5_THEME_URL; ?>/img/main/inc01/1.png)"></div>
                                                <div class="cont">윤호진<span>과학</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="teachers-math.html#teacher-song">
                                                <div class="img" style="background-image:url(<?php echo G5_THEME_URL; ?>/img/main/inc01/2.png)"></div>
                                                <div class="cont">송형주<span>수학</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="teachers-math.html#teacher-kim">
                                                <div class="img" style="background-image:url(<?php echo G5_THEME_URL; ?>/img/main/inc01/3.png)"></div>
                                                <div class="cont">김나영<span>수학</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="teachers-english.html#teacher-park">
                                                <div class="img" style="background-image:url(<?php echo G5_THEME_URL; ?>/img/main/inc01/4.png)"></div>
                                                <div class="cont">박노준<span>영어</span>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>                    
                                </div>
                            </div>
                            <div class="bot_box">
                                <div class="left" data-aos="fade-right">
                                    <div class="tit">
                                        대전·세종 <span>11개 관·분원</span>이<br>
                                        하나의 브랜드로 연결되어 있습니다
                                    </div>
                                    <div class="review_box">
                                        <ul class=" n_lt">
                                            <li class="first">
                                                <a href="<?php echo tbc_board_url('consult'); ?>">   
                                                    <span class="sound_only">강의후기</span>
                                                </a>
                                                <div class="lt_cont_f">
                                                    <a href="<?php echo tbc_board_url('consult'); ?>">
                                                        <div class="left">
                                                            <div class="icon"><img src="<?php echo G5_THEME_URL; ?>/img/main/inc01/chat.png" alt="아이콘"></div>
                                                            <span class="date">02.21</span>
                                                        </div>
                                                        <div class="right">
                                                            <p class="subj">실력 향상을 위한 완벽한 선택!</p>
                                                            <p class="subt">강사님들은 매우 친절하고, 개념을 쉽게 설명해주셔서 쉽게따라갈 수 있었습니다. 특히 매 수업마다 실생활에.....</p>
                                                        </div>
                                                    </a>
                                                    <a href="<?php echo tbc_board_url('consult'); ?>" class="more">더보기 <i data-feather="plus"></i></a>
                                                </div>
                                            </li>
                                            <li>
                                                <a href="<?php echo tbc_board_url('consult'); ?>">   
                                                    <span class="sound_only">강의후기</span>
                                                    <div class="lt_cont">
                                                        <p class="subj">언제나 친절하고 유익한 강의를 해주셔서 감사합니다 !</p>
                                                        <span class="date">2025.02.21</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo tbc_board_url('consult'); ?>">   
                                                    <span class="sound_only">강의후기</span>
                                                    <div class="lt_cont">
                                                        <p class="subj">언제나 친절하고 유익한 강의를 해주셔서 감사합니다 !</p>
                                                        <span class="date">2025.02.21</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo tbc_board_url('consult'); ?>">   
                                                    <span class="sound_only">강의후기</span>
                                                    <div class="lt_cont">
                                                        <p class="subj">언제나 친절하고 유익한 강의를 해주셔서 감사합니다 !</p>
                                                        <span class="date">2025.02.21</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo tbc_board_url('consult'); ?>">   
                                                    <span class="sound_only">강의후기</span>
                                                    <div class="lt_cont">
                                                        <p class="subj">언제나 친절하고 유익한 강의를 해주셔서 감사합니다 !</p>
                                                        <span class="date">2025.02.21</span>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="right box" data-aos="fade-left">
                                    <div>
                                        <p>본원 6개 전문관</p>
                                        초등관 · 중등관 · 고등관<br>
                                        과학관 · 알파 · 풀스토리
                                    </div>
                                    <div>
                                        <p>대전·세종 5개 분원</p>
                                        노은관 · 관평관 · 관저관<br>
                                        세종아름관 · 세종새롬관
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
                <!-- inc01 [e] -->
                <!-- inc02 [s] -->
                <article id="atc02">
                    <div class="inner">
                        <div class="left" data-aos="fade-right">
                            <img src="<?php echo G5_THEME_URL; ?>/img/main/inc02/img01.png" alt="캐릭터">
                            <h2>체계적인 교육과정과
                                전문관 시스템으로
                                학생의 성장을 함께합니다</h2>
                            <div class="cont_box">
                                <div class="tit_box">
                                    <div class="ko_tit">초등부터 고등까지 !
                                        전문관별 맞춤
                                        교육과정</div>
                                    <a href="<?php echo tbc_board_url('consult'); ?>" class="more"><span class="sound_only">커리큘럼</span><div class="icon"><i data-feather="arrow-up-right"></i></div></a>
                                </div>
                            </div>
                        </div>
                        <div class="right" data-aos="fade-left">
                            <div class="top_box">
                                <div class="tit_box">
                                    <div class="ko_tit">더브레인코어와 함께
                                        성장의 여정을
                                        시작해보세요.</div>
                                    <a href="<?php echo tbc_board_url('consult'); ?>"class="v_more"><img src="<?php echo G5_THEME_URL; ?>/img/main/inc02/touch.png" alt="캐릭터">VIEW</a>
                                </div>
                            </div>
                            <div class="bot_box">
                                <div class="txt_box">
                                    <div class="ko_tit">학생별 맞춤 학습과
                                        체계적인 관리 시스템</div>
                                    <a href="<?php echo tbc_board_url('consult'); ?>" class="more"><span class="sound_only">학습과정</span><div class="icon"><i data-feather="arrow-up-right"></i></div></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
                <!-- inc02 [e] -->
                <!-- inc03 [s] -->
                <article id="atc03">
                    <div class="inner">
                        <div class="tit_box">
                            <h2 data-aos="fade-down">공지사항 및 소식</h2>
                        </div>
                        <div class="cont_box">
                            <div class="left" data-aos="fade-right">
                                <div class="top">
                                    <ul class="late_tabs">
                                        <li class="on" rel="tab1">공지사항</li>
                                        <li rel="tab2">교육정보</li>
                                    </ul>
                                    <a href="<?php echo tbc_board_url('consult'); ?>">더보기 <em><i data-feather="arrow-up-right"></i></em></a>
                                </div>
                                <div class="bot">
                                    <div class="right">
                                        <div id="tabs">
                                            <div class="late_box">
                                                <div id="tab1" class="late_cont">
                                                    <div class="late">
                                                        <ul class=" n_lt">
                                                            <li>
                                                                <a href="<?php echo tbc_board_url('consult'); ?>">   
                                                                    <div class="lt_cont_f">
                                                                        <p class="cate">NOTICE</p>
                                                                        <p class="subj">더브레인코어 홈페이지가 새롭게 오픈...</p>
                                                                        <p class="subt">더브레인코어 홈페이지를 리뉴얼 오픈하였습니다. 많은 관심과 응원 부탁드립니다.</p>
                                                                        <span class="date">2025.02.25</span>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="<?php echo tbc_board_url('consult'); ?>">   
                                                                    <div class="lt_cont_f">
                                                                        <p class="cate">NOTICE</p>
                                                                        <p class="subj">더브레인코어 홈페이지가 새롭게 오픈...</p>
                                                                        <p class="subt">더브레인코어 홈페이지를 리뉴얼 오픈하였습니다. 많은 관심과 응원 부탁드립니다.</p>
                                                                        <span class="date">2025.02.25</span>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="<?php echo tbc_board_url('consult'); ?>">   
                                                                    <div class="lt_cont_f">
                                                                        <p class="cate">NOTICE</p>
                                                                        <p class="subj">더브레인코어 홈페이지가 새롭게 오픈...</p>
                                                                        <p class="subt">더브레인코어 홈페이지를 리뉴얼 오픈하였습니다. 많은 관심과 응원 부탁드립니다.</p>
                                                                        <span class="date">2025.02.25</span>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="<?php echo tbc_board_url('consult'); ?>">   
                                                                    <div class="lt_cont_f">
                                                                        <p class="cate">NOTICE</p>
                                                                        <p class="subj">더브레인코어 홈페이지가 새롭게 오픈...</p>
                                                                        <p class="subt">더브레인코어 홈페이지를 리뉴얼 오픈하였습니다. 많은 관심과 응원 부탁드립니다.</p>
                                                                        <span class="date">2025.02.21</span>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div id="tab2" class="late_cont">
                                                    <div class="late">
                                                        <ul class=" n_lt">
                                                            <li>
                                                                <a href="<?php echo tbc_board_url('consult'); ?>">   
                                                                    <div class="lt_cont_f">
                                                                        <p class="cate">지원전략</p>
                                                                        <p class="subj">모의고사, 이렇게 풀어야 진짜다!</p>
                                                                        <p class="subt">단순한 해설은 NO! 출제 의도부터 고득점 비법까지, 모의고사 완벽 정복 가이드!</p>
                                                                        <span class="date">2024.12.10</span>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="<?php echo tbc_board_url('consult'); ?>">   
                                                                    <div class="lt_cont_f">
                                                                        <p class="cate">입시정보</p>
                                                                        <p class="subj">최신 입시 동향 파헤치기!</p>
                                                                        <p class="subt">매일 바뀌는 입시 정보, 놓치지 않으셨죠? 최신 입시 동향에 유리한 전략을 준비해드려요!</p>
                                                                        <span class="date">2024.12.10</span>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="<?php echo tbc_board_url('consult'); ?>">   
                                                                    <div class="lt_cont_f">
                                                                        <p class="cate">지원전략</p>
                                                                        <p class="subj">2024년도 주요사항 분석_샘플대...</p>
                                                                        <p class="subt">2024년도 주요사항 분석_샘플대학교지원 전략 소개합니다.</p>
                                                                        <span class="date">2023.06.01</span>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="<?php echo tbc_board_url('consult'); ?>">   
                                                                    <div class="lt_cont_f">
                                                                        <p class="cate">입시정보</p>
                                                                        <p class="subj">2014년 8월 10일 기업형 전용...</p>
                                                                        <p class="subt">Make24에서는 트렌드에 맞는 샘플디자인제공을 위해&nbsp;이번 업종별 최적화 기획을 계기로...</p>
                                                                        <span class="date">2018.08.24</span>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                            <div class="right" data-aos="fade-left">
                                <div class="top">
                                    <p>교육 정보 미리보기</p>
                                    <a href="<?php echo tbc_board_url('consult'); ?>">더보기 <em><i data-feather="arrow-up-right"></i></em></a>
                                </div>
                                <div class="bot">
                                    <div class="swiper edu_banner_slide">
                                        <ul class="swiper-wrapper">
                                            <li class="swiper-slide">
                                                <a href="<?php echo tbc_board_url('consult'); ?>"><img src="<?php echo G5_THEME_URL; ?>/img/main/inc03/banner01.png" alt="교육 정보 미리보기"></a>
                                            </li>
                                            <li class="swiper-slide">
                                                <a href="<?php echo tbc_board_url('consult'); ?>"><img src="<?php echo G5_THEME_URL; ?>/img/main/inc03/banner02.png" alt="교육 정보 미리보기"></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="bot_txt">
                                        <div class="left">
                                            <a href="<?php echo tbc_board_url('consult'); ?>">
                                                <p>기초부터 상위권까지, 성적이 오르는 수학 루틴!</p>
                                                <div class="date">더브레인코어 고등관 수학</div>
                                            </a>
                                        </div>
                                        <div class="right">
                                            <div class="video_area">
                                                <ul>
                                                    <li>
                                                        <p class="gall_img_info">
                                                            <span>고등관 수학</span>
                                                        </p>
                                                    </li>
                                                </ul> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
                
                <script>
                $(function() { 
                    $(".late_cont").hide(); $(".late_cont:first").show();
                    $(".late_tabs li").click(function() {$(".late_tabs li").removeClass("on");
                    $(this).addClass("on"); $(".late_cont").hide() 
                    var activeTab = $(this).attr("rel"); $("#"+activeTab).show()
                });                 });   
                $(document).mouseup(function (e){
                    var LayerPopup = $("#video_view .inner");
                    if(LayerPopup.has(e.target).length === 0){
                    $('#video_view').remove();
                    }
                });

                var eduBannerSwiper = new Swiper(".edu_banner_slide", {
                    effect: "fade",
                    loop: true,
                    speed: 800,
                    autoplay: {
                        delay: 3500,
                        disableOnInteraction: false,
                    },
                });
                </script>
                <!-- inc03 [e] -->
            </section> 
            <!-- sh_section [e] -->
        </div>
        <!-- sh_container_wrapper [e] --> 
    </main>