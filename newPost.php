<!DOCTYPE html>
<html>
<head>
	<title>Login / Signup</title>
	<style type="text/css">
		.page {
		  width: 720px;
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
		.form input,textarea {
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
	</style>
</head>
<body>
	<?php
		session_start();
		if(!array_key_exists("username", $_SESSION)){
			header('Location:login.php?error=3');
		}
	?>
	<div class="page">
		<div class="form">		
		    <form class="register-form" action='index.php' method="post">
		      <input type="text" name="newTitle" placeholder="Title"/>
		      <input type="text" name="newLanguage" placeholder="Language"/>
		      <input type="text" name="newSyntax" placeholder="Syntax"/>
		      <textarea name="newDescription" placeholder="Description" rows="5" style="resize: none;"></textarea>
		      <button>create</button>	     
		    </form>
		</div>
	</div>
	
	  
</body>

</html>