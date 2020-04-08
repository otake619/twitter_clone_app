<?php
  //ログインしているかチェック
  require('login_check.php');
  //データベースに接続する
  require('dbconnect.php');
  $obj = new dbconnect();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <title>タイムライン</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.css"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome-animation/0.0.10/font-awesome-animation.css" type="text/css" media="all" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome-animation/0.0.10/font-awesome-animation.css" type="text/css" media="all" />
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+1p:wght@300&display=swap" rel="stylesheet">
  </head>
  <body>
    <section id="header">
      <header class="fixed-top">
      <div class="container-fluid">
        <div class="row">
          <div class="col-3 bg-primary text-white">
            <p><?php echo 'ユーザー名：'.htmlspecialchars($_SESSION['name'], ENT_QUOTES, "UTF-8"); ?></p><br>
            <p><?php echo 'メールアドレス：'.htmlspecialchars($_SESSION['email'], ENT_QUOTES, "UTF-8"); ?></p>
          </div>
          <div class="col-6">
            <!-- レイアウト調整の余白 -->
          </div>
          <div class="col-3 bg-primary text-white">
            <a class="text-white" href="follow_and_follower_list.php">フォロー/フォロワーリスト</a>
            <br>
            <p><?php echo '<a class="text-white" href="logout.php">ログアウトはこちら</a>'; ?></p>
          </div>
        </div>
      </div>
    </header>
    </section>
    <section id="timeline">
      <div class="container-fluid">
        <div class="row">
          <div class="col-3 bg-primary">
            <!-- レイアウト調整の余白 -->
          </div>
          <div class="col-6">
          <?php
            echo '<h2>【タイムライン】</h2>';
            //ログインしているアカウントのid
            $this_id = $_SESSION['user_id'];
            //ログインしているアカウントのname
            $this_name = $_SESSION['name'];
            //tweetを全件取得
            $sql = "SELECT * FROM tweets";
            $get_tweet = $obj->select($sql);
            foreach ($get_tweet as $item) {
              echo '<br>';
              echo '<p>【投稿】</p>';
              echo '<p>'.htmlspecialchars(mb_substr($item['tweet'],0,40), ENT_QUOTES, "UTF-8").'</p>';
              //自分のtweetの場合は削除および編集を可能とする
              if($item['user_id'] == $_SESSION['user_id']){
                echo edit_tweet($item['id']);
                echo delete_tweet($item['id']);
              }
              echo follow($item['user_id']);
              echo '<p>|</p>';
              if($item['user_id'] != $_SESSION['user_id']){
                echo response_tweet($item['id']);
              }
              echo '<p>【投稿者】'.display_name($item['user_id']).'</p>';
              echo '<p>【投稿日時】'.$item['created_at'].'</p>';
              echo '<br>';
              //リプライがあるか判定して、あれば表示
              echo htmlspecialchars(reply_check($item['id']), ENT_QUOTES, "UTF-8");
            }
          ?>
          <?php
          function follow($target_id){
          $this_id = $_SESSION['user_id'];
         //フォローしていれば、フォロー解除を返してそれ以外はフォローを返す
         //$target_idはフォローしたい投稿者のid
         //フォローしているかのチェック
         $obj = new dbconnect();
         $sql = "SELECT * FROM followers WHERE following_id = :following_id AND user_id = :user_id";
         $follow_check = $obj->check_exec($sql,$target_id,$_SESSION['user_id']);
         if($target_id == $this_id){
           //自分の投稿だった場合何も表示しない
         }else{

          if($follow_check != 1){
            //フォローしていた場合は$follow_checkに1が入っている。
             return '<a href="follow_do.php?id='.$target_id.'">フォロー</a>';
          }else if($follow_check == 1){
            return '<a href="unfollow_do.php?id='.$target_id.'">フォロー解除</a>';
          }
        }
       }
       function display_name($post_id){
         $obj = new dbconnect();
         //投稿者のnameを取得
          $sql = "SELECT * FROM users WHERE id = :id";
          $exec_sql = $obj->plural($sql,$post_id);
          foreach ($exec_sql as $item) {
            return $item['name'];
          }
        }

      //編集と投稿を同一アカウントにだけ表示
       function edit_tweet($tweet_id){
         echo '<a href="edit.php?id='.$tweet_id.'">編集</a>';
         echo '|';
       }

       function delete_tweet($tweet_id){
         echo '<a href="delete_tweet.php?id='.$tweet_id.'">削除</a>';
       }

       function response_tweet($tweet_id){
         echo '<a href="response.php?tweet_id='.$tweet_id.'">返信</a>';
       }

       //tweetに対してリプライがあれば表示する
       function reply_check($tweet_id){
         $obj = new dbconnect();
         //$tweet_idは現在表示されているtweetのid
         //このidとsqlのreplied_tweet_idを照合して合致すればreply_messageを表示
         $sql = "SELECT * FROM reply WHERE replied_tweet_id = :replied_tweet_id";
         $exec_sql = $obj->get_reply_message($sql,$tweet_id);
         foreach ($exec_sql as $item) {
           echo '<p>・</p>';
           echo '<p>【リプライ】</p>';
           echo '<p>'.$item['reply_message'].'</p>';
           echo '<p>【返信者】</p>';
           echo '<p>'.get_name($item['reply_user']).'</p>';
           if($_SESSION['user_id'] == $item['reply_user']){
             $delete_reply_id = $item['id'];
             echo '<a href="delete_reply.php?delete_reply_id='.$delete_reply_id.'">【削除】</a>';
           }
           echo '<br>';
         }
       }

       function get_name($convert_id){
         $obj = new dbconnect();
         $sql = "SELECT * FROM users WHERE id = :id";
         $exec_sql = $obj->plural($sql,$convert_id);
         foreach($exec_sql as $item){
           return $item['name'];
         }
       }

      ?>
      </div>
      <div class="col-3 bg-primary">
          <!-- レイアウト調整の余白 -->
      </div>
     </div>
    </div>
    </section>
    <section id="footer">
      <div class="container-fluid">
        <div class="row">
          <div class="col-3 bg-primary">
            <!-- レイアウト調整の余白 -->
          </div>
          <div class="col-6">
            <a href="index.php"><i class="fas fa-home fa-5x faa-float animated home"></i></a>
            <a href="input_search.php"><i class="fas fa-search fa-5x faa-float animated search"></i></a>
            <a href="notice_check.php"><i class="far fa-bell fa-5x faa-float animated notice"></i></a>
            <a href="input_tweet.php"><i class="fas fa-pen fa-5x faa-float animated post"></i></a>
          </div>
          <div class="col-3 bg-primary">
            <!-- レイアウト調整の余白 -->
          </div>
        </div>
      </div>
    </section>
    <script type="text/javascript" src="js/jquery-3.4.1.js"></script>
    <script type="text/javascript" src="js/bootstrap.bundle.js"></script>
  </body>
</html>
