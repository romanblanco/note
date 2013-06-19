<?php

	// connection to database
	define("DB_SERVER", "localhost");
	define("DB_USER", "root");
	define("DB_PASSWORD", "");
	define("DB_DATABASE", "note");
	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
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
		if (isset($_REQUEST['note']) && (isset($_REQUEST['topic']))) {
			if ($_REQUEST['note'] !== '' && $_REQUEST['topic'] !== '') {
				$_topic = trim(HTMLSpecialChars($_POST[topic], ENT_QUOTES));
				$_note = HTMLSpecialChars($_POST[note], ENT_QUOTES);
				$_tag = trim(HTMLSpecialChars($_POST[tag], ENT_QUOTES));
				$sqlTask = "
					INSERT INTO `notes` (`topic`, `note`, `tag`, `date`) 
					VALUES ('$_topic', '$_note', '$_tag', now())
				";
				if (!mysqli_query($connection, $sqlTask)) {
					echo "<div class=\"notify failure\">Error during saving note: " . mysqli_error($connection) . "</div>";
					?>
					<script type="text/javascript">
						$(".notify").fadeIn("slow");
						setTimeout(function() {
							$(".success").fadeOut("slow");
						}, 4000);
						$(".notify").click(function () {
							$(".notify").fadeOut(200);
						});
						return false;
					</script>
					<?php
				} else {
					echo "<div class=\"notify success\">The note was written in</div>";
					?>
					<script type="text/javascript">
						$(".notify").fadeIn("slow");
						setTimeout(function() {
							$(".success").fadeOut("slow");
						}, 4000);
						$(".notify").click(function () {
							$(".notify").fadeOut(200);
						});
						return false;
					</script>
					<?php
				}
			}
		}
	}

	function getNote($connection) {
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
				printNote($sqlTask);
			}
		} elseif (isset($_POST['search'])) {
			if ($_POST['search'] !== '') { 
				$_POST['search'] = HTMLSpecialChars($_POST['search']);
				// display all items that are matched with looked form
				$sqlTask = mysqli_query($connection, "
					SELECT * 
					FROM `notes` 
					WHERE (`topic` REGEXP '$_POST[search]' 
					OR `note` REGEXP '$_POST[search]' 
					OR `tag` REGEXP '$_POST[search]' 
					OR `date` REGEXP '$_POST[search]') 
					ORDER BY `date`
				");
				printNote($sqlTask);
			}
		} else {
			// display all items
			$sqlTask = mysqli_query($connection, "
				SELECT * 
				FROM `notes` 
				ORDER BY `date` 
				DESC
			");
			printNote($sqlTask);
		}
		mysqli_close($connection);
	}

	function printNote($sqlTask) {
		while ($result = mysqli_fetch_array($sqlTask)) {
			echo "<div class=\"module\">\n";
			echo "<h3>". $result['topic']. "<a href=\"" . $_SERVER["PHP_SELF"] . "?tag=" . $result['tag'] . "#search\">" . $result['tag'] . "</a></h3>\n";
			echo "<div class=\"note\">";
			echo nl2br($result['note'])."\n";
			echo "</div>";
			echo "<div class=\"info\"><img src=\"./files/pencil32.png\" alt=\"Edit\"> <img src=\"./files/stop32.png\" alt=\"Remove\"><span>".date("d.m.Y", strtotime($result['date']))."</span></div>"; // TODO: Make edit and remove links
			echo "</div>";
		}
	}

?>
