<!DOCTYPE html>
<html lang="en">
<head>
    <title>パスワード変更</title>
    <?php $this->load->view('header.php'); ?>
</head>
<body>
<?php if($this->session->flashdata('pass_validate_fail')) : ?>
    <div class="alert alert-danger print-error-msg">
        <?php 
        echo $this->session->flashdata('pass_validate_fail');
        ?>
    </div>
<?php endif ?>
    <form method="post" action="https://localhost:10443/sample/index.php/SendingMail/pass_validate">
    <table border="3">
        <thead>
            <tr>
                <th colspan="2"><?php echo $limit.'まで有効です。' ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>確認用コード</td>
                <td><input type="text" name="code"/></td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" id="code" class="btn btn-success" value="入力">
                    <a href="https://localhost:10443/sample/index.php/Login/index">ログインページへ</a>
                </td>
            </tr>
        </tbody>
    </table>
    </form>
</body>
</html>