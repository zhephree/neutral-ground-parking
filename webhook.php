<?php
date_default_timezone_set('America/Chicago');
$json = json_decode(file_get_contents('php://input'));
$tweet = strtolower($json->tweet);
$url = $json->url;
$date = str_replace(' at ', ' ', $json->date);

$start = 0;
$end = 0;

if(strpos($tweet, 'neutral ground parking allowed until') !== false || strpos($tweet, 'neutral ground parking is allowed until') !== false){
    $start = strtotime($date);
    if(strpos($tweet, 'further notice') > -1){
        $end = 9999999999;
    }else{
        $str_start = strpos($tweet, 'until ') + 6;
        $str_end = strpos($tweet, 'y. ') + 1;
        $end_date = substr($tweet, $str_start, $str_end - $str_start);
        $e_array = explode(' ', $end_date);
        $end = strtotime($e_array[1] . ' ' . $e_array[0]);
    }
    echo "Start: " . $date . "\n";
    echo "End: " . $e_array[1] . ' ' . $e_array[0] . "\n";

    $output = array("start" => $start, "end" => $end);
}else if(strpos($tweet, 'back into effect at') !== false){
    $str_start = strpos($tweet, 'effect at ') + 10;
    $str_end = strpos($tweet, '. ');
    $end_date = substr($tweet, $str_start, $str_end - $str_start);
    $e_array = explode(' ', $end_date);
    $end = strtotime($e_array[1] . ' ' . $e_array[0]);
    $current = json_decode(file_get_contents('./dates.json'));

    echo "Start: " . $current->start . "\n";
    echo "End: " . $e_array[1] . ' ' . $e_array[0] . "\n";

    $output = array("start" => $current->start, "end" => $end);
}else if(strpos($tweet, 'back into effect today') !== false){
    $str_start = strpos($tweet, 'effect today at ') + 16;
    $str_end = strpos($tweet, '. ');
    $end_date = substr($tweet, $str_start, $str_end - $str_start);
    $e_array = explode(' ', $end_date);
    $end = strtotime($e_array[1] . ' ' . $e_array[0]);
    $current = json_decode(file_get_contents('./dates.json'));

    echo "Start: " . $current->start . "\n";
    echo "End: " . $e_array[1] . ' ' . $e_array[0] . "\n";

    $output = array("start" => $current->start, "end" => $end);
}else{
    echo 'no matching text in tweet: ' . $tweet;
}

$fh = fopen('./dates.json', 'w');
fwrite($fh, json_encode($output));
fclose($fh);