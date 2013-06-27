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
		// select all items from 'tag' column 
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
				$_note = trim(HTMLSpecialChars($_POST[note], ENT_QUOTES));
				$_tag = trim(HTMLSpecialChars($_POST[tag], ENT_QUOTES));
				$sqlTask = "
					INSERT INTO `notes` (`topic`, `note`, `tag`, `date`) 
					VALUES ('$_topic', '$_note', '$_tag', now())
				";
				if (!mysqli_query($connection, $sqlTask)) {
					echo "<div class=\"notify failure\">Error during saving note: " . mysqli_error($connection) . "</div>";
				} else {
					echo "<div class=\"notify success\">The note was written in</div>";
				}
				echo "<script>notify()</script>";
			}
		}
	}

	function getNote($connection) {
		// display only chosen tag
		if (isset($_GET['tag'])) {
			if ($_GET['tag'] !== '') { 
				$sqlTask = mysqli_query($connection, "
					SELECT * 
					FROM `notes` 
					WHERE `tag` = '$_GET[tag]' 
					ORDER BY `date` 
					DESC
				");
				printNote($sqlTask);
			}
		} 
			// display all items that are matched with looked form
			elseif (isset($_GET['search'])) {
			if ($_GET['search'] !== '') { 
				$_GET['search'] = HTMLSpecialChars($_GET['search']);
				$sqlTask = mysqli_query($connection, "
					SELECT * 
					FROM `notes` 
					WHERE (`topic` REGEXP '$_GET[search]' 
					OR `note` REGEXP '$_GET[search]' 
					OR `tag` REGEXP '$_GET[search]' 
					OR `date` REGEXP '$_GET[search]') 
					ORDER BY `date`
				");
				printNote($sqlTask);
			}
		} 
			// display all items
			else {
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
			echo " 
				<div id=\"".$result['id']."\" class=\"module\">
					<h3>". 
						$result['topic']."
						<a href=\"".$_SERVER["PHP_SELF"]."?tag=".$result['tag']."#search\">". 
							$result['tag']."
						</a>
					</h3>
					<div class=\"note\">".
						nl2br($result['note'])."
					</div>
					<div class=\"info\">
						<a href=\"".$_SERVER["PHP_SELF"]."?action=edit&id=".$result['id']."\">
							<img src=\"./files/pencil32.png\" alt=\"Edit\">
						</a>
						<a href=\"".$_SERVER["PHP_SELF"]."?action=delete&id=".$result['id']."\">
							<img src=\"./files/stop32.png\" alt=\"Remove\">
						</a>
						<span>".
							date("d.m.Y", strtotime($result['date']))."
						</span>
					</div>
				</div>
				";
		}
	}

	function changeNotes($connection) {
		// deleting note
		if (isset($_GET['action'])) {
			if ($_GET['action'] === 'delete') { 
				$sqlTask = "
					DELETE FROM `notes`
					WHERE `id` = '$_GET[id]'
				";
				if (!mysqli_query($connection, $sqlTask)) {
					echo "<div class=\"notify failure\">Error during deleting note: " . mysqli_error($connection) . "</div>";
				} else {
					echo "<div class=\"notify success\">The note successfully removed</div>";
				}
				echo "<script>notify()</script>";
			}
		}
		// editing note
		if (isset($_GET['action'])) {
			if ($_GET['action'] === 'edit') { 
				global $IfEdit_topic;
				global $IfEdit_note;
				global $IfEdit_tag;
				$sqlTask = mysqli_query($connection, "
					SELECT *
					FROM `notes`
					WHERE `id` = '$_GET[id]'
				");
				while ($result = mysqli_fetch_array($sqlTask)) {
					$IfEdit_topic = $result['topic'];
					$IfEdit_note = $result['note'];
					$IfEdit_tag = $result['tag'];
				}
				$sqlTask = mysqli_query($connection, "
					DELETE FROM `notes`
					WHERE `id` = '$_GET[id]'
				");
			}
		}
	}

	function getXML($connection) {
		$sqlTask = mysqli_query($connection, "
			SELECT *
			FROM `notes`
		");
		header("Content-type: text/xml");
		$xmlOutput .= "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>".PHP_EOL;
		$xmlOutput .= "<notes>".PHP_EOL;
		while ($result = mysqli_fetch_array($sqlTask)) {
			$xmlOutput .= 
				"\t<item>".PHP_EOL.
				"\t\t<id>".$result['id']."</id>".PHP_EOL.
					"\t\t<topic>".$result['topic']."</topic>".PHP_EOL.
					"\t\t<note>".$result['note']."</note>".PHP_EOL.
					"\t\t<tag>".$result['tag']."</tag>".PHP_EOL.
					"\t\t<date>".$result['date']."</date>".PHP_EOL.
				"\t</item>".PHP_EOL;
		}
		$xmlOutput .= "</notes>";
		echo $xmlOutput;
	}

?>
