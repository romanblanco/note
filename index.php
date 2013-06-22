<?php require './files/handle.php'; ?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Note</title>
		<link rel="stylesheet" href="./files/style.css" type="text/css">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script src="./files/autoresize.jquery.js"></script>
		<script src="./files/shortcuts.jquery.js"></script>
	</head>
	<body>
	<?php changeNotes($connection) ?>
		<div id="main">
			<h1><a href="<?php echo $_SERVER["PHP_SELF"]; ?>">Note</a></h1>
			<div class="left-container">
				<form id="add" method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
					<h2>Topic:</h2>
					<input pattern=".{1,35}" type="text" name="topic" title="MAX 30 characters" value="<?php print $IfEdit_topic ?>" required>
					<h2>Note:</h2>
					<textarea id="resizable" style="height: 200px;" name="note" required><?php echo $IfEdit_note ?></textarea>
					<h2>Tag:</h2>
					<datalist id="tags">
						<?php tagList($connection); ?>
					</datalist>
					<input pattern=".{1,20}" type="text" name="tag" list="tags" title="MAX 20 characters" value="<? echo $IfEdit_tag ?>" required>
					<input type="image" src="./files/add.png" alt="Add">
				</form>
				<?php addNew($connection); ?>
			</div>
			<div class="right-container">
				<form  id="search" method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
					<h2>Notes: </h2>
					<input id="search" type="search" placeholder=" Search..." name="search">
					<input type="submit" style="visibility: hidden;">
				</form>
				<?php getNote($connection); ?>
			</div>
		</div>
	</body>
</html>
