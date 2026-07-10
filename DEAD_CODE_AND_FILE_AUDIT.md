# Dead Code and Unnecessary File Audit

Audit date: 2026-07-10

## Executive summary

This CodeIgniter 4 repository contains confirmed dead first-party code, stale debug endpoints, duplicate route declarations, missing view targets, and files that do not belong in the deployable application repository. The audit found 6,070 tracked files. Most tracked-file volume is dependency or template code: `vendor/` has 3,425 files (21.57 MB), `sbadmin2/` has 1,855 files (16.50 MB), and `system/` has 550 files (2.98 MB).

No files were deleted during this audit. Candidates are classified so that unreferenced code is not confused with code that might still be reachable through CodeIgniter legacy auto-routing or database-provided asset paths.

## Scope and method

The audit used:

- `git ls-files` and file-size inventories;
- explicit routes from `app/Config/Routes.php` and `php spark routes`;
- static searches for controllers, models, helpers, views, assets, and class references;
- comparison of `sbadmin2/` with `public/assets/sbadmin2/`;
- PHP syntax checks for every PHP file under `app/`;
- the existing PHPUnit suite.

Static reference analysis cannot prove that a path stored in the database, loaded by reflection, or reached through legacy auto-routing is unused. Those cases are marked conditional.

## 1. Confirmed cleanup candidates

These items have no application reference or have a clearly superseding implementation.

| Candidate | Evidence | Recommended action |
| --- | --- | --- |
| `scratch_db.php` | Root-level database schema dump script; bypasses normal routing. | Delete. |
| `scratch_dept.php` | Root-level one-off department query; bypasses normal routing. | Delete. |
| `test_ajax.php` | Manual endpoint test using hard-coded localhost URLs, including endpoints that are no longer explicitly routed. | Delete; replace any useful coverage with automated tests. |
| `app/Helpers/function_helper.php` | `isValidMimeImg()` and `generateRandomCode()` occur only at their declarations; the helper is never loaded. | Delete. |
| `app/Models/UserModel.php` | The class occurs only in its own file; it targets `users`, while authentication uses `UserAccount` and `useradmin`. | Delete after confirming no external CLI script instantiates it. |
| `app/Models/ContactInquiry.php` | The class occurs only in its own file; live contact code uses `ContactInquiryModel`. | Delete. |
| `app/Views/register_page.php` | No controller or view loads it and no registration route exists. | Delete. |
| `app/Views/job_modal.php` | No controller or view loads it. | Delete. |
| `app/Views/welcome_message.php` | Default CodeIgniter welcome page; only self-matches in reference search. | Delete. |
| `.idea/` | Six IDE-specific files are tracked even though `.idea/` is ignored. | Remove from Git tracking; keep local copies if needed. |
| `sbadmin2/` | All 1,855 files have counterparts under `public/assets/sbadmin2/`; 1,632 are byte-identical. Application helpers reference only `assets/sbadmin2/...`, not root `sbadmin2/...`. | Remove the root copy after confirming no external deployment script copies from it. Keep the served public copy. |

### Empty scaffold files

`app/Common.php` contains comments only and has no runtime behavior. The `.gitkeep` files in non-empty directories such as `app/Models/`, `app/Helpers/`, `app/Libraries/`, `app/Filters/`, and `app/Database/Migrations/` no longer preserve empty directories and can be removed. Keeping `app/Common.php` as a documented CodeIgniter extension point is harmless, so it is low priority rather than a required deletion.

## 2. Conditional dead-code groups

These groups have no explicit live route/reference, but legacy auto-routing has not been explicitly disabled (`Feature::$autoRoutesImproved` is `false`, and `setAutoRoute(false)` is commented out). Check web-server access logs and disable legacy auto-routing before treating them as proven unreachable.

| Candidate group | Evidence | Decision |
| --- | --- | --- |
| `app/Controllers/AdminJob.php`, `app/Views/admin/job_list.php`, `app/Views/admin/job_form.php` | No explicit route targets `AdminJob`. Current job administration is implemented by `Admin::mode('jobs')`, `app/Views/admin/mod/jobs.php`, and `Admin::ajax()`. | Likely obsolete. Remove as one group after an auto-route/access-log check. |
| `app/Controllers/ContactController.php` | Its two route declarations are shadowed by earlier identical `contact` and `contact/send` routes targeting `Home`; `php spark routes` resolves both paths to `Home`. | Consolidate contact handling, then remove either this controller or the duplicate methods in `Home`. Current effective handler is `Home`. |
| `Home::login()` at `app/Controllers/Home.php:537` | Explicit login routes target `Auther::login()`. | Remove after auto-routing is disabled. |
| `Home::getdepartments()` at line 565 | No explicit route or first-party caller; only stale `test_ajax.php` expects it. | Remove or add an intentional API route and tests. |
| `Home::getAllJobs()` at line 575 | Its old route is commented out and there is no current first-party caller. | Remove if `/jobs` is the replacement. |
| `Home::jobDetails()` at line 590 | Its old route is commented out and there is no current first-party caller. | Remove if job details are rendered by the current jobs page. |
| `app/Libraries/Auther.php` | No fully qualified reference or instantiation exists. It also calls non-existent `config('Auther')`. Do not confuse it with `app/Controllers/Auther.php`. | Delete after checking for external bootstrap code. |

## 3. Debug and dangerous development code

These items may be reachable and should be removed or protected even though they are not all technically dead.

| Location | Finding | Action |
| --- | --- | --- |
| `app/Config/Routes.php:92` and `Home::test_jobs()` | Public `/test-jobs` route exposes database/table diagnostics and renders `app/Views/test_jobs.php`. | Remove the route, method, and view. |
| `Home::make_dev()` at line 632 | Creates a developer account with hard-coded identity and password material. With legacy auto-routing, it may be reachable without an explicit route. | Remove immediately and disable legacy auto-routing. Use a protected CLI seeder for account creation. |
| `app/Controllers/MapController.php:19` | Logs all retrieved barangays as JSON. | Remove noisy data logging. |
| `app/Models/Map.php:20` | Logs model configuration on every construction. | Remove initialization logging. |
| `app/Views/officials_page.php:139` | Server-side `error_log(print_r(...))` logs the complete officials dataset during rendering. | Remove. |
| `app/Controllers/Admin.php` | Contains raw-query and map CRUD `error_log()` calls plus multiple debug-level messages. | Review individually; remove raw SQL/data logs from production paths. |

## 4. Stale, duplicate, and broken routes

These findings were exposed while tracing supposedly dead controllers. They should be corrected before deleting code because duplicate route order determines which implementation is live.

| Route/source | Finding | Action |
| --- | --- | --- |
| `Routes.php:43,75` | Duplicate GET `contact`; the first declaration (`Home::contact`) wins. | Keep one declaration and one controller implementation. |
| `Routes.php:45,76` | Duplicate POST `contact/send`; the first declaration (`Home::send`) wins. | Keep one declaration and one send implementation. |
| `Routes.php:57-60,79-83` | News and announcement routes are repeated. | Retain one declaration per method/path. |
| `Routes.php:61` | `$route[...]` is CodeIgniter 3 syntax and does not register a CodeIgniter 4 route. | Delete. |
| `Routes.php:85-86` | GET and POST `/process/(:any)` target missing `Home::process()`. | Delete or implement an intentional handler. |
| `Routes.php:129-130,140-141` | Admin image and preview routes are duplicated. | Remove the second pair. |
| `GET /barangay` | `Home::barangay()` loads missing `app/Views/barangay_page.php`. | Redirect to `/barangays`, create the intended view, or remove the route/method. |
| `GET /servicescontent` | Route supplies no ID, but `Home::servicescontent($id)` requires one. | Add an ID placeholder or remove the stale route. |
| `GET /cityofficials` | Loads missing `app/Views/cityofficials_page.php`. | Prefer the working `/officials` route or create the intended view. |
| `GET /career` | Loads missing `app/Views/career_page.php`; `/careers` has an existing view. | Remove/redirect the singular route or create the view. |
| `GET /jobpostings` | Loads missing `app/Views/jobpostings_page.php`; `/jobs` has an existing view. | Remove/redirect the old route or create the view. |
| `GET /admin/announcements` | Dynamic mode loading expects missing `admin/mod/announcements.php` and `admin/js/announcements.php`. | Remove the route or map it to the current post-content module. |

`MapController` also attempts to render missing `app/Views/errors/html/error_general.php` in its exception path. This is not dead code, but the fallback will itself fail when map loading throws.

## 5. Duplicate implementation to consolidate

| Implementation | Evidence | Recommendation |
| --- | --- | --- |
| `Home::index()` and `Home::home_page()` | Both build and render the same home-page dataset. The only material difference is that `index()` adds `visit_count`. | Make one method canonical and redirect/alias the other route without duplicating queries. |
| `Home::send()` and `ContactController::send()` | Both save `ContactInquiryModel` data and queue two emails, but return different response types and email content. | Choose one contract based on the AJAX form behavior, test it, and delete the other implementation. |
| `ContactInquiry` and `ContactInquiryModel` | Same table purpose; only `ContactInquiryModel` is live. | Keep `ContactInquiryModel`. |
| Root `sbadmin2/` and `public/assets/sbadmin2/` | Complete filename mirror; the public copy is the one referenced by the application. | Keep one served copy. |

## 6. Non-runtime artifacts and repository hygiene

The following are not application runtime dependencies. They may be useful project records, so archive or relocate them rather than deleting without owner approval:

- `Biñan_Practicum_Progress_Report.pptx`
- `Weekly_Journal.md`
- `Weekly_Journal.html`
- `Weekly_Journal_WebsiteBiñan.pdf`
- `presentation_script.txt`
- `checklist.txt`
- `patchnotes.txt`

The many feature-specific Markdown files are documentation, not dead code. Consolidating completed implementation notes into `docs/archive/` would make the root easier to navigate.

### Database scripts

`db/` contains two full database snapshots and several competing job-table scripts (`create_jobs_table.sql`, `create_jobs_table_simple.sql`, `jobs_table.sql`, and `fix_jobs_table.sql`). Static analysis cannot determine which scripts were applied. Do not delete them until the schema history is documented. Recommended cleanup:

1. Keep one sanitized baseline schema or backup outside Git.
2. Convert still-required changes into ordered CodeIgniter migrations.
3. Archive or delete superseded ad hoc SQL after comparing it with the live schema.
4. Ensure database dumps contain no production personal data or credentials.

### Tracked dependencies and framework source

- `vendor/` is reproducible from `composer.lock` and normally should not be tracked. Remove it from Git and add `/vendor/` to `.gitignore` only if deployment runs `composer install --no-dev --optimize-autoloader`.
- `system/` is framework source in this older CodeIgniter project layout. It is not safe to delete directly. A later framework/dependency modernization can move it to a Composer-managed package.
- `.env` is currently tracked despite the `.gitignore` rule. It is not useless, but it should not be versioned. Remove it from tracking, provide `.env.example`, and rotate any credentials that have ever been committed.

## 7. Test scaffold candidates

The test suite is still mostly the CodeIgniter starter scaffold. The following example-only files can be replaced with application tests and then removed:

- `tests/database/ExampleDatabaseTest.php`
- `tests/session/ExampleSessionTest.php`
- `tests/_support/Models/ExampleModel.php`
- `tests/_support/Database/Seeds/ExampleSeeder.php`
- `tests/_support/Database/Migrations/2020-02-22-222222_example_migration.php`

`tests/unit/HealthTest.php` and `tests/_support/Libraries/ConfigReader.php` provide a basic configuration check, but the current base URL assertion fails.

## 8. Asset audit limitation

Most of `public/assets/` is ignored by Git and many image/file paths can be stored in the database. A filename that does not appear in PHP, JavaScript, or CSS is therefore not enough evidence that the asset is orphaned. A safe asset cleanup requires exporting all path-bearing database columns, normalizing those paths, combining them with static references, and comparing the result with the filesystem. Large assets such as the 126 MB `binanclip.mp4`, multi-megabyte official photos, and duplicate-format history artwork should be optimized separately, not deleted based only on static references.

## 9. Verification results

- `php spark routes`: completed and confirmed which duplicate route handlers win.
- PHP lint: all PHP files under `app/` passed `php -l`.
- PHPUnit: 5 tests ran; 2 passed, 2 errored, and 1 failed.
- Database-test errors: the PHP SQLite3 extension is unavailable.
- Health-test failure: `Config\App::$baseURL` is empty in the config file used by the test.
- No code coverage driver is installed.

The failing example tests do not validate current application behavior and should not block removal of confirmed dead files, but real route/controller tests should be added before consolidating live implementations.

## 10. Recommended cleanup order

1. Remove or protect `/test-jobs` and `Home::make_dev()`; disable legacy auto-routing.
2. Fix the missing-handler and missing-view routes, then remove duplicate route declarations.
3. Delete root scratch scripts and confirmed orphan models, helper, and views.
4. Consolidate contact handling and the duplicate home-page methods.
5. Remove the obsolete `AdminJob` group after checking access logs.
6. Untrack `.idea/`, `.env`, and (if deployment supports Composer install) `vendor/`.
7. Remove the root `sbadmin2/` mirror after confirming the public copy is the deployment source.
8. Archive practicum/generated documents and normalize the database migration history.
9. Replace starter tests with application route, authentication, contact, jobs, and authorization tests.

