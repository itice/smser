<?php
namespace itice\smser;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%sms_report}}".
 *
 * @property string $id
 * @property string $mobile
 * @property string $created_at
 * @property string $id_code
 * @property string $msg
 * @property string $ip
 */
class SmsReport extends ActiveRecord
{
    public $captcha;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sms_report}}';
    }
    
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class'      => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    
    public function rules()
    {
        return [
            [['captcha','mobile'], 'required'],
            ['captcha','captcha'],
            ['mobile', 'match', 'pattern' => '/^(1(([35][0-9])|(47)|[8][0126789]))\d{8}$/'],
            ['mobile', 'validateRate'],
            ['ip', 'default', 'value'=>Yii::$app->request->userIP],
        ];
    }
    
    public function validateRate($attribute, $params){
        if(!$this->hasErrors()){
            $start = time() - 86400;
            $today_num = self::find()->where(['mobile'=>$this->mobile])->andWhere(['>','created_at',$start])->count();
            $DayMobile = \Yii::$app->smser->DayMobile;
            if($today_num >= $DayMobile)
                $this->addError($attribute, "当日发送短信数量超过限制 $DayMobile 条");
    
            $ip_num = self::find()->where(['ip'=>Yii::$app->request->userIP])->andWhere(['>','created_at',$start])->count();
            $DayIp = \Yii::$app->smser->DayIp;
            if($ip_num >= $DayIp)
                $this->addError($attribute, "当日单IP发送短信数量超过限制 $DayIp 条");
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mobile' => 'Mobile',
            'created_at' => 'created_at',
            'id_code' => 'Id Code',
            'msg' => 'Msg',
            'ip' => 'Ip',
        ];
    }
}
