# stock-price-service

接口地址：

    http://121.43.226.142/stock/p.php?s=sz300212,sz300059
    
参数为逗号分隔的股票ID

    sz300212,sz300059
    
返回结果为json格式，示例如下

    [{name: "易华录", price: 32.96, percent: 2.9}, {name: "东方财富", price: 42.35, percent: 1.24}]
    
如果股票ID错误，对应位置的数据为null

    http://121.43.226.142/stock/p.php?s=sz300212,error,sz300059
    
    [{name: "易华录", price: 33.05, percent: 3.18}, null, {name: "东方财富", price: 42.23, percent: 0.96}]
    
支持jsonp调用，增加jsonp参数即可

    http://121.43.226.142/stock/p.php?s=sz300212&jsonp=callback
    
    callback([{name: "易华录", price: 33.23, percent: 3.75}]);
