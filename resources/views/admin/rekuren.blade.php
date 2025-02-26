@extends('layout.app')

@section('title', 'Sistem Lisensi Pegawai | Data Pengajuan Rekuren Lisensi')

@section('content')
    @section('current_menu')Menu Rekuren @endsection
    @section('current_page')Data Pengajuan Rekuren Lisensi @endsection
    <div class="d-flex justify-content-end align-items-center flex-wrap grid-margin">
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#scheduleModal">
                Atur Jadwal Test
            </button>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap ms-3">
            <button type="button" class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#confirmationModal">
                Tolak Pengajuan
            </button>
        </div>
    </div>

    <!-- Modal Input Tanggal -->
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="checkAll"></th>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Masa Berlaku</th>
                                    <th>Lisensi</th>
                                    <th>Nomor Lisensi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($license as $data => $item)
                                    <tr>
                                        <td><input type="checkbox" class="checkItem" name="ids[]"
                                                value="{{ $item->id }}"></td>
                                        <td>{{ $data + 1 }}</td>
                                        <td>{{ $item->license->employee->fullname }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->license->end_date)->locale('id')->isoFormat('D MMM Y') }}
                                        </td>
                                        <td>
                                            {{ $item->license->license_type }}
                                        </td>
                                        <td>{{ $item->license->license_number }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $item->status }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleModalLabel">Atur Jadwal Rekuren</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="bulkProcessForm" action="{{ route('rekuren.acceptSubmission') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="date" class="form-label">Tanggal Rekuren</label>
                            <input type="date" class="form-control" name="date" required>
                        </div>
                        <input type="hidden" name="ids" id="selectedIds">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom-modal">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <img src="https://img.icons8.com/color/96/000000/delete-forever.png" alt="delete icon">
                    </div>
                    <h5 class="modal-title mb-2" id="deleteModalLabel">Konfirmasi tolak pengajuan rekuren lisensi</h5>
                    <p class="text-muted">Apakah anda yakin ingin menolak pengajuan rekuren lisensi?</p>
                    <input type="hidden" name="ids" id="selectedIds">
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Ya, Tolak!</button>
                    </form>
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
    <script src="{{ asset('assets/js/dropzone.js') }}"></script>
    <script src="{{ asset('assets/js/dropify.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-colorpicker.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/timepicker.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Saat checkbox "Check All" di klik
            $('#checkAll').click(function() {
                $('.checkItem').prop('checked', this.checked);
            });

            // Jika semua checkbox item dicentang, maka check "Check All"
            $('.checkItem').change(function() {
                if ($('.checkItem:checked').length == $('.checkItem').length) {
                    $('#checkAll').prop('checked', true);
                } else {
                    $('#checkAll').prop('checked', false);
                }
            });

            $('#scheduleModal').on('show.bs.modal', function() {
                let selectedIds = [];
                $('.checkItem:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Silakan pilih minimal satu data!',
                    });
                    $('#scheduleModal').modal('hide');
                    return false;
                }

                // Masukkan id ke dalam input hidden
                $('#selectedIds').val(selectedIds.join(','));
            });

            $('#confirmationModal').on('show.bs.modal', function() {
                let selectedIds = [];
                $('.checkItem:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Silakan pilih minimal satu data!',
                    });
                    $('#confirmationModal').modal('hide');
                    return false;
                }

                // Masukkan id ke dalam input hidden
                $('#selectedIds').val(selectedIds.join(','));
            });


        });
    </script>


@endsection
