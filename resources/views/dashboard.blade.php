<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frame Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background:#f5f6f8; }
        .sidebar {
            min-height:100vh;
            background:#212529;
            position:sticky;
            top:0;
        }
        .sidebar-brand {
            color:#fff;
            font-size:20px;
            font-weight:600;
            padding:24px;
        }
        .sidebar a {
            display:block;
            color:#adb5bd;
            text-decoration:none;
            padding:12px 24px;
            cursor:pointer;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background:#343a40;
            color:#fff;
        }
        .main-content { padding:30px; }
        .section { display:none; }
        .section.active { display:block; }

        .frame-card, .session-card {
            border:0;
            border-radius:12px;
            transition:.2s;
        }
        .frame-card:hover, .session-card:hover { transform:translateY(-2px); }

        .frame-preview {
            height:220px;
            object-fit:contain;
            background:#f8f9fa;
            width:100%;
        }
        .result-image {
            width:100%;
            height:180px;
            object-fit:contain;
            background:#f8f9fa;
            border-radius:8px;
        }
        .empty-state {
            padding:80px 20px;
            text-align:center;
        }
        .text-white{
            color: #d4d4d4
        }
    </style>
</head>

<body>
<div class="container-fluid">
    <div class="row">

        {{-- SIDEBAR --}}
        <aside class="col-md-2 col-lg-2 px-0 sidebar">
            <div class="sidebar-brand">
                Frame Dashboard
                <div class="small text-secondary">Admin Panel</div>
            </div>

            <nav>
                <a class="text-white" id="navFrames" class="active" onclick="showSection('frames')">
                    🖼️ Frame Templates
                </a>

                <a class="text-white" id="navSessions" onclick="showSection('sessions')">
                    📷 Sesi Foto
                </a>

                <a class="text-white" id="navSettings" onclick="showSection('settings')">
                    ⚙️ Pengaturan Website
                </a>
            </nav>
        </aside>

        {{-- MAIN --}}
        <main class="col-md-10 col-lg-10">
            <div class="main-content">
                <div id="alert" class="alert d-none"></div>
                {{-- =====================================================
                     FRAME TEMPLATES
                ====================================================== --}}
                <section id="framesSection" class="section active">

                    <div class="d-flex justify-content-between align-items-center py-2 mb-4">
                        <div>
                            <h2 class="mb-1">Frame Templates</h2>
                            <p class="text-muted mb-0">
                                Kelola frame yang tersedia di aplikasi.
                            </p>
                        </div>

                        <button class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#createModal">
                            + Tambah Frame
                        </button>
                    </div>

                    <div id="frameLoading" class="text-center py-5">
                        <div class="spinner-border text-primary"></div>
                        <p class="mt-3 text-muted">Memuat frame...</p>
                    </div>

                    <div id="frameList" class="row g-4 d-none"></div>
                </section>

                {{-- =====================================================
                     SESI FOTO
                ====================================================== --}}
                <section id="sessionsSection" class="section">

                    <div class="mb-4">
                        <h2 class="mb-1">Sesi Foto</h2>
                        <p class="text-muted mb-0">
                            Lihat dan kelola hasil foto dari setiap sesi.
                        </p>
                    </div>

                    <div id="sessionLoading" class="text-center py-5">
                        <div class="spinner-border text-primary"></div>
                        <div class="mt-2 text-muted">Memuat sesi foto...</div>
                    </div>

                    <div id="sessionList" class="row g-4"></div>
                </section>


                {{-- =====================================================
                     PENGATURAN WEBSITE
                ====================================================== --}}
              <section id="settingsSection" class="section">
                    <div class="mb-4">
                        <h2 class="mb-1">Pengaturan Website</h2>
                        <p class="text-muted mb-0">
                            Kelola nama website, informasi, harga foto, countdown, dan warna
                            tampilan.
                        </p>
                    </div>
                    {{-- ALERT --}}
                    <div id="settingsAlert" class="alert d-none" role="alert"></div>
                    <form
                        id="settingsForm"
                        class="card border-0 shadow-sm"
                        action="{{ route('settings.update') }}"
                        method="POST"
                    >
                            @csrf @method('PUT')
                            <div class="card-body p-4">
                                <h5 class="mb-3">Informasi Website</h5>
                                <div class="row g-3">
                                    {{-- EYEBROW --}}
                                    <div class="col-md-6">
                                        <label class="form-label"> Eyebrow </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="eyebrow"
                                            value="{{ old('eyebrow', $setting->eyebrow ?? '') }}"
                                            placeholder="Contoh: Photo Booth"
                                        />
                                        <small class="text-danger error-eyebrow"></small>
                                    </div>
                                    {{-- WEBSITE NAME --}}
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Nama Website <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="website_name"
                                            value="{{ old('website_name', $setting->website_name ?? '') }}"
                                            placeholder="Contoh: Speace Photo"
                                            required
                                        />
                                        <small class="text-danger error-website_name"></small>
                                    </div>
                                    {{-- TAGLINE --}}
                                    <div class="col-12">
                                        <label class="form-label"> Tagline </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="tagline"
                                            value="{{ old('tagline', $setting->tagline ?? '') }}"
                                            placeholder="Contoh: Capture Your Best Moment"
                                        />
                                        <small class="text-danger error-tagline"></small>
                                    </div>
                                    {{-- DESCRIPTION --}}
                                    <div class="col-12">
                                        <label class="form-label"> Deskripsi Website </label>
                                        <textarea
                                            class="form-control"
                                            name="website_description"
                                            rows="4"
                                            placeholder="Masukkan deskripsi website"
                                        >
                                        {{ old('website_description', $setting->website_description ?? '') }}
                                        </textarea
                                    >
                                    <small
                                        class="text-danger error-website_description"
                                    ></small>
                                </div>
                            </div>
                            <hr class="my-4" />
                   
                            <h5 class="mb-3">Pengaturan Foto</h5>
                            <div class="row g-3">
                                {{-- PHOTO PRICE --}}
                                <div class="col-md-3">
                                    <label class="form-label"> Harga Foto </label>
                                    <input
                                        type="number"
                                        class="form-control"
                                        name="photo_price"
                                        min="0"
                                        value="{{ old('photo_price', $setting->photo_price ?? 35000) }}"
                                        required
                                    />
                                    <small class="text-muted"> Masukkan angka tanpa Rp. </small>
                                    <small
                                        class="text-danger d-block error-photo_price"
                                    ></small>
                                </div>
                                {{-- PHOTO COUNT --}}
                                <div class="col-md-3">
                                    <label class="form-label"> Jumlah Foto </label>
                                    <input
                                        type="number"
                                        class="form-control"
                                        name="photo_count"
                                        min="1"
                                        value="{{ old('photo_count', $setting->photo_count ?? 6) }}"
                                        required
                                    />
                                    <small class="text-danger error-photo_count"></small>
                                </div>
                                {{-- COUNTDOWN --}}
                                <div class="col-md-3">
                                    <label class="form-label"> Countdown (detik) </label>
                                    <input
                                        type="number"
                                        class="form-control"
                                        name="countdown"
                                        min="0"
                                        value="{{ old('countdown', $setting->countdown ?? 5) }}"
                                        required
                                    />
                                    <small class="text-danger error-countdown"></small>
                                </div>
                                {{-- MAX TIME --}}
                                <div class="col-md-3">
                                    <label class="form-label"> Maksimal Waktu (detik) </label>
                                    <input
                                        type="number"
                                        class="form-control"
                                        name="max_time"
                                        min="1"
                                        value="{{ old('max_time', $setting->max_time ?? 300) }}"
                                        required
                                    />
                                    <small class="text-danger error-max_time"></small>
                                </div>
                            </div>
                            <hr class="my-4" />
                         
                            <h5 class="mb-3">Warna Website</h5>
                            <div class="row g-3">
                                {{-- PRIMARY --}}
                                <div class="col-md-4 col-lg-2">
                                    <label class="form-label"> Primary </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="primary_color"
                                        value="{{ old('primary_color', $setting->primary_color ?? '#B89B5E') }}"
                                        placeholder="#000000"
                                        required
                                    />
                                    <small class="text-danger error-primary_color"></small>
                                </div>
                                {{-- SECONDARY --}}
                                <div class="col-md-4 col-lg-2">
                                    <label class="form-label"> Secondary </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="secondary_color"
                                        value="{{ old('secondary_color', $setting->secondary_color ?? '#1E1E1E') }}"
                                        placeholder="#000000"
                                        required
                                    />
                                    <small class="text-danger error-secondary_color"></small>
                                </div>
                                {{-- BACKGROUND --}}
                                <div class="col-md-4 col-lg-2">
                                    <label class="form-label"> Background </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="background_color"
                                        value="{{ old('background_color', $setting->background_color ?? '#F5F0E8') }}"
                                        placeholder="#FFFFFF"
                                        required
                                    />
                                    <small class="text-danger error-background_color"></small>
                                </div>
                                {{-- SURFACE --}}
                                <div class="col-md-4 col-lg-2">
                                    <label class="form-label"> Surface </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="surface_color"
                                        value="{{ old('surface_color', $setting->surface_color ?? '#FFFFFF') }}"
                                        placeholder="#FFFFFF"
                                        required
                                    />
                                    <small class="text-danger error-surface_color"></small>
                                </div>
                                {{-- TEXT --}}
                                <div class="col-md-4 col-lg-2">
                                    <label class="form-label"> Text </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="text_color"
                                        value="{{ old('text_color', $setting->text_color ?? '#222222') }}"
                                        placeholder="#000000"
                                        required
                                    />
                                    <small class="text-danger error-text_color"></small>
                                </div>
                                {{-- ACCENT --}}
                                <div class="col-md-4 col-lg-2">
                                    <label class="form-label"> Accent </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="accent_color"
                                        value="{{ old('accent_color', $setting->accent_color ?? '#D4AF6A') }}"
                                        placeholder="#FFC107"
                                        required
                                    />
                                    <small class="text-danger error-accent_color"></small>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button
                                    type="submit"
                                    id="settingsButton"
                                    class="btn btn-primary px-4"
                                >
                                    💾 Simpan Pengaturan
                                </button>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </main>
    </div>
</div>


{{-- =====================================================
     MODAL CREATE FRAME
====================================================== --}}
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Frame</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="createForm">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Frame</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               placeholder="Contoh: Frame Astarte"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">File Frame</label>
                        <input type="file"
                               name="file"
                               id="createFile"
                               class="form-control"
                               accept="image/*,.gif"
                               required>
                        <small class="text-muted">Maksimal 10 MB.</small>
                    </div>

                    <div id="createPreviewContainer" class="d-none text-center">
                        <img id="createPreview"
                             src=""
                             class="img-fluid rounded"
                             style="max-height:300px;">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" id="createButton" class="btn btn-primary">
                        Upload Frame
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- =====================================================
     MODAL EDIT FRAME
====================================================== --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Frame</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editForm">
                @csrf

                <input type="hidden" id="editId">

                <div class="modal-body">
                    <div class="text-center mb-4">
                        <img id="editCurrentPreview"
                             src=""
                             class="img-fluid rounded"
                             style="max-height:250px;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Frame</label>
                        <input type="text"
                               id="editName"
                               name="name"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ganti File</label>
                        <input type="file"
                               id="editFile"
                               name="file"
                               class="form-control"
                               accept="image/*,.gif">
                        <small class="text-muted">
                            Kosongkan jika tidak ingin mengganti file.
                        </small>
                    </div>

                    <div id="editPreviewContainer" class="d-none text-center">
                        <p class="text-muted">Preview file baru</p>
                        <img id="editPreview"
                             src=""
                             class="img-fluid rounded"
                             style="max-height:250px;">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" id="editButton" class="btn btn-primary">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- =====================================================
     MODAL HASIL FOTO
====================================================== --}}
<div class="modal fade" id="resultModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="resultModalTitle">Hasil Foto</h5>
                    <small id="resultModalSession" class="text-muted"></small>
                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div id="resultList" class="row g-3"></div>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
const FRAME_API = "{{ url('/api/frame-templates') }}";
const SESSION_API = "{{ url('/api/photo-sessions') }}";
const SETTINGS_API = "{{ url('/api/settings') }}";
let currentSettingId = null;

const alertBox = document.getElementById('alert');

function showAlert(message, type = 'success') {
    alertBox.className = `alert alert-${type}`;
    alertBox.innerHTML = message;
    alertBox.classList.remove('d-none');

    setTimeout(() => alertBox.classList.add('d-none'), 4000);
}


/* =====================================================
   NAVIGATION
===================================================== */
function showSection(section) {
    document.querySelectorAll('.section').forEach(el => {
        el.classList.remove('active');
    });

    document.querySelectorAll('.sidebar a').forEach(el => {
        el.classList.remove('active');
    });

    if (section === 'frames') {
        document.getElementById('framesSection').classList.add('active');
        document.getElementById('navFrames').classList.add('active');
        loadFrames();
    }

    if (section === 'sessions') {
        document.getElementById('sessionsSection').classList.add('active');
        document.getElementById('navSessions').classList.add('active');
        loadSessions();
    }

    if (section === 'settings') {
        document.getElementById('settingsSection').classList.add('active');
        document.getElementById('navSettings').classList.add('active');
        loadSettings();
    }
}


/* =====================================================
   FRAME TEMPLATES
===================================================== */
const frameList = document.getElementById('frameList');
const frameLoading = document.getElementById('frameLoading');

async function loadFrames() {
    frameLoading.classList.remove('d-none');
    frameList.classList.add('d-none');

    try {
        const response = await fetch(FRAME_API, {
            headers: { 'Accept': 'application/json' }
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Gagal mengambil data frame');
        }

        renderFrames(result.data);
    } catch (error) {
        showAlert(error.message, 'danger');
    } finally {
        frameLoading.classList.add('d-none');
        frameList.classList.remove('d-none');
    }
}

function renderFrames(frames) {
    frameList.innerHTML = '';

    if (!frames || frames.length === 0) {
        frameList.innerHTML = `
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="empty-state">
                        <h4>Belum ada frame</h4>
                        <p class="text-muted">Tambahkan frame pertama Anda.</p>
                        <button class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#createModal">
                            + Tambah Frame
                        </button>
                    </div>
                </div>
            </div>
        `;
        return;
    }

    frames.forEach(frame => {
        frameList.innerHTML += `
            <div class="col-md-6 col-lg-4">
                <div class="card frame-card shadow-sm h-100">
                    <img src="${frame.file_url}"
                         class="card-img-top frame-preview"
                         alt="${escapeHtml(frame.name)}">

                    <div class="card-body">
                        <h5 class="card-title">${escapeHtml(frame.name)}</h5>

                        <p class="text-muted small mb-3">
                            ID: ${frame.id}
                        </p>

                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm flex-fill"
                                    onclick='openEditModal(${JSON.stringify(frame)})'>
                                ✏️ Edit
                            </button>

                            <button class="btn btn-outline-danger btn-sm flex-fill"
                                    onclick="deleteFrame(${frame.id})">
                                🗑️ Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
}


/* CREATE FRAME */
document.getElementById('createFile').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;

    const reader = new FileReader();

    reader.onload = e => {
        document.getElementById('createPreview').src = e.target.result;
        document.getElementById('createPreviewContainer').classList.remove('d-none');
    };

    reader.readAsDataURL(file);
});

document.getElementById('createForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const button = document.getElementById('createButton');
    const formData = new FormData(this);

    button.disabled = true;
    button.innerHTML = 'Mengupload...';

    try {
        const response = await fetch(FRAME_API, {
            method: 'POST',
            headers: { 'Accept': 'application/json' },
            body: formData
        });

        const result = await response.json();

        if (!response.ok) {
            let message = result.message || 'Upload gagal';

            if (result.errors) {
                message = Object.values(result.errors).flat().join('<br>');
            }

            throw new Error(message);
        }

        bootstrap.Modal.getInstance(
            document.getElementById('createModal')
        ).hide();

        this.reset();
        document.getElementById('createPreviewContainer').classList.add('d-none');

        showAlert(result.message || 'Frame berhasil diupload');
        loadFrames();

    } catch (error) {
        showAlert(error.message, 'danger');
    } finally {
        button.disabled = false;
        button.innerHTML = 'Upload Frame';
    }
});


/* EDIT FRAME */
function openEditModal(frame) {
    document.getElementById('editId').value = frame.id;
    document.getElementById('editName').value = frame.name;
    document.getElementById('editCurrentPreview').src = frame.file_url;
    document.getElementById('editFile').value = '';
    document.getElementById('editPreviewContainer').classList.add('d-none');

    new bootstrap.Modal(document.getElementById('editModal')).show();
}

document.getElementById('editFile').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;

    const reader = new FileReader();

    reader.onload = e => {
        document.getElementById('editPreview').src = e.target.result;
        document.getElementById('editPreviewContainer').classList.remove('d-none');
    };

    reader.readAsDataURL(file);
});

document.getElementById('editForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const id = document.getElementById('editId').value;
    const button = document.getElementById('editButton');
    const formData = new FormData(this);

    button.disabled = true;
    button.innerHTML = 'Menyimpan...';

    try {
        const response = await fetch(`${FRAME_API}/${id}`, {
            method: 'POST',
            headers: { 'Accept': 'application/json' },
            body: formData
        });

        const result = await response.json();

        if (!response.ok) {
            let message = result.message || 'Update gagal';

            if (result.errors) {
                message = Object.values(result.errors).flat().join('<br>');
            }

            throw new Error(message);
        }

        bootstrap.Modal.getInstance(
            document.getElementById('editModal')
        ).hide();

        showAlert(result.message || 'Frame berhasil diupdate');
        loadFrames();

    } catch (error) {
        showAlert(error.message, 'danger');
    } finally {
        button.disabled = false;
        button.innerHTML = 'Simpan Perubahan';
    }
});


/* DELETE FRAME */
async function deleteFrame(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus frame ini?')) return;

    try {
        const response = await fetch(`${FRAME_API}/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Frame gagal dihapus');
        }

        showAlert(result.message || 'Frame berhasil dihapus');
        loadFrames();

    } catch (error) {
        showAlert(error.message, 'danger');
    }
}


/* =====================================================
   SESI FOTO
===================================================== */
const sessionList = document.getElementById('sessionList');
const sessionLoading = document.getElementById('sessionLoading');
const resultList = document.getElementById('resultList');

async function loadSessions() {
    sessionLoading.style.display = 'block';
    sessionList.innerHTML = '';

    try {
        const response = await fetch(SESSION_API, {
            method: 'GET',
            headers: { 'Accept': 'application/json' }
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Gagal mengambil data sesi');
        }

        renderSessions(result.data);

    } catch (error) {
        showAlert(error.message, 'danger');
    } finally {
        sessionLoading.style.display = 'none';
    }
}

function renderSessions(sessions) {
    sessionList.innerHTML = '';

    if (!sessions || sessions.length === 0) {
        sessionList.innerHTML = `
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="empty-state">
                        <h4>Belum ada sesi foto</h4>
                        <p class="text-muted">
                            Belum terdapat sesi foto yang tersimpan.
                        </p>
                    </div>
                </div>
            </div>
        `;
        return;
    }

    sessions.forEach(session => {
        const results = session.results || [];

        const col = document.createElement('div');
        col.className = 'col-md-6 col-lg-4';

        col.innerHTML = `
            <div class="card session-card shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="mb-1">Sesi Foto</h5>
                            <span class="badge text-bg-primary">
                                ${results.length} Foto
                            </span>
                        </div>

                        <span class="text-muted small">
                            #${session.id}
                        </span>
                    </div>

                    <div class="mb-3">
                        <div class="text-muted small">Session ID</div>
                        <div class="fw-semibold text-break">
                            ${escapeHtml(session.session_id)}
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="text-muted small">Dibuat</div>
                        <div>${formatDate(session.created_at)}</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button"
                                class="btn btn-primary btn-sm flex-fill"
                                onclick="viewResults(${session.id})">
                            👁️ Lihat Foto
                        </button>

                        <button type="button"
                                class="btn btn-outline-danger btn-sm"
                                onclick="deleteSession('${escapeAttribute(session.session_id)}')">
                            🗑️
                        </button>
                    </div>

                </div>
            </div>
        `;

        sessionList.appendChild(col);
    });
}


/* VIEW RESULTS */
async function viewResults(id) {
    try {
        const response = await fetch(SESSION_API, {
            method: 'GET',
            headers: { 'Accept': 'application/json' }
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Gagal mengambil data');
        }

        const session = result.data.find(item => item.id == id);

        if (!session) {
            throw new Error('Sesi foto tidak ditemukan');
        }

        document.getElementById('resultModalSession').innerText =
            `Session ID: ${session.session_id}`;

        resultList.innerHTML = '';

        const results = session.results || [];

        if (results.length === 0) {
            resultList.innerHTML = `
                <div class="col-12 text-center py-5">
                    <h5>Tidak ada hasil foto</h5>
                    <p class="text-muted">
                        Sesi ini belum memiliki hasil foto.
                    </p>
                </div>
            `;
        } else {
            results.forEach((result, index) => {
                resultList.innerHTML += `
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">

                                <div class="mb-2">
                                    <span class="badge text-bg-secondary">
                                        Foto ${index + 1}
                                    </span>
                                </div>

                                <img src="${result.url}"
                                     class="result-image"
                                     alt="Hasil foto ${index + 1}">

                                <div class="mt-2">
                                    <a href="${result.url}"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-primary w-100">
                                        Buka Foto
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                `;
            });
        }

        new bootstrap.Modal(
            document.getElementById('resultModal')
        ).show();

    } catch (error) {
        showAlert(error.message, 'danger');
    }
}


/* DELETE SESSION */
async function deleteSession(sessionId) {
    if (!confirm(
        `Apakah Anda yakin ingin menghapus sesi "${sessionId}"?\n\n` +
        `Semua hasil foto dalam sesi ini juga akan dihapus.`
    )) return;

    try {
        const response = await fetch(
            `${SESSION_API}/${encodeURIComponent(sessionId)}`,
            {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            }
        );

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Gagal menghapus sesi');
        }

        showAlert(result.message || 'Photo session berhasil dihapus');
        loadSessions();

    } catch (error) {
        showAlert(error.message, 'danger');
    }
}


/* =====================================================
   PENGATURAN WEBSITE
===================================================== */
// const settingsForm = document.getElementById('settingsForm');
// const settingsLoading = document.getElementById('settingsLoading');
// const settingsButton = document.getElementById('settingsButton');

// async function loadSettings() {
//     settingsLoading.classList.remove('d-none');
//     settingsForm.classList.add('d-none');

//     try {
//         const response = await fetch(SETTINGS_API, {
//             method: 'GET',
//             headers: { 'Accept': 'application/json' }
//         });

//         const result = await response.json();

//         // Belum ada setting: tampilkan form kosong agar bisa dibuat.
//         if (response.status === 404) {
//             currentSettingId = null;
//             settingsForm.reset();
//             return;
//         }

//         if (!response.ok) {
//             throw new Error(result.message || 'Gagal mengambil pengaturan website');
//         }

//         currentSettingId = result.data?.id ?? null;
//         fillSettingsForm(result.data || {});

//     } catch (error) {
//         showAlert(error.message, 'danger');
//     } finally {
//         settingsLoading.classList.add('d-none');
//         settingsForm.classList.remove('d-none');
//     }
// }

// function fillSettingsForm(settings) {
//     const fields = [
//         'eyebrow',
//         'website_name',
//         'tagline',
//         'website_description',
//         'photo_price',
//         'photo_count',
//         'countdown',
//         'max_time',
//         'primary_color',
//         'secondary_color',
//         'background_color',
//         'surface_color',
//         'text_color',
//         'accent_color'
//     ];

//     fields.forEach(field => {
//         const input = settingsForm.elements[field];
//         if (input) input.value = settings[field] ?? '';
//     });
// }



/* =====================================================
   HELPERS
===================================================== */
function formatDate(date) {
    if (!date) return '-';

    return new Date(date).toLocaleString('id-ID', {
        dateStyle: 'medium',
        timeStyle: 'short'
    });
}

function escapeHtml(value) {
    const div = document.createElement('div');
    div.textContent = value ?? '';
    return div.innerHTML;
}

function escapeAttribute(value) {
    return String(value ?? '')
        .replace(/\\/g, '\\\\')
        .replace(/'/g, "\\'");
}


/* INITIAL LOAD */
loadFrames();
</script>

</body>
</html>
