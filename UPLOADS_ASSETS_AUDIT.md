# Uploads and Public Assets Audit

Audit date: 2026-07-20  
Scope: `writable/uploads`, `public/assets`, application references, and the current local `websitebinan` database.

No files were deleted during this audit.

## Cleanup execution

Cleanup was executed on 2026-07-20 using a reversible quarantine rather than permanent deletion.

- Quarantine location: `C:\tmp\websitebinan_cleanup_quarantine_20260720`
- Uploads moved: 728 files, 388,872,432 bytes (about 370.9 MB).
- Public assets moved: 2,200 files, 234,378,917 bytes (about 223.5 MB).
- Total moved: 2,928 files, 623,251,349 bytes (about 594.4 MB).
- Remaining upload files not referenced by the local database: 0.
- Missing database references after cleanup: 74, unchanged from before cleanup.

The quarantine preserves the original `writable/uploads/...` and `public/assets/...` relative paths. Its contents can therefore be restored to the project root if a removed candidate is later found to be required.

Post-cleanup checks confirmed HTTP 200 responses for the home page, Bootstrap CSS, Font Awesome CSS, the emergency-hotline image, and the referenced 2024 Citizens Charter PDF. Key PHP files also passed syntax checks.

## Executive summary

- Before cleanup, `writable/uploads` contained about 464 MB of files.
- The 728 upload files (about 371 MB) not referenced by the current local database were moved to quarantine.
- The local database also references 74 files that are missing from the local upload folders. Seventy of these are Career files.
- Before cleanup, `public/assets` contained about 526 MB of files; it now contains about 278 MB.
- About 224 MB of documented public-asset candidates were moved to quarantine.
- `public/assets/websitebinan.sql` was moved out of the public web root into quarantine.

## Important limitation

The upload folders are ignored by Git and can differ between local, staging, and production environments. An upload that is unreferenced in the local database may still be referenced by the production database or retained for recovery.

Do not bulk-delete the upload candidates until the same comparison has been run against a fresh production database snapshot and a backup of `writable/uploads` has been made.

## Upload candidates

The following files are candidates because their filename does not occur in the database column used by their module. Records of every status were included, so archived and inactive records were not intentionally excluded. Zero-byte `index.html` directory-protection files were excluded from the candidate totals and should remain.

| Upload directory | Files on disk | Database references | Unreferenced candidates | Candidate size |
|---|---:|---:|---:|---:|
| `ABOUT` | 22 | 9 | 13 | 6.2 MB |
| `BARANGAY` | 227 | 49 | 178 | 23.9 MB |
| `CAREERS` | 34 | 88 | 16 | 17.7 MB |
| `CITYOFFICIAL` | 116 | 32 | 85 | 101.6 MB |
| `DEPT` | 131 | 43 | 89 | 48.7 MB |
| `FULLDISC` | 41 | 9 | 34 | 10.1 MB |
| `INVEST` | 24 | 5 | 19 | 25.9 MB |
| `MAYOR` | 178 | 8 | 170 | 90.3 MB |
| `NEWS_EVENTS` | 1 | 0 | 1 | 0.4 MB |
| `POSTCONTENT` | 123 | 2 | 121 | 45.9 MB |
| `PROFILE` | 3 | 1 | 2 | less than 0.1 MB |
| **Total** | **900** | **246** | **728** | **370.9 MB** |

These totals are based on exact filename matching. The largest candidate groups are `CITYOFFICIAL`, `MAYOR`, `DEPT`, and `POSTCONTENT`.

### Database references missing on disk

There are 74 broken local references that should be investigated before cleanup:

- `CAREERS`: 70 referenced PDFs are missing.
- `CITYOFFICIAL`: `1770878916_b1e08558c720ec018823.jpg` is missing.
- `DEPT`: `1779340053_11feded74645ab22278b` is missing and has no extension in the database value.
- `FULLDISC`: `1778814228_f708c734dc6aabca79f4.pdf` and `1779344876_d38c22dabe3e509b1aba.xlsx` are missing.

This mismatch suggests that the local database and upload folder came from different snapshots.

## Public assets: high-confidence candidates

### Security-sensitive file

- `public/assets/websitebinan.sql` (about 79 KB)

This file is not a browser asset and should not be publicly downloadable. Move any required database backup outside `public` and protect it with appropriate filesystem permissions.

### Build and debugging sources

There is no root `package.json`, and the application loads compiled CSS and JavaScript. These files are not required for normal browser runtime, although they may be useful if a developer rebuilds third-party bundles manually.

| Pattern | Count | Size | Recommendation |
|---|---:|---:|---|
| `public/assets/**/*.map` | 83 | 72.3 MB | Omit from production unless browser source-map debugging is required. |
| `public/assets/**/*.scss` | 133 | 0.6 MB | Keep only in a separate source/build archive if needed. |
| `public/assets/**/*.less` | 20 | 0.5 MB | Keep only in a separate source/build archive if needed. |
| `public/assets/**/*.ts` | 180 | 0.7 MB | Keep only in a separate source/build archive if needed. |

Combined potential saving: about 74 MB.

### Font Awesome source-only SVG sets

The application loads Font Awesome through its compiled CSS/webfonts and JavaScript. No application code directly references these source SVG directories:

- `public/assets/sbadmin2/vendor/fontawesome-free/svgs` — 1,612 files, about 1.5 MB.
- `public/assets/sbadmin2/vendor/fontawesome-free/sprites` — 3 files, about 1.2 MB.

Keep `css`, `js`, and `webfonts`; only the source SVG and sprite folders are candidates.

### Template demonstration images

No application code directly references these template image directories:

- `public/assets/admin/img` — 24 files, about 1.8 MB.
- `public/assets/sbadmin2/img` — 6 files, about 0.1 MB.

Review visually before removal in case a template stylesheet uses one indirectly.

## Public assets: medium-confidence candidates

These directories have no direct application-code path references and appear to be old source, backup, or sample collections. They may still have editorial value, so archive them before removing them from the public deployment.

| Directory | Files | Size |
|---|---:|---:|
| `public/assets/img/brgy_logo` | 26 | 3.5 MB |
| `public/assets/img/dept_logo` | 28 | 8.3 MB |
| `public/assets/img/Mayor` | 9 | 8.6 MB |
| `public/assets/img/news_sample` | 4 | 1.8 MB |
| `public/assets/img/team` | 17 | 1.5 MB |
| `public/assets/img/team2025` | 33 | 91.4 MB |
| `public/assets/img/team2025(4mb)` | 14 | 6.0 MB |
| **Total** | **131** | **121.0 MB** |

### Root PDFs with no code reference

The following files are not referenced by application PHP, CSS, or JavaScript:

- `public/assets/2016-Revenue-Code.pdf`
- `public/assets/Active-Businesses-2017.pdf`
- `public/assets/ENVIRONMENTAL CODE OF THE CITY OF BIÑAN, LAGUNA.pdf`
- `public/assets/LGU BIÑAN_ 2026 CITIZENS CHARTER_1st Edition.pdf`
- `public/assets/LGU-BINAN-2025-CITIZENS-CHARTER-1ST-EDITION.pdf`
- `public/assets/Local-Investment-and-Incentive-Code.pdf`
- `public/assets/Market-Value.pdf`

Combined size: about 24.0 MB. Confirm that no external website or printed QR code links directly to these URLs before removal.

## Assets that look large but are currently used

Do not remove these based only on size:

- `public/assets/video/binanclip.mp4` (about 123 MB) is referenced by the home page.
- `public/assets/img/Emergency_Hotline` is referenced by the emergency-hotline section.
- `2024-CITIZENS-CHARTER_9th-Edition.pdf` and `BCH-Organizational-Chart-as-of-February-15-2023.pdf` are referenced by the navigation.
- `Safety-Seal-MC.pdf` and `Establishment-with-Safety-Seal-as-of-April-7-2022.pdf` are referenced by the Safety Seal page.
- Compiled vendor CSS, JavaScript, fonts, and webfonts are loaded indirectly through `app/Helpers/asset_helper.php` and must not be removed merely because a page template does not mention every file individually.

## Recommended cleanup sequence

1. Remove `websitebinan.sql` from the public deployment after placing a verified backup outside the web root.
2. Back up both the production database and `writable/uploads` at the same point in time.
3. Repeat the exact filename comparison against that production snapshot.
4. Restore or correct the 74 currently broken local database references before deleting orphans.
5. Move upload candidates to a quarantine directory outside the web root for 30 days instead of deleting immediately.
6. Remove source maps and preprocessor sources from the production artifact, while retaining them in a development archive if needed.
7. Archive the unreferenced static image/PDF collections, run page smoke tests, and only then remove the archive from the server.
