<!doctype html>
<html>
 <head>
  <meta charset = "utf-8">
  <title>会員登録ページ</title>
  <?php $this->load->view('header.php'); ?>
 </head>
 <body>
     <form method="POST" action="https://localhost:10443/sample/index.php/Login/regis_proc">
     <table class="table">
         <thead>
            <tr>
                <th>会員登録</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>アカウント</td>
                <td><input class="form-control" name="account" type="text" placeholder="IDを入力してください"></td>
            </tr>
            <tr>
                <td>パスワード</td>
                <td><input class="form-control" name="password" type="password" placeholder="パスワードを入力してください"></td>
            </tr>
            <tr>
                <td>パスワード確認</td>
                <td><input class="form-control" name="password1" type="password" placeholder="パスワードをもう一度入力してください"></td>
            </tr>
            <tr>
                <td>名前</td>
                <td><input class="form-control" name="name" type="text" placeholder="名前を入力してください"></td>
            </tr>
            <tr>
                <td>年齢</td>
                <td><input class="form-control" name="age" type="number" placeholder="年齢を入力してください"></td>
            </tr>
            <tr>
                <td>性別</td>
                <td>
                    <label><input class="" name="gender" type="radio" value="男">男</label>
                    <label><input class="" name="gender" type="radio" value="女">女</label>
                </td>
            </tr>
            <tr>
                <td>メール</td>
                <td><input class="form-control" name="email" type="email" placeholder="メールを入力してください"></td>
            </tr>
            <tr>
                <td>メール確認</td>
                <td><input class="form-control" name="email1" type="email" placeholder="メールをもう一度入力してください"></td>
            </tr>
            <tr>
                <td><input type="submit" value="登録"/></td>
            </tr>
        </tbody>
        <tfoot>

        </tfoot>
    </table>
    </form>
</body>
</html>