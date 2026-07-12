# Project Cleanup Summary

Date: July 12, 2026

This document summarizes the evidence-based cleanup performed on the Biñan website
project. Files were removed only when route inspection and source-reference searches
showed that they were unused, duplicated, diagnostic-only, or unreachable.

## Controllers and executable code removed

- Removed `app/Controllers/AdminJob.php`.
  - This was an obsolete standalone Jobs CRUD controller.
  - The active Jobs implementation uses `Admin::mode('jobs')` and `Admin::ajax()`.
- Removed `app/Controllers/ContactController.php`.
  - Its routes duplicated the active `Home::contact()` and `Home::send()` routes.
- Removed `Home::test_jobs()`.
  - This was a public diagnostic method that checked whether the Jobs table existed.
- Removed `Home::make_dev()`.
  - This contained hard-coded developer-account creation logic and did not belong in
    production application code.
- Removed the second `get_career` case from `Admin::ajax()`.
  - It duplicated an earlier switch case and could never execute.

## Models removed

- Removed `app/Models/ContactInquiry.php`.
  - The active contact flow uses `ContactInquiryModel`.
- Removed `app/Models/UserModel.php`.
  - The application uses `UserAccount`, which maps to the actual admin-user table.

## Views removed

- Removed `app/Views/test_jobs.php`.
- Removed `app/Views/admin/job_form.php`.
- Removed `app/Views/admin/job_list.php`.
- Removed a stale comment in `app/Views/admin/mod/profile.php` referring to modals that
  had already been removed.

## Route cleanup

Cleaned `app/Config/Routes.php` by removing:

- The public `/test-jobs` route.
- The nonexistent admin `announcements` mode route.
- Duplicate contact routes.
- Duplicate news, announcement, image, and file-preview routes.
- An obsolete CodeIgniter 3-style `$route[...]` declaration.
- Old commented Jobs route declarations.

The generated CodeIgniter route table was checked after cleanup and builds
successfully.

## JavaScript and CSS cleanup

Removed project-owned static files with no live source references:

- `public/assets/js/careers - Copy.js`
- `public/assets/js/home.js`
- `public/assets/js/departmentcontent_page.js`
- `public/assets/css/departmentcontent_page.css`
- `public/assets/css/all.css`
- `public/assets/css/font-awesome.min.css`
- `public/orgchart.js`

The stale commented vendor/script block was also removed from
`app/Helpers/asset_helper.php`.

The live `public/assets/js/careers.js` file was retained because
`app/Views/careers_page.php` loads it.

## Quill cleanup

Remote Quill CDN references were removed from:

- `app/Views/admin/mod/dept.php`
- `app/Views/admin/mod/services.php`
- `app/Views/admin/mod/profile.php`

The application now uses the locally hosted Quill 1.3.7 JavaScript and styles from
the shared asset loader.

## Developer-only permanent delete implementation

Permanent delete actions were restored with a server-side `DEVELOPER` restriction
for supported modules. The shared UI renderer also displays Delete only to a
developer account.

Supported modules include:

- Account Management
- Barangays
- Careers
- City Officials
- Contacts
- Departments
- Full Disclosure
- Invest
- Jobs
- Map
- Mayor's Corner
- Post Content
- Services

Account deletion prevents deletion of the currently signed-in account and the final
remaining developer account. Barangay deletion reports linked-record conflicts rather
than exposing raw database errors.

Removal instructions for this feature are maintained separately in
`docs/DEVELOPER_DELETE_REMOVAL.md`.

## Data and status logic cleanup

- Invest records now sort by status alphabetically, with creation date as a
  tie-breaker.
- City Official activation now supports inactive historical records while enforcing
  one active record per applicable position and one active record per rank.
- About/Homepage activation supports inactive historical records while enforcing:
  - one active Home Page record;
  - one active History record per year;
  - one active About Content or Emergency Hotline item per matching title.
- About/Homepage status errors now display the backend's descriptive message.
- Post Content DataTable columns were rearranged to Date Created, Title, Category,
  Author, Status, and Actions; the Image column is hidden.

## SB Admin assessment

Only one SB Admin installation exists at `public/assets/sbadmin2`. It contains both
runtime files and the original distribution's development/source files.

The application directly loads:

- `css/sb-admin-2.min.css`
- `js/sb-admin-2.min.js`
- `vendor/jquery/jquery.min.js`
- `vendor/jquery-easing/jquery.easing.min.js`
- `vendor/fontawesome-free/css/all.min.css`
- Font Awesome webfonts referenced by the CSS

The SCSS source, unminified builds, demos, SVG collection, and unused bundled vendor
libraries were retained. They do not affect browser load time because they are not
requested, and removing approximately 1,800 third-party files would be a separate
vendor-pruning operation.

## Files intentionally retained

- Images and PDFs without source-code references were retained because their filenames
  may be stored in database content or linked externally.
- Uploaded content was not modified.
- Framework and third-party vendor packages were not individually pruned.
- `public/assets/js/careers.js` was retained because it is loaded by the Careers page.

## Root artifact cleanup

Removed non-runtime files from the project root so they are not exposed or executed by
the web server:

- Database and AJAX scratch scripts: `scratch_db.php`, `scratch_dept.php`, and
  `test_ajax.php`.
- Practicum deliverables: the progress-report presentation, presentation script,
  Weekly Journal Markdown/HTML/PDF files.
- Stale project notes: `checklist.txt` and `patchnotes.txt`.
- Superseded implementation and audit documents covering asset localization, barangay
  staff, emergency hotlines, Smart/Globe hotlines, modal reuse, archive permissions,
  and the earlier cleanup audit/guide.

The framework `README.md`, application bootstrap files, license, Composer files, and
the current documents under `docs/` were retained.

## Verification performed

- Ran PHP syntax validation across every PHP file under `app`.
- Regenerated and reviewed the CodeIgniter route table with `php spark routes`.
- Checked for duplicate `Admin::ajax()` switch cases.
- Searched for stale references to removed controllers, methods, views, and assets.
- Ran `git diff --check` to detect whitespace and patch errors.
- Confirmed the active Careers JavaScript file remains present.

All verification checks passed at the end of the cleanup.
