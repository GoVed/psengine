<!DOCTYPE html>
<html>
<head>
	<title>Profile</title>
	<link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
	<div class="topnav">
	<?php

		
		session_start();
		$servername = "localhost";
		$username = "id15146908_root";
		$password = "(v1h&dt7P73D8[@r";
		$conn = new mysqli($servername, $username, $password);
		$conn->select_db('id15146908_pse');

		echo '<span style="display:inline-block;margin:10px;"><big>'.$_REQUEST["username"].'</big></span>';
		if(array_key_exists(("username"), $_SESSION)){	
			if($_SESSION["username"]==$_REQUEST["username"]){
				echo '<a href="index.php?logout=true">Logout</a>';
			}
		}
		echo '<a href="index.php">Home</a>';
	?>
	</div>
	<?php
		echo '<div style="padding-bottom:25px;">This is how I align elements, XD</div>';

		$query=array();
		if(array_key_exists("q", $_REQUEST)){
			array_push($query, " where title like '%".$_REQUEST["q"]."%'");
			array_push($query, " where description like '%".$_REQUEST["q"]."%'");
		}
		else{
			array_push($query, "");
		}
		foreach ($query as $key => $value) {
		    
    		$rowsInPage=10;
    		
    		
    		$result = mysqli_query($conn,"select count(1) FROM posts".$value." WHERE username='".$_REQUEST["username"]."' ORDER BY upvotes desc, id desc;");
            $row = mysqli_fetch_assoc($result);
            $total = $row['count(1)'];
    		
    		$page=0;
    	    if(array_key_exists("page", $_REQUEST)){
    	        $page=$_REQUEST["page"];   
    	    }
    		$res = mysqli_query($conn,"SELECT * FROM posts".$value." WHERE username='".$_REQUEST["username"]."' ORDER BY upvotes desc, id desc limit ".($page*$rowsInPage).",".(($page+1)*$rowsInPage).";");	
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
    		    $out=$out."page=".($page-1);
    		    echo '<button onclick="location.href=\''.$out.'\'">Previous page</button>';
    		}
    		
    		if((($page+1)*$rowsInPage)<$total){
    		    $out="index.php?";
    		    $out=$out."page=".($page+1);
    		    echo '<button style="float:right;" onclick="location.href=\''.$out.'\'">Next page</button>';
    		}
    		echo '</div>';
		    
		    
			
		}
		
		
	?>
</body>
<script type="text/javascript">
	
	function redirect(path){
		window.location=path;
	}
</script>
</html>