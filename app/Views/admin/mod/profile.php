<style>
    /* Responsive height for profile picture card */
    @media (min-width: 992px) {
        .h-lg-100 {
            height: 100% !important;
        }
    }
    
    /* Quill Custom Styling to align with deep green theme */
    .ql-toolbar.ql-snow {
        border-top-left-radius: 6px;
        border-top-right-radius: 6px;
        border: 1px solid #bfe7cf !important;
        background-color: #f6fff9 !important;
    }
    .ql-container.ql-snow {
        border-bottom-left-radius: 6px;
        border-bottom-right-radius: 6px;
        border: 1px solid #bfe7cf !important;
        font-family: inherit;
        font-size: 0.95rem;
    }
    .quill-editor-wrapper {
        padding: 0.5rem 0;
    }
    .quill-divider {
        margin: 2rem 0;
        border: 0;
        border-top: 1px solid #1b4d3e;
        opacity: 0.15;
    }

    /* Hover pencil edit overlay for circular logo uploads */
    .logo-upload-container {
        position: relative;
        width: 200px;
        height: 200px;
        cursor: pointer;
        border-radius: 50%;
        overflow: hidden;
        display: inline-block;
    }
    .profile-pic-upload-container {
        position: relative;
        width: 160px;
        height: 160px;
        cursor: pointer;
        border-radius: 50%;
        overflow: hidden;
        display: inline-block;
    }
    .logo-upload-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s ease-in-out;
        border-radius: 50%;
    }
    .logo-upload-container:hover .logo-upload-overlay,
    .profile-pic-upload-container:hover .logo-upload-overlay {
        opacity: 1;
    }
    .logo-upload-overlay i {
        color: #ffffff;
        font-size: 1.75rem;
    }
    /* Wrapper for circular preview + X clear button */
    .logo-preview-wrapper {
        position: relative;
        display: inline-block;
    }
    .logo-clear-btn {
        display: none;
        position: absolute;
        top: 4px;
        right: 4px;
        width: 26px;
        height: 26px;
        padding: 0;
        background: #dc3545;
        border: 2px solid #fff;
        border-radius: 50%;
        color: #fff;
        font-size: 13px;
        line-height: 1;
        cursor: pointer;
        z-index: 20;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        transition: background 0.15s, transform 0.15s;
    }
    .logo-clear-btn:hover {
        background: #b02a37;
        transform: scale(1.1);
    }
    .logo-clear-btn.active {
        display: flex;
    }</style>
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
    <?php if (!empty($profile_barangay)): ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link font-weight-bold" id="edit-barangay-tab" data-bs-toggle="tab"
                data-bs-target="#edit-barangay" type="button" role="tab" aria-controls="edit-barangay"
                aria-selected="false">
                Edit Barangay
            </button>
        </li>
    <?php endif; ?>
</ul>

<div class="tab-content" id="profileTabsContent">
    <div class="tab-pane fade show active" id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab">
        <div class="card shadow mb-4">
            <div class="card-body p-3 p-md-4">
                <div class="row">
                    <div class="col-lg-7">
                        <form id="profileDetailsForm">
                            <div class="mb-3">
                                <label for="profileFullName" class="form-label">Full Name</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="profileFullName" name="fullName"
                                        value="<?= esc(trim(implode(' ', array_filter([$user->fname ?? '', $user->mname ?? '', $user->lname ?? '', $user->suffix ?? ''], 'strlen')))) ?>"
                                        readonly required>
                                    <button class="btn btn-outline-secondary" type="button" id="btnEditName" data-bs-toggle="modal" data-bs-target="#editNameModal">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                </div>
                                <input type="hidden" id="profileFname" name="fname" value="<?= esc($user->fname ?? '') ?>">
                                <input type="hidden" id="profileMname" name="mname" value="<?= esc($user->mname ?? '') ?>">
                                <input type="hidden" id="profileLname" name="lname" value="<?= esc($user->lname ?? '') ?>">
                                <input type="hidden" id="profileSuffix" name="suffix" value="<?= esc($user->suffix ?? '') ?>">
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
                                <label for="profileDepartment" class="form-label">
                                    <?= !empty($profile_barangay) ? 'Current Barangay' : 'Current Department' ?>
                                </label>
                                <input type="text" class="form-control" id="profileDepartment" name="department"
                                    value="<?= esc($current_department ?? '') ?>" placeholder="No department assigned"
                                    readonly>
                            </div>

                            <div class="d-flex flex-column flex-sm-row">
                                <button type="submit" class="btn btn-success mb-2 mb-sm-0 mr-sm-2">
                                    <i class="fas fa-save mr-1"></i> Save Profile
                                </button>
                                <button type="button" class="btn btn-outline-success" id="showPasswordFormBtn">
                                    <i class="fas fa-key mr-1"></i> Change Password
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-5 mt-4 mt-lg-0">
                        <div class="border rounded p-4 bg-light h-lg-100 text-center d-flex flex-column align-items-center justify-content-center">
                        <div class="logo-preview-wrapper mb-3 mt-3">
                            <div class="<?= (($user->user_lvl ?? '') !== 'VIEWER') ? 'profile-pic-upload-container' : '' ?>" id="profilePictureWrapper" title="<?= (($user->user_lvl ?? '') !== 'VIEWER') ? 'Click to upload picture' : '' ?>">
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
                                <?php if (($user->user_lvl ?? '') !== 'VIEWER'): ?>
                                <div class="logo-upload-overlay">
                                    <i class="fas fa-pencil-alt"></i>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php if (($user->user_lvl ?? '') !== 'VIEWER'): ?>
                            <button type="button" class="logo-clear-btn" id="profilePictureClearBtn" title="Remove selected picture" aria-label="Clear selection">
                                <i class="fas fa-times"></i>
                            </button>
                            <?php endif; ?>
                        </div>

                            <h2 class="h5 text-gray-800 mb-3">Profile Picture</h2>
                            <form id="profilePictureForm" enctype="multipart/form-data" class="w-100">
                                <div class="text-left mb-0">
                                    <label for="profileImage" class="form-label">Upload Image</label>
                                    <input type="file" class="form-control" id="profileImage" name="profileImage"
                                        accept="image/png,image/jpeg,image/webp" required>
                                    <small class="text-muted">PNG, JPG, JPEG, or WEBP. Max size 2 MB.</small>
                                </div>

                                <div class="text-center mt-3">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-upload mr-1"></i> Save Picture
                                    </button>
                                </div>
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
            <div class="card shadow mb-4">
                <div class="card-body p-3 p-md-4">
                    <form id="profileDepartmentForm" enctype="multipart/form-data">
                        <div class="row">
                            <!-- Left column for the core text inputs -->
                            <div class="col-lg-7">
                                <div class="mb-3">
                                    <label for="profileDeptName" class="form-label font-weight-bold">Dept. Name</label>
                                    <input type="text" class="form-control" id="profileDeptName" name="deptName"
                                        value="<?= esc($profile_department->dept_name ?? '') ?>" required
                                        <?= (($user->user_lvl ?? '') === 'VIEWER') ? 'readonly' : '' ?>>
                                </div>

                                <div class="mb-3">
                                    <label for="profileDeptHead" class="form-label font-weight-bold">Officer in Charge</label>
                                    <input type="text" class="form-control" id="profileDeptHead" name="head"
                                        value="<?= esc($profile_department->head ?? '') ?>"
                                        <?= (($user->user_lvl ?? '') === 'VIEWER') ? 'readonly' : '' ?>>
                                </div>

                                <input type="hidden" id="profileDeptStatus" name="status" value="<?= esc(strtoupper(trim((string) ($profile_department->status ?? 'ACTIVE')))) ?>">

                                <?php if (($user->user_lvl ?? '') !== 'VIEWER'): ?>
                                <div class="d-flex flex-column flex-sm-row mb-4">
                                    <button type="submit" class="btn btn-success mb-2 mb-sm-0 mr-sm-2">
                                        <i class="fas fa-save mr-1"></i> Save Department
                                    </button>
                                    <?php if (in_array($user->user_lvl ?? '', ['DEVELOPER', 'SUPERADMIN'])): ?>
                                        <a class="btn btn-outline-danger" href="#" id="deleteLinkedDepartmentBtn">
                                            <i class="fas fa-trash mr-1"></i> Delete
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Right column for logo upload and preview -->
                            <div class="col-lg-5 mt-4 mt-lg-0">
                                <div class="border rounded p-4 bg-light h-lg-100 text-center d-flex flex-column align-items-center justify-content-center">
                                     <div class="logo-preview-wrapper mb-3 mt-3">
                                         <div class="<?= (($user->user_lvl ?? '') !== 'VIEWER') ? 'logo-upload-container' : '' ?>" id="profileDeptLogoWrapper" title="<?= (($user->user_lvl ?? '') !== 'VIEWER') ? 'Click to upload logo' : '' ?>">
                                             <?php if (!empty($profile_department->img_logo)): ?>
                                                 <img id="profileDeptLogoCell" src="<?= site_url('admin/image/DEPT/' . $profile_department->img_logo) ?>"
                                                     alt="Department logo" class="rounded-circle shadow-sm border p-1 bg-white"
                                                     style="width: 200px; height: 200px; object-fit: cover;">
                                                 <div id="profileDeptLogoEmpty" class="rounded-circle shadow-sm bg-light border d-none"
                                                     style="width: 200px; height: 200px;">
                                                 </div>
                                             <?php else: ?>
                                                 <div id="profileDeptLogoEmpty" class="rounded-circle shadow-sm bg-light border"
                                                     style="width: 200px; height: 200px;">
                                                 </div>
                                                 <img id="profileDeptLogoCell" src="" alt="Department logo" class="rounded-circle shadow-sm border p-1 bg-white d-none"
                                                     style="width: 200px; height: 200px; object-fit: cover;">
                                             <?php endif; ?>
                                             <?php if (($user->user_lvl ?? '') !== 'VIEWER'): ?>
                                             <div class="logo-upload-overlay">
                                                 <i class="fas fa-pencil-alt"></i>
                                             </div>
                                             <?php endif; ?>
                                         </div>
                                         <?php if (($user->user_lvl ?? '') !== 'VIEWER'): ?>
                                         <button type="button" class="logo-clear-btn" id="profileDeptLogoClearBtn" title="Remove selected logo" aria-label="Clear selection">
                                             <i class="fas fa-times"></i>
                                         </button>
                                         <?php endif; ?>
                                     </div>
                                     
                                     <?php if (($user->user_lvl ?? '') !== 'VIEWER'): ?>
                                     <div class="text-left w-100 mb-0">
                                         <label for="profileDeptLogo" class="form-label">Upload New Logo</label>
                                         <input type="file" class="form-control mb-3" id="profileDeptLogo" name="deptLogo" accept="image/*">
                                         <div class="text-center mt-3">
                                             <button type="button" id="profileDeptLogoSaveBtn" class="btn btn-success">
                                                 <i class="fas fa-save mr-1"></i> Save Picture
                                             </button>
                                         </div>
                                     </div>
                                     <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Rich Text Editors for About, Contact, Mission, Vision, Quality Policy -->
                        <div class="row">
                            <div class="col-12"><hr class="quill-divider mt-2 mb-4"></div>
                            
                            <div class="col-12 quill-editor-wrapper">
                                <label for="profileDeptAboutEditor" class="form-label font-weight-bold">About</label>
                                <div id="profileDeptAboutEditor" style="height: 150px;"><?= $profile_department->about ?? '' ?></div>
                                <input type="hidden" id="profileDeptAbout" name="about" value="<?= esc($profile_department->about ?? '') ?>">
                            </div>

                            <div class="col-12"><hr class="quill-divider"></div>

                            <div class="col-12 quill-editor-wrapper">
                                <label for="profileDeptContactEditor" class="form-label font-weight-bold">Contact Information</label>
                                <div id="profileDeptContactEditor" style="height: 150px;"><?= $profile_department->contact ?? '' ?></div>
                                <input type="hidden" id="profileDeptContact" name="contact" value="<?= esc($profile_department->contact ?? '') ?>">
                            </div>

                            <div class="col-12"><hr class="quill-divider"></div>

                            <div class="col-12 quill-editor-wrapper">
                                <label for="profileDeptMissionEditor" class="form-label font-weight-bold">Mission</label>
                                <div id="profileDeptMissionEditor" style="height: 150px;"><?= $profile_department->mission ?? '' ?></div>
                                <input type="hidden" id="profileDeptMission" name="mission" value="<?= esc($profile_department->mission ?? '') ?>">
                            </div>

                            <div class="col-12"><hr class="quill-divider"></div>

                            <div class="col-12 quill-editor-wrapper">
                                <label for="profileDeptVisionEditor" class="form-label font-weight-bold">Vision</label>
                                <div id="profileDeptVisionEditor" style="height: 150px;"><?= $profile_department->vision ?? '' ?></div>
                                <input type="hidden" id="profileDeptVision" name="vision" value="<?= esc($profile_department->vision ?? '') ?>">
                            </div>

                            <div class="col-12"><hr class="quill-divider"></div>

                            <div class="col-12 quill-editor-wrapper">
                                <label for="profileDeptPolicyEditor" class="form-label font-weight-bold">Quality Policy</label>
                                <div id="profileDeptPolicyEditor" style="height: 150px;"><?= $profile_department->quality_policy ?? '' ?></div>
                                <input type="hidden" id="profileDeptPolicy" name="qualityPolicy" value="<?= esc($profile_department->quality_policy ?? '') ?>">
                            </div>

                            <div class="col-12"><hr class="quill-divider"></div>

                            <div class="col-12 quill-editor-wrapper mb-4">
                                <label for="profileDeptOrgChart" class="form-label font-weight-bold">Organizational Chart</label>
                                <?php if (($user->user_lvl ?? '') !== 'VIEWER'): ?>
                                    <input type="file" class="form-control mb-3" id="profileDeptOrgChart" name="deptOrgChart" accept="image/*">
                                <?php endif; ?>
                                <div id="profileDeptOrgChartPreview" class="border rounded p-3 bg-light text-center d-inline-block">
                                    <?php if (!empty($profile_department->org_chart_img)): ?>
                                        <img src="<?= site_url('admin/image/DEPT/' . $profile_department->org_chart_img) ?>"
                                            alt="Organizational chart" class="img-fluid" style="max-height: 250px;">
                                    <?php else: ?>
                                        <small class="text-muted d-block py-3">No organizational chart uploaded.</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (($user->user_lvl ?? '') !== 'VIEWER'): ?>
                        <div class="d-flex flex-column flex-sm-row">
                            <button type="submit" class="btn btn-success mb-2 mb-sm-0 mr-sm-2">
                                <i class="fas fa-save mr-1"></i> Save Changes
                            </button>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div> <!-- End Edit Department Tab Pane -->
    <?php endif; ?>

    <?php if (!empty($profile_barangay)): ?>
        <div class="tab-pane fade" id="edit-barangay" role="tabpanel" aria-labelledby="edit-barangay-tab">
            <div class="card shadow mb-4">
                <div class="card-body p-3 p-md-4">
                    <form id="profileBarangayForm" enctype="multipart/form-data">
                        <input type="hidden" id="profileBrgyId" name="id" value="<?= esc($profile_barangay->ID ?? '') ?>">
                        
                        <div class="row">
                            <!-- Left column for the core text inputs -->
                            <div class="col-lg-7">
                                <div class="mb-3">
                                    <label for="profileBrgyName" class="form-label font-weight-bold">Barangay Name</label>
                                    <input type="text" class="form-control" id="profileBrgyName" name="editBrgy"
                                        value="<?= esc($profile_barangay->brgy_name ?? '') ?>" required
                                        <?= (($user->user_lvl ?? '') === 'VIEWER') ? 'readonly' : '' ?>>
                                </div>

                                <div class="mb-3">
                                    <label for="profileBrgyCaptain" class="form-label font-weight-bold">Barangay Captain</label>
                                    <input type="text" class="form-control" id="profileBrgyCaptain" name="editCapt"
                                        value="<?= esc($profile_barangay->brngy_capt ?? '') ?>" required
                                        <?= (($user->user_lvl ?? '') === 'VIEWER') ? 'readonly' : '' ?>>
                                </div>

                                <input type="hidden" id="profileBrgyStatus" name="status" value="<?= esc(strtoupper(trim((string) ($profile_barangay->status ?? 'ACTIVE')))) ?>">

                                <?php if (($user->user_lvl ?? '') !== 'VIEWER'): ?>
                                <div class="d-flex flex-column flex-sm-row mb-4">
                                    <button type="submit" class="btn btn-success mb-2 mb-sm-0 mr-sm-2">
                                        <i class="fas fa-save mr-1"></i> Save Barangay
                                    </button>
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Right column for logo upload and preview -->
                            <div class="col-lg-5 mt-4 mt-lg-0">
                                <div class="border rounded p-4 bg-light h-lg-100 text-center d-flex flex-column align-items-center justify-content-center">
                                     <div class="logo-preview-wrapper mb-3 mt-3">
                                         <div class="<?= (($user->user_lvl ?? '') !== 'VIEWER') ? 'logo-upload-container' : '' ?>" id="profileBrgyLogoWrapper" title="<?= (($user->user_lvl ?? '') !== 'VIEWER') ? 'Click to upload logo' : '' ?>">
                                             <?php if (!empty($profile_barangay->img_logo)): ?>
                                                 <img id="profileBrgyLogoCell" src="<?= site_url('admin/image/BARANGAY/' . $profile_barangay->img_logo) ?>"
                                                     alt="Barangay logo" class="rounded-circle shadow-sm border p-1 bg-white"
                                                     style="width: 200px; height: 200px; object-fit: cover;">
                                                 <div id="profileBrgyLogoEmpty" class="rounded-circle shadow-sm bg-light border d-none"
                                                     style="width: 200px; height: 200px;">
                                                 </div>
                                             <?php else: ?>
                                                 <div id="profileBrgyLogoEmpty" class="rounded-circle shadow-sm bg-light border"
                                                     style="width: 200px; height: 200px;">
                                                 </div>
                                                 <img id="profileBrgyLogoCell" src="" alt="Barangay logo" class="rounded-circle shadow-sm border p-1 bg-white d-none"
                                                     style="width: 200px; height: 200px; object-fit: cover;">
                                             <?php endif; ?>
                                             <?php if (($user->user_lvl ?? '') !== 'VIEWER'): ?>
                                             <div class="logo-upload-overlay">
                                                 <i class="fas fa-pencil-alt"></i>
                                             </div>
                                             <?php endif; ?>
                                         </div>
                                         <?php if (($user->user_lvl ?? '') !== 'VIEWER'): ?>
                                         <button type="button" class="logo-clear-btn" id="profileBrgyLogoClearBtn" title="Remove selected logo" aria-label="Clear selection">
                                             <i class="fas fa-times"></i>
                                         </button>
                                         <?php endif; ?>
                                     </div>
                                     
                                     <?php if (($user->user_lvl ?? '') !== 'VIEWER'): ?>
                                     <div class="text-left w-100 mb-0">
                                         <label for="profileBrgyLogo" class="form-label">Upload New Logo</label>
                                         <input type="file" class="form-control mb-3" id="profileBrgyLogo" name="editbrgyImg" accept="image/*">
                                         <div class="text-center mt-3">
                                             <button type="button" id="profileBrgyLogoSaveBtn" class="btn btn-success">
                                                 <i class="fas fa-save mr-1"></i> Save Picture
                                             </button>
                                         </div>
                                     </div>
                                     <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Rich Text Editors for About, Mission, Vision, Contact, Staff -->
                        <div class="row">
                            <div class="col-12"><hr class="quill-divider mt-2 mb-4"></div>
                            
                            <div class="col-12 quill-editor-wrapper">
                                <label class="form-label font-weight-bold">About</label>
                                <div id="profileBrgyAboutEditor" style="height: 180px;">
                                    <?= $profile_barangay->about ?? '' ?>
                                </div>
                                <input type="hidden" id="profileBrgyAbout" name="editAbout"
                                    value="<?= esc($profile_barangay->about ?? '') ?>">
                            </div>

                            <div class="col-12"><hr class="quill-divider"></div>

                            <div class="col-12 quill-editor-wrapper">
                                <label class="form-label font-weight-bold">Mission</label>
                                <div id="profileBrgyMissionEditor" style="height: 150px;">
                                    <?= $profile_barangay->mission ?? '' ?>
                                </div>
                                <input type="hidden" id="profileBrgyMission" name="editMission"
                                    value="<?= esc($profile_barangay->mission ?? '') ?>">
                            </div>

                            <div class="col-12"><hr class="quill-divider"></div>

                            <div class="col-12 quill-editor-wrapper">
                                <label class="form-label font-weight-bold">Vision</label>
                                <div id="profileBrgyVisionEditor" style="height: 150px;">
                                    <?= $profile_barangay->vision ?? '' ?>
                                </div>
                                <input type="hidden" id="profileBrgyVision" name="editVision"
                                    value="<?= esc($profile_barangay->vision ?? '') ?>">
                            </div>

                            <div class="col-12"><hr class="quill-divider"></div>

                            <div class="col-12 quill-editor-wrapper">
                                <label class="form-label font-weight-bold">Contact Information</label>
                                <div id="profileBrgyContactEditor" style="height: 150px;">
                                    <?= $profile_barangay->contact ?? '' ?>
                                </div>
                                <input type="hidden" id="profileBrgyContact" name="editContact"
                                    value="<?= esc($profile_barangay->contact ?? '') ?>">
                            </div>

                            <div class="col-12"><hr class="quill-divider"></div>

                            <div class="col-12 quill-editor-wrapper mb-4">
                                <label class="form-label font-weight-bold">Barangay Staff</label>
                                <div id="profileBrgyStaffEditor" style="height: 180px;">
                                    <?= $profile_barangay->barangay_staff ?? '' ?>
                                </div>
                                <input type="hidden" id="profileBrgyStaff" name="editStaff"
                                    value="<?= esc($profile_barangay->barangay_staff ?? '') ?>">
                            </div>
                        </div>
                        
                        <?php if (($user->user_lvl ?? '') !== 'VIEWER'): ?>
                        <div class="d-flex flex-column flex-sm-row">
                            <button id="btnProfileBarangay" type="submit" class="btn btn-success mb-2 mb-sm-0 mr-sm-2">
                                <i class="fas fa-save mr-1"></i> Save Changes
                            </button>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div> <!-- End Tab Content -->

<?php if (!empty($profile_department) || !empty($profile_barangay)): ?>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<?php endif; ?>

<?php /* Modals editLinkedBarangayModal and editLinkedDepartmentModal removed — forms are inline */ ?>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">
                    <i class="fas fa-key mr-2"></i>Change Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="profilePasswordForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="profileOldPassword" class="form-label">Current Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="profileOldPassword" name="old_password" required autocomplete="current-password" placeholder="Enter current password">
                            <button class="btn btn-outline-secondary" type="button" id="toggleOldPassword" tabindex="-1">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="profileNewPassword" class="form-label">New Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="profileNewPassword" name="new_password" required autocomplete="new-password" placeholder="Enter new password">
                            <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword" tabindex="-1">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="profileConfirmPassword" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="profileConfirmPassword" name="confirm_password" required autocomplete="new-password" placeholder="Repeat new password">
                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword" tabindex="-1">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div id="passwordMatchFeedback" class="invalid-feedback">Passwords do not match.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-1"></i> Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Name Modal -->
<div class="modal fade" id="editNameModal" tabindex="-1" aria-labelledby="editNameModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editNameModalLabel">Edit Name</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="dlgFirstName" class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="dlgFirstName" required>
                    <div class="invalid-feedback">First name is required.</div>
                </div>
                <div class="mb-3">
                    <label for="dlgMiddleName" class="form-label">Middle Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="dlgMiddleName" required>
                    <div class="invalid-feedback">Middle name is required.</div>
                </div>
                <div class="mb-3">
                    <label for="dlgLastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="dlgLastName" required>
                    <div class="invalid-feedback">Last name is required.</div>
                </div>
                <div class="mb-3">
                    <label for="dlgSuffix" class="form-label">Suffix <span class="text-muted">(Optional)</span></label>
                    <select class="form-select" id="dlgSuffix">
                        <option value="">— None —</option>
                        <option value="Jr.">Jr. (Junior)</option>
                        <option value="Sr.">Sr. (Senior)</option>
                        <option value="II">II</option>
                        <option value="III">III</option>
                        <option value="IV">IV</option>
                        <option value="V">V</option>
                        <option value="VI">VI</option>
                        <option value="VII">VII</option>
                        <option value="VIII">VIII</option>
                        <option value="IX">IX</option>
                        <option value="X">X</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="btnSaveNameDlg">
                    <i class="fas fa-save mr-1"></i> Apply
                </button>
            </div>
        </div>
    </div>
</div>
