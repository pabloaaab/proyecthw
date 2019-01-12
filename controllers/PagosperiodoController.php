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
use app\models\FormFiltroPagosPeriodo;
use app\models\FormPagosPeriodoCerrar;
use app\models\FormPagosPeriodo;
use yii\web\UploadedFile;

class PagosperiodoController extends Controller {
    
    public function actionIndex() {
        if (!Yii::$app->user->isGuest) {
            $form = new FormFiltroPagosPeriodo;
            $nivel = null;
            $identificación = null;            
            $sede = null;
            $anulado = null;
            $pagado = null;
            $mensualidad = null;
            if ($form->load(Yii::$app->request->get())) {
                if ($form->validate()) {
                    $nivel = Html::encode($form->nivel);
                    $identificación = Html::encode($form->identificacion);                    
                    $sede = Html::encode($form->sede);
                    $anulado = Html::encode($form->anulado);
                    $pagado = Html::encode($form->pagado);
                    $mensualidad = Html::encode($form->mensualidad);
                    $table = PagosPeriodo::find()                            
                            ->Where(['=', 'cerro_grupo', '0'])
                            ->andFilterWhere(['like', 'nivel', $nivel])
                            ->andFilterWhere(['like', 'anulado', $anulado])
                            ->andFilterWhere(['like', 'pagado', $pagado])
                            ->andFilterWhere(['like', 'mensualidad', $mensualidad])
                            ->andFilterWhere(['like', 'identificacion', $identificación])                            
                            ->andFilterWhere(['like', 'sede', $sede])
                            ->orderBy('consecutivo desc');
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 50,
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
                        ->Where(['=', 'cerro_grupo', '0'])
                        ->orderBy('consecutivo desc');
                $count = clone $table;
                $pages = new Pagination([
                    'pageSize' => 30,
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
            return $this->redirect(["site/login"]);
        }
    }    

    public function actionEditar($consecutivo) {
        $model = new FormPagosPeriodo;
        $msg = null;
        $tipomsg = null;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $table = PagosPeriodo::find()->where(['consecutivo' => $consecutivo])->one();
                if ($table) {
                    $table->identificacion = $model->identificacion;
                    $table->sede = $model->sede;
                    $table->mensualidad = $model->mensualidad;
                    $table->nropago = $model->nropago;
                    $table->total = $model->cuota;
                    $table->pago1 = $model->valorpagado;
                    $table->afecta_pago = $model->pagado;                    
                    if ($table->update()) {
                        $msg = "El registro ha sido actualizado correctamente";
                        return $this->redirect(["pagosperiodo/index"]);
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


        if (Yii::$app->request->get("consecutivo")) {
            $consecutivo = Html::encode($_GET["consecutivo"]);
            $table = PagosPeriodo::find()->where(['consecutivo' => $consecutivo])->one();
            if ($table) {
                $model->identificacion = $table->identificacion;
                $model->nropago = $table->nropago;
                $model->sede = $table->sede;
                $model->mensualidad = $table->mensualidad;
                $model->cuota = $table->total;
                $model->valorpagado = $table->pago1;
                $model->pagado = $table->afecta_pago;                               
            } else {
                return $this->redirect(["pagosperiodo/index"]);
            }
        } else {
            return $this->redirect(["pagosperiodo/index"]);
        }
        return $this->render("editar", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg]);
    }

    public function actionCerrar($consecutivo) {
        $model = new FormPagosPeriodoCerrar;
        $msg = null;
        $tipomsg = null;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $table = PagosPeriodo::find()->where(['consecutivo' => $consecutivo])->one();
                if ($table) {                    
                    if ($table->anulado == 0){
                        $table->cerro_grupo = 1;
                        $table->fecha_cerro_grupo = $model->fecha_cerro_grupo;                        
                        if ($table->update()) {
                            $msg = "El registro ha sido actualizado correctamente";
                            return $this->redirect(["pagosperiodo/index"]);
                        } else {
                            $msg = "El registro no sufrio ningun cambio";
                            $tipomsg = "danger";                        
                        }
                    }else{
                        $msg = "El registro se encuentra anulado";
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
        
        return $this->render("cerrar", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg]);
    }        

}
