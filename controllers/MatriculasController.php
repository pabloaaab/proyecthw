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
use app\models\Matriculados;
use app\models\Notas;
use yii\helpers\Url;
use app\models\FormFiltromatriculas;
use app\models\FormCancelarMatricula;
use app\models\FormAprobarMatricula;
use yii\web\UploadedFile;

class MatriculasController extends Controller {

    public function actionIndex() {
        if (!Yii::$app->user->isGuest) {
            $form = new FormFiltroMatriculas;
            $nivel = null;
            $identificación = null;
            $docente = null;
            $sede = null;
            if ($form->load(Yii::$app->request->get())) {
                if ($form->validate()) {
                    $nivel = Html::encode($form->nivel);
                    $identificación = Html::encode($form->identificacion);
                    $docente = Html::encode($form->docente);
                    $sede = Html::encode($form->sede);
                    $table = Matriculados::find()
                            ->where(['<>', 'estado2', 'ANTERIOR'])
                            ->andFilterWhere(['like', 'nivel', $nivel])
                            ->andFilterWhere(['like', 'identificacion', $identificación])
                            ->andFilterWhere(['like', 'docente', $docente])
                            ->andFilterWhere(['like', 'sede', $sede])
                            ->orderBy('consecutivo desc');
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
                $table = Matriculados::find()
                        ->where(['<>', 'estado2', 'ANTERIOR'])
                        ->orderBy('consecutivo desc');
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
    
    public function actionAprobar($consecutivo) {
        $model = new FormAprobarMatricula;
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
                    if ($table->estado2 == "APROBADA" OR $table->estado2 == "ABIERTA"){
                        $table->observaciones = $model->observaciones;                        
                        $table->fecha_cierre = $model->fecha_cierre;
                        $table->estado2 = 'APROBADA';
                        $nota = Notas::find()->where(['matricula' => $consecutivo])->andWhere(['identificacion' => $table->identificacion])->one();
                        if ($nota->observaciones != "Aprobó el nivel"){
                            $msg = "El estudiante no registra notas o aun no ha aprobado el nivel, revisar las notas";
                            $tipomsg = "danger";
                        }else{
                            if ($table->save(false)) {
                                $msg = "El registro ha sido actualizado correctamente";
                            } else {
                                $msg = "El registro no sufrio ningun cambio";
                                $tipomsg = "danger";                        
                            }
                        }
                        
                    }else{
                        $msg = "El registro fue cancelado, no se puede aprobar";
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
                $model->observaciones = $table->observaciones;
                $model->fecha_cierre = $table->fecha_cierre;
            } else {
                return $this->redirect(["matriculas/index"]);
            }
        } else {
            return $this->redirect(["matriculas/index"]);
        }
        
        return $this->render("aprobar", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg]);
    }

    public function actionImprimir($consecutivo) {
        $model2 = Matriculados::find()->where(['consecutivo' => $consecutivo])->one();
        $model = \app\models\Inscritos::find()->where(['identificacion' => $model2->identificacion])->one();
        return $this->render("generarimprimir", ["model" => $model, "model2" => $model2]);
    }

}
