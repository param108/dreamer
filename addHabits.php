<?php
include_once('header.php');
?>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Habits</title>
  <script src="js/jquery.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/habits.js"></script>
  <link rel="stylesheet" href="css/roles.css" />
  </head>
<body>

<div id="mod-btns">
	<div id="add-select-role-btn">
		<a href="habits.php">Select Role</a>
	</div>
</div>

<form id="habit-form"> 
	<input id="habit-text" type=text name="dreamText"/>
	<input id="habit-btn" type=submit value="Add"/>
	<img class="loader" src="img/load.gif"></img>
</form> 

<div id="dream-habits">
	<ul id="sortable-add">
	</ul>
</div> 
 
</body>
</html>
