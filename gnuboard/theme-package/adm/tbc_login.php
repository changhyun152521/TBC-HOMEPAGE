<?php
/**
 * TBC 관리자 전용 로그인 화면
 */
include_once(dirname(__FILE__) . '/../common.php');

if ($is_member) {
    $redirect = isset($_GET['url']) ? clean_xss_tags($_GET['url']) : G5_ADMIN_URL;
    if (function_exists('correct_goto_url')) {
        $redirect = correct_goto_url($redirect);
    }
    goto_url($redirect);
}

$return_url = isset($_GET['url']) ? clean_xss_tags($_GET['url']) : G5_ADMIN_URL;
if (function_exists('correct_goto_url')) {
    $return_url = correct_goto_url($return_url);
}

$site_title = defined('TBC_SITE_TITLE') ? TBC_SITE_TITLE : $config['cf_title'];
$logo_url = G5_THEME_URL . '/img/common/logo.png';
$bg_url = G5_THEME_URL . '/img/common/all_bg00.jpg';
$login_css = G5_ADMIN_URL . '/css/tbc_login.css';
$login_error = '';

if (!empty($_GET['msg'])) {
    $login_error = strip_tags($_GET['msg']);
}
?>
<!doctype html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title>관리자 로그인 | <?php echo htmlspecialchars($site_title, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="icon" href="<?php echo G5_THEME_URL; ?>/favicon.ico" sizes="any">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard-dynamic-subset.css">
    <link rel="stylesheet" href="<?php echo $login_css; ?>">
</head>
<body class="tbc-admin-login" style="--tbc-login-bg: url('<?php echo $bg_url; ?>');">
    <div class="tbc-admin-login__overlay"></div>

    <main class="tbc-admin-login__wrap">
        <section class="tbc-admin-login__card" aria-labelledby="tbc-admin-login-title">
            <div class="tbc-admin-login__brand">
                <a href="<?php echo G5_URL; ?>" class="tbc-admin-login__logo-link">
                    <img src="<?php echo $logo_url; ?>" alt="더브레인코어" class="tbc-admin-login__logo">
                </a>
                <p class="tbc-admin-login__site"><?php echo htmlspecialchars($site_title, ENT_QUOTES, 'UTF-8'); ?></p>
                <h1 id="tbc-admin-login-title" class="tbc-admin-login__title">관리자 로그인</h1>
                <p class="tbc-admin-login__desc">TBC 홈페이지 콘텐츠를 관리하려면 로그인해 주세요.</p>
            </div>

            <?php if ($login_error) { ?>
            <div class="tbc-admin-login__error" role="alert"><?php echo htmlspecialchars($login_error, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php } ?>

            <form class="tbc-admin-login__form" method="post" action="<?php echo G5_BBS_URL; ?>/login_check.php" autocomplete="on">
                <input type="hidden" name="url" value="<?php echo htmlspecialchars($return_url, ENT_QUOTES, 'UTF-8'); ?>">

                <label class="tbc-admin-login__field">
                    <span class="tbc-admin-login__label">아이디</span>
                    <input type="text" name="mb_id" id="login_id" class="tbc-admin-login__input" required maxlength="20" placeholder="관리자 아이디">
                </label>

                <label class="tbc-admin-login__field">
                    <span class="tbc-admin-login__label">비밀번호</span>
                    <input type="password" name="mb_password" id="login_pw" class="tbc-admin-login__input" required placeholder="비밀번호">
                </label>

                <label class="tbc-admin-login__remember">
                    <input type="checkbox" name="auto_login" id="login_auto" value="1">
                    <span>자동 로그인</span>
                </label>

                <button type="submit" class="tbc-admin-login__submit">로그인</button>
            </form>

            <div class="tbc-admin-login__footer">
                <a href="<?php echo G5_URL; ?>" class="tbc-admin-login__home">홈페이지로 돌아가기</a>
            </div>
        </section>

        <p class="tbc-admin-login__copy">ⓒ 더브레인코어</p>
    </main>

    <script>
    (function () {
        var idInput = document.getElementById('login_id');
        if (idInput) {
            idInput.focus();
        }
    })();
    </script>
</body>
</html>
