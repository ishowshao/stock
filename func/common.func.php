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
 * 解析新浪股票获取过来的原始股票价格字符串
 *
 * @param string $priceData
 * @return array
 */
function parseSinaPrice($priceData) {
    $parsed = array();
    $array = explode("\n", mb_convert_encoding($priceData, 'utf8', 'gbk'));
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
