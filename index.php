<?php require './files/handle.php'; ?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Note</title>
		<link rel="stylesheet" href="./files/style.css" type="text/css">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js"></script>
		<script src="./files/autoresize.jquery.js"></script>
		<script src="./files/shortcuts.jquery.js"></script>
	</head>
	<body>
		<div id="main">
			<h1><a href="<?php echo $_SERVER["PHP_SELF"] ?>">Note</a></h1>
			<div class="left-container">
				<form method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>">
					<h2>Topic:</h2>
					<input pattern="[A-Za-z]{1,30}" type="text" name="topic" title="MAX 30 characters" required>
					<h2>Note:</h2>
					<textarea id="resizable" style="height: 200px;" name="note" required></textarea>
					<h2>Tag:</h2>
					<datalist id="tags">
						<?php tagList($connection); ?>
					</datalist>
					<input type="text" name="tag" list="tags" required>
					<input type="image" src="./files/add.png" alt="Add">
				</form>
				<?php addNew($connection); ?>
			</div>
			<div class="right-container">
				<div class="form">
					<form method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>">
					<h2>Notes: </h2>
						<input id="search" type="search" placeholder=" Search..." name="search">
						<input type="submit" style="visibility: hidden;" /> 
					</form>
				</div>
				<?php printNote($connection); ?>
			</div>
		</div>
	</body>
</html>
