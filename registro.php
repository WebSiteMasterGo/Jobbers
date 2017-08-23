<?php
	require_once('classes/DatabasePDOInstance.function.php');
	$db = DatabasePDOInstance();
	$info = $db->getRow("SELECT politicas, terminos FROM plataforma WHERE id=1");
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- Title -->
		<title>JOBBERS - Registrarse</title>

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="vendor/themify-icons/themify-icons.css">
		<link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="vendor/sweetalert2/sweetalert2.min.css">

		<!-- Neptune CSS -->
		<link rel="stylesheet" href="css/core.css">

		<!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>

	<body class="img-cover" style="background-image: url(img/OB82100.jpg);">


	<script>
		window.fbAsyncInit = function() {
			FB.init({
				appId      : '1785394951770843',
				status	   : true,
				xfbml      : true,
				version    : 'v2.9'
			});
			FB.AppEvents.logPageView();
		};

		(function(d, s, id){
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) {return;}
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/sdk.js";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));

	</script>

		<div class="container-fluid">
			<div class="sign-form">
				<div class="row">
					<div class="col-md-4 offset-md-4 p-x-3">
						<div class="box b-a-0">
							<div style="text-align: center;margin: ;">
								<a href="./"><img src="img/logo_d.png" style="height: 50px; margin-top: 20px;"></a>
							</div>
							<div class="p-a-2 text-xs-center">
								<h5>Únete a nosotros</h5>
							</div>
							<form class="form-material">
								<div class="form-group">
									<input type="text" class="form-control" id="name" placeholder="Nombres (*)">
								</div>
								<div class="form-group">
									<input type="text" class="form-control" id="lastName" placeholder="Apellidos (*)">
								</div>
								<div class="form-group">
									<input type="text" class="form-control" id="userName" placeholder="Usuario (*)">
								</div>
								<div class="form-group">
									<input type="email" class="form-control" id="email" placeholder="Correo electrónico (*)">
								</div>
								<div class="form-group">
									<input type="password" class="form-control" id="passw1" placeholder="Contraseña (*)">
								</div>
								<div class="form-group">
									<input type="password" class="form-control" id="passw2" placeholder="Confirmar contraseña (*)">
								</div>
								<label class="custom-control custom-checkbox" style="margin-left: 20px;display: inline-flex;">
									<input class="custom-control-input" type="checkbox" id="aceptaCondiciones">
									<span class="custom-control-indicator"></span>
									<span class="custom-control-description">Acepto <a href="#" data-toggle="modal" data-target=".large-modal">Términos y condiciones</a> y <a href="#"  data-toggle="modal" data-target=".large-modal2">Políticas de Privacidad</a>. *</span>
								</label>
								<br>
								<label class="custom-control custom-checkbox" id="aceptaPublicidad" style="margin-left: 20px;display: inline-flex;">
									<input class="custom-control-input" type="checkbox">
									<span class="custom-control-indicator"></span>
									<span class="custom-control-description">Acepto recibir publicidad de terceros y sitios asociados.</span>
								</label>
								<br>
								<label class="custom-control custom-checkbox" style="margin-left: 20px;display: inline-flex;">
									<input class="custom-control-input" type="checkbox" id="aceptaNewsletter">
									<span class="custom-control-indicator"></span>
									<span class="custom-control-description">Acepto recibir newsletters con novedades, promociones y actualizaciones.</span>
								</label>
								<br>
								<div class="p-x-2 form-group m-b-0" style="margin-top: 10px;">
									<a href="javascript:void(0)" class="btn btn-primary btn-block text-uppercase" id="register" style="color: white;">Registrarse</a>
								</div><br>
								<div class="p-x-2">
									<div class="row">
										<div class="col-xs-12">
											<a href="javascript:void(0)" onClick="Login()" class="btn bg-facebook btn-block label-left m-b-0-25">
												<span class="btn-label"><i class="ti-facebook"></i></span>
												Regístrate con Facebook
											</a>
										</div>

									<!--<div class="col-xs-12">
										<fb:login-button
											class="btn bg-facebook btn-block label-left m-b-0-25"
											scope="public_profile,email"
											onlogin="Login();">
											<span>Facebook</span>
										</fb:login-button>
									</div>-->

										<!--<div class="col-xs-6">
											<button type="button" class="btn bg-googleplus btn-block label-left m-b-0-25">
												<span class="btn-label"><i class="ti-google"></i></span>
												Google+
											</button>
										</div>-->
									</div>
								</div>
							</form>
							<div class="p-a-2 text-xs-center text-muted">
								Ya tienes cuenta? <a class="text-black" href="ingresar.php"><span class="underline">Iniciar sesión</span></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="modal fade large-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						<h4 class="modal-title" id="mySmallModalLabel">Términos y condiciones</h4>
					</div>
					<div class="modal-body">
						<?php echo $info["terminos"]; ?>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>
		
		<div class="modal fade large-modal2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						<h4 class="modal-title" id="mySmallModalLabel">Políticas de privacidad</h4>
					</div>
					<div class="modal-body">
						<?php echo $info["politicas"]; ?>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Vendor JS -->
		<script type="text/javascript" src="vendor/jquery/jquery-1.12.3.min.js"></script>
		<script type="text/javascript" src="vendor/tether/js/tether.min.js"></script>
		<script type="text/javascript" src="vendor/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="vendor/sweetalert2/sweetalert2.min.js"></script>
		<script>
			function isEmail(email) {
			  var regex = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;
			  return regex.test(email);
			}
			
			var info = {};


			/*window.fbAsyncInit = function() {
				FB.init({
				  //appId      : '335054620211948', // Set YOUR APP ID
				  appId      : '1350607375027200', // Set YOUR APP ID
				  status     : true, // check login status
				  cookie     : true, // enable cookies to allow the server to access the session
				  xfbml      : true  // parse XFBML
				});*/

				/*FB.Event.subscribe('auth.authResponseChange', function(response) {
					if (response.status === 'connected') {
						//document.getElementById("message").innerHTML +=  "<br>Connected to Facebook";
						//console.log("Connected to Facebook");
						getUserInfo();
					}    
					else if (response.status === 'not_authorized') {
						//console.log("Failed to Connect");
					} else {
						//console.log("Logged Out");
					}
				});

			};*/

			function Login() {
				//getUserInfo();

				/*FB.login(function(response) {
				   if (response.authResponse) {
						getUserInfo();
					}
					else {
					 	//console.log('User cancelled login or did not fully authorize.');
					}
				},{scope: 'id,name,email,picture'});*/

				FB.login(function(response) {
					if (response.authResponse) {
						//console.log('Welcome!  Fetching your information.... ');
						//console.log(response); // dump complete info
						//access_token = response.authResponse.accessToken; //get access token
						//user_id = response.authResponse.userID; //get FB UID
						getUserInfo();
						/*FB.api('/me', function(response) {
							user_email = response.email; //get user email
							// you can store this data into your database
						});*/

					} else {
						//user hit cancel button
						//console.log('User cancelled login or did not fully authorize.');

					}
				}, {
					scope: 'public_profile,email'
				});

			}

		  	function getUserInfo() {
				FB.api('/me',{fields:'id,first_name,last_name,email,picture,gender '}, function(response) {
					
					console.log(response);
					gender = (response.gender === 'male')?'1':'2';

					$.ajax({
						type: 'POST',
						url: 'ajax/user.php',
						data: 'op=10&e=' + response.email + '&n=' + response.first_name +'&a=' + response.last_name + '&p=' + response.picture.data.url+ '&g=' + gender,
						dataType: 'json',
						success: function(data) {
							if(data.status == 1) {
								//console.log('Muestra algo');
								swal({
									title: 'Excelente!',
									text: 'Registrado Satisfactoriamente.',
									timer: 3000,
									confirmButtonClass: 'btn btn-primary btn-lg',
									buttonsStyling: false
								});
								setTimeout(function(){
									window.location.href = "./";
								},2000);
								//window.location.assign("./");
							}
							else {
								//console.log('Muestra algo 2');
								swal({
									title: 'Información!',
									text: 'Usuario existente.',
									timer: 3000,
									confirmButtonClass: 'btn btn-primary btn-lg',
									buttonsStyling: false
								});
							}
						}
					});
				});
			}
			function getPhoto() {
			  	FB.api('/me/picture?type=normal', function(response) {
					info.pic = response.data.url;
				});
			}
			
			function Logout() {
				FB.logout(function(){document.location.reload();});
			}

		  	/*// Load the SDK asynchronously
		  (function(d){
			 var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
			 if (d.getElementById(id)) {return;}
			 js = d.createElement('script'); js.id = id; js.async = true;
			 js.src = "//connect.facebook.net/en_US/all.js";
			 ref.parentNode.insertBefore(js, ref);
		   }(document));*/


			$("#register").click(function() {
				if($("#email").val() != "" && $("#passw1").val() != "" && $("#passw2").val() != "" && $("#name").val() != "" && $("#lastName").val() != "" && $("#userName").val() != "") {
					if($("#aceptaCondiciones:checked").length > 0) {
						if(isEmail($("#email").val())) {
							if($("#passw1").val() == $("#passw2").val()) {
								$.ajax({
									type: 'POST',
									url: 'ajax/user.php',
									data: 'op=2&email=' + $("#email").val() + '&password=' + $("#passw1").val() + '&name=' + $("#name").val() + '&lastName=' + $("#lastName").val() + '&userName=' + $("#userName").val() + '&publicidad=' + $("#aceptaPublicidad:checked").length + '&newsletter=' + $("#aceptaNewsletter:checked").length,
									dataType: 'json',
									success: function(data) {
										if(data.status == 1) {
											/*swal({
												title: 'Información!',
												text: 'Para confirmar tu registro, revisa la bandeja de tu correo electronico.',
												timer: 240000,
												confirmButtonClass: 'btn btn-primary btn-lg',
												buttonsStyling: false
											});*/
											swal("INFORMACIÓN!", "Para confirmar tu registro, revisa la bandeja de tu correo electronico.", "info");
											$('.form-material')[0].reset();
										} else if(data.status == 0){
											//console.log("Error al enviar correo electronico");
										}
										else {
											swal({
												title: 'Información!',
												text: 'Correo electrónico en uso intente de nuevo',
												timer: 2500,
												confirmButtonClass: 'btn btn-primary btn-lg',
												buttonsStyling: false
											});
										}
									}
								});
							}
							else {
								swal({
									title: 'Información!',
									text: 'Las contraseñas no coinciden',
									timer: 2000,
									confirmButtonClass: 'btn btn-primary btn-lg',
									buttonsStyling: false
								});
							}
						}
						else {
							swal({
								title: 'Información!',
								text: 'Correo electrónico inválido',
								timer: 2000,
								confirmButtonClass: 'btn btn-primary btn-lg',
								buttonsStyling: false
							});
						}
					}
					else {
						swal({
							title: 'Información!',
							text: 'Debe aceptar los Términos y condiciones y Políticas de Privacidad.',
							confirmButtonClass: 'btn btn-primary btn-lg',
							buttonsStyling: false
						});
					}
				}
				else {
					swal({
						title: 'Información!',
						text: 'Todos los campos con (*) son obligatorios',
						timer: 2000,
						confirmButtonClass: 'btn btn-primary btn-lg',
						buttonsStyling: false
					});
				}
			});
		</script>
	</body>
</html>