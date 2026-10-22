# Dashboard V14 — Modern & Minimal UI
## CMS Company Profile · PT Nihon Plast Indonesia

React frontend implementation with Tailwind CSS, Lucide React, Recharts and Plus Jakarta Sans. This is a functional local-data CMS prototype, not a deployed or security-audited production backend.

## Run
Use Node.js 20.19+ or 22.12+ and npm.

```sh
npm install
npm run dev
```

```sh
npm test
npm run build
npm run preview
```

Open the local address printed by Vite. Serve the built `dist` directory over HTTP, not file://. The relative Vite base supports subfolder deployment. Hash routes preserve the requested PHP destination names without requiring PHP or server rewrites.

## Included
- Exactly seven primary navigation entries for superadmin, a 240px desktop sidebar, and only two footer utilities.
- Three KPI cards, integrated Recharts SVG sparklines, 66/34 analytics layout and four featured product pillars.
- Permission-aware grouped global search, keyboard search navigation, notifications, theme persistence, date-range picker and period selection.
- Product edit deep links, segmented module navigation, record creation/editing/deletion with a focus-trapped confirmation modal.
- Local browser persistence, unread RFQ state, demo role switching, clean 403 screens and local sign-out.
- Responsive drawer with keyboard focus containment, Escape dismissal, focus styles and reduced-motion support.
- Mock records for all requested CMS modules and the five supported content languages.

## Try the interactions
1. Search “Steering” and use ArrowDown then Enter to open the editor.
2. Edit the description, save, and refresh. Local changes persist.
3. Open Messages and mark both RFQs as read. The sidebar dot disappears.
4. Open the avatar menu and switch Demo role to staff. Orders, Customers, Analytics and Settings disappear. Directly visiting `#/admin/users.php` displays 403.
5. Switch back to superadmin, visit Products, and use a Delete button. Cancel or confirm in the custom modal.
6. Apply a different date range. The selected range is shared with Messages and the Analytics RFQ count.
7. Toggle dark mode, resize to mobile, open/close the navigation drawer, and use Preview Website.
8. Logout ends only the local demo session; Return to demo starts it again.

## Central configuration
`src/config.js` owns navigation keys, PHP target routes, module definitions, field lists, permissions, current user, supported languages, dashboard fixtures and sample records. Components are in `src/components`. `App.jsx` owns local data mutations and session state. `styles.css` defines the design tokens and responsive rules alongside Tailwind utilities.

## Important fixture semantics
All records, identities, certificates, events and metrics are demonstration data, not verified company records. The requested $24,580 / 1,245 / 892 KPIs, product sales figures and Jan–Jun chart are independent fixed visual fixtures. Product sales do not reconcile to the KPI revenue. They are retained exactly as specified, not presented as an accounting dataset.

The default “This Month” view intentionally shows the requested Jan–Jun series, despite the period label. This Week uses an explicitly synthetic seven-day demo series totaling $24,580. This Quarter aggregates the supplied six months into Q1/Q2; This Year aggregates them into one 2025 total. These controls demonstrate interaction, not live period reporting. The date selector filters dated RFQ records and the Analytics count; it does not recalculate the fixed KPI/chart fixtures. Notifications are seeded demo events anchored to January 2025, not current alerts.

## Public preview
Without configuration, Preview Website opens a small local preview in a new tab, reading saved CMS products. It is not the full ten-page public website. Set `VITE_PUBLIC_SITE_URL` in `.env.local` to your actual public site URL before building. No real public domain is assumed.

## PHP/MySQL integration boundary
The app does not include a PHP API or database. Replace the local read/save/remove operations in App.jsx with your API adapter while preserving module keys and record shapes. The PHP-looking hash paths are frontend routes, not executable PHP endpoints.

Before real deployment implement server-side session authentication, logout invalidation, per-module CRUD authorization, CSRF protection, input validation, prepared SQL statements, upload validation, audit logging and secure transport. The demo role switcher must be removed from production. Browser-side RBAC is only a UI demonstration and is not a security boundary. Never store real RFQ/customer information in this demo localStorage.

Image fields currently accept a path string; there is no upload service. Translation records can be edited but automatic translation is not included. The Page Builder exposes record fields, not a drag-and-drop visual website editor. User records do not provision actual accounts.

## Reset demo data
Clear this site's browser storage, or remove the `npi-cms-v14-demo-1` localStorage key. This restores the initial four featured products after deletion.

## Validation status
All 10 configuration, route, permission and fixture tests passed. The complete JSX import graph also passed an esbuild syntax/bundle check with external dependencies excluded. Dependency installation could not be completed in the authoring sandbox, so a full Vite build, browser rendering and end-to-end tests were not verified here. Run the commands above in your development environment before deployment.

## Visual reference
The Instagram Reel could not be retrieved in the authoring environment and no screenshots were attached. The implementation follows the supplied structural specification and tokens; exact Reel fidelity is not claimed.
