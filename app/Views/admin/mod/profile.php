<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Account Settings</h1>
</div>

<ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active font-weight-bold" id="edit-profile-tab" data-bs-toggle="tab"
            data-bs-target="#edit-profile" type="button" role="tab" aria-controls="edit-profile" aria-selected="true">
            Edit Profile
        </button>
    </li>
    <?php if (!empty($profile_department)): ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link font-weight-bold" id="edit-department-tab" data-bs-toggle="tab"
                data-bs-target="#edit-department" type="button" role="tab" aria-controls="edit-department"
                aria-selected="false">
                Edit Department
            </button>
        </li>
    <?php endif; ?>
</ul>

<div class="tab-content" id="profileTabsContent">
    <div class="tab-pane fade show active" id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab">
        <div class="card shadow mb-4">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-lg-7">
                        <form id="profileDetailsForm">
                            <div class="mb-3">
                                <label for="profileFullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="profileFullName" name="fullName"
                                    value="<?= esc(trim(($user->fname ?? '') . ' ' . ($user->lname ?? ''))) ?>"
                                    required>
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
                                        value="<?= esc($current_department ?? '') ?>"
                                        placeholder="No department assigned" readonly>
                                    <button type="button" class="btn btn-success" id="changeDepartmentBtn">Change
                                        Department</button>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save mr-1"></i> Save Profile
                            </button>
                            <button type="button" class="btn btn-outline-success ml-2" id="showPasswordFormBtn">
                                <i class="fas fa-key mr-1"></i> Change Password
                            </button>
                        </form>
                    </div>

                    <div class="col-lg-5 mt-4 mt-lg-0">
                        <div class="border rounded p-4 bg-light h-100 text-center">
                            <div class="mb-3">
                                <?php if (!empty($profile_picture_url)): ?>
                                    <img id="profilePicturePreview" src="<?= esc($profile_picture_url) ?>"
                                        alt="Profile picture" class="rounded-circle shadow-sm"
                                        style="width: 160px; height: 160px; object-fit: cover;">
                                <?php else: ?>
                                    <div id="profilePictureFallback"
                                        class="rounded-circle shadow-sm bg-primary text-white d-inline-flex align-items-center justify-content-center"
                                        style="width: 160px; height: 160px; font-size: 3rem; font-weight: 700;">
                                        <?= esc(strtoupper(substr($user->fname ?? 'U', 0, 1) . substr($user->lname ?? '', 0, 1))) ?>
                                    </div>
                                    <img id="profilePicturePreview" src="" alt="Profile picture"
                                        class="rounded-circle shadow-sm d-none"
                                        style="width: 160px; height: 160px; object-fit: cover;">
                                <?php endif; ?>
                            </div>

                            <h2 class="h5 text-gray-800 mb-3">Profile Picture</h2>
                            <form id="profilePictureForm" enctype="multipart/form-data">
                                <div class="mb-3 text-left">
                                    <label for="profileImage" class="form-label">Upload Image</label>
                                    <input type="file" class="form-control" id="profileImage" name="profileImage"
                                        accept="image/png,image/jpeg,image/webp" required>
                                    <small class="text-muted">PNG, JPG, JPEG, or WEBP. Max size 2 MB.</small>
                                </div>

                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-upload mr-1"></i> Save Picture
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div> <!-- End Edit Profile Tab Pane -->

    <?php if (!empty($profile_department)): ?>
        <div class="tab-pane fade" id="edit-department" role="tabpanel" aria-labelledby="edit-department-tab">
            <style>
                .profile-department-layout {
                    background: #f7f9fc;
                    border: 1px solid #e3e8f0;
                    border-radius: 8px;
                    padding: 1.5rem;
                }

                .profile-department-card {
                    max-width: 760px;
                    margin: 0 auto;
                    border: 1px solid #dfe5ef;
                    border-radius: 8px;
                    background: #fff;
                    box-shadow: 0 0.5rem 1.5rem rgba(33, 40, 50, 0.08);
                    overflow: hidden;
                }

                .profile-department-card__header {
                    display: flex;
                    align-items: flex-start;
                    justify-content: space-between;
                    gap: 1rem;
                    padding: 1.5rem;
                    border-bottom: 1px solid #edf1f7;
                }

                .profile-department-card__identity {
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                    min-width: 0;
                }

                .profile-department-card__logo {
                    border: 0;
                    width: 64px;
                    height: 64px;
                    border-radius: 8px;
                    background: #e8f0fe;
                    color: #2f5fb3;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    flex: 0 0 auto;
                    font-size: 1.5rem;
                    overflow: hidden;
                    padding: 0;
                    cursor: pointer;
                    transition: box-shadow 0.15s ease, transform 0.15s ease;
                }

                .profile-department-card__logo:hover,
                .profile-department-card__logo:focus {
                    box-shadow: 0 0 0 0.2rem rgba(47, 95, 179, 0.18);
                    outline: 0;
                    transform: translateY(-1px);
                }

                .profile-department-card__logo img {
                    width: 100%;
                    height: 100%;
                    object-fit: contain;
                }

                .profile-department-card__title {
                    color: #25364d;
                    font-size: 1.45rem;
                    font-weight: 800;
                    line-height: 1.2;
                    overflow-wrap: anywhere;
                }

                .profile-department-card__title.is-empty {
                    color: #7b8798;
                    font-style: italic;
                }

                .profile-department-card__body {
                    padding: 1.5rem;
                }

                .dept-info-area+.dept-info-area {
                    margin-top: 1.25rem;
                    padding-top: 1.25rem;
                    border-top: 1px solid #edf1f7;
                }

                .dept-info-label {
                    display: block;
                    color: #697386;
                    font-size: 0.78rem;
                    font-weight: 800;
                    letter-spacing: 0;
                    text-transform: uppercase;
                    margin-bottom: 0.5rem;
                }

                .dept-officer-value {
                    color: #26364d;
                    font-size: 1.08rem;
                    font-weight: 700;
                    margin-bottom: 0.65rem;
                }

                .dept-officer-value.is-unassigned {
                    color: #7b8798;
                    font-style: italic;
                    font-weight: 600;
                }

                .dept-assign-cta {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.35rem;
                    border: 1px dashed #2f5fb3;
                    border-radius: 6px;
                    background: #f8fbff;
                    color: #2f5fb3;
                    font-weight: 800;
                    padding: 0.45rem 0.8rem;
                }

                .dept-assign-cta:hover {
                    color: #234b92;
                    text-decoration: none;
                    background: #eef5ff;
                }

                .dept-status-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.4rem;
                    border-radius: 999px;
                    font-size: 0.82rem;
                    font-weight: 800;
                    padding: 0.45rem 0.75rem;
                }

                .dept-status-toggle {
                    border: 0;
                    background: transparent;
                    padding: 0;
                    cursor: pointer;
                }

                .dept-status-toggle:focus {
                    outline: 0;
                    box-shadow: 0 0 0 0.2rem rgba(47, 95, 179, 0.18);
                    border-radius: 999px;
                }

                .dept-status-switch {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.65rem;
                    border: 1px solid #dfe5ef;
                    border-radius: 999px;
                    background: #fff;
                    padding: 0.35rem 0.75rem 0.35rem 0.4rem;
                    color: #526173;
                    font-weight: 800;
                }

                .dept-status-switch__track {
                    position: relative;
                    width: 46px;
                    height: 24px;
                    border-radius: 999px;
                    background: #d9e0ea;
                    transition: background 0.15s ease;
                    flex: 0 0 auto;
                }

                .dept-status-switch__thumb {
                    position: absolute;
                    top: 3px;
                    left: 3px;
                    width: 18px;
                    height: 18px;
                    border-radius: 50%;
                    background: #fff;
                    box-shadow: 0 1px 4px rgba(33, 40, 50, 0.22);
                    transition: transform 0.15s ease;
                }

                .dept-status-switch.is-active {
                    color: #17693a;
                    border-color: #bfe7cf;
                    background: #f6fff9;
                }

                .dept-status-switch.is-active .dept-status-switch__track {
                    background: #2e9d5b;
                }

                .dept-status-switch.is-active .dept-status-switch__thumb {
                    transform: translateX(22px);
                }

                .dept-status-switch.is-inactive {
                    color: #9d2424;
                    border-color: #f2c4c4;
                    background: #fff8f8;
                }

                .dept-status-badge.is-active {
                    background: #e6f6ed;
                    color: #17693a;
                    border: 1px solid #bfe7cf;
                }

                .dept-status-badge.is-inactive {
                    background: #fff0f0;
                    color: #9d2424;
                    border: 1px solid #f2c4c4;
                }

                @media (max-width: 575.98px) {
                    .profile-department-layout {
                        padding: 1rem;
                    }

                    .profile-department-card__header {
                        flex-direction: column;
                    }

                    .profile-department-card__identity {
                        align-items: flex-start;
                    }
                }
            </style>

            <div class="profile-department-layout mb-4" id="profileDepartmentCard">
                <div class="profile-department-card">
                    <div class="profile-department-card__header">
                        <div class="profile-department-card__identity">
                            <button type="button" class="profile-department-card__logo" id="profileDeptLogoButton"
                                title="Change department logo" aria-label="Change department logo">
                                <?php if (!empty($profile_department->img_logo)): ?>
                                    <img id="profileDeptLogoCell"
                                        src="<?= site_url('admin/image/DEPT/' . $profile_department->img_logo) ?>" alt="">
                                    <i id="profileDeptLogoEmpty" class="fas fa-shield-alt d-none"></i>
                                <?php else: ?>
                                    <i id="profileDeptLogoEmpty" class="fas fa-shield-alt"></i>
                                    <img id="profileDeptLogoCell" src="" alt="" class="d-none">
                                <?php endif; ?>
                            </button>
                            <input type="file" class="d-none" id="profileDeptCardLogoInput" accept="image/*">
                            <div>
                                <span class="dept-info-label">Dept. Name</span>
                                <?php $profileDeptName = trim((string) ($profile_department->dept_name ?? '')); ?>
                                <div class="profile-department-card__title <?= $profileDeptName === '' ? 'is-empty' : '' ?>"
                                    id="profileDeptNameCell">
                                    <?= esc($profileDeptName !== '' ? $profileDeptName : 'Department name not set') ?>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <?php $nextDeptStatus = ($profile_department->status ?? '') === 'ACTIVE' ? 'INACTIVE' : 'ACTIVE'; ?>
                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">
                                <i class="fas fa-list mr-1"></i> Actions
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#" id="editLinkedDepartmentActionBtn">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item profile-dept-status-action" href="#"
                                        data-current-status="<?= esc($profile_department->status ?? '') ?>">
                                        <i
                                            class="fas fa-toggle-<?= ($profile_department->status ?? '') === 'ACTIVE' ? 'on' : 'off' ?> mr-1"></i>
                                        <?= esc($nextDeptStatus === 'ACTIVE' ? 'Activate' : 'Deactivate') ?>
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#" id="deleteLinkedDepartmentBtn">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="profile-department-card__body">
                        <div class="dept-info-area">
                            <span class="dept-info-label">Officer in Charge</span>
                            <?php $profileDeptHead = trim((string) ($profile_department->head ?? '')); ?>
                            <div class="dept-officer-value <?= $profileDeptHead === '' ? 'is-unassigned' : '' ?>"
                                id="profileDeptHeadCell">
                                <?= esc($profileDeptHead !== '' ? $profileDeptHead : 'Unassigned') ?>
                            </div>
                            <a href="#" class="dept-assign-cta <?= $profileDeptHead !== '' ? 'd-none' : '' ?>"
                                id="profileDeptAssignCta">
                                <i class="fas fa-plus" aria-hidden="true"></i>
                                Assign Officer
                            </a>
                        </div>

                        <div class="dept-info-area">
                            <span class="dept-info-label">Status</span>
                            <div id="profileDeptStatusCell">
                                <?php
                                $deptStatus = $profile_department->status ?? '';
                                $deptStatusClass = $deptStatus === 'ACTIVE' ? 'is-active' : ($deptStatus === 'INACTIVE' ? 'is-inactive' : '');
                                ?>
                                <button type="button" class="dept-status-toggle profile-dept-status-action"
                                    data-current-status="<?= esc($deptStatus) ?>" aria-label="Toggle department status">
                                    <span class="dept-status-switch <?= esc($deptStatusClass) ?>">
                                        <span class="dept-status-switch__track" aria-hidden="true">
                                            <span class="dept-status-switch__thumb"></span>
                                        </span>
                                        <span
                                            class="dept-status-switch__label"><?= esc($deptStatus ? ucfirst(strtolower($deptStatus)) : 'Unknown') ?></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- End Edit Department Tab Pane -->
    <?php endif; ?>
</div> <!-- End Tab Content -->

<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="profilePasswordForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="profileOldPassword" class="form-label">Old Password</label>
                        <input type="password" class="form-control" id="profileOldPassword" name="oldPassword" required>
                    </div>

                    <div class="mb-3">
                        <label for="profileNewPassword" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="profileNewPassword" name="newPassword"
                            minlength="8" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Save Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if (!empty($profile_department)): ?>
    <div class="modal fade" id="editLinkedDepartmentModal" tabindex="-1" aria-labelledby="editLinkedDepartmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="profileDepartmentForm" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editLinkedDepartmentModalLabel">Edit Department</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="profileDeptName" class="form-label">Dept. Name</label>
                            <input type="text" class="form-control" id="profileDeptName" name="deptName"
                                value="<?= esc($profile_department->dept_name ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="profileDeptHead" class="form-label">Officer in Charge</label>
                            <input type="text" class="form-control" id="profileDeptHead" name="head"
                                value="<?= esc($profile_department->head ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="profileDeptStatus" class="form-label">Status</label>
                            <select class="form-select" id="profileDeptStatus" name="status" required>
                                <option value="ACTIVE" <?= ($profile_department->status ?? '') === 'ACTIVE' ? 'selected' : '' ?>>Active</option>
                                <option value="INACTIVE" <?= ($profile_department->status ?? '') === 'INACTIVE' ? 'selected' : '' ?>>Inactive</option>
                                <option value="ARCHIVED" <?= ($profile_department->status ?? '') === 'ARCHIVED' ? 'selected' : '' ?>>Archived</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="profileDeptAboutEditor" class="form-label">About</label>
                            <div id="profileDeptAboutEditor" style="height: 120px;"><?= $profile_department->about ?? '' ?>
                            </div>
                            <input type="hidden" id="profileDeptAbout" name="about"
                                value="<?= esc($profile_department->about ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="profileDeptContactEditor" class="form-label">Contact Information</label>
                            <div id="profileDeptContactEditor" style="height: 120px;">
                                <?= $profile_department->contact ?? '' ?>
                            </div>
                            <input type="hidden" id="profileDeptContact" name="contact"
                                value="<?= esc($profile_department->contact ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="profileDeptMissionEditor" class="form-label">Mission</label>
                            <div id="profileDeptMissionEditor" style="height: 120px;">
                                <?= $profile_department->mission ?? '' ?>
                            </div>
                            <input type="hidden" id="profileDeptMission" name="mission"
                                value="<?= esc($profile_department->mission ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="profileDeptVisionEditor" class="form-label">Vision</label>
                            <div id="profileDeptVisionEditor" style="height: 120px;">
                                <?= $profile_department->vision ?? '' ?>
                            </div>
                            <input type="hidden" id="profileDeptVision" name="vision"
                                value="<?= esc($profile_department->vision ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="profileDeptPolicyEditor" class="form-label">Quality Policy</label>
                            <div id="profileDeptPolicyEditor" style="height: 120px;">
                                <?= $profile_department->quality_policy ?? '' ?>
                            </div>
                            <input type="hidden" id="profileDeptPolicy" name="qualityPolicy"
                                value="<?= esc($profile_department->quality_policy ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="profileDeptLogo" class="form-label">Logo</label>
                            <input type="file" class="form-control" id="profileDeptLogo" name="deptLogo" accept="image/*">
                            <div class="mt-2" id="profileDeptLogoPreview">
                                <?php if (!empty($profile_department->img_logo)): ?>
                                    <img src="<?= site_url('admin/image/DEPT/' . $profile_department->img_logo) ?>"
                                        alt="Department logo" style="max-width: 120px; max-height: 120px; object-fit: contain;">
                                <?php else: ?>
                                    <small class="text-muted">No logo uploaded.</small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="profileDeptOrgChart" class="form-label">Organizational Chart</label>
                            <input type="file" class="form-control" id="profileDeptOrgChart" name="deptOrgChart"
                                accept="image/*">
                            <div class="mt-2" id="profileDeptOrgChartPreview">
                                <?php if (!empty($profile_department->org_chart_img)): ?>
                                    <img src="<?= site_url('admin/image/DEPT/' . $profile_department->org_chart_img) ?>"
                                        alt="Organizational chart"
                                        style="max-width: 160px; max-height: 160px; object-fit: contain;">
                                <?php else: ?>
                                    <small class="text-muted">No organizational chart uploaded.</small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Save Department
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>