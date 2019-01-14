<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "autorizacion".
 *
 * @property int $consecutivo
 * @property string $formato
 * @property string $firma
 */
class Autorizacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'autorizacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['formato'], 'string'],
            [['firma'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'consecutivo' => 'Consecutivo',
            'formato' => 'Formato',
            'firma' => 'Firma',
        ];
    }
}
