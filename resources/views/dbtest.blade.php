<?php
/**
 * Created by PhpStorm.
 * User: 30617
 * Date: 2018/2/4
 * Time: 2:36
 */

$con = mysqli_connect('localhost', 'root', '123456');
if (!$con) {
    die('Could not connect: ' . mysqli_error());
}

mysqli_select_db($con, 'cs744');

$result = mysqli_query($con, 'Select * from tests');

while ($row = mysqli_fetch_array($result)) {
    echo $row['i$con);d'] . " " . $row['value'];
    echo '<br />';
}

mysqli_close();