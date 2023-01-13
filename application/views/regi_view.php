<!doctype html>
<html>
 <head>
  <meta charset = "utf-8">
  <title>会員登録ページ</title>

  <?php $this->load->view('header.php'); ?>

 </head>
 <body>
    <?php if(isset($is_error)) : ?>
    <div class="alert alert-danger print-error-msg">
    <?php echo validation_errors(); ?>    
    </div>
    <?php endif ?>
     <form method="POST" action="https://localhost:10443/sample/index.php/Login/regis">
        <input type="hidden" name="submit" value="1"/>
     <table class="table">
         <thead>
            <tr>
                <th>会員登録</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>アカウント</td>
                <td><input class="form-control" name="account" type="text" placeholder="IDを入力してください" value="<?php echo isset($account) ? $account : "" ?>"></td>
            </tr>
            <tr>
                <td>パスワード</td>
                <td><input class="form-control" name="password" type="password" placeholder="パスワードを入力してください"></td>
            </tr>
            <tr>
                <td>パスワード確認</td>
                <td><input class="form-control" name="confirm_password" type="password" placeholder="パスワードをもう一度入力してください"></td>
            </tr>
            <tr>
                <td>名前</td>
                <td><input class="form-control" name="name" type="text" placeholder="名前を入力してください" value="<?php echo isset($name) ? $name : "" ?>"></td>
            </tr>
            <tr>
                <td>年齢</td>
                <td><input class="form-control" name="age" type="text" placeholder="年齢を入力してください" value="<?php echo isset($age) ? $age : "" ?>"></td>
            </tr>
            <tr>
                <td>性別</td>
                <td>
                    <label><input class="" name="gender" type="radio" value="男" <?php echo isset($gender) && $gender == "男" ? "checked" : "" ?>>男</label>
                    <label><input class="" name="gender" type="radio" value="女" <?php echo isset($gender) && $gender == "女" ? "checked" : "" ?>>女</label>
                </td>
            </tr>
            <tr>
                <td>メール</td>
                <td><input class="form-control" name="email" type="email" placeholder="メールを入力してください" value="<?php echo isset($email) ? $email : "" ?>"></td>
            </tr>
            <tr>
                <td>メール確認</td>
                <td><input class="form-control" name="email1" type="email" placeholder="メールをもう一度入力してください"></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">
                    <input class="btn btn-success btn-submit" type="submit" value="登録"/>
                    <a class="btn btn-default" href="https://localhost:10443/sample/index.php/Login/index">ログインページへ</a>
                </td>
            </tr>
        </tfoot>
    </table>
    </form>
</body>
</html>