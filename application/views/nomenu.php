

<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Vow Erp Report Portel</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel="stylesheet" href="<?=base_url('public')?>/assets/login/style.css">
<style>
.parent {
/* height: 200px; */
/* background: #CCCCCC; */
display: flex;
align-items: center;
justify-content: center;
height:500px;

}
.child {
width:100%;
height: 100px;
}
.logout{
    font-size:14px;
    color:#00ff00;
}
</style>
</head>
<body>
<div id="login-form-wrap" class="parent" style="">
<div class="child">
No Menu Permissions.
  <div class="logout"><a href="<?=base_url('welcome/logout')?>">Logout</a></div>
</div>  
</body>
</html>