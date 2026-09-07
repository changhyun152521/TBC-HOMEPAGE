<?php
if (!defined('_GNUBOARD_')) exit;
?>
    <div id="sh_ft_btns">
        <div class="btns">
            <a class="band" href="<?php echo TBC_BAND_URL; ?>" target="_blank" rel="noopener noreferrer">BAND 바로가기</a>
        </div>
    </div>
    <footer id="sh_ft">
        <div class="inner">
            <div class="top">
                <div class="link">
                    <a href="<?php echo get_pretty_url('content', 'provision'); ?>">이용약관</a>
                    <a class="infor" href="<?php echo get_pretty_url('content', 'privacy'); ?>">개인정보취급방침</a>
                </div>
                <dl>
                    <dt>대표전화</dt>
                    <dd><?php echo TBC_TEL; ?></dd>
                </dl>
            </div>
            <div class="ft_cen">
                <div class="left">
                    <img class="ft_logo" src="<?php echo G5_THEME_URL; ?>/img/common/logo_w.png" alt="더브레인코어">
                    <div class="ft_sns">
                        <a href="<?php echo TBC_BAND_URL; ?>" class="ft_band" target="_blank" rel="noopener noreferrer" aria-label="더브레인코어 BAND"><img src="<?php echo G5_THEME_URL; ?>/img/common/band_icon_w.svg" alt="BAND"></a>
                        <a href="<?php echo TBC_INSTA_URL; ?>" class="ft_insta" target="_blank" rel="noopener noreferrer" aria-label="더브레인코어 인스타그램"><i class="fa fa-instagram"></i></a>
                    </div>
                </div>
                <div class="right">
                    <p>COMPANY INFO</p>
                    <div class="adr">대전광역시 서구 둔산동,<br>
                        사업자번호,<br>
                        대표전화 <?php echo TBC_TEL; ?></div>
                </div>
            </div>
            <div class="ft_btm">
                <div class="copy">ⓒ 더브레인코어</div>
            </div>
        </div>
    </footer>

</div>
<!-- sh_wrapper [e] -->

<script>
    if (typeof feather !== 'undefined') feather.replace();
    if (typeof AOS !== 'undefined') AOS.init();
</script>

<?php
include_once(G5_THEME_PATH . '/tail.sub.php');
