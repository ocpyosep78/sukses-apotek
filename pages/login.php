<link rel="stylesheet" href="../themes/theme_default/theme-login.css" />
<script type="text/javascript" src="../plugins/metro-jquery/jquery-1.8.3.js"></script>
<script type="text/javascript" src="../plugins/metro-jquery/jquery-ui-1.9.2.custom.js"></script>
<script type="text/javascript">
$(function() {
    window.setTimeout("waktu()",1000);
    $('#loader').hide();
    $(document).keydown(function() {
        open_login();
    });
    $(document).click(function() {
        open_login();
    });
    $('#login').click(function() {
        $('#formlogin').submit();
    });
    $('#username,#password').keydown(function(e) {
        if (e.keyCode === 13) {
            $('#formlogin').submit();
        }
    });
    $('#formlogin').submit(function() {
        if ($('#username').val() === '') {
            $('#username').focus(); return false;
        }
        if ($('#password').val() === '') {
            $('#password').focus(); return false;
        }
        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: 'json',
            type: 'POST',
            cache: false,
            beforeSend: function() {
                $('#loader').show();
            },
            success: function(data) {
                if (data.username !== null) {
                    location.href='../';
                } else {
                    $('#result').show().html('Username dan password salah !');
                }
            }
        });
        return false;
    });
});
function close_login() {
    $('.body-lock').slideDown('fast');
    $('.body-login').hide();
}
function open_login() {
    $('.body-login').slideDown('fast');
    $('.body-lock').hide();
}
  
function waktu() {   
    var jam = document.getElementById("jam");
    var now = new Date();                    
    var dt  = now.toLocaleTimeString().split(':');
    $('#jam').html(dt[0]+':'+dt[1]);
    
}
window.onload = waktu();

</script>
<div class="body-lock">
    <div class="dateandtime">
        <div class="hour-time" id="jam"></div>
        <div class="date-time"><?= date("l") ?>, <?= date("F d") ?></div>
    </div>
</div>
<div class="body-login">
    <form action="../models/autocomplete.php?method=login" method="post" id="formlogin">
        <div class="wrapper">
            <img src="../img/icons/user-login.png" align="left" />
            <h1>Login Apotek <img src="../themes/theme_default/img/primary/loader.gif" align="right" id="loader" /></h1>
            <input type="text" name="username" id="username" placeholder="Username ..." size="50" />
            <input type="password" name="password" id="password" placeholder="Password ..." size="50" /><br/>
            <input type="button" value="Submit" class="button" id="login" /><span id="result"></span>
        </div>
    </form>
</div>