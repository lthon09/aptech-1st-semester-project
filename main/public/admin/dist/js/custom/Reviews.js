$(document).ready(function () {
  fncLoad();
  fncClick();
  fncChange();

  fncActionmodal();
  loadTours();
  loadAuthor();
});

function fncLoad() {
  $.ajax({
    url: "../../../admin/php/loadReviews.php",
    method: "GET",
    dataType: "JSON",
    success: function (data) {
      html_body = "";
      $.each(data, function (key, val) {
        html_body += "<tr>";
        html_body += "<th>" + val.id + "</th>";
        html_body += "<td>" + val.name + "</td>";
        html_body += "<td>" + val.Author + "</td>";
        html_body += "<td>" + val.Content + "</td>";
        html_body += "<td>" + val.Rating + "</td>";
        html_body += "<td>";
        html_body +=
          '<button type="button" class="btn btn-sm btn-warning btn-sua" attrId="' +
          val.id +
          '" >Sửa</button> ';
        html_body +=
          '<button type="button" class="btn btn-sm btn-danger btn-xoa" attrId="' +
          val.id +
          '" >Xóa</button>';
        html_body += "</td>";
        html_body += "</tr>";
      });
      $("#tbl_Reviews tbody").empty().append(html_body);
      $("#tbl_Reviews").DataTable();
    },
  });
}

function fncClick() {
  // Delete Reviews
  $("#tbl_Reviews").on("click", ".btn-xoa", function () {
    var conf = confirm(
      "Bạn có muốn xóa ID  " + $(this).attr("attrId") + " này không ?"
    );
    if (conf) {
      $.ajax({
        url: "../../../admin/php/deleteReviews.php",
        method: "POST",
        data: { id: $(this).attr("attrId") },
        success: function (strMessage) {
          if (strMessage == "ok") {
            toastr.info("Xóa thành công!");
            fncLoad();
          } else {
            toastr.error("Lỗi vui lòng liên hệ IT!");
          }
        },
      });
    }
  });
  // Update review
  $(".btn-capnhat").click(function (e) {
    e.preventDefault();
    if (validate) {
      var form_data = new FormData(document.getElementById("my_form"));
      form_data.append("id", $(this).attr("attrId"));
      $.ajax({
        url: "../../../admin/php/updateReviews.php",
        method: "POST",
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (strMessage) {
          if (strMessage == "ok") {
            toastr.info("Cập nhật thành công");
            $("#modalReviews").modal("hide");
            fncLoad();
          } else {
            toastr.error("Lỗi thông báo IT");
          }
        },
      });
    }
  });
  // show modal sửa
  $("#tbl_Reviews").on("click", ".btn-sua", function () {
    $(".btn-capnhat").attr("attrId", $(this).attr("attrId"));
    $("#modalReviews").modal("show");
    $(".modal-title").empty().append("Cập nhật Reviews");
    $(".btn-themmoi").hide();
    $(".btn-capnhat").show();
    loadTours();
    loadAuthor();
    $.ajax({
      url: "../../../admin/php/loadReviewsId.php",
      method: "GET",
      data: { id: $(this).attr("attrId") },
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        $("#Tour").val(data[0].name);
        $("#Author").val(data[0].Author);
        $("#Content").val(data[0].Content);
        $('input:radio[name=rate][value="' + data[0].Rating + '"]').attr(
          "checked",
          true
        );
      },
    });
  });
  // Thêm mới reviews
  $(".btn-themmoi").click(function (e) {
    e.preventDefault();
    if (validate()) {
      var form_data = new FormData(document.getElementById("my_form"));
      $.ajax({
        url: "../../../admin/php/insertReviews.php",
        method: "POST",
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (strMessage) {
          if (strMessage == "ok") {
            toastr.info("Thêm mới thành công");
            $("#modalReviews").modal("hide");
            fncLoad();
          } else {
            toastr.error("Lỗi thông báo IT");
          }
        },
      });
    }
  });
  // Click show add reviews
  $(".btn-add").click(function () {
    $("#modalReviews").modal("show");
    $(".modal-title").empty().append("Thêm mới Reviews");
    $(".btn-themmoi").show();
    $(".btn-capnhat").hide();
  });
}

function fncChange() {}

function loadTours() {
  $.ajax({
    url: "../../../admin/php/loadTours.php",
    method: "POST",
    dataType: "JSON",
    success: function (data) {
      html_option = "";

      $.each(data, function (key, val) {
        html_option +=
          '<option value="' + val.id + '">' + val.name + "</option>";
      });
      $("#Tour").empty().append(html_option);
    },
  });
}

function loadAuthor() {
  $.ajax({
    url: "../../../admin/php/loadMembers.php",
    method: "POST",
    dataType: "JSON",
    success: function (data) {
      html_option = "";
      $.each(data, function (key, val) {
        html_option +=
          '<option value="' + val.Username + '">' + val.Username + "</option>";
      });
      $("#Author").empty().append(html_option);
    },
  });
}

function validate() {
  if ($("input[name='rate']:checked").val() == undefined) {
    toastr.error("Vui lòng chọn đánh giá~");
    return false;
  }

  if ($("#Content").val() === "") {
    toastr.error("Vui lòng nhập content");
    return false;
  }

  return true;
}

function fncActionmodal() {
  $("#modalReviews").on("hide.bs.modal", function () {
    $("#Tour").val("");
    $("#Author").val("");
    $("#Content").val("");
    // $("input[name='rate']").addClass("removeColor");
  });
}
