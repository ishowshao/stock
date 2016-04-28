<?php
// TODO 周六需要测试一下，如果周六取数据，如何验证不开盘而不写入数据库！

define('PATH', dirname(__FILE__));

require PATH . '/../func/common.func.php';
require PATH . '/../class/DbClient.php';

$listenStocks = DbClient::getInstance('stock')->getCollection('listen')->find();
$stocks = array();
foreach ($listenStocks as $object) {
    $stocks[] = $object['id'];
}

$priceData = getSinaData(implode(',', $stocks));

var_dump($priceData);

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
    var_dump($found);
}
//foreach ($cursor as $data) {
//    var_dump($data);
//}
