

<!DOCTYPE html>
<?php require_once('ini.php'); ?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo isset($title) ? $title : ''; ?>  - FKL Automation System</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="./plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="./dist/css/adminlte.min.css">


    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="./plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="./plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="./plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="./plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="./plugins/jqvmap/jqvmap.min.css">
    <link rel="stylesheet" href="./dist/css/adminlte.min.css">
    <link rel="stylesheet" href="./plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="./plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="./plugins/summernote/summernote-bs4.min.css">

    <link rel="stylesheet" href="./plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="./plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="./plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <style>
    .flex-wrap{
                            display: block;
                            text-align: end;
                            padding-bottom: 5px;
                        }
                        .flex-wrap .btn{
                            padding: .1rem .75rem;
                            font-size: 0.8rem;
                        }
                        .table td, .table th{
                            padding: 0.3rem;
                        }
                        .dataTables_length{
                            text-align: end;
                        }

                        /* Custom CSS to align DataTable elements horizontally */
          .dataTables_wrapper .dt-buttons {
              float: right;
              margin-right: 10px;  /* Add some spacing between the buttons and other elements */
          }

          .dataTables_wrapper .dataTables_length,
          .dataTables_wrapper .dataTables_filter {
              float: left;
              margin-right: 15px;  /* Add some space between the dropdown and search box */
          }

          .dataTables_wrapper .dataTables_info {
              float: left;
              margin-right: 10px;  /* Space between info text and other elements */
          }

          .dataTables_wrapper .dataTables_paginate {
              float: right;
          }

          /* Optional: Add a clear to ensure everything clears after the row is done */
          .dataTables_wrapper {
              overflow: hidden;
          }
          .form-control-sm{
            border-radius: 5px;
          }
        .form-group{
            margin-bottom: 0.5rem;
              }
        .col-form-label {
            padding-top: 0;
            padding-bottom: 0;
            margin-bottom: 0;
        }
        .form-control{
            display: block;
    width: 100%;
    height: calc(1.8rem + 3px);
    padding: .375rem .75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #000000;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #dadee1;
    border-radius: .25rem;
    box-shadow: inset 0 0 0 transparent;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }

        .select2-container--bootstrap4 .select2-selection--single{
        height: calc(1.5em + .3rem + 2px) !important;
        }
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered{
            padding-left: .75rem;
            line-height: calc(1.5em + .3rem);
            color: #495057;
        }
        .select2-container--bootstrap4 .select2-selection{
            border-radius: 5px;
            border-bottom: 1px solid #ced4da;
        }
        .select2-search--dropdown .select2-search__field{
            padding: 0px;
            border: 1px solid #ced4da;
            border-radius: 0;
        }

        .select2-container--bootstrap4 .select2-results__option {
            padding: 5px;
        }
        
  </style>

</head>

