@extends('layout.app')

@section('title', 'Sistem Lisensi Pegawai | File Manager')

@section('content')
    <style>
        .search-box .form-control {
            border-radius: 10px;
            padding-left: 40px
        }

        .search-box .search-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
            fill: #545965;
            width: 16px;
            height: 16px
        }

        .card {
            margin-bottom: 24px;
            -webkit-box-shadow: 0 2px 3px #e4e8f0;
            box-shadow: 0 2px 3px #e4e8f0;
        }

        .card {
            position: relative;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid #eff0f2;
            border-radius: 8px;
        }

        .me-3 {
            margin-right: 1rem !important;
        }

        .font-size-24 {
            font-size: 24px !important;
        }

        .avatar-title {
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            background-color: #3b76e1;
            color: #fff;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            font-weight: 500;
            height: 100%;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            width: 100%;
        }

        .bg-soft-info {
            background-color: rgba(87, 201, 235, .25) !important;
        }

        .bg-soft-primary {
            background-color: rgba(59, 118, 225, .25) !important;
        }

        .avatar-xs {
            height: 1rem;
            width: 1rem
        }

        .avatar-sm {
            height: 2rem;
            width: 2rem
        }

        .avatar {
            height: 3rem;
            width: 3rem
        }

        .avatar-md {
            height: 4rem;
            width: 4rem
        }

        .avatar-lg {
            height: 5rem;
            width: 5rem
        }

        .avatar-xl {
            height: 6rem;
            width: 6rem
        }

        .avatar-title {
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            background-color: #3b76e1;
            color: #fff;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            font-weight: 500;
            height: 100%;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            width: 100%
        }

        .avatar-group {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            padding-left: 8px
        }

        .avatar-group .avatar-group-item {
            margin-left: -8px;
            border: 2px solid #fff;
            border-radius: 50%;
            -webkit-transition: all .2s;
            transition: all .2s
        }

        .avatar-group .avatar-group-item:hover {
            position: relative;
            -webkit-transform: translateY(-2px);
            transform: translateY(-2px)
        }

        .fw-medium {
            font-weight: 500;
        }

        a {
            text-decoration: none !important;
        }
    </style>
    @section('current_menu')File Manager @endsection
    @section('current_page'){{ $folder }} @endsection

    {{-- Content --}}
    <!-- Profile 1 - Bootstrap Brain Component -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.css"
        integrity="sha256-NAxhqDvtY0l4xn+YVa6WjAcmd94NNfttjNsDmNatFVc=" crossorigin="anonymous" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <!-- end row -->
                <!-- end row -->
                <div class="d-flex flex-wrap align-items-center">
                    <a href="{{ url()->previous() }}" class="text-primary me-3">
                        <i class="mdi mdi-arrow-left font-size-20"></i>
                    </a>
                    <h5 class="font-size-16 me-3">Berkas Pegawai</h5>
                    <div class="ms-auto">
                        <img src="{{ $photo->fileManager->employee->photo_url ? asset('storage/' . $photo->fileManager->employee->photo_url) : 'https://static.vecteezy.com/system/resources/thumbnails/003/337/584/small/default-avatar-photo-placeholder-profile-icon-vector.jpg' }}"
                            alt="" class="rounded-circle avatar-sm">
                    </div>
                </div>
                <hr class="mt-2">
                <div class="table-responsive">
                    @if ($files)
                        <table class="table align-middle table-nowrap table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama File</th>
                                    <th scope="col">Diunggah Tanggal</th>
                                    <th scope="col" colspan="2">Ukuran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($files as $index => $file)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><a href="javascript: void(0);" class="text-dark fw-medium"><i
                                                    class="mdi mdi-file-document font-size-16 align-middle text-primary me-2"></i>
                                                {{ $file->file_name }}</a></td>
                                        <td>{{ \Carbon\Carbon::parse($file->created_at)->locale('id')->isoFormat('D MMM Y') }}
                                        </td>
                                        <td>{{ $file->file_size }} KB</td>

                                        <td>
                                            <div class="dropdown">
                                                <a class="font-size-16 text-muted" role="button" data-bs-toggle="dropdown"
                                                    aria-haspopup="true">
                                                    <i class="mdi mdi-dots-horizontal"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="#">Open</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('file-manager.download', $file->id) }}">Download</a>

                                                    <div class="dropdown-divider"></div>
                                                    <form action="{{ route('file-manager.destroy', $file->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="dropdown-item">Hapus</a>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Tidak ada file</p>
                    @endif
                </div>

            </div>
        </div>

    </div>
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
    <script src="{{ asset('assets/vendors/dropzone/dropzone.min.js') }}"></script>

    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <script src="{{ asset('assets/js/dropify.js') }}"></script>

@endsection
