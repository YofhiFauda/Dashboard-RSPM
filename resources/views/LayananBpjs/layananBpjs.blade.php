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
    .download-btn {
        background-color: #4CAF50; /* Warna hijau */
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }
    .download-btn:hover {
        background-color: #45a049; /* Warna hijau lebih gelap saat di-hover */
    }
    
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <a class="btn btn-outline-secondary back-btn" href="{{ url('/') }}">
            <i class="bi bi-arrow-left"></i>
        </a>
        <a class="navbar-brand mx-auto" href="#">Layanan BPJS - PDF Generator</a>
        <!-- Placeholder untuk menjaga kesetimbangan -->
        <span class="navbar-text"></span>
    </div>
</nav>

    <!-- Tabel Data BPJS -->
    <div class="row justify-content-center mt-4">
            <div class="col-md-10">
                <h3>Data Pelayanan BPJS</h3>
                <table class="table table-bordered">
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
