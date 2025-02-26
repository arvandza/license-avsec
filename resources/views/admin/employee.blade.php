@extends('layout.app')

@section('title', 'Sistem Lisensi Pegawai | Data Pegawai')

@section('content')
    @section('current_menu')Pegawai @endsection
    @section('current_page')Data Pegawai @endsection
    <div class="d-flex justify-content-end align-items-center flex-wrap grid-margin">
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0" data-bs-toggle="modal"
                data-bs-target="#addModal">
                <i class="btn-icon-prepend" data-feather="user-plus"></i>
                Tambah Data Pegawai
            </button>
        </div>
    </div>
    <div class="row">

        <div class="col-md-12 grid-margin stretch-card">

            <div class="card">

                <div class="card-body">

                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>NIP</th>
                                    <th>Tempat, Tanggal Lahir</th>
                                    <th>Pendidikan</th>
                                    <th>Kompetensi</th>
                                    <th>Jabatan</th>
                                    <th>Pangkat</th>
                                    <th>Email</th>
                                    <th>No. Telepon</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employee as $data => $item)
                                    <tr>
                                        <td>{{ $data + 1 }}</td>
                                        <td>{{ $item->fullname }}</td>
                                        <td>{{ $item->nip }}</td>
                                        <td>{{ $item->place_of_birth }},
                                            {{ \Carbon\Carbon::parse($item->date_of_birth)->locale('id')->isoFormat('D MMM Y') }}
                                        </td>
                                        <td>{{ $item->education }}</td>
                                        <td>{{ $item->competence }}</td>
                                        <td>{{ $item->rank }}</td>
                                        <td>{{ $item->position }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->contact }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-icon btn-xs me-2" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $item->id }}">
                                                <i data-feather="edit"></i></button>
                                            <button class="btn btn-danger btn-icon btn-xs me-2" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $item->id }}">
                                                <i data-feather="trash"></i></button>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1"
                                        aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered custom-modal">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <div class="mb-3">
                                                        <img src="https://img.icons8.com/color/96/000000/delete-forever.png"
                                                            alt="delete icon">
                                                    </div>
                                                    <h5 class="modal-title mb-2" id="deleteModalLabel">Konfirmasi Hapus Data
                                                        {{ $item->fullname }}</h5>
                                                    <p class="text-muted">Apakah anda yakin ingin menghapus data
                                                        {{ $item->fullname }}</p>
                                                </div>
                                                <div class="modal-footer border-0 justify-content-center">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <form action="{{ route('employees.destroy', $item->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Ya, Hapus!</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Edit Modal --}}
                                    <div class="modal fade bd-example-modal-lg" tabindex="-1"
                                        aria-labelledby="myLargeModalLabel" aria-hidden="true"
                                        id="editModal{{ $item->id }}">
                                        <div class="modal-dialog modal-lg">
                                            <form action="{{ route('employees.update', $item->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title h4" id="myLargeModalLabel">Edit Data
                                                            {{ $item->fullname }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="btn-close">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <div class="row">
                                                            <div class="mb-3">
                                                                <label class="form-label">Nama Lengkap</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Masukkan nama lengkap pegawai"
                                                                    name="fullname" value="{{ $item->fullname }}">
                                                            </div>

                                                        </div><!-- Row -->
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">NIP</label>
                                                                    <input type="numeric" class="form-control"
                                                                        placeholder="Masukkan NIP Pegawai" name="nip"
                                                                        value="{{ $item->nip }}">
                                                                </div>
                                                            </div><!-- Col -->
                                                            <div class="col-sm-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Pangkat</label>
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Masukkan pangkat Pegawai"
                                                                        name="rank" value="{{ $item->rank }}">
                                                                </div>
                                                            </div><!-- Col -->
                                                        </div><!-- Row -->
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tempat</label>
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Masukkan tempat lahir pegawai"
                                                                        name="place_of_birth"
                                                                        value="{{ $item->place_of_birth }}">
                                                                </div>
                                                            </div><!-- Col -->
                                                            <div class="col-sm-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tanggal Lahir</label>
                                                                    <div class="input-group date datepicker"
                                                                        id="datePickerExample">
                                                                        <input type="text" class="form-control"
                                                                            name="date_of_birth"
                                                                            value="{{ \Carbon\Carbon::parse($item->date_of_birth)->format('m/d/Y') }}">
                                                                        <span
                                                                            class="input-group-text input-group-addon"><svg
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                width="24" height="24"
                                                                                viewBox="0 0 24 24" fill="none"
                                                                                stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                class="feather feather-calendar">
                                                                                <rect x="3" y="4" width="18"
                                                                                    height="18" rx="2"
                                                                                    ry="2"></rect>
                                                                                <line x1="16" y1="2"
                                                                                    x2="16" y2="6">
                                                                                </line>
                                                                                <line x1="8" y1="2"
                                                                                    x2="8" y2="6">
                                                                                </line>
                                                                                <line x1="3" y1="10"
                                                                                    x2="21" y2="10">
                                                                                </line>
                                                                            </svg></span>
                                                                    </div>
                                                                </div>
                                                            </div><!-- Col -->
                                                        </div><!-- Row -->
                                                        <div class="row">
                                                            <div class="mb-3">
                                                                <label class="form-label">Jabatan</label>
                                                                <select class="form-select" id="exampleFormControlSelect1"
                                                                    name="position">
                                                                    <option selected="" disabled="">-- Pilih Jabatan
                                                                        --</option>
                                                                    <option value="Kanit Avsec"
                                                                        {{ $item->position == 'Kanit Avsec' ? 'selected' : '' }}>
                                                                        Kanit Avsec</option>
                                                                    <option value="Danru 1"
                                                                        {{ $item->position == 'Danru 1' ? 'selected' : '' }}>
                                                                        Danru 1</option>
                                                                    <option value="Danru 2"
                                                                        {{ $item->position == 'Danru 2' ? 'selected' : '' }}>
                                                                        Danru 2</option>
                                                                    <option value="Danru 3"
                                                                        {{ $item->position == 'Danru 3' ? 'selected' : '' }}>
                                                                        Danru 3</option>
                                                                    <option value="Anggota"
                                                                        {{ $item->position == 'Anggota' ? 'selected' : '' }}>
                                                                        Anggota</option>
                                                                    <option value="Admin"
                                                                        {{ $item->position == 'Admin' ? 'selected' : '' }}>
                                                                        Admin</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Formasi</label>
                                                                    <select class="form-select"
                                                                        id="exampleFormControlSelect1" name="education">
                                                                        <option selected="" disabled="">-- Pilih
                                                                            Pendidikan --</option>
                                                                        <option value="SD"
                                                                            {{ $item->education == 'SD' ? 'selected' : '' }}>
                                                                            SD</option>
                                                                        <option value="SMP"
                                                                            {{ $item->education == 'SMP' ? 'selected' : '' }}>
                                                                            SMP</option>
                                                                        <option value="SMA"
                                                                            {{ $item->education == 'SMA' ? 'selected' : '' }}>
                                                                            SMA/SMK Sederajat</option>
                                                                        <option value="D1"
                                                                            {{ $item->education == 'D1' ? 'selected' : '' }}>
                                                                            D1</option>
                                                                        <option value="D2"
                                                                            {{ $item->education == 'D2' ? 'selected' : '' }}>
                                                                            D2</option>
                                                                        <option value="D3"
                                                                            {{ $item->education == 'D3' ? 'selected' : '' }}>
                                                                            D3</option>
                                                                        <option value="D4"
                                                                            {{ $item->education == 'D4' ? 'selected' : '' }}>
                                                                            D4</option>
                                                                        <option value="S1"
                                                                            {{ $item->education == 'S1' ? 'selected' : '' }}>
                                                                            S1</option>
                                                                        <option value="S2"
                                                                            {{ $item->education == 'S2' ? 'selected' : '' }}>
                                                                            S2</option>
                                                                        <option value="S3"
                                                                            {{ $item->education == 'S3' ? 'selected' : '' }}>
                                                                            S3</option>
                                                                    </select>
                                                                </div>
                                                            </div><!-- Col -->
                                                            <div class="col-sm-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Kompetensi</label>
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Masukkan kompetensi pegawai"
                                                                        name="competence"
                                                                        value="{{ $item->competence }}">
                                                                </div>
                                                            </div><!-- Col -->
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Email</label>
                                                                    <input type="email" class="form-control"
                                                                        placeholder="Masukkan email pegawai"
                                                                        name="email" value="{{ $item->email }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">No. Telepon</label>
                                                                    <input type="number" class="form-control"
                                                                        placeholder="Masukkan no. telepon pegawai"
                                                                        name="contact" value="{{ $item->contact }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="mb-3">
                                                                <label class="form-label">Posisi</label>
                                                                <select class="form-select" id="exampleFormControlSelect1"
                                                                    name="role">
                                                                    <option selected="" disabled="">-- Pilih Posisi
                                                                        --</option>
                                                                    <option value="1"
                                                                        {{ $item->user->role_id == '1' ? 'selected' : '' }}>
                                                                        Admin
                                                                    </option>
                                                                    <option value="2"
                                                                        {{ $item->user->role_id == '2' ? 'selected' : '' }}>
                                                                        Pegawai
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Kembali</button>
                                                        <button type="submit" class="btn btn-primary">Ubah Data</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="modal fade bd-example-modal-lg" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true"
        id="addModal">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title h4" id="myLargeModalLabel">Tambah Data Pegawai</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close">
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" placeholder="Masukkan nama lengkap pegawai"
                                    name="fullname">
                            </div>

                        </div><!-- Row -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">NIP</label>
                                    <input type="numeric" class="form-control" placeholder="Masukkan NIP Pegawai"
                                        name="nip">
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Pangkat</label>
                                    <input type="text" class="form-control" placeholder="Masukkan pangkat Pegawai"
                                        name="rank">
                                </div>
                            </div><!-- Col -->
                        </div><!-- Row -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Tempat</label>
                                    <input type="text" class="form-control"
                                        placeholder="Masukkan tempat lahir pegawai" name="place_of_birth">
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <div class="input-group date datepicker" id="datePickerExamples">
                                        <input type="text" class="form-control" name="date_of_birth">
                                        <span class="input-group-text input-group-addon"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-calendar">
                                                <rect x="3" y="4" width="18" height="18" rx="2"
                                                    ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6">
                                                </line>
                                                <line x1="8" y1="2" x2="8" y2="6">
                                                </line>
                                                <line x1="3" y1="10" x2="21" y2="10">
                                                </line>
                                            </svg></span>
                                    </div>
                                </div>
                            </div><!-- Col -->
                        </div><!-- Row -->
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label">Jabatan</label>
                                <select class="form-select" id="exampleFormControlSelect1" name="position">
                                    <option selected="" disabled="">-- Pilih Jabatan --</option>
                                    <option value="Kanit Avsec">Kanit Avsec</option>
                                    <option value="Danru 1">Danru 1</option>
                                    <option value="Danru 2">Danru 2</option>
                                    <option value="Danru 3">Danru 3</option>
                                    <option value="Anggota">Anggota</option>
                                    <option value="Admin">Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Formasi</label>
                                    <select class="form-select" id="exampleFormControlSelect1" name="education">
                                        <option selected="" disabled="">-- Pilih Pendidikan --</option>
                                        <option value="SD">SD</option>
                                        <option value="SMP">SMP</option>
                                        <option value="SMA">SMA/SMK Sederajat</option>
                                        <option value="D1">D1</option>
                                        <option value="D2">D2</option>
                                        <option value="D3">D3</option>
                                        <option value="D4">D4</option>
                                        <option value="S1">S1</option>
                                        <option value="S2">S2</option>
                                        <option value="S3">S3</option>
                                    </select>
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Kompetensi</label>
                                    <input type="text" class="form-control" placeholder="Masukkan kompetensi pegawai"
                                        name="competence">
                                </div>
                            </div><!-- Col -->
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" placeholder="Masukkan email pegawai"
                                        name="email">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">No. Telepon</label>
                                    <input type="number" class="form-control" placeholder="Masukkan no. telepon pegawai"
                                        name="contact">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label">Posisi</label>
                                <select class="form-select" id="exampleFormControlSelect1" name="role">
                                    <option selected="" disabled="">-- Pilih Posisi --</option>
                                    <option value="1">Admin</option>
                                    <option value="2">Pegawai</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('assets/vendors/core/core.js') }}"></script>
    <!-- endinject -->

    <!-- Plugin js for this page -->
    <script src="{{ asset('assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js') }}"></script>

    <script src="{{ asset('assets/vendors/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/typeahead.js/typeahead.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/dropzone/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/dropify/dist/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap-colorpicker/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.js') }}"></script>
    <!-- End plugin js for this page -->

    <!-- inject:js -->
    <script src="{{ asset('assets/vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <!-- endinject -->

    <!-- Custom js for this page -->
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <script src="{{ asset('assets/js/form-validation.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-maxlength.js') }}"></script>
    <script src="{{ asset('assets/js/inputmask.js') }}"></script>
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead.js') }}"></script>
    <script src="{{ asset('assets/js/tags-input.js') }}"></script>
    <script src="{{ asset('assets/js/dropzone.js') }}"></script>
    <script src="{{ asset('assets/js/dropify.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-colorpicker.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/timepicker.js') }}"></script>

@endsection
