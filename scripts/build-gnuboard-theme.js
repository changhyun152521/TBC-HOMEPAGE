/**
 * apps/web HTML → 그누보드 TBC 테마 변환
 */
const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const ROOT = path.join(__dirname, '..');
const WEB = path.join(ROOT, 'apps', 'web');
const THEME = path.join(ROOT, 'gnuboard', 'theme-package', 'tbc');
const PAGES = path.join(THEME, 'pages');

const PAGE_MAP = {
  'sub1.html': 'greeting',
  'about-philosophy.html': 'philosophy',
  'about-history.html': 'history',
  'academies.html': 'academies',
  'academies-main.html': 'academies_main',
  'academies-branch.html': 'academies_branch',
  'teachers.html': 'teachers',
  'teachers-korean.html': 'teachers_korean',
  'teachers-math.html': 'teachers_math',
  'teachers-science.html': 'teachers_science',
  'teachers-english.html': 'teachers_english',
  'teachers-social.html': 'teachers_social',
  'schedule.html': 'schedule',
  'schedule-main.html': 'schedule_main',
  'schedule-branch.html': 'schedule_branch',
};

const HREF_MAP = {
  'index.html': "<?php echo G5_URL; ?>",
  'sub1.html': "<?php echo tbc_page_url('greeting'); ?>",
  'about-philosophy.html': "<?php echo tbc_page_url('philosophy'); ?>",
  'about-history.html': "<?php echo tbc_page_url('history'); ?>",
  'academies.html': "<?php echo tbc_page_url('academies'); ?>",
  'academies-main.html': "<?php echo tbc_page_url('academies_main'); ?>",
  'academies-branch.html': "<?php echo tbc_page_url('academies_branch'); ?>",
  'teachers.html': "<?php echo tbc_page_url('teachers'); ?>",
  'teachers-korean.html': "<?php echo tbc_page_url('teachers_korean'); ?>",
  'teachers-math.html': "<?php echo tbc_page_url('teachers_math'); ?>",
  'teachers-science.html': "<?php echo tbc_page_url('teachers_science'); ?>",
  'teachers-english.html': "<?php echo tbc_page_url('teachers_english'); ?>",
  'teachers-social.html': "<?php echo tbc_page_url('teachers_social'); ?>",
  'schedule.html': "<?php echo tbc_page_url('schedule'); ?>",
  'schedule-main.html': "<?php echo tbc_page_url('schedule_main'); ?>",
  'schedule-branch.html': "<?php echo tbc_page_url('schedule_branch'); ?>",
};

const BOARD_HREF = {
  '공지사항': "<?php echo tbc_board_url('notice'); ?>",
  '교육정보': "<?php echo tbc_board_url('edu'); ?>",
  '교육·입시정보': "<?php echo tbc_board_url('edu'); ?>",
  '수강후기': "<?php echo tbc_board_url('review'); ?>",
  '상담신청': "<?php echo tbc_board_url('consult'); ?>",
  '입학절차': "<?php echo tbc_page_url('admission'); ?>",
  'FAQ': "<?php echo tbc_page_url('faq'); ?>",
};

function copyDir(from, to) {
  fs.mkdirSync(to, { recursive: true });
  if (fs.cpSync) {
    fs.cpSync(from, to, { recursive: true, force: true });
    return;
  }
  for (const entry of fs.readdirSync(from, { withFileTypes: true })) {
    const src = path.join(from, entry.name);
    const dest = path.join(to, entry.name);
    if (entry.isDirectory()) copyDir(src, dest);
    else fs.copyFileSync(src, dest);
  }
}

function syncAssets() {
  const isWin = process.platform === 'win32';
  ['css', 'js', 'img'].forEach((dir) => {
    const from = path.join(WEB, dir);
    const to = path.join(THEME, dir);
    if (!fs.existsSync(from)) return;
    if (isWin) {
      try {
        execSync(`robocopy "${from}" "${to}" /E /NFL /NDL /NJH /NJS /nc /ns /np`, { stdio: 'ignore' });
      } catch (e) {
        /* robocopy: exit 1 = files copied */
      }
    } else {
      if (fs.existsSync(to)) fs.rmSync(to, { recursive: true, force: true });
      copyDir(from, to);
    }
    console.log(`  복사: ${dir}/`);
  });
  ['favicon.ico', 'favicon.png'].forEach((file) => {
    const from = path.join(WEB, file);
    if (fs.existsSync(from)) {
      fs.copyFileSync(from, path.join(THEME, file));
      console.log(`  복사: ${file}`);
    }
  });
}

function convertPaths(html) {
  let out = html;
  out = out.replace(/src="img\//g, 'src="<?php echo G5_THEME_URL; ?>/img/');
  out = out.replace(/src='img\//g, "src='<?php echo G5_THEME_URL; ?>/img/");
  out = out.replace(/url\(img\//g, 'url(<?php echo G5_THEME_URL; ?>/img/');
  out = out.replace(/url\('img\//g, "url('<?php echo G5_THEME_URL; ?>/img/");
  out = out.replace(/href="favicon\.ico"/g, 'href="<?php echo G5_THEME_URL; ?>/favicon.ico"');

  for (const [file, php] of Object.entries(HREF_MAP)) {
    const re = new RegExp(`href="${file.replace('.', '\\.')}"`, 'g');
    out = out.replace(re, `href="${php}"`);
  }

  for (const [label, php] of Object.entries(BOARD_HREF)) {
    out = out.replace(
      new RegExp(`<a href="">(${label})</a>`, 'g'),
      `<a href="${php}">$1</a>`
    );
  }

  return out;
}

function extractMain(html) {
  const start = html.indexOf('<main id="sh_container">');
  const end = html.indexOf('</main>', start);
  if (start === -1 || end === -1) return '';
  return html.slice(start, end + 7);
}

function wrapPhp(body, comment) {
  return `<?php
/**
 * ${comment}
 * apps/web 에서 자동 생성 — 직접 수정 시 build 스크립트 재실행 시 덮어씌워집니다.
 */
if (!defined('_GNUBOARD_')) exit;
?>\n${body}`;
}

function build() {
  console.log('TBC 그누보드 테마 빌드 시작...');
  try {
    if (process.env.SKIP_ASSETS === '1') {
      console.log('  에셋 복사 건너뜀 (SKIP_ASSETS=1)');
    } else {
      syncAssets();
    }

    fs.mkdirSync(PAGES, { recursive: true });

    const indexHtml = fs.readFileSync(path.join(WEB, 'index.html'), 'utf8');
    fs.writeFileSync(
      path.join(THEME, 'index.body.php'),
      wrapPhp(convertPaths(extractMain(indexHtml)), '메인 페이지 본문')
    );
    console.log('  생성: index.body.php');

    for (const [htmlFile, pageId] of Object.entries(PAGE_MAP)) {
      const filePath = path.join(WEB, htmlFile);
      if (!fs.existsSync(filePath)) continue;
      const html = fs.readFileSync(filePath, 'utf8');
      fs.writeFileSync(
        path.join(PAGES, `${pageId}.php`),
        wrapPhp(convertPaths(extractMain(html)), `${htmlFile} → ${pageId}`)
      );
      console.log(`  생성: pages/${pageId}.php`);
    }

    console.log('테마 빌드 완료.');
  } catch (err) {
    console.error('빌드 실패:', err.message);
    process.exit(1);
  }
}

build();
