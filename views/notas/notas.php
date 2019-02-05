<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use kartik\select2\Select2;

$this->title = 'Notas';

?>

<h1>Notas</h1>
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("notas/notas"),
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
    <div class="panel-body" id="nuevopago">
        <div class="row" >
            <?= $formulario->field($form, "identificacion")->input("search") ?>                        
        </div>
        <div class="panel-footer text-right">            
            <?= Html::submitButton("Buscar", ["class" => "btn btn-primary",]) ?>
            <a align="right" href="<?= Url::toRoute("notas/notas") ?>" class="btn btn-primary"> Actualizar</a>            
        </div>
    </div>
</div>

<?php $formulario->end() ?>

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

<div class="table-responsive">
<div class="panel panel-primary ">
    <div class="panel-heading">
        Registros: <?= $count ?>
    </div>
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Consecutivo</th>                                                                                                
                <th scope="col">identificacion</th>                
                <th scope="col">Docente</th>
                <th scope="col">Listening</th>
                <th scope="col">Reading</th>
                <th scope="col">Speaking</th>
                <th scope="col">Writing</th>                
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col">Observaciones</th>
                <th scope="col"></th>                
            </tr>
            </thead>
            <tbody>                           
            <?php foreach ($model as $val): ?>
            <tr>                                
                <td><?= $val->consecutivo ?></td>
                <td><?= $val->identificacion ?></td>
                <td><?= $val->docente ?></td>
                <td><input type="text" name="n1[]" value="<?= $val->nota1 ?>" size="2px"></td>
                <td><input type="text" name="n2[]" value="<?= $val->nota2 ?>" size="2px"></td>
                <td><input type="text" name="n3[]" value="<?= $val->nota3 ?>" size="2px"></td>
                <td><input type="text" name="n4[]" value="<?= $val->nota4 ?>" size="2px"></td>                
                <td><input type="text" name='nf[]' value="<?= $val->nf ?>" size="2px"></td>
                <td><input type="text" name='nfp[]' value="<?= $val->nfp ?>" size="2px"></td>
                <td><?= $val->observaciones ?></td>                
                <td></td>
                <td><input type="hidden" name="consecutivo[]" value="<?= $val->consecutivo ?>" size="2px"></td>
            </tr>
            </tbody>
            <?php endforeach; ?>
        </table>        
    </div>
    <div class="panel-footer text-right">        
        <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Actualizar", ["class" => "btn btn-success",]) ?>
    </div>
</div>

<?php $form->end() ?>






<script>
function vall()
{
	 var totalitem = document.getElementById("totalr").value;
         for (i=1;i<=totalitem;i++)
             { 
				if (document.getElementById("n1[" + i + "]").value >5)
				{
					alert('Las calificaciones estan en una escala de 0 a 5');
					document.getElementById("n1[" + i + "]").focus;
					document.getElementById("n1[" + i + "]").value = '';
				}
				if (document.getElementById("n2[" + i + "]").value >5)
				{
					alert('Las calificaciones estan en una escala de 0 a 5');
					document.getElementById("n2[" + i + "]").focus;
					document.getElementById("n2[" + i + "]").value = '';
				}
				if (document.getElementById("n3[" + i + "]").value >5)
				{
					alert('Las calificaciones estan en una escala de 0 a 5');
					document.getElementById("n3[" + i + "]").focus;
					document.getElementById("n3[" + i + "]").value = '';
				}
				if (document.getElementById("n4[" + i + "]").value >5)
				{
					alert('Las calificaciones estan en una escala de 0 a 5');
					document.getElementById("n4[" + i + "]").focus;
					document.getElementById("n4[" + i + "]").value = '';
				}
				if (document.getElementById("n1[" + i + "]").value == '' && document.getElementById("n2[" + i + "]").value == '' && document.getElementById("n3[" + i + "]").value == '' && document.getElementById("n4[" + i + "]").value == '')
				{
					document.getElementById("nfp[" + i + "]").value = 0;
					document.getElementById("nf[" + i + "]").value = 0;
				}
			}
}
function ActualizarSaldo2()
       {
         var suma1 = 0; var suma2 = 0; var suma3 = 0; var suma4 = 0;
         var t1 = 0; var t2 = 0; var t3 = 0; var t4 = 0;
         var tt = 0;
         var totalitem = document.getElementById("totalr").value;
         for (j=1;j<=totalitem;j++)
             { 
				suma1= document.getElementById("n1[" + j + "]").value;
				suma2= document.getElementById("n2[" + j + "]").value;
				suma3= document.getElementById("n3[" + j + "]").value;
				suma4= document.getElementById("n4[" + j + "]").value;
				
				if (document.getElementById("n1[" + j + "]").value !== '' || document.getElementById("n2[" + j + "]").value !== '' || document.getElementById("n3[" + j + "]").value !== '' || document.getElementById("n4[" + j + "]").value !== '')
                {
						
						if (suma1 != '' && suma2 != '' && suma3 != '' && suma4 != '')
						{
						   //document.getElementById("nfp[" + j + "]").value = 100;
						   tt = (parseFloat(suma1)+parseFloat(suma2)+parseFloat(suma3)+parseFloat(suma4))/4;
						   //document.getElementById("nf[" + j + "]").value = tt.toFixed(1);
						   if (suma1 >= 3 && suma2 >= 3 && suma3 >= 3 && suma4 >= 3)
						   {
								document.getElementById("observaciones[" + j + "]").value = "Aprobó el nivel";
						   }
						   else
						   {
								document.getElementById("observaciones[" + j + "]").value = "";
						   }
						}
						
						
                }
             }

       }   
</script>