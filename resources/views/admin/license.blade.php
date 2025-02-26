@extends('layout.app')

@section('title', 'Sistem Lisensi Pegawai | Verifikasi Lisensi')

@section('content')
    @section('current_menu')Menu Lisensi @endsection
    @section('current_page')Pengajuan Verifikasi Lisensi @endsection

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
                                    <th>Masa Berlaku</th>
                                    <th>Lisensi</th>
                                    <th>Nomor Lisensi</th>
                                    <th>Status</th>
                                    <th>Status</th>
                                    <th>Lihat Sertifikat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($license as $data => $item)
                                    <tr>
                                        <td>{{ $data + 1 }}</td>
                                        <td>{{ $item->employee->fullname }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->end_date)->locale('id')->isoFormat('D MMM Y') }}
                                        </td>
                                        <td>
                                            {{ $item->license_type }}
                                        </td>
                                        <td>{{ $item->license_number }}</td>
                                        <td>
                                            @if ($item->license_status === 'ACTIVE')
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Tidak Aktif</span>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="badge bg-warning">Menunggu Persetujuan</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-icon btn-xs me-2" data-bs-toggle="modal"
                                                data-bs-target="#imageModal{{ $item->id }}">
                                                <i data-feather="eye"></i></button>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <form action="{{ route('licenses.submission', $item->id) }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="employee_id" value="{{ Auth::user()->id }}">
                                                    <input type="hidden" name="status" value="ACCEPTED">
                                                    <button class="btn btn-success btn-icon btn-xs me-2" type="submit">
                                                        <i data-feather="check"></i></button>
                                                </form>

                                                <form action="{{ route('licenses.submission', $item->id) }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="employee_id" value="{{ Auth::user()->id }}">
                                                    <input type="hidden" name="status" value="REJECTED">
                                                    <button class="btn btn-danger btn-icon btn-xs me-2" type="submit">
                                                        <i data-feather="x"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="imageModal{{ $item->id }}" tabindex="-1"
                                        aria-labelledby="imageModalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="imageModalLabel{{ $item->id }}">Lisensi
                                                        Preview</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="{{ asset('storage/' . $item->license_url) }}"
                                                        class="img-fluid" alt="Image">
                                                </div>
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
    <script src="{{ asset('assets/js/dropzone.js') }}"></script>
    <script src="{{ asset('assets/js/dropify.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-colorpicker.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/timepicker.js') }}"></script>

@endsection
