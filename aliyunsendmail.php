<?php    

    include_once 'aliyun-php-sdk-core/Config.php';
    use Dm\Request\V20151123 as Dm;            
    //需要设置对应的region名称，如华东1（杭州）设为cn-hangzhou，新加坡Region设为ap-southeast-1，澳洲Region设为ap-southeast-2。
    $iClientProfile = DefaultProfile::getProfile("cn-hangzhou", "AccessKey ID", "AccessKey");        
    //新加坡或澳洲region需要设置服务器地址，华东1（杭州）不需要设置。
    //$iClientProfile::addEndpoint("ap-southeast-1","ap-southeast-1","Dm","dm.ap-southeast-1.aliyuncs.com");
    //$iClientProfile::addEndpoint("ap-southeast-2","ap-southeast-2","Dm","dm.ap-southeast-2.aliyuncs.com");
    $client = new DefaultAcsClient($iClientProfile);    
    $request = new Dm\SingleSendMailRequest();     
    //新加坡或澳洲region需要设置SDK的版本，华东1（杭州）不需要设置。
    //$request->setVersion("2017-06-22");
    $request->setAccountName("发件邮箱");
    $request->setFromAlias("发送者用户名");
    $request->setAddressType(1);
    $request->setTagName("Inform");
    $request->setReplyToAddress("true");

    echo "收到来自前端的数组，结构如下：<br>";

    var_dump($_POST['email']);
    $content=$_POST["content"];
    $subject=$_POST["subject"];

    echo "<br><br>开始发送邮件....<Br>";
    
    $arrlength=count($_POST['email']);
    echo "共有 $arrlength 邮件等待发送<br>内容为：$content <br>";
 

    for($x=0;$x<$arrlength;$x++)
    {
        $email=$_POST['email'][$x];
        echo "正在向 $email 发送邮件..............收到来自阿里云的反馈————————————————————";
        $request->setToAddress("$email");
        //可以给多个收件人发送邮件，收件人之间用逗号分开,若调用模板批量发信建议使用BatchSendMailRequest方式
        //$request->setToAddress("邮箱1,邮箱2");
        $request->setSubject("$subject");
        $request->setHtmlBody("$content");        
        try {
            $response = $client->getAcsResponse($request);
            print_r($response);
        }
        catch (ClientException  $e) {
            print_r($e->getErrorCode());   
            print_r($e->getErrorMessage());   
        }
        catch (ServerException  $e) {        
            print_r($e->getErrorCode());   
            print_r($e->getErrorMessage());
        }
        echo "<br>任务完成，准备下一封邮件<br><br>";
    }
    
    echo "<br><br><br><br>邮件发送任务已经全部完成，感谢使用本系统";

?>