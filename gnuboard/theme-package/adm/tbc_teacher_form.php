<?php

$sub_menu = '950300';

require_once './_common.php';



if ($is_admin != 'super') {

    alert('최고관리자만 접근 가능합니다.');

}



auth_check_menu($auth, $sub_menu, 'w');



include_once(G5_THEME_PATH . '/tbc.teacher.lib.php');

tbc_teacher_ensure_tables();



$tc_id = isset($_GET['tc_id']) ? (int) $_GET['tc_id'] : 0;

$default_subject = isset($_GET['subject']) ? preg_replace('/[^a-z]/', '', $_GET['subject']) : '';

$teacher = $tc_id ? tbc_teacher_get($tc_id) : null;



if ($tc_id && !$teacher) {

    alert('강사 정보를 찾을 수 없습니다.', './tbc_teachers.php');

}



$subjects = tbc_teacher_subjects();

$admin_token = get_admin_token();

$is_edit = (bool) $teacher;



$g5['title'] = $is_edit ? '강사 수정' : '강사 추가';

$admin_msg = !empty($_GET['msg']) ? strip_tags($_GET['msg']) : '';

include_once('./admin.head.php');



$form = $teacher ? $teacher : array(

    'tc_subject' => $default_subject,

    'tc_name' => '',

    'tc_slug' => '',

    'tc_subject_label' => '',

    'tc_tagline' => '',

    'tc_bg_tags' => '',

    'tc_round_tags' => '',

    'tc_expertise' => "내신 대비 심화·개념 완성\n수능 고난도 문제 해결 전략\n학생 수준별 맞춤 지도",

    'tc_career' => "해당 과목 전문 지도\n중등·고등 강의 경력\n학생 맞춤형 학습 설계\n더브레인코어 전문 강사",

    'tc_use' => 1,

    'tc_profile_image' => '',

    'curriculum_images' => array(),

    'intro_images' => array(),

);

$profile_preview_url = '';
if (!empty($form['tc_profile_image'])) {
    $profile_preview_url = tbc_media_resolve_url('teacher', $form['tc_profile_image'], '');
}

?>



<style>

.tbc-form-intro{margin-bottom:18px}

.tbc-form-section{margin-top:24px}

.tbc-form-title{margin:0 0 8px;font-size:16px;font-weight:700;color:#1e293b}

.tbc-form-desc{margin:0 0 12px;color:#64748b;font-size:13px}

.tbc-form-preview img{display:block;max-width:220px;border-radius:10px;border:1px solid #e2e8f0}
.tbc-form-preview-empty{padding:24px;text-align:center;color:#64748b;border:1px dashed #cbd5e1;border-radius:10px;background:#f8fafc;max-width:220px}

.tbc-image-panel{margin-top:10px}

.tbc-image-list{list-style:none;margin:0;padding:0}

.tbc-image-item{display:flex;align-items:center;gap:14px;padding:12px;border:1px solid #e2e8f0;border-radius:10px;background:#fff;margin-bottom:8px}

.tbc-image-item img{width:120px;height:auto;border-radius:8px;border:1px solid #e2e8f0}

.tbc-image-order{display:flex;flex-direction:column;gap:4px}

.tbc-image-order button{width:28px;height:24px;border:1px solid #cbd5e1;border-radius:6px;background:#fff;cursor:pointer}

.tbc-image-order-num{width:28px;height:28px;border-radius:50%;background:#2563eb;color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700}

.tbc-image-empty{padding:24px;text-align:center;color:#64748b;border:1px dashed #cbd5e1;border-radius:10px;background:#f8fafc}

.tbc-image-status{min-height:18px;margin-top:6px;font-size:13px;color:#2563eb}

.tbc-image-status.is-error{color:#dc2626}

</style>



<?php if ($admin_msg) { ?>

<div class="local_desc01 local_desc" style="margin-bottom:15px;">

    <p><?php echo nl2br(htmlspecialchars($admin_msg, ENT_QUOTES, 'UTF-8')); ?></p>

</div>

<?php } ?>



<div class="local_desc01 local_desc tbc-form-intro">

    <p>강사 정보를 입력하고 맨 아래 <strong>「저장」</strong> 버튼을 눌러주세요.<br>

    커리큘럼·소개 사진은 여러 장 등록할 수 있고, ▲▼ 으로 순서를 바꿀 수 있습니다.</p>

</div>



<form name="ftbcteacher" id="ftbcteacher" action="./tbc_teacher_update.php" method="post" enctype="multipart/form-data">

<input type="hidden" name="token" value="<?php echo $admin_token; ?>">

<input type="hidden" name="tc_id" value="<?php echo (int) $tc_id; ?>">



<div class="tbl_frm01 tbl_wrap tbc-form-section">

    <h2 class="tbc-form-title">① 기본 정보</h2>

    <p class="tbc-form-desc">강사 카드에 표시되는 이름·과목·한줄 소개입니다.</p>

    <table>

    <colgroup><col class="grid_4"><col></colgroup>

    <tbody>

    <tr>

        <th scope="row"><label for="tc_subject">과목</label></th>

        <td>

            <select name="tc_subject" id="tc_subject" class="frm_input" required>

                <option value="">선택</option>

                <?php foreach ($subjects as $code => $label) { ?>

                <option value="<?php echo $code; ?>"<?php echo $form['tc_subject'] === $code ? ' selected' : ''; ?>><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></option>

                <?php } ?>

            </select>

        </td>

    </tr>

    <tr>

        <th scope="row"><label for="tc_name">강사 이름</label></th>

        <td><input type="text" name="tc_name" id="tc_name" value="<?php echo get_text($form['tc_name']); ?>" class="frm_input" style="width:100%;max-width:320px;" required placeholder="예: 홍길동 강사"></td>

    </tr>

    <tr>

        <th scope="row"><label for="tc_subject_label">과목 표시 문구</label></th>

        <td>

            <input type="text" name="tc_subject_label" id="tc_subject_label" value="<?php echo get_text($form['tc_subject_label']); ?>" class="frm_input" style="width:100%;max-width:420px;" placeholder="예: 수학 / 중등·고등">

            <p class="frm_info">강사 카드에 작게 표시되는 과목 설명입니다.</p>

        </td>

    </tr>

    <tr>

        <th scope="row"><label for="tc_tagline">한줄 소개</label></th>

        <td><input type="text" name="tc_tagline" id="tc_tagline" value="<?php echo get_text($form['tc_tagline']); ?>" class="frm_input" style="width:100%;max-width:520px;" placeholder="예: 막히던 수학이 풀리는 순간"></td>

    </tr>

    <tr>

        <th scope="row"><label for="tc_slug">페이지 링크 ID</label></th>

        <td>

            <input type="text" name="tc_slug" id="tc_slug" value="<?php echo get_text($form['tc_slug']); ?>" class="frm_input" style="width:100%;max-width:320px;" placeholder="예: teacher-hong">

            <p class="frm_info">비워두면 자동 생성됩니다. 메인 등에서 #teacher-hong 형태로 연결할 때 사용합니다.</p>

        </td>

    </tr>

    <tr>

        <th scope="row">노출 여부</th>

        <td><label><input type="checkbox" name="tc_use" value="1"<?php echo !empty($form['tc_use']) ? ' checked' : ''; ?>> 강사진 페이지에 표시</label></td>

    </tr>

    </tbody>

    </table>

</div>



<div class="tbl_frm01 tbl_wrap tbc-form-section">

    <h2 class="tbc-form-title">② 프로필 사진</h2>

    <p class="tbc-form-desc">강사 카드 왼쪽에 보이는 대표 사진입니다.</p>

    <table>

    <colgroup><col class="grid_4"><col></colgroup>

    <tbody>

    <tr>

        <th scope="row">현재 사진</th>

        <td>
            <div class="tbc-form-preview" id="profile-preview">
                <?php if ($profile_preview_url) { ?>
                <img src="<?php echo $profile_preview_url; ?>" alt="프로필 미리보기">
                <?php } else { ?>
                <div class="tbc-form-preview-empty">등록된 사진이 없습니다.</div>
                <?php } ?>
            </div>
        </td>

    </tr>

    <tr>

        <th scope="row"><label for="profile_image">사진 업로드</label></th>

        <td>

            <input type="file" name="profile_image" id="profile_image" accept=".jpg,.jpeg,.png,.webp,.gif">

            <p class="frm_info">jpg, png, webp, gif · 최대 5MB</p>

        </td>

    </tr>

    </tbody>

    </table>

</div>



<div class="tbl_frm01 tbl_wrap tbc-form-section">

    <h2 class="tbc-form-title">③ 사진 위·뱃지 태그</h2>

    <p class="tbc-form-desc">프로필 사진 위에 겹쳐 보이는 키워드입니다. 쉼표(,)로 구분해 입력하세요.</p>

    <table>

    <colgroup><col class="grid_4"><col></colgroup>

    <tbody>

    <tr>

        <th scope="row"><label for="tc_bg_tags">사진 위 태그</label></th>

        <td><input type="text" name="tc_bg_tags" id="tc_bg_tags" value="<?php echo get_text($form['tc_bg_tags']); ?>" class="frm_input" style="width:100%;max-width:420px;" placeholder="예: 수학,입시"></td>

    </tr>

    <tr>

        <th scope="row"><label for="tc_round_tags">이름 위 뱃지</label></th>

        <td><input type="text" name="tc_round_tags" id="tc_round_tags" value="<?php echo get_text($form['tc_round_tags']); ?>" class="frm_input" style="width:100%;max-width:420px;" placeholder="예: 수학,중등,고등"></td>

    </tr>

    </tbody>

    </table>

</div>



<div class="tbl_frm01 tbl_wrap tbc-form-section">

    <h2 class="tbc-form-title">④ 전문 지도 · 학력 경력</h2>

    <p class="tbc-form-desc">강사 카드 오른쪽에 표시됩니다. 한 줄에 한 항목씩 입력하세요.</p>

    <table>

    <colgroup><col class="grid_4"><col></colgroup>

    <tbody>

    <tr>

        <th scope="row"><label for="tc_expertise">전문 지도 분야</label></th>

        <td><textarea name="tc_expertise" id="tc_expertise" rows="5" class="frm_input" style="width:100%;max-width:640px;"><?php echo get_text($form['tc_expertise']); ?></textarea></td>

    </tr>

    <tr>

        <th scope="row"><label for="tc_career">학력 및 경력</label></th>

        <td><textarea name="tc_career" id="tc_career" rows="5" class="frm_input" style="width:100%;max-width:640px;"><?php echo get_text($form['tc_career']); ?></textarea></td>

    </tr>

    </tbody>

    </table>

</div>



<?php

$gallery_sections = array(

    array('type' => 'curriculum', 'step' => '⑤', 'title' => '커리큘럼 사진', 'desc' => '「커리큘럼 바로가기」 모달에 순서대로 표시됩니다.', 'field' => 'curriculum_images', 'input' => 'curriculum_images'),

    array('type' => 'intro', 'step' => '⑥', 'title' => '수업 특징·소개 사진', 'desc' => '「수업 특징 바로가기」 모달에 순서대로 표시됩니다.', 'field' => 'intro_images', 'input' => 'intro_images'),

);



foreach ($gallery_sections as $section) {

    $images = isset($form[$section['field']]) ? $form[$section['field']] : array();

?>

<div class="tbl_frm01 tbl_wrap tbc-form-section tbc-image-panel" data-gallery-type="<?php echo $section['type']; ?>">

    <h2 class="tbc-form-title"><?php echo $section['step'] . ' ' . $section['title']; ?></h2>

    <p class="tbc-form-desc"><?php echo htmlspecialchars($section['desc'], ENT_QUOTES, 'UTF-8'); ?></p>

    <p class="tbc-image-status" id="status-<?php echo $section['type']; ?>" aria-live="polite"></p>



    <ul class="tbc-image-list" id="list-<?php echo $section['type']; ?>">

        <?php if ($images) {

            foreach ($images as $i => $image) { ?>

        <li class="tbc-image-item" data-ti-id="<?php echo (int) $image['ti_id']; ?>">

            <div class="tbc-image-order">

                <span class="tbc-image-order-num"><?php echo $i + 1; ?></span>

                <button type="button" class="tbc-img-up" title="위로">▲</button>

                <button type="button" class="tbc-img-down" title="아래로">▼</button>

            </div>

            <img src="<?php echo $image['image_url']; ?>" alt="">

            <button type="button" class="btn btn_02 tbc-img-delete">삭제</button>

        </li>

        <?php }

        } ?>

    </ul>



    <div class="tbc-image-empty" id="empty-<?php echo $section['type']; ?>"<?php echo $images ? ' style="display:none;"' : ''; ?>>

        등록된 사진이 없습니다. 아래에서 추가해 주세요.

    </div>



    <table style="margin-top:14px;">

    <colgroup><col class="grid_4"><col></colgroup>

    <tbody>

    <tr>

        <th scope="row">사진 추가</th>

        <td>

            <input type="file" name="<?php echo $section['input']; ?>[]" accept=".jpg,.jpeg,.png,.webp,.gif" multiple>

            <p class="frm_info">여러 장을 한 번에 선택할 수 있습니다. 저장 시 목록 맨 아래에 추가됩니다.</p>

        </td>

    </tr>

    </tbody>

    </table>

</div>

<?php } ?>



<div class="btn_fixed_top">

    <a href="./tbc_teachers.php" class="btn btn_02">목록</a>

    <input type="submit" value="강사 저장" class="btn_submit btn">

</div>

</form>



<script>

(function($) {

    var ajaxUrl = './tbc_teacher_ajax.php';

    var adminToken = <?php echo json_encode($admin_token); ?>;



    function setGalleryStatus(type, message, isError) {

        var $status = $('#status-' + type);

        $status.text(message || '').toggleClass('is-error', !!isError);

    }



    function updateGalleryUi(type) {

        var $list = $('#list-' + type);

        var $items = $list.children('.tbc-image-item');

        $items.each(function(index) {

            var $item = $(this);

            $item.find('.tbc-image-order-num').text(index + 1);

            $item.find('.tbc-img-up').prop('disabled', index === 0);

            $item.find('.tbc-img-down').prop('disabled', index === $items.length - 1);

        });

        $('#empty-' + type).toggle($items.length === 0);

    }



    function postAction(data, onSuccess, onFail) {

        data.token = adminToken;

        $.ajax({

            url: ajaxUrl,

            method: 'POST',

            dataType: 'json',

            data: data

        }).done(function(res) {

            if (!res || !res.ok) {

                onFail(res && res.message ? res.message : '요청에 실패했습니다.');

                return;

            }

            onSuccess(res);

        }).fail(function() {

            onFail('서버 통신에 실패했습니다.');

        });

    }



    $('.tbc-image-panel').each(function() {

        var type = $(this).data('gallery-type');

        updateGalleryUi(type);

    });



    $('.tbc-image-list').on('click', '.tbc-img-up, .tbc-img-down', function() {

        var $item = $(this).closest('.tbc-image-item');

        var $panel = $item.closest('.tbc-image-panel');

        var type = $panel.data('gallery-type');

        var tiId = $item.data('ti-id');

        var direction = $(this).hasClass('tbc-img-up') ? 'up' : 'down';

        var $target = direction === 'up' ? $item.prev('.tbc-image-item') : $item.next('.tbc-image-item');



        if (!$target.length) return;



        if (direction === 'up') {

            $item.insertBefore($target);

        } else {

            $item.insertAfter($target);

        }

        updateGalleryUi(type);

        setGalleryStatus(type, '순서 변경 중...');



        postAction({ action: 'move_image', ti_id: tiId, direction: direction }, function() {

            setGalleryStatus(type, '순서가 변경되었습니다.');

        }, function(message) {

            if (direction === 'up') {

                $item.insertAfter($target);

            } else {

                $item.insertBefore($target);

            }

            updateGalleryUi(type);

            setGalleryStatus(type, message, true);

        });

    });



    $('.tbc-image-list').on('click', '.tbc-img-delete', function() {

        var $item = $(this).closest('.tbc-image-item');

        var $panel = $item.closest('.tbc-image-panel');

        var type = $panel.data('gallery-type');

        var tiId = $item.data('ti-id');



        if (!window.confirm('이 사진을 삭제하시겠습니까?')) return;



        setGalleryStatus(type, '삭제 중...');

        postAction({ action: 'delete_image', ti_id: tiId }, function() {

            $item.remove();

            updateGalleryUi(type);

            setGalleryStatus(type, '사진이 삭제되었습니다.');

        }, function(message) {

            setGalleryStatus(type, message, true);

        });

    });

})(jQuery);

</script>



<?php

include_once('./admin.tail.php');

