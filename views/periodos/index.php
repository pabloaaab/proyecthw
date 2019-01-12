<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use app\models\PagosPeriodo;
use yii\helpers\ArrayHelper;

$this->title = 'Periodos';
?>

<h1>Periodos</h1>
<?php
$f = ActiveForm::begin([
            "method" => "get",
            "action" => Url::toRoute("periodos/index"),            
            "enableClientValidation" => true,
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                            'template' => '{label}<div class="col-sm-11 form-group">{input}{error}</div>',
                            'labelOptions' => ['class' => 'col-sm-1 control-label'],
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
            <?php
            $periodos = PagosPeriodo::find()
                    ->groupBy('mensualidad')
                    ->orderBy('nropago desc')
                    ->all();
            ;
            $periodos = ArrayHelper::map($periodos, "mensualidad", "mensualidad");
            ?>
            <?= $f->field($form, 'mes')->dropDownList($periodos, ['prompt' => 'Seleccione...']) ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
            <a align="right" href="<?= Url::toRoute("periodos/index") ?>" class="btn btn-primary">Actualizar</a>
        </div>
    </div>
</div>

<?php $f->end() ?>

<div class="container-fluid">
    <div class="col-lg-2">

    </div>
</div>
<div class = "form-group" align="right">
    <a href="<?= Url::toRoute("periodos/generarperiodo") ?>" class="btn btn-primary">Generar Periodo</a>
</div>
<div class="table-condensed">
    <table class="table table-condensed">
        <thead>
            <tr>
                <th scope="col">N°</th>                                
                <th scope="col">Mes</th>                                
                <th scope="col">N° Registros</th>                
                <th scope="col"></th>                                
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach ($model as $val): ?>
                <?php $registros = PagosPeriodo::find()->Where(['mensualidad' => $val->mensualidad])->count();   ?>
                <tr>                                        
                    <th scope="row"><?= $i; ?></th>                
                    <td><?= $val->mensualidad ?></td>                                                
                    <td><?= $registros ?></td>                                    
                    <td><a href="<?= Url::toRoute(["periodos/generarperiodoclonar", "mesaclonar" => $val->mensualidad]) ?>"><img src="svg/si-glyph-plus.svg" align="center" width="20px" height="20px" title="Clonar"></a></td>
                </tr>
            </tbody>
            <?php $i = $i + 1; ?>
        <?php endforeach; ?>
    </table>

    <div class = "form-group" align="right">
        <a href="<?= Url::toRoute("periodos/generarperiodo") ?>" class="btn btn-primary">Generar Periodo</a>
    </div>
    <div class = "form-group" align="left">
        <?= LinkPager::widget(['pagination' => $pagination]) ?>
    </div>
</div>

