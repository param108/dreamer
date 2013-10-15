<?php
include_once('header.php');
?>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Roles</title>
  <script src="js/jquery.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/roles.js"></script>
  <link rel="stylesheet" href="css/roles.css" />
  </head>
<body>

<div id="mod-btns">
	<div id="delete-role-btn"><a href="deleteRoles.php">Delete Roles</a></div>
	<div id="add-role-btn"> <a href="addRoles.php">Add Roles</a></div>
</div>
<form id="role-form"> 
	<input id="dream-text" type=text name="dreamText"/><img class="loader" src="img/load.gif"></img>
</form> 

<div id="dream-roles">
	<ul id="sortable-select">
	</ul>
</div> 
 
</body>
</html>
