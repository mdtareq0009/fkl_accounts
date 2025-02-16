

<body class="hold-transition sidebar-mini sidebar-collapse">
            <div class="wrapper">

                <!-- Preloader -->
                <div class="preloader flex-column justify-content-center align-items-center">
                    <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="50" width="100">
                </div>


<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <style>
        .navbar-expand .navbar-nav .nav-link{
             padding-right: 0rem; 
                padding-left: 1rem;
                font-size: 20px;
        }
        .dropdown-footer{
            text-align: left;
        }
        .dropdown-menu-lg{
            min-width: 0 !important;
        }
    </style>
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-lg-none d-inline-block">
        <a href="index.php" class="nav-link"> <span class="brand-text font-weight-light"> Fkl ERP System </span></a>
      </li>
    </ul>

    


      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
         
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Branch</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <li><a href="#" class="dropdown-item">FKL</a></li>
              <li><a href="#" class="dropdown-item">Fakir Eco</a></li>
              <!-- End Level two -->
            </ul>
          </li>
        </ul>
    <ul class="navbar-nav ml-auto pr-3">
    <?php $userInfo = $auth->loggedUser();?>
      <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i> <?=$userInfo['name']?>
          
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          
          <a href="users.php?page=change-password" class="dropdown-item dropdown-footer">Change Password</a>
          <div class="dropdown-divider"></div>
          <a href="?logout=1" class="dropdown-item dropdown-footer">Logout</a>
        </div>
      </li>
    </ul>


    <!-- Right navbar links -->
    
  </nav>



