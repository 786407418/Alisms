<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/26 0026
 * Time: 17:36
 */
namespace shangxin\alisms;
use yii\base\Component;

class AliSms extends Component{

    //fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
    public $accessKeyId =   "your access key id";
    public $accessKeySecret =   "your access key secret";
    public $domain = "dysmsapi.aliyuncs.com";

    public $params=[];

    /**
     * @param integer $phone
     * @return $this
     */
    public function setReceive($phone){
        //设置收件人手机号码
        $this->params['PhoneNumbers'] = $phone;
        return $this;
    }

    /**
     * @param string $templateCode
     * @param array $templateParams
     * @return $this
     */
    public function setTemplateInfo($templateCode='',$templateParams=array()){
        //设置短信模板
        $this->params["TemplateCode"] = $templateCode;
        //设置短信模板变量
        if(count($templateParams)>0){
            $this->params['TemplateParam'] = $templateParams;
        }
        return $this;
    }

    /**
     *  fixme 可选: 设置发送短信流水号
     * @param integer $outId
     * @return $this
     */
    public function setOutId($outId){
        $this->params['outId'] = $outId;
    }

    /**
     * fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
     * @param $SignName
     * @return $this
     */
    public function setSignName($SignName){
        $this->params['SignName'] = $SignName;
        return $this;
    }


    /**
     * fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
     * @param integer $SmsUpExtendCode
     * @return $this
     */
    public function SetSmsUpExtendCode($SmsUpExtendCode){
        $this->params['SmsUpExtendCode'] = $SmsUpExtendCode;
        return $this;
    }

    public function sendSms(){
        if(!empty($this->params["TemplateParam"]) && is_array($this->params["TemplateParam"])) {
            $this->params["TemplateParam"] = json_encode($this->params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }
        $helper = new SignatureHelper();
        // 此处可能会抛出异常，注意catch

        $content = $helper->request(
                        $this->accessKeyId,
                        $this->accessKeySecret,
                        $this->domain,
                        array_merge($this->params, array(
                            "RegionId" => "cn-hangzhou",
                            "Action" => "SendSms",
                            "Version" => "2017-05-25",
                        ))
        );

        return $content;
    }
}