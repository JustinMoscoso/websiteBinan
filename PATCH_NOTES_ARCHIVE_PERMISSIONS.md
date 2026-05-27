# Patch Notes: Archive, Restore, Delete Permissions

Date: 2026-05-27

## Summary

Implemented archive/restore action controls across admin action dropdowns and tightened delete/account-management permissions.

## Permission Rules

- Archive is available to `ADMIN`, `SUPERADMIN`, and `DEVELOPER`.
- Restore is available only to `SUPERADMIN` and `DEVELOPER`.
- Delete is available only to `SUPERADMIN` and `DEVELOPER`.
- Scoped barangay/department admin accounts cannot access Account Management.
- Lower privilege accounts cannot archive, restore, or delete through action bars.

## Shared Frontend Helpers

Location: `app/Views/admin/common-js.php`

- Added `adminCanArchive(level)`.
- Added `adminCanRestore(level)`.
- Added `adminCanDelete(level)`.
- Added `renderArchiveRestoreAction(level, row, toggleFnName)`.
- Added `renderDeleteAction(level, rowId, deleteFnName)`.
- Added status helper functions for consistent action text and success messages.

## Backend Permission Enforcement

Location: `app/Controllers/Admin.php`

- Added global delete guard for all `delete_*` AJAX modes.
- Added global archive guard for all `set_status_*` AJAX modes when requested status is `ARCHIVED`.
- Added restore guard that blocks non-Developer/Superadmin users from changing archived records back to `ACTIVE` or `INACTIVE`.
- Added helper methods:
  - `canArchiveRecords($user)`
  - `canRestoreRecords($user)`
  - `statusModelForMode($mode)`
- Allowed `ARCHIVED` status for job and barangay status updates.
- Added missing delete handlers:
  - `delete_about`
  - `delete_barangay`
  - `delete_services`
  - `delete_map`
- Restricted user-account deletion via `set_status_user` status `DELETED` to `DEVELOPER` and `SUPERADMIN`.

## Account Management Access

Locations:

- `app/Controllers/Admin.php`
- `app/Views/admin/admin_page.php`

Changes:

- Department-scoped `ADMIN` accounts cannot access Account Management.
- Barangay-scoped `ADMIN` accounts cannot access Account Management.
- Sidebar Account Management link is hidden for both scoped admin types.

## Updated Action Dropdowns

Locations:

- `app/Views/admin/js/about.php`
- `app/Views/admin/js/accounts_mgmt.php`
- `app/Views/admin/js/brgy.php`
- `app/Views/admin/js/careers.php`
- `app/Views/admin/js/cityOff.php`
- `app/Views/admin/js/contacts.php`
- `app/Views/admin/js/dept.php`
- `app/Views/admin/js/fullDisc.php`
- `app/Views/admin/js/invest.php`
- `app/Views/admin/js/jobs.php`
- `app/Views/admin/js/map.php`
- `app/Views/admin/js/mayor.php`
- `app/Views/admin/js/postcontent.php`
- `app/Views/admin/js/services.php`

Changes:

- Non-archived rows show regular Activate/Deactivate where allowed.
- Non-archived rows show Archive for Admin and above.
- Archived rows show Restore only for Developer/Superadmin.
- Delete appears only for Developer/Superadmin.
- Existing Delete options were removed from Admin-only visibility.

## Notes

- Archive uses existing `status = 'ARCHIVED'`; records are not deleted from the database.
- Restore sets the record back to `ACTIVE`.
- Physical delete remains available only to Developer/Superadmin.
