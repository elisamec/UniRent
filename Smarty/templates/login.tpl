<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="icon" href="/UniRent/Smarty/images/favicon.png" type="image/png">
  <link rel="stylesheet" type="text/css" href="/UniRent/Smarty/css/login.css">

</head>
<body>
  <div class="container">
      <div class="heading">Sign In</div>
      <form action="" class="form">
        <input required="" class="input" type="email" name="email" id="email" placeholder="E-mail">
        <input required="" class="input" type="password" name="password" id="password" placeholder="Password">
        <span class="forgot-password"><a href="#">Forgot Password ?</a></span>
        <input class="login-button" type="submit" value="Login">
      </form>
      <div class="subheading">You don't have an account?</div>
      <form action="" class="form">
        <input class="login-button" type="submit" onclick="location.href='#'" value="Sign Up">
      </form>
  </div>
</body>
</html>