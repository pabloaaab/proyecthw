<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\UploadedFile;
use app\models\Matriculados;
use app\models\Inscritos;
use app\models\PagosPeriodo;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Session;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use kartik\date\DatePicker;

$this->title = 'Users Permisos';
?>

<?php
$form = ActiveForm::begin([

            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
                'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
                'labelOptions' => ['class' => 'col-sm-3 control-label'],
                'options' => []
            ],
        ]);
?>

<?php
if ($mensaje != "") {
    ?> <div class="alert alert-danger"><?= $mensaje ?></div> <?php
}
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        Usuario
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <th><?= Html::activeLabel($model, 'id') ?>:</th>
                <td><?= Html::encode($model->id) ?></td>
                <th><?= Html::activeLabel($model, 'username') ?>:</th>
                <td><?= Html::encode($model->username) ?></td>
                <th><?= Html::activeLabel($model, 'nombrecompleto') ?>:</th>
                <td><?= Html::encode($model->nombrecompleto) ?></td>
            </tr>
            <tr>
                <th><?= Html::activeLabel($model, 'fechacreacion') ?>:</th>
                <td><?= Html::encode($model->fechacreacion) ?></td>
                <th><?= Html::activeLabel($model, 'sede') ?>:</th>
                <td><?= Html::encode($model->sede) ?></td>
                <th><?= Html::activeLabel($model, 'perfil') ?>:</th>
                <td><?= Html::encode($model->perfil) ?></td>
            </tr>                                           
        </table>
    </div>
</div>
<div class="table table-responsive">
    <div class="panel panel-primary ">
        <div class="panel-heading">
            Permisos
        </div>
        <div class="panel-body">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Permiso</th>
                        <th scope="col">activo</th>                                                            
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($userspermisos as $val): ?>
                        <tr>                    
                            <?php if ($val->activo == 0){$activo = "NO";}else{$activo = "SI";}?>
                            <td><?= $val->id_users_detalle ?></td>
                            <td><?= $val->permisoFk->permiso ?></td>                            
                            <td><select name="activo[]" required="true">
                                    <option value="<?= $val->activo ?>"><?= $activo ?></option>
                                    <option value="1">SI</option>
                                    <option value="0">NO</option>                        
                                </select></td>
                            <td><input type="hidden" name="id_users_detalle[]" value="<?= $val->id_users_detalle ?>"></td>    
                            <td><input type="hidden" name="id" value="<?= $model->id ?>"></td>    
                        </tr>
                    </tbody>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="panel-footer text-right">
            <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['site/usuarios'], ['class' => 'btn btn-primary']) ?>
            <?= Html::submitButton("Actualizar", ["class" => "btn btn-primary", 'name' => 'actualizar']) ?>
            <?= Html::a('Nuevo', ['site/newpermiso','id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
