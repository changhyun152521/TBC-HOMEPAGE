<?php
$sub_menu = '950360';
require_once './_common.php';

if ($is_admin != 'super') {
    alert('최고관리자만 접근 가능합니다.');
}

auth_check_menu($auth, $sub_menu, 'w');

include_once(G5_THEME_PATH . '/tbc.notice.lib.php');
tbc_notice_ensure_tables();

$nt_id = isset($_GET['nt_id']) ? (int) $_GET['nt_id'] : 0;
$notice = $nt_id ? tbc_notice_get($nt_id) : null;

if ($nt_id && !$notice) {
    alert('공지사항을 찾을 수 없습니다.', './tbc_notices.php');
}

$admin_token = get_admin_token();
$is_edit = (bool) $notice;
$g5['title'] = $is_edit ? '공지사항 수정' : '공지사항 작성';
include_once('./admin.head.php');

$form = $notice ? $notice : array(
    'nt_subject' => '',
    'nt_content' => '',
    'nt_author' => '관리자',
    'nt_is_notice' => 0,
    'nt_use' => 1,
    'nt_file' => '',
    'nt_file_source' => '',
);

$file_url = '';
if (!empty($form['nt_file'])) {
    $file_url = tbc_notice_file_url($form['nt_file']);
}
?>

<div class="local_desc01 local_desc">
    <p>공지사항 제목·내용·첨부파일을 입력합니다. 상단 고정을 선택하면 목록 최상단에 강조 표시됩니다.</p>
</div>

<form name="ftbcnotice" action="./tbc_notice_update.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="token" value="<?php echo $admin_token; ?>">
<input type="hidden" name="nt_id" value="<?php echo (int) $nt_id; ?>">

<div class="tbl_frm01 tbl_wrap">
    <table>
    <colgroup><col class="grid_4"><col></colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="nt_subject">제목</label></th>
        <td><input type="text" name="nt_subject" id="nt_subject" value="<?php echo get_text($form['nt_subject']); ?>" class="frm_input" style="width:100%;max-width:720px;" required></td>
    </tr>
    <tr>
        <th scope="row"><label for="nt_author">작성자</label></th>
        <td><input type="text" name="nt_author" id="nt_author" value="<?php echo get_text($form['nt_author']); ?>" class="frm_input" style="width:100%;max-width:240px;"></td>
    </tr>
    <tr>
        <th scope="row"><label for="nt_content">내용</label></th>
        <td><textarea name="nt_content" id="nt_content" rows="14" class="frm_input" style="width:100%;max-width:900px;" required><?php echo get_text($form['nt_content']); ?></textarea></td>
    </tr>
    <tr>
        <th scope="row"><label for="nt_file">첨부파일</label></th>
        <td>
            <?php if ($file_url) { ?>
            <p style="margin:0 0 8px;"><a href="<?php echo htmlspecialchars($file_url, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars($form['nt_file_source'] ?: $form['nt_file'], ENT_QUOTES, 'UTF-8'); ?></a></p>
            <?php } ?>
            <input type="file" name="nt_file" id="nt_file" accept=".jpg,.jpeg,.png,.webp,.gif,.pdf,.hwp,.doc,.docx,.xls,.xlsx,.zip">
        </td>
    </tr>
    <tr>
        <th scope="row">옵션</th>
        <td>
            <label style="margin-right:18px;"><input type="checkbox" name="nt_is_notice" value="1"<?php echo !empty($form['nt_is_notice']) ? ' checked' : ''; ?>> 상단 고정 공지</label>
            <label><input type="checkbox" name="nt_use" value="1"<?php echo !empty($form['nt_use']) ? ' checked' : ''; ?>> 홈페이지 노출</label>
        </td>
    </tr>
    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <a href="./tbc_notices.php" class="btn btn_02">목록</a>
    <input type="submit" value="저장" class="btn_submit btn">
</div>
</form>

<?php include_once('./admin.tail.php'); ?>
