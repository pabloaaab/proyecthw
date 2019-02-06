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
        $nivel = null;
        $tipo_jornada = null;
        $docente = null;
        $horario = null;
        $dias = null;
        $count = 0;
        $count2 = 0;
        $nf = 0;
        $observaciones = '';
        $error = 0;
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $identificacion = Html::encode($form->identificacion);
                $nivel = Html::encode($form->nivel);
                $tipo_jornada = Html::encode($form->tipo_jornada);
                $docente = Html::encode($form->docente);
                $horario = Html::encode($form->horario);
                $dias = Html::encode($form->dias);
                
                //$model = Notas::find()->Where(['<>','matricula',0])->orwhere(['identificacion' => $identificacion])->all();
                $model = Notas::find()->where(['<>', 'matricula', 0])
                                      ->andFilterWhere(['like', 'identificacion', $identificacion])
                                      ->andFilterWhere(['like', 'nivel', $nivel])
                                      ->andFilterWhere(['like', 'docente', $docente])
                                      ->andFilterWhere(['like', 'tipo_jornada', $tipo_jornada])
                                      ->andFilterWhere(['like', 'horario', $horario])
                                      ->andFilterWhere(['like', 'dias', $dias]);  
                                      $count2 = clone $model;
                    $pages = new Pagination([
                        'pageSize' => 40,
                        'totalCount' => $count2->count()
                    ]);
                    $model = $model
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                $count = count($model);
                              
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
                    if ($table->nota1 > 5 or $table->nota2 > 5 or $table->nota3 > 5 or $table->nota4 > 5){
                        Yii::$app->getSession()->setFlash('warning', 'Las calificaciones estan en una escala de 0 a 5, verificar las notas del registro '.$intCodigo);
                        $error = 1;
                    }else{
                        //$nf = $table->nota1 + $table->nota2 + $table->nota3 + $table->nota4 / 4;
                        if ($table->nota1 >= 3 && $table->nota2 >= 3 && $table->nota3 >= 3 && $table->nota4 >= 3){
                            $observaciones = 'Aprobó el nivel';
                        }else{
                            $observaciones = '';
                        }
                    }
                    if ($error == 0){
                        $table->observaciones = $observaciones;
                        $table->save(false);
                    }                    
                $intIndice++;
            }            
        }    
        //$model = Notas::find()->where(['identificacion' => $identificacion])->andWhere(['<>','matricula',0])->all();    
        $model = Notas::find()->where(['<>', 'matricula', 0])
                                          ->andFilterWhere(['like', 'identificacion', $identificacion])
                                          ->andFilterWhere(['like', 'nivel', $nivel])
                                          ->andFilterWhere(['like', 'docente', $docente])
                                          ->andFilterWhere(['like', 'tipo_jornada', $tipo_jornada])
                                          ->andFilterWhere(['like', 'horario', $horario])
                                          ->andFilterWhere(['like', 'dias', $dias]);
                                          $count = clone $model;
                    $pages = new Pagination([
                        'pageSize' => 30,
                        'totalCount' => $count->count()
                    ]);
                    $model = $model
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();                
        } else {
            $model = Notas::find()->where(['identificacion' => 00])->andWhere(['<>','matricula',0]);
            $count = clone $model;
                    $pages = new Pagination([
                        'pageSize' => 30,
                        'totalCount' => $count->count()
                    ]);
                    $model = $model
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();                        
        }
        
        return $this->render('notas', [
                    'model' => $model,
                    'form' => $form,                    
                    'pagination' => $pages,
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
