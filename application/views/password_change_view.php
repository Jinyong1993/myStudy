<!DOCTYPE html>
<html lang="en">
<head>
    <title>パスワード変更</title>
    <?php $this->load->view('header.php'); ?>
</head>
<body>
<?php if($this->session->userdata('password_change_validate')) : ?>
    <div class="alert alert-danger print-error-msg">
        <?php 
        echo $this->session->userdata('password_change_validate');
        $this->session->unset_userdata('password_change_validate');
        ?>
    </div>
<?php endif ?>
    <form method="post" action="https://localhost:10443/sample/index.php/SendingMail/pass_change">
    <table border="3">
        <thead>
            <tr>
                <th colspan="2">パスワード変更</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>パスワード</td>
                <td><input type="password" name="password" placeholder="変更するパスワードを入力して下さい。"/></td>
            </tr>
            <tr>
                <td>パスワード確認</td>
                <td><input type="password" name="password_confirm" placeholder="再入力して下さい。"/></td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" class="btn btn-success" value="入力">
                    <a href="https://localhost:10443/sample/index.php/Login/index">ログインページへ</a>
                </td>
            </tr>
        </tbody>
    </table>
    </form>
</body>
</html>