import { readdir, readFile, mkdir, writeFile } from 'node:fs/promises';
import { resolve, relative, join } from 'node:path';
import { fileURLToPath } from 'node:url';
import { zipSync } from 'fflate';

const extensionRoot = resolve(fileURLToPath(new URL('..', import.meta.url)));
const distDirectory = join(extensionRoot, 'dist');
const downloadDirectory = resolve(extensionRoot, '..', 'frontend', 'public', 'downloads');
const archivePath = join(downloadDirectory, 'taskflow-extension.zip');
const files = {};

async function addDirectory(directory) {
  const entries = await readdir(directory, { withFileTypes: true });

  for (const entry of entries) {
    const entryPath = join(directory, entry.name);
    if (entry.isDirectory()) {
      await addDirectory(entryPath);
      continue;
    }

    if (!entry.isFile()) continue;
    const archiveEntryPath = relative(distDirectory, entryPath).replaceAll('\\', '/');
    files[archiveEntryPath] = new Uint8Array(await readFile(entryPath));
  }
}

await addDirectory(distDirectory);

if (!files['manifest.json'] || !files['background.js'] || !files['index.html']) {
  throw new Error('Extension build is incomplete. Build the extension before packaging it.');
}

await mkdir(downloadDirectory, { recursive: true });
await writeFile(archivePath, zipSync(files, { level: 6 }));
console.log(`Created ${archivePath}`);
