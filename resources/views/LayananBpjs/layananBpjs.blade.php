<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPJS PDF Generator</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Layanan BPJS - PDF Generator</a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Convert Jasper File to PDF</div>
                    <div class="card-body">
                        <!-- Form upload -->
                        <form action="{{ route('layanan-bpjs.uploadFile') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="jasperFile" class="form-label">Upload Jasper File</label>
                                <input class="form-control" type="file" id="jasperFile" name="file" accept=".jasper" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload File</button> <!-- Tombol untuk mengunggah file -->
                        </form>
                        @if(session('success'))
                            <div>{{ session('success') }}</div>
                        @endif

                        <div class="mt-2">
                            <!-- Tambahkan tombol untuk generate PDF -->
                            <form action="{{ route('layanan-bpjs.generatePDF') }}" method="POST">
                                @csrf
                                <button type="submit" class="download-btn">Generate PDF</button>
                            </form>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger mt-3">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
