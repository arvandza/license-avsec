@extends('layout.app')

@section('title', 'Sistem Lisensi Pegawai | Diklat')

@section('content')
    @section('current_menu')Menu Diklat @endsection
    @section('current_page')Data Pengajuan Diklat @endsection

    <!-- Modal Input Tanggal -->
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <form action="{{ route('diklat.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Pegawai</label>
                            <p class="text-muted mb-3">Pilih nama pegawai yang akan melaksanakan diklat</p>
                            <select class="js-example-basic-multiple form-select" multiple="multiple" data-width="100%"
                                name="license_id[]">
                                @foreach ($employees as $item)
                                    <option value="{{ $item->id }}">{{ $item->employee->fullname }} -
                                        {{ $item->license_type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Diklat</label>
                            <div class="input-group date datepicker" id="datePickerExample">
                                <input type="text" class="form-control" name="date">
                                <span class="input-group-text input-group-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                        </rect>
                                        <line x1="16" y1="2" x2="16" y2="6">
                                        </line>
                                        <line x1="8" y1="2" x2="8" y2="6">
                                        </line>
                                        <line x1="3" y1="10" x2="21" y2="10">
                                        </line>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end align-items-center flex-wrap grid-margin">
                            <button type="submit" class="btn btn-primary mt-3">
                                Simpan Diklat
                            </button>
                        </div>
                    </div>
                </form>
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
