<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan BPJS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
    }

    .navbar {
        background-color: #405D72;
        color: #FFF2E1;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand, .navbar .btn {
        color: #405D72 !important;
    }
    .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

    .navbar .btn:hover {
        background-color: #8badd0; /* Accent color for button hover */
        color: white;
    }

    .btn-custom {
    background-color: #405D72;
    color: white; /* White text color */
    border: none; /* Remove border */
    }

    .btn-custom:hover {
        background-color: #8badd0; /* Hover background color */
        color: white; /* Text color on hover */
    }


    .table-container {
        background-color: #ffffff; /* Light tan for the table container background */
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    .table th, .table td {
        text-align: start;
        vertical-align: middle;
    }

    .table thead {
        background-color: #A79277; /* Primary color for the table headers */
        color: #FFF2E1;
    }

    .form-select, .form-control {
        border: 1px solid #405D72; /* Accent color for form borders */
        color: #A79277;
    }

    .form-select:focus, .form-control:focus {
        border-color: #405D72; /* Primary color for focused form elements */
        box-shadow: none;
    }

    .pagination-container .page-item .page-link {
        color: #405D72;
    }

    .pagination-container .page-item.active .page-link, 
    .pagination-container .page-item .page-link:hover {
        background-color: #405D72; /* Primary color for active and hover states */
        color: #FFF2E1;
        border-color: #405D72;
    }
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.25rem;
            }
            .table th, .table td {
                font-size: 0.9rem;
            }
        }
        .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .badge {
        font-size: 1rem;
        font-weight: bold;
    }

    .card-title {
        font-size: 1.25rem;
        color: #333;
    }

    /* Untuk mengoptimalkan tampilannya pada perangkat mobile */
    @media (max-width: 576px) {
        .card-body {
            padding: 2rem;
        }

        .card-title {
            font-size: 1.1rem;
        }

        .badge {
            font-size: 0.9rem;
        }
    }
    
    .age-distribution-list {
    list-style: none; /* Hilangkan bullet default */
    padding: 0;
    }

    .age-distribution-list li {
        margin-bottom: 8px; /* Jarak antar item */
        position: relative; /* Untuk posisi bullet custom */
        padding-left: 25px; /* Beri ruang untuk bullet */
        color: black; /* Teks tetap hitam */
    }

    .age-distribution-list li::before {
        content: ''; /* Bullet custom */
        position: absolute;
        left: 0;
        top: 7px; /* Posisikan agar sejajar teks */
        width: 12px;
        height: 12px;
        border-radius: 50%; /* Buat menjadi lingkaran */
        background-color: var(--bullet-color, black); /* Warna sesuai CSS variabel */
    }

    .dropdown-large {
        width: 56rem; /* Lebar dropdown 900px */
        height: 13rem;
        display: none; /* Default disembunyikan */
    }

    .dropdown-item{
        height: 5vh;
        margin: 5px;
    }

    .nav-item.dropdown:hover .dropdown-menu {
        display: block; /* Tampilkan saat hover */
    }

    #dropdownMenu {
        transition: transform 0.2s ease-in-out;
    }

    /* Styling untuk Toast */
    #toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    .toast {
        margin-bottom: 10px;
        min-width: 250px;
        background-color: rgba(23,162,184,.9)!important;
        color: white;
        border-radius: 5px;
        padding: 10px;
    }

    .toast-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 16px;
        font-weight: bold;
        color: white;
        background-color: rgba(23,162,184,.9)!important;
        padding-bottom: 5px; /* Memberikan ruang di bawah header */
        border-bottom: 1px solid #ffffff; /* Divider garis putih di bawah header */
    }

    .toast-body {
        font-size: 14px;
        background-color: rgba(23,162,184,.9)!important;
    }

    /* Untuk tombol close */
    .close {
        color: white;
        border: none;
        background-color: transparent;
        font-size: 20px;
        cursor: pointer;
        padding: 0;
        margin: 0;
    }



    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid px-5">
        <a class="navbar-brand" href="{{ url('/') }}">RSPM</a>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Dashboard
                    </a>
                    <div class="dropdown-menu dropdown-large p-4" aria-labelledby="dropdownMenu">
                        <div class="row">
                            <div class="col-3">
                                <a class="dropdown-item" href="#">Paru & Pernapasan</a>
                                <a class="dropdown-item" href="#">Radiologi</a>
                                <a class="dropdown-item" href="#">Jantung & Pembulu Darah</a>
                                <a class="dropdown-item" href="#">Penyakit Dalam</a>
                            </div>
                            <div class="col-3">
                                <a class="dropdown-item" href="#">Laboratorium</a>
                                <a class="dropdown-item" href="#">Anak</a>
                                <a class="dropdown-item" href="#">Rehab Medik</a>
                                <a class="dropdown-item" href="#">Akupuntur Medik</a>
                            </div>
                            <div class="col-3">
                                <a class="dropdown-item" href="#">Saraf Neurologi</a>
                                <a class="dropdown-item" href="#">Psikologi</a>
                                <a class="dropdown-item" href="#">Kandungan & Kebidanan</a>
                                <a class="dropdown-item" href="#">Jiwa</a>
                            </div>
                            <div class="col-3">
                                <a class="dropdown-item" href="{{ url('/patients') }}">Pelayanan BPJS</a>
                                <a class="dropdown-item" href="#">Billing</a>
                                <a class="dropdown-item" href="#">Projects</a>
                                <a class="dropdown-item" href="#">Help Center</a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/patients') }}">Statistik Pasien</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/resume-pasien') }}">Resume Pasien</a>
                </li>
            </ul>
        </div>
        <div>
            <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search..." value="{{ request('search') }}">
        </div>
    </div>
</nav>

<div class="container" style="max-width:100%">
    <!-- Statistik Kartu -->
    <div class="row g-4 mt-4">
        <!-- Kartu Jumlah Pasien Baru Bulan Ini -->
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0 text-center rounded-4">
                <div class="card-body py-4">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-person-plus fs-3 text-primary me-2"></i>
                        <span class="badge {{ $newPatientsPercentageChange >= 0 ? 'bg-success' : 'bg-danger' }} text-white rounded-pill p-2" data-bs-toggle="tooltip" title="{{ abs(number_format($newPatientsPercentageChange, 2)) }}%  {{$dischargedPatientsPercentageChange >= 0 ? 'Higher than last month' : 'Lower than last month'}}">
                            {{ abs(number_format($newPatientsPercentageChange, 2)) }}% 
                            <i class="bi {{ $newPatientsPercentageChange >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                        </span>
                    </div>
                    <h5 class="card-title fw-bold text-dark">{{ $newPatientsThisMonth }}</h5>
                    <p class="text-muted mb-0">Pasien Baru</p>
                </div>
            </div>
        </div>

        <!-- Kartu Jumlah Pasien Pulang Bulan Ini -->
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0 text-center rounded-4">
                <div class="card-body py-4">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-box-arrow-right fs-3 text-success me-2"></i>
                        <span class="badge {{ $dischargedPatientsPercentageChange >= 0 ? 'bg-success' : 'bg-danger' }} text-white rounded-pill p-2" data-bs-toggle="tooltip" title="{{ abs(number_format($dischargedPatientsPercentageChange, 2)) }}% {{$dischargedPatientsPercentageChange >= 0 ? 'Higher than last month' : 'Lower than last month'}}">
                            {{ abs(number_format($dischargedPatientsPercentageChange, 2)) }}% 
                            <i class="bi {{ $dischargedPatientsPercentageChange >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                        </span>
                    </div>
                    <h5 class="card-title fw-bold text-dark">{{ $dischargedPatientsThisMonth }}</h5>
                    <p class="text-muted mb-0">Pasien Pulang</p>
                </div>
            </div>
        </div>

        <!-- Kartu Persentase Pasien Pulih Bulan Ini -->
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0 text-center rounded-4">
                <div class="card-body py-4">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-heart-pulse fs-3 text-info me-2"></i>
                        <span class="badge {{ $recoveredPatientsPercentageChange >= 0 ? 'bg-success' : 'bg-danger' }} text-white rounded-pill p-2" data-bs-toggle="tooltip" title="{{ abs(number_format($recoveredPatientsPercentageChange, 2)) }}% {{$dischargedPatientsPercentageChange >= 0 ? 'Higher than last month' : 'Lower than last month'}}">
                            {{ abs(number_format($recoveredPatientsPercentageChange, 2)) }}% 
                            <i class="bi {{ $recoveredPatientsPercentageChange >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                        </span>
                    </div>
                    <h5 class="card-title fw-bold text-dark">{{ $recoveredPatientsThisMonth }}</h5>
                    <p class="text-muted mb-0">Persentase Pemulihan</p>
                </div>
            </div>
        </div>

        <!-- Kartu Rata-Rata Durasi Rawat Inap Bulan Ini -->
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0 text-center rounded-4">
                <div class="card-body py-4">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-clock-history fs-3 text-warning me-2"></i>
                        <span class="badge {{ $stayChangePercentage >= 0 ? 'bg-success' : 'bg-danger' }} text-white rounded-pill p-2" data-bs-toggle="tooltip" title="{{ abs(number_format($stayChangePercentage, 2)) }}% {{$dischargedPatientsPercentageChange >= 0 ? 'Higher than last month' : 'Lower than last month'}}">
                            {{ abs(number_format($stayChangePercentage, 2)) }}% 
                            <i class="bi {{ $stayChangePercentage >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                        </span>
                    </div>
                    <h5 class="card-title fw-bold text-dark">{{ $averageStayThisMonth }} Hari</h5>
                    <p class="text-muted mb-0">Rata-Rata Lama Rawat Inap</p>
                </div>
            </div>
        </div>
    </div>

    <!-- DEMOGRAFI PASIEN -->
    <div class="row mt-4">
        <!-- Bar Chart for Patient Registration Distribution -->
        <div class="col-md-8 mb-4" style="height: 100%; max-width: 90%; margin: auto;">
            <div class="card">
                <div class="card-header border-0" style="background-color:white">
                    Distribusi Registrasi Pasien (Per Tahun)
                </div>
                <div class="card-body">
                    <canvas id="registrationDistributionChart"
                        data-registrations-by-month-current="{{ json_encode($registrationsByMonthCurrentYear) }}"
                        data-registrations-by-month-last="{{ json_encode($registrationsByMonthLastYear) }}"
                        data-month-labels="{{ json_encode($monthLabels) }}"></canvas>
                </div>
            </div>
        </div>

        <!-- Pie Chart for Age Distribution -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header border-0" style="background-color:white;">
                    Distribusi Umur Pasien
                </div>
                    <div class="card-body mt-3 ">
                        <canvas id="ageDistributionChart" style="max-width: 60%; margin: auto;" 
                            data-age-values="{{ json_encode($ageGroups) }}"
                            data-age-labels="{{ json_encode($ageGroups) }}"></canvas>
                        <div class="mt-3">
                        <ul class="text-left age-distribution-list">
                            @php
                                $colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'];
                                $index = 0;
                            @endphp
                            @foreach ($ageGroupsWithPercentage as $range => $data)
                                <li style="--bullet-color: {{ $colors[$index] }}">
                                    <span style="font-weight: bold;">{{ $range }}</span>: 
                                    {{ $data['count'] }} pasien ({{ $data['percentage'] }}%)
                                </li>
                                @php $index++; @endphp
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- PENDAFTARAN PASIEN TERBARU -->
    <div class="row">
        <div class="col-md-8 mb-4" style="height: 100%; max-width: 90%; margin: 0;">
            <div class="table-container">


                <div class="table-responsive" style="border-radius: 8px;border: 1px solid #e4e4e4;">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama Pasien</th>
                            <th scope="col">No Rekam Medis</th>
                            <th scope="col">Tanggal Registrasi</th>
                            <th scope="col">Diagnosa Utama</th>
                            <th scope="col">Diagnosa Sekunder</th>
                            <th scope="col">Dokter</th>
                            </tr>
                        </thead>
                        <tbody id="dataTableBody">
                            @if(!empty($patientsToday ) && count($patientsToday ) > 0)
                                @foreach($patientsToday  as $patient)
                                    <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $patient->nm_pasien }}</td>
                                    <td>{{ $patient->no_rkm_medis }}</td>
                                    <td>{{ $patient->tgl_registrasi }}</td>
                                    <td>{{ $patient->diagnosis->diagnosa_utama ?? '-' }}</td>
                                    <td>{{ $patient->diagnosis->diagnosa_sekunder ?? '-' }}</td>
                                    <td>{{ $patient->diagnosis->nm_dokter ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            @else
                                    <td colspan="1" class="text-center">No data available</td>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- PASIEN KONDISI PULANG -->
        <div class="col-md-4">
            <div class="table-container">
                <div class="table-responsive" style="border-radius: 8px;border: 1px solid #e4e4e4;">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama Pasien</th>
                            <th scope="col">No Rekam Medis</th>
                            <th scope="col">Kondisi</th>
                            </tr>
                        </thead>
                        <tbody id="dataTableBody">
                            @if(!empty($patientsToday ) && count($patientsToday ) > 0)
                                @foreach($patientsToday  as $patient)
                                    <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $patient->nm_pasien }}</td>
                                    <td>{{ $patient->no_rkm_medis }}</td>
                                    <td>{{ $patient->kondisi_pulang }}</td>
                                    </tr>
                                @endforeach
                            @else
                                    <td colspan="1" class="text-center">No data available</td>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
        

    <!-- RESUME PASIEN -->
    <div class="table-container">
        <form method="GET" action="{{ url()->current() }}">
            <div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
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
                <div class="d-flex justify-content-end mb-3">
                    <div>
                        <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                    </div>
                        <!-- Filter Button with Icon -->
                    <button class="btn btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="position: relative; margin-left:5px">
                        <i class="bi bi-funnel"></i> <!-- Filter icon -->
                    </button>
                    
                    <!-- Filter Dropdown Card -->
                    <div class="dropdown-menu p-3" style="min-width: 300px;">
                        <h5 style="display:flex; justify-content:space-between">Filters <a href="{{ url()->current() }}" class="text-danger ms-3" style="font-size: 0.9rem;">Reset</a></h5>
                        
                        <form method="GET" action="{{ url()->current() }}">

                            <!-- Created from date -->
                            <div class="mb-2">
                                <label for="start_date" class="form-label">Created from</label>
                                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}" placeholder="dd/mm/yyyy">
                            </div>

                            <!-- Created until date -->
                            <div class="mb-2">
                                <label for="end_date" class="form-label">Created until</label>
                                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}" placeholder="dd/mm/yyyy">
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mt-3">Apply Filters</button>
                        </form>
                    </div>
                 </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
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
                                <a href="{{ route('patients.generateReport', $patient->ID_Pasien) }}" class="btn btn-custom btn-sm btn-generate" data-id="{{ $patient->ID_Pasien }}">
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
            <div class="pagination-container">
                {{ $patients->links('pagination::bootstrap-5') }}
            </div>
    </div>
</div>

<!-- Tambahkan Toast Container -->
<div id="toast-container" class="toasts-top-right fixed"></div>


<!-- Function Alert after Generate Jasper -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-generate').forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Mencegah aksi default tombol
            const url = this.getAttribute('href'); // Ambil URL untuk unduhan
            const patientId = this.getAttribute('data-id'); // Ambil ID pasien

            // Tampilkan Toast untuk proses unduh
            showToast(`Mohon tunggu, laporan akan segera diunduh`);

            // Navigasi ke URL untuk memulai unduhan
            window.location.href = url;
        });
    });

    // Fungsi untuk menampilkan Toast
    function showToast(message) {
        const toastContainer = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = 'toast bg-info';
        toast.style.minWidth = '250px';
        toast.innerHTML = `
            <div class="toast-header">
                <strong class="mr-auto">Laporan Sedang Diunduh</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        `;
        toastContainer.appendChild(toast);

        // Tampilkan Toast
        $(toast).toast({ delay: 8000 }).toast('show');

        // Hapus Toast setelah selesai
        $(toast).on('hidden.bs.toast', function () {
            toast.remove();
        });
    }
});
</script>

    <!-- FILTER TANGGAL -->
<script>
    function clearFilters() {
        document.querySelector('input[name="start_date"]').value = '';
        document.querySelector('input[name="end_date"]').value = '';
        document.querySelector('input[name="search"]').value = '';
        document.getElementById('entriesPerPage').selectedIndex = 0; // Optional: reset items per page
        document.forms[0].submit();
    }
</script>

    <!-- DEMOGRAFI PASIEN -->
    <script>
        // Data untuk Pie Chart Distribusi Usia
        const ageData = <?php echo json_encode(array_values($ageGroups)); ?>;
        const ageLabels = <?php echo json_encode(array_keys($ageGroups)); ?>;
        
        const ageCtx = document.getElementById('ageDistributionChart').getContext('2d');
        new Chart(ageCtx, {
            type: 'pie',
            data: {
                labels: ageLabels,
                datasets: [{
                    label: 'Distribusi Usia Pasien',
                    data: ageData,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
                }]
            },
    });

    // Data untuk Line Chart Distribusi Registrasi
    const registrationsByMonthCurrent = JSON.parse(document.getElementById('registrationDistributionChart').getAttribute('data-registrations-by-month-current'));
    const registrationsByMonthLast = JSON.parse(document.getElementById('registrationDistributionChart').getAttribute('data-registrations-by-month-last'));
    const monthLabels = JSON.parse(document.getElementById('registrationDistributionChart').getAttribute('data-month-labels'));

    const ctx = document.getElementById('registrationDistributionChart').getContext('2d');
    const registrationDistributionChart = new Chart(ctx, {
        type: 'line',  // Line Chart
        data: {
            labels: monthLabels,  // Months (1-12)
            datasets: [
                {
                    label: "Registrasi Pasien Tahun Ini",
                    data: Object.values(registrationsByMonthCurrent),
                    backgroundColor: 'rgba(54, 162, 235, 1)',  // Line color for current year
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                    pointBorderColor: 'rgba(54, 162, 235, 1)',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                },
                {
                    label: "Registrasi Pasien Tahun Lalu",
                    data: Object.values(registrationsByMonthLast),
                    backgroundColor: 'rgba(173, 173, 173, 0.8)',  // Line color for last year
                    borderColor: 'rgba(173, 173, 173, 0.8)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(173, 173, 173, 0.8)',
                    pointBorderColor: 'rgba(173, 173, 173, 0.8)',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Angka Pendaftaran'
                    }
                }
            }
        }
    });
</script>


    <!-- TOOLTIP TITLE -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>
</html>