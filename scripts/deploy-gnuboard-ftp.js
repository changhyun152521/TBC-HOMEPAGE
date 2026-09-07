/**
 * gnuboard/theme-package → 카페24 FTP 동기화
 * 사용: .env.cafe24 설정 후  npm run gnuboard:deploy
 */
const fs = require('fs');
const path = require('path');
const { Client } = require('basic-ftp');

const ROOT = path.join(__dirname, '..');
const ENV_FILE = path.join(ROOT, '.env.cafe24');

function loadEnv(filePath) {
  if (!fs.existsSync(filePath)) {
    console.error('`.env.cafe24` 파일이 없습니다.');
    console.error('`.env.cafe24.example` 을 복사한 뒤 FTP 비밀번호를 입력하세요.');
    process.exit(1);
  }
  const env = {};
  for (const line of fs.readFileSync(filePath, 'utf8').split(/\r?\n/)) {
    const trimmed = line.trim();
    if (!trimmed || trimmed.startsWith('#')) continue;
    const idx = trimmed.indexOf('=');
    if (idx === -1) continue;
    env[trimmed.slice(0, idx).trim()] = trimmed.slice(idx + 1).trim();
  }
  return env;
}

async function uploadDir(client, localDir, remoteDir) {
  await client.ensureDir(remoteDir);
  for (const name of fs.readdirSync(localDir)) {
    const localPath = path.join(localDir, name);
    const remotePath = `${remoteDir}/${name}`.replace(/\\/g, '/');
    if (fs.statSync(localPath).isDirectory()) {
      await uploadDir(client, localPath, remotePath);
    } else {
      process.stdout.write(`업로드: ${remotePath}\n`);
      await client.uploadFrom(localPath, remotePath);
    }
  }
}

async function main() {
  const env = loadEnv(ENV_FILE);
  const host = env.REMOTE_HOST;
  const user = env.REMOTE_USER;
  const password = env.REMOTE_PASSWORD;
  const baseDir = (env.REMOTE_BASE_DIR || '/www').replace(/\/$/, '');

  if (!host || !user || !password) {
    console.error('REMOTE_HOST, REMOTE_USER, REMOTE_PASSWORD 가 필요합니다.');
    process.exit(1);
  }

  const themeLocal = path.join(ROOT, 'gnuboard', 'theme-package', 'tbc');
  const themeRemote = `${baseDir}/gnuboard5/theme/tbc`;
  const pageLocal = path.join(ROOT, 'gnuboard', 'theme-package', 'bbs', 'page.php');
  const pageRemote = `${baseDir}/gnuboard5/bbs/page.php`;
  const mediaLocal = path.join(ROOT, 'gnuboard', 'theme-package', 'bbs', 'tbc_media.php');
  const mediaRemote = `${baseDir}/gnuboard5/bbs/tbc_media.php`;
  const extendLocal = path.join(ROOT, 'gnuboard', 'theme-package', 'extend');
  const extendRemote = `${baseDir}/gnuboard5/extend`;
  const admLocal = path.join(ROOT, 'gnuboard', 'theme-package', 'adm');
  const admRemote = `${baseDir}/gnuboard5/adm`;
  const installLocal = path.join(ROOT, 'gnuboard', 'theme-package', 'install-tbc-main.php');
  const installRemote = `${baseDir}/gnuboard5/install-tbc-main.php`;
  const installGreetingLocal = path.join(ROOT, 'gnuboard', 'theme-package', 'install-tbc-greeting.php');
  const installGreetingRemote = `${baseDir}/gnuboard5/install-tbc-greeting.php`;
  const installHistoryLocal = path.join(ROOT, 'gnuboard', 'theme-package', 'install-tbc-history.php');
  const installHistoryRemote = `${baseDir}/gnuboard5/install-tbc-history.php`;
  const installTeachersLocal = path.join(ROOT, 'gnuboard', 'theme-package', 'install-tbc-teachers.php');
  const installTeachersRemote = `${baseDir}/gnuboard5/install-tbc-teachers.php`;
  const installAcademiesLocal = path.join(ROOT, 'gnuboard', 'theme-package', 'install-tbc-academies.php');
  const installAcademiesRemote = `${baseDir}/gnuboard5/install-tbc-academies.php`;
  const installBandsLocal = path.join(ROOT, 'gnuboard', 'theme-package', 'install-tbc-bands.php');
  const installBandsRemote = `${baseDir}/gnuboard5/install-tbc-bands.php`;
    const installScheduleLocal = path.join(ROOT, 'gnuboard', 'theme-package', 'install-tbc-schedule.php');
    const installScheduleRemote = `${baseDir}/gnuboard5/install-tbc-schedule.php`;
    const installMenuLocal = path.join(ROOT, 'gnuboard', 'theme-package', 'install-tbc-menu.php');
    const installMenuRemote = `${baseDir}/gnuboard5/install-tbc-menu.php`;
    const installNoticeLocal = path.join(ROOT, 'gnuboard', 'theme-package', 'install-tbc-notice.php');
    const installNoticeRemote = `${baseDir}/gnuboard5/install-tbc-notice.php`;
    const installEduLocal = path.join(ROOT, 'gnuboard', 'theme-package', 'install-tbc-edu.php');
    const installEduRemote = `${baseDir}/gnuboard5/install-tbc-edu.php`;
    const installAdmissionLocal = path.join(ROOT, 'gnuboard', 'theme-package', 'install-tbc-admission.php');
    const installAdmissionRemote = `${baseDir}/gnuboard5/install-tbc-admission.php`;
    const installConsultLocal = path.join(ROOT, 'gnuboard', 'theme-package', 'install-tbc-consult.php');
    const installConsultRemote = `${baseDir}/gnuboard5/install-tbc-consult.php`;
  const upgradeLocal = path.join(ROOT, 'gnuboard', 'theme-package', 'upgrade-tbc-main.php');
  const upgradeRemote = `${baseDir}/gnuboard5/upgrade-tbc-main.php`;

  const client = new Client(120000);
  client.ftp.verbose = false;

  try {
    await client.access({
      host,
      user,
      password,
      port: Number(env.FTP_PORT || 21),
      secure: false,
    });
    client.ftp.ipFamily = 4;

    console.log(`연결됨: ${host}`);
    console.log(`테마 업로드: ${themeLocal} -> ${themeRemote}`);
    await uploadDir(client, themeLocal, themeRemote);

    console.log(`page.php 업로드 -> ${pageRemote}`);
    await client.uploadFrom(pageLocal, pageRemote);

    console.log(`tbc_media.php 업로드 -> ${mediaRemote}`);
    await client.uploadFrom(mediaLocal, mediaRemote);

    if (fs.existsSync(extendLocal)) {
      console.log(`extend 업로드: ${extendLocal} -> ${extendRemote}`);
      await uploadDir(client, extendLocal, extendRemote);
    }

    if (fs.existsSync(admLocal)) {
      console.log(`adm(TBC) 업로드: ${admLocal} -> ${admRemote}`);
      await uploadDir(client, admLocal, admRemote);
    }

    if (fs.existsSync(upgradeLocal)) {
      console.log(`upgrade-tbc-main.php 업로드 -> ${upgradeRemote}`);
      await client.uploadFrom(upgradeLocal, upgradeRemote);
    }

    if (fs.existsSync(installGreetingLocal)) {
      console.log(`install-tbc-greeting.php 업로드 -> ${installGreetingRemote}`);
      await client.uploadFrom(installGreetingLocal, installGreetingRemote);
    }

    if (fs.existsSync(installHistoryLocal)) {
      console.log(`install-tbc-history.php 업로드 -> ${installHistoryRemote}`);
      await client.uploadFrom(installHistoryLocal, installHistoryRemote);
    }

    if (fs.existsSync(installTeachersLocal)) {
      console.log(`install-tbc-teachers.php 업로드 -> ${installTeachersRemote}`);
      await client.uploadFrom(installTeachersLocal, installTeachersRemote);
    }

    if (fs.existsSync(installAcademiesLocal)) {
      console.log(`install-tbc-academies.php 업로드 -> ${installAcademiesRemote}`);
      await client.uploadFrom(installAcademiesLocal, installAcademiesRemote);
    }

    if (fs.existsSync(installBandsLocal)) {
      console.log(`install-tbc-bands.php 업로드 -> ${installBandsRemote}`);
      await client.uploadFrom(installBandsLocal, installBandsRemote);
    }

    if (fs.existsSync(installScheduleLocal)) {
      console.log(`install-tbc-schedule.php 업로드 -> ${installScheduleRemote}`);
      await client.uploadFrom(installScheduleLocal, installScheduleRemote);
    }

    if (fs.existsSync(installMenuLocal)) {
      console.log(`install-tbc-menu.php 업로드 -> ${installMenuRemote}`);
      await client.uploadFrom(installMenuLocal, installMenuRemote);
    }

    if (fs.existsSync(installNoticeLocal)) {
      console.log(`install-tbc-notice.php 업로드 -> ${installNoticeRemote}`);
      await client.uploadFrom(installNoticeLocal, installNoticeRemote);
    }

    if (fs.existsSync(installEduLocal)) {
      console.log(`install-tbc-edu.php 업로드 -> ${installEduRemote}`);
      await client.uploadFrom(installEduLocal, installEduRemote);
    }

    if (fs.existsSync(installAdmissionLocal)) {
      console.log(`install-tbc-admission.php 업로드 -> ${installAdmissionRemote}`);
      await client.uploadFrom(installAdmissionLocal, installAdmissionRemote);
    }

    if (fs.existsSync(installConsultLocal)) {
      console.log(`install-tbc-consult.php 업로드 -> ${installConsultRemote}`);
      await client.uploadFrom(installConsultLocal, installConsultRemote);
    }

    console.log('완료.');
  } finally {
    client.close();
  }
}

main().catch((err) => {
  console.error(err.message || err);
  process.exit(1);
});
