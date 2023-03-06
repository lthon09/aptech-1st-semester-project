$(document).ready(function () {
  fncClick();
});

function fncClick() {
  $(".btn-block").click(function (e) {
    e.preventDefault();
    var form_data = new FormData(document.getElementById("my_form"));
    // console.log(...form_data);
    $.ajax({
      url: "../../../admin/php/login1.php",
      method: "POST",
      data: form_data,
      contentType: false,
      cache: false,
      processData: false,
      success: function (strMessage) {
          if (strMessage == "0") {
            toastr.error("Tài khoản của bạn không chính xác!");
            $("#Username").val("");
            $("#Password").val("");
          } else if(strMessage == "1"){
            toastr.error("Mật khẩu không đúng hoặc bạn không có quyền!");
            $("#Username").val("");
            $("#Password").val("")
          }else if(strMessage == "2"){
            toastr.info("Đăng nhập thành công!");
            window.location.href = ("http://localhost:8080/admin/Countries.php");
          }
      },
    });
  });
}
