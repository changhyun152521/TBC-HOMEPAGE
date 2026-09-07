<?php
$sub_menu = '950100';
require_once './_common.php';

if ($is_admin != 'super') {
    alert('최고관리자만 접근 가능합니다.');
}

auth_check_menu($auth, $sub_menu, 'r');

include_once(G5_THEME_PATH . '/tbc.main.lib.php');
tbc_main_ensure_tables();
tbc_main_seed_defaults();

$config = tbc_main_get_config();
$banners = tbc_main_get_all_banners();
$field_groups = tbc_main_admin_field_groups();
$admin_token = get_admin_token();

$g5['title'] = 'TBC 메인관리';
$admin_msg = !empty($_GET['msg']) ? strip_tags($_GET['msg']) : '';
include_once('./admin.head.php');
?>

<style>
.tbc-admin-intro { margin-bottom: 20px; }
.tbc-section-title { margin: 28px 0 10px; font-size: 16px; font-weight: 700; color: #1e293b; }
.tbc-section-desc { margin: 0 0 12px; color: #64748b; font-size: 13px; }
.tbc-banner-panel { margin-top: 10px; }
.tbc-banner-list { list-style: none; margin: 0; padding: 0; }
.tbc-banner-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 14px 16px;
    margin-bottom: 10px;
    border: 1px solid #dfe3e8;
    border-radius: 10px;
    background: #fff;
    transition: transform 0.25s ease, box-shadow 0.25s ease, opacity 0.25s ease;
}
.tbc-banner-item.is-moving {
    transform: scale(1.01);
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.15);
    border-color: #93c5fd;
}
.tbc-banner-item.is-removing {
    opacity: 0;
    transform: translateX(24px);
}
.tbc-banner-order {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    min-width: 56px;
}
.tbc-banner-order-num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #2563eb;
    color: #fff;
    font-size: 13px;
    font-weight: 700;
}
.tbc-banner-order-btns { display: flex; flex-direction: column; gap: 4px; }
.tbc-banner-order-btns button {
    width: 30px;
    height: 26px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    background: #f8fafc;
    cursor: pointer;
    line-height: 1;
}
.tbc-banner-order-btns button:hover { background: #e2e8f0; }
.tbc-banner-order-btns button:disabled { opacity: 0.35; cursor: not-allowed; }
.tbc-banner-preview img {
    display: block;
    width: 220px;
    max-width: 100%;
    height: auto;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
}
.tbc-banner-actions { margin-left: auto; display: flex; align-items: center; gap: 10px; }
.tbc-banner-empty {
    padding: 28px;
    text-align: center;
    color: #64748b;
    border: 1px dashed #cbd5e1;
    border-radius: 10px;
    background: #f8fafc;
}
.tbc-banner-status {
    min-height: 20px;
    margin: 8px 0 0;
    font-size: 13px;
    color: #2563eb;
}
.tbc-banner-status.is-error { color: #dc2626; }
</style>

<?php if ($admin_msg) { ?>
<div class="local_desc01 local_desc" style="margin-bottom:15px;">
    <p><?php echo nl2br(htmlspecialchars($admin_msg, ENT_QUOTES, 'UTF-8')); ?></p>
</div>
<?php } ?>

<div class="local_desc01 local_desc tbc-admin-intro">
    <p>메인 화면을 <strong>위에서 아래 순서</strong>대로 수정할 수 있습니다.<br>
    배너 이미지의 <strong>순서 변경·삭제</strong>는 저장 버튼 없이 바로 반영됩니다.<br>
    문구·이미지 교체는 맨 아래 <strong>「저장」</strong> 버튼을 눌러주세요.</p>
</div>

<form name="ftbcmain" id="ftbcmain" action="./tbc_main_update.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="token" value="<?php echo $admin_token; ?>">

<div class="tbc-banner-panel">
    <h2 class="h2_frm">① 최상단 — 배너 슬라이드 이미지</h2>
    <p class="tbc-section-desc">메뉴 바로 아래 돌아가는 큰 배너 사진입니다. ▲▼ 으로 순서를 바꾸고, 삭제는 바로 반영됩니다.</p>
    <p id="tbc-banner-status" class="tbc-banner-status" aria-live="polite"></p>

    <ul id="tbc-banner-list" class="tbc-banner-list">
        <?php if ($banners) {
            foreach ($banners as $i => $banner) {
                $preview = $banner['image_url'] ? $banner['image_url'] : G5_THEME_URL . '/img/main/main_banner_01.jpg';
        ?>
        <li class="tbc-banner-item" data-bn-id="<?php echo (int) $banner['bn_id']; ?>">
            <div class="tbc-banner-order">
                <span class="tbc-banner-order-num"><?php echo $i + 1; ?></span>
                <div class="tbc-banner-order-btns">
                    <button type="button" class="tbc-btn-up" title="위로">▲</button>
                    <button type="button" class="tbc-btn-down" title="아래로">▼</button>
                </div>
            </div>
            <div class="tbc-banner-preview">
                <img src="<?php echo $preview; ?>" alt="배너 미리보기">
            </div>
            <div class="tbc-banner-file">
                <input type="file" name="banner_file[<?php echo (int) $banner['bn_id']; ?>]" accept=".jpg,.jpeg,.png,.webp,.gif">
                <p class="frm_info">이미지 바꾸기 (저장 필요)</p>
            </div>
            <div class="tbc-banner-actions">
                <button type="button" class="btn btn_02 tbc-btn-delete">삭제</button>
            </div>
        </li>
        <?php }
        } ?>
    </ul>

    <div id="tbc-banner-empty" class="tbc-banner-empty"<?php echo $banners ? ' style="display:none;"' : ''; ?>>
        등록된 배너가 없습니다. 아래에서 새 배너를 추가하세요.
    </div>

    <div class="tbl_frm01 tbl_wrap" style="margin-top:16px;">
        <table>
        <caption>배너 추가</caption>
        <colgroup><col class="grid_4"><col></colgroup>
        <tbody>
        <tr>
            <th scope="row">새 배너</th>
            <td>
                <input type="file" name="new_banner" accept=".jpg,.jpeg,.png,.webp,.gif">
                <p class="frm_info">jpg, png, webp, gif · 최대 5MB · 저장 시 슬라이드에 추가됩니다.</p>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
</div>

<?php foreach ($field_groups as $group) { ?>
<div class="tbl_frm01 tbl_wrap" style="margin-top:24px;">
    <h2 class="tbc-section-title"><?php echo htmlspecialchars($group['step'] . ' ' . $group['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
    <p class="tbc-section-desc"><?php echo htmlspecialchars($group['desc'], ENT_QUOTES, 'UTF-8'); ?></p>
    <table>
    <caption><?php echo htmlspecialchars($group['title'], ENT_QUOTES, 'UTF-8'); ?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
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
            <p class="frm_info">줄바꿈은 메인 화면에서 줄이 바뀌어 표시됩니다.</p>
            <?php } ?>
        </td>
    </tr>
    <?php } ?>
    </tbody>
    </table>
</div>
<?php } ?>

<div class="btn_fixed_top">
    <input type="submit" value="문구·이미지 저장" class="btn_submit btn">
</div>

</form>

<script>
(function($) {
    var ajaxUrl = './tbc_banner_ajax.php';
    var adminToken = <?php echo json_encode($admin_token); ?>;
    var $list = $('#tbc-banner-list');
    var $empty = $('#tbc-banner-empty');
    var $status = $('#tbc-banner-status');

    function setStatus(message, isError) {
        $status.text(message || '').toggleClass('is-error', !!isError);
        if (message && !isError) {
            window.setTimeout(function() {
                if ($status.text() === message) {
                    $status.text('');
                }
            }, 2000);
        }
    }

    function updateOrderUi() {
        var $items = $list.children('.tbc-banner-item');
        $items.each(function(index) {
            var $item = $(this);
            $item.find('.tbc-banner-order-num').text(index + 1);
            $item.find('.tbc-btn-up').prop('disabled', index === 0);
            $item.find('.tbc-btn-down').prop('disabled', index === $items.length - 1);
        });
        $empty.toggle($items.length === 0);
    }

    function swapDomItem($item, direction) {
        var $target = direction === 'up' ? $item.prev('.tbc-banner-item') : $item.next('.tbc-banner-item');
        if (!$target.length) {
            return false;
        }

        $item.addClass('is-moving');
        window.setTimeout(function() {
            $item.removeClass('is-moving');
        }, 260);

        if (direction === 'up') {
            $item.insertBefore($target);
        } else {
            $item.insertAfter($target);
        }

        updateOrderUi();
        return true;
    }

    function postBannerAction(data, onSuccess, onFail) {
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

    $list.on('click', '.tbc-btn-up, .tbc-btn-down', function() {
        var $btn = $(this);
        var $item = $btn.closest('.tbc-banner-item');
        var direction = $btn.hasClass('tbc-btn-up') ? 'up' : 'down';
        var bnId = $item.data('bn-id');
        var rollbackItem = $item;
        var rollbackDirection = direction === 'up' ? 'down' : 'up';

        if (!swapDomItem($item, direction)) {
            return;
        }

        setStatus('순서 변경 중...');

        postBannerAction({
            action: 'move',
            bn_id: bnId,
            direction: direction
        }, function() {
            setStatus('순서가 변경되었습니다.');
        }, function(message) {
            swapDomItem(rollbackItem, rollbackDirection);
            setStatus(message, true);
        });
    });

    $list.on('click', '.tbc-btn-delete', function() {
        var $item = $(this).closest('.tbc-banner-item');
        var bnId = $item.data('bn-id');

        if (!window.confirm('이 배너를 삭제하시겠습니까?\n삭제하면 메인 슬라이드에서도 바로 제거됩니다.')) {
            return;
        }

        setStatus('삭제 중...');

        postBannerAction({
            action: 'delete',
            bn_id: bnId
        }, function() {
            $item.addClass('is-removing');
            window.setTimeout(function() {
                $item.remove();
                updateOrderUi();
                setStatus('배너가 삭제되었습니다.');
            }, 240);
        }, function(message) {
            setStatus(message, true);
        });
    });

    updateOrderUi();
})(jQuery);
</script>

<?php
include_once('./admin.tail.php');
