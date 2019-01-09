<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notas".
 *
 * @property int $consecutivo
 * @property string $grupo
 * @property string $identificacion
 * @property string $nota1
 * @property string $nota2
 * @property string $nota3
 * @property string $nota4
 * @property string $nf
 * @property string $nfp
 * @property string $observaciones
 * @property int $matricula
 *
 * @property Inscritos $entificacion
 */
class Notas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['identificacion', 'matricula'], 'required'],
            [['observaciones'], 'string'],
            [['matricula'], 'integer'],
            [['grupo'], 'string', 'max' => 50],
            [['identificacion'], 'string', 'max' => 15],
            [['nota1', 'nota2', 'nota3', 'nota4', 'nf', 'nfp'], 'string', 'max' => 4],
            [['identificacion'], 'exist', 'skipOnError' => true, 'targetClass' => Inscritos::className(), 'targetAttribute' => ['identificacion' => 'identificacion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'consecutivo' => 'Consecutivo',
            'grupo' => 'Grupo',
            'identificacion' => 'Identificacion',
            'nota1' => 'Nota1',
            'nota2' => 'Nota2',
            'nota3' => 'Nota3',
            'nota4' => 'Nota4',
            'nf' => 'Nf',
            'nfp' => 'Nfp',
            'observaciones' => 'Observaciones',
            'matricula' => 'Matricula',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntificacion()
    {
        return $this->hasOne(Inscritos::className(), ['identificacion' => 'identificacion']);
    }
}
