# Shared Add/Edit Modal Reuse Guide

Use this pattern when a module has separate Add and Edit modals and you want to consolidate them into one reusable modal.

## Goal

Keep one modal, one form, one save button, and one submit path. The modal should switch behavior based on mode:

- `add` for creating a record
- `edit` for updating a record

## What To Change

### 1. Keep only one modal

- Remove the separate Edit modal.
- Reuse the Add modal for both add and edit actions.
- Add a hidden input for the record ID, for example:

```html
<input type="hidden" id="recordId" name="id">
<input type="hidden" id="recordMode" name="mode" value="add">
```

### 2. Add a modal title element

Use one title that changes based on mode:

```html
<span id="recordModalTitle">Add Record</span>
```

### 3. Create one modal helper

Use a small helper to switch the modal into add or edit mode.

Rules:

- `openRecordModal('add')`
- `openRecordModal('edit', record)`
- Fill the hidden ID in edit mode
- Fill all fields from the record payload
- Reset fields in add mode

### 4. Use one submit handler

- Keep one `#btnAdd` or shared save button.
- On submit, inspect `#recordMode`.
- If mode is `edit`, send the record ID with the request.
- If mode is `add`, create a new record.

Important:

- Explicitly set `formData.set('id', $('#recordId').val())` before update requests.
- Do not rely only on automatic form serialization for the ID.

### 5. Normalize backend payloads

If the backend may return `ID` or `id`, normalize it before using it in the modal.

Example:

```php
if (!isset($row['ID']) && isset($row['id'])) {
    $row['ID'] = $row['id'];
    unset($row['id']);
}
```

### 6. Update controller validation

For update endpoints:

- Accept `id` from the shared modal
- Optionally accept a fallback field like `jobId` or `recordId`
- Validate the ID before running the update

Example:

```php
$id = $this->request->getPost('id') ?: $this->request->getPost('recordId');
if (!$id || !is_numeric($id)) {
    $message = 'Invalid record ID';
    break;
}
```

### 7. Reset modal state on close

When the modal closes:

- reset the form
- clear the hidden ID
- restore the modal title
- reset the button text
- clear any rich text editor state

### 8. Remove legacy fallbacks after migration

Once the shared modal is working end to end, clean out the old edit-specific field names.

Do this in the controller and JS:

- remove `edit*` payload fallbacks
- keep only the shared field names used by the reusable modal
- keep a fallback ID only if an older screen still depends on it
- remove duplicate edit-only submit handlers and duplicate modal reset code

This keeps the refactor from accumulating compatibility code that no longer serves a purpose.

## Rich Text / Quill Note

If the module uses Quill or another rich text editor:

- keep one editor instance
- use one hidden input for the content
- load edit content after the modal is shown
- update the hidden field before submit

## Good Reuse Pattern

- one modal
- one form
- one save button
- one submit handler
- one editor instance
- one ID field

## Modules Already Converted In This Repo

- Contacts
- Invest
- Job Management
- Services
- About / Homepage
- Post Content

## Copy-Paste Prompt For New Modules

Use this prompt when converting another module:

```text
Patch the [MODULE NAME] module so the edit action reuses the add modal instead of maintaining two separate modals.

Requirements:
- Keep one shared modal for add/edit.
- Add hidden fields for record ID and mode.
- Switch the modal title and button text based on mode.
- Populate fields from the selected record in edit mode.
- Ensure the edit submit sends the record ID explicitly in FormData.
- Update the backend update endpoint to accept the shared modal payload.
- Normalize payload keys if the API returns `ID` instead of `id`.
- Remove any duplicate edit modal, edit form, duplicate Quill editor, and duplicate submit handlers.
- Reset modal state cleanly on close.

Also keep the table and actions working after the refactor.
```

## Suggested Checklist

- [ ] Remove the second modal
- [ ] Reuse the Add modal for edit
- [ ] Add hidden `id` and `mode` fields
- [ ] Make the title/button text dynamic
- [ ] Explicitly set `id` in `FormData` for updates
- [ ] Accept fallback ID in controller
- [ ] Remove legacy `edit*` field fallbacks after verification
- [ ] Reset modal state on close
- [ ] Test add flow
- [ ] Test edit flow
- [ ] Test cancel and reopen
