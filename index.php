<?php
/*
 * $Id: index.php,v 0.2 2015/02/02 12:00:00 Janos Szentgyorgyi $
 *
 *  Licensed under the Apache License.
 *    http://www.apache.org/licenses/
 *
 */

include_once "lib/easyRss.php";

$RSS = new easyRss();
$RSS->load("https://news.google.com/?output=rss", true);
// print channel
foreach ($RSS->channel as $key => $value){
    echo "<br/><strong>$key:</strong>";
    if (is_string($value)){
        echo " $value";
    }
    if (is_array($value)){
        foreach($value as $v_key => $v_value){
            echo "<br/> - <strong>$v_key:</strong> $v_value";
        }
    }
}
?>