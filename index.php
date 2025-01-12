<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>POS System | By สะดวกขาย</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- <link rel="icon" type="" href="assets/img/t.png" /> -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="assets/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="assets/adminlte.min.css">
  <link href="https://fonts.googleapis.com/css?family=Kanit:400" rel="stylesheet">
</head>

<style>
body {
  background: #f4f6f9;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  margin: 0;
}

/* Login Box Styles */
.login-box {
  width: 360px;
  margin: 0;
}

.card {
  box-shadow: 0 0 20px rgba(0,0,0,0.1);
  border-radius: 10px;
  border: none;
}

.login-card-body {
  padding: 30px;
  border-radius: 10px;
}

/* Input Group Styles */
.input-group {
  position: relative;
  width: 100%;
}

.input-group-append {
  margin-left: 0;
  position: absolute;
  right: 0;
  height: 100%;
  z-index: 3;
}

.input-group-text {
  background-color: transparent;
  border: 1px solid #ced4da;
  border-left: none;
  border-radius: 0 4px 4px 0;
  height: 100%;
  padding: 0.375rem 0.75rem;
}

/* Form Control Styles */
.form-control {
  padding-right: 40px;
  border: 1px solid #dee2e6;
  border-right: 1px solid #ced4da !important;
  position: relative;
  z-index: 1;
  line-height: 1.5;
  height: calc(2.25rem + 2px);
}

.form-control:focus {
  position: relative;
  z-index: 2;
  border-right: 1px solid #80bdff !important;
  border-color: #80bdff;
  box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

/* Button Styles */
.btn-primary {
  background-color: #007bff;
  border: none;
  padding: 10px;
}

.btn-primary:hover {
  background-color: #0056b3;
}

/* Logo Styles */
.logo-img {
  max-width: 200px;
  height: auto;
  margin-bottom: 15px;
}
</style>

<body>
<div class="login-box">
  <div class="card">
    <div class="login-card-body">
      <center>
        <!-- <img class="logo-img" src="logo_fordev22_2.png"> -->
        <h3><b>สะดวกขาย POS</b></h3>
        <h4>Please Login</h4>
      </center>
      
      <form action="chk_login.php" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="mem_username" id="mem_username" placeholder="Username"autocomplete="off">
          
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user text-primary"></span>
            </div>
          </div>
        </div>
        
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="mem_password" id="mem_password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock text-primary"></span>
            </div>
          </div>
        </div>
        
        <div class="social-auth-links text-center mb-3">
          <button type="submit" class="btn btn-primary btn-block">Login</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="assets/jquery.min.js"></script>
<script src="assets/bootstrap.bundle.min.js"></script>
</body>
</html>