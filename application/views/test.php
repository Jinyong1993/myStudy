<!DOCTYPE html>
<html>
<head>
<style>
    td {border: 1px solid black;}
</style>
</head>
<body>
<form method="GET" action="https://localhost:10443/h28-042_comyu-navi/sample/index.php/Hello/calendar?<?php ?>">
    <input type="text" name="year" value="<?php echo $year ?>">/<input type="text" name="month" value="<?php echo $month ?>">
    <button type="submit">search</button>
    <br>
    <?php if($month == 1) : ?>
        <a href="https://localhost:10443/h28-042_comyu-navi/sample/index.php/Hello/calendar?<?php echo "year=".($year-1) ?>&month=12">previous month</a>
    <?php else : ?>
        <a href="https://localhost:10443/h28-042_comyu-navi/sample/index.php/Hello/calendar?<?php echo "year=".$year ?>&month=<?php echo $month-1?>">previous month</a>
    <?php endif ?>
    <?php if($month == 12) : ?>
        <a href="https://localhost:10443/h28-042_comyu-navi/sample/index.php/Hello/calendar?<?php echo "year=".($year+1) ?>&month=1">next month</a>
    <?php else : ?>
        <a href="https://localhost:10443/h28-042_comyu-navi/sample/index.php/Hello/calendar?<?php echo "year=".$year ?>&month=<?php echo $month+1 ?>">next month</a>
    <?php endif ?>
<?php if(!$isError) : ?>
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
            <?php $day = date('d', $time) ?>
        </td>
        <td>
            <?= date('D', $time) ?>
        </td>
        <td>
            <?php 
            $text = '';
            foreach($query as $q){
                if($q->day == $day){
                    $text = $q->text;
                }
            }
                
            ?>
            <input value="<?=$text?>"/>
        </td>
    </tr>
    <?php
    $time += $sec;
    endfor
    ?>
</table>
<?php endif ?>
</form>
</body>
</html>
