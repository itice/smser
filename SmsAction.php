<?php
namespace itice\smser;

use Yii;
use yii\base\Action;
class SmsAction extends Action
{
    public function run()
    {
        Yii::$app->smser->send('13373946090','test');
    }
}