<?php
// TODO 周六需要测试一下，如果周六取数据，如何验证不开盘而不写入数据库！

define('PATH', dirname(__FILE__));

require PATH . '/../func/common.func.php';
require PATH . '/../class/DbClient.php';

$listenStocks = DbClient::getInstance('stock')->getCollection('listen')->find();
$stocks = array();
$count = 0;
foreach ($listenStocks as $object) {
    $stocks[] = $object['id'];
    $count++;
    if ($count == 10) {
        // 批处理10份数据
        $priceData = getSinaData(implode(',', $stocks));

        foreach ($stocks as $i => $stock) {
            $collection = DbClient::getInstance('stock_history')->getCollection($stock);
            $cursor = $collection->find(array('date' => $priceData[$i]['date']));
            echo 'find ', $priceData[$i]['date'], "\n";
            $found = $cursor->count();
            if (!$found) {
                $object = $priceData[$i];
                unset($object['name']);
                unset($object['yesterday_close']);
                unset($object['time']);
                $object['close'] = $object['current'];
                unset($object['current']);

                $collection->insert($object);
            }
        }

        // reset
        $count = 0;
        $stocks = array();
    }
}

// 处理剩余的数据
$priceData = getSinaData(implode(',', $stocks));

foreach ($stocks as $i => $stock) {
    $collection = DbClient::getInstance('stock_history')->getCollection($stock);
    $cursor = $collection->find(array('date' => $priceData[$i]['date']));
    echo 'find ', $priceData[$i]['date'], "\n";
    $found = $cursor->count();
    if (!$found) {
        $object = $priceData[$i];
        unset($object['name']);
        unset($object['yesterday_close']);
        unset($object['time']);
        $object['close'] = $object['current'];
        unset($object['current']);

        $collection->insert($object);
    }
}
//foreach ($cursor as $data) {
//    var_dump($data);
//}
