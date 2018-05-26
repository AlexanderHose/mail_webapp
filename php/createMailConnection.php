<?php
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  $mail_addr = $_POST['email'];
  $password = $_POST['password'];
  $imap_server = $_POST['imap-server'];
  $_SESSION['mail_addr'] = $mail_addr;
  $_SESSION['imap_server'] = $imap_server;
  if(!isset($_SESSION['mail_path'])){
    $mail_path = randomString();
    $_SESSION['mail_path'] = $mail_path;
  }
  else{
    $mail_path = $_SESSION['mail_path'];
  }

  #echo("python ../python/mail.py -e " . $mail_addr . " -p " . $password . " -s " . $imap_server . " --path " . $mail_path);
  $command= escapeshellcmd("python ../python/mail.py -e " . $mail_addr . " -p " . $password . " -s " . $imap_server . " --path " . $mail_path);
  $output = shell_exec($command);
  #echo $output;
  header("Location: ../index.php");
  die();
  function randomString($length = 38){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
  }
?>
