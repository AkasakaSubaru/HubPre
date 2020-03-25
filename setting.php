<?php
$cell = $_GET['cell'];
if($cell == 0 & !empty($_GET['miss'])){
    echo "項目の入力にミスがありました。再入力してください";
    echo "<br><a href='count.php'>戻る</a>";
}else{
?>
<!DOCTYPE html>
<html>
    <head>
        <title>セッション設定</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <h1 class="display-5">それぞれの項目を入力してください</h1>
        <form method="get" action="result.php">
            <?php
            for($i = 1;$i <= $cell;$i++){
            ?>
            <h3>アウトライン<?php echo $i; ?></h3>
            <div class="form-group row">
                <label for="content" class="col-sm-2 col-form-label">アウトラインの内容</label>
                <div class="col-sm-10">
                    <input type="text" name="content_array[]" maxlength="15" class="form-control" id="content" placeholder="アウトラインの内容">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="hour">時間（hour）</label>
                    <input  type="number" name="hour_array[]" value="0" min="0" max="23" class="form-control" id="hour">
                </div>
                <div class="form-group col-md-4">
                    <label for="minute">分（minute）</label>
                    <input  type="number" name="min_array[]" value="0" min="0" max="59" class="form-control" id="minute">
                </div>
                <div class="form-group col-md-4">
                    <label for="seconds">秒（seconds）</label>
                    <input  type="number" name="sec_array[]" min="0" max="59" class="form-control" id="seconds" placeholder="せめてここは数入れてー">
                </div>
            </div>
            <div class="form-group row">
                <label for="content" class="col-sm-4 col-form-label">色</label>
                <div class="col-sm-4">
                    <input type="color" name="color_array[]" value="#<?php echo rand(333333,999999) ?>" class="form-control">
                </div>
            </div>
            <?php
            }
            ?>
            <div style="display: none">
                <input type="text" name="cell" value="<?php echo $cell ?>">
            </div>
            <input type="submit" class="btn btn-primary btn-lg btn-block mb-3" value="決定">
        </form>
    </body>
</html>
<?php
}
?>