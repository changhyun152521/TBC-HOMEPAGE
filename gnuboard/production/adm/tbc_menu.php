<?php

$sub_menu = '950120';

require_once './_common.php';



auth_check_menu($auth, $sub_menu, 'r');



include_once(G5_THEME_PATH . '/tbc.menu.lib.php');

tbc_menu_ensure_tables();

tbc_menu_seed_defaults();



$rows = tbc_menu_get_rows();

$defs = tbc_menu_definitions();

$admin_token = get_admin_token();



$g5['title'] = '메뉴 이미지 관리';

$admin_msg = !empty($_GET['msg']) ? strip_tags($_GET['msg']) : '';

include_once('./admin.head.php');

?>



<style>

.tbc-menu-intro{margin-bottom:18px}

.tbc-menu-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:16px}

.tbc-menu-card{border:1px solid #e2e8f0;border-radius:12px;background:#fff;padding:16px}

.tbc-menu-card h3{margin:0 0 4px;font-size:16px;color:#0f172a}

.tbc-menu-card p{margin:0 0 14px;font-size:12px;color:#64748b;line-height:1.5}

.tbc-menu-field{margin-bottom:14px}

.tbc-menu-field label{display:block;margin-bottom:6px;font-size:13px;font-weight:600;color:#334155}

.tbc-menu-preview{display:block;width:100%;max-height:140px;object-fit:cover;border:1px solid #e2e8f0;border-radius:8px;background:#f8fafc}

.tbc-menu-preview.is-banner{max-height:100px}

.tbc-menu-field input[type="file"]{width:100%}

.tbc-menu-actions{margin-top:18px}

</style>



<?php if ($admin_msg) { ?>

<div class="local_desc01 local_desc" style="margin-bottom:15px;">

    <p><?php echo nl2br(htmlspecialchars($admin_msg, ENT_QUOTES, 'UTF-8')); ?></p>

</div>

<?php } ?>



<div class="local_desc01 local_desc tbc-menu-intro">

    <p>햄버거 메뉴(전체메뉴)에서 메뉴에 마우스를 올렸을 때 나오는 사진과, 각 메뉴 서브페이지 상단 배너 사진을 관리합니다.</p>

</div>



<form method="post" action="./tbc_menu_update.php" enctype="multipart/form-data">

<input type="hidden" name="token" value="<?php echo $admin_token; ?>">



<div class="tbc-menu-grid">

<?php foreach ($defs as $key => $meta) {

    $row = isset($rows[$key]) ? $rows[$key] : array();

    $panel_url = tbc_menu_panel_url($key);

    $banner_url = tbc_menu_sub_banner_url($key);

    $is_default = $key === 'default';

?>

    <div class="tbc-menu-card">

        <h3><?php echo htmlspecialchars($meta['label'], ENT_QUOTES, 'UTF-8'); ?></h3>

        <p>

            <?php if ($is_default) { ?>

            전체메뉴를 열었을 때 기본으로 보이는 오른쪽 사진입니다.

            <?php } else { ?>

            전체메뉴에서 「<?php echo htmlspecialchars($meta['label'], ENT_QUOTES, 'UTF-8'); ?>」에 마우스를 올렸을 때 보이는 사진과, 해당 메뉴 서브페이지 상단 배너입니다.

            <?php } ?>

        </p>



        <div class="tbc-menu-field">

            <label>전체메뉴 패널 사진</label>

            <img src="<?php echo htmlspecialchars($panel_url, ENT_QUOTES, 'UTF-8'); ?>" alt="" class="tbc-menu-preview">

            <input type="file" name="panel_file[<?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>]" accept=".jpg,.jpeg,.png,.webp,.gif">

        </div>



        <?php if (!$is_default) { ?>

        <div class="tbc-menu-field">

            <label>서브페이지 상단 배너</label>

            <img src="<?php echo htmlspecialchars($banner_url, ENT_QUOTES, 'UTF-8'); ?>" alt="" class="tbc-menu-preview is-banner">

            <input type="file" name="sub_banner_file[<?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>]" accept=".jpg,.jpeg,.png,.webp,.gif">

        </div>

        <?php } ?>

    </div>

<?php } ?>

</div>



<div class="tbc-menu-actions">

    <button type="submit" class="btn btn_03">저장</button>

</div>

</form>



<?php

include_once('./admin.tail.php');


