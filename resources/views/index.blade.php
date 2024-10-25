<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard RS Paru Madiun</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
  <!-- Custom CSS -->
  <style>
    html, body {
      overflow-y: scroll; /* Menyembunyikan scrollbar vertikal */
    }
        /* Navbar styling */
    .navbar {
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative; /* Prevents unwanted shifts */
      width: 100%; /* Ensures the navbar spans the full width */
    }

    /* Search form styling */
    .form-control-lg {
      width: 100%; /* Prevents resizing when typing */
      max-width: 600px; /* Limits the maximum width */
    }

    .input-group {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Adjust margin or padding if needed */
    .navbar-nav {
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      flex-direction: row;
    }
    /* Navbar styling */
    .dashboard-item {
      display: flex;
      align-items: center;
      background-color: white;
      border-radius: 10px;
      margin: 10px;
      padding: 10px;
      flex-shrink: 0;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      transition: box-shadow 0.3s ease-in-out;
    }
    .dashboard-item:hover {
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    .icon-wrapper {
      width: 50px;
      height: 50px;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 5px;
      margin-right: 15px;
    }
    .icon-blue { background-color: #17a2b8; color: white; }
    .icon-green { background-color: #28a745; color: white; }
    .icon-yellow { background-color: #ffc107; color: white; }
    .icon-red { background-color: #dc3545; color: white; }
    .dashboard-content {
      font-size: 18px;
      font-weight: bold;
    }
  </style>
</head>
<body>
<div class="wrapper">
  <!-- Search Section -->
  <section class="content">
    <div class="container-fluid">
        <h2 class="text-center display-4 mt-5">Dashboard</h2>
        <div class="row">
            <div class="col-md-8 offset-md-2 mt-3">
                <div class="input-group">
                    <input id="search-input" type="search" class="form-control form-control-lg" placeholder="Type your keywords here">
                    <div class="input-group-append">
                        <button id="search-btn" class="btn btn-lg btn-default">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid my-5">
      <div class="row mt-5 m-4" id="dashboard-container">
        <!-- Dashboard Items -->
        @php
          $menus = [
            ['name' => 'Paru & Pernapasan', 'icon' => 'fa-envelope', 'color' => 'blue'],
            ['name' => 'Radiologi', 'icon' => 'fa-tasks', 'color' => 'green'],
            ['name' => 'Jantung & Pembulu Darah', 'icon' => 'fa-chart-bar', 'color' => 'yellow'],
            ['name' => 'Penyakit Dalam', 'icon' => 'fa-star', 'color' => 'red'],
            ['name' => 'Laboratorium', 'icon' => 'fa-cogs', 'color' => 'blue'],
            ['name' => 'Anak', 'icon' => 'fa-calendar-alt', 'color' => 'green'],
            ['name' => 'Rehab Medik', 'icon' => 'fa-comments', 'color' => 'yellow'],
            ['name' => 'Akupuntur Medik', 'icon' => 'fa-bell', 'color' => 'red'],
            ['name' => 'Saraf Neurologi', 'icon' => 'fa-users', 'color' => 'blue'],
            ['name' => 'Psikologi', 'icon' => 'fa-folder-open', 'color' => 'green'],
            ['name' => 'Kandungan & Kebidanan', 'icon' => 'fa-chart-pie', 'color' => 'yellow'],
            ['name' => 'Jiwa', 'icon' => 'fa-life-ring', 'color' => 'red'],
            ['name' => 'Pelayanan BPJS', 'icon' => 'fa-shield-alt', 'color' => 'blue'],
            ['name' => 'Billing', 'icon' => 'fa-file-invoice-dollar', 'color' => 'green'],
            ['name' => 'Projects', 'icon' => 'fa-briefcase', 'color' => 'yellow'],
            ['name' => 'Help Center', 'icon' => 'fa-question-circle', 'color' => 'red']
          ];
        @endphp

        @foreach($menus as $menu)
          <div class="col-md-3 dashboard-item-container">
            <div class="dashboard-item">
              <div class="icon-wrapper icon-{{ $menu['color'] }}">
                <i class="fas {{ $menu['icon'] }}"></i>
              </div>
              <div class="dashboard-content">{{ $menu['name'] }}</div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
</div>

<!-- jQuery -->
<script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>

<!-- Search Functionality -->
<script>
  $(document).ready(function() {
    $('#search-btn').on('click', function() {
      filterMenus();
    });

    $('#search-input').on('keyup', function() {
      filterMenus();
    });

    function filterMenus() {
      var searchValue = $('#search-input').val().toLowerCase();
      $('.dashboard-item-container').each(function() {
        var itemName = $(this).find('.dashboard-content').text().toLowerCase();
        if (itemName.startsWith(searchValue)) {  // Menggunakan startsWith alih-alih includes
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    }

    $('#search-input').on('search', function(){
      $('.dashboard-item-container').show();
    });
  });
</script>
</body>
</html>
