<?php
if (!defined('_GNUBOARD_')) exit;

$main_config = tbc_main_get_config();
?>
                            <div class="top_box">
                                <div class="tit_box">
                                    <div class="ko_tit"><?php echo tbc_main_format_preline_text($main_config['section02_card2']); ?></div>
                                    <a href="<?php echo tbc_board_url('consult'); ?>" class="v_more"><img src="<?php echo G5_THEME_URL; ?>/img/main/inc02/touch.png" alt="캐릭터">VIEW</a>
                                </div>
                            </div>
                            <div class="bot_box">
                                <div class="txt_box">
                                    <div class="ko_tit"><?php echo tbc_main_format_preline_text($main_config['section02_card3']); ?></div>
                                    <a href="<?php echo tbc_board_url('consult'); ?>" class="more"><span class="sound_only">학습과정</span><div class="icon"><i data-feather="arrow-up-right"></i></div></a>
                                </div>
                            </div>
