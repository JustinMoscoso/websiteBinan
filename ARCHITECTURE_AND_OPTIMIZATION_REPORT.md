# System Optimization and Architecture Report

Based on a review of the current codebase (specifically focusing on CodeIgniter 4 usage, Controller structures like `Home.php` and `Admin.php`, and standard web application practices), here are the critical improvements needed to make the system run faster, reduce technical debt, and improve reusability through OOP.

---

## 1. Performance Optimizations (Run Better & Faster)

### A. Implement Data & View Caching (Critical for Public Sites)
**Issue:** 
Your `Home.php` controller queries the database multiple times on every single page load. For example, `Home::index()` fetches from `VisitCountModel`, `Content` (twice), `MayorContent`, `About`, and `Hotlines`.
**Solution:**
Public city websites have high read traffic but data changes infrequently. 
*   **Page Caching:** Use CodeIgniter's `$this->cachePage($time)` for static pages (like History or Full Disclosure).
*   **Query Caching:** Cache the results of frequent queries (like Announcements and News) using `cache()->remember()`. This avoids hitting the MySQL database on every request.

### B. Database Indexing
**Issue:** 
Almost every query in the system uses `->where('status', 'ACTIVE')` and `->where('category', 'some_category')`. If the database tables grow, scanning the whole table for `ACTIVE` records will slow down the application.
**Solution:**
Create **Composite Indexes** in your database tables. For example, in the `content_tbl`, create an index on `(status, category)`.

### C. Remove Duplicated Code
**Issue:** 
In `app/Controllers/Home.php`, the methods `index()` (Lines 14-60) and `home_page()` (Lines 62-105) contain **exactly the same logic and queries**. 
**Solution:**
Refactor these methods so one calls the other or extracts the shared logic into a private method. This reduces memory usage and improves maintainability.

### D. Asset Optimization & Lazy Loading
**Issue:**
Pages like "City Officials" and "Barangays" load many images (carousels, logos).
**Solution:**
*   Add `loading="lazy"` to `<img>` tags in your views to speed up initial page load times.
*   Implement image resizing/compression upon upload in the Admin controllers to prevent serving 4MB+ images on the frontend.

---

## 2. Architecture & OOP Reusability Improvements

### A. Frontend Controller Decomposition (Refactor `Home.php`)
**Observation:** 
You recently did an excellent job refactoring the monolithic `Admin.php` into modular controllers (`EntityController`, `JobController`, etc.) using a "Thin Facade Pattern".
**Issue:** 
The `Home.php` is now suffering from the same "God Class" problem (over 700 lines). It handles Jobs, City Officials, Map, Safety Seal, Announcements, etc.
**Solution:** 
Apply the same architectural pattern to the frontend. Create sub-controllers under `App\Controllers\Frontend\`:
*   `Frontend\NewsController`
*   `Frontend\EntityController` (for Barangays, Depts, Officials)
*   `Frontend\JobController`
Leave `Home.php` only for the actual landing page (`index()`).

### B. Introduce a Base Model (Global Scopes)
**Issue:** 
In every controller, you are repeatedly writing `->where('status', 'ACTIVE')`. This is a violation of the DRY (Don't Repeat Yourself) principle. If you ever change 'ACTIVE' to '1', you will have to update hundreds of lines.
**Solution:** 
Create an `App\Models\BaseModel` that extends CodeIgniter's model. Use CI4's **Model Events** (`beforeFind`) to automatically append `->where('status', 'ACTIVE')` to every select query globally. Then have `Barangay`, `Department`, etc., extend `BaseModel`.

### C. Service / Repository Layer
**Issue:** 
Both `Admin\JobController` and `Home::jobs()` contain logic for handling job postings. Business logic is tightly coupled to the controllers.
**Solution:** 
Implement a "Service Layer" (e.g., `App\Services\JobService`). This service will contain the logic for searching, filtering, and validating jobs. Both the frontend controller and the backend admin controller can then reuse this exact same service, ensuring 100% consistency and OOP reusability.

### D. View Partials & Components
**Issue:**
There is likely duplication in how tables, cards, and pagination links are generated in the `.php` view files.
**Solution:**
Utilize CodeIgniter's View Cells (`view_cell()`) to create reusable UI components (e.g., a standard `NewsCard` component) instead of copy-pasting HTML structure.

---
## Summary of Next Steps
If you'd like to proceed, we can tackle these in phases:
1.  **Phase 1:** Clean up `Home.php` (Remove duplicates, split into smaller controllers).
2.  **Phase 2:** Implement the `BaseModel` to handle repetitive query conditions globally.
3.  **Phase 3:** Introduce Caching and verify Database Indexes.
