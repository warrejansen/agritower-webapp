<?php
  $path = $_SERVER['DOCUMENT_ROOT'];
  include $path."/includes/dbh.php";

  $remark = array('');

  $user = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, $_POST ['password']);

  $sql = "SELECT * FROM user WHERE 	`username` = '$user'";
  $result = mysqli_query($conn, $sql);
  $resultCheck = mysqli_num_rows($result);
  if ($resultCheck < 1) {
    $remark[0] = 'Gebruiker niet gevonden';
    $remark[1] = 'No';
  } else {
    if($row = mysqli_fetch_array($result)){
      if ($password !== $row['password']) {
        $remark[0] = 'Gebruiker niet gevonden';
        $remark[1] = 'No';
      } elseif ($password === $row['password']){
        $remark[0] = 'Wachtwoord is juist';
        $remark[1] = 'yes';
        session_start();
        $_SESSION['user'] = $user;
       }
      }
    }
    echo json_encode($remark);

  ?>
