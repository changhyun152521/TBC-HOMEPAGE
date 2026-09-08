const fs = require('fs');
const path = require('path');
const sharp = require('sharp');

const SRC = path.join('C:', 'Users', '이창현', 'Desktop', '새 폴더 (3)', '파비콘.png');
const ROOT = path.resolve(__dirname, '..');

const OUTPUT_DIRS = [
  path.join(ROOT, 'gnuboard', 'theme-package', 'tbc'),
  path.join(ROOT, 'apps', 'web'),
];

async function removeBlackBackground(inputPath) {
  const { data, info } = await sharp(inputPath)
    .ensureAlpha()
    .raw()
    .toBuffer({ resolveWithObject: true });

  const pixels = data;
  const threshold = 30;

  for (let i = 0; i < pixels.length; i += 4) {
    const r = pixels[i];
    const g = pixels[i + 1];
    const b = pixels[i + 2];
    if (r <= threshold && g <= threshold && b <= threshold) {
      pixels[i + 3] = 0;
    }
  }

  return sharp(pixels, {
    raw: { width: info.width, height: info.height, channels: 4 },
  }).png();
}

async function resizePng(pipeline, size) {
  return pipeline
    .clone()
    .resize(size, size, { fit: 'contain', background: { r: 0, g: 0, b: 0, alpha: 0 } })
    .png()
    .toBuffer();
}

async function writeFile(filePath, buffer) {
  fs.mkdirSync(path.dirname(filePath), { recursive: true });
  fs.writeFileSync(filePath, buffer);
  console.log('wrote', path.relative(ROOT, filePath));
}

async function main() {
  if (!fs.existsSync(SRC)) {
    throw new Error(`Source not found: ${SRC}`);
  }

  const base = await removeBlackBackground(SRC);
  const sizes = [
    { name: 'favicon-32.png', size: 32 },
    { name: 'favicon-48.png', size: 48 },
    { name: 'favicon-192.png', size: 192 },
    { name: 'apple-touch-icon.png', size: 180 },
    { name: 'favicon.png', size: 192 },
  ];

  const buffers = {};
  for (const { name, size } of sizes) {
    buffers[name] = await resizePng(base, size);
  }

  const favicon32 = buffers['favicon-32.png'];
  const { default: pngToIco } = await import('png-to-ico');
  const faviconIco = await pngToIco([favicon32]);

  for (const dir of OUTPUT_DIRS) {
    await writeFile(path.join(dir, 'favicon.ico'), faviconIco);
    await writeFile(path.join(dir, 'favicon.png'), buffers['favicon.png']);

    for (const { name } of sizes) {
      await writeFile(path.join(dir, 'img', 'open', name), buffers[name]);
    }

    await writeFile(path.join(dir, 'img', 'open', 'favicon.ico'), faviconIco);
  }

  console.log('Favicon generation complete.');
}

main().catch((err) => {
  console.error(err);
  process.exit(1);
});
