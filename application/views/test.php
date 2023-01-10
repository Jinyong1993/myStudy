<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-2.2.4.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.min.js"></script>
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<script type="text/javascript" src="https://localhost:10443/sample/assets/js/bootstrap-colorpicker.min.js"></script>
<link rel="stylesheet" href="https://localhost:10443/sample/assets/css/bootstrap-colorpicker.min.css">

<script>
    $(function(){
        var days = [];

        $("#output").click(function (){
            $(':checkbox[name="check_test"]:checked').each(function () {
                days.push($(this).val());
                $("#output_area").text(days);
                console.log($(this).parent());
            });
        });

        $("#del").click(function (){
           $(':checkbox[name="check_test"]:checked').parent("td").children("textarea").empty();
        });

        $("#input").click(function (){
            var input_text = $("#input_text").val();
            $(':checkbox[name="check_test"]:checked').parent("td").children("textarea").val(input_text);
        });

        $('.color_change').change(function (){
            $(this).parent("td").css({
                "background-color":$(this).val()
            })
        });

        $("#check_all").click(function (){
            if($(this).is(":checked")) {
                $("input[name=check_test]").prop("checked", true);
            } else {
                $("input[name=check_test]").prop("checked", false);
            } 
            console.log($("input[name=check_test]"));
        });

        $("#color_change_all").change(function (){
            $("input[name=check_test]:checked").parent("td").css({
                "background-color":$(this).val()
            })
            
            $("input[name=check_test]:checked").parent("td")
            .children(".color_change").val($(this).val());
        })

        $(".set_color").click(function (){
            $("#year_ajax").val();
            $("#month_ajax").val();
            $(this).parent("td").children(":checkbox[name=check_test]").val();
            $(this).parent("td").children("textarea").val();
            $(this).parent("td").children(".color_change").val();

            var ajax_data = {
                year:$("#year_ajax").val(),
                month:$("#month_ajax").val(),
                day:$(this).parent("td").children(":checkbox[name=check_test]").val(),
                text:$(this).parent("td").children("textarea").val(),
                color:$(this).parent("td").children(".color_change").val()
            }
            $.ajax({
                url: "calendar_ajax_controller",
                type: "post",
                data: ajax_data,
            }).done(function(data) {
                alert(data);
            });
        });
    });
</script>

<style>
    td {
        border: 1px solid black;
    }

    .table-condensed{
        cursor:move;
    }
</style>
</head>

<body>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar">1</span>
        <span class="icon-bar">2</span>
        <span class="icon-bar">3</span>
      </button>
      <a class="navbar-brand" href="https://localhost:10443/sample/index.php/Hello/calendar">カレンダー</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Link <span class="sr-only"></span></a></li>
        <li><a href="#">Link</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Separated link</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">One more separated link</a></li>
          </ul>
        </li>
      </ul>
      <form class="navbar-form navbar-left"method="GET" action="https://localhost:10443/sample/index.php/Hello/calendar">
        <?php if($month == 1) : ?>
        <a class="btn btn-default btn-xs" href="https://localhost:10443/sample/index.php/Hello/calendar?<?php echo "year=".($year-1) ?>&month=12" role="button">先月</a>
        <?php else : ?>
            <a class="btn btn-default btn-xs" href="https://localhost:10443/sample/index.php/Hello/calendar?<?php echo "year=".$year ?>&month=<?php echo $month-1?>">先月</a>
        <?php endif ?>
        <?php if($month == 12) : ?>
            <a class="btn btn-default btn-xs" href="https://localhost:10443/sample/index.php/Hello/calendar?<?php echo "year=".($year+1) ?>&month=1">来月</a>
        <?php else : ?>
            <a class="btn btn-default btn-xs" href="https://localhost:10443/sample/index.php/Hello/calendar?<?php echo "year=".$year ?>&month=<?php echo $month+1 ?>">来月</a>
        <?php endif ?>
        <div class="form-group">
          <input type="text" name="year" value="<?php echo $year ?>" class="form-control" placeholder="年">
          <input type="text" name="month" value="<?php echo $month ?>" class="form-control" placeholder="月">
        </div>
        <button type="submit" class="btn btn-default">検索</button>
      </form>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#">Link</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Separated link</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>


<!--
<form method="GET" action="https://localhost:10443/sample/index.php/Hello/calendar">
    <input type="text" name="year" value="<?php echo $year ?>">/<input type="text" name="month" value="<?php echo $month ?>">
    <button type="submit">search</button>
    <br>
    <?php if($month == 1) : ?>
        <a class="btn btn-default btn-xs" href="https://localhost:10443/sample/index.php/Hello/calendar?<?php echo "year=".($year-1) ?>&month=12" role="button">previous month</a>
    <?php else : ?>
        <a class="btn btn-default btn-xs" href="https://localhost:10443/sample/index.php/Hello/calendar?<?php echo "year=".$year ?>&month=<?php echo $month-1?>">previous month</a>
    <?php endif ?>
    <?php if($month == 12) : ?>
        <a class="btn btn-default btn-xs" href="https://localhost:10443/sample/index.php/Hello/calendar?<?php echo "year=".($year+1) ?>&month=1">next month</a>
    <?php else : ?>
        <a class="btn btn-default btn-xs" href="https://localhost:10443/sample/index.php/Hello/calendar?<?php echo "year=".$year ?>&month=<?php echo $month+1 ?>">next month</a>
    <?php endif ?>
</form>
-->


<form method="POST" id="ajax_form" action="https://localhost:10443/sample/index.php/Hello/insert">
    <input type="hidden" id="year_ajax" name="year" value="<?php echo $year ?>"><input type="hidden" id="month_ajax" name="month" value="<?php echo $month ?>">
<?php if(!$isError) : ?>


<!--
<table name="table">
    <tr>
        <td>날짜</td>
        <td>요일</td>
        <td>빈칸</td>
    </tr>
    <?php
    $sec = 86400;
    for($i=1; $i<$total_day + 1; $i++):
    ?>
    <tr>
        <td>
            <?= date("Y/m/d", $time); ?>
            <?php $day = (int) date('d', $time) ?>
        </td>
        <td>
            <?= date('D', $time) ?>
        </td>
        <td>
            <?php 
            if(isset($select[$day]))
            {
                $text = $select[$day];
            }
            else
            {
                $text = '';
            }
            ?>
            <input name="text_save[]" value="<?=$text?>"/>
        </td>
    </tr>
    <?php
    $time += $sec;
    endfor
    ?>
</table>
<?php endif ?> 
-->


<table class="table table-condensed">
    <?php
    $first = "$year/$month/1";
    $time_stamp = strtotime($first);
    // $last = strtotime(date("Y/m/t", $time_stamp));
    $total_day = date("t", $time_stamp);

    $start_day_week = (int) date("w", $time_stamp);
    $total_week = (int) ceil(($total_day + $start_day_week) / 7);
    ?>
    <thead>
        <tr>
            <th>日</th>
            <th>月</th>
            <th>火</th>
            <th>水</th>
            <th>木</th>
            <th>金</th>
            <th>土</th>
        </tr>
    </thead>

    <tbody>
    <?php
    $day = 1;

    for($i=0; $i<$total_week; $i++){
    ?>
        <tr>
    <?php
        for($j=0; $j<7; $j++){
    ?>
        <?php 
        $result = isset($select[$day]) ? $select[$day] : null;
        ?>
        <?php if (($day > 1 || $j >= $start_day_week) && ($total_day >= $day)) : ?>
            <td style="background:<?php echo isset($result->color) ? $result->color : "white" ?>">
            <?php echo $day ?>
            <input type="checkbox" class="chk" name="check_test" value="<?php echo $day ?>">
            <input class="color_change" name="color[]" type="color" value="<?php echo isset($result->color) ? $result->color : "#ffffff" ?>"/>
            &nbsp;<button class="set_color btn btn-primary btn-xs" type="button">適用</button>
            <br>
            <textarea id="" name="text_save[]"><?php echo isset($result->text) ? $result->text : '';?></textarea>
            <?php $day++ ?>
            </td>
            <?php else : ?>
            <td></td>
        <?php endif ?>
    <?php
        }
    }
    ?>


    <!--
    <p>選択 <span id="output_area"></span></p>
    <input type="button" id="output" value="outputボタン"/>
    -->


    </tbody>

    <tfoot>
        
    </tfoot>
</table>
<input type="checkbox" id="check_all"/>
<button class="btn btn-success" type="submit">保存</button>
<button class="btn btn-danger" id="del" type="button">削除</button>
<input type="text" id="input_text"/>&nbsp;
<input type="color" id="color_change_all" value="#ffffff"/>
<button class="btn btn-primary" id="input" type="button">テキスト入力</button>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@fat">入力</button>

<!-- modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">New message</h4>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Recipient:</label>
            <input type="text" class="form-control" id="recipient-name">
          </div>
          <div class="form-group">
            <label for="message-text" class="control-label">Message:</label>
            <textarea class="form-control" id="message-text"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Send message</button>
      </div>
    </div>
  </div>
</div>

<address>
  <strong>株式会社アイビーシステム</strong><br>
  新潟県新潟市北区すみれ野2-1-12<br>
  <abbr title="Phone">TEL</abbr> 025-257-3050<br>
  <abbr title="Fax">FAX</abbr> 025-257-3060
</address>

<address>
  <strong>ジョンジンヨン</strong><br>
  <a href="mailto:#">jeong-jin-young@ib-system.co.jp</a>
</address>
</form>
</body>
</html>
