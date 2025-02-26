@extends('layout.app')

@section('title', 'Sistem Lisensi Pegawai | Profile Pegawai')

@section('content')
    <style>
        .profile-container {
            position: relative;
            display: inline-block;
        }

        .profile-avatar {
            display: block;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            transition: 0.3s ease-in-out;
        }

        .file-input {
            display: none;
            /* Sembunyikan input file */
        }

        .edit-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0;
            transition: 0.3s ease-in-out;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            cursor: pointer;
        }

        .edit-icon {
            stroke: white !important;
            margin-right: 5px;
        }

        .profile-container:hover .edit-button {
            opacity: 1;
            background-color: rgba(0, 0, 0, 0.5);
            /* Tampilkan tombol saat hover */
        }
    </style>
    @section('current_menu')Profil @endsection
    @section('current_page')Profil Pegawai @endsection

    {{-- Content --}}
    <!-- Profile 1 - Bootstrap Brain Component -->
    <section class="py-3 py-md-5 py-xl-8">
        <div class="container">
            <div class="row gy-4 gy-lg-0">
                <div class="col-12 col-lg-4 col-xl-3">
                    <div class="row gy-4">
                        <div class="col-12">
                            <div class="card widget-card border-light shadow-sm">
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <img src="{{ $employee->photo_url ? asset('storage/' . $employee->photo_url) : 'https://static.vecteezy.com/system/resources/thumbnails/003/337/584/small/default-avatar-photo-placeholder-profile-icon-vector.jpg' }}"
                                            class="img-fluid rounded-circle"
                                            style="width: 150px; height: 150px; object-fit: cover;" alt="avatar"
                                            id="profile-avatars">
                                    </div>
                                    <h5 class="text-center mb-1">{{ $employee->fullname }}</h5>
                                    <p class="text-center text-secondary mb-4">{{ $employee->position }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-8 col-xl-9">
                    <div class="card widget-card border-light shadow-sm">
                        <div class="card-body p-4">
                            <ul class="nav nav-tabs" id="profileTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="overview-tab" data-bs-toggle="tab"
                                        data-bs-target="#overview-tab-pane" type="button" role="tab"
                                        aria-controls="overview-tab-pane" aria-selected="true">Lisensi</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#profile-tab-pane" type="button" role="tab"
                                        aria-controls="profile-tab-pane" aria-selected="false">Profil</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="email-tab" data-bs-toggle="tab"
                                        data-bs-target="#email-tab-pane" type="button" role="tab"
                                        aria-controls="email-tab-pane" aria-selected="false">Riwayat Diklat</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="diklat-tab" data-bs-toggle="tab"
                                        data-bs-target="#diklat-tab-pane" type="button" role="tab"
                                        aria-controls="diklat-tab-pane" aria-selected="false">Riwayat Rekuren</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="password-tab" data-bs-toggle="tab"
                                        data-bs-target="#password-tab-pane" type="button" role="tab"
                                        aria-controls="password-tab-pane" aria-selected="false">Password</button>
                                </li>
                            </ul>
                            <div class="tab-content pt-4" id="profileTabContent">
                                <div class="tab-pane fade show active" id="overview-tab-pane" role="tabpanel"
                                    aria-labelledby="overview-tab" tabindex="0">
                                    @if ($employe !== null)
                                        @if ($employe->status == 'ACCEPTED')
                                            <h5 class="mb-3">Lisensi</h5>
                                            <div class="row g-0">
                                                <div class="col-5 col-md-3 bg-white border-bottom border-white border-3">
                                                    <div class="p-2">Jenis Lisensi</div>
                                                </div>
                                                <div
                                                    class="col-7 col-md-9 bg-white border-start border-bottom border-white border-3">
                                                    <div class="p-2">:
                                                        <span class="badge bg-primary">{{ $employe->license_type }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-5 col-md-3 bg-white border-bottom border-white border-3">
                                                    <div class="p-2">No. Lisensi</div>
                                                </div>
                                                <div
                                                    class="col-7 col-md-9 bg-white border-start border-bottom border-white border-3">
                                                    <div class="p-2">: {{ $employe->license_number }}</div>
                                                </div>
                                                <div class="col-5 col-md-3 bg-white border-bottom border-white border-3">
                                                    <div class="p-2">Lisensi Status</div>
                                                </div>
                                                <div
                                                    class="col-7 col-md-9 bg-white border-start border-bottom border-white border-3">
                                                    <div class="p-2">:
                                                        @if ($employe->license_status === 'ACTIVE')
                                                            <span class="badge bg-success">Aktif</span>
                                                        @else
                                                            <span class="badge bg-danger">Tidak Aktif</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @php
                                                    $today = \Carbon\Carbon::now();
                                                    $expiredDate = \Carbon\Carbon::parse($employe->date);
                                                    $monthsLeft = $expiredDate->diffInMonths($today);
                                                    $currentYear = $today->year;
                                                    $expiredYear = $expiredDate->year;
                                                @endphp
                                                <div class="col-5 col-md-3 bg-white border-bottom border-white border-3">
                                                    <div class="p-2">Tanggal Berakhir</div>
                                                </div>
                                                <div
                                                    class="col-7 col-md-9 bg-white border-start border-bottom border-white border-3">
                                                    <div class="p-2">:
                                                        {{ \Carbon\Carbon::parse($employe->end_date)->locale('id')->isoFormat('D MMM Y') }}
                                                    </div>
                                                </div>
                                                @if ($employees->rekurenSubmission)
                                                    @if ($employees->rekurenSubmission->status === 'PENDING')
                                                        <div
                                                            class="col-5 col-md-3 bg-white border-bottom border-white border-3">
                                                            <div class="p-2">Status</div>
                                                        </div>
                                                        <div
                                                            class="col-7 col-md-9 bg-white border-start border-bottom border-white border-3">
                                                            <div class="p-2">:
                                                                <span class="badge bg-warning">Pengajuan Perpanjangan
                                                                    Rekuren</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif

                                                @if ($message)
                                                    <div class="alert alert-{{ $status }} mt-2">
                                                        {!! $message !!}
                                                    </div>
                                                @endif
                                                @if ($message && !$employees->rekurenSubmission)
                                                    <form action="{{ route('rekuren.submission') }}" method="POST">
                                                        @csrf
                                                        @method('POST')
                                                        <div class="col-5">
                                                            <button class="btn btn-outline-primary"
                                                                type="submit">Pengajuan
                                                                Perpanjangan
                                                                Lisensi</button>
                                                        </div>
                                                    </form>
                                                @else
                                                    @if ($employees->rekurenSubmission->status !== 'PENDING' && !$employees->rekurenSchedule)
                                                        <form action="{{ route('rekuren.submission') }}" method="POST">
                                                            @csrf
                                                            @method('POST')
                                                            <div class="col-5">
                                                                <button class="btn btn-outline-primary"
                                                                    type="submit">Pengajuan
                                                                    Perpanjangan
                                                                    Lisensi</button>
                                                            </div>
                                                        </form>
                                                    @endif
                                                @endif
                                            </div>
                                        @else
                                            <div class="row g-0">
                                                <div class="col-5 col-md-3 bg-white border-bottom border-white border-3">
                                                    <div class="p-2">Status</div>
                                                </div>
                                                <div
                                                    class="col-7 col-md-9 bg-white border-start border-bottom border-white border-3">
                                                    <div class="p-2">:
                                                        <span class="badge bg-warning">Menunggu Persetujuan</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="alert alert-warning mt-2">
                                            ⚠️ Anda belum verifikasi lisensi, segera lakukan verifikasi lisensi!
                                        </div>
                                        <form action="{{ route('license-submission') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('POST')
                                            <input type="hidden" name="employee_id"
                                                value="{{ Auth::user()->employee->id }}">
                                            <div class="row g-0">
                                                <div class="mb-3">
                                                    <label class="form-label">Masa Berlaku</label>
                                                    <div class="input-group date datepicker" id="datePickerExample">
                                                        <input type="text" class="form-control" name="end_date">
                                                        <span class="input-group-text input-group-addon"><svg
                                                                xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-calendar">
                                                                <rect x="3" y="4" width="18" height="18"
                                                                    rx="2" ry="2"></rect>
                                                                <line x1="16" y1="2" x2="16"
                                                                    y2="6">
                                                                </line>
                                                                <line x1="8" y1="2" x2="8"
                                                                    y2="6">
                                                                </line>
                                                                <line x1="3" y1="10" x2="21"
                                                                    y2="10">
                                                                </line>
                                                            </svg></span>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Lisensi</label>
                                                        <select class="form-select" id="exampleFormControlSelect1"
                                                            name="license_type">
                                                            <option selected="" disabled="">-- Pilih
                                                                Lisensi
                                                                --</option>
                                                            <option value="BASIC AVSEC">
                                                                BASIC AVSEC</option>
                                                            <option value="JUNIOR AVSEC">
                                                                JUNIOR AVSEC</option>
                                                            <option value="SENIOR AVSEC">
                                                                SENIOR AVSEC</option>
                                                        </select>
                                                    </div>
                                                </div><!-- Col -->
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">No. Lisensi</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="Masukkan Nomor Lisensi" name="license_number">
                                                    </div>
                                                </div><!-- Col -->


                                                <div class="mb-3">
                                                    <label class="form-label">Scan Lisensi</label>
                                                    <p class="text-muted mb-3">Maksimal ukuran file 2MB</p>
                                                    <input type="file" id="dropify" name="license_url"
                                                        accept="image/*" />
                                                </div>

                                            </div>
                                            <div class="mb-3">
                                                <button type="submit" class="btn btn-primary">Verifikasi Lisensi</button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel"
                                    aria-labelledby="profile-tab" tabindex="0">
                                    <form action="{{ route('pegawais.update', $employee->id) }}" method="POST"
                                        class="row gy-3 gy-xxl-4" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="col-12 d-flex justify-content-center">
                                            <div class="profile-container">
                                                <img id="profile-avatar"
                                                    src="{{ $employee->photo_url ? asset('storage/' . $employee->photo_url) : 'https://static.vecteezy.com/system/resources/thumbnails/003/337/584/small/default-avatar-photo-placeholder-profile-icon-vector.jpg' }}"
                                                    class="profile-avatar" alt="avatar">
                                                <input type="file" id="fileInput" class="file-input" accept="image/*"
                                                    name="photo_url">
                                                <label for="fileInput" class="edit-button">
                                                    <i data-feather="upload" class="edit-icon"></i> Upload
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="inputFullname" class="form-label">Nama Lengkap</label>
                                            <input type="text" class="form-control" id="inputFullname"
                                                name="fullname" value="{{ $employee->fullname }}">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="inputNip" class="form-label">NIP</label>
                                            <input type="text" class="form-control" id="inputNip"
                                                value="{{ $employee->nip }}" readonly>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="inputPlace" class="form-label">Tempat Lahir</label>
                                            <input type="text" class="form-control" id="inputPlace"
                                                name="place_of_birth" value="{{ $employee->place_of_birth }}">
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="inputJob" class="form-label">Tanggal Lahir</label>
                                            <div class="input-group date datepicker" id="datePickerExample">
                                                <input type="text" class="form-control" name="date_of_birth"
                                                    value="{{ \Carbon\Carbon::parse($employee->date_of_birth)->format('m/d/Y') }}">
                                                <span class="input-group-text input-group-addon"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-calendar">
                                                        <rect x="3" y="4" width="18" height="18" rx="2"
                                                            ry="2"></rect>
                                                        <line x1="16" y1="2" x2="16"
                                                            y2="6">
                                                        </line>
                                                        <line x1="8" y1="2" x2="8"
                                                            y2="6">
                                                        </line>
                                                        <line x1="3" y1="10" x2="21"
                                                            y2="10">
                                                        </line>
                                                    </svg></span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="inputEmail" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="inputEmail" name="email"
                                                value="{{ $employee->email }}">
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="inputPhone" class="form-label">Phone</label>
                                            <input type="tel" class="form-control" id="inputPhone" name="contact"
                                                value="{{ $employee->contact }}">
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="email-tab-pane" role="tabpanel"
                                    aria-labelledby="email-tab" tabindex="0">
                                    <div class="table-responsive">
                                        <table id="dataTableExample" class="table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Lisensi Lama</th>
                                                    <th>Lisensi Terbaru</th>
                                                    <th>Nomor Lisensi</th>
                                                    <th>Tanggal Dikonfirmasi</th>
                                                    <th>Hasil Test</th>
                                                    <th>Evaluasi</th>
                                                    <th>Sertifikat</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($employee->diklatHistory as $data => $item)
                                                    <tr>
                                                        <td>{{ $data + 1 }}</td>

                                                        <td>
                                                            {{ $item->old_license }}
                                                        </td>
                                                        <td>
                                                            {{ $item->license->license_type }}
                                                        </td>
                                                        <td>{{ $item->license->license_number }}</td>
                                                        <td>
                                                            @if ($item->result === 'GRADUATED')
                                                                {{ \Carbon\Carbon::parse($item->date)->locale('id')->isoFormat('D MMM Y') }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($item->result === 'GRADUATED')
                                                                <span class="badge bg-success">LULUS</span>
                                                            @else
                                                                <span class="badge bg-danger">TIDAK
                                                                    LULUS</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($item->notes !== null)
                                                                {{ $item->notes }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($item->certificate_url)
                                                                <a href="{{ route('certificate.download', $item->id) }}"
                                                                    class="btn btn-success btn-icon btn-xs me-2">
                                                                    <i data-feather="download"></i>
                                                                </a>
                                                            @else
                                                                <span class="text-muted">Tidak ada
                                                                    sertifikat</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="diklat-tab-pane" role="tabpanel"
                                    aria-labelledby="diklat-tab" tabindex="0">
                                    <div class="table-responsive">
                                        <table id="dataTableExamples" class="table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Lisensi</th>
                                                    <th>Nomor Lisensi</th>
                                                    <th>Tanggal Dikonfirmasi</th>
                                                    <th>Masa Berlaku Lisensi Lama</th>
                                                    <th>Masa Berlaku Lisensi Terbaru</th>
                                                    <th>Hasil Test</th>
                                                    <th>Evaluasi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($employee->rekurenHistory as $data => $item)
                                                    <tr>
                                                        <td>{{ $data + 1 }}</td>
                                                        <td>
                                                            {{ $item->license_type }}
                                                        </td>
                                                        <td>{{ $item->license->license_number }}</td>
                                                        <td>
                                                            @if ($item->test_result === 'GRADUATED')
                                                                {{ \Carbon\Carbon::parse($item->date)->locale('id')->isoFormat('D MMM Y') }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ \Carbon\Carbon::parse($item->old_period_of_validity)->locale('id')->isoFormat('D MMM Y') }}
                                                        </td>
                                                        <td>
                                                            @if ($item->test_result === 'GRADUATED')
                                                                {{ \Carbon\Carbon::parse($item->period_of_validity)->locale('id')->isoFormat('D MMM Y') }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($item->test_result === 'GRADUATED')
                                                                <span class="badge bg-success">LULUS</span>
                                                            @else
                                                                <span class="badge bg-danger">TIDAK LULUS</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ $item->notes }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="password-tab-pane" role="tabpanel"
                                    aria-labelledby="password-tab" tabindex="0">
                                    <form action="{{ route('pegawais.updatePassword', $employee->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="row gy-3 gy-xxl-4">
                                            <div class="col-12">
                                                <label for="currentPassword" class="form-label">Password Saat Ini</label>
                                                <input type="password" class="form-control" id="currentPassword"
                                                    name="password">
                                            </div>
                                            <div class="col-12">
                                                <label for="newPassword" class="form-label">Password Baru</label>
                                                <input type="password" class="form-control" id="newPassword"
                                                    name="newPassword">
                                            </div>
                                            <div class="col-12">
                                                <label for="confirmPassword" class="form-label">Konfirmasi
                                                    Password</label>
                                                <input type="password" class="form-control" id="confirmPassword"
                                                    name="confirmPassword">
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">Ubah Password</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@section('scripts')
    <script src="{{ asset('assets/vendors/core/core.js') }}"></script>

    <script src="{{ asset('assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js') }}"></script>

    <script src="{{ asset('assets/vendors/prismjs/prism.js') }}"></script>
    <script src="{{ asset('assets/vendors/clipboard/clipboard.min.js') }}"></script>

    <script src="{{ asset('assets/vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>

    <script src="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.js') }}"></script>
    <script src="{{ asset('assets/vendors/dropify/dist/dropify.min.js') }}"></script>

    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <script src="{{ asset('assets/js/dropify.js') }}"></script>

    <script>
        document.getElementById("fileInput").addEventListener("change", function(event) {
            const file = event.target.files[0]; // Ambil file yang diunggah
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById("profile-avatar").src = e.target.result; // Ganti src gambar

                };
                reader.readAsDataURL(file); // Baca file sebagai URL
            }
        });
    </script>
@endsection
