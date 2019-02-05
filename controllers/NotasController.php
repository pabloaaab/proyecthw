<?php

namespace app\controllers;

use app\models\Notas;
use Codeception\Lib\HelperModule;
use yii;
use yii\base\Model;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Session;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use moonland\phpexcel\Excel;
use app\models\FormNotas;

class NotasController extends Controller {

    public function actionNotas() {               
        $form = new FormNotas;
        $identificacion = null;
        $count = 0;        
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $identificacion = Html::encode($form->identificacion);                                
                if ($identificacion){
                    $model = Notas::find()->where(['identificacion' => $identificacion])->andWhere(['<>','matricula',0])->all();
                    $count = count($model);
                }               
            } else {
                $form->getErrors();
            }
        if (isset($_POST["consecutivo"])) {
            $intIndice = 0;
            foreach ($_POST["consecutivo"] as $intCodigo) {                
                    $table = Notas::findOne($intCodigo);
                    $table->nota1 = $_POST["n1"][$intIndice];
                    $table->nota2 = $_POST["n2"][$intIndice];
                    $table->nota3 = $_POST["n3"][$intIndice];
                    $table->nota4 = $_POST["n4"][$intIndice];
                    $table->save(false);
                $intIndice++;
            }            
        }    
        $model = Notas::find()->where(['identificacion' => $identificacion])->andWhere(['<>','matricula',0])->all();    
        } else {
            $model = Notas::find()->where(['identificacion' => 00])->andWhere(['<>','matricula',0])->all();
            $count = count($model);
        }
        
        return $this->render('notas', [
                    'model' => $model,
                    'form' => $form,
                    'count' => $count,
        ]);        
    }
    
    public function actionModificarnotas($id) {                        
        if (isset($_POST["consecutivo"])) {
            $intIndice = 0;
            foreach ($_POST["consecutivo"] as $intCodigo) {                
                    $table = Notas::findOne($intCodigo);
                    $table->nota1 = $_POST["nota1"][$intIndice];
                    $table->nota2 = $_POST["nota2"][$intIndice];
                    $table->nota3 = $_POST["nota3"][$intIndice];
                    $table->nota4 = $_POST["nota4"][$intIndice];
                    
                
                $intIndice++;
            }

        }
        
        return $this->render('notas', [
                    'costolaboral' => $costolaboral,
                    'costolaboraldetalle' => $costolaboraldetalle,
        ]);        
    }
        
}
