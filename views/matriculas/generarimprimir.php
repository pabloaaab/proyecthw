<?php
$this->title = 'Imprimir Matricula';
?>
	
<body onload='window.print()'>
    <font face="Arial" size = "4.5">
<table width="96%" height="96%" border="0" align="center">
  <tr>
    <td width="141" rowspan="6"><img src="images/logo.png" width="162" height="200" /></td>
    <td height="30" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td width="793" height="30" align="center"><strong>LIBRO DE MATRICULAS </strong></td>
  </tr>
  <tr>
    <td height="30" align="center" valign="middle"><strong>ESCUELA DE IDIOMAS HEADWAY </strong></td>
  </tr>
  <tr>
    <td height="30" align="center"><strong>INSTITUCI&Oacute;N DE EDUCACI&Oacute;N PARA EL TRABAJO Y EL DESARROLLO HUMANO </strong></td>
  </tr>
  <tr>
    <td height="30" align="center"><span class="Estilo2">Licencia de funcionamiento N&#176 201750009079 de 2017, 460 de 2018 y Registro de programa N&#176 9274 de 2016, 016512 de 2016, 018089 de 2016 y 1177 de 2018, 420 de 2014, 034 de 2016 </span></td>
  </tr>
  <tr>
    <td height="30">&nbsp;</td>
  </tr>
</table>

<table width="96%" height="96%" border="1" align="center" bordercolor="#000000">
  <tr>
    <td height="30" colspan="12" align="center"><strong>DATOS PERSONALES </strong></td>
  </tr>
  <tr align="center">
    <td colspan="3"><strong>Nombres y Apellidos del Alumno </strong></td>
    <td colspan="3"><strong>Fecha de Nacimiento </strong></td>
    <td colspan="2"><strong>Lugar de Nacimiento </strong></td>
    <td colspan="2"><strong>Documento de Identidad </strong></td>
    <td colspan="2"><strong>Lugar de Expedici&oacute;n </strong></td>
  </tr>
  <tr align="center">
    <td colspan="3" rowspan="2"><?= $model->nombre1. ' ' .$model->nombre2. ' ' .$model->apellido1. ' '. $model->apellido2  ?></td>
    <td width="102" height="30"><strong>A&ntilde;o</strong></td>
    <td width="35" height="30"><strong>Mes</strong></td>
    <td width="64" height="30"><strong>D&iacute;a</strong></td>
    <td width="60" height="30"><strong>Municipio</strong></td>
    <td width="134" height="30"><strong>Departamento</strong></td>
    <td colspan="2" rowspan="2"><?= $model->identificacion ?></td>
    <td colspan="2" rowspan="2"><?= $model->lugar_exp ?></td>
  </tr>
  <tr align="center">
    <td height="30" colspan="3"><?= $model->fecha_nac ?></td>
    <td height="30"><?= $model->municipio_nac ?></td>
    <td height="30"><?= $model->departamento_nac ?></td>
  </tr>
  <tr align="center">
    <td height="30" colspan="5"><strong>Direcci&oacute;n de Residencia </strong></td>
    <td height="30" colspan="3"><strong>Barrio</strong></td>
    <td colspan="2"><strong>Comuna</strong></td>
    <td colspan="2"><strong>Tel&eacute;fono</strong></td>
  </tr>
  <tr align="center">
    <td height="30" colspan="5"><?= $model->direccion ?></td>
    <td height="30" colspan="3"><?= $model->barrio ?></td>
    <td height="30" colspan="2"><?= $model->comuna ?></td>
    <td height="30" colspan="2"><?= $model->telefono ?></td>
  </tr>
  <tr align="center">
    <td height="30" colspan="5"><strong>Seguro </strong></td>
    <td height="30" colspan="7"><?= $model2->seguro ?></td>    
  </tr>
  <tr>
  <tr>
    <td height="30" colspan="12" align="center"><strong>DATOS FAMILIARES </strong></td>
  </tr>
  <tr align="center">
    <td colspan="3"><strong>Nombres y Apellidos de la Madre </strong></td>
    <td><strong>N&deg; Documento </strong></td>
    <td colspan="2"><strong>Ocupaci&oacute;n</strong></td>
    <td colspan="3"><strong>Nombres y Apellidos del Padre </strong></td>
    <td width="154"><strong>N&deg; Documento </strong></td>
    <td colspan="2"><strong>Ocupaci&oacute;n</strong></td>
  </tr>
  <tr align="center">
    <td height="30" colspan="3"><?= $model->nom_madre ?></td>
    <td height="30"><?= $model->doc_madre ?></td>
    <td height="30" colspan="2"><?= $model->ocupacion_madre ?></td>
    <td height="30" colspan="3"><?= $model->nom_padre ?></td>
    <td height="30"><?= $model->doc_padre ?></td>
    <td height="30" colspan="2"><?= $model->ocupacion_padre ?></td>
  </tr>
  <tr>
    <td height="20" colspan="12">&nbsp;</td>
  </tr>
  <tr align="center">
    <td height="30" colspan="7"><strong>&Uuml;ltimos Estudios Realizados </strong></td>
    <td height="30" colspan="2"><strong>Grado Cursado </strong></td>
    <td height="30"><strong>A&ntilde;o</strong></td>
    <td height="30" colspan="2"><strong>Graduado</strong></td>
  </tr>
  <tr align="center">
    <td height="30" colspan="7"><?= $model->estudio1 ?></td>
    <td height="30" colspan="2"><?= $model->gradoc1 ?></td>
    <td height="30"><?= $model->anioc1 ?></td>
    <td height="30" colspan="2"><?= $model->graduado1 ?></td>
  </tr>
  <tr align="center">
    <td height="30" colspan="7"><?= $model->estudio2 ?></td>
    <td height="30" colspan="2"><?= $model->gradoc2 ?></td>
    <td height="30"><?= $model->anioc2 ?></td>
    <td height="30" colspan="2"><?= $model->graduado2 ?></td>
  </tr>
  <tr>
    <td height="20" colspan="12">&nbsp;</td>
  </tr>
  <tr>
    <td height="30" colspan="12"><strong>Compromiso: </strong>Los firmantes aceptamos el proyecto Educativo Institucional y el Manual de Convivencia </td>
  </tr>
  <tr>
    <td height="20" colspan="12">&nbsp;</td>
  </tr>
  <tr align="center">
    <td height="30" colspan="4"><strong>Matricula y Renovaci&oacute;n </strong></td>
    <td height="30" colspan="2" rowspan="2"><strong>Programa</strong></td>
    <td height="30" colspan="3" rowspan="2"><strong>Acudiente, en caso de que lo requiera </strong></td>
    <td height="30" colspan="2" rowspan="2"><strong>Firma Estudiante</strong></td>
    <td width="134" height="30" rowspan="2"><strong>Firma Director </strong></td>
  </tr>
  <tr align="center">
    <td width="60" height="30"><strong>A&ntilde;o</strong></td>
    <td width="76" height="30"><strong>Mes</strong></td>
    <td width="74" height="30"><strong>D&iacute;a</strong></td>
    <td height="30"><strong>Grado</strong></td>
  </tr>
  <tr align="center">
    <td height="30" colspan="3"><?= $model2->fechamat ?></td>
    <td height="30"></td>
    <td height="30" colspan="2"><?= $model2->nivel ?></td>
    <td height="30" colspan="3"><?php if ($model->firmaacudiente != "") {?><img src="../images/<?php echo $rg['firmaacudiente'];?>" width="80" height="40" /><?php }?></td>
    <td height="30" colspan="2"><?php if ($model->firma != "") {?><img src="../images/<?php echo $model->firma;?>" width="80" height="40" /><?php }?></td>
    <td height="30"><img src="../images/firma_ever.PNG" width="80" height="40" /></td>
  </tr>
  <tr align="center">
    <td height="30" colspan="3"><?= $model2->fecha_ren2 ?></td>
    <td height="30">&nbsp;</td>
    <td height="30" colspan="2"><?= $model2->nivel ?></td>
    <td height="30" colspan="3"></td>
    <td height="30" colspan="2"></td>
    <td height="30"></td>
  </tr>
  <tr align="center">
    <td height="30" colspan="3"></td>
    <td height="30">&nbsp;</td>
    <td height="30" colspan="2">&nbsp;</td>
    <td height="30" colspan="3"></td>
    <td height="30" colspan="2"></td>
    <td height="30">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" colspan="12">&nbsp;</td>
  </tr>
  <tr>
    <td height="30" colspan="12"><strong>Cancelaci&oacute;n de Matricula </strong></td>
  </tr>
  <tr valign="middle">
    <td height="30" align="center"><strong>A&ntilde;o</strong></td>
    <td height="30" align="center"><strong>Mes</strong></td>
    <td height="30" align="center"><strong>D&iacute;a</strong></td>
    <td height="30" colspan="6" align="center"><strong>Motivo</strong></td>
    <td height="30" colspan="2" align="center"><strong>Firma Estudiante </strong></td>
    <td height="30" align="center"><strong>Firma Director </strong></td>
  </tr>
  <tr align="center">
    <td height="30" colspan="3" align="center"><?= $model2->fecha_can ?></td>
    <td height="30" colspan="6"><?= $model2->motivo_can ?></td>
    <td height="30" colspan="2" align="center"><?php if($model2->estado2 == "Cancelado" && $model2->motivo_can != "" && $model->firma != "") { echo "<img src='../images/$model->firma' width='80' height='40'/>";}?></td>
    <td height="30" align="center"><?php if($model2->estado2 == "Cerro grupo" && $model2->motivo_can != "") { echo "<img src='../images/firma_ever.PNG' width='80' height='40'/>";}?></td>
  </tr>
  <tr>
    <td height="30" colspan="12"><strong>Observaciones:</strong></td>
  </tr>
  <tr>
    <td height="25" colspan="12"><p><?php echo $model2->observaciones;?></p>
    </td>
  </tr>
</table>
</body>

</html>
