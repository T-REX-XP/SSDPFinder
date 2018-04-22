<?php

# include data base
require "../../config.php";
$DB = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

echo '<select size="1" name="select_url" onchange="document.getElementById ('."'".'play_url'."'".').value = this.value">';
echo '  <option value="0" >Change the name</option>';
$rows = $DB->query('SELECT * FROM mediaservers_playlist');
foreach ($rows as $numRow => $row) {
   echo '<option value="'.$row['URL_LINK'].'">'.$row['TITLE'].'</option>';
};
echo '</select>';
?>
