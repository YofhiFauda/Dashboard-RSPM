<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan BPJS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
    body {
        background-color: #FFF2E1; /* Soft beige background for a warm feel */
        font-family: Arial, sans-serif;
    }

    .navbar {
        background-color: #A79277; /* Primary color for the navbar */
        color: #FFF2E1;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand, .navbar .btn {
        color: white !important;
    }
    .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

    .navbar .btn:hover {
        background-color: #D1BB9E; /* Accent color for button hover */
        color: #FFF2E1;
    }

    .btn-custom {
    background-color: #A79277; /* Primary background color */
    color: white; /* White text color */
    border: none; /* Remove border */
    }

    .btn-custom:hover {
        background-color: #EAD8C0; /* Hover background color */
        color: #A79277; /* Text color on hover */
    }


    .table-container {
        background-color: #EAD8C0; /* Light tan for the table container background */
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    .table th, .table td {
        text-align: center;
        vertical-align: middle;
    }

    .table thead {
        background-color: #A79277; /* Primary color for the table headers */
        color: #FFF2E1;
    }

    .form-select, .form-control {
        border: 1px solid #D1BB9E; /* Accent color for form borders */
        color: #A79277;
    }

    .form-select:focus, .form-control:focus {
        border-color: #A79277; /* Primary color for focused form elements */
        box-shadow: none;
    }

    .pagination-container .page-item .page-link {
        color: #A79277;
    }

    .pagination-container .page-item.active .page-link, 
    .pagination-container .page-item .page-link:hover {
        background-color: #A79277; /* Primary color for active and hover states */
        color: #FFF2E1;
        border-color: #A79277;
    }
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.25rem;
            }
            .table th, .table td {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-5">
        <a class="navbar-brand" href="{{ url('/') }}">RSPM</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="ms-auto">
                <a class="btn btn-outline-light me-2" href="{{ url('/') }}">Dashboard</a>
                <a class="btn btn-outline-light" href="{{ url('/patients') }}">Generate Bjps</a>
                <a class="btn btn-outline-light me-2" href="{{ route('layanan-bpjs.resumePasien') }}">Resume Pasien</a>
            </div>
        </div>
    </div>
</nav>

<div class="container" style="max-width:100%">
    <div class="table-container">
        <form method="GET" action="{{ url()->current() }}">
            <div class="d-flex justify-content-between mb-3">
                <div>
                    <label>Show 
                        <select id="entriesPerPage" name="itemsPerPage" class="form-select d-inline-block w-auto" onchange="this.form.submit();">
                            <option value="10" {{ request('itemsPerPage') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('itemsPerPage') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('itemsPerPage') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('itemsPerPage') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </label>
                </div>
                <div>
                    <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                    <th>ID Pasien</th>
                    <th>Nama Pasien</th>
                    <th>Umur Pasien</th>
                    <th>Tanggal Lahir</th>
                    <th>Pekerjaan</th>
                    <th>Alamat</th>
                    <th>No Rekam Medis</th>
                    <th>Jenis Kelamin</th>
                    <th>Tanggal Registrasi</th>
                    <th>Tanggal Keluar</th>
                    <th>Diagnosa Utama</th>
                    <th>Diagnosa Sekunder</th>
                    <th>Dokter</th>
                    <th>File</th>
                    </tr>
                </thead>
                <tbody id="dataTableBody">
                    @if(!empty($patients) && count($patients) > 0)
                        @foreach($patients as $patient)
                            <tr>
                            <td>{{ $patient->ID_Pasien }}</td>
                            <td>{{ $patient->nm_pasien }}</td>
                            <td>{{ $patient->umurdaftar }}</td>
                            <td>{{ $patient->tgl_lahir }}</td>
                            <td>{{ $patient->pekerjaan }}</td>
                            <td>{{ $patient->alamat }}</td>
                            <td>{{ $patient->no_rkm_medis }}</td>
                            <td>{{ $patient->jk }}</td>
                            <td>{{ $patient->tgl_registrasi }}</td>
                            <td>{{ $patient->tanggalkeluar }}</td>
                            <td>{{ $patient->diagnosis->diagnosa_utama ?? '-' }}</td>
                            <td>{{ $patient->diagnosis->diagnosa_sekunder ?? '-' }}</td>
                            <td>{{ $patient->diagnosis->nm_dokter ?? '-' }}</td>
                            <td>
                                <a href="{{ route('patients.generateReport', $patient->ID_Pasien) }}" class="btn btn-custom btn-sm">
                                    Generate
                                </a>
                            </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center">No data available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>