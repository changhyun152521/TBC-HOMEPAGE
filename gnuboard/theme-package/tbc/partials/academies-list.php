<?php
if (!defined('_GNUBOARD_')) exit;

if (!isset($tbc_academy_type)) {
    $tbc_academy_type = '';
}

$academies = tbc_academy_get_list($tbc_academy_type, '', true);
?>
<ul class="gall_row">
<?php if (!$academies) { ?>
    <li class="gall_li">
        <div class="gall_con" style="padding:60px 20px;text-align:center;color:#64748b;">
            등록된 분원 정보가 없습니다.
        </div>
    </li>
<?php } else {
    foreach ($academies as $academy) {
        $map_url = trim($academy['ac_map_url']);
?>
    <li class="gall_li">
        <div class="gall_con">
            <div class="gall_img" style="background-image:url(<?php echo $academy['image_url']; ?>)"></div>
            <div class="gall_txt">
                <div class="subject"><?php echo htmlspecialchars($academy['ac_name'], ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="desc">
                    <dl>
                        <dt>지역</dt>
                        <dd><?php echo htmlspecialchars($academy['ac_region'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    </dl>
                    <dl>
                        <dt>주소</dt>
                        <dd><?php echo htmlspecialchars($academy['ac_address'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    </dl>
                    <dl>
                        <dt>전화번호</dt>
                        <dd><?php echo htmlspecialchars($academy['ac_phone'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    </dl>
                    <dl>
                        <dt>컨셉</dt>
                        <dd><?php echo htmlspecialchars($academy['ac_concept'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    </dl>
                    <?php if ($map_url) { ?>
                    <dl>
                        <dt>위치</dt>
                        <dd>
                            <a href="<?php echo htmlspecialchars($map_url, ENT_QUOTES, 'UTF-8'); ?>" class="map_btn" target="_blank" rel="noopener noreferrer">지도보기</a>
                        </dd>
                    </dl>
                    <?php } ?>
                </div>
            </div>
        </div>
    </li>
<?php }
} ?>
</ul>
