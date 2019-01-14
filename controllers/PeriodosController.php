<?php

namespace app\controllers;

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
use app\models\PagosPeriodo;
use yii\helpers\Url;
use app\models\FormFiltroPeriodos;
use app\models\Inscritos;
use app\models\Matriculados;
use yii\web\UploadedFile;

class PeriodosController extends Controller {

    public function actionIndex() {
        if (!Yii::$app->user->isGuest) {
            $form = new FormFiltroPeriodos;            
            $mes = null;            
            if ($form->load(Yii::$app->request->get())) {
                if ($form->validate()) {                    
                    $mes = Html::encode($form->mes);                    
                    $table = PagosPeriodo::find()                            
                            ->andFilterWhere(['like', 'mensualidad', $mes]) 
                            ->groupBy('mensualidad')
                            ->orderBy('consecutivo desc');
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 40,
                        'totalCount' => $count->count()
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                } else {
                    $form->getErrors();
                }
            } else {
                $table = PagosPeriodo::find()                        
                        ->groupBy('mensualidad')
                        ->orderBy('consecutivo desc');
                $count = clone $table;
                $pages = new Pagination([
                    'pageSize' => 100,
                    'totalCount' => $count->count(),
                ]);
                $model = $table
                        ->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();
            }
            return $this->render('index', [
                        'model' => $model,
                        'form' => $form,
                        'pagination' => $pages,
            ]);
        } else {
            return $this->redirect(["periodos/index"]);
        }
    }

    public function actionGenerarperiodo() {
        $matriculasabiertas = Matriculados::find()->where(['=', 'estado2', 'ABIERTA'])->all();
        $mensaje = "";
        if(Yii::$app->request->post()) {
            if (isset($_POST["consecutivo"])) {
                $intIndice = 0;
                foreach ($_POST["consecutivo"] as $intCodigo) {
                    $pagosperiodo = new PagosPeriodo();
                    $mensualidad = date("F-Y", strtotime($_POST["periodo"]));
                    $validar = date('Y-m', strtotime($_POST["periodo"]));                    
                    $estudiante = Matriculados::find()->where(['consecutivo' => $intCodigo])->one();
                    $validar2 = date('Y-m', strtotime($estudiante->fechamat));                    
                    $pagosperiodosgenerados = PagosPeriodo::find()
                        ->where(['=', 'mensualidad', $mensualidad])
                        ->andWhere(['=', 'identificacion', $estudiante->identificacion])
                        ->all();
                    $reg = count($pagosperiodosgenerados);
                    if ($reg == 0) {
                        if ($validar2 <= $validar){
                        $pagosperiodo->identificacion = $estudiante->identificacion;
                        $pagosperiodo->mensualidad = $mensualidad;
                        $pagosperiodo->pago1 = 0;
                        $pagosperiodo->total = $_POST["valor_mensual"][$intIndice];
                        $pagosperiodo->afecta_pago = 0;
                        $pagosperiodo->anulado = 0;
                        $pagosperiodo->sede = $estudiante->sede;
                        $pagosperiodo->matricula = $estudiante->consecutivo;
                        $pagosperiodo->nivel = $estudiante->nivel;
                        $pagosperiodo->insert(false);
                        }
                        
                    }
                    $intIndice++;
                }
                $this->redirect(["periodos/index"]);
            }else{
                $mensaje = "Debe seleccionar al menos un registro";
            }
        }

        return $this->render('generarperiodo', [
            'matriculasabiertas' => $matriculasabiertas,            
            'mensaje' => $mensaje,

        ]);
    }
    
    public function actionGenerarperiodoclonar($mesaclonar) {        
        $pagosperiodosaclonar = PagosPeriodo::find()->where(['=','mensualidad',$mesaclonar])->all();
        $mensaje = "";                
        if(Yii::$app->request->post()) {
            if (isset($_POST["consecutivo"])) {
                $periodo = date("F-Y", strtotime($_POST["periodo"]));
                $intIndice = 0;
                foreach ($_POST["consecutivo"] as $intCodigo) {
                    $pagosperiodo = new PagosPeriodo();                    
                    $pagoaclonar = PagosPeriodo::find()->where(['consecutivo' => $intCodigo])->one();
                    $estudiante = Matriculados::find()->where(['consecutivo' => $pagoaclonar->matricula])->one();
                    $pagosperiodosgenerados = PagosPeriodo::find()
                        ->where(['=', 'mensualidad', $periodo])
                        ->andWhere(['=', 'identificacion', $estudiante->identificacion])
                        ->all();
                    $reg = count($pagosperiodosgenerados);
                    if ($reg == 0) {
                        $pagosperiodo->identificacion = $pagoaclonar->identificacion;
                        $pagosperiodo->mensualidad = $periodo;
                        $pagosperiodo->pago1 = 0;
                        $pagosperiodo->total = $estudiante->valor_mensual;
                        $pagosperiodo->afecta_pago = 0;
                        $pagosperiodo->anulado = 0;
                        $pagosperiodo->sede = $estudiante->sede;
                        $pagosperiodo->matricula = $estudiante->consecutivo;
                        $pagosperiodo->nivel = $estudiante->nivel;
                        $pagosperiodo->insert(false);
                    }
                    $intIndice++;
                }
                $this->redirect(["periodos/index"]);
            }else{
                $mensaje = "Debe seleccionar al menos un registro";
            }
        }

        return $this->render('generarperiodoclonar', [
            'pagosperiodosaclonar' => $pagosperiodosaclonar,            
            'mensaje' => $mensaje,

        ]);
    }

}
