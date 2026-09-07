<?php
$sub_menu = '950370';
require_once './_common.php';

if ($is_admin != 'super') {
    alert('최고관리자만 접근 가능합니다.');
}

auth_check_menu($auth, $sub_menu, 'w');

include_once(G5_THEME_PATH . '/tbc.edu.lib.php');
tbc_edu_ensure_tables();

$ed_id = isset($_GET['ed_id']) ? (int) $_GET['ed_id'] : 0;
$edu = $ed_id ? tbc_edu_get($ed_id) : null;

if ($ed_id && !$edu) {
    alert('교육정보를 찾을 수 없습니다.', './tbc_edu.php');
}

$admin_token = get_admin_token();
$is_edit = (bool) $edu;
$g5['title'] = $is_edit ? '교육정보 수정' : '교육정보 작성';
include_once('./admin.head.php');

$form = $edu ? $edu : array(
    'ed_subject' => '',
    'ed_content' => '',
    'ed_author' => '더브레인코어',
    'ed_thumb' => '',
    'ed_use' => 1,
);

$thumb_url = '';
if (!empty($form['ed_thumb'])) {
    $thumb_url = tbc_edu_file_url($form['ed_thumb']);
}
?>

<style>
.tbc-edu-editor-wrap{border:1px solid #d1d5db;border-radius:10px;background:#fff;overflow:hidden}
.tbc-edu-editor-toolbar{display:flex;flex-wrap:wrap;gap:8px;padding:10px 12px;border-bottom:1px solid #e5e7eb;background:#f8fafc}
.tbc-edu-editor-toolbar button{height:34px;padding:0 12px;border:1px solid #cbd5e1;border-radius:6px;background:#fff;cursor:pointer;font-size:13px}
.tbc-edu-editor-toolbar button:hover{background:#eff6ff;border-color:#93c5fd}
.tbc-edu-editor-body{min-height:360px;padding:20px;font-size:15px;line-height:1.8;color:#333}
.tbc-edu-editor-body:focus{outline:none}
.tbc-edu-editor-body img{max-width:100%;height:auto;margin:12px 0;border-radius:8px}
.tbc-edu-editor-body p{margin:0 0 14px}
.tbc-edu-thumb-preview{width:160px;height:120px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;background:#f8fafc}
.tbc-edu-help{font-size:12px;color:#64748b;margin-top:6px;line-height:1.6}
</style>

<div class="local_desc01 local_desc">
    <p>네이버 밴드처럼 글 중간에 사진을 넣을 수 있습니다. <strong>사진 삽입</strong> 버튼으로 이미지를 업로드하고, 목록용 썸네일·요약 정보를 함께 입력해 주세요.</p>
</div>

<form name="ftbcedu" id="ftbcedu" action="./tbc_edu_update.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="token" value="<?php echo $admin_token; ?>">
<input type="hidden" name="ed_id" value="<?php echo (int) $ed_id; ?>">
<textarea name="ed_content" id="ed_content" style="display:none;"><?php echo htmlspecialchars($form['ed_content'], ENT_NOQUOTES, 'UTF-8'); ?></textarea>

<div class="tbl_frm01 tbl_wrap">
    <table>
    <colgroup><col class="grid_4"><col></colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="ed_subject">제목</label></th>
        <td><input type="text" name="ed_subject" id="ed_subject" value="<?php echo get_text($form['ed_subject']); ?>" class="frm_input" style="width:100%;max-width:720px;" required></td>
    </tr>
    <tr>
        <th scope="row"><label for="ed_author">작성자</label></th>
        <td><input type="text" name="ed_author" id="ed_author" value="<?php echo get_text($form['ed_author']); ?>" class="frm_input" style="width:100%;max-width:240px;"></td>
    </tr>
    <tr>
        <th scope="row"><label for="ed_thumb_file">목록 썸네일</label></th>
        <td>
            <?php if ($thumb_url) { ?>
            <p style="margin:0 0 8px;"><img src="<?php echo htmlspecialchars($thumb_url, ENT_QUOTES, 'UTF-8'); ?>" alt="" class="tbc-edu-thumb-preview"></p>
            <label style="margin-right:12px;"><input type="checkbox" name="ed_thumb_remove" value="1"> 현재 썸네일 삭제</label>
            <?php } ?>
            <input type="file" name="ed_thumb_file" id="ed_thumb_file" accept="image/jpeg,image/png,image/webp,image/gif">
            <p class="tbc-edu-help">비워 두면 본문 첫 번째 사진이 썸네일로 사용됩니다.</p>
        </td>
    </tr>
    <tr>
        <th scope="row">본문 (밴드형)</th>
        <td>
            <div class="tbc-edu-editor-wrap">
                <div class="tbc-edu-editor-toolbar">
                    <button type="button" data-cmd="bold"><strong>B</strong></button>
                    <button type="button" data-cmd="italic"><em>I</em></button>
                    <button type="button" id="tbcEduInsertImage">사진 삽입</button>
                    <button type="button" data-cmd="createLink">링크</button>
                    <input type="file" id="tbcEduImageInput" accept="image/jpeg,image/png,image/webp,image/gif" style="display:none;">
                </div>
                <div id="tbcEduEditor" class="tbc-edu-editor-body" contenteditable="true"></div>
            </div>
        </td>
    </tr>
    <tr>
        <th scope="row">옵션</th>
        <td>
            <label><input type="checkbox" name="ed_use" value="1"<?php echo !empty($form['ed_use']) ? ' checked' : ''; ?>> 홈페이지 노출</label>
        </td>
    </tr>
    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <a href="./tbc_edu.php" class="btn btn_02">목록</a>
    <input type="submit" value="저장" class="btn_submit btn">
</div>
</form>

<script>
(function () {
    var token = <?php echo json_encode($admin_token); ?>;
    var editor = document.getElementById('tbcEduEditor');
    var hidden = document.getElementById('ed_content');
    var imageInput = document.getElementById('tbcEduImageInput');
    var form = document.getElementById('ftbcedu');

    function syncContent() {
        hidden.value = editor.innerHTML;
    }

    if (hidden.value) {
        editor.innerHTML = hidden.value;
    }

    document.querySelectorAll('.tbc-edu-editor-toolbar button[data-cmd]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var cmd = btn.getAttribute('data-cmd');
            if (cmd === 'createLink') {
                var url = prompt('링크 주소를 입력하세요.', 'https://');
                if (url) document.execCommand('createLink', false, url);
            } else {
                document.execCommand(cmd, false, null);
            }
            editor.focus();
            syncContent();
        });
    });

    document.getElementById('tbcEduInsertImage').addEventListener('click', function () {
        imageInput.click();
    });

    imageInput.addEventListener('change', function () {
        if (!imageInput.files || !imageInput.files[0]) return;

        var fd = new FormData();
        fd.append('token', token);
        fd.append('action', 'upload_image');
        fd.append('image', imageInput.files[0]);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', './tbc_edu_ajax.php');
        xhr.onload = function () {
            var res = {};
            try { res = JSON.parse(xhr.responseText); } catch (e) {}
            if (!res.ok) {
                alert(res.message || '이미지 업로드에 실패했습니다.');
                return;
            }

            editor.focus();
            document.execCommand('insertHTML', false, '<p><img src="' + res.url + '" alt=""></p>');
            syncContent();
            imageInput.value = '';
        };
        xhr.send(fd);
    });

    editor.addEventListener('input', syncContent);
    form.addEventListener('submit', function (e) {
        syncContent();
        if (!hidden.value || hidden.value.replace(/<[^>]+>/g, '').trim() === '') {
            e.preventDefault();
            alert('본문 내용을 입력해 주세요.');
        }
    });

    syncContent();
})();
</script>

<?php include_once('./admin.tail.php'); ?>
