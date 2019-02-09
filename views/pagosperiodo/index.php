<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;

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

<?php
if (Yii::$app->user->identity->role == 2){
    $sede = ArrayHelper::map(\app\models\Sede::find()->where(['=','estado',1])->all(), 'sede','sede');
}else{
    $sede = ArrayHelper::map(\app\models\Sede::find()->where(['=','sede',Yii::$app->user->identity->sede])->all(), 'sede','sede');
}
$nivel = ArrayHelper::map(\app\models\Nivel::find()->all(), 'nivel','nivel');

?>    
    
<div class="panel panel-primary panel-filters">
    <div class="panel-heading">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtropagosperiodo">
        <div class="row" >
            <?= $formulario->field($form, "identificacion")->input("search") ?>            
            <?php if (Yii::$app->user->identity->role == 2){ ?>
                <?= $formulario->field($form, 'sede')->dropDownList($sede,['prompt' => 'Seleccione...' ]) ?> 
            <?php }else{ ?>
                <?= $formulario->field($form, 'sede')->dropDownList($sede) ?>
            <?php } ?>
            <?php
            $periodos = \app\models\PagosPeriodo::find()
                    ->groupBy('mensualidad')
                    ->orderBy('nropago desc')
                    ->all();
            ;
            $periodos = ArrayHelper::map($periodos, "mensualidad", "mensualidad");
            ?>
            <?= $formulario->field($form, 'mensualidad')->dropDownList($periodos, ['prompt' => 'Seleccione...']) ?>
            <?= $formulario->field($form, 'anulado')->dropDownList(['1' => 'SI', '0' => 'NO'],['prompt' => 'Seleccione...' ]) ?>
            <?= $formulario->field($form, 'pagado')->dropDownList(['1' => 'SI', '0' => 'NO'],['prompt' => 'Seleccione...' ]) ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
            <a align="right" href="<?= Url::toRoute("pagosperiodo/index") ?>" class="btn btn-primary">Actualizar</a>
        </div>
    </div>
</div>    
    
<?php $formulario->end() ?>

    <!-- Trigger the modal with a button -->

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Informe</h4>
      </div>
      <div class="modal-body">          
        <p class="alert-danger">Total Pagos Generados por Cancelar: <?= $totaldeudagenerada ?></p>
        <p class="alert-danger">Total Pagos Pendientes por Cancelar: <?= $totaldeuda ?></p>                
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>
    
    <div class="alert alert-info">Registros: <?= $pagination->totalCount ?> <a class="btn btn-info" data-toggle="modal" data-target="#myModal">Ver informe</a></div>
    
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
                <?php if ($val->anulado == 1) { $anulado = "SI"; }else { $anulado = "NO"; } ?>
                <?php if ($val->afecta_pago == 1) { $pagado = "SI"; }else { $pagado = "NO"; } ?>                
                <th scope="row"><?= $val->consecutivo ?></th>                
                <td><?= $val->nropago ?></td>                                
                <td><?= $val->mensualidad ?></td>
                <td><?= $val->identificacion.' - '.$val->nombres ?></td>
                <td><?= "$ ".number_format($val->total) ?></td>
                <td><?= "$ ".number_format($val->pago1) ?></td>
                <td><?= $pagado ?></td>
                <td><?= $anulado ?></td>
                <td><?= $val->sede ?></td>
                <td><?= $val->nivel ?></td>
                <?php if (Yii::$app->user->identity->role == 2){ ?>
                <?php if ($val->anulado == 0) { ?>
                <td><a href="<?= Url::toRoute(["pagosperiodo/editar", "consecutivo" => $val->consecutivo]) ?>" ><img src="svg/si-glyph-document-edit.svg" align="center" width="20px" height="20px" title="Editar"></a></td>                                                
                <?php } else { ?>
                <td></td>
                <?php } ?>
                <td><a href="<?= Url::toRoute(["pagosperiodo/cerrar", "consecutivo" => $val->consecutivo]) ?>"><img src="svg/si-glyph-delete.svg" align="center" width="20px" height="20px" title="Cerrar Pago"></a></td>
                <?php } ?>
            </tr>
            </tbody>
            <?php endforeach; ?>
        </table>
                
        <div class = "form-group" align="left">
            <?= LinkPager::widget(['pagination' => $pagination]) ?>
        </div>
    </div>

<?php
$form = ActiveForm::begin([
            "method" => "post",
            'id' => 'formulario',
            'enableClientValidation' => false,
            'enableAjaxValidation' => true,
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
                'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                'labelOptions' => ['class' => 'col-sm-2 control-label'],
                'options' => []
            ],
        ]);
?>
<div class="panel-footer text-right">
    <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> excel", ["class" => "btn btn-primary", 'name' => 'excel', 'value' => 1]) ?>        
</div>

<?php $formulario->end() ?>