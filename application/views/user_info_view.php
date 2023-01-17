<!doctype html>
<html>
 <head>
  <meta charset = "utf-8">
  <title>会員情報</title>

  <?php $this->load->view('header.php'); ?>

 </head>
 <body>
    <?php if($this->session->userdata('validate')) : ?>
        <div class="alert alert-danger print-error-msg">
        <?php 
        echo $this->session->userdata('validate'); 
        $this->session->unset_userdata('validate');
        ?>
        </div>
    <?php endif ?>
    <?php if($this->session->userdata('err_message')) : ?>
        <div class="alert alert-danger print-error-msg">
        <?php 
        echo $this->session->userdata('err_message'); 
        $this->session->unset_userdata('err_message');
        ?>
        </div>
    <?php endif ?>
    <?php if($this->session->userdata('success')) : ?>
        <div class="alert alert-success print-error-msg">
            処理しました。
        <?php 
        $this->session->unset_userdata('success');
        ?>
        </div>
    <?php endif ?>
     <form method="POST" action="https://localhost:10443/sample/index.php/Login/user_info_proc">
     <table class="table">
         <thead>
            <tr>
                <th class="glyphicon glyphicon-user">会員情報</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>アカウント</td>
                <td><input class="form-control" name="account" type="text" placeholder="IDを入力してください" value="<?php echo isset($result->account) ? $result->account : "" ?>" disabled></td>
            </tr>
            <tr>
                <td>パスワード</td>
                <td><input class="form-control" name="password" type="password" placeholder="現在パスワードを入力してください"></td>
            </tr>
            <tr>
                <td>パスワード変更</td>
                <td><input class="form-control" name="password_change" type="password" placeholder="新しいパスワードを入力してください"></td>
            </tr>
            <tr>
                <td>パスワード変更確認</td>
                <td><input class="form-control" name="password_change_confirm" type="password" placeholder="新しいパスワードをもう一度入力してください"></td>
            </tr>
            <tr>
                <td>名前</td>
                <td><input class="form-control" name="name" type="text" placeholder="名前を入力してください" value="<?php echo isset($result->name) ? $result->name : "" ?>"></td>
            </tr>
            <tr>
                <td>年齢</td>
                <td><input class="form-control" name="age" type="text" placeholder="年齢を入力してください" value="<?php echo isset($result->age) ? $result->age : "" ?>"></td>
            </tr>
            <tr>
                <td>性別</td>
                <td>
                    <label><input class="" name="gender" type="radio" value="男" <?php echo isset($result->gender) && $result->gender == "男" ? "checked" : "" ?>>男</label>
                    <label><input class="" name="gender" type="radio" value="女" <?php echo isset($result->gender) && $result->gender == "女" ? "checked" : "" ?>>女</label>
                </td>
            </tr>
            <tr>
                <td>メール</td>
                <td style="color:grey"><?php echo isset($result->email) ? $result->email : "" ?></td>
            </tr>
            <tr>
                <td>メール変更</td>
                <td><input class="form-control" name="email_change" type="email" placeholder="新しいメールを入力してください"></td>
            </tr>
            <tr>
                <td>メール変更確認</td>
                <td><input class="form-control" name="email_change_confirm" type="email" placeholder="新しいメールをもう一度入力してください"></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">
                    <input class="btn btn-success btn-submit" type="submit" value="修正"/>
                    <a class="btn btn-default" href="https://localhost:10443/sample/index.php/Hello/calendar">カレンダーへ</a>
                </td>
            </tr>
        </tfoot>
    </table>
    </form>
</body>
</html>