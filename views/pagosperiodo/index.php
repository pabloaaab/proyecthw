<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Pagos Periodos';
?>

    <h1>Pagos Periodo</h1>
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("pagosperiodo/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
?>

<div class="panel panel-primary panel-filters">
    <div class="panel-heading">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtropagosperiodo">
        <div class="row" >
            <?= $formulario->field($form, "identificacion")->input("search") ?>
            <?= $formulario->field($form, "nivel")->input("search") ?>
            <?= $formulario->field($form, "sede")->input("search") ?>
            <?= $formulario->field($form, "mensualidad")->input("search") ?>
            <?= $formulario->field($form, "anulado")->input("search") ?>
            <?= $formulario->field($form, "pagado")->input("search") ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
            <a align="right" href="<?= Url::toRoute("pagosperiodo/index") ?>" class="btn btn-primary">Actualizar</a>
        </div>
    </div>
</div>    
    
<?php $formulario->end() ?>
    
    <div class="container-fluid">
        <div class="col-lg-2">

        </div>
    </div>
    <div class="table-condensed">
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Código</th>                                
                <th scope="col">N° Pago</th>
                <th scope="col">Mensualidad</th>
                <th scope="col">Estudiante</th>
                <th scope="col">Valor a Pagar</th>
                <th scope="col">Valor Pagado</th>
                <th scope="col">Pagado</th>
                <th scope="col">Anulado</th>                
                <th scope="col">Sede</th>
                <th scope="col">Nivel</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr>
                <th scope="row"><?= $val->consecutivo ?></th>                
                <td><?= $val->nropago ?></td>                                
                <td><?= $val->mensualidad ?></td>
                <td><?= $val->identificacion ?></td>
                <td><?= "$ ".number_format($val->total) ?></td>
                <td><?= "$ ".number_format($val->pago1) ?></td>
                <td><?= $val->afecta_pago ?></td>
                <td><?= $val->anulado ?></td>
                <td><?= $val->sede ?></td>
                <td><?= $val->nivel ?></td>
                <td><a href="<?= Url::toRoute(["pagosperiodo/editar", "consecutivo" => $val->consecutivo]) ?>" ><img src="svg/si-glyph-document-edit.svg" align="center" width="20px" height="20px" title="Editar"></a></td>                                                
                <td><a href="<?= Url::toRoute(["pagosperiodo/cerrar", "consecutivo" => $val->consecutivo]) ?>"><img src="svg/si-glyph-delete.svg" align="center" width="20px" height="20px" title="Cerrar Pago"></a></td>
            </tr>
            </tbody>
            <?php endforeach; ?>
        </table>
                
        <div class = "form-group" align="left">
            <?= LinkPager::widget(['pagination' => $pagination]) ?>
        </div>
    </div>

