<?php
include_once('header.php');
?>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Roles</title>
  <script src="js/jquery.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/habits.js"></script>
  <link rel="stylesheet" href="css/roles.css" />
  </head>
<body>

<div id="mod-btns">
	<div id="delete-habit-btn"><a href="deleteHabits.php">Delete Habits</a></div>
	<div id="add-habit-btn"> <a href="addHabits.php">Add Habits</a></div>
</div>
<form id="habit-form"> 
	<input id="habit-text" type=text name="dreamText"/><img class="loader" src="img/load.gif"></img>
</form> 

<div id="dream-habits">
	<ul id="sortable-select">
	</ul>
</div> 
 
</body>
</html>
