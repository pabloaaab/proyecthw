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
use app\models\Notas;
use yii\helpers\Url;
use app\models\FormFiltroPagosOtros;
use app\models\FormOtrosPagos;
use app\models\FormOtrosPagosAnula;
use app\models\Resolucion;
use app\models\Inscritos;
use yii\web\UploadedFile;

class PagosotrosController extends Controller {

    public function actionIndex() {
        if (!Yii::$app->user->isGuest) {
            $form = new FormFiltroPagosOtros;            
            $identificación = null;            
            if ($form->load(Yii::$app->request->get())) {
                if ($form->validate()) {                    
                    $identificación = Html::encode($form->identificacion);                    
                    $table = Pagos::find()
                            ->where(['=', 'tipo_pago', 'otros'])
                            ->andFilterWhere(['like', 'identificacion', $identificación])                            
                            ->orderBy('nropago desc');
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 15,
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
                        ->where(['=', 'tipo_pago', 'otros'])                                                    
                        ->orderBy('nropago desc');
                $count = clone $table;
                $pages = new Pagination([
                    'pageSize' => 15,
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
            return $this->redirect(["pagosotros/index"]);
        }
    }

    public function actionNuevo() {
        $model = new FormOtrosPagos;
        $msg = null;
        $tipomsg = null;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $resolucion = Resolucion::find()->where(['codigo_resolucion_pk' => 1])->one();
                $sedeinscrito = Inscritos::find()->where(['=','identificacion',$model->identificacion])->one();
                $table = new Pagos;
                $table->identificacion = $model->identificacion;
                $table->mensualidad = $model->mensualidad;
                $table->pago1 = $model->pago1;
                $table->total = $table->pago1;
                $table->fecha_registro = date("Y-m-d");
                $table->usuarioregistra = Yii::$app->user->identity->username;
                $table->observaciones = $model->observaciones;
                $table->anulado = '';
                $table->ttpago = $model->ttpago;
                $table->resolucion = $resolucion->resolucion;
                $table->tipo_pago = "otros";
                $table->sede = $sedeinscrito->municipio;
                if ($table->insert()) {
                    $msg = "Registros guardados correctamente";
                    return $this->redirect(["pagosotros/index"]);                    
                } else {
                    $msg = "error";
                }
            } else {
                $model->getErrors();
            }
        }

        return $this->render('nuevo', ['model' => $model, 'msg' => $msg, 'tipomsg' => $tipomsg]);
    }

    public function actionAnulacion($nropago) {
        $model = new FormOtrosPagosAnula;
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
                    $table->anulado = 'Si';
                    $table->fechaanulado = $model->fechaanulado.' '.date("H:i:s");
                    $table->usuarioanula = Yii::$app->user->identity->username;
                    $table->motivo = $model->motivo;                    
                    if ($table->save(false)) {
                        $msg = "El registro ha sido actualizado correctamente";
                        return $this->redirect(["pagosotros/index"]);   
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

    public function actionImprimir($nropago) {
        $model2 = Pagos::find()->where(['nropago' => $nropago])->one();
        $model = Inscritos::find()->where(['identificacion' => $model2->identificacion])->one();
        return $this->render("generarimprimir", ["model" => $model, "model2" => $model2]);
    }

}
