<?php
$sub_menu = '950225';
require_once './_common.php';

if ($is_admin != 'super') {
    alert('최고관리자만 접근 가능합니다.');
}

auth_check_menu($auth, $sub_menu, 'w');

include_once(G5_THEME_PATH . '/tbc.band.lib.php');
tbc_band_ensure_tables();

$bd_id = isset($_GET['bd_id']) ? (int) $_GET['bd_id'] : 0;
$band = $bd_id ? tbc_band_get($bd_id) : null;

if ($bd_id && !$band) {
    alert('BAND 링크를 찾을 수 없습니다.', './tbc_bands.php');
}

$admin_token = get_admin_token();
$is_edit = (bool) $band;
$g5['title'] = $is_edit ? 'BAND 링크 수정' : 'BAND 링크 추가';
include_once('./admin.head.php');

$form = $band ? $band : array(
    'bd_title' => '',
    'bd_url' => '',
    'bd_use' => 1,
);
?>

<form name="ftbcband" action="./tbc_band_update.php" method="post">
<input type="hidden" name="token" value="<?php echo $admin_token; ?>">
<input type="hidden" name="bd_id" value="<?php echo (int) $bd_id; ?>">

<div class="tbl_frm01 tbl_wrap">
    <table>
    <colgroup><col class="grid_4"><col></colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="bd_title">표시 제목</label></th>
        <td><input type="text" name="bd_title" id="bd_title" value="<?php echo get_text($form['bd_title']); ?>" class="frm_input" style="width:100%;max-width:420px;" required placeholder="예: 더브코 초등관 BAND"></td>
    </tr>
    <tr>
        <th scope="row"><label for="bd_url">BAND URL</label></th>
        <td><input type="url" name="bd_url" id="bd_url" value="<?php echo get_text($form['bd_url']); ?>" class="frm_input" style="width:100%;max-width:640px;" required placeholder="https://www.band.us/..."></td>
    </tr>
    <tr>
        <th scope="row">노출 여부</th>
        <td><label><input type="checkbox" name="bd_use" value="1"<?php echo !empty($form['bd_use']) ? ' checked' : ''; ?>> 선택 목록에 표시</label></td>
    </tr>
    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <a href="./tbc_bands.php" class="btn btn_02">목록</a>
    <input type="submit" value="저장" class="btn_submit btn">
</div>
</form>

<?php include_once('./admin.tail.php'); ?>
