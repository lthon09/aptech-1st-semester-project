var html_body;
var css_admin;
var text_admin;
$(document).ready(function () {
  fncLoad();
  fncClick();
  fncChange();
  fncActionmodal();
});

function fncLoad() {
  $.ajax({
    url: "../../../admin/php/loadMembers.php",
    method: "POST",
    dataType: "JSON",
    success: function (data) {
      html_body = "";
      $.each(data, function (key, val) {
        if (val.Administrator == "1") {
          css_admin = "label-success";
          text_admin = "Admin";
        } else {
          css_admin = "label-danger";
          text_admin = "Member";
        }

        html_body += "<tr>";
        html_body += "<td>" + val.Username + "</td>";
        html_body += "<td>" + val.Email + "</td>";
        html_body +=
          '<td><span class="label span-normal-width ' +
          css_admin +
          ' ">' +
          text_admin +
          "</span></td>";
        html_body += "<td>";
        html_body +=
          '<button type="button" class="btn btn-sm btn-warning btn-sua" attrIdSua="' +
          val.Username +
          '">Sửa</button> ';
        html_body +=
          '<button type="button" class="btn btn-sm btn-danger btn-xoa" attrIdXoa="' +
          val.Username +
          '">Xóa</button>';
        html_body += "</td>";
        html_body += "</tr>";
      });
      $("#tbl_Members tbody").empty().append(html_body);
      $("#tbl_Members").DataTable();
    },
  });
}

function fncClick() {
  // cập nhât member
  $(".btn-capnhat").click(function (e) {
    e.preventDefault();
    if (validate()) {
      var data = new FormData();

      data.append("id", $(this).attr("attrid"));

      data.append("username", $("#InputName").val());
      data.append("email", $("#Email").val());

      if ($("#checkAdmin").is(":checked")) {
        data.append("checkadmin", "1");
      } else {
        data.append("checkadmin", "0");
      }

      if ($("#checkshowPW").is(":checked")) {
        data.append("password", "");
        data.append("confirmPassword", "");
      } else {
        data.append("password", $("#password").val());
        data.append("confirmPassword", $("#confirmPassword").val());
      }

      $.ajax({
        url: "../../../admin/php/updateMembers.php",
        method: "POST",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (strMessage) {
          if (strMessage == "ok") {
            toastr.info("Cập nhật thành công");
            $("#modalMembers").modal("hide");
            fncLoad();
          } else {
            toastr.error("Lỗi vui lòng liên hệ IT");
          }
        },
      });
    }
  });

  // delete member
  $("#tbl_Members").on("click", ".btn-xoa", function () {
    var conf = confirm("Bạn có muốn xóa không ?");
    if (conf) {
      $.ajax({
        url: "../../../admin/php/deleteMembers.php",
        method: "POST",
        data: { id: $(this).attr("attridxoa") },
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

  // show member detail
  $("#tbl_Members").on("click", ".btn-sua", function () {
    $("#modalMembers").modal("show");
    $(".modal-title").empty().append("Cập nhật Members");
    $(".btn-themmoi").hide();
    $(".btn-capnhat").show();
    $(".btn-capnhat").attr("attrId", $(this).attr("attridsua"));
    $.ajax({
      url: "../../../admin/php/loadMembersId.php",
      method: "GET",
      data: { id: $(this).attr("attridsua") },
      dataType: "JSON",
      success: function (data) {
        console.log(data)
        $("#InputName").val(data[0].Username);
        $("#Email").val(data[0].Email);
        if (data[0].Administrator == "1") {
          document.getElementById("checkAdmin").checked = true;
        } else {
          document.getElementById("checkAdmin").checked = false;
        }
      },
    });
  });

  // thêm mới member
  $(".btn-themmoi").click(function (e) {
    e.preventDefault();

    if (validate()) {
      var data = new FormData();

      data.append("username", $("#InputName").val());
      data.append("email", $("#Email").val());

      if ($("#checkAdmin").is(":checked")) {
        data.append("checkadmin", "1");
      } else {
        data.append("checkadmin", "0");
      }

      if ($("#checkshowPW").is(":checked")) {
        data.append("password", "");
        data.append("confirmPassword", "");
      } else {
        data.append("password", $("#password").val());
        data.append("confirmPassword", $("#confirmPassword").val());
      }

      $.ajax({
        url: "../../../admin/php/insertMembers.php",
        method: "POST",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (strMessage) {
          if (strMessage == "ok") {
            toastr.info("Thêm mới thành công");
            $("#modalMembers").modal("hide");
            fncLoad();
          } else {
            toastr.error("Lỗi vui lòng liên hệ IT");
          }
        },
      });
    }
  });

  // show thêm mới members
  $(".btn-add").click(function () {
    $("#modalMembers").modal("show");
    $(".modal-title").empty().append("Thêm mới Members");
    $(".btn-themmoi").show();
    $(".btn-capnhat").hide();
  });
}

function fncChange() {
  $("#checkshowPW").change(function () {
    if ($(this).is(":checked")) {
      $("#password").attr("disabled", "disabled");
      $("#confirmPassword").attr("disabled", "disabled");
    } else {
      $("#password").removeAttr("disabled");
      $("#confirmPassword").removeAttr("disabled");
    }
  });
}

function validate() {
  if ($("#InputName").val() == "" || $("#Email").val() == "") {
    toastr.error("Vui lòng nhập đủ trường thông tin UserName và Email");
    return false;
  }

  if (!$("#checkshowPW").is(":checked")) {
    if ($("#password").val() !== $("#confirmPassword").val()) {
      toastr.error("Mật khẩu không trùng khớp vui lòng thử lại");
      return false;
    }
  }

  return true;
}

function fncActionmodal() {
  $("#modalMembers").on("hide.bs.modal", function () {
    $("#InputName").val("");
    $("#Email").val("");
    $("#checkAdmin").prop("checked", false);
    $("#checkshowPW").prop("checked", true);
    $("#password").val("");
    $("#confirmPassword").val("");
    $("#password").attr("disabled", "disabled");
    $("#confirmPassword").attr("disabled", "disabled");
  });
}
