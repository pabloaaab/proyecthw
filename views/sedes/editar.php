<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Sede;

$this->title = 'Editar Sede';
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
$sede = ArrayHelper::map(\app\models\Municipio::find()->all(), 'opcion','opcion');
?>

<div class="row" id="sede">
    <div class="col-lg-3">
        <?= $form->field($model, 'consecutivo')->input("hidden") ?>
        <?= $form->field($model, 'sede')->dropDownList($sede,['prompt' => 'Seleccione...' ]) ?>
    </div>

</div>

<div class="row">
    <div class="col-lg-4">
        <?= Html::submitButton("Actualizar", ["class" => "btn btn-primary"])?>
        <a href="<?= Url::toRoute("sedes/index") ?>" class="btn btn-primary">Regresar</a>
    </div>
</div>

<?php $form->end() ?>
