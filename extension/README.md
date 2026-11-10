# Task Tracker for Chrome

The extension uses Vue 3, TypeScript, Vite, Tailwind CSS, Pinia, and Axios. Its Manifest V3 side panel supports signing in, viewing and filtering tasks, creating tasks, updating task statuses, deleting tasks, and signing out.

## Configure and build

The default API URL is `http://localhost:8000/api`. Set `VITE_API_BASE_URL` in an `extension/.env` file to your backend's API URL when using another server. The URL's origin must also be listed in `public/manifest.json` under `host_permissions`.

```powershell
cd extension
npm install
npm run build
```

The production extension is written to `extension/dist`. The build includes `manifest.json` and the `background.js` service worker.

## Load in Chrome

1. Open `chrome://extensions` and enable **Developer mode**.
2. Select **Load unpacked** and choose `extension/dist`.
3. Click the Task Tracker toolbar button to open the side panel.
4. Sign in with the same account used by the Task Tracker website.

To test against a deployed backend, update the API URL and the matching `host_permissions`, then rebuild and reload the unpacked extension. The backend must allow cross-origin requests from the extension.

## Publish the website integration link

Set `VITE_CHROME_EXTENSION_URL` in the main frontend's environment to the published Chrome Web Store listing URL. Until that is available, the Integrations page shows local installation steps.
