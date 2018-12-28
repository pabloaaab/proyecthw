<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Sede;
use app\models\Nivel;
use app\models\Inscritos;
use app\models\Horario;

$this->title = 'Editar Grupo';
?>

<h1>Editar registro con Consecutivo <?= Html::encode($_GET["consecutivo"]) ?></h1>
<?php if ($tipomsg == "danger") { ?>
    <h3 class="alert-danger"><?= $msg ?></h3>
<?php } else{ ?>
    <h3 class="alert-success"><?= $msg ?></h3>
<?php } ?>

<?php $form = ActiveForm::begin([
    "method" => "post",
    'id' => 'formulario',
    'enableClientValidation' => false,
    'enableAjaxValidation' => true,
]);
?>

<?php
$sede = ArrayHelper::map(Sede::find()->all(), 'sede','sede');
$nivel = ArrayHelper::map(Nivel::find()->all(), 'nivel','nivel');
$desde = ArrayHelper::map(Horario::find()->all(), 'horario','horario');
$hasta = ArrayHelper::map(Horario::find()->all(), 'horario','horario');
$docente = Inscritos::find()->where(['tipo_personal' => 'Docente'])->asArray()->all();
$docente = ArrayHelper::map($docente, 'identificacion', 'nombre1');
?>

<div class="row" id="grupo">
    <div class="col-lg-4">
        <?= $form->field($model, 'consecutivo')->input("hidden") ?>
        <?= $form->field($model, 'fechaInicio')->input("date") ?>
        <?= $form->field($model, 'nivel')->dropDownList($nivel,['prompt' => 'Seleccione...' ]) ?>
        <?= $form->field($model, 'sede')->dropDownList($sede,['prompt' => 'Seleccione...' ]) ?>
        <?= $form->field($model, 'tipo_horario')->dropdownList(['Semana' => 'Semana', 'Sabado' => 'Sabado', 'Domingo' => 'Domingo'], ['prompt' => 'Seleccione...']) ?>
        <div class="row" id="familiar">
            <div class="col-lg-2">
                <?= $form->field($model, 'lunes')->checkbox(array('label'=>'Lunes','labelOptions'=>array('style'=>'padding:5px;'))); ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'martes')->checkbox(array('label'=>'Martes','labelOptions'=>array('style'=>'padding:5px;'))); ?>
            </div>
            <div class="col-lg-3">
                <?= $form->field($model, 'miercoles')->checkbox(array('label'=>'Miercoles','labelOptions'=>array('style'=>'padding:5px;'))); ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'jueves')->checkbox(array('label'=>'Jueves','labelOptions'=>array('style'=>'padding:5px;'))); ?>
            </div>
            <div class="col-lg-2">
                <?= $form->field($model, 'viernes')->checkbox(array('label'=>'Viernes','labelOptions'=>array('style'=>'padding:5px;'))); ?>
            </div>
        </div>
        <?= $form->field($model, 'jornada')->dropdownList(['Mañana' => 'Mañana', 'Tarde' => 'Tarde', 'Noche' => 'Noche'], ['prompt' => 'Seleccione...']) ?>
        <?= $form->field($model, 'de')->dropDownList($desde,['prompt' => 'Seleccione...' ]) ?>
        <?= $form->field($model, 'a')->dropDownList($hasta,['prompt' => 'Seleccione...' ]) ?>
        <?= $form->field($model, 'docente')->dropDownList($docente,['prompt' => 'Seleccione...' ]) ?>
        <?= $form->field($model, 'cuota_mensual')->input("text") ?>
    </div>

</div>

<div class="row">
    <div class="col-lg-4">
        <?= Html::submitButton("Actualizar", ["class" => "btn btn-primary"])?>
        <a href="<?= Url::toRoute("grupos/index") ?>" class="btn btn-primary">Regresar</a>
    </div>
</div>

<?php $form->end() ?>
