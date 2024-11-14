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
            background-color: #FFF8F3;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #405D72;
            color: #FFF8F3;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand, .navbar .btn {
            color: #F7E7DC !important;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        .navbar .btn:hover {
            background-color: #758694;
            color: #FFF8F3;
        }
        .table-container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
        .table thead {
            background-color: #405D72;
            color: #FFF8F3;
        }
        .btn-custom {
            background-color: #405D72; /* Primary background color */
            color: white; /* White text color */
            border: none; /* Remove border */
        }

        .btn-custom:hover {
                background-color: #759cba; /* Hover background color */
                color: white; /* Text color on hover */
        }
        .form-select, .form-control {
            border: 1px solid #758694;
            color: #405D72;
        }
        .form-select:focus, .form-control:focus {
            border-color: #405D72;
            box-shadow: none;
        }
        .pagination-container .page-item .page-link {
            color: #405D72;
        }
        .pagination-container .page-item.active .page-link, 
        .pagination-container .page-item .page-link:hover {
            background-color: #405D72;
            color: #FFF8F3;
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
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-5">
        <a class="navbar-brand" href="#">RSPM</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="ms-auto">
                <a class="btn btn-outline-light me-2" href="{{ url('/') }}">Dashboard</a>
                <a class="btn btn-outline-light" href="{{ url('/patients') }}">Generate Bjps</a>
                <a class="btn btn-outline-light me-2" href="{{ url('/resume-pasien') }}">Resume Pasien</a>
            </div>
        </div>
    </div>
</nav>

<div class="container" style="max-width:100%">
    <div class="table-container" >
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
                <div class="d-flex col-md-4" style="margin-right: 20px;">
                    <div style="margin-right:10px">
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" placeholder="Dari Tanggal">
                    </div>
                    <div style="margin-right:10px">
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" placeholder="Sampai Tanggal">
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ url()->current() }}" class="btn btn-secondary">Clear</a>
                    </div>
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
                        <th>No Rawat</th>
                        <th>Keluhan Utama</th>
                        <th>Diagnosa Utama</th>
                        <th>KD Diagnosa Utama</th>
                        <th>Diagnosa Sekunder</th>
                        <th>KD Diagnosa Sekunder</th>
                        <th>Diagnosa Sekunder 2</th>
                        <th>KD Diagnosa Sekunder 2</th>
                        <th>Diagnosa Sekunder 3</th>
                        <th>File</th>
                    </tr>
                </thead>
                <tbody id="dataTableBody">
                    @if(!empty($result) && count($result) > 0)
                        @foreach($result as $item)
                            <tr>
                                <td>{{ $item->no_rawat }}</td>
                                <td>{{ $item->keluhan_utama }}</td>
                                <td>{{ $item->diagnosa_utama }}</td>
                                <td>{{ $item->kd_diagnosa_utama }}</td>
                                <td>{{ $item->diagnosa_sekunder }}</td>
                                <td>{{ $item->kd_diagnosa_sekunder }}</td>
                                <td>{{ $item->diagnosa_sekunder2 }}</td>
                                <td>{{ $item->kd_diagnosa_sekunder2 }}</td>
                                <td>{{ $item->diagnosa_sekunder3 }}</td>
                                <td>
                                    <form action="{{ route('bpjs.generateReport') }}" method="GET">
                                        @csrf
                                        <input type="hidden" name="no_rawat" value="{{ $item->no_rawat }}"> <!-- Hidden field untuk no_rawat -->
                                        <button type="submit" class="btn btn-custom btn-sm">Report</button>
                                    </form>
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
            {{ $result->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
    <!-- FILTERING TANGGAL -->
    <script>
        function clearFilters() {
            document.querySelector('input[name="start_date"]').value = '';
            document.querySelector('input[name="end_date"]').value = '';
            document.querySelector('input[name="search"]').value = '';
            document.getElementById('entriesPerPage').selectedIndex = 0; // Optional: reset items per page
            document.forms[0].submit();
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const dataTableBody = document.getElementById("dataTableBody");
            const searchInput = document.getElementById("searchInput");
            const entriesPerPage = document.getElementById("entriesPerPage");
            const pagination = document.getElementById("pagination");

            let currentPage = 1;
            let itemsPerPage = parseInt(entriesPerPage.value, 10);

            function displayData() {
                const filteredData = Array.from(dataTableBody.rows).filter(row =>
                    Array.from(row.cells).some(cell => cell.textContent.toLowerCase().includes(searchInput.value.toLowerCase()))
                );
                const start = (currentPage - 1) * itemsPerPage;
                const end = start + itemsPerPage;

                Array.from(dataTableBody.rows).forEach(row => (row.style.display = "none"));
                filteredData.slice(start, end).forEach(row => (row.style.display = "table-row"));

                renderPagination(filteredData.length, itemsPerPage);
            }

            function renderPagination(filteredCount, itemsPerPage) {
                pagination.innerHTML = ''; // Clear existing pagination

                const totalPages = Math.ceil(filteredCount / itemsPerPage);
                currentPage = Math.min(currentPage, totalPages); // Ensure currentPage does not exceed totalPages

                // Create Previous button
                const prevItem = document.createElement('li');
                prevItem.className = 'page-item' + (currentPage === 1 ? ' disabled' : '');
                prevItem.innerHTML = `<a class="page-link" href="#">Previous</a>`;
                prevItem.addEventListener('click', function () {
                    if (currentPage > 1) {
                        currentPage--;
                        displayData();
                    }
                });
                pagination.appendChild(prevItem);

                // Create page numbers
                for (let i = 1; i <= totalPages; i++) {
                    const pageItem = document.createElement('li');
                    pageItem.className = 'page-item' + (currentPage === i ? ' active' : '');
                    pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                    pageItem.addEventListener('click', function () {
                        currentPage = i;
                        displayData();
                    });
                    pagination.appendChild(pageItem);
                }

                // Create Next button
                const nextItem = document.createElement('li');
                nextItem.className = 'page-item' + (currentPage === totalPages ? ' disabled' : '');
                nextItem.innerHTML = `<a class="page-link" href="#">Next</a>`;
                nextItem.addEventListener('click', function () {
                    if (currentPage < totalPages) {
                        currentPage++;
                        displayData();
                    }
                });
                pagination.appendChild(nextItem);
            }

            entriesPerPage.addEventListener("change", function () {
                itemsPerPage = parseInt(this.value, 10);
                currentPage = 1;
                displayData();
            });

            searchInput.addEventListener("input", function () {
                currentPage = 1;
                displayData();
            });

            displayData(); // Initial display
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

