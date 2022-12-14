<?php
date_default_timezone_set('America/Chicago');
$tweet = $_GET['tweet'] . ' ';
$tweet = str_replace('a.m.', 'am', $tweet);
$tweet = str_replace('A.M.', 'am', $tweet);
$tweet = str_replace('AM', 'am', $tweet);
$tweet = str_replace(' am', 'am', $tweet);
$tweet = str_replace('p.m.', 'pm', $tweet);
$tweet = str_replace('P.M.', 'pm', $tweet);
$tweet = str_replace('PM', 'pm', $tweet);
$tweet = str_replace(' pm', 'pm', $tweet);

$start = 0;
$end = 0;
function saveFile($output) {
    $fh = fopen('./dates.json', 'w');
    fwrite($fh, json_encode($output));
    fclose($fh);
    echo 'Updated file.';
}

function contains($needle, $haystack = null){
    global $tweet;
    if(!isset($haystack) || is_null($haystack)){
        $haystack = $tweet;
    }
    return strpos(strtolower($haystack), strtolower($needle)) !== false;
}

function where($needle, $haystack = null){
    global $tweet;
    if(!isset($haystack) || is_null($haystack)){
        $haystack = $tweet;
    }
    return strpos(strtolower($haystack), strtolower($needle));
}

function word_after($needle){
    global $tweet;
    $s = strpos($tweet, $needle . ' ') + strlen($needle) + 1;
    $se = strpos($tweet, ' ', $s);
    return substr($tweet, $s, $se - $s);
}

function contains_number($str){
    $has_number = false;
    for($i = 0; $i < 10; $i++){
        if(contains($i, $str)){
            $has_number = true;
            break;
        }
    }

    return $has_number;
}



if(contains('lifted') || contains('allowed')){
    if(contains('from') && (contains_number(word_after('from')) || contains('noon', word_after('from')))){
        $start_time = word_after('from');
        // $start_date = strtotime('today ' . $start_time);    
    }else if(contains('starting at')){
        $start_time = word_after('starting at');
        // $start_date = strtotime('today ' . $start_time);    
    }else if(contains('pm', word_after('allowed')) || contains('am', word_after('allowed'))){
        $start_time = word_after('allowed');
    }else{
        $start_time = date('ga');
        $start_date = time();
    }

    $start_day = str_replace('.','', word_after($start_time));

    if($start_day == 'on'){
        $start_day = str_replace('.','', word_after($start_time . ' on'));
    }

    if(in_array(strtolower($start_day), ['tomorrow', 'tmrw', 'tom', 'tmrw.', 'tom.'])){
        $start_date = strtotime('tomorrow ' . $start_time);
    }else if(in_array(strtolower($start_day), ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sun', 'mon', 'tues', 'wed', 'thur', 'fri', 'sat'])){
        $start_date = strtotime($start_day . ' ' . $start_time);
    }else {
        $start_day = 'today';
        $start_date = strtotime('today ' . $start_time);
    }

    if(contains('until') && (where('until') > where('allowed') && where('until') > where('lifted'))){ 
        $end_time = word_after('until');
    }else if(contains('thru')){
        $end_time = word_after('thru');
    }else if(contains('through')){
        $end_time = word_after('through');
    }else if(contains(word_after($start_time) . ' to')){
        $end_time = word_after(' to');
    }else{
        $end_time = null;
    }

    if( !is_null($end_time) && (contains('am', $end_time) || contains('pm', $end_time))){
        $end_time = str_replace('.', '', $end_time);
        $end_day = str_replace('.','', word_after($end_time));

        if($end_day == 'on'){
            $end_day = str_replace(['.',','],'', word_after($end_time . ' on'));
        }

        if(in_array(strtolower($end_day), ['tomorrow', 'tmrw', 'tom', 'tmrw.', 'tom.'])){
            $end_date = strtotime('tomorrow ' . $end_time);
        }else if(in_array(strtolower($end_day), ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sun', 'mon', 'tues', 'wed', 'thur', 'fri', 'sat'])){
            $end_date = strtotime($end_day . ' ' . $end_time);
        }else {
            $end_date = strtotime('today ' . $end_time);
        }
    }else if(contains('further', $end_time)){
        $end_date = 9999999999;
    }else{ 
        $orig_end_time = $end_time;
        $end_time = str_replace(['.', ','], '', $end_time);
        if(in_array(strtolower($end_time), ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sun', 'mon', 'tues', 'wed', 'thur', 'fri', 'sat'])){
            $end_day = $end_time;
            $end_month = word_after($orig_end_time);

            if(in_array(strtolower($end_month), ['january', 'jan', 'february', 'feb', 'march', 'april', 'may', 'june', 'july', 'august', 'aug', 'september', 'sept', 'october', 'oct', 'november', 'nov', 'december', 'dec'])){
                $end_dom = word_after($end_month);
                if(is_numeric(intval($end_dom))){ //is a date
                    if(word_after($end_dom) == 'at'){
                        $end_time = word_after(word_after($end_dom));
                    }else{ 
                        $end_dom = '';
                        $end_time = '00:00:00';
                    }
                }else{ 
                    $end_dom = '';
                    $end_time = '00:00:00';
                }
            }else{
                $end_time = '00:00:00';
            }

            $end_date = strtotime($end_day . ' ' .$end_time);
        }else{
            $end_date = 9999999999; //i know...
        }
    }
}else if(contains('back into effect') || contains('back in effect')){
    $start_time = date('ga');
    $start_date = time();

    $after_effect = word_after('effect');
    if($after_effect === 'at'){
        $end_time = word_after('effect at');
    }else{
        $end_time = word_after('effect ' . $after_effect . ' at');
        $end_day = $after_effect;
    }
    $end_time = str_replace('.','', $end_time);
    
    if(!isset($end_day)){
        $end_day = str_replace('.','', word_after($end_time));

        if($end_day == 'on'){
            $end_day = str_replace('.','', word_after($end_time . ' on'));
        }
    }

    if(in_array(strtolower($end_day), ['tomorrow', 'tmrw', 'tom', 'tmrw.', 'tom.'])){
        $end_date = strtotime('tomorrow ' . $end_time);
    }else if(in_array(strtolower($end_day), ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sun', 'mon', 'tues', 'wed', 'thur', 'fri', 'sat'])){
        $end_date = strtotime($end_day . ' ' . $end_time);
    }else {
        $end_date = strtotime('today ' . $end_time);
    }
}

echo '<pre>';
echo 'Start: ' . $start_time . "\n";
echo 'Start Day: ' . $start_day . "\n";
echo 'End: ' . $end_time . "\n";
echo 'End Day: ' . $end_day . "\n";
echo 'Start Date:' . $start_date . "\n";
echo 'End Date:' . $end_date . "\n";
echo '</pre>';

if(isset($start_date) && !is_null($start_date) && $start_date > 0 && isset($end_date) && !is_null($end_date) && $end_date > 0){
    $output = ["start" => $start_date, "end" => $end_date];
    saveFile($output);
}
