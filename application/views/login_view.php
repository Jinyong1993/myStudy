<!doctype html>
<html>
 <head>
  <meta charset = "utf-8">
  <title>ログインページ</title>
 </head>
 <body>
     <form method="POST" action="https://localhost:10443/sample/index.php/Login/login_proc">
     <table border="2">
         <thead>
            <tr>
                <th>ID</th>
                <th>パスワード</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input name="account" type="text" placeholder="IDを入力してください"></td>
                <td><input name="password" type="password" placeholder="パスワードを入力してください"></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">
                    <input type="submit" value="次へ">
                    <a href="https://localhost:10443/sample/index.php/Login/regis">会員登録</a>
                    <a href="https://localhost:10443/sample/index.php/SendingMail/index">パスワードを忘れた時</a>
                </td>
            </tr>
        </tfoot>
    </table>
    </form>
</body>
</html>