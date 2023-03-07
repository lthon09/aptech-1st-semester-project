var html_option = "";
var html_body = "";
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
  document.onload;
  // $.ajax({
  //   url: "../../../admin/php/loadTours.php",
  //   method: "GET",
  //   dataType: "JSON",
  //   success: function (data) {
  //     html_body = "";
  //     $.each(data, function (key, val) {
  //       html_body += "<tr>";
  //       html_body += "<th>" + val.id + "</th>";
  //       html_body += "<td>" + val.name + "</td>";
  //       html_body += "<td>" + val.ShortDescription + "</td>";
  //       html_body += "<td>" + val.LongDescription + "</td>";
  //       html_body += "<td>" + val.Price + "</td>";
  //       html_body += "<td>" + val.Sale + "%</td>";
  //       html_body += "<td>" + val.NameCoutries + "</td>";
  //       html_body +='<td>';
  //         // '<td><img src="../static/assets/tours/' + val.id + "/avatar/'" + val.Avatar +' " width="50" height="50"></td>';
  //         html_body +="<img src=\"data:image/jpg;charset=utf8;base64, \""+base64_encode($row['avatar'])+"\" \" />";
  //         html_body += "</td>";
  //       html_body += "<td>" + val.NameCategory + "</td>";
  //       html_body += "<td>Không có tài liệu</td>";
  //       html_body += "<td>";
  //       html_body +=
  //         '<button type="button" class="btn btn-sm btn-warning btn-sua" attrId="' +
  //         val.id +
  //         '" >Sửa</button>';
  //       html_body +=
  //         '<button type="button" class="btn btn-sm btn-danger btn-xoa" attrId="' +
  //         val.id +
  //         '" >Xóa</button>';
  //       html_body += "</td>";
  //       html_body += "</tr>";
  //     });
  //     $("#tbl_Tours tbody").empty().append(html_body);
  //   },
  // });
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
    // validate();
    var form_data = new FormData(document.getElementById("my_form"));
    form_data.append("id", $(this).attr("attrId"));
    console.log(...form_data);
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
        $("#shortdescription").val(data[0].ShortDescription);
        $("#longdescription").val(data[0].LongDescription);
        $("#detailedInformation").val(data[0].DetailedInformations);
      },
    });
  });

  // Insert tours
  $(".btn-themmoi").click(function (e) {
    e.preventDefault();
    validate();
    var form_data = new FormData(document.getElementById("my_form"));
    $.ajax({
      url: "../../../admin/php/insertTours.php",
      method: "POST",
      data: form_data,
      contentType: false,
      cache: false,
      processData: false,
      success: function (strMessage) {
        if(strMessage == "ok"){
          toastr.info("Thêm mới thành công");
          $("#modalTours").modal("hide");
          fncLoad();
        }else{
          toastr.error("Lỗi thông báo IT");
        }
      },
    });
  });

  // show modal thêm mới
  $(".btn-add").click(function () {
    loadCountries();
    loadCategory();
    // $("#From").datepicker();
    // $("#To").datepicker();
    $("#modalTours").modal("show");
    $(".modal-title").empty().append("Thêm mới Tours");
    $(".btn-themmoi").show();
    $(".btn-capnhat").hide();

    // toastr.info('Are you the 6 fingered man?')
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
  // if (Date.parse($("#From").val()) - Date.parse($("#To").val()) > 0) {
  //   alert("Ngày bắt đầu phải lớn hơn ngày kết thúc !");
  //   return;
  // }

  if (
    $("#InputName").val() == "" ||
    $("#Sale").val() == "" ||
    $("#Price").val() == "" ||
    $("#country").val() == "" ||
    $("#category").val() == "" ||
    $("#shortdescription").val() == "" ||
    $("#longdescription").val() == "" ||
    $("#detailedInformation").val() == "" ||
    $("#fileToUpload").get(0).files.length === 0 ||
    $("#fileToUploadDoccument").get(0).files.length === 0
  ) {
    toastr.error("Vui lòng nhập đầy đủ các trường dữ liệu.");
    return;
  }
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
    $("#fileToUploadDoccument").val("");
  });
}