import { readdir, readFile, mkdir, writeFile } from 'node:fs/promises';
import { resolve, relative, join } from 'node:path';
import { fileURLToPath } from 'node:url';
import { zipSync } from 'fflate';
import { loadEnv } from 'vite';

const extensionRoot = resolve(fileURLToPath(new URL('..', import.meta.url)));
const distDirectory = join(extensionRoot, 'dist');
const downloadDirectory = resolve(extensionRoot, '..', 'frontend', 'public', 'downloads');
const archivePath = join(downloadDirectory, 'taskflow-extension.zip');
const files = {};

const apiBaseUrl =
  process.env.VITE_API_BASE_URL || loadEnv('production', extensionRoot, '').VITE_API_BASE_URL;

if (!apiBaseUrl) {
  throw new Error('Set VITE_API_BASE_URL to the production API URL before packaging the extension.');
}

let apiUrl;
try {
  apiUrl = new URL(apiBaseUrl);
} catch {
  throw new Error('VITE_API_BASE_URL must be an absolute URL ending in /api.');
}

if (
  !apiUrl.pathname.replace(/\/+$/, '').endsWith('/api') ||
  (apiUrl.protocol !== 'https:' && !['localhost', '127.0.0.1'].includes(apiUrl.hostname))
) {
  throw new Error('Use an HTTPS API URL ending in /api for a production extension build.');
}

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

const manifestPath = join(distDirectory, 'manifest.json');
const manifest = JSON.parse(await readFile(manifestPath, 'utf8'));
manifest.host_permissions = [`${apiUrl.origin}/*`];
await writeFile(manifestPath, `${JSON.stringify(manifest, null, 2)}\n`);
files['manifest.json'] = new Uint8Array(await readFile(manifestPath));

await mkdir(downloadDirectory, { recursive: true });
await writeFile(archivePath, zipSync(files, { level: 6 }));
console.log(`Created ${archivePath}`);
