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
  /* Background gradient */

  html, body {
      overflow-y: hidden; /* Menyembunyikan scrollbar vertikal */
    }

  body {
    margin: 0;
    padding: 0;
    height: 100vh;
    background: linear-gradient(145deg, #f2f7f9, #d1e6f1, #a8c8de);
    font-family: 'Helvetica Neue', sans-serif;
    color: #333;
  }

  /* Navbar styling */
  .navbar {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
  }

  /* Header title */
  h2.display-4 {
    color: #4a6572;
    font-weight: bold;
    margin-top: 2rem;
  }

  /* Search form styling */
  .form-control-lg {
    width: 100%;
    max-width: 600px;
    box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
    border: 1px solid #ddd;
    border-radius: 6px;
  }

  .input-group {
      display: flex;
      align-items: center;
      justify-content: center;
    }

  .input-group-append .btn {
    border: 1px solid #4a6572;
    color: #4a6572;
    background-color: #fff;
    border-radius: 6px;
  }

  .input-group-append .btn:hover {
    color: #fff;
    background-color: #4a6572;
  }

  /* Dashboard item styling with glassmorphism */
  .dashboard-item {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.3); /* Semi-transparent white */
    border-radius: 16px;
    margin: 10px;
    padding: 20px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px); /* Blur effect for glassmorphism */
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.2);
  }

  .dashboard-item:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 20px rgba(74, 101, 114, 0.2);
    background: rgba(255, 255, 255, 0.4); /* Slightly more opaque on hover */
  }

  /* Dashboard icon styling */
  .icon-wrapper {
    width: 50px;
    height: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    margin-right: 15px;
    font-size: 1.5rem;
    color: white;
  }

  /* Soft pastel color themes */
  .icon-blue { background-color: #7da8e5; }
  .icon-green { background-color: #88d497; }
  .icon-yellow { background-color: #ffcc66; }
  .icon-red { background-color: #f28989; }

  /* Dashboard content styling */
  .dashboard-content {
    font-size: 17px;
    font-weight: 500;
    color: #2d4059;
  }
</style>



</head>
<body>
<div class="wrapper">
  <!-- Search Section -->
  <section class="content">
    <div class="container-fluid">
        <h2 class="text-center display-4 mt-5">Dashboard RSPM</h2>
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
            ['name' => 'Paru & Pernapasan', 'icon' => 'fa-lungs', 'color' => 'blue'],
            ['name' => 'Radiologi', 'icon' => 'fa-x-ray', 'color' => 'green'],
            ['name' => 'Jantung & Pembulu Darah', 'icon' => 'fa-heartbeat', 'color' => 'yellow'],
            ['name' => 'Penyakit Dalam', 'icon' => 'fa-stethoscope', 'color' => 'red'],
            ['name' => 'Laboratorium', 'icon' => 'fa-vials', 'color' => 'blue'], 
            ['name' => 'Anak', 'icon' => 'fa-baby', 'color' => 'green'],
            ['name' => 'Rehab Medik', 'icon' => 'fa-dumbbell', 'color' => 'yellow'],
            ['name' => 'Akupuntur Medik', 'icon' => 'fa-hand-sparkles', 'color' => 'red'],
            ['name' => 'Saraf Neurologi', 'icon' => 'fa-brain', 'color' => 'blue'],
            ['name' => 'Psikologi', 'icon' => 'fa-user-md', 'color' => 'green'],
            ['name' => 'Kandungan & Kebidanan', 'icon' => 'fa-baby-carriage', 'color' => 'yellow'],
            ['name' => 'Jiwa', 'icon' => 'fa-user-md', 'color' => 'red'],
            ['name' => 'Pelayanan BPJS', 'icon' => 'fa-shield-alt', 'color' => 'blue', 'route' => route('patients.index')],
            ['name' => 'Billing', 'icon' => 'fa-file-invoice-dollar', 'color' => 'green'],
            ['name' => 'Projects', 'icon' => 'fa-briefcase-medical', 'color' => 'yellow'],
            ['name' => 'Help Center', 'icon' => 'fa-question-circle', 'color' => 'red'],
          ];
        @endphp

        @foreach($menus as $menu)
          <div class="col-md-3 dashboard-item-container">
              <a href="{{ isset($menu['route']) ? $menu['route'] : '#' }}">
                  <div class="dashboard-item">
                      <div class="icon-wrapper icon-{{ $menu['color'] }}">
                          <i class="fas {{ $menu['icon'] }}"></i>
                      </div>
                      <div class="dashboard-content">{{ $menu['name'] }}</div>
                  </div>
              </a>
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
