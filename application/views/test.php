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

        $(".plus_button").click(function (){
			var plus_position = $(this).closest('td').find(':checkbox[name=check_test]').val()
			
            $("#plus_title").val(null)
            $("#plus_textarea").val(null)
            $("#plus_save").off('click')
            
            $("#plus_save").click(function (){
                var plus_object = {
                    year:$("#year_ajax").val(),
                    month:$("#month_ajax").val(),
                    day:plus_position,
                    title:$("#plus_title").val(),
                    text:$("#plus_textarea").val(),
                }

                $.ajax({
                    url: "plus_ajax_controller",
                    type: "post",
                    data: plus_object,
                    dataType: "json"
                }).done(function(data) {
                    if(data.success){
                        location.reload()
                    } else {
                        alert(data.error)
                    }
                })
            });
        });

        $(".title").click(function(){
			var day = $(this).closest('td').find(':checkbox[name=check_test]').val()
            var id = $(this).data("id")

            var id_object = {
                id:$(this).data("id")
            }
            
            $.ajax({
                url: "title_ajax_controller",
                type: "get",
                data: id_object,
                dataType: "json"
            }).done(function(data) {
                $("#plus_title").val(data.title);
                $("#plus_textarea").val(data.text);
            })
            
            $("#plus_save").off('click')
            $("#plus_delete").off('click')
            $("#plus_save").click(function (){
                var plus_object = {
                    id:id,
                    year:$("#year_ajax").val(),
                    month:$("#month_ajax").val(),
                    day:day,
                    title:$("#plus_title").val(),
                    text:$("#plus_textarea").val(),
                }

                $.ajax({
                    url: "plus_ajax_controller",
                    type: "post",
                    data: plus_object,
                    dataType: "json"
                }).done(function(data) {
                    if(data.success){
                        location.reload()
                    } else {
                        alert(data.error)
                    }
                })
            });

            $("#plus_delete").click(function(){
                var plus_object = {
                    id:id,
                    year:$("#year_ajax").val(),
                    month:$("#month_ajax").val(),
                    day:day,
                    title:$("#plus_title").val(),
                    text:$("#plus_textarea").val(),
                }
                console.log(plus_object)

                $.ajax({
                    url: "delete_ajax",
                    type: "post",
                    data: plus_object,
                    dataType: "json"
                }).done(function(data) {
                    location.reload()
                    alert("処理しました。")
                })
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
                dataType: "json" // data = JSON.parse(data)
            }).done(function(data) {
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

        $("#chk_plus").click(function(){
            var days = []
            $(':checkbox[name="check_test"]:checked').each(function () {
                days.push($(this).val());
            });
            console.log(days);

            
            $("#plus_save").off('click')
            $("#plus_save").click(function (){
                var plus_object = {
                    year:$("#year_ajax").val(),
                    month:$("#month_ajax").val(),
                    day:days,
                    title:$("#plus_title").val(),
                    text:$("#plus_textarea").val(),
                }
                $.ajax({
                    url: "plus_ajax_controller",
                    type: "post",
                    data: plus_object,
                    dataType: "json"
                }).done(function(data) {
                    if(data.success){
                        location.reload()
                    } else {
                        alert(data.error)
                    }
                })
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

    th {
        border: 1px solid black;
        align: center;
    }
</style>
</head>

<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  	<div class="container-fluid">
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<a class="navbar-brand" href="https://localhost:10443/sample/index.php/Hello/calendar#">カレンダー</a>
		<div class="collapse navbar-collapse" id="navbarTogglerDemo03">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
				<li class="nav-item">
				<a class="nav-link active" aria-current="page" href="#">Home</a>
				</li>
				<li class="nav-item">
				<a class="nav-link" href="#">Link</a>
				</li>
				<li class="nav-item">
				<a class="nav-link disabled">Disabled</a>
				</li>
			</ul>
		</div>
		<form class="navbar-form navbar-left row" method="GET" action="https://localhost:10443/sample/index.php/Hello/calendar">
			<div class="col-auto">
			<?php if($month == 1) : ?>
				<a class="btn btn-secondary btn-sm" href="https://localhost:10443/sample/index.php/Hello/calendar?<?php echo "year=".($year-1) ?>&month=12" role="button">先月</a>
			<?php else : ?>
				<a class="btn btn-secondary btn-sm" href="https://localhost:10443/sample/index.php/Hello/calendar?<?php echo "year=".$year ?>&month=<?php echo $month-1?>">先月</a>
			<?php endif ?>
			</div>
			<div class="col-auto">
			<?php if($month == 12) : ?>
				<a class="btn btn-secondary btn-sm" href="https://localhost:10443/sample/index.php/Hello/calendar?<?php echo "year=".($year+1) ?>&month=1">来月</a>
			<?php else : ?>
				<a class="btn btn-secondary btn-sm" href="https://localhost:10443/sample/index.php/Hello/calendar?<?php echo "year=".$year ?>&month=<?php echo $month+1 ?>">来月</a>
			<?php endif ?>
			</div>
			<div class="col-auto">
				<input type="text" id="year_search" name="year" value="<?php echo $year ?>" class="date form-control form-control-sm" placeholder="年" autocomplete="off">
			</div>
			<div class="col-auto">
				<input type="text" id="month_search" name="month" value="<?php echo $month ?>" class="date form-control form-control-sm" placeholder="月" autocomplete="off">
			</div>
			<div class="col-auto">
				<button type="submit" id="search" class="btn btn-secondary btn-sm">検索</button>
			</div>
			<div class="col-auto">
				<?php if($this->session->userdata('user_id')) : ?>
				<div class="nav navbar-nav navbar-left">
				  <h3><?php echo $this->session->userdata('name') ?> 様</h3>
				</div>
				<?php endif ?>
			</div>
			<div class="col-auto">
				<a class="btn btn-danger btn-sm" href="https://localhost:10443/sample/index.php/Login/logout">ログアウト</a>
			</div>
			<div class="col-auto">
			<a class="btn btn-info btn-sm" href="https://localhost:10443/sample/index.php/Login/user_info"> 会員情報</a>
			</div>
		</form>
	</div>
</nav>

<form method="POST" action="https://localhost:10443/sample/index.php/Hello/insert">
    <input type="text" id="year_ajax" name="year" value="<?php echo $year ?>"><input type="text" id="month_ajax" name="month" value="<?php echo $month ?>">

<table class="table table-condensed">
    <?php
    $first = "$year/$month/1";
    $time_stamp = strtotime($first);
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
            <td>
				<div class="d-flex align-items-center">
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
					<div class="ps-2">
						<input type="checkbox" data-weekday="<?php echo $day_week ?>" class="chk pl-2" name="check_test" value="<?php echo $day ?>">
					</div>
				<div style="flex-grow:1"></div>
				<button style="" class="plus_button btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target=".plus">+</button><br>
                <?php $day++ ?>
				</div>
				<?php foreach($result as $row) : ?>
				<div>
					<?php if(!empty($row->title)) : ?>
					<input type="button" class="title" data-id="<?php echo $row->id ?>" value="<?php echo $row->title ?>" data-bs-toggle="modal" data-bs-target=".plus"/>
					<?php endif ?>
				</div>
				<?php endforeach ?>
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
	<button type="button" id="chk_plus" class="btn btn-success" data-bs-toggle="modal" data-bs-target=".plus">追加</button>
	<button type="button" id="del" class="btn btn-danger">削除</button>

	<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">検索</button>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#calendar_search">検索</button>
</form>


<!-- + button modal -->
<div class="modal plus" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">日程追加</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>

			<div class="modal-body">
				<div>
                    タイトル<br>
					<input type="text" id="plus_title" value=""/>
				</div>
				<div>
					<textarea id="plus_textarea"></textarea>
				</div>
			</div>

			<div class="modal-footer">
				<button id="plus_delete" type="button" class="btn btn-danger">削除</button>
				<button id="plus_cancel" type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
				<button id="plus_save" type="button" class="btn btn-success">保存</button>
			</div>
		</div>
	</div>
</div>




<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">

    <div class="modal-content">
      	<div class="modal-header">
        	<h1 class="modal-title fs-5" id="exampleModalLabel">検索</h1>
        	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      	</div>

      	<div class="modal-body">
			<form>
			<div class="mb-3">
				<label for="text_search" class="col-form-label">Recipient:</label>
				<input type="text" class="form-control" id="recipient-name">
			</div>
			<div class="mb-3">
				<label for="message-text" class="col-form-label">Message:</label>
				<textarea class="form-control" id="message-text"></textarea>
			</div>
			</form>
      	</div>

      	<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary">Send message</button>
      	</div>
    </div>
  </div>
</div>

<!-- modal -->
<div class="modal fade" id="calendar_search" tabindex="-1" role="dialog">
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
            <button class="btn btn-secondary" id="text_search_input"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> 入力</button>
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
      </div>

    </div>
  </div>
</div>
</form>
</body>
</html>
