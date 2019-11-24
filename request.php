<?php
    header('Content-Type: text/html;charset=utf-8');
    header('Access-Control-Allow-Origin:*'); // *代表允许任何网址请求
    header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE'); // 允许请求的类型
    header('Access-Control-Allow-Credentials: true'); // 设置是否允许发送 cookies
    header('Access-Control-Allow-Headers: Content-Type,Content-Length,Accept-Encoding,X-Requested-with, Origin'); // 设置允许自定义请求头的字段
	function http_get($url)
	{
		  $curl = curl_init(); // 启动一个CURL会话
		    curl_setopt($curl, CURLOPT_URL, $url);
		    curl_setopt($curl, CURLOPT_HEADER, 0);
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
		    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
		    $tmpInfo = curl_exec($curl);     //返回api的json对象
		    //关闭URL请求
		    curl_close($curl);
		    return $tmpInfo;    //返回json对象
		
    }

    function http_post($url,$data_string,$timeout = 60)
    {
        //curl验证成功
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);//// 跳过证书检查 
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
/*         curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        )); */
 
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            print curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }
    if(isset($_GET["url"])){
        $url=urldecode($_GET["url"]);
    } else {
        echo json_encode(array(
            "msg" => 'url错误'
        ));
        return;
    }
    if(isset($_GET["method"])){
        $method=strtolower($_GET["method"]);
        if($method!="get"||$method!="post"){
            echo json_encode(array(
                "msg" => '请求方式错误'
            ));
            return;     //返回，终止后续代码的允许
        }
    } else{
        $method="get";  //默认方式为get
    }
    if(isset($_GET["parameter"])){
        $parameter=$_GET["parameter"];
    } else{
        $parameter="";
    }

    if("get"==$method){
        echo http_get($url);
    } else if("post"){
        echo http_post($url,$parameter);
    } else {
        echo json_encode(array(
            "msg" => '未知错误'
        ));
        return ;
    }



    /* 
        使用教程：
            method参数：get，post，默认get请求
            url参数：请求地址，必须，需要url编码
            parameter参数：非必须，额外请求参数，需要url编码
    */
?>