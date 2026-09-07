<?php
if (!defined('_GNUBOARD_')) exit;

$main_config = tbc_main_get_config();
?>
                            <h2><?php echo tbc_main_format_preline_text($main_config['section02_heading']); ?></h2>
                            <div class="cont_box">
                                <div class="tit_box">
                                    <div class="ko_tit"><?php echo tbc_main_format_preline_text($main_config['section02_card1']); ?></div>
                                    <a href="<?php echo tbc_board_url('consult'); ?>" class="more"><span class="sound_only">커리큘럼</span><div class="icon"><i data-feather="arrow-up-right"></i></div></a>
                                </div>
                            </div>
