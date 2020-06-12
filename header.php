<div class="topnav">
  <div class="login-container">
    <form id="loginForm"action="" method="POST">
      <?php
        if ($user) {
          ?>
          <button type="button" name="button" onclick="logout()">logout</button>
          <?php
        } else {
          ?>
          <input type="text" placeholder="Username" name="username">
          <input type="password" placeholder="Password" name="password">
          <button type="submit">Login</button>
          <?php
        }
       ?>
    </form>
  </div>
</div>
<!-- <img src="/pictures/logo-provil.png" style="width:128px;height:40px;margin-top:-10px; margin-left:0px"> -->
<script type="text/javascript">

function logout(){
  $.ajax({
    url: '/includes/logout.php',
    success: function(html){
      window.location.href="/";
    }
  })
}

$('#loginForm').on('submit',function(event){
  event.preventDefault();
  $.ajax({
    url: '/includes/login.php',
    method : 'POST',
    data: new FormData(this),
    contentType: false,
    dataType: 'json',
    processData: false,
    success: function(data){
      alert(data)
      if (data[1] === 'no') {
        $('#loginInformation').html(data[0]);
        $('#loginInformation').css({'dislplay':'block'});
      } else if (data[1] === 'yes') {
        location.reload();
      }
    }
  })
})
</script>
