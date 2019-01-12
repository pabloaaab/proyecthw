<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "permisos".
 *
 * @property int $id_permiso_pk
 * @property string $permiso
 *
 * @property UsersDetalle[] $usersDetalles
 */
class Permisos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'permisos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['permiso'], 'required'],
            [['permiso'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_permiso_pk' => 'Id Permiso Pk',
            'permiso' => 'Permiso',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersDetalles()
    {
        return $this->hasMany(UsersDetalle::className(), ['id_permiso_fk' => 'id_permiso_pk']);
    }
}
