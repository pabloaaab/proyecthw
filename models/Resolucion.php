<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resolucion".
 *
 * @property int $codigo_resolucion_pk
 * @property string $resolucion
 */
class Resolucion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resolucion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['resolucion'], 'required'],
            [['resolucion'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'codigo_resolucion_pk' => 'Codigo Resolucion Pk',
            'resolucion' => 'Resolucion',
        ];
    }
}
