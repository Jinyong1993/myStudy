<!DOCTYPE html>
<html>
<head>
<style>
    td {border: 1px solid black;}
</style>
</head>
<body>
<form method="GET" action="https://localhost:10443/sample/index.php/Hello/calendar">
    <input type="text" name="year" value="<?php echo $year ?>">/<input type="text" name="month" value="<?php echo $month ?>">
    <button type="submit">search</button>
    <br>
    <?php if($month == 1) : ?>
        <a href="https://localhost:10443/sample/index.php/Hello/calendar?<?php echo "year=".($year-1) ?>&month=12">previous month</a>
    <?php else : ?>
        <a href="https://localhost:10443/sample/index.php/Hello/calendar?<?php echo "year=".$year ?>&month=<?php echo $month-1?>">previous month</a>
    <?php endif ?>
    <?php if($month == 12) : ?>
        <a href="https://localhost:10443/sample/index.php/Hello/calendar?<?php echo "year=".($year+1) ?>&month=1">next month</a>
    <?php else : ?>
        <a href="https://localhost:10443/sample/index.php/Hello/calendar?<?php echo "year=".$year ?>&month=<?php echo $month+1 ?>">next month</a>
    <?php endif ?>
</form>
<form method="POST" action="https://localhost:10443/sample/index.php/Hello/insert">
    <input type="hidden" name="year" value="<?php echo $year ?>"><input type="hidden" name="month" value="<?php echo $month ?>">
    <button type="submit">save</button>
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


<table>
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

</form>
</body>
</html>
