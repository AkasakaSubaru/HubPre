<!DOCTYPE html>
<html>
    <head>
        <title>プレゼンの設定｜プレクロ</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <h1>プレゼンテーションの設定をします</h1>
        <h3>以下のフォームを入力してください</h3>
        <form method="get" action="setting.php">
            <div class="formgroup row">
                <label for="session" class="col-sm-2 col-form-label">アウトライン＆日程数</label>
                <div class="col-sm-7">
                    <input type="number" name="cell" min="2" max="100" value="2" class="form-control mb-2" placeholder="アウトライン数" id="session">
                </div>
            </div>
            <input type="submit" value="決定" class="btn btn-primary btn-lg btn-block">
        </form>
        <p>
            ※<b>アウトライン</b>とは、プレゼンでいう「目次」を指します。例えば、「挨拶」→「アプリの紹介」→「終わり」ならば、アウトライン数は「3」になります。
        </p>
    </body>
</html>