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
  const preserveCss = new Set(['tbc_custom.css']);
  ['css', 'js', 'img'].forEach((dir) => {
    const from = path.join(WEB, dir);
    const to = path.join(THEME, dir);
    if (!fs.existsSync(from)) return;
    if (dir === 'css' && isWin) {
      for (const name of fs.readdirSync(from)) {
        if (preserveCss.has(name)) continue;
        const src = path.join(from, name);
        const dest = path.join(to, name);
        if (fs.statSync(src).isDirectory()) {
          try {
            execSync(`robocopy "${src}" "${dest}" /E /NFL /NDL /NJH /NJS /nc /ns /np`, { stdio: 'ignore' });
          } catch (e) {
            /* robocopy: exit 1 = files copied */
          }
        } else {
          fs.copyFileSync(src, dest);
        }
      }
      console.log(`  복사: ${dir}/ (tbc_custom.css 유지)`);
      return;
    }
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

function patchMainBannerHero(body) {
  const heroInclude = `                        <div class="left">
                            <?php include_once(G5_THEME_PATH . '/partials/main-banner-hero.php'); ?>
                        </div>`;

  const heroPattern = /<div class="left">[\s\S]*?<\/div>\s*\n\s*<div class="right">/;
  if (heroPattern.test(body)) {
    body = body.replace(heroPattern, `${heroInclude}\n                        <div class="right">`);
  }
  return body;
}

function patchMainBannerRight(body) {
  const rightInclude = `                                <?php include_once(G5_THEME_PATH . '/partials/main-banner-right.php'); ?>`;
  const rightPattern = /<div class="top_box">\s*<div class="top_cont">[\s\S]*?<\/div>\s*<ul>/;
  if (rightPattern.test(body)) {
    return body.replace(rightPattern, `<div class="top_box">\n                                ${rightInclude}\n                                <ul>`);
  }
  return body;
}

function patchMainSection01Ko(body) {
  const sectionInclude = `                            <?php include_once(G5_THEME_PATH . '/partials/main-section01-ko.php'); ?>`;
  const sectionPattern = /<img src="<\?php echo G5_THEME_URL; \?>\/img\/main\/inc01\/img01\.png" alt="메인이미지">\s*<div class="ko_box">[\s\S]*?<\/div>/;
  if (sectionPattern.test(body)) {
    return body.replace(
      sectionPattern,
      `<img src="<?php echo G5_THEME_URL; ?>/img/main/inc01/img01.png" alt="메인이미지">\n                            ${sectionInclude}`
    );
  }
  return body;
}

function patchMainTeachersGallery(body) {
  const include = `                                <?php include_once(G5_THEME_PATH . '/partials/main-teachers-gallery.php'); ?>`;
  const pattern = /<div class="gall_box">[\s\S]*?<\/div>\s*\n\s*<\/div>\s*\n\s*<div class="bot_box">/;
  if (pattern.test(body)) {
    return body.replace(pattern, `${include}\n                            </div>\n                            <div class="bot_box">`);
  }
  return body;
}

function patchMainNoticeSlider(body) {
  const sliderInclude = `                                    <?php include_once(G5_THEME_PATH . '/partials/main-notice-slider.php'); ?>`;
  const sliderPattern = /<ul class="swiper-wrapper">[\s\S]*?<\/ul>\s*<div class="index_btm_pager">/;
  if (sliderPattern.test(body)) {
    body = body.replace(sliderPattern, `${sliderInclude}\n                                    <div class="index_btm_pager">`);
  }

  const morePattern = /(<div class="notice_box">[\s\S]*?)<a href="[^"]*" class="more">더보기/;
  if (morePattern.test(body)) {
    body = body.replace(
      morePattern,
      `$1<a href="<?php echo tbc_board_url('notice'); ?>" class="more">더보기`
    );
  }

  return body;
}

function patchMainSection03Notices(body) {
  const include = `                                                        <?php include_once(G5_THEME_PATH . '/partials/main-section03-notices.php'); ?>`;
  const tabPattern = /<div id="tab1" class="late_cont">\s*<div class="late">\s*<ul class=" n_lt">[\s\S]*?<\/ul>\s*<\/div>\s*<\/div>/;
  if (tabPattern.test(body)) {
    body = body.replace(
      tabPattern,
      `<div id="tab1" class="late_cont">\n                                                    <div class="late">\n${include}\n                                                    </div>\n                                                </div>`
    );
  }

  const morePattern = /(<article id="atc03">[\s\S]*?<ul class="late_tabs">[\s\S]*?<\/ul>\s*)<a href="[^"]*">더보기/;
  if (morePattern.test(body)) {
    body = body.replace(
      morePattern,
      `$1<a href="<?php echo tbc_board_url('notice'); ?>">더보기`
    );
  }

  return body;
}

function patchMainSection02(body) {
  const leftInclude = `                            <?php include_once(G5_THEME_PATH . '/partials/main-section02-left.php'); ?>`;
  const rightInclude = `                            <?php include_once(G5_THEME_PATH . '/partials/main-section02-right.php'); ?>`;

  const leftPattern = /<img src="<\?php echo G5_THEME_URL; \?>\/img\/main\/inc02\/img01\.png" alt="캐릭터">\s*<h2>[\s\S]*?<\/div>\s*<\/div>\s*<\/div>/;
  if (leftPattern.test(body)) {
    body = body.replace(
      leftPattern,
      `<img src="<?php echo G5_THEME_URL; ?>/img/main/inc02/img01.png" alt="캐릭터">\n                            ${leftInclude}\n                        </div>`
    );
  }

  const rightPattern = /<div class="right" data-aos="fade-left">\s*<div class="top_box">[\s\S]*?<\/div>\s*<\/div>\s*<\/div>\s*<\/article>\s*<!-- inc02 \[e\] -->/;
  if (rightPattern.test(body)) {
    body = body.replace(
      rightPattern,
      `<div class="right" data-aos="fade-left">\n                            ${rightInclude}\n                        </div>\n                    </div>\n                </article>\n                <!-- inc02 [e] -->`
    );
  }

  return body;
}

function patchTeachersList(body, subject) {
  const include = `                        <?php $tbc_teacher_subject = '${subject}'; include_once(G5_THEME_PATH . '/partials/teachers-list.php'); ?>`;
  const pattern = /<ul class="instructor_list">[\s\S]*?<\/div>\s*<\/div>\s*<!-- 서브페이지 \[e\] -->/;
  if (pattern.test(body)) {
    body = body.replace(
      pattern,
      `${include}\n                    </div>\n                </div>\n                <!-- 서브페이지 [e] -->`
    );
  }
  return body;
}

const TEACHER_SUBJECT_MAP = {
  teachers: '',
  teachers_korean: 'korean',
  teachers_math: 'math',
  teachers_science: 'science',
  teachers_english: 'english',
  teachers_social: 'social',
};

function patchGreetingContent(body) {
  const include = `                <?php include_once(G5_THEME_PATH . '/partials/greeting-content.php'); ?>`;
  const pattern = /<div id="greeting" class="pagecommon">[\s\S]*?<\/div>\s*<!-- 서브페이지 \[e\] -->/;
  if (pattern.test(body)) {
    body = body.replace(pattern, `${include}\n                <!-- 서브페이지 [e] -->`);
  }
  return body;
}

function patchHistoryContent(body) {
  const include = `                <?php include_once(G5_THEME_PATH . '/partials/history-content.php'); ?>`;
  const pattern = /<div id="history1008" class="pagecommon[\s\S]*?<\/div>\s*<!-- 서브페이지 \[e\] -->/;
  if (pattern.test(body)) {
    body = body.replace(pattern, `${include}\n                <!-- 서브페이지 [e] -->`);
  }
  return body;
}

const SCHEDULE_PAGE_CONFIG = {
  schedule: {
    title: '전체 시간표',
    desc: '전체 강좌 목록을 확인하고, 관·학년·과목 필터로 원하는 강좌를 찾을 수 있습니다.',
    group: '',
  },
  schedule_main: {
    title: '본원 시간표',
    desc: '본원 강좌 전체를 확인하고, 관·학년·과목 필터로 원하는 강좌를 찾을 수 있습니다.',
    group: 'main',
  },
  schedule_branch: {
    title: '분원 시간표',
    desc: '분원 강좌 전체를 확인하고, 관·학년·과목 필터로 원하는 강좌를 찾을 수 있습니다.',
    group: 'branch',
  },
};

function patchScheduleContent(body, pageId) {
  const config = SCHEDULE_PAGE_CONFIG[pageId];
  if (!config) return body;

  const groupLine = config.group
    ? `\n                $tbc_schedule_group = '${config.group}';`
    : '';

  const include = `                <?php
                $tbc_schedule_title = '${config.title}';
                $tbc_schedule_desc = '${config.desc}';${groupLine}
                include_once(G5_THEME_PATH . '/partials/schedule-content.php');
                ?>`;
  const pattern = /<div id="schedule1001"[\s\S]*?<\/div>\s*<!-- 서브페이지 \[e\] -->/;
  if (pattern.test(body)) {
    body = body.replace(pattern, `${include}\n                <!-- 서브페이지 [e] -->`);
  }
  return body;
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
    let indexBody = patchMainBannerHero(convertPaths(extractMain(indexHtml)));
    indexBody = patchMainBannerRight(indexBody);
    indexBody = patchMainSection01Ko(indexBody);
    indexBody = patchMainSection02(indexBody);
    indexBody = patchMainNoticeSlider(indexBody);
    indexBody = patchMainSection03Notices(indexBody);
    indexBody = patchMainTeachersGallery(indexBody);
    fs.writeFileSync(
      path.join(THEME, 'index.body.php'),
      wrapPhp(indexBody, '메인 페이지 본문')
    );
    console.log('  생성: index.body.php');

    for (const [htmlFile, pageId] of Object.entries(PAGE_MAP)) {
      const filePath = path.join(WEB, htmlFile);
      if (!fs.existsSync(filePath)) continue;
      const html = fs.readFileSync(filePath, 'utf8');
      let pageBody = convertPaths(extractMain(html));
      if (pageId === 'greeting') {
        pageBody = patchGreetingContent(pageBody);
      }
      if (pageId === 'history') {
        pageBody = patchHistoryContent(pageBody);
      }
      if (Object.prototype.hasOwnProperty.call(SCHEDULE_PAGE_CONFIG, pageId)) {
        pageBody = patchScheduleContent(pageBody, pageId);
      }
      if (Object.prototype.hasOwnProperty.call(TEACHER_SUBJECT_MAP, pageId)) {
        pageBody = patchTeachersList(pageBody, TEACHER_SUBJECT_MAP[pageId]);
      }
      fs.writeFileSync(
        path.join(PAGES, `${pageId}.php`),
        wrapPhp(pageBody, `${htmlFile} → ${pageId}`)
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
