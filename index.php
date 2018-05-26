<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
 ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mail Client</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
  <link rel="stylesheet" href="style/style.css">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">Webmail Client</a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="php/delete.php?delete=true">Delete all tiles</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" href="">Refresh mail</a>
        </li>
      </ul>
      <button type="button" class="btn btn-secondary" onclick="window.location.href='php/logout.php'">Logout</button>
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#login">Add mail provider</button>
    </div>
  </nav>
<div class="container-fluid text-center">
  <div class="row content">
    <div class="col-sm-2 sidenav">
    </div>
    <div class="col-sm-8 text-left" id="email_content">
      <h1 id="welcome_msg">Welcome <?php
        if(isset($_SESSION['mail_addr'])){
          echo($_SESSION['mail_addr']);
        }
      ?> </h1>
      <div class="col-sm-12">
        <?php
            function show_email(){
              if(isset($_SESSION['mail_path'])){
              $mail_path = $_SESSION['mail_path'];
                if ($handle = opendir('content/'.$mail_path)) {
                  while (false !== ($entry = readdir($handle))) {
                    if($entry[0] != "."){
                      $content = file_get_contents('content/'.$mail_path."/".$entry);
                      $content_array = explode("::",$content);
                      echo('
                      <div class="card" style="width: 18rem;">
                        <div class="card-body">
                          <h5 class="card-title">'.$content_array[0].'</h5>
                          <p class="card-text">'.$content_array[1].'</p>
                          <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#'.explode(".", $entry)[0].'">Open</a>
                        </div>
                      </div>'
                      );
                      echo('
                      <!-- Modal -->
                      <div class="modal fade" id="'.explode(".", $entry)[0].'" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">'.$content_array[0].'</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <textarea class="form-control" id="message-text">'.$content_array[2].'</textarea>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>'
                      );
                    }
                    else{

                    }
                  }
                  closedir($handle);
                }
              }
              else{

              }
          }
          show_email();
        ?>
      </div>
    </div>
    <div class="col-sm-2 sidenav">
    </div>
  </div>
</div>


  <div id="login" class="modal fade" tabindex="-1" aria-hidden="true" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="mailserverLabel">Login to mailserver</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal" action="php/createMailConnection.php" method="post">
            <div class="form-group">
              <label for="email" class="col-sm-3 control-label">
                                  Email</label>
              <div class="col-sm-9">
                <input required="true" type="email" class="form-control" name="email" id="email" placeholder="Email" />
              </div>
            </div>
            <div class="form-group">
              <label for="password" class="col-sm-3 control-label">
                                  Password</label>
              <div class="col-sm-9">
                <input required="true" type="password" class="form-control" name="password" id="password" placeholder="Password" />
              </div>
            </div>
            <div class="form-group">
              <label for="imap-server" class="col-sm-3 control-label">
                                  IMAP Server</label>
              <div class="col-sm-9">
                <input required="true" type="imap-server" class="form-control" name="imap-server" id="imap-server" placeholder="Server" />
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-3"></div>
              <div class="col-sm-9">
              <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      </div>
  </div>
</body>
<footer class="container-fluid text-center">
</footer>
</html>
