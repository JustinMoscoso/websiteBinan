# Removing Developer-Only Permanent Delete

Permanent deletion is intentionally restricted to users whose `user_lvl` is exactly
`DEVELOPER`. The server enforces this rule; hiding the button is not the security
boundary.

## Fast global shutdown

To disable permanent deletion across all modules without editing each module, replace
the `delete_` request guard in `app/Controllers/Admin.php` with an unconditional error
response. Search for:

```php
if (str_starts_with($mode, 'delete_')) {
```

Return a failed JSON response and stop execution inside that block. This is the
central kill switch and must be changed first.

## Remove the shared button renderer

Delete `renderDeleteAction()` from `app/Views/admin/common-js.php` after all calls
listed below have been removed.

## Remove module action-menu calls

Remove the `renderDeleteAction(...)` line from:

- `app/Views/admin/js/careers.php`
- `app/Views/admin/js/brgy.php`
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

Jobs also contains `window.deleteJob`; remove that function when removing the Jobs
button. The other modules already had dormant delete functions before the buttons
were enabled. Those functions may also be removed as dead code after the UI calls are
gone.

## Optional backend cleanup

After the central shutdown is deployed, the corresponding `case 'delete_*'` handlers
in `app/Controllers/Admin.php` can be removed. Keeping the central shutdown in place
during cleanup prevents a partially removed UI from exposing a working endpoint.

## Verification

1. Sign in as `DEVELOPER` and confirm no Delete item appears in any action menu.
2. Sign in as another role and confirm no Delete item appears.
3. Send a direct POST request to a former `admin/ajax/delete_*` endpoint and confirm
   it returns `status: 0` without deleting data.
4. Check System Logs and the affected tables to confirm no record was removed.

Modules without an existing supported delete endpoint were not given one. This avoids
unsafe deletion of records with linked accounts or dependent entities.
