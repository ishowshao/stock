<?php
/**
 * 使用curl获取一个url
 *
 * @param string $url
 * @return string
 */
function fetch($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $return = curl_exec($ch);
    curl_close($ch);
    return $return;
}

/**
 * 解析新浪股票获取过来的原始股票价格字符串，只适用以下格式
 * http://hq.sinajs.cn/list=s_sz300212
 * var hq_str_s_sz300212="易华录,32.760,0.730,2.28,62617,20692";
 *
 * @param string $stockIds 逗号分隔的stock id sz300212,sz300059
 * @return array
 */
function getSinaSimpleData($stockIds) {
    // 格式转换 sz300212,sz300059  --> s_sz300212,s_sz300059
    $sArray = explode(',', $stockIds);
    $list = '';
    foreach ($sArray as $stockId) {
        $list .= 's_' . $stockId . ',';
    }

    $parsed = array();
    $array = explode("\n", mb_convert_encoding(fetch('http://hq.sinajs.cn/list=' . $list), 'utf8', 'gbk'));
    foreach ($array as $stockString) {
        if ($stockString) {
            $stock = array();
            $stockString = substr($stockString, strpos($stockString, '"') + 1);
            $stockString = substr($stockString, 0, strpos($stockString, '"'));
            if ($stockString !== '') {
                $stockData = explode(',', $stockString);
                $stock['name'] = $stockData[0];
                $stock['price'] = floatval($stockData[1]);
                $stock['percent'] = floatval($stockData[3]);
                $parsed[] = $stock;
            } else {
                $parsed[] = null;
            }
        }
    }
    return $parsed;
}

function getSinaData($stockIds) {
    $parsed = array();
    $array = explode("\n", mb_convert_encoding(fetch('http://hq.sinajs.cn/list=' . $stockIds), 'utf8', 'gbk'));
    foreach ($array as $stockString) {
        if ($stockString) {
            $stockString = substr($stockString, strpos($stockString, '"') + 1);
            $stockString = substr($stockString, 0, strpos($stockString, '"'));
            if ($stockString !== '') {
                $stockData = explode(',', $stockString);
                $stock = array();
                $stock['name'] = $stockData[0];
                $stock['open'] = floatval($stockData[1]);
                $stock['yesterday_close'] = floatval($stockData[2]);
                $stock['current'] = floatval($stockData[3]);
                $stock['high'] = floatval($stockData[4]);
                $stock['low'] = floatval($stockData[5]);
                $stock['date'] = $stockData[30];
                $stock['time'] = $stockData[31];
                $parsed[] = $stock;
            } else {
                $parsed[] = null;
            }
        }
    }
    return $parsed;
}