<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($pages) ? $pages : APP_NAME; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
    }

    /* Untuk scrollbar pada Firefox */
    * {
      scrollbar-width: thin !important;
      scrollbar-color: transparent transparent !important;
      /* Warna track, Warna thumb */
    }

    /* Untuk scrollbar pada Chrome, Edge, dan Safari */
    *::-webkit-scrollbar {
      width: 6px !important;
      /* Lebar scrollbar */
    }

    *::-webkit-scrollbar-thumb {
      background-color: transparent !important;
      /* Warna thumb */
    }
  </style>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

    * {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
    }


    .tr {
      padding-top: 20px;
      padding-bottom: 20px;
    }

    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }

    .b-example-divider {
      height: 3rem;
      background-color: rgba(0, 0, 0, .1);
      border: solid rgba(0, 0, 0, .15);
      border-width: 1px 0;
      box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
    }

    .b-example-vr {
      flex-shrink: 0;
      width: 1.5rem;
      height: 100vh;
    }

    .bi {
      vertical-align: -.125em;
      fill: currentColor;
    }

    .nav-scroller {
      position: relative;
      z-index: 2;
      height: 2.75rem;
      overflow-y: hidden;
    }

    .nav-scroller .nav {
      display: flex;
      flex-wrap: nowrap;
      padding-bottom: 1rem;
      margin-top: -1px;
      overflow-x: auto;
      text-align: center;
      white-space: nowrap;
      -webkit-overflow-scrolling: touch;
    }

    .feather {
      width: 16px;
      height: 16px;
    }

    /*
 * Sidebar
 */

    .sidebar {
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      z-index: 100;
      padding: 48px 0 0;
      box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
    }

    ::-webkit-scrollbar {
      width: 0px;
      scroll-behavior: smooth;
    }

    ::-webkit-scrollbar-thumb {
      background: #FF0000;
    }

    @media (max-width: 767.98px) {
      .sidebar {
        top: 5rem;
      }
    }

    .sidebar-sticky {
      height: calc(100vh - 48px);
      overflow-x: hidden;
      overflow-y: auto;
    }

    .sidebar .nav-link {
      font-weight: 500;
      color: #333;
    }

    .sidebar .nav-link .feather {
      margin-right: 4px;
      color: #727272;
    }

    .sidebar .nav-link.active {
      color: #2470dc;
    }

    .sidebar .nav-link:hover .feather,
    .sidebar .nav-link.active .feather {
      color: inherit;
    }

    .sidebar-heading {
      font-size: .75rem;
    }

    .navbar-brand {
      padding-top: .75rem;
      padding-bottom: .75rem;
      box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
    }

    .navbar .navbar-toggler {
      top: .25rem;
      right: 1rem;
    }

    .navbar .form-control {
      padding: .75rem 1rem;
    }

    .form-control-dark {
      color: rgb(48, 47, 47);
      background-color: rgba(51, 51, 51, 0.534);
      border-color: rgba(255, 255, 255, .1);
    }

    .form-control-dark::placeholder {
      color: #fff;
    }

    .form-control-dark:focus {
      border-color: transparent;
      box-shadow: 0 0 0 3px rgba(255, 255, 255, .25);
    }

    .ico {
      height: 26;
    }

    /* Initial style for the nav-link */
    .nav-link {
      transition: transform 0.1s ease-in-out;
      /* Add a smooth transition effect */
    }

    /* Style for the nav-link when hovered */
    .nav-link:hover {
      transform: scale(0.95);
      /* Reduce the size to 95% of the original */
    }

    .text-yellow {
      color: #fb7c04;
    }
  </style>
</head>

<body>
  <!-- content -->
  <!-- Navbar -->
  <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="index.php"><?php echo substr(APP_NAME, 0, 12); ?></a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <input class="form-control form-control-dark w-100 rounded-0 border-0 " type="text" placeholder="Search" aria-label="Search">
    <div class="navbar-nav">
      <div class="nav-item text-nowrap">
        <a class="nav-link px-3" href="../auth/logout.php">Sign out</a>
      </div>

    </div>
  </header>
  <!-- Navbar -->

  <!-- Sidebar -->
  <div class="container-fluid">
    <div class="row">
      <nav id="sidebarMenu" class="col-sm-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="position-sticky pt-3 sidebar-sticky">
          <ul class="nav flex-column">
            <!-- Menu Dashboard -->

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle  pt-lg-0 pt-1" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="rounded-circle pt-2 me-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                  </svg>
                </span>
                <?php
                // Check if the user is logged in and session variables are set
                if (isset($_SESSION['username']) && isset($_SESSION['user_rank'])) {
                  $username = $_SESSION['username'];
                  $userRank = $_SESSION['user_rank'];

                  // Define role labels based on user_rank value
                  $roleLabels = [
                    1 => 'User',
                    2 => 'Admin',
                    3 => 'S. Admin',
                  ];

                  // Check if the user_rank is a valid key in the roleLabels array
                  if (array_key_exists($userRank, $roleLabels)) {
                    $userRoleLabel = $roleLabels[$userRank];
                    echo "$username - <span class='text-yellow'>$userRoleLabel</span>";
                  } else {
                    // If user_rank is not recognized, you can display a default role or handle it as needed.
                    echo "$username - <span class='text-danger'>Unknown Role</span>";
                  }
                } else {
                  // If session variables are not set (user not logged in), you can display a default message or handle it as needed.
                  echo "Guest";
                }
                ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-dark">
                <li><a class="dropdown-item" href="../auth/logout.php">Sign Out</a></li>
              </ul>
            </li>


            <hr>


            <li class="nav-item  mt-3 mt-lg-4">
              <a href="index.php" class="nav-link mx-2  <?php echo isset($pages) && strtolower($pages) === 'admin dashboard' ? 'bg-dark text-light rounded-1' : ''; ?>">
                <span class="me-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-speedometer" viewBox="0 0 16 16">
                    <path d="M8 2a.5.5 0 0 1 .5.5V4a.5.5 0 0 1-1 0V2.5A.5.5 0 0 1 8 2zM3.732 3.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 8a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 8zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 7.31A.91.91 0 1 0 8.85 8.569l3.434-4.297a.389.389 0 0 0-.029-.518z" />
                    <path fill-rule="evenodd" d="M6.664 15.889A8 8 0 1 1 9.336.11a8 8 0 0 1-2.672 15.78zm-4.665-4.283A11.945 11.945 0 0 1 8 10c2.186 0 4.236.585 6.001 1.606a7 7 0 1 0-12.002 0z" />
                  </svg>
                </span>
                Dashboard
              </a>
            </li>

            <li class="nav-item">
              <a href="users.php" class="nav-link mx-2  <?php echo isset($pages) && strtolower($pages) === 'daftar users' ? 'bg-dark text-light rounded-1' : ''; ?>">
                <span class="me-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-gear" viewBox="0 0 16 16">
                    <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm.256 7a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Zm3.63-4.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382l.045-.148ZM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z" />
                  </svg>
                </span>
                Users
              </a>
            </li>
            <li class="nav-item">
              <a href="key-management.php" class="nav-link mx-2  <?php echo isset($pages) && strtolower($pages) === 'manage key' ? 'bg-dark text-light rounded-1' : ''; ?>">
                <span class="me-2">
                  <i class="bi bi-person-vcard"></i>
                </span>
                Manage Key
              </a>
            </li>



            <hr class="mt-3 mt-lg-5">
            <!-- tampilan submenu -->
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-3 text-muted text-uppercase">
              <span>Users Preferences</span>
              <a class="link-secondary" href="#" aria-label="Add a new report">
                <span data-feather="plus-circle" class="align-text-bottom"></span>
              </a>
            </h6>
            <ul class="nav flex-column mb-2">
              <li class="nav-item">
                <a class="nav-link  mx-2  <?php echo isset($pages) && strtolower($pages) === 'users' ? 'bg-dark text-light rounded-1' : ''; ?>" href="profiles.php">
                  <span class="me-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                      <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
                    </svg>
                  </span>
                  Profile User
                </a>
              </li>


            </ul>
        </div>
      </nav>
      <!-- Sidebar -->

      <!-- Konten paling atas -->
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2"><?php echo isset($pages) ? $pages : APP_NAME; ?></h1>
          <div class="btn-toolbar mb-2 mb-md-0">

            <div class="dropdown">
              <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span data-feather="calendar" class="align-text-bottom"></span>
                Minggu ini
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Minggu ini</a></li>
                <li class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Minggu lalu</a></li>
                <li class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">2 Minggu lalu</a></li>
              </ul>

            </div>

          </div>
        </div>
        <!-- Konten-->