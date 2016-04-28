<?php
function parse($content) {
    $content = mb_convert_encoding(trim($content), 'utf8', 'gbk');
    $contentArray = explode("\n", $content);

    $baseInfo = array_shift($contentArray);
    array_shift($contentArray);

    $dataArray = array();
    foreach ($contentArray as $line) {
        $dataArray[] = explode(";", $line);
    }
    array_pop($dataArray);
    // print_r($dataArray);
    return $dataArray;
}

define('PATH', dirname(__FILE__));

require PATH . '/../class/DbClient.php';

$path = dirname(dirname(__FILE__)) . '/data/';
if ($handle = opendir($path)) {
    $listen = DbClient::getInstance('stock')->getCollection('listen');
    $listen->remove();
    while (false !== ($entry = readdir($handle))) {
        if ($entry != '.' && $entry != '..') {
            $parsed = parse(file_get_contents($path . '/' . $entry));
            $id = strtolower(substr($entry, 0, 8));
            echo "$id\n";
            $listen->insert(array('id' => $id));

            $collection = DbClient::getInstance('stock_history')->getCollection($id);
            $collection->remove();
            foreach ($parsed as $day) {
                $object = array();
                $object['date'] = str_replace('/', '-', $day[0]);
                $object['open'] = floatval($day[1]);
                $object['high'] = floatval($day[2]);
                $object['low'] = floatval($day[3]);
                $object['close'] = floatval($day[4]);
                $collection->insert($object);
            }
//            print_r($parsed);
        }
    }
    closedir($handle);
}
