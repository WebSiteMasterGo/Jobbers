<?php
	session_start();

	define('ACCEDER', 1);
	define('CHECK_MAIL', 2);
	define('ADD', 3);
	define('RESET_PASSWORD', 4);
	define('CHECK_CODE', 5);
	define('EDIT_PASS_FORGOT', 6);
	define('UPDATE_STATE_PLAN', 7);
	define('ADD_PHOTO', 8);
	define('SOCIAL', 9);
	define('ADD_CONTRACT', 10);
	define('UPDATE_PROFILE', 11);

	require_once('../../classes/DatabasePDOInstance.function.php');

	$db = DatabasePDOInstance();

	$op = isset($_REQUEST["op"]) ? $_REQUEST["op"] : false;

	if($op) {
		switch($op) {
			case ACCEDER:
				$usuarioCorreo 	= isset($_REQUEST["usuario"]) 	? $_REQUEST["usuario"] 	: false;
				$clave 			= isset($_REQUEST["clave"]) 	? $_REQUEST["clave"]	: false;
				if($usuarioCorreo && $clave) {
					$info = $db->getRow("
						SELECT
							id,
							uid,
							id_imagen,
							nombre,
							nombre_responsable,
							apellido_responsable,
							sitio_web,
							facebook,
							twitter,
							instagram,
							snapchat,
							suspendido,
							confirmar
						FROM
							empresas
						WHERE
							correo_electronico = '$usuarioCorreo'
						AND
							clave = '$clave'
					");
					
					if($info) {
						if($info["suspendido"] == 1) {
							echo json_encode(array(
							"msg" => "ERROR",
							"data" => "Su cuenta ha sido suspendida."
						));
						} elseif ($info["confirmar"] == 0) {
							echo json_encode(array(
								"msg" => "PEND",
							));
						}
						else {
							$_SESSION["ctc"]["empresa"] = $info;
							$_SESSION["ctc"]["id"] = $info["id"];
							$_SESSION["ctc"]["uid"] = $info["uid"];
							$_SESSION["ctc"]["name"] = $info["nombre"];
							$_SESSION["ctc"]["type"] = 1;

							$_SESSION["ctc"]["plan"] = $db->getRow("SELECT empresas_planes.*, planes.nombre FROM empresas_planes INNER JOIN planes ON planes.id=empresas_planes.id_plan WHERE empresas_planes.id_empresa=$info[id]");;
							$_SESSION["ctc"]["servicio"] = $db->getRow("SELECT empresas_servicios.*, servicios.nombre FROM empresas_servicios INNER JOIN servicios ON servicios.id=empresas_servicios.id_servicio WHERE empresas_servicios.id_empresa=$info[id]");;

							if($info["id_imagen"] != 0) {
								$pic = $db->getOne("SELECT CONCAT(directorio,'/',titulo,'.',extension) FROM imagenes WHERE id=".$info["id_imagen"]);
								$_SESSION["ctc"]["pic"] = $pic;
							}
							else {
								$_SESSION["ctc"]["pic"] = 'avatars/user.png';
							}
							echo json_encode(array(
								"msg" => "OK",
								"data" => $info
							));
						}
					}
					else {
						echo json_encode(array(
							"msg" => "ERROR",
							"data" => "Usuario o contraseña incorrectos."
						));
					}
				}
				break;
			case CHECK_MAIL:
				$exist = $db->getOne("SELECT id FROM empresas WHERE correo_electronico='$_REQUEST[email]'");
				if($exist) {
					echo json_encode(array("status" => 2));
				}
				else {
					echo json_encode(array("status" => 1));
				}
				break;
			case ADD:
				$exist = $db->getOne("SELECT id FROM empresas WHERE correo_electronico='$_REQUEST[email]'");
				if($exist) {
					echo json_encode(array("msg" => 'NO'));
				} else {

					$id = $db->getOne("SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'db678638694' AND TABLE_NAME = 'empresas'");
					$uid = $db->getOne("SELECT valor FROM uid");
					$db->query("INSERT INTO empresas (id, uid, id_imagen, nombre_responsable, apellido_responsable, nombre, razon_social, clave, correo_electronico, telefono, sitio_web, facebook, twitter, instagram, snapchat, fecha_creacion, fecha_actualizacion, cuit) VALUES ('$id', '$uid', '0', '$_REQUEST[name]', '$_REQUEST[lastName]', '$_REQUEST[company]', '$_REQUEST[razon]', '$_REQUEST[password]', '$_REQUEST[email]', '$_REQUEST[phone]', '', '', '', '', '', '".date('Y-m-d h:i:s')."', '".date('Y-m-d h:i:s')."', '$_REQUEST[cuit]')");

					$idE = $db->getInsertID();

					$db->query("UPDATE uid SET valor = (valor + 1) WHERE id = 1");
					$info_plan = $db->getRow("SELECT * FROM planes WHERE id=$_REQUEST[plan]");
					$db->query("INSERT INTO empresas_planes (id_empresa, id_plan, fecha_creacion, fecha_plan, logo_home, link_empresa) VALUES ('$id', '$info_plan[id]', '".date('Y-m-d')."', '".date('Y-m-d')."', '$info_plan[logo_home]', '$info_plan[link_empresa]')");
					$serv = $_REQUEST["serv"] == 0 ? 4 : $_REQUEST["serv"];
					$info_serv = $db->getRow("SELECT * FROM servicios WHERE id=$serv");
					$db->query("INSERT INTO empresas_servicios (id_empresa, fecha_creacion, fecha_servicio, curriculos_disponibles, filtros_personalizados, id_servicio) VALUES ('$id', '".date('Y-m-d')."', '".date('Y-m-d')."', '$info_serv[curriculos_disponibles]', '$info_serv[filtros_personalizados]', '$info_serv[id]')");
					
					$db->query("INSERT INTO empresas_pagos (id_empresa, informacion, plan, servicio, fecha) VALUES ($id, '".(isset($_REQUEST["transaction"]) ? $_REQUEST["transaction"] : 'Plan gratis')."', $_REQUEST[plan], $serv, '".date('Y-m-d')."')");
					
					$info = $db->getRow("
						SELECT
							id,
							uid,
							id_imagen,
							nombre,
							sitio_web,
							facebook,
							twitter,
							instagram,
							snapchat
						FROM
							empresas
						WHERE
							id = $id
					");
					
					/*$_SESSION["ctc"]["empresa"] = $info;
					$_SESSION["ctc"]["id"] = $info["id"];
					$_SESSION["ctc"]["uid"] = $info["uid"];
					$_SESSION["ctc"]["name"] = $info["nombre"];
					$_SESSION["ctc"]["type"] = 1;
					
					$_SESSION["ctc"]["plan"] = $info_plan;
					$_SESSION["ctc"]["servicio"] = $info_serv;
					if($info["id_imagen"] != 0) {
						$pic = $db->getOne("SELECT CONCAT(directorio,'/',titulo,'.',extension) FROM imagenes WHERE id=".$info["id_imagen"]);
						$_SESSION["ctc"]["pic"] = $pic;
					}
					else {
						$_SESSION["ctc"]["pic"] = 'avatars/user.png';
					}*/

					$destinatario = $_REQUEST['email'];
					$asunto = "Confirmación de correo electronico - JOBBERS ARGENTINA";
					$headers = "MIME-Version: 1.0\r\n";
					$headers .= "Content-type: text/html; charset= iso-8859-1\r\n";
					$headers .= "From: Jobbers Argentina < administracion@jobbers.com >\r\n";

					$mensaje = "Saludos $_REQUEST[name],<br><br>";

					$nombre_link = str_replace(array(" ","á","é","í","ó","ú","Á","É","Í","Ó","Ú"),array("%20","a","e","i","o","u","A","E","I","O","U"),$_REQUEST['name']);

					$mensaje .= "Por favor confirma el registro de su empresa en la plataforma de Jobbers Argentina haciendo clic <a href='www.jobbersargentina.com/empresa/bienvenida.php?id=$idE&c=$nombre_link'>aquí</a>. <br><br><br>";

					$enlace = "<a href='www.jobbersargentina.com/empresa/bienvenida.php?id=$idE&c=$nombre_link'>www.jobbersargentina.com/empresa/bienvenida.php?id=$idE&c=$nombre_link</a>";

					$mensaje .= "Si no funciona el enlace anterior puedes acceder a la siguiente URL: $enlace";

					mail($destinatario,$asunto,$mensaje,$headers);

					echo json_encode(array(
						"msg" => "OK",
						"data" => $info
					));

				}
				break;
			case RESET_PASSWORD:
				$exist = $db->getOne("SELECT id FROM empresas WHERE correo_electronico='$_REQUEST[email]'");
				if($exist) {
					$controlador = strtotime(date('Hms'));
					$para = $_REQUEST["email"];
					$asunto = "JOBBERS - Recuperar contraseña";
					$cuerpo= "		
						 <table width=\"620\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\"><tr><td bgcolor=\"#f5f5f5\" style=\"border-style:solid; border-width:1px; border-color:#e1e1e1;\">
						   <table width=\"578\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">
							 <tr>
							   <td height=\"16\"></td>
							 </tr>
							 <tr>
							   <td>
								 JOBBERS
							   </td>
							 </tr>
							 <tr>
							   <td height=\"16\"></td>
							 </tr>

							 <tr>
							   <td align=\"left\" bgcolor=\"#FFFFFF\">
								 <div style=\"border-style:solid; border-width:1px; border-color:#e1e1e1;\">
								   <table width=\"578\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">
									 <tr>
									   <td height=\"22\" colspan=\"3\"></td>
									 </tr>

									 <tr>
									   <td width=\"40\"></td>
									   <td width=\"498\">
										 <div style=\"font-family:arial,Arial,sans-serif; line-height:24px\">
										   <strong>¡HOLA!</strong><br>
										 <p>No hay de qué preocuparse, puedes restablecer tu contraseña de JOBBERS introduciendo el siguiente código:<p>

										 <p>{$controlador}</p>

										 <p>Si no solicitaste el restablecimiento de tu contraseña, puedes borrar este correo y continuar disfrutando de JOBBERS.</p>

										 <p>El equipo JOBBERS</p>

										 </div>
									   </td>
									   <td width=\"40\"></td>
									 </tr>

									 <tr>
									   <td height=\"22\" colspan=\"3\"></td>
									 </tr>
								   </table>
								 </div>
							   </td>
							 </tr>

							 <tr>
							   <td height=\"16\"></td>
							 </tr>
						   </table>
						 </td></tr>
							 <tr>
							   <td align=\"center\">
								 <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">
								   <tr>
									 <td height=\"5\"></td>
								   </tr>
								   <tr>
									 <td>
									   <div style=\"font-family:arial,Arial,sans-serif; font-size:11px; color:#999999; line-height:13px;\">
										COPYRIGHT ® JOBBERS 2017. TODOS LOS DERECHOS RESERVADOS.
									   </div>
									 </td>
								   </tr>
								 </table>
							   </td>
							 </tr>    
					   </table>
									 ";
					$headers_mensaje = 	"From: JOBBERS <administracion@jobbers.com>\r\n" . 
						 "Reply-To: administracion@jobbers.com\r\n" . 
						 "Return-path: administracion@jobbers.com\r\n" . 
						 "MIME-Version: 1.0\n" . 
						 "Content-type: text/html; charset=utf-8";
					if(@mail($para,$asunto,$cuerpo,$headers_mensaje)) {
						$db->query("UPDATE empresas SET codigo_recuperacion='$controlador' WHERE correo_electronico='$_REQUEST[email]'");
						echo json_encode(array("status" => 1));
					}
					else {
						$db->query("UPDATE empresas SET codigo_recuperacion='$controlador' WHERE correo_electronico='$_REQUEST[email]'");
						echo json_encode(array("status" => 1));
					}
				}
				else {
					echo json_encode(array("status" => 2));
				}
				
				break;
			case CHECK_CODE:
				$exist = $db->getOne("SELECT id FROM empresas WHERE correo_electronico='$_REQUEST[email]' AND codigo_recuperacion='$_REQUEST[code]'");
				if($exist) {
					echo json_encode(array("status" => 1));
				}
				else {
					echo json_encode(array("status" => 2));
				}
				break;
			case EDIT_PASS_FORGOT:
				$db->query("UPDATE empresas SET clave='$_REQUEST[p]', fecha_actualizacion='".date('Y-m-d h:i:s')."' WHERE correo_electronico='$_REQUEST[email]'");
				$info = $db->getRow("SELECT * FROM empresas WHERE correo_electronico='$_REQUEST[email]' AND clave='$_REQUEST[p]'");
				$_SESSION["ctc"]["empresa"] = $info;
				$_SESSION["ctc"]["id"] = $info["id"];
				$_SESSION["ctc"]["name"] = $info["nombre"];
				$_SESSION["ctc"]["type"] = 1;

				$_SESSION["ctc"]["plan"] = $db->getRow("SELECT empresas_planes.*, planes.nombre FROM empresas_planes INNER JOIN planes ON planes.id=empresas_planes.id_plan WHERE empresas_planes.id_empresa=$info[id]");;
				$_SESSION["ctc"]["servicio"] = $db->getRow("SELECT empresas_servicios.*, servicios.nombre FROM empresas_servicios INNER JOIN servicios ON servicios.id=empresas_servicios.id_servicio WHERE empresas_servicios.id_empresa=$info[id]");;

				if($info["id_imagen"] != 0) {
					$pic = $db->getOne("SELECT CONCAT(directorio,'/',titulo,'.',extension) FROM imagenes WHERE id=".$info["id_imagen"]);
					$_SESSION["ctc"]["pic"] = $pic;
				}
				else {
					$_SESSION["ctc"]["pic"] = 'avatars/user.png';
				}
				echo json_encode(array("status" => 1));
				break;
			case UPDATE_STATE_PLAN:
				$db->query("UPDATE empresas_planes SET id_plan=1, fecha_plan='".date("Y-m-d")."', logo_home='0', link_empresa='0' WHERE id_empresa=".$_SESSION["ctc"]["id"]);
				$db->query("UPDATE empresas_servicios SET id_servicio=4, fecha_servicio='".date("Y-m-d")."', curriculos_disponibles=0, filtros_personalizados=0 WHERE id_empresa=".$_SESSION["ctc"]["id"]);
				$plan = $db->getRow("SELECT empresas_planes.*, planes.nombre FROM empresas_planes INNER JOIN planes ON planes.id=empresas_planes.id_plan WHERE empresas_planes.id_empresa=".$_SESSION["ctc"]["id"]);
				$_SESSION["ctc"]["plan"] = $plan;
				$servicio = $db->getRow("SELECT empresas_servicios.*, servicios.nombre FROM empresas_servicios INNER JOIN servicios ON servicios.id=empresas_servicios.id_servicio WHERE empresas_servicios.id_empresa=".$_SESSION["ctc"]["id"]);
				$_SESSION["ctc"]["plan"] = $plan;
				$_SESSION["ctc"]["servicio"] = $servicio;
				echo json_encode(array("status" => 1));
				break;
			case ADD_PHOTO:
				$ext = getExtension($_FILES["file"]["name"]);
				$id = $db->getOne("SELECT id_imagen FROM empresas WHERE id=".$_SESSION["ctc"]["id"]);
				if($id > 0) {
					$file = $db->getRow("SELECT directorio, extension FROM imagenes WHERE id=$id");
					if(file_exists("../img/$file[directorio]/$id.$file[extension]")) {
						unlink("../img/$file[directorio]/$id.$file[extension]");
					}
					$db->query("UPDATE extension='$ext', fecha_actualizacion='".date('Y-m-d h:i:s')."' FROM imagenes WHERE id=$id");
				}
				else {
					$id = $db->getOne("SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'db678638694' AND TABLE_NAME = 'imagenes'");
					$db->query("INSERT INTO imagenes (id, titulo, directorio, extension, fecha_creacion, fecha_actualizacion, nombre) VALUES ('$id', '$id', 'profile', '$ext', '".date('Y-m-d h:i:s')."', '".date('Y-m-d h:i:s')."', '$id')");
					$db->query("UPDATE empresas SET id_imagen='$id' WHERE id=".$_SESSION["ctc"]["id"]);
				}
				if(move_uploaded_file($_FILES["file"]["tmp_name"], "../img/profile/$id.$ext")) {
					$t = 1;
					$_SESSION["ctc"]["pic"] = "profile/$id.$ext";
				}
				else{
					$t = 0;
				}
				echo json_encode(array("status" => $t));
				break;
			case SOCIAL:
				$aux = 1;
				switch($_REQUEST["opt"]) {
					case 'activity':
						$db->query("UPDATE empresas SET id_actividad='$_REQUEST[text]' WHERE id=".$_SESSION["ctc"]["id"]);
						break;
					case 'web':
						$db->query("UPDATE empresas SET sitio_web='$_REQUEST[text]' WHERE id=".$_SESSION["ctc"]["id"]);
						break;
					case 'fb':
						$db->query("UPDATE empresas SET facebook='$_REQUEST[text]' WHERE id=".$_SESSION["ctc"]["id"]);
						break;
					case 'tw':
						$db->query("UPDATE empresas SET twitter='$_REQUEST[text]' WHERE id=".$_SESSION["ctc"]["id"]);
						break;
					case 'ins':
						$db->query("UPDATE empresas SET instagram='$_REQUEST[text]' WHERE id=".$_SESSION["ctc"]["id"]);
						break;
					case 'lin':
						$db->query("UPDATE empresas SET linkedin='$_REQUEST[text]' WHERE id=".$_SESSION["ctc"]["id"]);
						break;
					case 'fields':
						$value = str_replace("+", "", $_REQUEST["value"]);
						if($value != "") {
							switch($_REQUEST["option"]) {
								case 'activity':
									$db->query("UPDATE empresas SET id_actividad='$value' WHERE id=".$_SESSION["ctc"]["id"]);
								break;
								case 'web':
									$db->query("UPDATE empresas SET sitio_web='$value' WHERE id=".$_SESSION["ctc"]["id"]);
									break;
								case 'fb':
									$db->query("UPDATE empresas SET facebook='$value' WHERE id=".$_SESSION["ctc"]["id"]);
									break;
								case 'tw':
									$db->query("UPDATE empresas SET twitter='$value' WHERE id=".$_SESSION["ctc"]["id"]);
									break;
								case 'ins':
									$db->query("UPDATE empresas SET instagram='$value' WHERE id=".$_SESSION["ctc"]["id"]);
									break;
								case 'lin':
									$db->query("UPDATE empresas SET linkedin='$value' WHERE id=".$_SESSION["ctc"]["id"]);
									break;
							}
						}
						else {
							$aux = 0;
						}
						break;
				}
				echo json_encode(array("status" => 1, "info" => $aux)); 
				break;
			case ADD_CONTRACT:
				$f = isset($_REQUEST["f"]) ? date('Y-m-d', strtotime($_REQUEST["f"])) : '';
				$id_trabajador = $db->getOne("SELECT id_trabajador FROM postulaciones WHERE id_publicacion=$_REQUEST[ip]");
				$db->query("INSERT INTO empresas_contrataciones (id_empresa, fecha_creacion, id_publicacion, id_trabajador, finalizado) VALUES ('".$_SESSION["ctc"]["id"]."', '".date('Y-m-d')."', '$_REQUEST[ip]', '$id_trabajador', 0)");
				echo json_encode(array("status" => 1)); 
				break;
			case UPDATE_PROFILE:

				$empresa = $_REQUEST['empresa'];
				$nom_responsable = $_REQUEST['nom_responsable'];
				$ape_responsable = $_REQUEST['ape_responsable'];
				$email = $_REQUEST['email'];
				$razon_soc = $_REQUEST['razon_soc'];
				$cuit = $_REQUEST['cuit'];
				$tlf = $_REQUEST['tlf'];

				$hecho = $db->query("UPDATE empresas SET nombre = '".$empresa."', nombre_responsable = '".$nom_responsable."', apellido_responsable = '".$ape_responsable."', correo_electronico = '".$email."', razon_social = '".$razon_soc."', cuit = '".$cuit."', telefono = '".$tlf."' WHERE id = ".$_SESSION['ctc']['id']);

				if ($hecho) {
					echo "Los datos de la empresa se han actualizado satisfactoriamente.";
				} else {
					echo "Ha ocurrido un error de conexión, por favor, vuelve a intentarlo.";
				}

				break;	
		}
	}
	function getExtension($str) {$i=strrpos($str,".");if(!$i){return"";}$l=strlen($str)-$i;$ext=substr($str,$i+1,$l);return $ext;}
?>