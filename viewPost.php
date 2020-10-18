<!DOCTYPE html>
<html>
<head>
	<title>Post</title>
	<link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
	<br>
	<div class="data_root">
		<?php
			session_start();
			function timeDiff($time1,$time2){
				$diff=$time2-$time1;
				if($diff<0){
					$diff=$diff*-1;
				}		
				$diff=$diff/1000;
				$mode=0;		
				if($diff>60){
					$diff/=60;
					$mode++;
				}
				if($diff>60){
					$diff/=60;
					$mode++;
				}
				if($diff>24){
					$diff/=24;
					$mode++;
				}
				if($diff>30){
					$diff/=30;
					$mode++;
				}
				if($diff>12){
					$diff/=12;
					$mode++;
				}
				$unit="";
				if($mode==0){
					$unit=" second";
				}
				if($mode==1){
					$unit=" minute";
				}
				if($mode==2){
					$unit=" hour";
				}
				if($mode==3){
					$unit=" day";
				}
				if($mode==4){
					$unit=" month";
				}
				if($mode==5){
					$unit=" year";
				}
				$diff=round($diff);
				if($diff!=1){
					$unit=$unit."s";
				}
				return $diff.$unit;
			}


			$servername = "localhost";
			$username = "root";
			$password = "";
			$conn = new mysqli($servername, $username, $password);
			$conn->select_db('pse');

			if(array_key_exists(("comment"), $_REQUEST)){
				if(array_key_exists(("username"), $_SESSION)){
					$id=round(microtime(true) * 1000);
					$msg=$_REQUEST['comment'];
					$username=$_SESSION['username'];
					$msg=str_replace("'", "\\'", $msg);
					$msg=str_replace(";", "\\;", $msg);
					$msg=str_replace("\"", "\\\"", $msg);
					$username=str_replace("'", "\\'", $username);
					$username=str_replace(";", "\\;", $username);
					$username=str_replace("\"", "\\\"", $username);
					mysqli_query($conn,'insert into c'.$_REQUEST['id'].' values("'.$id.'","'.$username.'","'.$msg.'");'); 
				}
				
				
				header("Location:viewPost.php?id=".$_REQUEST["id"]);
			}

			if(array_key_exists(("upvote"), $_REQUEST)){
				if(array_key_exists(("username"), $_SESSION)){
					$res = mysqli_query($conn,"SELECT * FROM u".$_REQUEST["id"]." where username='".$_SESSION["username"]."';");
					
					if($res->num_rows==0){
						mysqli_query($conn,'insert into u'.$_REQUEST["id"].' values("'.$_SESSION["username"].'")');
					}
					else{
						mysqli_query($conn,'delete from u'.$_REQUEST["id"].' where username="'.$_SESSION["username"].'"');
					}				
					$res = mysqli_query($conn,"SELECT * FROM d".$_REQUEST["id"]." where username='".$_SESSION["username"]."';");
					if($res->num_rows>0){
						mysqli_query($conn,'delete from d'.$_REQUEST["id"].' where username="'.$_SESSION["username"].'"');
					}

					$res = mysqli_query($conn,"SELECT * FROM u".$_REQUEST["id"].";");
					$upvoteCount=$res->num_rows;
					$res = mysqli_query($conn,"SELECT * FROM d".$_REQUEST["id"].";");
					$downvoteCount=$res->num_rows;
					$votes=$upvoteCount-$downvoteCount;
					mysqli_query($conn,'update posts set upvotes='.$votes.' where id="'.$_REQUEST["id"].'"');
				}
				header("Location:viewPost.php?id=".$_REQUEST["id"]);
			}
			if(array_key_exists(("downvote"), $_REQUEST)){
				if(array_key_exists(("username"), $_SESSION)){
					$res = mysqli_query($conn,"SELECT * FROM d".$_REQUEST["id"]." where username='".$_SESSION["username"]."';");
					
					if($res->num_rows==0){
						mysqli_query($conn,'insert into d'.$_REQUEST["id"].' values("'.$_SESSION["username"].'")');
					}
					else{
						mysqli_query($conn,'delete from d'.$_REQUEST["id"].' where username="'.$_SESSION["username"].'"');
					}				
					$res = mysqli_query($conn,"SELECT * FROM u".$_REQUEST["id"]." where username='".$_SESSION["username"]."';");
					if($res->num_rows>0){
						mysqli_query($conn,'delete from u'.$_REQUEST["id"].' where username="'.$_SESSION["username"].'"');
					}

					$res = mysqli_query($conn,"SELECT * FROM u".$_REQUEST["id"].";");
					$upvoteCount=$res->num_rows;
					$res = mysqli_query($conn,"SELECT * FROM d".$_REQUEST["id"].";");
					$downvoteCount=$res->num_rows;
					$votes=$upvoteCount-$downvoteCount;
					mysqli_query($conn,'update posts set upvotes='.$votes.' where id="'.$_REQUEST["id"].'"');
				}
				header("Location:viewPost.php?id=".$_REQUEST["id"]);
			}

			if(array_key_exists(("removeComment"), $_REQUEST)){
				
				$res = mysqli_query($conn,"SELECT * FROM c".$_REQUEST["id"]." where id=".$_REQUEST["removeComment"].";");
				$row = mysqli_fetch_assoc($res);
				if($row["username"]==$_SESSION["username"]){
					mysqli_query($conn,'update c'.$_REQUEST['id'].' set msg="[DELETED COMMENT]" where id="'.$_REQUEST["removeComment"].'";');
				}
				
				header("Location:viewPost.php?id=".$_REQUEST["id"]);
			}	

			$res = mysqli_query($conn,"SELECT * FROM posts WHERE id=".$_REQUEST["id"].";");	
			$row = mysqli_fetch_assoc($res);
			
			echo '<big>'.$row['title'].'</big><small> '.timeDiff($row['id'],round(microtime(true) * 1000)).' ago by <a href=\'profile.php?username='.$row['username'].'\'>'.$row['username'].'</a></small>';
			if(array_key_exists("username", $_SESSION)){
				if($_SESSION["username"]==$row["username"]){
					echo '<button class="removePost" id="removeButton" onclick="remove(\''.$_REQUEST["id"].'\')">Remove</button>';
				}
			}

			$upvoted=false;
			if(array_key_exists(("username"), $_SESSION)){
				$res = mysqli_query($conn,"SELECT * FROM u".$_REQUEST["id"]." where username='".$_SESSION["username"]."';");			
				if($res->num_rows>0){
					$upvoted=true;
				}
			}
			

			$downvoted=false;
			if(array_key_exists(("username"), $_SESSION)){
				$res = mysqli_query($conn,"SELECT * FROM d".$_REQUEST["id"]." where username='".$_SESSION["username"]."';");			
				if($res->num_rows>0){
					$downvoted=true;
				}
			}
			

			echo '<hr><table style="width:100%"><tr><td rowspan=3>';
			echo 'Syntax: '.$row['syntax'].'<br>';
			echo 'Language: '.$row['language'].'<br>';
			echo $row['description'].'<br>';

			if($upvoted){
				echo '<td style="width:3%;text-align:center;cursor:pointer;color:green;" onclick="vote(true)"><big>▲</big></td></tr>';
			}
			else{
				echo '<td style="width:3%;text-align:center;cursor:pointer;" onclick="vote(true)"><big>▲</big></td></tr>';	
			}
			echo '<tr><td style="text-align:center;">'.$row["upvotes"].'</td></tr>';
			if($downvoted){
				echo '<tr><td style="text-align:center;cursor:pointer;color:red;" onclick="vote(false)"><big>▼</big></td></tr>';
			}			
			else{
				echo '<tr><td style="text-align:center;cursor:pointer;" onclick="vote(false)"><big>▼</big></td></tr>';
			}
			echo '</tr></table>';
			
		?>
	</div>
	<br>
	<div class="data_root">
		Comments<hr>
		<div class="field-wrap">
			<input id="comment" placeholder="Write a comment here..."><BUTTON style="float:right;" onclick="addComment()">Post</BUTTON>			
		</div>
		<div class="marginElement">
			<?php
				$res = mysqli_query($conn,"SELECT * FROM c".$_REQUEST["id"]." ORDER BY id desc;");	
				if($res!=null){
					while ($row = mysqli_fetch_assoc($res)) {
						echo '<a href=\'profile.php?username='.$row['username'].'\'>'.$row['username'].'</a> <small>('.timeDiff($row['id'],round(microtime(true) * 1000)).' ago)</small>';
						if(array_key_exists(("username"), $_SESSION)){
							if($row["username"]==$_SESSION["username"]){
								if($row["msg"]!="[DELETED COMMENT]"){
									echo '<button class="removePost" id="removeComment'.$row["id"].'" onclick="removeComment(\''.$row["id"].'\')">Remove</button>';
								}
							}
						}
						
						echo '<br>';
						echo $row['msg']."<br><br>";
					}	
				}	
			?>
		</div>
	</div>
</body>
<script type="text/javascript">
	const urlParams = new URLSearchParams(window.location.search);
	document.getElementById('comment').onkeydown = function(e){
	   if(e.keyCode == 13){
	     addComment();
	   }
	};
	function addComment(){
		var comment=document.getElementById("comment").value;
		if(comment.length>0){
			window.location="viewPost.php?id="+urlParams.get("id")+"&comment="+comment;
		}
	}
	function remove(id){
		if(document.getElementById('removeButton').innerHTML=="Remove"){
			document.getElementById('removeButton').innerHTML="Confirm?"
		}
		else{
			window.location="index.php?removePID="+id;
		}
	}
	function removeComment(id){
		if(document.getElementById('removeComment'+id).innerHTML=="Remove"){
			document.getElementById('removeComment'+id).innerHTML="Confirm?"
		}
		else{
			window.location="viewPost.php?id="+urlParams.get("id")+"&removeComment="+id;
		}
	}
	function vote(mode){
		var out="viewPost.php?id="+urlParams.get("id")+"&";
		if(mode){
			out+="upvote=true"		
		}
		else{
			out+="downvote=true"
		}
		window.location=out;
	}
</script>
</html>