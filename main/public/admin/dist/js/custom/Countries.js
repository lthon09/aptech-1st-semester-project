var html_body;
$(document).ready(function () {
  fncLoad();
  fncClick();
  fncChange();
  fncModalAction();
});

function fncLoad() {
  $.ajax({
    url: "../../../admin/php/loadCountries.php",
    method: "POST",
    dataType: "JSON",
    success: function (data) {
      html_body = "";
      $.each(data, function (key, val) {
        html_body += "<tr>";
        html_body += "<td>" + val.id + "</td>";
        html_body += "<td>" + val.name + "</td>";
        html_body += "<td>";
        html_body +=
          '<button type="button" class="btn btn-sm btn-warning btn-sua" attrIdSua="' +
          val.id +
          '">Sửa</button>';
        html_body +=
          '<button type="button" class="btn btn-sm btn-danger btn-xoa" attrIdXoa="' +
          val.id +
          '">Xóa</button>';
        html_body += "</td>";
        html_body += "</tr>";
      });
      $("#tbl_Countries tbody").empty().append(html_body);
    },
  });
}

function fncClick() {
  $("#tbl_Countries").on("click", ".btn-xoa", function () {
    var conf = confirm("Bạn có muốn xóa không ?");
    if (conf) {
      $.ajax({
        url: "../../../admin/php/deleteCountries.php",
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

  $(".btn-capnhat").click(function (e) {
    e.preventDefault();
    $.ajax({
      url: "../../../admin/php/updateCountries.php",
      method: "POST",
      data: { id: $(this).attr("attrID"), name: $("#InputName").val() },
      success: function (strMessage) {
        if (strMessage == "ok") {
          toastr.info("Cập nhật thành công!");
          $("#modalCountries").modal("hide");
          fncLoad();
        } else {
          toastr.error("Lỗi vui lòng liên hệ IT!");
        }
      },
    });
  });

  $("#tbl_Countries").on("click", ".btn-sua", function () {
    $("#modalCountries").modal("show");
    $(".modal-title").empty().append("Cập nhật Countries");
    $(".btn-submit").hide();
    $(".btn-capnhat").show();
    $(".btn-capnhat").attr("attrId", $(this).attr("attridsua"));
    $.ajax({
      url: "../../../admin/php/loadCountriesId.php",
      method: "GET",
      data: { id: $(this).attr("attridsua") },
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        $("#InputName").val(data[0].name);
      },
    });
  });

  $(".btn-submit").click(function (e) {
    e.preventDefault();
    $.ajax({
      url: "../../../admin/php/insertCountries.php",
      method: "POST",
      data: $("form").serialize(),
      success: function (strMessage) {
        if (strMessage == "ok") {
          toastr.info("Thêm mới thành công!");
          $("#modalCountries").modal("hide");
          fncLoad();
        } else {
          toastr.error("Lỗi vui lòng liên hệ IT!");
        }
      },
    });
  });

  $(".btn-add").click(function () {
    $("#modalCountries").modal("show");
    $(".modal-title").empty().append("Thêm mới Countries");
    $(".btn-submit").show();
    $(".btn-capnhat").hide();
  });
}

function fncChange() {}

function fncModalAction() {
  $("#modalCountries").on("hidden.bs.modal", function (e) {
    $("#InputName").val("");
  });
}
