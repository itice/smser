<?php
namespace itice\smser;

use Yii;
use yii\base\Action;
use yii\helpers\Json;
class SmsAction extends Action
{
    public function run()
    {
        $model = new SmsReport();
        $data['SmsReport'] = Yii::$app->request->get();
        $model->load($data);
        if($model->validate()){
            $model->id_code = $this->random(6);
            $result = Yii::$app->smser->send($model->mobile, $model->id_code);
            if($result == NULL)
                return Json::encode(['err'=>1,'msg'=>'发送失败']);
            if($result->statusCode != 0){
                return Json::encode(['err'=>1,'msg'=>$result->statusMsg]);
            }else{
                $expire = Yii::$app->smser->Expire;
                $model->msg = "您的验证码是{$model->id_code}，请于{$expire}分钟内正确输入";
                $model->save(FALSE);
                return Json::encode(['err'=>0]);
            }
        }
        
        $message = '';
        foreach ($model->errors as $msg)
            $message .= $msg[0]."\n";
        return Json::encode(['err'=>1,'msg'=>$message]);
    }
    
    private function random($length, $chars = '0123456789') {
        $hash = '';
        $max = strlen($chars) - 1;
        for($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
        return $hash;
    }
}