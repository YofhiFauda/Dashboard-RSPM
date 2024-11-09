<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan BPJS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        html, body {
            overflow-y: scroll;
        }
        body {
            background-color: #f8f9fa; /* Light background color for a clean look */
        }
        .navbar {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
        }
        .navbar-brand {
            font-weight: bold; /* Bold font for the brand */
            font-size: 1.5rem; /* Increased font size */
        }
        .navbar .btn {
            margin-left: 1rem; /* Spacing between buttons */
        }
        .table th {
            background-color: #007bff; /* Bootstrap primary color */
            color: white;
        }
        .table {
            border-radius: 8px; /* Rounded corners for table */
            overflow: hidden; /* Clip borders */
        }
        .download-btn {
            background-color: #4CAF50; /* Green color */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .download-btn:hover {
            background-color: #45a049; /* Darker green on hover */
        }
        h3 {
            margin-bottom: 20px; /* Spacing below heading */
        }
        
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid" style="margin-right:10px; margin-left:10px">
        <a class="navbar-brand" href="{{ url('/') }}">RSPM</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="ms-auto"> <!-- Added margin for right alignment -->
                <a class="btn btn-outline-secondary" href="{{ url('/') }}">
                    Dashboard
                </a>
                <a class="btn btn-outline-secondary" href="{{ route('layanan-bpjs') }}">
                    Layanan Bpjs
                </a>
                <a class="btn btn-outline-secondary" href="{{ route('layanan-bpjs.resumePasien') }}">
                    Resume Pasien
                </a>
                <a class="btn btn-outline-secondary" href="{{ route('layanan-bpjs.generateReport') }}">
                     Generate Pasien
                </a>


                <!-- Button Navbar -->
                <!-- <form action="{{ route('layanan-bpjs.generateReport') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary">
                        Generate Jasper
                    </button>
                </form> -->
            </div>
        </div>
    </div>
</nav>

<!-- Tabel Data BPJS -->
<div class="container mt-4">
    <h3 class="text-center">Data Pelayanan BPJS</h3>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID Pasien</th>
                        <th>Nama Pasien</th>
                        <th>Tanggal Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th>No BPJS</th>
                        <th>Tanggal Pelayanan</th>
                        <th>Jenis Pelayanan</th>
                        <th>Dokter</th>
                        <th>Keterangan</th>
                        <th>File Jasper</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($data as $item)
                    <tr>
                        <td>{{ $item->ID_Pasien }}</td>
                        <td>{{ $item->Nama_Pasien }}</td>
                        <td>{{ $item->Tanggal_Lahir }}</td>
                        <td>{{ $item->Jenis_Kelamin }}</td>
                        <td>{{ $item->No_BPJS }}</td>
                        <td>{{ $item->Tanggal_Pelayanan }}</td>
                        <td>{{ $item->Jenis_Pelayanan }}</td>
                        <td>{{ $item->Dokter }}</td>
                        <td>{{ $item->Keterangan }}</td>
                        <td>
                            @if($item->file_jasper)
                                <form action="{{ route('layanan-bpjs.generatePDF') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->ID_Pasien }}">
                                    <button type="submit" class="btn btn-link p-0" title="Generate PDF">
                                        <i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size: 1.5em;"></i>
                                    </button>
                                </form>
                            @else
                                <span class="text-muted">No file</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
