<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;

$this->title = 'Informe Pagos';
?>

<h1>Informe Pagos</h1>
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("informepagos/index"),
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
$sede = ArrayHelper::map(\app\models\Sede::find()->where(['=','estado',1])->all(), 'sede','sede');
$nivel = ArrayHelper::map(\app\models\Nivel::find()->all(), 'nivel','nivel');
?>    
    
<div class="panel panel-primary panel-filters">
    <div class="panel-heading">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtromatriculas">
        <div class="row" >
            <?= $formulario->field($form, "identificacion")->input("search") ?>
            <?= $formulario->field($form, 'nivel')->dropDownList($nivel,['prompt' => 'Seleccione...' ]) ?>
            <?= $formulario->field($form, 'sede')->dropDownList($sede,['prompt' => 'Seleccione...' ]) ?>
            <?= $formulario->field($form,'fechapago')->widget(DatePicker::className(),['name' => 'check_issue_date',
                'value' => date('d-m-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-d',
                    'todayHighlight' => true]]) ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
            <a align="right" href="<?= Url::toRoute("informepagos/index") ?>" class="btn btn-primary">Actualizar</a>
        </div>
    </div>
</div>    
    
<?php $formulario->end() ?>

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
                <th scope="col">Nivel</th>                
                <th scope="col">Observaciones</th>
                <th scope="col">Anulado</th>                                               
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
                    <td><?= $val->nivel ?></td>                    
                    <td><?= $val->observaciones ?></td>
                    <td align="center"><?= $anulado ?></td>                        
                </tr>
            </tbody>
<?php endforeach; ?>
    </table>    
    <div class = "form-group" align="left">
<?= LinkPager::widget(['pagination' => $pagination]) ?>
    </div>
</div>

