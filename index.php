<!DOCTYPE html>
<html>
<head>
	<title>Coding search engine</title>
	<link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
	<div class='topnav'>
		<?php	
			session_start();		
			$servername = "localhost";
			$username = "root";
			$password = "";
			$conn = new mysqli($servername, $username, $password);
			if ($conn->select_db('pse') === false) {
			    $conn->query("CREATE DATABASE pse");
			}
			$conn->select_db('pse');
			$res = mysqli_query($conn,"SHOW TABLES LIKE 'users'");
			if($res->num_rows == 0){
				$conn->query('create table users(email varchar(128) primary key,username varchar(128),password varchar(128));'); 
			}	
			$res = mysqli_query($conn,"SHOW TABLES LIKE 'posts'");
			if($res->num_rows == 0){
				$conn->query('create table posts(id bigint primary key,title varchar(128),username varchar(128),syntax varchar(128),language varchar(128),description varchar(2048),upvotes integer,comments integer);'); 
			}	
			if(array_key_exists(("logout"), $_REQUEST)){	
				session_unset();
				session_destroy();
				header('Location:index.php');
			}	
			if(array_key_exists(("removePID"), $_REQUEST)){
				$res = mysqli_query($conn,"SELECT * FROM posts WHERE id=".$_REQUEST["removePID"].";");	
				$row = mysqli_fetch_assoc($res);
				if(array_key_exists(("username"), $_SESSION)){	
					if($_SESSION["username"]==$row["username"]){
						$conn->query('drop table u'.$_REQUEST['removePID']);
						$conn->query('drop table d'.$_REQUEST['removePID']);
						$conn->query('drop table c'.$_REQUEST['removePID']); 
						$conn->query('delete from posts where id='.$_REQUEST['removePID']);
					}
				}
				
				header("Location:index.php");
			}	
			if(array_key_exists(("email"), $_REQUEST)){
				$email = $_REQUEST['email'];
				$username = $_REQUEST['username'];
				$password = $_REQUEST['password'];
				$res = mysqli_query($conn,"SELECT email FROM users WHERE email = '".$email."'");

				if($res->num_rows == 0){
					$res = mysqli_query($conn,"SELECT username FROM users WHERE username = '".$username."'");

					if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
						if($res->num_rows == 0){
							mysqli_query($conn,"insert into users values('".$email."','".$username."','".$password."');"); 
							header("Location:index.php");
						}		
						else{
							header('Location:login.php?error=2');
						}			
					}					
					else{
						header('Location:login.php?error=4');
					}
				}	
				else{
					header('Location:login.php?error=1');
				}
			}
			else{
				if(array_key_exists(("username"), $_REQUEST)){
					$username = $_REQUEST['username'];
					$password = $_REQUEST['password'];
					$res = mysqli_query($conn,"SELECT username,password FROM users WHERE username = '".$username."'");

					if($res->num_rows > 0){
						$row = mysqli_fetch_assoc($res);
						if($row['password']==$password){
							$_SESSION["username"]=$username;	
							header("Location:index.php");												
						}
						else{
							header('Location:login.php?error=0');
						}
					}	
					else{
						header('Location:login.php?error=0');
					}
				}
			}
			if(array_key_exists(("newTitle"), $_REQUEST)){
				
				$id=round(microtime(true) * 1000);
				$title=$_REQUEST['newTitle'];
				$syntax=$_REQUEST['newSyntax'];
				$language=$_REQUEST['newLanguage'];
				$description=$_REQUEST['newDescription'];
				$title=str_replace("'", "\\'", $title);
				$title=str_replace(";", "\\;", $title);
				$title=str_replace("\"", "\\\"", $title);
				$syntax=str_replace("'", "\\'", $syntax);
				$syntax=str_replace(";", "\\;", $syntax);
				$syntax=str_replace("\"", "\\\"", $syntax);
				$language=str_replace("'", "\\'", $language);
				$language=str_replace(";", "\\;", $language);
				$language=str_replace("\"", "\\\"", $language);
				$description=str_replace("'", "\\'", $description);
				$description=str_replace(";", "\\;", $description);
				$description=str_replace("\"", "\\\"", $description);
				
				if (mysqli_query($conn,'insert into posts values("'.$id.'","'.$title.'","'.$_SESSION["username"].'","'.$syntax.'","'.$language.'","'.$description.'",0,0);') === TRUE) {
					$conn->query('create table c'.$id.'(id bigint primary key,username varchar(128),msg varchar(128))');
					$conn->query('create table u'.$id.'(username varchar(128))'); 
					$conn->query('create table d'.$id.'(username varchar(128))'); 
					header("Location:index.php");
				}	
				else{
					echo 'Failed to add new post, retry';
				}
			}
			echo '<div class="search-container">';
			if(array_key_exists("q", $_REQUEST)){
				echo '<input placeholder="Query" type="text" name="search" id="query" value="'.$_REQUEST['q'].'"> <button onclick="search()">🔍	Search</button>';
			}
			else{				
				echo '<input placeholder="Query" type="text" name="search" id="query"> <button onclick="search()">	Search</button>';
			}
			echo '</div>';
			echo "&nbsp; <a href='newPost.php'>+ New</a>";
			if(array_key_exists("username", $_SESSION)){
				echo '<a class="profileButton" href="profile.php?username='.$_SESSION["username"].'">'.$_SESSION["username"].'</a>';
			}
			else{
				echo "&nbsp; <a href='login.php'>Login / Signup</a>";
			}			
		?>
		

		
	</div>
	
	
	<div>
		<?php
			if(array_key_exists("q", $_REQUEST)){
				echo '<br>&nbsp; <a href="https://www.google.com/search?q='.$_REQUEST['q'].'">Google</a>&nbsp;';
				echo '&nbsp; <a href="https://en.wikipedia.org/w/index.php?search='.$_REQUEST['q'].'">Wikipedia</a>';
			}
		?>
		
	</div>

	<?php
		$query="";
		$rowsInPage=10;
		if(array_key_exists("q", $_REQUEST)){
		    $query=" where (title LIKE '%".$_REQUEST["q"]."%' OR description LIKE '%".$_REQUEST["q"]."%')";
		}
		
		$result = mysqli_query($conn,"select count(1) FROM posts".$query." ORDER BY upvotes desc, id desc;");
        $row = mysqli_fetch_assoc($result);
        $total = $row['count(1)'];
		
		$page=0;
	    if(array_key_exists("page", $_REQUEST)){
	        $page=$_REQUEST["page"];   
	    }
		$res = mysqli_query($conn,"SELECT * FROM posts".$query." ORDER BY upvotes desc, id desc limit ".($page*$rowsInPage).",".(($page+1)*$rowsInPage).";");	
		while ($row = mysqli_fetch_assoc($res)) { 
			echo 
			'<br><table class="data_root clickable" onclick="redirect(\'viewPost.php?id='.$row['id'].'\')">
			<tr>
				<td >'.$row['title'].'
				<small> - '.$row['username'].'</small></td>
				<td rowspan=2 style="width: 3%;">'.$row['upvotes'].' ⇅</td>
			</tr>
			<tr>
				<td>Language : '.$row['language'].' - '.$row['syntax'].'</td>
			</tr>
			</table>';
		}
		
		echo '<br><div style="width:80%;margin:auto;">';
		if($page!=0){
		    $out="index.php?";
		    if(array_key_exists("q", $_REQUEST)){
		        $out=$out."q=".$_REQUEST["q"]."&";   
		    }
		    $out=$out."page=".($page-1);
		    echo '<button onclick="location.href=\''.$out.'\'">Previous page</button>';
		}
		
		if((($page+1)*$rowsInPage)<$total){
		    $out="index.php?";
		    if(array_key_exists("q", $_REQUEST)){
		        $out=$out."q=".$_REQUEST["q"]."&";   
		    }
		    $out=$out."page=".($page+1);
		    echo '<button style="float:right;" onclick="location.href=\''.$out.'\'">Next page</button>';
		}
		echo '</div>';
		
	?>

</body>
<script type="text/javascript">
	document.getElementById('query').onkeydown = function(e){
	   if(e.keyCode == 13){
	     search();
	   }
	};
	function search(){
		var query=document.getElementById("query").value;
		
		if(query.length>0){
			window.location="?q="+query;
		}
	}
	function redirect(path){
		window.location=path;
	}
</script>
</html>