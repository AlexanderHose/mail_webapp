<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['mail_path'])){
  delete_mail_tiles();
}
function delete_mail_tiles(){
  $mail_path = $_SESSION['mail_path'];
    if ($handle = opendir('../content/'.$mail_path)) {
      while (false !== ($entry = readdir($handle))) {
        unlink('../content/'.$mail_path."/".$entry);
      }
    }
  rmdir('../content/'.$mail_path);
  unset($_SESSION['mail_path']);
}
header("Location: ../index.php");
die();
?>
