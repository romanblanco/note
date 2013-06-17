<?php
	
	// connection to database
	$host = "localhost";
	$user = "root";
	$password = "";
	$database = "note";

	$connection = mysqli_connect($host, $user, $password, $database);
	// control of database connection
	if (mysqli_connect_errno($connection)) {
		echo "Connection to MySQL database failed: ".mysqli_connect_error();
		exit;
	}

	mysqli_query($connection, "SET NAMES utf8");

	function tagList($connection) {
		// select all items from 'tag' table
		$sqlTask = mysqli_query($connection, "
			SELECT DISTINCT `tag` 
			FROM `notes`
		");
		while ($result = mysqli_fetch_array($sqlTask)) {
			echo "<option value=\"" . $result[tag] . "\">" . $result[tag] . "</option>\n";
		}
	}

	function addNew($connection) {
		// adding new note into database
		// controll if required form aren't empty
		if (isset($_REQUEST['note']) && (isset($_REQUEST['topic']))) {
			if ($_REQUEST['note'] !== '' && $_REQUEST['topic'] !== '') {
				$_topic = trim(HTMLSpecialChars($_POST[topic]));
				$_note = HTMLSpecialChars($_POST[note]);
				$_tag = trim(HTMLSpecialChars($_POST[tag]));
				$sqlTask = "
					INSERT INTO `notes` (`topic`, `note`, `tag`, `date`) 
					VALUES ('$_topic', '$_note', '$_tag', now())
				";
				if (!mysqli_query($connection, $sqlTask)) {
					die('Error during saving note: ' . mysqli_error($connection));
				} else {
					echo "<span>The note was written in</span>";
				}
			}
		}
	}

	function printNote($connection) {
		if (isset($_GET['tag'])) {
			if ($_GET['tag'] !== '') { 
				// display only chosen tag
				$sqlTask = mysqli_query($connection, "
					SELECT * 
					FROM `notes` 
					WHERE `tag` = '$_GET[tag]' 
					ORDER BY `date` 
					DESC
				");
				while ($result = mysqli_fetch_array($sqlTask)) {
					echo "<div class=\"module green\">\n";
					echo "<h2>". $result['topic']. "<a href=\"" . $_SERVER["PHP_SELF"] . "?tag=" . $result['tag'] . "\">" . $result['tag'] . "</a></h2>\n";
					echo nl2br($result['note'])."\n";
					echo "</div>\n";
				}
			}
		} elseif (isset($_REQUEST['search'])) {
			if ($_REQUEST['search'] !== '') { 
				// display all items that are matched with looked form
				$sqlTask = mysqli_query($connection, "
					SELECT * 
					FROM `notes` 
					WHERE (`topic` REGEXP '$_REQUEST[search]' 
					OR `note` REGEXP '$_REQUEST[search]' 
					OR `tag` REGEXP '$_REQUEST[search]' 
					OR `date` REGEXP '$_REQUEST[search]') 
					ORDER BY `date`
				");
				while ($result = mysqli_fetch_array($sqlTask)) {
					echo "<div class=\"module green\">\n";
					echo "<h2>". $result['topic']. "<a href=\"" . $_SERVER["PHP_SELF"] . "?tag=" . $result['tag'] . "\">" . $result['tag'] . "</a></h2>\n";
					echo nl2br($result['note'])."\n";
					echo "</div>\n";
				}
			}
		} else {
			// display all items
			$sqlTask = mysqli_query($connection, "
				SELECT * 
				FROM `notes` 
				ORDER BY `date` 
				DESC
			");
			while ($result = mysqli_fetch_array($sqlTask)) {
				echo "<div class=\"module green\">\n";
				echo "<h2>". $result['topic']. "<a href=\"" . $_SERVER["PHP_SELF"] . "?tag=" . $result['tag'] . "\">" . $result['tag'] . "</a></h2>\n";
				echo nl2br($result['note'])."\n";
				echo "</div>\n";
			}
		}
		mysqli_close($connection);
	}
?>
