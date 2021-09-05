<?php
    $string = file_get_contents("https://jsnbrn.com/experiments/monitor/status-content.php?".$_SERVER['QUERY_STRING']);

    if($string === FALSE) {
         echo "Could not read the file.";
    } else {
         echo $string;
    }
