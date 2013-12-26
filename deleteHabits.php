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
	<div id="delete-select-habit-btn">
		<a href="habits.php">Select Habit</a>
	</div>
</div>


<form id="habit-form"> 
	<input id="habit-text" type=text name="dreamText"/>
	<img class="loader" src="img/load.gif"></img>
</form> 

<div id="dream-habits">
	<ul id="sortable-delete">
	</ul>
</div> 
 
</body>
</html>
