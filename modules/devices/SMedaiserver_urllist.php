<?php
# include data base
require "../../config.php";
$DB = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$DB->set_charset("utf8");
// selectType - tip faila
// selectServers - imya servera
// selectName - imya faila
switch ($_POST['action']){
        case "SERVERS":
		//  сортируме названия серверов 
		$rows = $DB->query('SELECT * FROM mediaservers_playlist WHERE GENRE LIKE"'.$_POST['types'].'"');
                $chekedserver = [];
                echo '<select size="1" name="selectServer" onClick="document.getElementById('."'".'selectedname'."'".').value='."''".'; return false;" onchange="selectNames();selectUrl();" style="width:160px; text-align:center; font-family: monospace; ">';
                foreach ($rows as $numRow => $row) {
                   if (!in_array($row['LINKED_OBJECT'], $chekedserver)){
                       echo '<option value="'.$row['LINKED_OBJECT'].'">'.$row['LINKED_OBJECT'].'</option>';
                       array_push($chekedserver, $row['LINKED_OBJECT']);
                       };
                   };
		echo '</optgroup>';
                echo '</select>';
                echo '</div>';
                return;
        case "NAMES":
		echo '<datalist id="names" name="selected_url" >';
		//  сортируме названия names 
		$rows = $DB->query('SELECT * FROM mediaservers_playlist WHERE LINKED_OBJECT="'.$_POST['servers'].'" AND GENRE LIKE"'.$_POST['types'].'"');
		foreach ($rows as $numRow => $row) {
		  echo '<option >'.$row['TITLE'].'</option>';
		};
		echo '</datalist>';
                return;
        case "URL":
		//  сортируме названия url
		$rows = $DB->query('SELECT * FROM mediaservers_playlist WHERE TITLE="'.$_POST['name'].'" LIMIT 1');   
                foreach ($rows as $numRow => $row) {
                      if ($row['URL_LINK']){
		        echo '<input type="text" id="play_url" value="'.$row['URL_LINK'].'" style="width:160px; text-align:center; font-family: monospace; "/>';
                      return;
		   };
                };
                echo '<input type="text" id="play_url" value="файл не найден" style="width:160px; text-align:center; font-family: monospace; " />';
 		return;
};
?>
