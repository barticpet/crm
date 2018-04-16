<!DOCTYPE html>
<!--<html lang="en">
<head>
  <title>Bootstrap table</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">-->
<div class="starter-template">
        <h1>Lista useri</h1>
        <p class="lead"></p>
      </div>
  <table class="table table-striped">
    <?php if($_SESSION['user']['user_role'] == 2){
    ?>
      <thead>
      <tr>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>Email</th>
        <th>Wrong Logins</th>
        <th>User Role</th>
        <th>Confirmed</th>
      </tr>
    </thead><tbody>
    <?php
    	foreach ($vars as $user) {
    	?>
    		<tr>
		        <td><?=$user['fname']?></td>
		        <td><?=$user['lname']?></td>
		        <td><?=$user['email']?></td>
		        <td><?=$user['wrong_logins']?></td>
		        <td><?=$user['user_role']?></td>
		        <td><?=$user['confirmed']?></td>
		    </tr>
    	<?php
    	}
    }else{ ?>
    <thead>
      <tr>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>Email</th>
      </tr>
    </thead><tbody>
      <tr>
        <td><?=$_SESSION['user']['fname']?></td>
        <td><?=$_SESSION['user']['lname']?></td>
        <td><?=$_SESSION['user']['email']?></td>
      </tr>    
    <?php } ?></tbody>
  </table>
  <!--<p><a href='logout.php'>Logout</a></p>   -->

  
<!--</div>

</body>
</html>-->
