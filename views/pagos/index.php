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
        ]);
?>

<div class="form-group">
    <?= $f->field($form, "identificacion")->input("search") ?>
</div>

<div class="row" >
    <div class="col-lg-4">
        <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
        <a align="right" href="<?= Url::toRoute("pagos/index") ?>" class="btn btn-primary">Actualizar</a>
    </div>
</div>
<?php $f->end() ?>

<div class="container-fluid">
    <div class="col-lg-2">

    </div>
</div>
<div class = "form-group" align="right">
    <a href="<?= Url::toRoute("pagos/nuevo") ?>" class="btn btn-primary">Nuevo Pago</a>
</div>
<div class="table-condensed">
    <table class="table table-condensed">
        <thead>
            <tr>
                <th scope="col">N° Pago</th>                                
                <th scope="col">Estudiante</th>                
                <th scope="col">Fecha Pago</th>                
                <th scope="col">Tipo Pago</th>                
                <th scope="col">Valor Pago</th>
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
                    <td><?= $val->fecha_registro ?></td>                
                    <td><?= $val->ttpago ?></td>                    
                    <td><?= number_format($val->total) ?></td>
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
        <a href="<?= Url::toRoute("pagos/nuevo") ?>" class="btn btn-primary">Nuevo Pago</a>
    </div>
    <div class = "form-group" align="left">
<?= LinkPager::widget(['pagination' => $pagination]) ?>
    </div>
</div>

