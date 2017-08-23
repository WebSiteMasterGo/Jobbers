<?php
	session_start();
	require_once('../classes/DatabasePDOInstance.function.php');
	$db = DatabasePDOInstance();
	
	$op = isset($_REQUEST["op"]) ? $_REQUEST["op"] : false;
	define('LOGIN', 1);
	define('ADD', 2);
	define('LOGOUT', 3);
	define('EDIT_PASS', 4);
	define('RESET_PASS', 5);
	define('CHECK_CODE', 6);
	define('EDIT_PASS_FORGOT', 7);
	define('SAVE_COMPANY', 8);
	define('LOGIN_FB', 9);
	define('ADD_FB', 10);
	define('LOGIN_ADMIN', 11);
	define('SENT_MAIL', 12);
	switch($op) {
		case LOGIN:
			$info = $db->getRow("SELECT * FROM trabajadores WHERE (correo_electronico='$_REQUEST[email]' OR usuario='$_REQUEST[email]') AND clave='".md5($_REQUEST["password"])."'");
			if($info) {

				$confirmar = $db->getRow("SELECT confirmar FROM trabajadores WHERE correo_electronico='$_REQUEST[email]' OR usuario='$_REQUEST[email]'");


				if ($confirmar['confirmar'] == 1) {
					$_SESSION["ctc"]["id"] = $info["id"];
					$_SESSION["ctc"]["uid"] = $info["uid"];
					$_SESSION["ctc"]["name"] = $info["nombres"];
					$_SESSION["ctc"]["lastName"] = $info["apellidos"];
					$_SESSION["ctc"]["email"] = $info["correo_electronico"];
					$_SESSION["ctc"]["type"] = 2;

					if ($info["nombres"] != "" && $info["apellidos"] != "" && $info["correo_electronico"] != "" && $info["id_imagen"] != "" && $info["id_estado_civil"] != "" && $info["id_tipo_documento_identificacion"] != "" && $info["id_pais"] != "" && $info["provincia"] != "" && $info["localidad"] != "" && $info["calle"] != "" && $info["numero_documento_identificacion"] != "" && $info["fecha_nacimiento"] != "" && $info["telefono"] != "") {
						$_SESSION["ctc"]["postulate"] = 1;
					} else {
						$_SESSION["ctc"]["postulate"] = 0;
					}

					if ($info["id_imagen"] != 0) {
						$pic = $db->getOne("SELECT CONCAT(directorio,'/',titulo,'.',extension) FROM imagenes WHERE id=" . $info["id_imagen"]);
						$_SESSION["ctc"]["pic"] = $pic;
					}
                    else {
                        $_SESSION["ctc"]["pic"] = 'avatars/user.png';
                    }
					echo json_encode(array("status" => 1));
				} else {
					echo json_encode(array("status" => 3));
				}
			}
            else {
				echo json_encode(array("status" => 2));
            }
		break;
         case ADD:
			$id = $db->getOne("SELECT id FROM trabajadores WHERE correo_electronico='$_REQUEST[email]' OR usuario='$_REQUEST[email]'");
			if($id) {
				echo json_encode(array("status" => 2));
			}
			else {
				$id = $db->getOne("SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'db678638694' AND TABLE_NAME = 'trabajadores'");
				$uid = $db->getOne("SELECT valor FROM uid");
				$db->query("INSERT INTO trabajadores (id, uid, id_imagen, id_sexo, id_estado_civil, id_tipo_documento_identificacion, id_pais, provincia, localidad, calle, id_metodo_acceso, nombres, apellidos, numero_documento_identificacion, fecha_nacimiento, telefono, telefono_alternativo, usuario, clave, correo_electronico, fecha_creacion, fecha_actualizacion, publicidad, newsletter) VALUES ('$id', '$uid', '0', '', '', '', '', '', '', '', '', '$_REQUEST[name]', '$_REQUEST[lastName]', '', NULL, '', '',  '$_REQUEST[userName]', '".md5($_REQUEST["password"])."', '$_REQUEST[email]', '".date("Y-m-d h:i:s")."', '".date("Y-m-d h:i:s")."', '$_REQUEST[publicidad]', '$_REQUEST[newsletter]')");

				$idU = $db->getInsertID();

				$db->query("UPDATE uid SET valor = (valor + 1) WHERE id = 1");
				/*$_SESSION["ctc"]["id"] = $id;
				$_SESSION["ctc"]["uid"] = $uid;
				$_SESSION["ctc"]["name"] = $_REQUEST["name"];
				$_SESSION["ctc"]["lastName"] = $_REQUEST["lastName"];
				$_SESSION["ctc"]["email"] = $_REQUEST["email"];
				$_SESSION["ctc"]["type"] = 2;
				$_SESSION["ctc"]["pic"] = 'avatars/user.png';
				$_SESSION["ctc"]["postulate"] = 0;*/

				//$idU = $db->getOne("SELECT id FROM trabajadores ORDER BY id DESC LIMIT 1");

				$destinatario = $_REQUEST['email'];
				$asunto = "Confirmación de correo electronico - JOBBERS ARGENTINA";
				$headers = "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset= iso-8859-1\r\n";
				$headers .= "From: Jobbers Argentina < administracion@jobbers.com >\r\n";

				$mensaje = "Hola $_REQUEST[name],<br><br>";

				$nombre_link = str_replace(array(" ","á","é","í","ó","ú","Á","É","Í","Ó","Ú"),array("%20","a","e","i","o","u","A","E","I","O","U"),$_REQUEST['name']);

				$apellido_link = str_replace(array(" ","á","é","í","ó","ú","Á","É","Í","Ó","Ú"),array("%20","a","e","i","o","u","A","E","I","O","U"),$_REQUEST['lastName']);

				$mensaje .= "Por favor confirma tu registro en la plataforma de Jobbers Argentina haciendo clic <a href='www.jobbersargentina.com/bienvenida.php?id=$idU&n=$nombre_link&a=$apellido_link'>aquí</a>. <br><br><br>";

				$enlace = "<a href='www.jobbersargentina.com/bienvenida.php?id=$idU&n=$nombre_link&a=$apellido_link'>www.jobbersargentina.com/bienvenida.php?id=$idU&n=$nombre_link&a=$apellido_link</a>";

				$mensaje .= "Si no funciona el enlace anterior puedes acceder a la siguiente URL: $enlace";

				$mensaje .= "<br>
				<br>
				<br> Gracias, <br><br> <b>Team JobbersArgentina.</b>";

				# CONDICIONAL PARA VALIDAR SI EL CORREO PERTENECE A "HOTMAIL" U "OUTLOOK"
				if (strstr($destinatario, "hotmail") || strstr($destinatario, "outlook")) {
					$mensaje= "Saludos $_REQUEST[name],<br><br>";

					$nombre_link = str_replace(array(" ","á","é","í","ó","ú","Á","É","Í","Ó","Ú"),array("%20","a","e","i","o","u","A","E","I","O","U"),$_REQUEST['name']);

					$mensaje .= "Por favor confirma el registro de su empresa en la plataforma de Jobbers Argentina copiando la siguiente URL: <b>www.jobbersargentina.com/bienvenida.php?id=$idU&n=$nombre_link&a=$apellido_link </b> y pegandola en su navegador. <br><br><br>";

					$mensaje .= "<br>
					<br>
					<br> Gracias, <br><br> <b>Team JobbersArgentina.</b>";
				}


				if (mail($destinatario,$asunto,$mensaje,$headers)) {
					echo json_encode(array(
						"status" => 1,
					));
				} else {
					echo json_encode(array(
						"status" => 0,
					));
				}
              }
         break;
         case LOGOUT:
               session_unset();
               session_destroy();
              echo json_encode(array("status" => 1));
              break;
         case EDIT_PASS:
		  	$id = $db->getOne("SELECT id FROM trabajadores WHERE clave='".md5($_REQUEST["current"])."' AND id=".$_SESSION["ctc"]["id"]);
			if($id) {
				$db->query("UPDATE trabajadores SET clave='".md5($_REQUEST["p"])."' WHERE id=".$_SESSION["ctc"]["id"]);
				echo json_encode(array("status" => 1));
			}
			else {
				echo json_encode(array("status" => 2));
			}
            break;
		case RESET_PASS:
			//require_once("../vendor/phpmailer/PHPMailerAutoload.php");
			$exist = $db->getOne("SELECT id FROM trabajadores WHERE correo_electronico='$_REQUEST[email]'");
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
				$headers_mensaje = 	"From: jobbers <administracion@jobbers.com>\r\n" . 
					 "Reply-To: administracion@jobbers.com\r\n" . 
					 "Return-path: administracion@jobbers.com\r\n" . 
					 "MIME-Version: 1.0\n" . 
					 "Content-type: text/html; charset=utf-8";
				if(@mail($para,$asunto,$cuerpo,$headers_mensaje)) {
					$db->query("UPDATE trabajadores SET codigo_recuperacion='$controlador' WHERE correo_electronico='$_REQUEST[email]'");
					echo json_encode(array("status" => 1));
				}
				else {
					$db->query("UPDATE trabajadores SET codigo_recuperacion='$controlador' WHERE correo_electronico='$_REQUEST[email]'");
					echo json_encode(array("status" => 1));
				}
			}
			else {
				echo json_encode(array("status" => 2));
			}
			
			break;
		case CHECK_CODE:
			$exist = $db->getOne("SELECT id FROM trabajadores WHERE correo_electronico='$_REQUEST[email]' AND codigo_recuperacion='$_REQUEST[code]'");
			if($exist) {
				echo json_encode(array("status" => 1));
			}
			else {
				echo json_encode(array("status" => 2));
			}
			break;
		case EDIT_PASS_FORGOT:
			$db->query("UPDATE trabajadores SET clave='".md5($_REQUEST["p"])."' WHERE correo_electronico='$_REQUEST[email]'");
			$info = $db->getRow("SELECT * FROM trabajadores WHERE correo_electronico='$_REQUEST[email]' AND clave='".md5($_REQUEST["p"])."'");
		 	$_SESSION["ctc"]["id"] = $info["id"];
			$_SESSION["ctc"]["name"] = $info["nombres"];
			$_SESSION["ctc"]["lastName"] = $info["apellidos"];
			$_SESSION["ctc"]["email"] = $info["correo_electronico"];
			$_SESSION["ctc"]["type"] = 2;
			if($info["id_imagen"] != 0) {
				$pic = $db->getOne("SELECT CONCAT(directorio,'/',titulo,'.',extension) FROM imagenes WHERE id=".$info["id_imagen"]);
				$_SESSION["ctc"]["pic"] = $pic;
			}
			else {
				$_SESSION["ctc"]["pic"] = 'avatars/user.png';
			}
			echo json_encode(array("status" => 1));
			break;
		case SAVE_COMPANY:
			
			break;
		case LOGIN_FB:
			$info = $db->getRow("SELECT * FROM trabajadores WHERE correo_electronico='$_REQUEST[e]'");
			if($info) {
                $_SESSION["ctc"]["id"] = $info["id"];
				$_SESSION["ctc"]["name"] = $info["nombres"];
				$_SESSION["ctc"]["lastName"] = $info["apellidos"];
				$_SESSION["ctc"]["email"] = $info["correo_electronico"];
				$_SESSION["ctc"]["type"] = 2;
				if($info["id_imagen"] != 0) {
					/*$isfb = $db->getOne("SELECT id FROM imagenes WHERE id=".$info["id_imagen"]." AND nombre='$_REQUEST[p]'");
					if($isfb) {
						$pic = $_REQUEST["p"];
					}
					else {
						$pic = $db->getOne("SELECT CONCAT(directorio,'/',titulo,'.',extension) FROM imagenes WHERE id=".$info["id_imagen"]);
					}*/
					$pic = $db->getOne("SELECT CONCAT(directorio,'/',titulo,'.',extension) FROM imagenes WHERE id=".$info["id_imagen"]);
					$_SESSION["ctc"]["pic"] = $pic;
				}
				else {
					$_SESSION["ctc"]["pic"] = 'avatars/user.png';
				}
				echo json_encode(array("status" => 1));
			}
            else {
				echo json_encode(array("status" => 2));
            }
			break;
		case ADD_FB:
			$info = $db->getRow("SELECT * FROM trabajadores WHERE correo_electronico='$_REQUEST[e]'");
			if($info) {
				echo json_encode(array("status" => 2));
			}
			else {
				$id = $db->getOne("SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'db678638694' AND TABLE_NAME = 'trabajadores'");
				$uid = $db->getOne("SELECT valor FROM uid");
				//$idi = $db->getOne("SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'db678638694' AND TABLE_NAME = 'imagenes'");
				$nombres = $_REQUEST['n'];
				$apellidos = $_REQUEST['a'];
				$correo = $_REQUEST['e'];
				$pictureURL = $_REQUEST['p'];
				$genero = $_REQUEST['g'];
				$db->query("INSERT INTO trabajadores (id,uid, id_imagen, id_sexo, id_estado_civil, 
				id_tipo_documento_identificacion, id_pais, provincia, localidad, 
				calle, id_metodo_acceso, nombres, apellidos, numero_documento_identificacion,
				fecha_nacimiento, telefono, telefono_alternativo, usuario, clave, correo_electronico,
				fecha_creacion, fecha_actualizacion) 
				VALUES ('$id','$uid' ,'0', '$genero', '', '', '', '', '', '', '', '$nombres', '$apellidos', '', NULL, '', '',  '', '', '$correo', '".date("Y-m-d h:i:s")."', '".date("Y-m-d h:i:s")."')");
				//$db->query("INSERT INTO imagenes (id, titulo, directorio, extension, fecha_creacion, fecha_actualizacion, nombre) VALUES ($idi, '', '', '', '".date("Y-m-d h:i:s")."', '".date("Y-m-d h:i:s")."', '$_REQUEST[p]')");
				$_SESSION["ctc"]["id"] = $id;
				$_SESSION["ctc"]["name"] = $_REQUEST["n"];
				$_SESSION["ctc"]["lastName"] = $_REQUEST["a"];
				$_SESSION["ctc"]["email"] = $_REQUEST["e"];
				$_SESSION["ctc"]["type"] = 2;
				$_SESSION["ctc"]["pic"] = $_REQUEST["p"];
				echo json_encode(array("status" => 1));
			}
			break;
		case LOGIN_ADMIN:
			$info = $db->getRow("SELECT * FROM usuarios WHERE (correo_electronico='$_REQUEST[usuario]' OR usuario='$_REQUEST[usuario]') AND clave='".$_REQUEST["clave"]."'");
			if($info) {
                $_SESSION["ctc"]["id"] = $info["id"];
				$_SESSION["ctc"]["name"] = $info["nombre"];
				$_SESSION["ctc"]["lastName"] = $info["apellido"];
				$_SESSION["ctc"]["email"] = $info["correo_electronico"];
				$_SESSION["ctc"]["type"] = 3;
				$_SESSION["ctc"]["pic"] = 'avatars/user.png';
				$_SESSION["ctc"]["rol"] = $info["rol"];
				echo json_encode(array("msg" => "OK"));
			}
            else {
				echo json_encode(array("msg" => "ERROR"));
            }
			break;
		case SENT_MAIL:
			$email = $db->getOne("SELECT correo_contacto FROM plataforma WHERE id=1");
			$para = empty($_REQUEST["to"]) ? $email : $_REQUEST["to"];
			$asunto = $_REQUEST["subject"];
			$cuerpo = $_REQUEST['message'];
				$headers_mensaje = 	"From: jobbers <administracion@jobbers.com>\r\n" . 
					 "Reply-To: administracion@jobbers.com\r\n" . 
					 "Return-path: administracion@jobbers.com\r\n" . 
					 "MIME-Version: 1.0\n" . 
					 "Content-type: text/html; charset=utf-8";
				if(@mail($para,$asunto,$cuerpo,$headers_mensaje)) {
					echo json_encode(array("msg" => "OK"));
				}
				else {
					echo json_encode(array("msg" => "ERROR"));
				}
			break;
	}
	
?>