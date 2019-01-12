<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users_detalle".
 *
 * @property int $id_users_detalle
 * @property int $activo
 * @property int $id_permiso_fk
 * @property int $id_users
 *
 * @property Users $users
 * @property Permisos $permisoFk
 */
class UsersDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['activo', 'id_permiso_fk', 'id_users'], 'required'],
            [['activo', 'id_permiso_fk', 'id_users'], 'integer'],
            [['id_users'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['id_users' => 'id']],
            [['id_permiso_fk'], 'exist', 'skipOnError' => true, 'targetClass' => Permisos::className(), 'targetAttribute' => ['id_permiso_fk' => 'id_permiso_pk']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_users_detalle' => 'Id Users Detalle',
            'activo' => 'Activo',
            'id_permiso_fk' => 'Id Permiso Fk',
            'id_users' => 'Id Users',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'id_users']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermisoFk()
    {
        return $this->hasOne(Permisos::className(), ['id_permiso_pk' => 'id_permiso_fk']);
    }
}
