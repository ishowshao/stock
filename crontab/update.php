<?php
require '../func/common.func.php';

$stocks = 'sz300212,sz300059'; // 这个要改成从数据库读取

$priceData = getSinaSimpleData($stocks);

var_dump($priceData);
