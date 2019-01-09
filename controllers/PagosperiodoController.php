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

    public function actionNuevo() {
        $model = new Matriculados;
        $msg = null;
        $tipomsg = null;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $table = new Matriculados;
                $table->identificacion = $model->identificacion;
                $table->fechamat = $model->fechamat;
                $table->acudiente1 = $model->acudiente1;
                $table->observaciones = $model->observaciones;
                $table->nivel = $model->nivel;
                $table->valor_matricula = $model->valor_matricula;
                $table->valor_mensual = $model->valor_mensual;
                $table->docente = $model->docente;
                $table->sede = $model->sede;
                $table->estado2 = "ABIERTA";
                if ($table->insert()) {
                    $msg = "Registros guardados correctamente";
                    $model->identificacion = null;
                    $model->fechamat = null;
                    $model->acudiente1 = null;
                    $model->observaciones = null;
                    $model->nivel = null;
                    $model->valor_matricula = null;
                    $model->valor_mensual = null;
                    $model->docente = null;
                    $model->sede = null;
                    $nota = new Notas;
                    $nota->identificacion = $table->identificacion;
                    $nota->matricula = $table->consecutivo;
                    $nota->insert();
                } else {
                    $msg = "error";
                }
            } else {
                $model->getErrors();
            }
        }

        return $this->render('nuevo', ['model' => $model, 'msg' => $msg, 'tipomsg' => $tipomsg]);
    }

    public function actionEditar($consecutivo) {
        $model = new Matriculados;
        $msg = null;
        $tipomsg = null;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $table = Matriculados::find()->where(['consecutivo' => $consecutivo])->one();
                if ($table) {
                    $table->identificacion = $model->identificacion;
                    $table->fechamat = $model->fechamat;
                    $table->acudiente1 = $model->acudiente1;
                    $table->observaciones = $model->observaciones;
                    $table->nivel = $model->nivel;
                    $table->valor_matricula = $model->valor_matricula;
                    $table->valor_mensual = $model->valor_mensual;
                    $table->docente = $model->docente;
                    $table->sede = $model->sede;
                    if ($table->update()) {
                        $msg = "El registro ha sido actualizado correctamente";
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
            $table = Matriculados::find()->where(['consecutivo' => $consecutivo])->one();
            if ($table) {
                $model->identificacion = $table->identificacion;
                $model->fechamat = $table->fechamat;
                $model->acudiente1 = $table->acudiente1;
                $model->observaciones = $table->observaciones;
                $model->nivel = $table->nivel;
                $model->valor_matricula = $table->valor_matricula;
                $model->valor_mensual = $table->valor_mensual;
                $model->docente = $table->docente;
                $model->sede = $table->sede;
            } else {
                return $this->redirect(["matriculas/index"]);
            }
        } else {
            return $this->redirect(["matriculas/index"]);
        }
        return $this->render("editar", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg]);
    }

    public function actionCancelar($consecutivo) {
        $model = new FormCancelarMatricula;
        $msg = null;
        $tipomsg = null;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $table = Matriculados::find()->where(['consecutivo' => $consecutivo])->one();
                if ($table) {                    
                    if ($table->estado2 == "CANCELADA" OR $table->estado2 == "ABIERTA"){
                        $table->motivo_can = $model->motivo_can;
                        $table->fecha_can = $model->fecha_can;
                        $table->fecha_cierre = $model->fecha_can;
                        $table->estado2 = 'CANCELADA';
                        if ($table->save(false)) {
                            $msg = "El registro ha sido actualizado correctamente";
                        } else {
                            $msg = "El registro no sufrio ningun cambio";
                            $tipomsg = "danger";                        
                        }
                    }else{
                        $msg = "El registro ya fue aprobado, no se puede cancelar";
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
            $table = Matriculados::find()->where(['consecutivo' => $consecutivo])->one();
            if ($table) {
                $model->motivo_can = $table->motivo_can;
                $model->fecha_can = $table->fecha_can;
            } else {
                return $this->redirect(["matriculas/index"]);
            }
        } else {
            return $this->redirect(["matriculas/index"]);
        }
        
        return $this->render("cancelar", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg]);
    }        

}
