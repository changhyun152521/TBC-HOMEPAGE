<?php
$sub_menu = '950210';
require_once './_common.php';

if ($is_admin != 'super') {
    alert('최고관리자만 접근 가능합니다.');
}

auth_check_menu($auth, $sub_menu, 'w');

include_once(G5_THEME_PATH . '/tbc.history.lib.php');
tbc_history_ensure_tables();

$he_id = isset($_GET['he_id']) ? (int) $_GET['he_id'] : 0;
$entry = $he_id ? tbc_history_get($he_id) : null;

if ($he_id && !$entry) {
    alert('연혁 항목을 찾을 수 없습니다.', './tbc_history.php');
}

$admin_token = get_admin_token();
$is_edit = (bool) $entry;
$g5['title'] = $is_edit ? '연혁 수정' : '연혁 추가';
include_once('./admin.head.php');

$form = $entry ? $entry : array(
    'he_year' => '',
    'he_items' => '',
    'he_use' => 1,
);
?>

<div class="local_desc01 local_desc">
    <p>연도와 연혁 내용을 입력합니다. 내용은 <strong>한 줄에 하나</strong>씩 작성해 주세요.</p>
</div>

<form name="ftbchistoryentry" action="./tbc_history_entry_update.php" method="post">
<input type="hidden" name="token" value="<?php echo $admin_token; ?>">
<input type="hidden" name="he_id" value="<?php echo (int) $he_id; ?>">

<div class="tbl_frm01 tbl_wrap">
    <table>
    <colgroup><col class="grid_4"><col></colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="he_year">연도</label></th>
        <td><input type="text" name="he_year" id="he_year" value="<?php echo get_text($form['he_year']); ?>" class="frm_input" style="width:100%;max-width:160px;" required placeholder="예: 2024"></td>
    </tr>
    <tr>
        <th scope="row"><label for="he_items">연혁 내용</label></th>
        <td>
            <textarea name="he_items" id="he_items" rows="8" class="frm_input" style="width:100%;max-width:640px;" required placeholder="한 줄에 하나씩 입력&#10;예: 세종 아름·새롬 분원 오픈&#10;예: 강사진·교육과정 시스템 고도화"><?php echo get_text($form['he_items']); ?></textarea>
        </td>
    </tr>
    <tr>
        <th scope="row">노출 여부</th>
        <td><label><input type="checkbox" name="he_use" value="1"<?php echo !empty($form['he_use']) ? ' checked' : ''; ?>> 연혁 페이지에 표시</label></td>
    </tr>
    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <a href="./tbc_history.php" class="btn btn_02">목록</a>
    <input type="submit" value="저장" class="btn_submit btn">
</div>
</form>

<?php include_once('./admin.tail.php'); ?>
