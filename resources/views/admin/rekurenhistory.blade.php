@extends('layout.app')

@section('title', 'Sistem Lisensi Pegawai | Riwayat Rekuren')

@section('content')
    @section('current_menu')Menu Rekuren @endsection
    @section('current_page')Riwayat Rekuren @endsection

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
                                    <th>Tanggal Dikonfirmasi</th>
                                    <th>Hasil Test</th>
                                    <th>Evaluasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($license as $data => $item)
                                    <tr>
                                        <td>{{ $data + 1 }}</td>
                                        <td>{{ $item->employee->fullname }}</td>
                                        <td>
                                            {{ $item->license->license_type }}
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

@endsection
