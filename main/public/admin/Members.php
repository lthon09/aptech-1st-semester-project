<?php
session_start();
if (!$_SESSION["Username"]) {
  header("location:login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Members Admin</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">

  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="dist/css/toastr.css">

  <link rel="stylesheet" href="dist/css/custom/Members.css">
    <!-- datatable -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css"   >
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <?php
    // include 'Preloader.php'
    ?>
    <?php
    include 'Navheader.php'
    ?>
    <?php
    include 'SideBarContainer.php'
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <?php
      include 'ContentHeader.php'
      ?>
      <!-- Main content -->
      <section class="content">
        <button class="btn btn-sm btn-primary btn-add">Add</button>
        <table class="table table-bordered" id="tbl_Members">
          <thead>
            <tr>
              <th>Username</th>
              <th>Email</th>
              <th>Type</th>
              <th>Function</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <?php
    include 'footer.php';
    ?>

    <div class="modal fade show" id="modalMembers" aria-modal="true" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="my_form" name="my_form" method="post" enctype="multipart/form-data">
              <div class="grid">
                <div class="row">
                  <div class="form-group col-4">
                    <label for="InputName">UserName</label>
                    <input type="text" name="name" class="form-control" id="InputName" placeholder="Enter name">
                  </div>
                  <div class="form-group col-4">
                    <label for="Email">Email</label>
                    <input type="text" name="Email" class="form-control" id="Email" placeholder="Enter Email">
                  </div>
                  <div class="form-group col-3">
                    <label for="From">Check Admin</label> <br />
                    <div class="icheck-primary d-inline">
                      <input type="checkbox" id="checkAdmin" name="checkAdmin">
                      <label for="checkAdmin">
                      </label>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="icheck-primary d-inline">
                    <input type="checkbox" id="checkshowPW" checked>
                    <label for="checkshowPW">
                    </label>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-4">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Enter password" disabled>
                  </div>
                  <div class="form-group col-4">
                    <label for="confirmPassword">Confirm password</label>
                    <input type="password" name="confirmPassword" class="form-control" id="confirmPassword" placeholder="Enter confirmPassword" disabled>
                  </div>
                </div>

              </div>

              <div class="card-footer">
                <button type="submit" name="submit" class="btn btn-primary btn-themmoi">Thêm mới</button>
                <button type="submit" name="submit" class="btn btn-primary btn-capnhat" attrId="">Cập nhật</button>
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button> -->
          </div>
        </div>

      </div>

    </div>
    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->
  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- ChartJS -->
  <script src="plugins/chart.js/Chart.min.js"></script>
  <!-- Sparkline -->
  <script src="plugins/sparklines/sparkline.js"></script>
  <!-- JQVMap -->
  <script src="plugins/jqvmap/jquery.vmap.min.js"></script>
  <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
  <!-- jQuery Knob Chart -->
  <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
  <!-- daterangepicker -->
  <script src="plugins/moment/moment.min.js"></script>
  <script src="plugins/daterangepicker/daterangepicker.js"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- Summernote -->
  <script src="plugins/summernote/summernote-bs4.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.js"></script>
  <!-- AdminLTE for demo purposes -->
  <!-- <script src="dist/js/demo.js"></script> -->
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <!-- <script src="dist/js/pages/dashboard.js"></script> -->
  <script type="text/javascript" src="dist/js/custom/Members.js"></script>
  <!-- Toastr js -->
  <script src="dist/js/toastr.js"></script>
  <script src="//cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js" type="text/javascript"></script>

</body>

</html>