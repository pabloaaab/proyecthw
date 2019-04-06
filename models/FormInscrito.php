<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Inscritos;

/**
 * ContactForm is the model behind the contact form.
 */
class FormInscrito extends Model
{
    public $identificacion;
    public $nombre1;
    public $nombre2;
    public $apellido1;
    public $apellido2;
    public $consecutivo;
    public $nom_madre;
    public $doc_madre;
    public $ocupacion_madre;
    public $nom_padre;
    public $doc_padre;
    public $ocupacion_padre;
    public $a;
    public $tipo_doc;
    public $tipo_personal;
    public $sexo;
    public $telefono;
    public $celular;
    public $email;
    public $direccion;
    public $municipio;
    public $barrio;
    public $comuna;
    public $fecha_nac;
    public $municipio_nac;
    public $departamento_nac;
    public $sede;
    public $estudio1;
    public $estudio2;
    public $gradoc1;
    public $gradoc2;
    public $anioc1;
    public $anioc2;
    public $graduado1;
    public $graduado2;
    public $autoriza;
    public $fecha_autoriza;
    public $ciudad_firma;
    public $lugar_exp;

    public function rules()
    {
        return [

            ['identificacion', 'required', 'message' => 'Campo requerido'],
            ['consecutivo', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
            ['identificacion', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
            ['nombre1', 'required', 'message' => 'Campo requerido'],
            ['nombre1', 'match', 'pattern' => '/^[a-záéíóúñÑ\s]+$/i', 'message' => 'Sólo se aceptan letras'],
            ['nombre2', 'match', 'pattern' => '/^[a-záéíóúñÑ\s]+$/i', 'message' => 'Sólo se aceptan letras'],
            ['apellido1', 'required', 'message' => 'Campo requerido'],
            ['apellido1', 'match', 'pattern' => '/^[a-záéíóúñÑ\s]+$/i', 'message' => 'Sólo se aceptan letras'],
            ['apellido2', 'match', 'pattern' => '/^[a-záéíóúñÑ\s]+$/i', 'message' => 'Sólo se aceptan letras'],
            ['identificacion', 'identificacion_existe'],
            ['nom_madre', 'match', 'pattern' => '/^[a-záéíóúñÑ\s]+$/i', 'message' => 'Sólo se aceptan letras'],
            ['nom_padre', 'match', 'pattern' => '/^[a-záéíóúñÑ\s]+$/i', 'message' => 'Sólo se aceptan letras'],
            ['doc_madre', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
            ['doc_padre', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
            ['ocupacion_madre', 'match', 'pattern' => '/^[a-záéíóúñÑ\s]+$/i', 'message' => 'Sólo se aceptan letras'],
            ['ocupacion_padre', 'match', 'pattern' => '/^[a-záéíóúñÑ\s]+$/i', 'message' => 'Sólo se aceptan letras'],
            ['a', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
            ['tipo_doc', 'required', 'message' => 'Campo requerido'],
            ['tipo_personal', 'required', 'message' => 'Campo requerido'],
            ['lugar_exp', 'required', 'message' => 'Campo requerido'],
            ['telefono', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
            ['celular', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
            ['email', 'email'],
            ['email', 'email_existe'],
            ['direccion', 'default'],
            ['sexo', 'default'],
            ['telefono', 'required', 'message' => 'Campo requerido'],
            ['sexo', 'required', 'message' => 'Campo requerido'],
            ['email', 'required', 'message' => 'Campo requerido'],
            ['celular', 'required', 'message' => 'Campo requerido'],
            ['barrio', 'required', 'message' => 'Campo requerido'],
            ['apellido2', 'required', 'message' => 'Campo requerido'],
            ['estudio1', 'required', 'message' => 'Campo requerido'],
            ['gradoc1', 'required', 'message' => 'Campo requerido'],
            ['anioc1', 'required', 'message' => 'Campo requerido'],
            ['fecha_nac', 'required', 'message' => 'Campo requerido'],
            ['departamento_nac', 'required', 'message' => 'Campo requerido'],
            ['municipio', 'required', 'message' => 'Campo requerido'],
            ['municipio_nac', 'required', 'message' => 'Campo requerido'],
            ['graduado1', 'required', 'message' => 'Campo requerido'],
            ['comuna', 'default'],
            ['barrio', 'default'],
            ['fecha_nac', 'default'],
            ['municipio_nac', 'default'],
            ['departamento_nac', 'default'],
            ['municipio', 'default'],
            ['estudio1', 'default'],
            ['estudio2', 'default'],
            ['gradoc1', 'default'],
            ['gradoc2', 'default'],
            ['anioc1', 'default'],
            ['anioc2', 'default'],
            ['graduado1', 'default'],
            ['graduado2', 'default'],
            ['autoriza', 'default'],
            ['fecha_autoriza', 'default'],
            ['ciudad_firma', 'default'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'identificacion' => 'Identificación:',
            'nombre1' => 'Primer Nombre:',
            'nombre2' => 'Segundo Nombre:',
            'apellido1' => 'Primer Apellido:',
            'apellido2' => 'Segundo Apellido:',
            'consecutivo' => '',
            'nom_madre' => 'Nombre de la Madre:',
            'nom_padre' => 'Nombre del Padre:',
            'doc_madre' => 'Identificación:',
            'doc_padre' => 'Identificación:',
            'ocupacion_madre' => 'Ocupación:',
            'ocupacion_padre' => 'Ocupación:',
            'a' => '',
            'tipo_doc' => 'Tipo Documento:',
            'tipo_personal' => 'Tipo de Personal:',
            'sexo' => 'Sexo:',
            'telefono' => 'Teléfono:',
            'celular' => 'Celular:',
            'email' => 'Email:',
            'direccion' => 'Dirección:',
            'municipio' => 'Sede:',
            'comuna' => 'Comuna Residencia:',
            'barrio' => 'Barrio Residencia:',
            'fecha_nac' => 'Fecha Nacimiento:',
            'municipio_nac' => 'Municipio Nacimiento:',
            'departamento_nac' => 'Departamento Nacimiento:',
            'lugar_exp' => 'Mun. Expedición Identificación:',
            'estudio1' => 'Estudio Realizado:',
            'estudio2' => 'Estudio Realizado:',
            'gradoc1' => 'Grado Cursado:',
            'gradoc2' => 'Grado Cursado:',
            'anioc1' => 'Año:',
            'anioc2' => 'Año:',
            'graduado1' => 'Graduado:',
            'graduado2' => 'Graduado:',
            'autoriza' => 'Autoriza:',
            'fecha_autoriza' => 'Fecha Autorización:',
            'ciudad_firma' => 'Ciudad Firma:',
        ];
    }

    public function identificacion_existe($attribute, $params)
    {
        //Buscar la identificacion en la tabla
        $table = Inscritos::find()->where("identificacion=:identificacion", [":identificacion" => $this->identificacion])->andWhere("consecutivo!=:consecutivo", [':consecutivo' => $this->consecutivo]);
        //Si la identificacion existe mostrar el error
        if ($table->count() == 1)
        {
            $this->addError($attribute, "El número de identificación ya existe".$this->consecutivo);
        }
    }
    public function email_existe($attribute, $params)
    {
        //Buscar el email en la tabla
        $table = Inscritos::find()->where("email=:email", [":email" => $this->email])->andWhere("consecutivo!=:consecutivo", [':consecutivo' => $this->consecutivo]);
        //Si el email existe mostrar el error
        if ($table->count() == 1)
        {
            $this->addError($attribute, "El email ya existe".$this->consecutivo);
        }
    }        
}
