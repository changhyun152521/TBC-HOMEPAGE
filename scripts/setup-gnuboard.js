/**
 * 그누보드5 다운로드 + TBC 테마 설치 + Docker 안내
 */
const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const ROOT = path.join(__dirname, '..');
const RUNTIME = path.join(ROOT, 'gnuboard', 'runtime');
const THEME_PKG = path.join(ROOT, 'gnuboard', 'theme-package', 'tbc');
const GNUBOARD_REPO = 'https://github.com/gnuboard/gnuboard5.git';

function run(cmd, opts = {}) {
  console.log(`> ${cmd}`);
  execSync(cmd, { stdio: 'inherit', ...opts });
}

function copyDir(from, to) {
  fs.mkdirSync(to, { recursive: true });
  for (const entry of fs.readdirSync(from, { withFileTypes: true })) {
    const src = path.join(from, entry.name);
    const dest = path.join(to, entry.name);
    if (entry.isDirectory()) copyDir(src, dest);
    else fs.copyFileSync(src, dest);
  }
}

function main() {
  console.log('\n=== TBC 그누보드 로컬 환경 설정 ===\n');

  run('node scripts/build-gnuboard-theme.js', { cwd: ROOT });

  if (!fs.existsSync(RUNTIME)) {
    console.log('\n그누보드5 다운로드 중...');
    run(`git clone --depth 1 ${GNUBOARD_REPO} "${RUNTIME}"`);
  } else {
    console.log('\n그누보드 runtime 폴더가 이미 있습니다. (건너뜀)');
  }

  const themeDest = path.join(RUNTIME, 'theme', 'tbc');
  fs.rmSync(themeDest, { recursive: true, force: true });
  copyDir(THEME_PKG, themeDest);
  console.log('테마 복사: theme/tbc');

  const pageRouter = path.join(ROOT, 'gnuboard', 'theme-package', 'bbs', 'page.php');
  fs.copyFileSync(pageRouter, path.join(RUNTIME, 'bbs', 'page.php'));
  console.log('페이지 라우터 복사: bbs/page.php');

  const dataDir = path.join(RUNTIME, 'data');
  fs.mkdirSync(dataDir, { recursive: true });

  console.log(`
설정 완료!

다음 단계:
  1. Docker Desktop 실행
  2. npm run gnuboard:start
  3. http://localhost:8080/install/ — DB: host=db, user/pass/db=gnuboard
  4. 관리자 → 테마 tbc 선택
  5. docs/GNUBOARD_GUIDE.md 참고

phpMyAdmin: http://localhost:8081
`);
}

main();
