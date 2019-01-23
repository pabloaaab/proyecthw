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
use app\models\PagosPeriodo;
use yii\helpers\Url;
use app\models\FormFiltromatriculas;
use app\models\FormCancelarMatricula;
use app\models\FormAprobarMatricula;
use app\models\FormFiltroNiveles;
use yii\web\UploadedFile;

class MatriculasController extends Controller {

    public function actionIndex() {
        if (!Yii::$app->user->isGuest) {
            $form = new FormFiltroMatriculas;
            $nivel = null;
            $identificación = null;
            $docente = null;
            $sede = null;
            $jornada = null;
            if ($form->load(Yii::$app->request->get())) {
                if ($form->validate()) {
                    $nivel = Html::encode($form->nivel);
                    $identificación = Html::encode($form->identificacion);
                    $docente = Html::encode($form->docente);
                    $sede = Html::encode($form->sede);
                    $jornada = Html::encode($form->jornada);
                    $table = Matriculados::find()
                            ->where(['<>', 'estado2', 'ANTERIOR'])
                            ->andFilterWhere(['like', 'nivel', $nivel])
                            ->andFilterWhere(['like', 'identificacion', $identificación])
                            ->andFilterWhere(['like', 'docente', $docente])
                            ->andFilterWhere(['like', 'sede', $sede])
                            ->andFilterWhere(['like', 'tipo_jornada', $jornada])
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
                if(Yii::$app->user->identity->role == 2){
                    $table = Matriculados::find()                        
                        ->orderBy('consecutivo desc'); 
                }else{
                    $table = Matriculados::find()                                                
                        ->where(['=', 'sede', Yii::$app->user->identity->sede])
                        ->orderBy('consecutivo desc');                    
                }
                
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
    
    public function actionNiveles() {
        if (!Yii::$app->user->isGuest) {
            $form = new FormFiltroNiveles;
            $nivel = null;            
            $sede = null;
            if ($form->load(Yii::$app->request->get())) {
                if ($form->validate()) {
                    $nivel = Html::encode($form->nivel);                    
                    $sede = Html::encode($form->sede);
                    $table = Matriculados::find()
                            ->where(['=', 'estado2', 'ABIERTA'])
                            ->andFilterWhere(['like', 'nivel', $nivel])                            
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
                    $A1 = 0;
                    $A2 = 0;
                    $B1 = 0;
                    $B2 = 0;
                    $C1 = 0;
                    $C2 = 0;
                    $pedagogia = 0;
                    $seb = 0;
                    $niveles = Matriculados::find()
                            ->where(['=', 'estado2', 'ABIERTA'])
                            ->andFilterWhere(['like', 'nivel', $nivel])                            
                            ->andFilterWhere(['like', 'sede', $sede])
                            ->orderBy('consecutivo desc')
                            ->all();
                    foreach ($niveles as $val){
                        if($val->nivel == "A1"){
                            $A1++;
                        }
                        if($val->nivel == "A2"){
                            $A2++;
                        }
                        if($val->nivel == "B1"){
                            $B1++;
                        }
                        if($val->nivel == "B2"){
                            $B2++;
                        }
                        if($val->nivel == "C1"){
                            $C1++;
                        }
                        if($val->nivel == "C2"){
                            $C2++;
                        }
                        if($val->nivel == "Pedagogia"){
                            $pedagogia++;
                        }
                        if($val->nivel == "S.E.B"){
                            $seb++;
                        }
                    }
                } else {
                    $form->getErrors();
                }
            } else {
                
                $table = Matriculados::find()
                    ->where(['=', 'estado2', 'ABIERTA'])    
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
                $A1 = 0;
                $A2 = 0;
                $B1 = 0;
                $B2 = 0;
                $C1 = 0;
                $C2 = 0;
                $pedagogia = 0;
                $seb = 0;
                $niveles = Matriculados::find()
                    ->where(['=', 'estado2', 'ABIERTA'])    
                    ->orderBy('consecutivo desc')
                    ->all();                
                foreach ($niveles as $val){
                    if($val->nivel == "A1"){
                        $A1++;
                    }
                    if($val->nivel == "A2"){
                        $A2++;
                    }
                    if($val->nivel == "B1"){
                        $B1++;
                    }
                    if($val->nivel == "B2"){
                        $B2++;
                    }
                    if($val->nivel == "C1"){
                        $C1++;
                    }
                    if($val->nivel == "C2"){
                        $C2++;
                    }
                    if($val->nivel == "Pedagogia"){
                        $pedagogia++;
                    }
                    if($val->nivel == "S.E.B"){
                        $seb++;
                    }
                }
            }
            return $this->render('niveles', [
                        'model' => $model,
                        'form' => $form,
                        'pagination' => $pages,
                        'A1' => $A1,
                        'A2' => $A2,
                        'B1' => $B1,
                        'B2' => $B2,
                        'C1' => $C1,
                        'C2' => $C2,
                        'pedagogia' => $pedagogia,
                        'seb' => $seb,
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
                $table->tipo_jornada = $model->tipo_jornada;
                $table->horario = $model->horario;
                $table->dias = $model->dias;
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
                    //$nota->tipo_jornada = $table->tipo_jornada;
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
                    $table->tipo_jornada = $model->tipo_jornada;
                    $table->horario = $model->horario;
                    $table->dias = $model->dias;
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
                $model->tipo_jornada = $table->tipo_jornada;
                $model->horario = $table->horario;
                $model->dias = $table->dias;
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
                            $pagoperiodo = PagosPeriodo::find()->where(['=','identificacion',$table->identificacion])->all();
                            if ($pagoperiodo){
                                $validar = date('Y-m', strtotime($table->fecha_can));
                                foreach ($pagoperiodo as $val) {
                                    $validar2 = date('Y-m', strtotime($val->mensualidad));
                                    if ($validar2 > $validar){
                                        $val->delete();
                                    }
                                }
                            }                            
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
