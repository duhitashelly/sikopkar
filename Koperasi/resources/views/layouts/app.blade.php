<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI KOPKAR</title>

    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    

    <style>
        body, html, #page-top, #wrapper {
            background-color:rgb(237, 236, 255) !important; /* abu terang */
            height: 100%;
        }

        #content-wrapper {
            background-color: transparent !important; /* biar ga nutupin */
        }

        .custom-container {
            background-color:rgb(255, 255, 255);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }
    </style>

    @yield('styles')
</head>

<body id="page-top">
    <div id="wrapper">
        @include('layouts.sidebar')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('layouts.navbar')

                <div class="container-fluid">
                    <div class="custom-container">
                        @yield('content')
                    </div>
                </div>
            </div>

    
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function () {
            const tables = ['#tabel-pinjaman', '#tabel-simpanan', '#tabel-pengguna', '#tabel-angsuran', '#laporan-anggota', '#laporan-simpanan', '#laporan-pinjaman', '#laporan-bunga'];

            tables.forEach(function(id) {
                if ($(id).length && !$.fn.dataTable.isDataTable(id)) {
                    $(id).DataTable({
                        language: {
                            emptyTable: "Data tidak tersedia"
                        }
                    });
                }
            });

        });
    </script>

    @yield('scripts')
    @stack('scripts')
    {{-- HAPUS BARIS INI (PERBAIKAN INI) --}}
    {{-- @section('scripts')
    @show --}}
</body>
</html>