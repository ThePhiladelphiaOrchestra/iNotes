<?php

$link    = mysqli_connect('localhost', 'inotes', 'inotes');
$charset = mysqli_character_set_name($link);
echo "The current character set is: $charset\n";

?>