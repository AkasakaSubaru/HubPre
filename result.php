<?php
$start = $_GET['start'];
$time_array = array();
if($start != "開始"){
    $hour_array = $_GET['hour_array'];
    $min_array = $_GET['min_array'];
    $sec_array = $_GET['sec_array'];
    $cell =  $_GET['cell'];
    $content_array = $_GET['content_array'];
    $color_array = $_GET['color_array'];
    $hour_array_view = $hour_array;
    $min_array_view = $min_array;
    $sec_array_view = $sec_array;
    foreach($hour_array as &$value){
        $value *= 3600;
    }
    foreach($min_array as &$value){
        $value *= 60;
    }
    for($i=0;$i<count($sec_array);$i++){
        $time = (int)$hour_array[$i] + (int)$min_array[$i] + (int)$sec_array[$i];
        if($time == 0 | empty($content_array[$i])){
            echo "値が入力されていません。";
            echo "<br><a href='setting.php?miss=true&cell=".$cell."'>戻る</a>";
            exit();
        }
        array_push($time_array, $time);
    }
    if(count($time_array) != count($content_array)){
        echo "時間とセルの数が合いません。";
    }
}else{
    $time_array = $_GET['time_array_js'];
    $content_array = $_GET['content_array_js'];
    $color_array = $_GET['color_array_js'];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>開始</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
   </head>
    <body>
        <?php if($start != "開始"){ ?>
        <h1>開始ボタンを押すと始まります</h1>
        <table align="center" class="mb-2">
            <tr>
                <th class="pr-5">アウトライン名</th><th>時間</th>
            </tr>
            <?php for($i=0;$i<count($sec_array_view);$i++){
                $view_outline = $content_array[$i];
                $view_time = $hour_array_view[$i]."時間".$min_array_view[$i]."分".$sec_array_view[$i]."秒";
            ?>
            <tr>
                <td><?php echo $view_outline ?></td><td><?php echo $view_time ?></td>
            </tr>
            <?php } ?>
        </table>
        <form method="get" action="">
            <div style="display: none">
                <input type="text" name="user" value="<?php echo $user ?>">
                <?php
                foreach($time_array as $value){
                ?>
                <input type="hidden" name="time_array_js[]" value="<?php echo $value ?>">
                <?php
                }
                ?>
                <?php
                foreach($content_array as $value){
                ?>
                <input type="hidden" name="content_array_js[]" value="<?php echo $value ?>">
                <?php
                }
                ?>
                <?php
                foreach($color_array as $value){
                ?>
                <input type="hidden" name="color_array_js[]" value="<?php echo $value ?>">
                <?php
                }
                ?>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-6">
                        <a class="btn btn-primary btn-info btn-lg btn-block" href="setDB.php" role="button">戻る</a>
                    </div>
                    <div class="col-6">
                        <input type="submit" name="start" value="開始" class="btn btn-warning btn-lg btn-block">
                    </div>
                </div>
            </div>
        </form>
        <?php }?>
        <?php if($start == "開始"){
            $json_time = json_encode($time_array);
            $json_content = json_encode($content_array);
            $json_color = json_encode($color_array);
        ?>
        <div class="counter">
            <h1 id="content" class="content mt-2"></h1>
            <h3 id="count" class="count"></h3>
            <div class="button_list">
                <div class="next_class">
                    <input type="button" id="button_next" class="next btn-primary" value="次">
                </div>
                <div class="back_class">
                    <input type="button" id="button_back" class="back btn-primary" value="前">
                </div>
            </div>
            <div class="button_list">
                <div class="next_class">
                    <h4 id="next" class="next ml-2"></h4>
                </div>
                <div class="back_class">
                    <h4 id="back" class="back"></h4>
                </div>
            </div>
            <div class="btn-group btn-group-toggle mb-2 mt-2" data-toggle="buttons">
                <label class="btn btn-secondary active" id="label_play">
                    <input type="radio" name="stop" id="play" checked>再生
                </label>
                <label class="btn btn-secondary" id="label_stop">
                    <input type="radio" name="stop" id="stop">停止
                </label>
            </div>
            <div>
                <input class="btn btn-danger" type="button" id="audio" value="音の有無">
                <p id="audio_text" class="audio_off">現在音は鳴りません</p>
            </div>
        </div>
        <script type="text/javascript">
        var h = 0;
        var s = 0;
        var m = 0;
        var nowTime = 0;
        var index = 0;
        var first = true;
        var time_array = <?php echo $json_time; ?>;
        var content_array = <?php echo $json_content; ?>;
        var color_array = <?php echo $json_color; ?>;
        var elem = document.getElementById("content");
        var elem2 = document.getElementById("count");
        var elem_next = document.getElementById("next");
        var elem_back = document.getElementById("back");
        var elem_audio_text = document.getElementById("audio_text");
        var elem_play = document.getElementById("play");
        var elem_stop = document.getElementById("stop");
        var isAudio = false;
        var audio = new Audio();
        var genTime = 0;
        var stop = false;
        var v_time = "";
        var difTime = 0;
        function timeShow() {   //1秒間繰り返す
            var now = new Date();
            document.getElementById("button_next").onclick = function(){        //次のセッションへ進む処理
                index++;        //次のセッションへ
                if(Number.isNaN(Number(time_array[index]))){        //もしも次のセッションがNaNなら
                    index--;        //戻す
                }else{
                    genTime = 0;
                }
            };
            document.getElementById("button_back").onclick = function(){        //前のセッションへ戻る処理
                index--;        //前のセッション
                if(Number.isNaN(Number(time_array[index]))){        //もしも前のセッションがNaNなら
                    index++;        //戻す
                }else{
                    genTime = 0;
                }
            };
            document.getElementById("audio").onclick = function(){      //音のボタンが押されたときの処理
                if(!isAudio){       //もしもisAudioがfalseであれば
                    audio.src = "decision1.mp3";        //audioのパス名指定
                    isAudio = true;
                    elem_audio_text.innerHTML = "<p id='audio_text' class='audio_on'>現在音がなる状態です</p>";         //idに対応するHTMLの表示変更
                }else{      //もしもisAudioがtrueであれば
                    isAudio = false;
                    elem_audio_text.innerHTML = "<p id='audio_text' class='audio_off'>現在音は鳴りません</p>";      //idに対応するHTMLの表示変更
                }
            };
            elem_play.onclick = function(){
                stop = false;
                $('#label_stop').removeClass('active');
                $('#label_play').addClass('active');
            };
            elem_stop.onclick = function(){
                stop = true;
                $('#label_play').removeClass('active');
                $('#label_stop').addClass('active');
            }
            var time = Number(time_array[index]);       //indexに対応する配列を代入
            if(!stop){
                h = now.getHours();
                m = now.getMinutes();
                s = now.getSeconds();
                h *= 3600;
                m *= 60;
                nowTime = s + m + h;
                if(difTime != 0){
                    nowTime -= difTime;
                }
            }else{
                if(difTime != 0){
                    difTime = 0;
                }
                h = now.getHours();
                m = now.getMinutes();
                s = now.getSeconds();
                h *= 3600;
                m *= 60;
                var backTime = h + s + m;
                difTime = backTime - nowTime;
            }
            if(genTime == 0){
                genTime = nowTime;
            }
            if(nowTime - genTime > time){
                genTime = 0;
                index++;
            }else{
                if(nowTime - genTime == 0){
                    var content = content_array[index];     //indexに対応する配列を代入
                    if(content == undefined){
                        content="なし";
                    }else if(content == undefined & isAudio){
                        var speak = new SpeechSynthesisUtterance();
                        speak.text = "なし";
                        speak.rate = 10;
                        speak.pitch = 10;
                        speechSynthesis.speak(speak);
                    }
                    if(isAudio & !stop){
                        audio.play();       //音なる
                        var speak = new SpeechSynthesisUtterance();
                        speak.text = content;
                        speak.rate = 10;
                        speak.pitch = 10;
                        speechSynthesis.speak(speak);
                    }
                    elem.innerHTML = "<h1 id='content' class='content mt-2' style='color: "+ color_array[index] +"'>"+content+"</h1>";
                    if(content_array[index+1] == undefined){
                        next = "なし";
                        var next_color = "#000000";
                    }else{
                        next = content_array[index + 1];
                        var next_color = color_array[index + 1];
                    }
                    if(content_array[index-1] == undefined){
                        back = "なし";
                        var back_color = "#000000";
                    }else{
                        back = content_array[index - 1];
                        var back_color = color_array[index-1];
                    }
                    elem_next.innerHTML = "<h4 id='next' class='next' style='color: "+ next_color +"'>"+next+"</h4>";
                    elem_back.innerHTML = "<h4 id='back' class='back' style='color: "+ back_color +"'>"+back+"</h4>";
                }
                if(!stop){
                var restTime = time + genTime - nowTime;
                if(restTime >= 3600){
                    var int = Math.floor(restTime / 3600);
                    var v_h = int;
                    var num = restTime - int * 3600;
                    int = Math.floor(num / 60);
                    var v_m = int;
                    var v_s = num - int * 60;
                    v_time = v_h + "時間" + v_m + "分" + v_s + "秒";
                }else if(restTime >= 60 | restTime < 3600){
                    var int = Math.floor(restTime / 60);
                    var v_m = int;
                    var v_s = restTime - int * 60;
                    v_time = v_m + "分" + v_s + "秒";
                }else{
                    v_time = restTime + "秒";
                }
                }
                elem2.innerHTML = "<h3 id='count'>残り：" + v_time + "</h3>";
            }
            if(Number.isNaN(Number(time_array[index]))){
                console.log("終了");
                clearInterval(inter);
                elem2.innerHTML = "<h3 id='count'><a href='index.html'>戻る</a></h3>";
                elem.innerHTML = "<h1 id='content' class='content mt-2'>終了</h1>";
            }
        }
        var inter = setInterval('timeShow()',1000);
        </script>
        <h2 style="text-align: center;">全体のスケジュール</h2>
        <div class="chart" style="position: relative; width: 80vw; height: 50vh; margin: 0 auto;">
            <canvas id="myPieChart"></canvas>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
        <script>
        var ctx = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
            labels: [
                <?php foreach($content_array as $value){ ?>
                    "<?php echo $value ?>"
                <?php
                if(count($content_array) != array_search($value,$content_array)+1){
                    echo ",";
                }
            } ?>
            ],
            datasets: [{
                backgroundColor: [
                <?php foreach($color_array as $value){ ?>
                    "<?php echo $value ?>"
                <?php
                if(count($color_array) != array_search($value,$color_array)+1){
                    echo ",";
                }
            } ?>
                ],
                data: [
                <?php foreach($time_array as $value){ ?>
                    "<?php echo $value ?>"
                <?php
                if(count($time_array) != array_search($value,$time_array)+1){
                    echo ",";
                }
            } ?>
                ]
            }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            title: {
                display: true
            }
            }
        });
        </script>
        <?php } ?>
    </body>
</html>