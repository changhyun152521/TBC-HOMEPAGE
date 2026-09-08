/**
 * 카페24 서버 gnuboard5 → 로컬 gnuboard/production 동기화
 * 사용: .env.cafe24 설정 후  npm run gnuboard:pull
 */
const fs = require('fs');
const path = require('path');
const { Client } = require('basic-ftp');

const ROOT = path.join(__dirname, '..');
const ENV_FILE = path.join(ROOT, '.env.cafe24');
const LOCAL_DIR = path.join(ROOT, 'gnuboard', 'production');

const SKIP_DIRS = new Set([
  'cache',
  'session',
  'tmp',
  'backup',
  'log',
  'captcha',
]);

const SKIP_PATH_PREFIXES = [
  'data/file/',
  'data/editor/',
  'data/member_image/',
  'data/content/',
];

const SKIP_FILES = new Set([
  'data/dbconfig.php',
]);

function loadEnv(filePath) {
  if (!fs.existsSync(filePath)) {
    console.error('`.env.cafe24` 파일이 없습니다.');
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

function shouldSkip(remotePath) {
  const normalized = remotePath.replace(/^\/+/, '').replace(/\\/g, '/');
  const parts = normalized.split('/');

  if (parts.length >= 2 && parts[0] === 'data' && SKIP_DIRS.has(parts[1])) {
    return true;
  }

  for (const prefix of SKIP_PATH_PREFIXES) {
    if (normalized.startsWith(prefix)) {
      return true;
    }
  }

  if (SKIP_FILES.has(normalized)) {
    return true;
  }

  return false;
}

async function downloadDir(client, remoteDir, localDir, baseRemote) {
  await fs.promises.mkdir(localDir, { recursive: true });
  const list = await client.list(remoteDir);

  for (const item of list) {
    const remotePath = `${remoteDir}/${item.name}`.replace(/\\/g, '/');
    const relative = remotePath.replace(baseRemote, '').replace(/^\/+/, '');
    const localPath = path.join(localDir, item.name);

    if (shouldSkip(relative)) {
      process.stdout.write(`건너뜀: ${relative}\n`);
      continue;
    }

    if (item.isDirectory) {
      await downloadDir(client, remotePath, localPath, baseRemote);
      continue;
    }

    process.stdout.write(`다운로드: ${relative}\n`);
    await client.downloadTo(localPath, remotePath);
  }
}

async function writeDbConfigExample() {
  const examplePath = path.join(LOCAL_DIR, 'data', 'dbconfig.example.php');
  const content = `<?php
/**
 * 서버의 data/dbconfig.php 예시 템플릿입니다.
 * 실제 비밀번호는 git에 올리지 않습니다. 로컬/서버에서 직접 작성하세요.
 */
if (!defined('_GNUBOARD_')) exit;

define('G5_MYSQL_HOST', 'localhost');
define('G5_MYSQL_USER', 'your_db_user');
define('G5_MYSQL_PASSWORD', 'your_db_password');
define('G5_MYSQL_DB', 'your_db_name');
define('G5_MYSQL_SET_MODE', true);
`;
  await fs.promises.mkdir(path.dirname(examplePath), { recursive: true });
  await fs.promises.writeFile(examplePath, content, 'utf8');
}

async function main() {
  const env = loadEnv(ENV_FILE);
  const host = env.REMOTE_HOST;
  const user = env.REMOTE_USER;
  const password = env.REMOTE_PASSWORD;
  const baseDir = (env.REMOTE_BASE_DIR || '/www').replace(/\/$/, '');
  const remoteGnuboard = `${baseDir}/gnuboard5`;

  if (!host || !user || !password) {
    console.error('REMOTE_HOST, REMOTE_USER, REMOTE_PASSWORD 가 필요합니다.');
    process.exit(1);
  }

  const client = new Client(300000);
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
    console.log(`다운로드: ${remoteGnuboard} -> ${LOCAL_DIR}`);

    if (fs.existsSync(LOCAL_DIR)) {
      fs.rmSync(LOCAL_DIR, { recursive: true, force: true });
    }

    await downloadDir(client, remoteGnuboard, LOCAL_DIR, `${remoteGnuboard}/`);
    await writeDbConfigExample();
    console.log('완료.');
  } finally {
    client.close();
  }
}

main().catch((err) => {
  console.error(err.message || err);
  process.exit(1);
});
