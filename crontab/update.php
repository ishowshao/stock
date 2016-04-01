<?php
// TODO 周六需要测试一下，如果周六取数据，如何验证不开盘而不写入数据库！

define('PATH', dirname(__FILE__));

require PATH . '/../func/common.func.php';
require PATH . '/../class/DbClient.php';

$stocks = 'sz300212,sz300059'; // 这个要改成从数据库读取

$priceData = getSinaData($stocks);

var_dump($priceData);

$stockArray = explode(',', $stocks);
foreach ($stockArray as $i => $stock) {
    $collection = DbClient::getInstance('stock_history')->getCollection($stock);
    $cursor = $collection->find(array('date' => $priceData[$i]['date']));
    echo 'find ', $priceData[$i]['date'], "\n";
    $found = $cursor->count();
    var_dump($found);
}
//foreach ($cursor as $data) {
//    var_dump($data);
//}
