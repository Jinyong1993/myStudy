<!DOCTYPE html>
<html>
<head>
<script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<style>
    td {border: 1px solid black;}

    .table-condensed{
        cursor:move;
    }
</style>
</head>

<body>
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
<form method="POST" action="https://localhost:10443/sample/index.php/Hello/insert">
    <input type="hidden" name="year" value="<?php echo $year ?>"><input type="hidden" name="month" value="<?php echo $month ?>">
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
<?php endif ?>-->

<h1 >カレンダー</h1>
<table class="table table-condensed">
    <?php
    $first = "$year/$month/1";
    $time_stamp = strtotime($first);
    $last = strtotime(date("Y/m/t", $time_stamp));
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
        <td>
            <?php if (($day > 1 || $j >= $start_day_week) && ($total_day >= $day)) : ?>
                <?php echo $day ?>
                <br>
                <textarea name="text_save[]"><?php echo isset($select[$day]) ? $select[$day] : '';?></textarea>
                <?php $day++ ?>
            <?php endif ?>
        </td>
    <?php
        }
    }
    ?>
    </tbody>

    <tfoot>

    </tfoot>
</table>
<button class="btn btn-success" type="submit">save</button>
<address>
  <strong>Twitter, Inc.</strong><br>
  1355 Market Street, Suite 900<br>
  San Francisco, CA 94103<br>
  <abbr title="Phone">P:</abbr> (123) 456-7890
</address>

<address>
  <strong>Full Name</strong><br>
  <a href="mailto:#">first.last@example.com</a>
</address>
</form>
</body>
</html>
