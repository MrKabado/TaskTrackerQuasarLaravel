# Quasar App (frontend)

## Install the dependencies

```bash
pnpm install
# or: yarn/npm/bun install
```

### Start the app in development mode (HMR, error reporting, etc.)

```bash
quasar dev
```

### Format & Lint the files

```bash
pnpm run lint
# or: yarn/npm/bun run lint
```

...or just check formatting & linting:

```bash
pnpm run lint:check
# or: yarn/npm/bun run lint:check
```

### Build the app for production

```bash
quasar build
```

### Build the Android app against a local Laravel backend

The Android WebView cannot use the computer's `localhost` as the backend host. Create
`frontend/.env.local` with the computer's LAN IP address (the phone and computer must be on the
same Wi-Fi):

```env
VITE_API_BASE_URL=http://192.168.1.50:8000/api
```

Replace the example IP with the computer's current LAN address. Start Laravel from `backend` so it
listens on the network:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Allow inbound TCP port 8000 through the computer's firewall if prompted. Then build and sync the
Android project:

```bash
quasar build -m capacitor -T android
```

From `frontend/src-capacitor/android`, build the debug APK with
`.\gradlew.bat assembleDebug` on Windows. The current Android manifest allows cleartext traffic for
local HTTP testing; use an HTTPS API and remove that allowance before distributing a production app.

### Customize the configuration

See [Configuring quasar.config.js](https://v2.quasar.dev/quasar-cli-vite/quasar-config-file).
