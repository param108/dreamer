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
	<div id="add-select-role-btn">
		<a href="roles.php">Select Role</a>
	</div>
</div>

<form id="role-form"> 
	<input id="dream-text" type=text name="dreamText"/>
	<input id="dream-btn" type=submit value="Add"/>
	<img class="loader" src="img/load.gif"></img>
</form> 

<div id="dream-roles">
	<ul id="sortable-add">
	</ul>
</div> 
 
</body>
</html>
