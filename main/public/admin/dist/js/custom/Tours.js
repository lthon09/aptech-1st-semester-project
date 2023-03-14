var html_option = "";
var html_body = "";
var css_hot;
var text_hot;
$(document).ready(function () {
  fncLoad();
  fncClick();
  fncChange();
  fncActionmodal();
  //Date picker
  $("#reservationdateFrom").datetimepicker({
    format: "DD/MM/YYYY HH:mm:ss",
  });
  $("#reservationdateTo").datetimepicker({
    format: "DD/MM/YYYY HH:mm:ss",
  });
});

function fncLoad() {
  // document.onload;
  $.ajax({
    url: "../../../admin/php/loadTours.php",
    method: "GET",
    dataType: "JSON",
    success: function (data) {
      html_body = "";
      $.each(data, function (key, val) {
        if (val.Hot == "1") {
          css_hot = "label-success";
          text_hot = "Hot";
        } else {
          css_hot = "label-danger";
          text_hot = "Normal";
        }

        html_body += "<tr>";
        html_body += "<td>" + val.id + "</td>";
        html_body += "<td>" + val.name + "</td>";
        html_body += "<td>" + val.ShortDescription + "</td>";
        html_body += "<td>" + val.LongDescription + "</td>";
        html_body += "<td>" + val.Price + "</td>";
        html_body += "<td>" + val.Sale + "%</td>";
        html_body += "<td>" + val.NameCoutries + "</td>";
        html_body +=
          '<td><img src="' + val.Avatar + '" width="50" height="50"></td>';
        html_body += "<td>" + val.NameCategory + "</td>";
        html_body +=
          '<td><span class="label span-normal-width ' +
          css_hot +
          ' ">' +
          text_hot +
          "</span></td>";
        html_body += "<td>";
        html_body +=
          '<button type="button" class="btn btn-sm btn-warning btn-sua" attrId="' +
          val.id +
          '" >Sửa</button>';
        html_body +=
          '<button type="button" class="btn btn-sm btn-danger btn-xoa" attrId="' +
          val.id +
          '" >Xóa</button>';
        html_body += "</td>";
        html_body += "</tr>";
      });
      $("#tbl_Tours tbody").empty().append(html_body);
      $("#tbl_Tours").DataTable();
    },
  });
}

function fncClick() {
  // Delete Tour
  $("#tbl_Tours").on("click", ".btn-xoa", function () {
    var conf = confirm(
      "Bạn có muốn xóa ID  " + $(this).attr("attrId") + " này không ?"
    );
    if (conf) {
      $.ajax({
        url: "../../../admin/php/deleteTours.php",
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

  // Update tours
  $(".btn-capnhat").click(function (e) {
    e.preventDefault();
    if (validate()) {
      var form_data = new FormData(document.getElementById("my_form"));
      form_data.append("id", $(this).attr("attrId"));

      if ($("#checkboxTour").is(":checked")) {
        form_data.append("checkboxTour", "1");
      } else {
        form_data.append("checkboxTour", "0");
      }
      $.ajax({
        url: "../../../admin/php/updateTours.php",
        method: "POST",
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (strMessage) {
          toastr.info(strMessage);
          $("#modalTours").modal("hide");
          fncLoad();
        },
      });
    }
  });

  // show modal sửa
  $("#tbl_Tours").on("click", ".btn-sua", function () {
    $(".btn-capnhat").attr("attrId", $(this).attr("attrId"));
    loadCountries();
    loadCategory();
    $("#modalTours").modal("show");
    $(".modal-title").empty().append("Cập nhật Tours");
    $(".btn-themmoi").hide();
    $(".btn-capnhat").show();
    $.ajax({
      url: "../../../admin/php/loadToursId.php",
      method: "GET",
      data: { id: $(this).attr("attrId") },
      dataType: "JSON",
      success: function (data) {
        $("#InputName").val(data[0].name);
        $("#Sale").val(data[0].Sale);
        $("#Price").val(data[0].Price);
        $("#country").val(data[0].NameCoutries);
        $("#category").val(data[0].NameCategory);
        $("#fileToUpload").val(data[0].Avatar);
        $("#shortdescription").val(data[0].ShortDescription);
        $("#longdescription").val(data[0].LongDescription);
        $("#detailedInformation").val(data[0].DetailedInformations);
        if (data[0].Hot == "1") {
          document.getElementById("checkboxTour").checked = true;
        } else {
          document.getElementById("checkboxTour").checked = false;
        }
      },
    });
  });

  // Insert tours
  $(".btn-themmoi").click(function (e) {
    e.preventDefault();
    if (validate()) {
      var form_data = new FormData(document.getElementById("my_form"));

      if ($("#checkboxTour").is(":checked")) {
        form_data.append("checkboxTour", "1");
      } else {
        form_data.append("checkboxTour", "0");
      }

      $.ajax({
        url: "../../../admin/php/insertTours.php",
        method: "POST",
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (strMessage) {
          if (strMessage == "ok") {
            toastr.info("Thêm mới thành công");
            $("#modalTours").modal("hide");
            fncLoad();
          } else {
            toastr.error("Lỗi thông báo IT");
          }
        },
      });
    }
  });

  // show modal thêm mới
  $(".btn-add").click(function () {
    loadCountries();
    loadCategory();
    $("#modalTours").modal("show");
    $(".modal-title").empty().append("Thêm mới Tours");
    $(".btn-themmoi").show();
    $(".btn-capnhat").hide();
  });
}

function fncChange() {}

function loadCountries() {
  $.ajax({
    url: "../../../admin/php/loadCountries.php",
    method: "POST",
    dataType: "JSON",
    success: function (data) {
      html_option = "";

      $.each(data, function (key, val) {
        html_option +=
          '<option value="' + val.id + '">' + val.name + "</option>";
      });
      $("#country").empty().append(html_option);
    },
  });
}

function loadCategory() {
  $.ajax({
    url: "../../../admin/php/loadCategories.php",
    method: "POST",
    dataType: "JSON",
    success: function (data) {
      html_option = "";
      $.each(data, function (key, val) {
        html_option +=
          '<option value="' + val.id + '">' + val.name + "</option>";
      });
      $("#category").empty().append(html_option);
    },
  });
}

function validate() {
  if (
    $("#InputName").val() == "" ||
    $("#Sale").val() == "" ||
    $("#Price").val() == "" ||
    $("#country").val() == "" ||
    $("#category").val() == "" ||
    $("#shortdescription").val() == "" ||
    $("#longdescription").val() == "" ||
    $("#detailedInformation").val() == "" ||
    $("#fileToUpload").val() == ""
  ) {
    toastr.error("Vui lòng nhập đầy đủ các trường dữ liệu.");
    return false;
  }

  return true;
}

function fncActionmodal() {
  $("#modalTours").on("hide.bs.modal", function () {
    $("#InputName").val("");
    $("#Sale").val("");
    $("#Price").val("");
    $("#country").val("");
    $("#category").val("");
    $("#shortdescription").val("");
    $("#longdescription").val("");
    $("#detailedInformation").val("");
    $("#fileToUpload").val("");
    $("#checkboxTour").prop("checked", false);
  });
}
