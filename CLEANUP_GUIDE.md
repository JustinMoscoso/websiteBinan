# Cleanup Guide — Unnecessary / Dead Code

## 1. Delete These Files (Safe to Remove)

| File | Reason |
|------|--------|
| `app/Libraries/Auther.php` | Never instantiated anywhere; references non-existent `config('Auther')` |
| `app/Helpers/function_helper.php` | Helper never loaded; functions `isValidMimeImg()` and `generateRandomCode()` never called |
| `app/Models/UserModel.php` | Targets `users` table; app uses `useradmin` table via `UserAccount.php` |
| `app/Models/ContactInquiry.php` | Duplicate of `ContactInquiryModel.php` (used), both target `contact_inquiries` |
| `app/Common.php` | Contains only comments, no code |
| `app/Controllers/AdminJob.php` | No routes point to this controller (job mgmt done via `Admin::ajax()`) |
| `app/Views/welcome_message.php` | Default CodeIgniter welcome page, never loaded by any controller |
| `app/Views/register_page.php` | No route/controller references it |
| `app/Views/job_modal.php` | Never loaded by any controller |
| `scratch_db.php`, `scratch_dept.php`, `test_ajax.php` | Debug/test scripts at project root, bypass routing |

## 2. Missing View Files (Create or Remove Routes)

| Route | Controller Method | Missing View |
|-------|-------------------|-------------|
| `/cityofficials` | `Home::cityofficials()` | `cityofficials_page` |
| `/career` | `Home::career()` | `career_page` |
| `/jobpostings` | `Home::jobpostings()` | `jobpostings_page` |

## 3. Routes to Fix / Remove (`app/Config/Routes.php`)

| Line(s) | Issue | Action |
|---------|-------|--------|
| 85–86 | `Home::process/$1` — method doesn't exist | Remove both lines |
| 61 | `$route['announcements/(:num)']` — CI3 syntax, `$route` undefined | Remove |
| 57–60, 79–83 | Duplicate routes for `/newsevents`, `/announcements`, `/newseventscontent`, `/announcementcontent` | Remove duplicates (keep lines 57–60) |
| 43, 75 | Duplicate `/contact` (GET) pointing to `Home::contact` and `ContactController::index` | Pick one |
| 45, 76 | Duplicate `contact/send` (POST) pointing to `Home::send` and `ContactController::send` | Pick one |
| 129–130, 140–141 | Duplicate `admin/image/` and `admin/preview_file/` routes | Remove duplicates |
| 47–49 | Commented-out routes for `/jobs`, `/jobdetails`, `/getalljobs` | Clean up |

## 4. Dead / Debug Methods to Remove

| File | Method | Issue |
|------|--------|-------|
| `app/Controllers/Home.php:632` | `make_dev()` | Creates dev account with hardcoded creds — dangerous in production |
| `app/Controllers/Home.php:611` | `test_jobs()` | Debug method, route at `/test-jobs` |
| `app/Controllers/Home.php:537` | `login()` | Just calls helper + view; no route points to it |

## 5. Duplicate Code to Consolidate

| What | Files | Recommendation |
|------|-------|---------------|
| Contact send logic | `Home::send()` and `ContactController::send()` | Keep one, remove the other |
| Home page | `Home::index()` and `Home::home_page()` | Nearly identical — consolidate into one |
| Contact inquiry models | `ContactInquiry.php` and `ContactInquiryModel.php` | Delete `ContactInquiry.php`, keep `ContactInquiryModel.php` |

## 6. Debug / Dev Logging to Remove for Production

| File | Line(s) | What |
|------|---------|------|
| `app/Controllers/Admin.php:351,384` | `error_log($query->getLastQuery())` | Exposes raw SQL |
| `app/Controllers/Admin.php:3715,3720,3725,3729` | `error_log()` in map CRUD |
| `app/Controllers/MapController.php:19,39` | `error_log()` debug output |
| `app/Models/Map.php:20-21` | `error_log("Map model initialized...")` every instantiation |
| `app/Controllers/Admin.php:4007` | Commented-out `log_message('debug', ...)` |
| `app/Controllers/Admin.php` (multiple) | `log_message('debug', ...)` calls |

## 7. Security Issues

| File | Line | Issue | Fix |
|------|------|-------|-----|
| `app/Config/Email.php:52` | Hardcoded Gmail app password `djkh tkzp jphu kchp` | Move to `.env` |
| `app/Config/Email.php:14` | Hardcoded `$fromEmail` | Move to `.env` |
| `app/Controllers/BaseController.php:71` | Hardcoded `$baseURL = "http://localhost/"` | Use `base_url()` helper or config |
| `app/Controllers/Admin.php:411` | CORS `Access-Control-Allow-Origin: *` always | Restrict to specific origins |
| `app/Config/Filters.php:27` | No CSRF protection enabled globally | Consider enabling CSRF filter |

## 8. Commented-Out Code to Clean

- `app/Config/Routes.php:47-49` — old job routes
- `app/Config/Filters.php:30` — `// 'toolbar'`
- `app/Controllers/BaseController.php:45,58` — commented session code
- `app/Controllers/Admin.php:2264,2273,2289,3117-3128` — commented captain image upload logic
- `app/Helpers/asset_helper.php:75-82` — commented JS asset URLs

## 9. `.gitignore` Additions

Add to `.gitignore`:

```
/vendor/
*.txt
*.pptx
*.pdf
scratch_db.php
scratch_dept.php
test_ajax.php
```