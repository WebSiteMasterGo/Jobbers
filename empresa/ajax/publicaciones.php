<?php
	session_start();

	define('AGREGAR', 1);
	define('DETALLES', 2);
	define('MODIFICAR', 3);
	define('ELIMINAR', 4);
	define('CARGAR_TODAS', 5);
	define('OBTENER_POSTULADOS', 6);
	define('AGREGAR_ESPECIAL', 7);
	define('CARGAR_ESPECIAL', 8);
	define('VALIDAR_PUB', 9);

	require_once('../../classes/DatabasePDOInstance.function.php');
	require_once('../../slug.function.php');

	$db = DatabasePDOInstance();

	$op = isset($_REQUEST["op"]) ? $_REQUEST["op"] : false;

	$infoEmpresa = $_SESSION["ctc"]["empresa"];

	if($op) {
		switch($op) {
			case AGREGAR:
				$info = isset($_REQUEST["info"]) ? json_decode($_REQUEST["info"], true) : false;
				if($info) {
					$coordenadas = "";
					if($info["latitud"] != "" && $info["latitud"] != null  && $info["longitud"] != "" && $info["longitud"] != null) {
						$coordenadas = "$info[latitud],$info[longitud]";
					}
					$id = $db->getOne("
						SELECT
							AUTO_INCREMENT
						FROM
							INFORMATION_SCHEMA.TABLES
						WHERE
							TABLE_SCHEMA = 'db678638694'
						AND TABLE_NAME = 'publicaciones'
					");
					
					$db->query("
						INSERT INTO publicaciones (
							id,
							id_empresa,
							titulo,
							descripcion,
							amigable,
							fecha_creacion,
							fecha_actualizacion,
							coordenadas,
							ubicacion
						)
						VALUES
						(
							'$id',
							'$infoEmpresa[id]',
							'$info[titulo]',
							'$info[descripcion]',
							'" . slug($info["titulo"]) . "',
							'" . date('Y-m-d H:i:s') . "',
							'" . date('Y-m-d H:i:s') . "',
							'$coordenadas',
							'$_REQUEST[ubicacion]'
						)
					");
					
					$db->query("
						INSERT INTO publicaciones_sectores (
							id_publicacion,
							id_sector
						)
						VALUES (
							'$id',
							'$info[sector]'
						)
					");
					
					$publicacion = $db->getRow("
						SELECT
							*
						FROM
							publicaciones
						WHERE
							id = $id
					");
					
					echo json_encode(array(
						"msg" => "OK",
						"data" => array(
							"publicacion" => $publicacion
						)
					));
				}
				break;
			case MODIFICAR:
				$id = isset($_REQUEST["i"]) ? json_decode($_REQUEST["i"], true) : false;
				$info = isset($_REQUEST["info"]) ? json_decode($_REQUEST["info"], true) : false;
				if($id && $info) {
					
					$coordenadas = "";
					if($info["latitud"] != "" && $info["latitud"] != null  && $info["longitud"] != "" && $info["longitud"] != null) {
						$coordenadas = "$info[latitud],$info[longitud]";
					}
					
					$db->query("
						UPDATE publicaciones
						SET 
						titulo = '$info[titulo]',
						descripcion = '$info[descripcion]',
						amigable = '" . slug($info["titulo"]) . "',
						 fecha_actualizacion = '" . date('Y-m-d H:i:s') . "',
						 coordenadas='$coordenadas',
						 ubicacion='$_REQUEST[ubicacion]'
						WHERE
							id = $id
					");
					
					$db->query("
						DELETE
						FROM
							publicaciones_sectores
						WHERE
							id_publicacion = $id
					");
					
					$db->query("
						INSERT INTO publicaciones_sectores (
							id_publicacion,
							id_sector
						)
						VALUES (
							'$id',
							'$info[sector]'
						)
					");
					
					$publicacion = $db->getRow("
						SELECT
							*
						FROM
							publicaciones
						WHERE
							id = $id
					");
					
					echo json_encode(array(
						"msg" => "OK",
						"data" => array(
							"publicacion" => $publicacion
						)
					));
				}
				break;
			case ELIMINAR:
				$id = isset($_REQUEST["i"]) ? json_decode($_REQUEST["i"], true) : false;
				if($id) {
					$db->query("
						DELETE
						FROM
							publicaciones
						WHERE
							id = $id
					");
					$db->query("
						DELETE
						FROM
							publicaciones_sectores
						WHERE
							id_publicacion = $id
					");
					echo json_encode(array(
						"msg" => "OK"
					));
				}
				break;
			case CARGAR_TODAS:
				$publicaciones = array();
				$publicaciones["data"] = array();

				$datos = $db->getAll("
					SELECT
						r.*
					FROM
						(
							SELECT
								p.*, a.amigable AS area_amigable,
								asec.amigable AS sector_amigable,
								(
									SELECT
										COUNT(*)
									FROM
										postulaciones
									WHERE
										id_publicacion = p.id
								) AS postulados,
								(
									SELECT
										MAX(fecha_hora)
									FROM
										postulaciones
									WHERE
										id_publicacion = p.id
								) AS ultima_fecha_postulacion
							FROM
								publicaciones AS p
							LEFT JOIN publicaciones_sectores AS ps ON p.id = ps.id_publicacion
							LEFT JOIN areas_sectores AS asec ON ps.id_sector = asec.id
							LEFT JOIN areas AS a ON asec.id_area = a.id
							WHERE
								id_empresa = $infoEmpresa[id]
						) AS r
					ORDER BY
						r.ultima_fecha_postulacion,
						r.postulados DESC
				");

                $plan = $db->getRow("SELECT id_plan FROM empresas_planes WHERE id_empresa=".$_SESSION['ctc']['empresa']['id']);

                if($datos) {
					$datos = array_reverse($datos);					
					foreach($datos as $k => $pub) {
						$pub["link_postulados"] = $pub["postulados"] > 0 ? ('<a class="text-primary" href="javascript: void(0);" data-toggle="modal" data-target="#modal-postulados" data-id="' . $pub["id"] . '"><span class="underline">' . $pub["postulados"] . ' trabajador(es)</span></a>') : "";

						$fecha_creac_pub = date('d/m/Y', strtotime($pub["fecha_creacion"]));
						$fecha_final_pub = '&#x221e;';

						switch ($plan['id_plan']){ // Planes
                            case 1: // Plan Gratis
                                $timestamp_final = strtotime("+15 day", strtotime($pub["fecha_creacion"]));
                                $fecha_final_pub = date('d/m/Y', $timestamp_final);

                                $timestamp_today = strtotime(date('Y-m-d'));

                                if ($timestamp_today <= $timestamp_final) {
                                    $publicaciones["data"][] = array(
                                        $k + 1,
                                        $pub["titulo"],
                                        $pub["descripcion"],
                                        $pub["link_postulados"],
                                        $fecha_creac_pub,
                                        $fecha_final_pub,
                                        '<div class="acciones-publicacion" data-target="' . $pub["id"] . '"> <a class="accion-publicacion btn btn-success waves-effect waves-light" title="Previsualizar publicación" href="../empleos-detalle.php?a=' . $pub["area_amigable"] . '&s=' . $pub["sector_amigable"] . '&p=' . $pub["amigable"] . '" target="_blank"><span class="ti-eye"></span></a> <button type="button" class="accion-publicacion btn btn-primary waves-effect waves-light" onclick="modificarPublicacion(this);" title="Modificar publicación"><span class="ti-pencil"></span></button> <button type="button" class="accion-publicacion btn btn-danger waves-effect waves-light" title="Eliminar publicación" onclick="eliminarPublicacion(this);"><span class="ti-close"></span></button> </div>',
                                    );
                                }
                                break;
                            case 2: // Plan Bronce
                                $timestamp_final = strtotime("+30 day", strtotime($pub["fecha_creacion"]));
                                $fecha_final_pub = date('d/m/Y', $timestamp_final);

                                $timestamp_today = strtotime(date('Y-m-d'));

                                if ($timestamp_today <= $timestamp_final) {
                                    $publicaciones["data"][] = array(
                                        $k + 1,
                                        $pub["titulo"],
                                        $pub["descripcion"],
                                        $pub["link_postulados"],
                                        $fecha_creac_pub,
                                        $fecha_final_pub,
                                        '<div class="acciones-publicacion" data-target="' . $pub["id"] . '"> <a class="accion-publicacion btn btn-success waves-effect waves-light" title="Previsualizar publicación" href="../empleos-detalle.php?a=' . $pub["area_amigable"] . '&s=' . $pub["sector_amigable"] . '&p=' . $pub["amigable"] . '" target="_blank"><span class="ti-eye"></span></a> <button type="button" class="accion-publicacion btn btn-primary waves-effect waves-light" onclick="modificarPublicacion(this);" title="Modificar publicación"><span class="ti-pencil"></span></button> <button type="button" class="accion-publicacion btn btn-danger waves-effect waves-light" title="Eliminar publicación" onclick="eliminarPublicacion(this);"><span class="ti-close"></span></button> </div>',
                                    );
                                }
                                break;
                            case 3: // Plan Plata
                            	$timestamp_final = strtotime("+30 day", strtotime($pub["fecha_creacion"]));
                                $fecha_final_pub = date('d/m/Y', $timestamp_final);

                                $timestamp_today = strtotime(date('Y-m-d'));

                                if ($timestamp_today <= $timestamp_final) {
                                    $publicaciones["data"][] = array(
                                        $k + 1,
                                        $pub["titulo"],
                                        $pub["descripcion"],
                                        $pub["link_postulados"],
                                        $fecha_creac_pub,
                                        $fecha_final_pub,
                                        '<div class="acciones-publicacion" data-target="' . $pub["id"] . '"> <a class="accion-publicacion btn btn-success waves-effect waves-light" title="Previsualizar publicación" href="../empleos-detalle.php?a=' . $pub["area_amigable"] . '&s=' . $pub["sector_amigable"] . '&p=' . $pub["amigable"] . '" target="_blank"><span class="ti-eye"></span></a> <button type="button" class="accion-publicacion btn btn-primary waves-effect waves-light" onclick="modificarPublicacion(this);" title="Modificar publicación"><span class="ti-pencil"></span></button> <button type="button" class="accion-publicacion btn btn-danger waves-effect waves-light" title="Eliminar publicación" onclick="eliminarPublicacion(this);"><span class="ti-close"></span></button> </div>',
                                    );
                                }
                            	break;    
                            default: // Plan Oro
                                $publicaciones["data"][] = array(
                                    $k + 1,
                                    $pub["titulo"],
                                    $pub["descripcion"],
                                    $pub["link_postulados"],
                                    $fecha_creac_pub,
                                    $fecha_final_pub,
                                    '<div class="acciones-publicacion" data-target="' . $pub["id"] . '"> <a class="accion-publicacion btn btn-success waves-effect waves-light" title="Previsualizar publicación" href="../empleos-detalle.php?a=' . $pub["area_amigable"] . '&s=' . $pub["sector_amigable"] . '&p=' . $pub["amigable"] . '" target="_blank"><span class="ti-eye"></span></a> <button type="button" class="accion-publicacion btn btn-primary waves-effect waves-light" onclick="modificarPublicacion(this);" title="Modificar publicación"><span class="ti-pencil"></span></button> <button type="button" class="accion-publicacion btn btn-danger waves-effect waves-light" title="Eliminar publicación" onclick="eliminarPublicacion(this);"><span class="ti-close"></span></button> </div>',
                                );
                                break;
                        }

					}
				}
				
				echo json_encode($publicaciones);
				break;
			case DETALLES:
				$id = isset($_REQUEST["i"]) ? $_REQUEST["i"] : false;
				if($id) {
					$publicacion = $db->getRow("
						SELECT
							p.*, a.id AS area_id,
							s.id AS sector_id
						FROM
							publicaciones AS p
						LEFT JOIN publicaciones_sectores AS ps ON p.id = ps.id_publicacion
						LEFT JOIN areas_sectores AS s ON ps.id_sector = s.id
						LEFT JOIN areas AS a ON s.id_area = a.id
						WHERE
							p.id = $id
					");
					
					echo json_encode(array(
						"msg" => "OK",
						"data" => array(
							"publicacion" => $publicacion
						)
					));
				}
				break;
			case OBTENER_POSTULADOS:
				$id = isset($_REQUEST["i"]) ? $_REQUEST["i"] : false;
				$postulados = array();

				$plan = $db->getRow("SELECT id_plan FROM empresas_planes WHERE id_empresa=".$_SESSION['ctc']['empresa']['id']);

				$limit = '';
				
				switch ($plan['id_plan']) {
					case 1: // Plan Gratis
						$limit = " LIMIT 10";
						break;
					case 2: // Plan Bronce
						$limit = " LIMIT 40";
						break;
					case 3: // Plan Plata
						$limit = " LIMIT 100";
						break;
				}
					
				$datos = $db->getAll("
					SELECT
						tra.id AS trabajador_id,
						tra.uid,
						tra.nombres AS trabajador_nombres,
						tra.apellidos AS trabajador_apellidos,
						a.amigable AS area_amigable,
						ase.amigable AS sector_amigable,
						p.amigable AS publicacion_amigable,
						pos.fecha_hora
					FROM
						postulaciones AS pos
					INNER JOIN trabajadores AS tra ON pos.id_trabajador = tra.id
					INNER JOIN publicaciones AS p ON pos.id_publicacion = p.id
					INNER JOIN publicaciones_sectores AS ps ON p.id = ps.id_publicacion
					INNER JOIN areas_sectores AS ase ON ps.id_sector = ase.id
					INNER JOIN areas AS a ON ase.id_area = a.id
					WHERE p.id = $id $limit
				");

				/*if($datos) {
					foreach($datos as $k => $fila) {
						$fila["fecha_hora_formateada"] = date('d/m/Y h:i:s A', strtotime($fila["fecha_hora"]));
						$postulados[] = array(
							$k + 1,
							'<a href="../trabajador-detalle.php?t=' . $fila["trabajador_id"] . '" target="_blank">' . "$fila[trabajador_nombres] $fila[trabajador_apellidos]" . '</a>',
							"$fila[fecha_hora_formateada]",
							'<div class="acciones-publicacion" data-target="' . $fila["trabajador_id"] . '"> <a class="accion-publicacion btn btn-success waves-effect waves-light" href="contratar-trabajador.php?a=' . $fila["area_amigable"] . '&s=' . $fila["sector_amigable"] . '&p=' . $fila["publicacion_amigable"] . '&t=' . slug("$fila[trabajador_nombres] $fila[trabajador_apellidos]-$fila[trabajador_id]") . '" title="Contratar trabajador"><span class="ti-check"></span> Contratar</a> </div>',
						);
					}
				}*/
				if($datos) {
					foreach($datos as $k => $fila) {
						$fila["fecha_hora_formateada"] = date('d/m/Y h:i:s A', strtotime($fila["fecha_hora"]));
						$postulados[] = array(
							$k + 1,
							'<a href="../trabajador-detalle.php?t=' . $fila["trabajador_id"] . '" target="_blank">' . "$fila[trabajador_nombres] $fila[trabajador_apellidos]" . '</a>',
							"$fila[fecha_hora_formateada]",
							'<div class="acciones-publicacion" data-target="' . $fila["trabajador_id"] . '"> <a class="accion-publicacion contactJobber btn btn-success waves-effect waves-light" href="javascript:void(0)" title="Contactar jobber" data-id="' . $fila["uid"] . '" data-toggle="modal" data-target="#contactM" onclick="callEvent(this)"><span class="ti-comment-alt"></span> Contactar</a> </div>',
						);
					}
				}

				echo json_encode(array(
					"data" => $postulados
				));
				break;
			case AGREGAR_ESPECIAL:
				if(isset($_REQUEST["edit"])) {
					if(isset($_REQUEST["video"])) {
						$id_imagen = $db->getOne("SELECT id_imagen FROM empresas_publicaciones_especiales WHERE id_empresa=".$_SESSION["ctc"]["id"]);
						if($id_imagen != "") {
							$imagen = $db->getOne("SELECT CONCAT(directorio,'/',nombre,'.',extension) FROM imagenes WHERE id=$id_imagen");
							if(file_exists("../img/$imagen")) {
								unlink("../img/$imagen");
							}
							$db->query("DELETE FROM imagenes WHERE id=$id_imagen");
						}
						$db->query("UPDATE empresas_publicaciones_especiales SET titulo='$_REQUEST[titulo]', url='$_REQUEST[url]'");
						$t = 1;
					}
					else {
						$ext = getExtension($_FILES["file"]["name"]);
						$id_imagen = $db->getOne("SELECT id_imagen FROM empresas_publicaciones_especiales WHERE id_empresa=".$_SESSION["ctc"]["id"]);
						$imagen = $db->getOne("SELECT CONCAT(directorio,'/',nombre,'.',extension) FROM imagenes WHERE id=$id_imagen");
						if(file_exists("../img/$imagen")) {
							unlink("../img/$imagen");
						}
						$db->query("INSERT INTO empresas_publicaciones_especiales (id, id_empresa, tipo, titulo, descripcion, id_imagen, enlace, fecha_creacion) VALUES ($id, '".$_SESSION["ctc"]["id"]."', 1, '$_REQUEST[titulo]', '$_REQUEST[descripcion]', $id_imagen, NULL, '".date('Y-m-d')."');");
						if(move_uploaded_file($_FILES["file"]["tmp_name"], "../img/product/$id_imagen.$ext")) {
							$db->query("UPDATE empresas_publicaciones_especiales SET extension='$ext', fecha_actualizacion='".date('Y-m-d h:i:s')."' WHERE id=$id_imagen");
							$t = 1;
						}
						else {
							$t = 2;
						}
					}
				}
				else {
					$id = $db->getOne("SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'db678638694' AND TABLE_NAME = 'empresas_publicaciones_especiales'");
					if(isset($_REQUEST["video"])) {
						$db->query("INSERT INTO empresas_publicaciones_especiales (id, id_empresa, tipo, titulo, descripcion, id_imagen, enlace, fecha_creacion) VALUES ($id, '".$_SESSION["ctc"]["id"]."', 2, '$_REQUEST[titulo]', '', 0, '$_REQUEST[url]', '".date('Y-m-d')."');");
						$t = 1;
					}
					else {
						$ext = getExtension($_FILES["file"]["name"]);
						$id_imagen = $db->getOne("SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'db678638694' AND TABLE_NAME = 'imagenes'");
						$db->query("INSERT INTO empresas_publicaciones_especiales (id, id_empresa, tipo, titulo, descripcion, id_imagen, enlace) VALUES ($id, '".$_SESSION["ctc"]["id"]."', 1, '$_REQUEST[titulo]', '$_REQUEST[descripcion]', $id_imagen, NULL);");
						if(move_uploaded_file($_FILES["file"]["tmp_name"], "../img/product/$id_imagen.$ext")) {
							$db->query("INSERT INTO imagenes (id, titulo, directorio, extension, fecha_creacion, fecha_actualizacion, nombre) VALUES ('$id_imagen', '$id_imagen', 'product', '$ext', '".date('Y-m-d h:i:s')."', '".date('Y-m-d h:i:s')."', '$id_imagen')");
							$t = 1;
						}
						else {
							$t = 2;
						}
					}
				}
				echo json_encode(array("status" => $t));
				break;
			case CARGAR_ESPECIAL:
				$info = $db->getRow("SELECT * FROM empresas_publicaciones_especiales WHERE id_empresa=".$_SESSION["ctc"]["id"]);
				if($info) {
					$info["imagen"] = $db->getOne("SELECT CONCAT(directorio,'/',nombre,'.',extension) FROM imagenes WHERE id=$info[id_imagen]");
					echo json_encode(array("msg" => "OK", "info" => $info));
				}
				else {
					echo json_encode(array("msg" => "ERROR"));
				}
				break;
			case VALIDAR_PUB:
				$info = $db->getRow("SELECT id_plan FROM empresas_planes WHERE id_empresa = ".$_SESSION['ctc']['id']);
				if ($info['id_plan'] != 1){
					echo json_encode(array("msg" => "OK"));
				} else {
					$pub = $db->getRow("SELECT COUNT(*) AS pub FROM publicaciones WHERE id_empresa=".$_SESSION['ctc']['id']);
					
					if ($pub['pub'] >= 2){
						echo json_encode(array("msg" => "NO"));
					} else {
						echo json_encode(array("msg" => "OK"));
					}
				}
				break;
		}
	}
	function getExtension($str) {$i=strrpos($str,".");if(!$i){return"";}$l=strlen($str)-$i;$ext=substr($str,$i+1,$l);return $ext;}
?>