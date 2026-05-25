<?php $departmentLabel = !empty($current_department) ? $current_department : 'No department assigned'; ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Profile</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-body p-4">
        <div class="row">
            <div class="col-lg-7">
                <form id="profileDetailsForm">
                    <div class="mb-3">
                        <label for="profileFullName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="profileFullName" name="fullName"
                            value="<?= esc(trim(($user->fname ?? '') . ' ' . ($user->lname ?? ''))) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="profileEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="profileEmail" name="email"
                            value="<?= esc($user->email ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="profileUsername" class="form-label">Username</label>
                        <input type="text" class="form-control" id="profileUsername" name="username"
                            value="<?= esc($user->username ?? '') ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="profileDepartment" class="form-label">Current Department</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="profileDepartment" name="department"
                                value="<?= esc($departmentLabel) ?>" readonly>
                            <button type="button" class="btn btn-primary" id="changeDepartmentBtn">Change Department</button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Save Profile
                    </button>
                    <button type="button" class="btn btn-outline-primary ml-2" id="showPasswordFormBtn">
                        <i class="fas fa-key mr-1"></i> Change Password
                    </button>
                </form>
            </div>
        </div>

        <div class="row mt-4 d-none" id="passwordChangePanel">
            <div class="col-lg-7">
                <div class="border rounded p-3 bg-light">
                    <h2 class="h5 text-gray-800 mb-3">Change Password</h2>
                    <form id="profilePasswordForm">
                        <div class="mb-3">
                            <label for="profileOldPassword" class="form-label">Old Password</label>
                            <input type="password" class="form-control" id="profileOldPassword" name="oldPassword" required>
                        </div>

                        <div class="mb-3">
                            <label for="profileNewPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="profileNewPassword" name="newPassword" minlength="8" required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Save Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
