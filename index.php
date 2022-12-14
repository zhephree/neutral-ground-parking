<?php
date_default_timezone_set('America/Chicago');

$json = json_decode(file_get_contents('./dates.json'));
$now = time();
if($now >= $json->start && $now < $json->end){
    $answer = 'Yes';

    if($json->end === 9999999999){
        $end = 'Further Notice';
    }else{
        $end = date('g:ia \o\n F jS', $json->end);
    }

    $start = date('g:ia \o\n F jS', $json->start);
    
}else{
    $answer = 'No';
    $end = '';
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Can I Park on the Neutral Ground?</title>

    <style type="text/css">
        body, html {
            margin: 0;
            padding: 0;
            background: url(grass.png);
        }

        #waves {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            -webkit-filter: drop-shadow( 4px 4px 3px rgba(0, 0, 0, .7));
            filter: drop-shadow( 4px 4px 3px rgba(0, 0, 0, .7));
        }

        	#waves.no {
        		display: none;
        	}

        #center-text {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
            #answer {
                color: #fff;
                font-size: 125pt;
                font-family: sans-serif;
                font-weight: bold;
                text-align: center;
            }

            #end {
                color: #fff;
                font-size: 12pt;
                font-family: sans-serif;
                font-weight: bold;
                text-align: center;
            }
        

        #q {
            position: fixed;
            bottom: 10px;
            right: 10px;
            width: 45px;
            height: 45px;
            border: none;
            background-color: white;
            color: #0099ff;
            border-radius: 50%;
            font-size: 22px;
        }

        #modal {
            display: none;
            background-color: white;
            color: #000;
            font-family: sans-serif;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 100;
            padding: 25px;
        }

        #modal.show {
            display: block;
        }
            p.center {
                text-align: center;
            }

                #x {
                    border: 0;
                    background-color: #0099ff;
                    border-radius: 7px;
                    color: #fff;
                    font-size: 18pt;
                    padding: 7px 15px;;
                }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <div id="center-text">
            <div id="answer" <?=$answer == 'Yes'? 'title="As of ' . $start . '"': ''?>><?=$answer?></div>
            <div id="end" class="<?= $answer == 'No'? 'hidden': '';?>">Until <?=$end?></div>
        </div>
        <svg id="waves" class="<?=strtolower($answer)?>" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#0099ff" fill-opacity="1" d="M0,160L20,165.3C40,171,80,181,120,170.7C160,160,200,128,240,122.7C280,117,320,139,360,149.3C400,160,440,160,480,160C520,160,560,160,600,176C640,192,680,224,720,245.3C760,267,800,277,840,261.3C880,245,920,203,960,186.7C1000,171,1040,181,1080,192C1120,203,1160,213,1200,213.3C1240,213,1280,203,1320,197.3C1360,192,1400,192,1420,192L1440,192L1440,320L1420,320C1400,320,1360,320,1320,320C1280,320,1240,320,1200,320C1160,320,1120,320,1080,320C1040,320,1000,320,960,320C920,320,880,320,840,320C800,320,760,320,720,320C680,320,640,320,600,320C560,320,520,320,480,320C440,320,400,320,360,320C320,320,280,320,240,320C200,320,160,320,120,320C80,320,40,320,20,320L0,320Z"></path></svg>

        <button id="q">?</button>
    </div>

    <div id="modal">
        <p>This site was made by <a href="http://geoffreygauchet.com" target="_blank">@animatedGeoff</a>. It searches through the NOLAReady Twitter account every so often and looks for tweets about neutral ground parking and tries to determine if you can currently park on the neutral ground. No one asked for this.</p>
        <p>You can hover over the YES to see what time neutral ground parking was allowed from.php</p>
        <p>Information could be delayed up to 1 hour. Check <a href="https://twitter.com/NOLAReady" target="_blank">@NOLAReady</a> for information.</p>
        <p class="center"><button id="x">Close</button></p>
    </div>

    <script type="text/javascript">
        document.getElementById('q').addEventListener('click', function (e){
            e.preventDefault();

            document.getElementById('modal').classList.add('show');
        })

        document.getElementById('x').addEventListener('click', function (e){
            e.preventDefault();

            document.getElementById('modal').classList.remove('show');
        })
    </script>
</body>
</html>