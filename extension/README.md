# TaskFlow for Chrome

The extension uses Vue 3, TypeScript, Vite, Tailwind CSS, Pinia, and Axios. Its Manifest V3 side panel supports signing in, viewing and filtering tasks, creating tasks, updating task statuses, deleting tasks, and signing out.

## Configure and build

The default API URL is `http://localhost:8000/api`. Set `VITE_API_BASE_URL` in an `extension/.env` file to your backend's API URL when using another server. The URL's origin must also be listed in `public/manifest.json` under `host_permissions`.

```powershell
cd extension
npm install
npm run package
```

The production extension is written to `extension/dist`. The package command also creates `frontend/public/downloads/taskflow-extension.zip` for the Integrations page's download button. Run it before building or deploying the frontend so the ZIP is included in the website.

The ZIP is a downloadable release asset and must be included in the repository/deployment. After changing the extension, run `npm run package` and include the updated ZIP with the frontend release.

## Load in Chrome

1. Open `chrome://extensions` and enable **Developer mode**.
2. Select **Load unpacked** and choose the extracted folder containing `manifest.json`.
3. Click the TaskFlow toolbar button to open the side panel.
4. Sign in with the same account used by the TaskFlow website.

To test against a deployed backend, update the API URL and the matching `host_permissions`, then rebuild and reload the unpacked extension. The backend must allow cross-origin requests from the extension.

The website's Integrations page downloads the packaged ZIP. Extract that ZIP before selecting **Load unpacked** in Chrome.
