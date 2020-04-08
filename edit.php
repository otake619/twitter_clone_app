<?php
  require('login_check.php');
 ?>
<?php
  $edit_id = $_GET['id'];
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.css"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome-animation/0.0.10/font-awesome-animation.css" type="text/css" media="all" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome-animation/0.0.10/font-awesome-animation.css" type="text/css" media="all" />
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+1p:wght@300&display=swap" rel="stylesheet">
    <title>編集画面</title>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <div class="col-3 bg-primary text-white">
          <!-- レイアウト調整の余白 -->
          <div style="height: 1000px;">
            <!-- レイアウト用の余白 -->
          </div>
        </div>
        <div class="col-6">
          <div class="text-center">
            <h2>変更内容を入力してください</h2>
          </div>
          <form action="edit_do.php" method="post">
            <div class="mb-3">
              <label for="validationTextarea">テキストエリア</label>
              <textarea class="form-control" id="validationTextarea" rows="8" placeholder="返信を入力してください" name="edit_tweet" required></textarea>
              <div class="invalid-feedback">
                テキストエリアに変更内容を入力してください。
              </div>
            </div>
            <div class="form-group row">
              <div class="offset-sm-2 col-sm-10">
                <button type="submit" class="btn btn-primary">変更</button>
              </div>
            </div>
            <input type="hidden" name="id" value=
            "<?php echo htmlspecialchars($edit_id, ENT_QUOTES, "UTF-8"); ?>">
            <div class="text-center">
              <a href="index.php">Homeに戻る</a>
            </div>
          </form>
        </div>
        <div class="col-3 bg-primary text-white">
          <!-- レイアウト調整の余白 -->
        </div>
      </div>
    </div>
    <script type="text/javascript" src="js/jquery-3.4.1.js"></script>
    <script type="text/javascript" src="js/bootstrap.bundle.js"></script>
  </body>
</html>
