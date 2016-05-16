<?php
define('PATH', dirname(__FILE__));

require PATH . '/../class/DbClient.php';

$collection = DbClient::getInstance('stock_history')->getCollection('sz300059');

$all = $collection->find()->sort(array('date' => 1));

$averageDay = 5;
$initMoney = 100000;

$closeArray = array();
foreach ($all as $day) {
    if ($day['close'] !== .0) {
        $closeArray[] = $day['close'];
    }
}

$total = count($closeArray);
$sum = 0;

$averageArray = array();

for ($i = 0; $i < $total; $i++) {
//    echo $closeArray[$i], ' ';
    if ($i >= $averageDay) {
        $sum -= $closeArray[$i - $averageDay];
    }
    $sum += $closeArray[$i];
    if ($i >= $averageDay - 1) {
        $averageArray[] = $sum / $averageDay;
//        echo $sum / $averageDay, ' ';
    }
//    echo "\n";
}

$averageCount = count($averageArray);
$n2 = 0;
$n1 = 0;
$n = 0;
for ($i = 2; $i < $averageCount; $i++) {
    $n = $averageArray[$i];
    $n1 = $averageArray[$i - 1];
    $n2 = $averageArray[$i - 2];
    if ($n2 > $n1 && $n > $n1) {
        echo "$i: $n2 $n1 $n buy\n";
    }
    if ($n2 < $n1 && $n < $n1) {
        echo "$i: $n2 $n1 $n sail\n";
    }
}
