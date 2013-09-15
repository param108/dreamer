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

<form id="add-role-form"> 
	<input id="dream-text" type=text name="dreamText"/>
	<input type=submit value="Add"/>
</form> 

<div id="dream-roles">
	<ul id="sortable">
	</ul>
</div> 
 
</body>
</html>
