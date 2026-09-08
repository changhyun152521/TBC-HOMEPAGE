<?php
$sub_menu = '950380';
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

include_once(G5_THEME_PATH . '/tbc.admission.lib.php');
tbc_admission_ensure_tables();
tbc_admission_seed_defaults();

$config = tbc_admission_get_config();
$steps = tbc_admission_get_steps(false);
$points = tbc_admission_get_points(false);
$flows = tbc_admission_get_flows(false);
$admin_token = get_admin_token();

$g5['title'] = '입학절차 관리';
$admin_msg = !empty($_GET['msg']) ? strip_tags($_GET['msg']) : '';
include_once('./admin.head.php');
?>

<style>
.tbc-adm-section{margin-bottom:36px}
.tbc-adm-section h2{margin:0 0 8px;font-size:16px;font-weight:700;color:#1e293b}
.tbc-adm-section p{margin:0 0 14px;color:#64748b;font-size:13px}
.tbc-adm-repeat{border:1px solid #e2e8f0;border-radius:10px;padding:16px;margin-bottom:12px;background:#fff}
.tbc-adm-repeat h3{margin:0 0 12px;font-size:14px;font-weight:700;color:#334155}
.tbc-adm-icon-preview img{display:block;width:56px;height:56px;object-fit:contain;border:1px solid #e2e8f0;border-radius:8px;background:#f8fafc}
</style>

<?php if ($admin_msg) { ?>
<div class="local_desc01 local_desc" style="margin-bottom:15px;">
    <p><?php echo nl2br(htmlspecialchars($admin_msg, ENT_QUOTES, 'UTF-8')); ?></p>
</div>
<?php } ?>

<div class="local_desc01 local_desc">
    <p>메뉴 <strong>입학안내 → 입학절차</strong> 페이지(service1003 디자인) 내용을 수정합니다.<br>
    상단 제목 → 단계 목록 → 포인트 카드 → 하단 테스트 안내 순으로 편집한 뒤 저장하세요.</p>
</div>

<form name="ftbcadmission" id="ftbcadmission" action="./tbc_admission_update.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="token" value="<?php echo $admin_token; ?>">

<div class="tbc-adm-section tbl_frm01 tbl_wrap">
    <h2>① 상단 제목</h2>
    <p>페이지 가운데 큰 제목입니다.</p>
    <table>
    <colgroup><col class="grid_4"><col></colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="ad_heading">제목</label></th>
        <td><input type="text" name="ad_heading" id="ad_heading" value="<?php echo get_text($config['ad_heading']); ?>" class="frm_input" style="width:100%;max-width:480px;"></td>
    </tr>
    </tbody>
    </table>
</div>

<div class="tbc-adm-section">
    <h2>② 입학 절차 단계</h2>
    <p>번호·강조 문구·설명을 입력합니다. 강조 문구는 분홍색으로 표시됩니다.</p>
    <?php foreach ($steps as $idx => $step) { ?>
    <div class="tbc-adm-repeat">
        <h3>단계 <?php echo (int) ($idx + 1); ?></h3>
        <table>
        <colgroup><col class="grid_4"><col></colgroup>
        <tbody>
        <tr>
            <th scope="row">번호</th>
            <td><input type="text" name="step_num[]" value="<?php echo get_text($step['as_num']); ?>" class="frm_input" style="width:80px;"></td>
        </tr>
        <tr>
            <th scope="row">앞 문장</th>
            <td><input type="text" name="step_before[]" value="<?php echo get_text($step['as_text_before']); ?>" class="frm_input" style="width:100%;max-width:640px;"></td>
        </tr>
        <tr>
            <th scope="row">강조 문구</th>
            <td><input type="text" name="step_em[]" value="<?php echo get_text($step['as_text_em']); ?>" class="frm_input" style="width:100%;max-width:640px;"></td>
        </tr>
        <tr>
            <th scope="row">뒤 문장</th>
            <td><input type="text" name="step_after[]" value="<?php echo get_text($step['as_text_after']); ?>" class="frm_input" style="width:100%;max-width:640px;"></td>
        </tr>
        <tr>
            <th scope="row">설명</th>
            <td><textarea name="step_desc[]" rows="2" class="frm_input" style="width:100%;max-width:640px;"><?php echo get_text($step['as_desc']); ?></textarea></td>
        </tr>
        <tr>
            <th scope="row">노출</th>
            <td><label><input type="checkbox" name="step_use[<?php echo (int) $idx; ?>]" value="1"<?php echo !empty($step['as_use']) ? ' checked' : ''; ?>> 표시</label></td>
        </tr>
        </tbody>
        </table>
    </div>
    <?php } ?>
</div>

<div class="tbc-adm-section">
    <h2>③ 포인트 카드</h2>
    <p>4개 카드 형태로 표시됩니다. 줄바꿈은 페이지에서 그대로 반영됩니다.</p>
    <?php foreach ($points as $idx => $point) { ?>
    <div class="tbc-adm-repeat">
        <h3>카드 <?php echo (int) ($idx + 1); ?></h3>
        <table>
        <colgroup><col class="grid_4"><col></colgroup>
        <tbody>
        <tr>
            <th scope="row">라벨</th>
            <td><input type="text" name="point_label[]" value="<?php echo get_text($point['ap_label']); ?>" class="frm_input" style="width:200px;"></td>
        </tr>
        <tr>
            <th scope="row">내용</th>
            <td><textarea name="point_text[]" rows="2" class="frm_input" style="width:100%;max-width:640px;"><?php echo get_text($point['ap_text']); ?></textarea></td>
        </tr>
        <tr>
            <th scope="row">노출</th>
            <td><label><input type="checkbox" name="point_use[<?php echo (int) $idx; ?>]" value="1"<?php echo !empty($point['ap_use']) ? ' checked' : ''; ?>> 표시</label></td>
        </tr>
        </tbody>
        </table>
    </div>
    <?php } ?>
</div>

<div class="tbc-adm-section tbl_frm01 tbl_wrap">
    <h2>④ 레벨 테스트 안내</h2>
    <p>하단 박스 제목과 설명입니다. 강조 문구는 밑줄로 표시됩니다.</p>
    <table>
    <colgroup><col class="grid_4"><col></colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="ad_test_title">제목</label></th>
        <td><input type="text" name="ad_test_title" id="ad_test_title" value="<?php echo get_text($config['ad_test_title']); ?>" class="frm_input" style="width:100%;max-width:480px;"></td>
    </tr>
    <tr>
        <th scope="row"><label for="ad_test_highlight">강조 문구</label></th>
        <td><textarea name="ad_test_highlight" id="ad_test_highlight" rows="3" class="frm_input" style="width:100%;max-width:640px;"><?php echo get_text($config['ad_test_highlight']); ?></textarea></td>
    </tr>
    <tr>
        <th scope="row"><label for="ad_test_body">나머지 설명</label></th>
        <td><textarea name="ad_test_body" id="ad_test_body" rows="3" class="frm_input" style="width:100%;max-width:640px;"><?php echo get_text($config['ad_test_body']); ?></textarea></td>
    </tr>
    </tbody>
    </table>
</div>

<div class="tbc-adm-section">
    <h2>⑤ 테스트 진행 단계 (아이콘 3개)</h2>
    <p>하단 3단계 카드입니다. 아이콘은 png/svg 업로드 가능합니다.</p>
    <?php foreach ($flows as $idx => $flow) { ?>
    <div class="tbc-adm-repeat">
        <h3>단계 <?php echo (int) ($idx + 1); ?></h3>
        <input type="hidden" name="flow_icon_old[]" value="<?php echo get_text($flow['af_icon']); ?>">
        <table>
        <colgroup><col class="grid_4"><col></colgroup>
        <tbody>
        <tr>
            <th scope="row">현재 아이콘</th>
            <td class="tbc-adm-icon-preview">
                <img src="<?php echo htmlspecialchars(tbc_admission_resolve_icon_url($flow['af_icon'], $idx), ENT_QUOTES, 'UTF-8'); ?>" alt="">
            </td>
        </tr>
        <tr>
            <th scope="row">아이콘 교체</th>
            <td><input type="file" name="flow_icon[]" accept=".jpg,.jpeg,.png,.webp,.gif,.svg"></td>
        </tr>
        <tr>
            <th scope="row">문구</th>
            <td><input type="text" name="flow_label[]" value="<?php echo get_text($flow['af_label']); ?>" class="frm_input" style="width:100%;max-width:640px;"></td>
        </tr>
        <tr>
            <th scope="row">노출</th>
            <td><label><input type="checkbox" name="flow_use[<?php echo (int) $idx; ?>]" value="1"<?php echo !empty($flow['af_use']) ? ' checked' : ''; ?>> 표시</label></td>
        </tr>
        </tbody>
        </table>
    </div>
    <?php } ?>
</div>

<div class="btn_fixed_top">
    <input type="submit" value="입학절차 저장" class="btn_submit btn">
</div>

</form>

<?php
include_once('./admin.tail.php');
