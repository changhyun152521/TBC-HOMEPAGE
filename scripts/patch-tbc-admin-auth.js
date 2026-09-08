const fs = require('fs');
const path = require('path');

const dir = path.join(__dirname, '..', 'gnuboard', 'theme-package', 'adm');

const blockPattern = /if\s*\(\s*!\$is_admin\s*\)\s*\{[\s\S]*?\}\s*\n+/g;

for (const name of fs.readdirSync(dir)) {
  if (!name.startsWith('tbc_') || !name.endsWith('.php')) continue;
  const file = path.join(dir, name);
  let src = fs.readFileSync(file, 'utf8');
  const next = src.replace(blockPattern, '');
  if (next !== src) {
    fs.writeFileSync(file, next);
    console.log('updated', name);
  }
}
