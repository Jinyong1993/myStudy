<!DOCTYPE html>
<html>
<head>
<?php $this->load->view('header.php'); ?>
<script>
    function validate(year, month) {
        if(isNaN(year) || isNaN(month) || (year > 9999 || month > 12) || (year < 1 || month < 1)){
            return true;
        } else {
            return false;
        }
    }

    $(function(){
        var days = [];

        $("#search").click(function (event){
            var result = validate($("#year_search").val(), $("#month_search").val());
            if(result){
                $("#alert_message").show();
                event.preventDefault();
            }
        });

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

        $("#year_search").datepicker( {
            language: "ja",
            minViewMode: 2,
            format: "yyyy"
        });

        $("#month_search").datepicker( {
            language: "ja",
            minViewMode: 1,
            format: "m"
        });

        $(".chk_date").click(function(){
            $("input[data-weekday="+$(this).data("weekday")+"]").prop("checked", $(this).is(":checked"));
        });

        $("#check_all").click(function (){
            if($(this).is(":checked")) {
                $("input[name=check_test]").prop("checked", true);
            } else {
                $("input[name=check_test]").prop("checked", false);
            } 
        });

        $("#color_change_all").change(function (){
            $("input[name=check_test]:checked").parent("td").css({
                "background-color":$(this).val()
            })
            
            $("input[name=check_test]:checked").parent("td")
            .children(".color_change").val($(this).val());
        })

        // $("#today_highlight").parent("td").css("background", "yellow");
        $("#today_highlight").parent("td").animate({
            borderWidth:3
        },5000);

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

        $("#text_search_input").click(function() {
            $("#text_search").val();
            console.log($("#text_search").val());

            $('#search_result').empty();

            var text_object = {
                text:$("#text_search").val()
            }

            $.ajax({
                url: "search_ajax_controller",
                type: "get",
                data: text_object,
                dataType: "json"
            }).done(function(data) {
                // data = JSON.parse(data)
                $.each(data, function(i,v){
                    var date = new Date(v.year+"/"+v.month+"/"+v.day)
                    var date1 = new Date(v.year,v.month,v.day)
                    var date2 = new Date(v.year,v.month)
                    var date_f = date.getFullYear() + "年" + (date.getMonth()+1) + "月" + date.getDate() + "日"

                    var a = '<a href="https://localhost:10443/sample/index.php/Hello/calendar?year='+v.year+'&month='+v.month+'">link</a>'
                    var tr = '<tr><td>'+date_f+''+'</td> <td>'+v.text+'</td><td>'+a+'</td></tr>'
                    
                    $('#search_result').append(tr)
                    
                })
            })

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

    th {
        border: 1px solid black;
        align: center;
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


      <form class="navbar-form navbar-left" method="GET" action="https://localhost:10443/sample/index.php/Hello/calendar">
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
        <div id="y_m_search" class="form-group">
          <input type="text" id="year_search" name="year" value="<?php echo $year ?>" class="date form-control" placeholder="年" autocomplete="off">
          <input type="text" id="month_search" name="month" value="<?php echo $month ?>" class="date form-control" placeholder="月" autocomplete="off">
        </div>
        <button type="submit" id="search" class="btn btn-default">検索</button>
        <a class="btn btn-default btn-xs" href="https://localhost:10443/sample/index.php/Login/logout">ログアウト</a>
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


<div id="alert_message" class="alert alert-warning alert-dismissible" role="alert" style="display:none">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>エラー</strong>
</div>
<form method="POST" action="https://localhost:10443/sample/index.php/Hello/insert">
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
            <th style="color:red">日&nbsp;<input class="chk_date" data-weekday="Sunday" type="checkbox"/></th>
            <th>月&nbsp;<input class="chk_date" data-weekday="Monday" type="checkbox"/></th>
            <th>火&nbsp;<input class="chk_date" data-weekday="Tuesday" type="checkbox"/></th>
            <th>水&nbsp;<input class="chk_date" data-weekday="Wednesday" type="checkbox"/></th>
            <th>木&nbsp;<input class="chk_date" data-weekday="Thursday" type="checkbox"/></th>
            <th>金&nbsp;<input class="chk_date" data-weekday="Friday" type="checkbox"/></th>
            <th style="color:blue">土&nbsp;<input class="chk_date" data-weekday="Saturday" type="checkbox"/></th>
        </tr>
    </thead>

    <tbody>
    <?php
    $day = 1;
    $today = date("Y-n");
    $today1 = date("d");
    $this_month = strtotime($today) == strtotime("$year-$month");
    ?>

<?php
for($i=0; $i<$total_week; $i++){
?>
    <tr>
    <?php
    for($j=0; $j<7; $j++){
        $day_week = date("l", strtotime("$year/$month/$day"));
        $result = isset($select[$day]) ? $select[$day] : null;
    ?>
        <?php if (($day > 1 || $j >= $start_day_week) && ($total_day >= $day)) : ?>
            <td style="background:<?php echo isset($result->color) ? $result->color : "white" ?>">
                    <?php if($day_week == "Sunday") : ?>
                        <span style="color:red"><?php echo $day ?></span>
                    <?php elseif($day_week == "Saturday") : ?>
                        <span style="color:blue"><?php echo $day ?></span>
                    <?php else : ?>
                        <span style="color:black"><?php echo $day ?></span>
                    <?php endif ?>
                    <?php if(($today1 == $day) && ($this_month)) : ?>
                        <span style="color:red" id="today_highlight">本日</span>
                    <?php endif ?>
                <input type="checkbox" data-weekday="<?php echo $day_week ?>" class="chk" name="check_test" value="<?php echo $day ?>">
                <input class="color_change" name="color[]" type="color" value="<?php echo isset($result->color) ? $result->color : "#ffffff" ?>"/>
                &nbsp;<button class="set_color btn btn-primary btn-xs" type="button">適用</button><br>
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
    <button class="btn btn-default" id="input" type="button">テキスト入力</button>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#calendar_search" data-whatever="@fat">検索</button>
</form>


<!-- modal -->
<div class="modal fade" id="calendar_search" tabindex="0" role="dialog">
  <div class="modal-dialog" role="document">

    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">検索</h4>
        </div>

    <div class="modal-body">
        <div class="text_input_group">
            <label for="text_search" class="control-label">検索したいテキストを入力して下さい。</label>
            <input type="text" class="form-control" id="text_search">
            <button class="btn btn-default" id="text_search_input">入力</button>
        </div>
        <div class="output_group">
            <table class="table table-bordered">
            <thead>
                <tr>
                    <th>日付</th>
                    <th>内容</th>
                    <th></th>
                </tr>
            </thead> 
            <tbody id="search_result">

            </tbody>   
            </table>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
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
