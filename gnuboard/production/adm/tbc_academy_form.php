<?php
$sub_menu = '950250';
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'w');

include_once(G5_THEME_PATH . '/tbc.academy.lib.php');
tbc_academy_ensure_tables();

$ac_id = isset($_GET['ac_id']) ? (int) $_GET['ac_id'] : 0;
$default_type = isset($_GET['type']) ? preg_replace('/[^a-z]/', '', $_GET['type']) : '';
$academy = $ac_id ? tbc_academy_get($ac_id) : null;

if ($ac_id && !$academy) {
    alert('분원 정보를 찾을 수 없습니다.', './tbc_academies.php');
}

$types = tbc_academy_types();
$admin_token = get_admin_token();
$is_edit = (bool) $academy;

$g5['title'] = $is_edit ? '분원 수정' : '분원 추가';
$admin_msg = !empty($_GET['msg']) ? strip_tags($_GET['msg']) : '';
include_once('./admin.head.php');

$form = $academy ? $academy : array(
    'ac_type' => ($default_type === 'main' || $default_type === 'branch') ? $default_type : 'main',
    'ac_name' => '',
    'ac_region' => '',
    'ac_address' => '',
    'ac_phone' => '',
    'ac_consult_phone' => '',
    'ac_consult_hours' => '평일 14:00~22:00 · 주말 및 공휴일 10:00~22:00',
    'ac_concept' => '',
    'ac_map_url' => '',
    'ac_use' => 1,
    'ac_image' => '',
);

$image_preview_url = '';
if (!empty($form['ac_image'])) {
    $image_preview_url = tbc_media_resolve_url('academy', $form['ac_image'], '');
}
?>

<style>
.tbc-form-intro{margin-bottom:18px}
.tbc-form-section{margin-top:24px}
.tbc-form-title{margin:0 0 8px;font-size:16px;font-weight:700;color:#1e293b}
.tbc-form-desc{margin:0 0 12px;color:#64748b;font-size:13px}
.tbc-form-preview img{display:block;max-width:220px;border-radius:10px;border:1px solid #e2e8f0}
.tbc-form-preview-empty{padding:24px;text-align:center;color:#64748b;border:1px dashed #cbd5e1;border-radius:10px;background:#f8fafc;max-width:220px}
</style>

<?php if ($admin_msg) { ?>
<div class="local_desc01 local_desc" style="margin-bottom:15px;">
    <p><?php echo nl2br(htmlspecialchars($admin_msg, ENT_QUOTES, 'UTF-8')); ?></p>
</div>
<?php } ?>

<div class="local_desc01 local_desc tbc-form-intro">
    <p>본원·분원 카드에 표시되는 정보를 입력하고 맨 아래 <strong>「저장」</strong> 버튼을 눌러주세요.</p>
</div>

<form name="ftbcacademy" id="ftbcacademy" action="./tbc_academy_update.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="token" value="<?php echo $admin_token; ?>">
<input type="hidden" name="ac_id" value="<?php echo (int) $ac_id; ?>">

<div class="tbl_frm01 tbl_wrap tbc-form-section">
    <h2 class="tbc-form-title">① 기본 정보</h2>
    <table>
    <colgroup><col class="grid_4"><col></colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="ac_type">구분</label></th>
        <td>
            <select name="ac_type" id="ac_type" class="frm_input" required>
                <?php foreach ($types as $code => $label) { ?>
                <option value="<?php echo $code; ?>"<?php echo $form['ac_type'] === $code ? ' selected' : ''; ?>><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="ac_name">관 이름</label></th>
        <td><input type="text" name="ac_name" id="ac_name" value="<?php echo get_text($form['ac_name']); ?>" class="frm_input" style="width:100%;max-width:420px;" required placeholder="예: 더브코 초등관"></td>
    </tr>
    <tr>
        <th scope="row"><label for="ac_region">지역</label></th>
        <td><input type="text" name="ac_region" id="ac_region" value="<?php echo get_text($form['ac_region']); ?>" class="frm_input" style="width:100%;max-width:320px;" placeholder="예: 대전 둔산동"></td>
    </tr>
    <tr>
        <th scope="row"><label for="ac_address">주소</label></th>
        <td><input type="text" name="ac_address" id="ac_address" value="<?php echo get_text($form['ac_address']); ?>" class="frm_input" style="width:100%;max-width:520px;" placeholder="예: 대전광역시 서구 둔산동"></td>
    </tr>
    <tr>
        <th scope="row"><label for="ac_phone">전화번호</label></th>
        <td><input type="text" name="ac_phone" id="ac_phone" value="<?php echo get_text($form['ac_phone']); ?>" class="frm_input" style="width:100%;max-width:240px;" placeholder="예: 042-000-0000"></td>
    </tr>
    <tr>
        <th scope="row"><label for="ac_consult_phone">상담 전화번호</label></th>
        <td>
            <input type="text" name="ac_consult_phone" id="ac_consult_phone" value="<?php echo get_text($form['ac_consult_phone']); ?>" class="frm_input" style="width:100%;max-width:240px;" placeholder="예: 042-000-0000">
            <p class="frm_info">메인 상담문의 슬라이드에 표시됩니다. 비우면 위 전화번호를 사용합니다.</p>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="ac_consult_hours">상담 시간</label></th>
        <td>
            <input type="text" name="ac_consult_hours" id="ac_consult_hours" value="<?php echo get_text($form['ac_consult_hours']); ?>" class="frm_input" style="width:100%;max-width:520px;" placeholder="예: 평일 14:00~22:00 · 주말 및 공휴일 10:00~22:00">
            <p class="frm_info">메인 상담문의 슬라이드 하단에 표시됩니다.</p>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="ac_concept">컨셉</label></th>
        <td><textarea name="ac_concept" id="ac_concept" rows="3" class="frm_input" style="width:100%;max-width:640px;" placeholder="예: 본원 초등부 수업을 담당합니다."><?php echo get_text($form['ac_concept']); ?></textarea></td>
    </tr>
    <tr>
        <th scope="row"><label for="ac_map_url">지도 링크</label></th>
        <td>
            <input type="url" name="ac_map_url" id="ac_map_url" value="<?php echo get_text($form['ac_map_url']); ?>" class="frm_input" style="width:100%;max-width:640px;" placeholder="https://map.naver.com/...">
            <p class="frm_info">네이버 지도 등 외부 지도 URL을 입력하세요.</p>
        </td>
    </tr>
    <tr>
        <th scope="row">노출 여부</th>
        <td><label><input type="checkbox" name="ac_use" value="1"<?php echo !empty($form['ac_use']) ? ' checked' : ''; ?>> 분원 페이지에 표시</label></td>
    </tr>
    </tbody>
    </table>
</div>

<div class="tbl_frm01 tbl_wrap tbc-form-section">
    <h2 class="tbc-form-title">② 대표 사진</h2>
    <table>
    <colgroup><col class="grid_4"><col></colgroup>
    <tbody>
    <tr>
        <th scope="row">현재 사진</th>
        <td>
            <div class="tbc-form-preview">
                <?php if ($image_preview_url) { ?>
                <img src="<?php echo $image_preview_url; ?>" alt="분원 사진 미리보기">
                <?php } else { ?>
                <div class="tbc-form-preview-empty">등록된 사진이 없습니다.</div>
                <?php } ?>
            </div>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="academy_image">사진 업로드</label></th>
        <td>
            <input type="file" name="academy_image" id="academy_image" accept=".jpg,.jpeg,.png,.webp,.gif">
            <p class="frm_info">jpg, png, webp, gif · 최대 5MB · 미등록 시 기본 이미지가 표시됩니다.</p>
        </td>
    </tr>
    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <a href="./tbc_academies.php" class="btn btn_02">목록</a>
    <input type="submit" value="분원 저장" class="btn_submit btn">
</div>
</form>

<?php include_once('./admin.tail.php'); ?>
