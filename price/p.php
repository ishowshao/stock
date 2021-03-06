<?php
/**
 * http://hq.sinajs.cn/list=s_sz300212
 * var hq_str_s_sz300212="易华录,32.760,0.730,2.28,62617,20692";
 * http://hq.sinajs.cn/list=sz300212
 * var hq_str_sz300212="易华录,33.010,32.030,32.760,33.490,32.600,32.760,32.800,6261759,206925190.880,900,32.760,4100,32.750,4600,32.740,4240,32.730,20900,32.720,500,32.800,700,32.810,1800,32.820,1000,32.870,200,32.880,2016-02-22,11:35:56,00";
 */
require '../func/common.func.php';

$response = array();
if (isset($_GET['s'])) {
    $s = trim($_GET['s']);
    if (strlen($s) > 0) {
        $response = getSinaSimpleData($s);
    }
}

if (isset($_GET['jsonp']) && preg_match('/^[_a-zA-Z][A-Za-z0-9_]*/', $_GET['jsonp'])) {
    header('Content-Type: application/javascript');
    echo $_GET['jsonp'], '(', json_encode($response), ');';
} else {
    header('Content-Type: application/json');
    echo json_encode($response);
}
