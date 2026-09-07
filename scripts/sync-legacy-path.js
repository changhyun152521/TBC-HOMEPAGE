const fs = require('fs');
const path = require('path');

const src = path.join(__dirname, '..', 'apps', 'web');
const dest = path.join(__dirname, '..', 'html예시파일');

function copyDir(from, to) {
  fs.mkdirSync(to, { recursive: true });
  for (const entry of fs.readdirSync(from, { withFileTypes: true })) {
    const srcPath = path.join(from, entry.name);
    const destPath = path.join(to, entry.name);
    if (entry.isDirectory()) {
      copyDir(srcPath, destPath);
    } else {
      fs.copyFileSync(srcPath, destPath);
    }
  }
}

if (!fs.existsSync(src)) {
  console.error('Source not found:', src);
  process.exit(1);
}

if (fs.existsSync(dest)) {
  fs.rmSync(dest, { recursive: true, force: true });
}

copyDir(src, dest);
console.log('Synced apps/web -> html예시파일');
