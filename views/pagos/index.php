<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Pagos Mensualidad';
?>

<h1>Pagos Mensualidad</h1>
<?php
$f = ActiveForm::begin([
            "method" => "get",
            "action" => Url::toRoute("pagos/index"),
            "enableClientValidation" => true,
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                            'template' => '{label}<div class="col-sm-10 form-group">{input}{error}</div>',
                            'labelOptions' => ['class' => 'col-sm-2 control-label'],
                            'options' => []
                        ],
]);
?>
<div class="panel panel-primary panel-filters">
    <div class="panel-heading">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtromatriculas">
        <div class="row" >
            <?= $f->field($form, "identificacion")->input("search") ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
            <a align="right" href="<?= Url::toRoute("pagos/index") ?>" class="btn btn-primary">Actualizar</a>
        </div>
    </div>
</div>
<?php $f->end() ?>

<div class = "form-group" align="right">
    <a href="<?= Url::toRoute("pagos/pagospendientes") ?>" class="btn btn-primary" target="_blank">Nuevo Pago</a>
</div>

<div class="alert alert-info">Registros: <?= $pagination->totalCount ?></div>

<div class="table-condensed">
    <table class="table table-condensed">
        <thead>
            <tr>
                <th scope="col">N° Pago</th>                                
                <th scope="col">Estudiante</th>                                                
                <th scope="col">Pago</th>
                <th scope="col">Tipo Pago</th>                
                <th scope="col">Valor Pago</th>
                <th scope="col">Fecha Pago</th>
                <th scope="col">Observaciones</th>
                <th scope="col">Anulado</th>                
                <th scope="col"></th>
                <th scope="col"></th>                
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model as $val): ?>
                <tr>
                    <?php if ($val->anulado == "") {
                        $anulado = "NO";
                    } else {
                        $anulado = "SI";
                    } ?>
                    <th scope="row"><?= $val->nropago ?></th>                
                    <td><?= $val->entificacion->nombreestudiante ?></td>                                                                    
                    <td><?= $val->mensualidad ?></td>
                    <td><?= $val->ttpago ?></td>                    
                    <td><?= number_format($val->total) ?></td>
                    <td><?= $val->fecha_registro ?></td>
                    <td><?= $val->observaciones ?></td>
                    <td align="center"><?= $anulado ?></td>
                    <td><a href="<?= Url::toRoute(["pagos/imprimir", "nropago" => $val->nropago]) ?>" target="_blank"><img src="svg/si-glyph-print.svg" align="center" width="20px" height="20px" title="Imprimir"></a></td>
                    <?php if ($anulado == "SI") { ?>
                        <td></td>
                    <?php } else { ?>
                        <td><a href="<?= Url::toRoute(["pagos/anulacion", "nropago" => $val->nropago]) ?>"><img src="svg/si-glyph-delete.svg" align="center" width="20px" height="20px" title="Anular"></a></td>
                    <?php } ?>    
                </tr>
            </tbody>
<?php endforeach; ?>
    </table>

    <div class = "form-group" align="right">
        <a href="<?= Url::toRoute("pagos/pagospendientes") ?>" class="btn btn-primary">Nuevo Pago</a>
    </div>
    <div class = "form-group" align="left">
<?= LinkPager::widget(['pagination' => $pagination]) ?>
    </div>
</div>

