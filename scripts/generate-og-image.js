const fs = require('fs');
const path = require('path');
const sharp = require('sharp');

const SRC = path.join('C:', 'Users', '이창현', 'Desktop', '새 폴더 (3)', '오픈그래픽.jpg');
const ROOT = path.resolve(__dirname, '..');
const WIDTH = 1280;
const HEIGHT = 720;

const OUTPUT_DIRS = [
  path.join(ROOT, 'gnuboard', 'theme-package', 'tbc', 'img', 'open'),
  path.join(ROOT, 'apps', 'web', 'img', 'open'),
];

async function writeFile(filePath, buffer) {
  fs.mkdirSync(path.dirname(filePath), { recursive: true });
  fs.writeFileSync(filePath, buffer);
  console.log('wrote', path.relative(ROOT, filePath), `(${buffer.length} bytes)`);
}

async function main() {
  if (!fs.existsSync(SRC)) {
    throw new Error(`Source not found: ${SRC}`);
  }

  const pipeline = sharp(SRC).resize(WIDTH, HEIGHT, {
    fit: 'cover',
    position: 'centre',
  });

  const png = await pipeline.clone().png({ compressionLevel: 9 }).toBuffer();
  const jpg = await pipeline.clone().jpeg({ quality: 88, mozjpeg: true }).toBuffer();

  for (const dir of OUTPUT_DIRS) {
    await writeFile(path.join(dir, 'open.png'), png);
    await writeFile(path.join(dir, 'open.jpg'), jpg);
    await writeFile(path.join(dir, 'og.png'), png);
  }

  const meta = await sharp(png).metadata();
  console.log(`size: ${meta.width}x${meta.height}`);
  console.log('OG image generation complete.');
}

main().catch((err) => {
  console.error(err);
  process.exit(1);
});
