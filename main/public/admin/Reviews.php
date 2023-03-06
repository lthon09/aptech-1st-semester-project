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

  <link rel="stylesheet" href="dist/css/custom/Tours.css">
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
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Tour</th>
              <th>Author</th>
              <th>Content</th>
              <th>Rating</th>
              <th>Function</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>Bắc Ninh - Hà Nội</td>
              <td>Bắc Nguyễn</td>
              <td>Chơi chán vãi ò</td>
              <td>1</td>
              <td>
                <button type="button" class="btn btn-sm btn-primary">Thêm</button>
                <button type="button" class="btn btn-sm btn-warning">Sửa</button>
                <button type="button" class="btn btn-sm btn-danger">Xóa</button>
              </td>
            </tr>

            <tr>
              <td>2</td>
              <td>TP.HCM - Thái Lan</td>
              <td>Bắc Nguyễn</td>
              <td>Chuyển mẹ giới rồi</td>
              <td>1</td>
              <td>
                <button type="button" class="btn btn-sm btn-primary">Thêm</button>
                <button type="button" class="btn btn-sm btn-warning">Sửa</button>
                <button type="button" class="btn btn-sm btn-danger">Xóa</button>
              </td>
            </tr>

            <tr>
              <td>3</td>
              <td>Hà Nội - Nhật Bản</td>
              <td>Bắc Nguyễn</td>
              <td>Đắt vãi cả lò</td>
              <td>1</td>
              <td>
                <button type="button" class="btn btn-sm btn-primary">Thêm</button>
                <button type="button" class="btn btn-sm btn-warning">Sửa</button>
                <button type="button" class="btn btn-sm btn-danger">Xóa</button>
              </td>
            </tr>

            <tr>
              <td>4</td>
              <td>Hà Nội - Cát Bà</td>
              <td>Bắc Nguyễn</td>
              <td>Nước bân vãi đéo có sóng</td>
              <td>1</td>
              <td>
                <button type="button" class="btn btn-sm btn-primary">Thêm</button>
                <button type="button" class="btn btn-sm btn-warning">Sửa</button>
                <button type="button" class="btn btn-sm btn-danger">Xóa</button>
              </td>
            </tr>

          </tbody>
        </table>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <?php
    include 'footer.php';
    ?>

    <div class="modal fade show" id="modalTours" aria-modal="true" role="dialog">
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
                  <div class="form-group col-3">
                    <label for="InputName">Name</label>
                    <input type="text" name="name" class="form-control" id="InputName" placeholder="Enter name">
                  </div>
                  <div class="form-group col-3">
                    <label for="Sale">Sale</label>
                    <input type="text" name="sale" class="form-control" id="Sale" placeholder="Enter Sale">
                  </div>
                  <div class="form-group col-3">
                    <label for="From">From</label>
                    <!-- <input type="text" name="from" class="form-control" id="From" placeholder="Enter From"> -->
                    <div class="input-group date" id="reservationdateFrom" data-target-input="nearest">
                      <input type="text" class="form-control datetimepicker-input" name="from" id="From" data-target="#reservationdateFrom" />
                      <div class="input-group-append" data-target="#reservationdateFrom" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-3">
                    <label for="To">To</label>
                    <!-- <input type="text" name="to" class="form-control" id="To" placeholder="Enter To"> -->
                    <div class="input-group date" id="reservationdateTo" data-target-input="nearest">
                      <input type="text" class="form-control datetimepicker-input" name="to" id="To" data-target="#reservationdateTo" />
                      <div class="input-group-append" data-target="#reservationdateTo" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-4">
                    <label for="Country">Country</label>
                    <select class="custom-select" name="country" id="country"></select>
                  </div>
                  <div class="form-group col-4">
                    <label for="Category">Category</label>
                    <select class="custom-select" name="category" id="category"></select>
                  </div>
                  <div class="form-group col-4">
                    <label for="Price">Price</label>
                    <input type="text" name="price" class="form-control" id="Price" placeholder="Enter Price">
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-3">
                    <label for="fileupload">Avatar</label>
                    <!-- <div class="custom-file"> -->
                    <input type="file" name="fileToUpload" id="fileToUpload">
                    <!-- <label class="custom-file-label" for="fileupload">Choose file</label> -->
                    <!-- </div> -->
                  </div>
                  <div class="form-group col-3">
                    <label for="fileupload">Document</label>
                    <!-- <div class="custom-file"> -->
                    <input type="file" name="fileToUploadDoccument" id="fileToUploadDoccument">
                    <!-- <label class="custom-file-label" for="fileupload">Choose file</label> -->
                    <!-- </div> -->
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-4">
                    <label for="ShortDescription">ShortDescription</label>
                    <textarea id="shortdescription" name="shortdescription" rows="4" cols="50"></textarea>
                    <!-- <input type="text" name="shortdescription" class="form-control" id="ShortDescription" placeholder="Enter ShortDescription"> -->
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-4">
                    <label for="LongDescription">LongDescription</label>
                    <textarea id="longdescription" name="longdescription" rows="4" cols="50"></textarea>
                    <!-- <input type="text" name="longdescription" class="form-control" id="LongDescription" placeholder="Enter LongDescription"> -->
                  </div>
                </div>
              </div>

              <div class="card-footer">
                <button type="submit" name="submit" class="btn btn-primary btn-themmoi">Thêm mới</button>
                <!-- <button type="submit" name="submit" class="btn btn-primary btn-capnhat">Cập nhật</button> -->
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
  <script type="text/javascript" src="dist/js/custom/Tours.js"></script>
  <!-- Toastr js -->
  <script src="dist/js/toastr.js"></script>

</body>

</html>