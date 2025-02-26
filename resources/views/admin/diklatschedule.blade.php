@extends('layout.app')

@section('title', 'Sistem Lisensi Pegawai | Jadwal Diklat')

@section('content')
    @section('current_menu')Menu Diklat @endsection
    @section('current_page')Jadwal Diklat @endsection

    <!-- Modal Input Tanggal -->
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
                                    <th>Lisensi</th>
                                    <th>Nomor Lisensi</th>
                                    <th>Nama Diklat</th>
                                    <th>Tanggal Diklat</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($license as $data => $item)
                                    <tr>
                                        <td>{{ $data + 1 }}</td>
                                        <td>{{ $item->license->employee->fullname }}</td>
                                        <td>
                                            {{ $item->license->license_type }}
                                        </td>
                                        <td>{{ $item->license->license_number }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->date)->locale('id')->isoFormat('D MMM Y') }}
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ $item->status }}</span>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-primary btn-icon btn-xs me-2"
                                                data-bs-toggle="modal"
                                                data-bs-target="#confirmationModal{{ $item->id }}"><i
                                                    data-feather="user-check"></i></a>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="confirmationModal{{ $item->id }}" tabindex="-1"
                                        aria-labelledby="confirmationModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="confirmationModalLabel">Hasil Diklat
                                                        {{ $item->license->employee->fullname }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('diklat.graduated', $item->id) }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <div class="mb-3">
                                                                <label for="test_result" class="form-label">Hasil
                                                                    Test</label>
                                                                <select class="form-select" id="exampleFormControlSelect1"
                                                                    name="result">
                                                                    <option selected="" disabled="">-- Pilih
                                                                        Hasil Test
                                                                        --</option>
                                                                    <option value="GRADUATED">
                                                                        LULUS</option>
                                                                    <option value="UNGRADUATED">
                                                                        TIDAK LULUS</option>
                                                                </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Hasil Evaluasi</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Masukkan hasil evaluasi (Opsional)"
                                                                    name="notes">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Sertifikat Diklat</label>
                                                                <input class="form-control" type="file"
                                                                    name="certificate_url" accept="application/pdf">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Konfirmasi</button>
                                                    </div>
                                                </form>
                                            </div>
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
    <script src="{{ asset('assets/js/bootstrap-colorpicker.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/timepicker.js') }}"></script>


    <script>
        $(document).ready(function() {
            $('.dropify').dropify(); // Inisialisasi Dropify saat halaman dimuat

            $('.modal').on('shown.bs.modal', function() {
                $(this).find('.dropify').dropify({
                    messages: {
                        default: 'Drag and drop atau klik untuk memilih file',
                        replace: 'Ganti file',
                        remove: 'Hapus',
                        error: 'Oops, terjadi kesalahan!'
                    }
                });
            });

            $('.modal').on('hidden.bs.modal', function() {
                $(this).find('.dropify').dropify()
                    .destroy(); // Hancurkan instance Dropify agar bisa direfresh
                $(this).find('.dropify').dropify(); // Re-inisialisasi setelah modal ditutup
            });
        });
    </script>

@endsection
