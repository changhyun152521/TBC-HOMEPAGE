<?php

$sub_menu = '950200';

require_once './_common.php';



if ($is_admin != 'super') {

    alert('최고관리자만 접근 가능합니다.');

}



auth_check_menu($auth, $sub_menu, 'r');



include_once(G5_THEME_PATH . '/tbc.greeting.lib.php');

tbc_greeting_ensure_table();

tbc_greeting_seed_defaults();



$config = tbc_greeting_get_config();

$field_groups = tbc_greeting_admin_field_groups();

$image_url = tbc_greeting_resolve_image_url($config['gr_image']);

$admin_token = get_admin_token();



$g5['title'] = '더브코 인사말 관리';

$admin_msg = !empty($_GET['msg']) ? strip_tags($_GET['msg']) : '';

include_once('./admin.head.php');

?>



<style>

.tbc-admin-intro { margin-bottom: 20px; }

.tbc-section-title { margin: 28px 0 10px; font-size: 16px; font-weight: 700; color: #1e293b; }

.tbc-section-desc { margin: 0 0 12px; color: #64748b; font-size: 13px; }

.tbc-greeting-preview img {

    display: block;

    max-width: 480px;

    width: 100%;

    height: auto;

    border: 1px solid #e2e8f0;

    border-radius: 8px;

}

</style>



<?php if ($admin_msg) { ?>

<div class="local_desc01 local_desc" style="margin-bottom:15px;">

    <p><?php echo nl2br(htmlspecialchars($admin_msg, ENT_QUOTES, 'UTF-8')); ?></p>

</div>

<?php } ?>



<div class="local_desc01 local_desc tbc-admin-intro">

    <p>메뉴 <strong>더브코 → 인사말</strong> 페이지 내용을 수정합니다.<br>

    <strong>위에서 아래 순서</strong>대로 제목 → 사진 → 본문 → 서명을 편집한 뒤, 맨 아래 <strong>「저장」</strong> 버튼을 눌러주세요.</p>

</div>



<form name="ftbcgreeting" id="ftbcgreeting" action="./tbc_greeting_update.php" method="post" enctype="multipart/form-data">

<input type="hidden" name="token" value="<?php echo $admin_token; ?>">



<?php foreach ($field_groups as $group) { ?>

<div class="tbl_frm01 tbl_wrap" style="margin-top:24px;">

    <h2 class="tbc-section-title"><?php echo htmlspecialchars($group['step'] . ' ' . $group['title'], ENT_QUOTES, 'UTF-8'); ?></h2>

    <p class="tbc-section-desc"><?php echo htmlspecialchars($group['desc'], ENT_QUOTES, 'UTF-8'); ?></p>



    <?php if ($group['step'] === '②') { ?>

    <table>

    <caption>대표 사진</caption>

    <colgroup><col class="grid_4"><col></colgroup>

    <tbody>

    <tr>

        <th scope="row">현재 사진</th>

        <td>

            <div class="tbc-greeting-preview">

                <img src="<?php echo $image_url; ?>" alt="인사말 대표 사진 미리보기">

            </div>

        </td>

    </tr>

    <tr>

        <th scope="row"><label for="greeting_image">사진 교체</label></th>

        <td>

            <input type="file" name="greeting_image" id="greeting_image" accept=".jpg,.jpeg,.png,.webp,.gif">

            <p class="frm_info">jpg, png, webp, gif · 최대 5MB · 가로로 넓은 사진을 권장합니다.</p>

        </td>

    </tr>

    </tbody>

    </table>

    <?php } else { ?>

    <table>

    <caption><?php echo htmlspecialchars($group['title'], ENT_QUOTES, 'UTF-8'); ?></caption>

    <colgroup><col class="grid_4"><col></colgroup>

    <tbody>

    <?php foreach ($group['fields'] as $field) {

        $key = $field['key'];

        $rows = isset($field['rows']) ? (int) $field['rows'] : 3;

        $value = isset($config[$key]) ? $config[$key] : '';

    ?>

    <tr>

        <th scope="row"><label for="<?php echo $key; ?>"><?php echo htmlspecialchars($field['label'], ENT_QUOTES, 'UTF-8'); ?></label></th>

        <td>

            <?php if (!empty($field['single_line'])) { ?>

            <input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo get_text($value); ?>" class="frm_input" style="width:100%;max-width:640px;">

            <?php } else { ?>

            <textarea name="<?php echo $key; ?>" id="<?php echo $key; ?>" rows="<?php echo $rows; ?>" class="frm_input" style="width:100%;max-width:640px;"><?php echo get_text($value); ?></textarea>

            <p class="frm_info">문단 사이는 빈 줄로 구분합니다. 문단 안 줄바꿈은 페이지에서 그대로 표시됩니다.</p>

            <?php } ?>

        </td>

    </tr>

    <?php } ?>

    </tbody>

    </table>

    <?php } ?>

</div>

<?php } ?>



<div class="btn_fixed_top">

    <input type="submit" value="인사말 저장" class="btn_submit btn">

</div>



</form>



<?php

include_once('./admin.tail.php');

