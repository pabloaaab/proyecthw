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
use app\models\FormMatriculados;
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
            $horario = null;
            $dias = null;
            $estado = null;
            if ($form->load(Yii::$app->request->get())) {
                if ($form->validate()) {
                    $nivel = Html::encode($form->nivel);
                    $identificación = Html::encode($form->identificacion);
                    $docente = Html::encode($form->docente);
                    $sede = Html::encode($form->sede);
                    $jornada = Html::encode($form->jornada);
                    $horario = Html::encode($form->horario);
                    $dias = Html::encode($form->dias);
                    $estado = Html::encode($form->estado);
                    $table = Matriculados::find()
                            ->where(['<>', 'estado2', 'ANTERIOR'])
                            ->andFilterWhere(['like', 'nivel', $nivel])
                            ->andFilterWhere(['like', 'identificacion', $identificación])
                            ->andFilterWhere(['like', 'docente', $docente])
                            ->andFilterWhere(['like', 'sede', $sede])
                            ->andFilterWhere(['like', 'tipo_jornada', $jornada])
                            ->andFilterWhere(['=', 'horario', $horario])
                            ->andFilterWhere(['=', 'dias', $dias])
                            ->andFilterWhere(['like', 'estado2', $estado])
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
                if(isset($_POST['excel'])){
                    $table = Matriculados::find()
                            ->where(['<>', 'estado2', 'ANTERIOR'])
                            ->andFilterWhere(['like', 'nivel', $nivel])
                            ->andFilterWhere(['like', 'identificacion', $identificación])
                            ->andFilterWhere(['like', 'docente', $docente])
                            ->andFilterWhere(['like', 'sede', $sede])
                            ->andFilterWhere(['like', 'tipo_jornada', $jornada])
                            ->andFilterWhere(['=', 'horario', $horario])
                            ->andFilterWhere(['=', 'dias', $dias])
                            ->andFilterWhere(['like', 'estado2', $estado])
                            ->orderBy('consecutivo desc')
                            ->all();
                    $this->actionExcel($table);                    
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
                if(isset($_POST['excel'])){                                                           
                    $this->actionExcel($model);                    
                }
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
                    $A1Frances = 0;
                    $A2Frances = 0;
                    $B1Frances = 0;
                    $B2Frances = 0;
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
                        if($val->nivel == "A1 Frances"){
                            $A1Frances++;
                        }
                        if($val->nivel == "A2 Frances"){
                            $A2Frances++;
                        }
                        if($val->nivel == "B1 Frances"){
                            $B1Frances++;
                        }
                        if($val->nivel == "B2 Frances"){
                            $B2Frances++;
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
                $A1Frances = 0;
                $A2Frances = 0;
                $B1Frances = 0;
                $B2Frances = 0;
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
                    if($val->nivel == "A1 Frances"){
                        $A1Frances++;
                    }
                    if($val->nivel == "A2 Frances"){
                        $A2Frances++;
                    }
                    if($val->nivel == "B1 Frances"){
                        $B1Frances++;
                    }
                    if($val->nivel == "B2 Frances"){
                        $B2Frances++;
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
                        'A1Frances' => $A1Frances,
                        'A2Frances' => $A2Frances,
                        'B1Frances' => $B1Frances,
                        'B2Frances' => $B2Frances,
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
                $table->seguro = $model->seguro;
                $table->horario = $_POST['de'].'-'.$_POST['hasta'];
                if (isset($_POST['lunes'])){
                    $lunes = $_POST['lunes'];
                }else{
                    $lunes = '';
                }
                if (isset($_POST['martes'])){
                    $martes = $_POST['martes'];
                }else{
                    $martes = '';
                }
                if (isset($_POST['miercoles'])){
                    $miercoles = $_POST['miercoles'];
                }else{
                    $miercoles = '';
                }
                if (isset($_POST['jueves'])){
                    $jueves = $_POST['jueves'];
                }else{
                    $jueves = '';
                }
                if (isset($_POST['viernes'])){
                    $viernes = $_POST['viernes'];
                }else{
                    $viernes = '';
                }
                if (isset($_POST['sabado'])){
                    $sabado = $_POST['sabado'];
                }else{
                    $sabado = '';
                }
                if (isset($_POST['domingo'])){
                    $domingo = $_POST['domingo'];
                }else{
                    $domingo = '';
                }                
                $table->dias = $lunes.' '.$martes.' '.$miercoles.' '.$jueves.' '.$viernes.' '.$sabado.' '.$domingo;
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
                    $model->seguro = null;
                    $nota = new Notas;
                    $nota->identificacion = $table->identificacion;
                    $nota->matricula = $table->consecutivo;
                    $nota->nivel = $table->nivel;
                    $nota->docente = $table->docente;
                    $nota->tipo_jornada = $table->tipo_jornada;
                    $nota->horario = $table->horario;
                    $nota->dias = $table->dias;
                    $nota->sede = $table->sede;
                    $nota->estado2 = 'ABIERTA';
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
        $model = new FormMatriculados();
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
                    $table->seguro = $model->seguro;
                    $table->horario = $_POST['de'].'-'.$_POST['hasta'];
                    if (isset($_POST['lunes'])){
                    $lunes = $_POST['lunes'];
                }else{
                    $lunes = '';
                }
                if (isset($_POST['martes'])){
                    $martes = $_POST['martes'];
                }else{
                    $martes = '';
                }
                if (isset($_POST['miercoles'])){
                    $miercoles = $_POST['miercoles'];
                }else{
                    $miercoles = '';
                }
                if (isset($_POST['jueves'])){
                    $jueves = $_POST['jueves'];
                }else{
                    $jueves = '';
                }
                if (isset($_POST['viernes'])){
                    $viernes = $_POST['viernes'];
                }else{
                    $viernes = '';
                }
                if (isset($_POST['sabado'])){
                    $sabado = $_POST['sabado'];
                }else{
                    $sabado = '';
                }
                if (isset($_POST['domingo'])){
                    $domingo = $_POST['domingo'];
                }else{
                    $domingo = '';
                }                
                $table->dias = $lunes.' '.$martes.' '.$miercoles.' '.$jueves.' '.$viernes.' '.$sabado.' '.$domingo;
                    if ($table->save(false)) {
                        $nota = Notas::find()->where(['=','matricula',$consecutivo])->one();
                        $nota->sede = $table->sede;
                        $nota->docente = $table->docente;
                        $nota->nivel = $table->nivel;
                        $nota->tipo_jornada = $table->tipo_jornada;
                        $nota->horario = $_POST['de'].'-'.$_POST['hasta'];
                        $nota->dias = $table->dias;
                        $nota->save(false);
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
                $model->consecutivo = $table->consecutivo;
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
                $model->seguro = $table->seguro;
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
                            $nota = Notas::find()->where(['=','matricula',$consecutivo])->one();
                            $nota->estado2 = $table->estado2; 
                            $nota->save(false);
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
                                $nota = Notas::find()->where(['=','matricula',$consecutivo])->one();
                                $nota->estado2 = $table->estado2; 
                                $nota->save(false);
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
    
    public function actionExcel($model) {
        //$costoproducciondiario = CostoProduccionDiaria::find()->all();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Código')
                    ->setCellValue('B1', 'Estudiante')
                    ->setCellValue('C1', 'Nivel')
                    ->setCellValue('D1', 'Fecha Matricula')                                        
                    ->setCellValue('E1', 'Docente')
                    ->setCellValue('F1', 'Sede')
                    ->setCellValue('G1', 'Valor Mensual')
                    ->setCellValue('H1', 'Jornada')
                    ->setCellValue('I1', 'Horario')
                    ->setCellValue('J1', 'Dias')
                    ->setCellValue('K1', 'Estado')
                    ->setCellValue('L1', 'Fecha Cierre')
                    ->setCellValue('M1', 'Fecha Cancelación');

        $i = 2;
        
        foreach ($model as $val) {
            if($val->docente){
                $docente = \app\models\Inscritos::find()->where(['=','identificacion',$val->docente])->one();
                $dato = $docente->nombredocente;
            } else {
                $dato = "Sin definir";
            }
               
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->consecutivo)
                    ->setCellValue('B' . $i, $val->identificacion.' - '.$val->entificacion->nombreEstudiante2)
                    ->setCellValue('C' . $i, $val->nivel)
                    ->setCellValue('D' . $i, $val->fechamat)                    
                    ->setCellValue('E' . $i, $dato)
                    ->setCellValue('F' . $i, $val->sede)
                    ->setCellValue('G' . $i, '$ '.number_format($val->valor_mensual))
                    ->setCellValue('H' . $i, $val->tipo_jornada)                    
                    ->setCellValue('I' . $i, $val->horario)
                    ->setCellValue('J' . $i, $val->dias)
                    ->setCellValue('K' . $i, $val->estado2)
                    ->setCellValue('l' . $i, $val->fecha_cierre)
                    ->setCellValue('m' . $i, $val->fecha_can);                    
                    
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Matriculas');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="matriculas.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        //return $model;
        exit;
        
    }

}
