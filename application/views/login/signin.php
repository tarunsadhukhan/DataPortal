



<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Reports</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link
      href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
      rel="stylesheet"
      id="bootstrap-css"
    />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <link href="<?=base_url('public')?>/assets/css/style.css" rel="stylesheet" />
  </head>
  <body>
    <div class="sidenav">
      <div class="login-main-text">
        <!-- <h1>
            Welcome to VOW <br />Data Portal
        </h1> -->
      </div>
    </div>
    <div class="main">
      <div class="loginContainer">
        <div class="loginBlock">
          <img src="<?=base_url('public')?>/assets/images/vow_logo.png" alt="" />
          <h1>Login</h1>
          <p>
            Enter your Email ID or username to continue with the reports
          </p>
          <form id="login-form" action="<?=base_url('welcome/login/')?>" method="POST">
            <div novalidate="">
              <div class="loginInput">
                <div class="form-group">
                  <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Email / Username"
                    required
                  /><i class="validation"><span></span><span></span></i>
                </div>
              </div>
              <div class="loginInput">
                <div class="form-group">
                  <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter Passwords"
                    required
                  /><i class="validation"><span></span><span></span></i>
                </div>
              </div>
              <div class="col-xl-12 text-center">
              <div class="text-danger error-msg"><?=$this->session->flashdata('error')?></div>
              <div class="text-success error-msg"><?=$this->session->flashdata('message')?></div>
              </div>
              <button class="loginButton" tabindex="0" type="sumbit" id="login">
                Log In
              </button>
              
            </div>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>