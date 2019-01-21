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
use app\models\Pagos;
use app\models\PagosPeriodo;
use app\models\Notas;
use yii\helpers\Url;
use app\models\FormFiltroPagos;
use app\models\FormPagos;
use app\models\FormPagosAnula;
use app\models\FormPagosPendientes;
use app\models\FormPagosNuevo;
use app\models\Resolucion;
use app\models\Inscritos;
use yii\web\UploadedFile;

class PagosController extends Controller {

    public function actionIndex() {
        if (!Yii::$app->user->isGuest) {
            $form = new FormFiltroPagos;            
            $identificación = null;            
            if ($form->load(Yii::$app->request->get())) {
                if ($form->validate()) {                    
                    $identificación = Html::encode($form->identificacion);                    
                    $table = Pagos::find()
                            ->where(['=', 'tipo_pago', 'mensualidad'])
                            ->andFilterWhere(['like', 'identificacion', $identificación])                            
                            ->orderBy('nropago desc');
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 20,
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
                $table = Pagos::find()
                        ->where(['=', 'tipo_pago', 'mensualidad'])                                                    
                        ->orderBy('nropago desc');
                $count = clone $table;
                $pages = new Pagination([
                    'pageSize' => 20,
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
            return $this->redirect(["pagos/index"]);
        }
    }    

    public function actionAnulacion($nropago) {
        $model = new FormPagosAnula;
        $msg = null;
        $tipomsg = null;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $table = Pagos::find()->where(['nropago' => $nropago])->one();
                if ($table) {                                        
                    $table->anulado = 'SI';
                    $table->fechaanulado = $model->fechaanulado.' '.date("H:i:s");
                    $table->usuarioanula = Yii::$app->user->identity->username;
                    $table->motivo = $model->motivo;                    
                    if ($table->save(false)) {
                        $msg = "El registro ha sido actualizado correctamente";
                        $pagoperiodo = PagosPeriodo::find()->where(['nropago' => $nropago])->one();
                        $pagoperiodo->anulado = 1;
                        $pagoperiodo->fechaanulado = $model->fechaanulado;
                        $pagoperiodo->usuarioanula = Yii::$app->user->identity->username;
                        $pagoperiodo->motivo = $model->motivo;
                        $pagoperiodo->save(false);
                        $nuevopagoperiodo = new PagosPeriodo();
                        $nuevopagoperiodo->identificacion = $pagoperiodo->identificacion;
                        $nuevopagoperiodo->pago1 = 0;
                        $nuevopagoperiodo->total = $pagoperiodo->total;
                        $nuevopagoperiodo->mensualidad = $pagoperiodo->mensualidad;                        
                        $nuevopagoperiodo->usuarioregistra = Yii::$app->user->identity->username;
                        $nuevopagoperiodo->observaciones = $pagoperiodo->observaciones;                        
                        $nuevopagoperiodo->ttpago = $pagoperiodo->ttpago;
                        $nuevopagoperiodo->resolucion = $pagoperiodo->resolucion;                        
                        $nuevopagoperiodo->afecta_pago = 0;
                        $nuevopagoperiodo->sede = $pagoperiodo->sede;
                        $nuevopagoperiodo->nivel = $pagoperiodo->nivel;                        
                        $nuevopagoperiodo->matricula = $pagoperiodo->matricula;
                        $nuevopagoperiodo->insert();
                        return $this->redirect(["pagos/index"]);   
                    } else {
                        $msg = "El registro no sufrio ningun cambio";
                        $tipomsg = "danger";                        
                    }                                        
                } else {
                    $msg = "El registro seleccionado no ha sido encontrado";
                    $tipomsg = "danger";
                }
            } else {
                $model->getErrors();
            }
        }                
        return $this->render("anulacion", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg]);
    }
    
    public function actionPagospendientes() {
        //if (!Yii::$app->user->isGuest) {        
        $form = new FormPagosPendientes;
        $identificacion = null;
        $registro = null;
        if ($form->load(Yii::$app->request->get())) {
            if ($form->validate()) {
                $identificacion = Html::encode($form->identificacion);
                $estudiante = Inscritos::find()->where(['identificacion' => $identificacion])->one();
                $registro = $estudiante->nombreestudiante;
                if ($identificacion){
                    $model = PagosPeriodo::find()->where(['=','identificacion',$identificacion])->andWhere(['=','afecta_pago',0])->andWhere(['=','cerro_grupo',0])->all();
                }else{
                    
                }                
            } else {
                $form->getErrors();
            }
        } else {
            $table = PagosPeriodo::find()->where(['=','identificacion',0])->andWhere(['=','afecta_pago',0])->all();
            $model = $table;            
        }        
        return $this->render('pendientes', [
                    'model' => $model,
                    'form' => $form,
                    'registro' => $registro,                    
        ]);
        /* }else{
          return $this->redirect(["site/login"]);
          } */
    }
    
    public function actionNuevo($consecutivo)
    {
        $model = new FormPagosNuevo;
        $msg = null;
        $tipomsg = null;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $table = PagosPeriodo::find()->where(['consecutivo' => $consecutivo])->one();
                $resolucion = Resolucion::find()->where(['codigo_resolucion_pk' => 1])->one();
                if($table->nropago == ""){
                if ($table) {
                    $pago = new Pagos;                    
                    $pago->identificacion = $model->identificacion;
                    $pago->pago1 = $model->total;
                    $pago->total = $model->total;
                    $pago->mensualidad = $model->mensualidad;
                    $pago->fecha_registro = date('Y-m-d');
                    $pago->usuarioregistra = Yii::$app->user->identity->username;
                    $pago->observaciones = $model->observaciones;
                    $pago->bono = $model->bono;
                    $pago->ttpago = $model->ttpago;
                    $pago->resolucion = $resolucion->resolucion;
                    $pago->tipo_pago = "mensualidad";
                    $pago->afecta_pago = 1;
                    $pago->sede = $table->sede;
                    $pago->save(false);
                    //update pago periodo        
                    $table->nropago = $pago->nropago;
                    $table->identificacion = $pago->identificacion;
                    $table->pago1 = $pago->total;
                    $table->mensualidad = $pago->mensualidad;
                    $table->fecha_registro = $pago->fecha_registro;
                    $table->usuarioregistra = $pago->usuarioregistra;
                    $table->observaciones = $pago->observaciones;
                    $table->bono = $pago->bono;
                    $table->ttpago = $pago->ttpago;
                    $table->resolucion = $pago->resolucion;
                    $table->afecta_pago = $pago->afecta_pago;
                    if ($table->save(false)) {
                        $msg = "El registro ha sido actualizado correctamente";
                        return $this->redirect(["pagos/imprimir",'nropago' => $table->nropago]);                        
                    } else {
                        $msg = "El registro no sufrio ningun cambio";
                        $tipomsg = "danger";
                    }
                } else {
                    $msg = "El registro seleccionado no ha sido encontrado";
                    $tipomsg = "danger";
                }
                } else {
                    $msg = "El pago ya fue generado";
                    $tipomsg = "danger";
                }
            } else {
                $model->getErrors();
            }
        }
        if (Yii::$app->request->get("consecutivo")) {
            $consecutivo = Html::encode($_GET["consecutivo"]);
            $table = PagosPeriodo::find()->where(['consecutivo' => $consecutivo])->one();
            if ($table) {
                $model->identificacion = $table->identificacion;
                $model->total = $table->total;
                $model->mensualidad = $table->mensualidad;                                         
            } else {
                return $this->redirect(["pagos/index"]);
            }
        } else {
            return $this->redirect(["pagos/index"]);
        }
        return $this->render("nuevo", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg]);
    }

    public function actionImprimir($nropago) {
        $model2 = Pagos::find()->where(['nropago' => $nropago])->one();
        $model = Inscritos::find()->where(['identificacion' => $model2->identificacion])->one();
        return $this->render("generarimprimir", ["model" => $model, "model2" => $model2]);
    }

}
