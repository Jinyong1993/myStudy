<html>
<head>    
    <title>パスワードを探す</title>
    <?php $this->load->view('header.php'); ?>
</head>
<body>
<?php if($this->session->flashdata('time_over')) : ?>
    <div class="alert alert-danger print-error-msg">
        <?php 
        echo $this->session->flashdata('time_over');
        ?>
    </div>
<?php endif ?>
<?php if($this->session->flashdata('error')) : ?>
    <div class="alert alert-danger print-error-msg">
        <?php 
        echo $this->session->flashdata('error');
        ?>
    </div>
<?php endif ?>
<?php if($this->session->flashdata('email_sent_success')) : ?>
    <div class="alert alert-success">
        <?php 
        echo $this->session->flashdata('email_sent_success');
        ?>
    </div>
<?php endif ?>

<?php
echo $this->session->flashdata('email_sent');
echo form_open('https://localhost:10443/sample/index.php/SendingMail/send_mail');
?>
<table border="3">
    <thead>
        <tr>
            <th colspan="2">パスワードを探す</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>アカウント名</td>
            <td>
                <input type="text" name="account" placeholder="アカウント"/>
            </td>
        </tr>
        <tr>
            <td>メールアドレス</td>
            <td>
                <input type="email" name="email" placeholder="me-ru@example.co.jp"/>
            </td>
        </tr>
         <tr>
            <td colspan="2">
                <input class="btn btn-success" type="submit" value="メール送信">
                <a href="https://localhost:10443/sample/index.php/Login/index">ログインページへ</a>
            </td>
        </tr>
    </tbody>
</table>
<?php
echo form_close();
?>
</body>
</html>