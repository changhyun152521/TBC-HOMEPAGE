<?php
$sub_menu = '950390';
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'w');

include_once(G5_THEME_PATH . '/tbc.consult.lib.php');
include_once(G5_THEME_PATH . '/tbc.academy.lib.php');
tbc_consult_ensure_tables();
tbc_academy_ensure_tables();

$cf_id = isset($_GET['cf_id']) ? (int) $_GET['cf_id'] : 0;
$form = $cf_id ? tbc_consult_get($cf_id) : null;

if ($cf_id && !$form) {
    alert('구글폼 링크를 찾을 수 없습니다.', './tbc_consults.php');
}

$academies = tbc_academy_get_list('', '', false);
$default_ac_id = isset($_GET['ac_id']) ? (int) $_GET['ac_id'] : 0;
$admin_token = get_admin_token();
$is_edit = (bool) $form;
$g5['title'] = $is_edit ? '구글폼 링크 수정' : '구글폼 링크 추가';
include_once('./admin.head.php');

$row = $form ? $form : array(
    'ac_id' => $default_ac_id,
    'cf_title' => '',
    'cf_url' => '',
    'cf_use' => 1,
);
?>

<form name="ftbcconsult" action="./tbc_consult_update.php" method="post">
<input type="hidden" name="token" value="<?php echo $admin_token; ?>">
<input type="hidden" name="cf_id" value="<?php echo (int) $cf_id; ?>">

<div class="tbl_frm01 tbl_wrap">
    <table>
    <colgroup><col class="grid_4"><col></colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="ac_id">관(분원)</label></th>
        <td>
            <select name="ac_id" id="ac_id" required>
                <option value="">선택</option>
                <?php foreach ($academies as $academy) { ?>
                <option value="<?php echo (int) $academy['ac_id']; ?>"<?php echo (int) $row['ac_id'] === (int) $academy['ac_id'] ? ' selected' : ''; ?>>
                    <?php echo htmlspecialchars($academy['type_name'] . ' - ' . $academy['ac_name'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
                <?php } ?>
            </select>
            <p class="frm_info">이 구글폼이 표시될 관을 선택합니다.</p>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="cf_title">폼 제목</label></th>
        <td><input type="text" name="cf_title" id="cf_title" value="<?php echo get_text($row['cf_title']); ?>" class="frm_input" style="width:100%;max-width:520px;" required placeholder="예: 고등관 수학 상담 신청"></td>
    </tr>
    <tr>
        <th scope="row"><label for="cf_url">구글폼 URL</label></th>
        <td>
            <input type="url" name="cf_url" id="cf_url" value="<?php echo get_text($row['cf_url']); ?>" class="frm_input" style="width:100%;max-width:640px;" required placeholder="https://forms.gle/... 또는 https://docs.google.com/forms/...">
            <p class="frm_info">구글폼 공유 링크를 입력합니다.</p>
        </td>
    </tr>
    <tr>
        <th scope="row">노출 여부</th>
        <td><label><input type="checkbox" name="cf_use" value="1"<?php echo !empty($row['cf_use']) ? ' checked' : ''; ?>> 상담신청 페이지에 표시</label></td>
    </tr>
    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <a href="./tbc_consults.php" class="btn btn_02">목록</a>
    <input type="submit" value="저장" class="btn_submit btn">
</div>
</form>

<?php include_once('./admin.tail.php'); ?>
