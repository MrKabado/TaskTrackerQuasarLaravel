# TaskFlow for Chrome

The extension uses Vue 3, TypeScript, Vite, Tailwind CSS, Pinia, and Axios. Its Manifest V3 side panel supports signing in, viewing and filtering tasks, creating tasks, updating task statuses, deleting tasks, and signing out.

## Configure and build a production release

Set `VITE_API_BASE_URL` to the public HTTPS API URL. It must end in `/api`. The package script writes that API origin into the built manifest's `host_permissions`, keeping the downloaded release limited to the production API host.

```powershell
cd extension
npm install
$env:VITE_API_BASE_URL = "https://YOUR-RENDER-SERVICE.onrender.com/api"
npm run package
```

The production extension is written to `extension/dist`. The package command also creates `frontend/public/downloads/taskflow-extension.zip` for the Integrations page's download button. Run it before building or deploying the frontend so the ZIP is included in the website.

The ZIP is a downloadable release asset and must be included in the repository/deployment. After changing the extension, run `npm run package` with the production API URL and include the updated ZIP with the frontend release. Do not publish a package built with the default localhost API.

## Load in Chrome

1. Open `chrome://extensions` and enable **Developer mode**.
2. Select **Load unpacked** and choose the extracted folder containing `manifest.json`.
3. Click the TaskFlow toolbar button to open the side panel.
4. Sign in with the same account used by the TaskFlow website.

For local development, use `npm run dev` with the default `http://localhost:8000/api`. Configure the backend's CORS allowlist for the deployed website origin. The production package has host access only to the API origin configured during packaging.

The website's Integrations page currently downloads the packaged ZIP for manual installation. For a public production release, publish the package through the Chrome Web Store and link the Install button to the store listing; the ZIP workflow requires users to enable Developer mode and load it unpacked.
