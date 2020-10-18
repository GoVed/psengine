<!DOCTYPE html>
<html>
<head>
	<title>Login / Signup</title>
	<style type="text/css">
		@import url(https://fonts.googleapis.com/css?family=Roboto:300);

		.login-page {
		  width: 360px;
		  padding: 8% 0 0;
		  margin: auto;
		}
		.form {
		  position: relative;
		  z-index: 1;
		  background: #FFFFFF;
		  max-width: 360px;
		  margin: 0 auto 100px;
		  padding: 45px;
		  text-align: center;
		  box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
		}
		.form input {
		  font-family: "Roboto", sans-serif;
		  outline: 0;
		  background: #f2f2f2;
		  width: 100%;
		  border: 0;
		  margin: 0 0 15px;
		  padding: 15px;
		  box-sizing: border-box;
		  font-size: 14px;
		}
		.form button {
		  font-family: "Roboto", sans-serif;
		  text-transform: uppercase;
		  outline: 0;
		  background: #4CAF50;
		  width: 100%;
		  border: 0;
		  padding: 15px;
		  color: #FFFFFF;
		  font-size: 14px;
		  -webkit-transition: all 0.3 ease;
		  transition: all 0.3 ease;
		  cursor: pointer;
		}
		.form button:hover,.form button:active,.form button:focus {
		  background: #43A047;
		}
		.error{
			color: #ffb3b3;
			font-size: 12px;
		}
		.form .message {
		  margin: 15px 0 0;
		  color: #b3b3b3;
		  font-size: 12px;
		}
		.form .message a {
		  color: #4CAF50;
		  text-decoration: none;
		}
		.form .register-form {
		  display: none;
		}
		.container {
		  position: relative;
		  z-index: 1;
		  max-width: 300px;
		  margin: 0 auto;
		}
		.container:before, .container:after {
		  content: "";
		  display: block;
		  clear: both;
		}
		.container .info {
		  margin: 50px auto;
		  text-align: center;
		}
		.container .info h1 {
		  margin: 0 0 15px;
		  padding: 0;
		  font-size: 36px;
		  font-weight: 300;
		  color: #1a1a1a;
		}
		.container .info span {
		  color: #4d4d4d;
		  font-size: 12px;
		}
		.container .info span a {
		  color: #000000;
		  text-decoration: none;
		}
		.container .info span .fa {
		  color: #EF3B3A;
		}
		body {
		  background: #76b852; /* fallback for old browsers */
		  background: -webkit-linear-gradient(right, #76b852, #8DC26F);
		  background: -moz-linear-gradient(right, #76b852, #8DC26F);
		  background: -o-linear-gradient(right, #76b852, #8DC26F);
		  background: linear-gradient(to left, #76b852, #8DC26F);
		  font-family: "Roboto", sans-serif;
		  -webkit-font-smoothing: antialiased;
		  -moz-osx-font-smoothing: grayscale;      
		}
	</style>
</head>
<body>
	<div class="login-page">
	  <div class="form">
	    <form class="register-form" action='index.php' method="post">
	      <input type="text" name="username" placeholder="username" required/>
	      <input type="password" name="password" placeholder="password" required/>
	      <input type="text" name="email" placeholder="email address"/>
	      <button>create</button>
	      <p class="message">Already registered? <a href="#">Sign In</a></p>
	    </form>
	    <form class="login-form" action='index.php' method="post">
	      <input type="text" name="username" placeholder="username" required/>
	      <input type="password" name="password" placeholder="password" required/>
	      <button>login</button>
	      <p class="message">Not registered? <a href="#">Create an account</a></p>
	    </form>	   
	    <?php
	    	if(array_key_exists(("error"), $_REQUEST)){
	    		$error=$_REQUEST['error'];
	    		echo '<p class="error">';
	    		if($error==0){
	    			echo 'Username/Password is incorrect';
	    		}
	    		if($error==1){
	    			echo 'E-Mail ID already registered';
	    		}
	    		if($error==2){
	    			echo 'Username is not available';
	    		}
	    		if($error==3){
	    			echo 'Login/Signup to create new posts';
	    		}
	    		if($error==4){
	    			echo 'Invalid email';
	    		}
	    		echo '</p>';
	    	}
	    ?>
	  </div>
	</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
	$('.message a').click(function(){
	   $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
	});
</script>
</html>