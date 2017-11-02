<?php
	session_start();
	if (isset($_SESSION['ctc']['type'])) {
		header('Location: ./');
	}
	session_destroy();

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
		<title>JOBBERS - Iniciar sesión</title>

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
		
		<div class="container-fluid">
			<div class="sign-form">
				<div class="row">
					<div class="col-md-4 offset-md-4 p-x-3">
						<div class="box b-a-0">
							<div style="text-align: center;margin: ;">
								<a href="./"><img src="img/logo_d.png" style="height: 50px; margin-top: 20px;"></a>
							</div>
							<div class="p-a-2 text-xs-center">
								<h5>Bienvenido a JOBBERS</h5>
							</div>
							<form class="form-material m-b-1">
								<div class="form-group">
									<input type="email" class="form-control" id="email" placeholder="Usuario o correo electrónico" onkeypress="return runScript(event)">
								</div>
								<div class="form-group">
									<input type="password" class="form-control" id="passw" placeholder="Contraseña" onkeypress="return runScript(event)">
								</div>
								<div class="p-x-2 form-group m-b-0">
									<a href="javascript:void(0)" id="login" class="btn btn-primary btn-block text-uppercase" style="color: white;">Ingresar</a>
								</div>
							</form>
							<div class="p-x-2">
								<div class="row">
									<div class="col-xs-12">
										<a href="javascript:void(0)" onClick="Login()" class="btn bg-facebook btn-block label-left m-b-0-25">
											<span class="btn-label"><i class="ti-facebook"></i></span>
											Ingresar con Facebook
										</a>
									</div>
									<!--<div class="col-xs-6">
										<button type="button" class="btn bg-googleplus btn-block label-left m-b-0-25">
											<span class="btn-label"><i class="ti-google"></i></span>
											Google+
										</button>
									</div>-->
								</div>
							</div>
							<div class="p-a-2 text-xs-center text-muted" style="padding-bottom: 0 !important;">
								<a class="text-black" href="javascript:void(0)" id="resetPass" data-toggle="modal" data-target="#forgotPass"><span class="underline">Olvidaste tu contraseña?</span></a>
							</div>
							<div class="p-a-2 text-xs-center text-muted">
								Aun no tienes cuenta? <a class="text-black" href="registro.php"><span class="underline">Regístrate</span></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="modal fade" id="forgotPass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="exampleModalLabel">Recuperación de contraseña</h4>
					</div>
					<div class="modal-body">
						<form id="contentEmail">
							<div class="form-group">
								<label for="f-email" class="form-control-label">Correo electrónico:</label>
								<input type="text" class="form-control" id="f-email">
							</div>
						</form>
						<div id="contentCode" style="display: none;">
							<div class="alert alert-success alert-dismissible fade in" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
								</button>
								Le hemos enviado un código a su correo electónico, por favor busque en su bandeja de entrada o la carpeta spam e ingrese el codigo en el campo de abajo.
							</div>
							<div class="form-group">
								<label for="code" class="form-control-label">Ingrese el código:</label>
								<input type="text" class="form-control" id="code">
							</div>
						</div>
						<div id="contentPass" style="display: none;">
							<div class="alert alert-success alert-dismissible fade in" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
								</button>
								Código validado correctamente, por favor ingrese nu nueva contraseña.
							</div>
							<div class="form-group">
								<label for="p1" class="form-control-label">Nueva contraseña:</label>
								<input type="text" class="form-control" id="p1">
							</div>
							<div class="form-group">
								<label for="p2" class="form-control-label">Repita su nueva contraseña:</label>
								<input type="text" class="form-control" id="p2">
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						<button type="button" class="btn btn-primary" id="sendF" data-value="1">Enviar</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Vendor JS -->
		<script type="text/javascript" src="vendor/jquery/jquery-1.12.3.min.js"></script>
		<script type="text/javascript" src="vendor/tether/js/tether.min.js"></script>
		<script type="text/javascript" src="vendor/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="vendor/sweetalert2/sweetalert2.min.js"></script>
		
		<div id="fb-root"></div>
        <script>/*(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v2.9&appId=1350607375027200";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));*/</script>
		
		<script>
			function runScript(e) {
           		if (e.keyCode == 13) {
					validateForm();
               		return false;
           		}
			}
			
			function isEmail(email) {
			  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			  return regex.test(email);
			}
			
			function validateForm() {
				if($("#email").val() != "" && $("#passw").val() != "") {
					if(isEmail($("#email").val())) {
						$.ajax({
							type: 'POST',
							url: 'ajax/user.php',
							data: 'op=1&email=' + $("#email").val() + '&password=' + $("#passw").val(),
							dataType: 'json',
							success: function(data) {
								if(data.status == 1) {
									window.location.href="./";
								} else if (data.status == 3){
									swal({
										title: 'Información!',
										text: 'No has confirmado el registro desde tu correo electronico',
										timer: 60000,
										confirmButtonClass: 'btn btn-primary btn-lg',
										buttonsStyling: false
									});
								}
								else {
									swal({
										title: 'Información!',
										text: 'Correo electrónico o contraseña incorrectos.',
										timer: 2000,
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
							text: 'Correo electrónico inválido.',
							timer: 2000,
							confirmButtonClass: 'btn btn-primary btn-lg',
							buttonsStyling: false
						});
					}
				}
				else {
					swal({
						title: 'Información!',
						text: 'Los campos correo y contraseña son obligatorios.',
						timer: 2000,
						confirmButtonClass: 'btn btn-primary btn-lg',
						buttonsStyling: false
					});
				}
			}
			
			var info = null;
			
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
						console.log("Connected to Facebook");
						getUserInfo();
					}    
					else if (response.status === 'not_authorized') {
						console.log("Failed to Connect");
					} else {
						console.log("Logged Out");
					}
				}); */

			//};
			
			window.fbAsyncInit = function() {
			  FB.init({
				appId      : '1785394951770843',
				cookie     : true,  // enable cookies to allow the server to access 
									// the session
				xfbml      : true,  // parse social plugins on this page
				version    : 'v2.9' // use graph api version 2.8
			  });
			}
				
				
			
			/*function checkLoginState() {
			  FB.getLoginStatus(function(response) {
				//statusChangeCallback(response);
				  if(response.status == "connected") {
					getUserInfo();
				  }
				  else {
				  	Login();
				  }
			  });
			}*/

			function Login() {
				/*FB.login(function(response) {
                    console.log(response);
                    console.log(response.authResponse);
				   if (response.authResponse) {
						getUserInfo();
					}
					else {
					 	console.log('User cancelled login or did not fully authorize.');
					}
				},{scope: 'public_profile,email'});*/

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
				FB.api('/me?fields=id,name,email,picture', function(response) {
					$.ajax({
						type: 'POST',
						url: 'ajax/user.php',
						data: 'op=9&e=' + response.email + '&n=' + response.name + '&p=' + response.picture.data.url,
						dataType: 'json',
						success: function(data) {
							if(data.status == 1) {
								window.location.assign("./");
							}
							else {
								swal({
									title: 'Información!',
									text: 'Usuario no registrado.',
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
			
		  	// Load the SDK asynchronously
		  (function(d){
			 var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
			 if (d.getElementById(id)) {return;}
			 js = d.createElement('script'); js.id = id; js.async = true;
			 js.src = "//connect.facebook.net/en_US/all.js";
			 ref.parentNode.insertBefore(js, ref);
		   }(document));
			
			$('#forgotPass').on('hidden.bs.modal', function (event) {
				$("#sendF").attr("data-value", 1);
				$("#contentEmail").css("display", "block");
				$("#contentCode").css("display", "none");
				$("#contentPass").css("display", "none");
				$("#f-email").val("");
				$("#code").val("");
				$("#p1").val("");
				$("#p2").val("");
			});
			
			$("#sendF").click(function() {
				if(parseInt($("#sendF").attr("data-value")) == 1) {
					if($("#f-email").val() != "") {
						if(isEmail($("#f-email").val())) {
							$.ajax({
								type: 'POST',
								url: 'ajax/user.php',
								data: 'op=5&email=' + $("#f-email").val(),
								dataType: 'json',
								success: function(data) {
									if(data.status == 1) {
										$("#sendF").attr("data-value", 2);
										$("#contentEmail").css("display", "none");
										$("#contentCode").css("display", "block");
									}
									else {
										swal({
											title: 'Información!',
											text: 'Correo electrónico no registrado, por favor intente con otro, de lo contrario puede registrarse haciendo click <a href="registro.php">aquí</a>.',
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
								text: 'Correo electrónico inválido.',
								timer: 2000,
								confirmButtonClass: 'btn btn-primary btn-lg',
								buttonsStyling: false
							});
						}
					}
					else {
						swal({
							title: 'Información!',
							text: 'Debe ingresar su correo electrónico.',
							timer: 2000,
							confirmButtonClass: 'btn btn-primary btn-lg',
							buttonsStyling: false
						});
					}
				}
				else {
					if(parseInt($("#sendF").attr("data-value")) == 2) {
						if($("#code").val() != "") {
							$.ajax({
								type: 'POST',
								url: 'ajax/user.php',
								data: 'op=6&email=' + $("#f-email").val() + '&code=' + $("#code").val(),
								dataType: 'json',
								success: function(data) {
									if(data.status == 1) {
										$("#sendF").attr("data-value", 3);
										$("#contentCode").css("display", "none");
										$("#contentPass").css("display", "block");
									}
									else {
										swal({
											title: 'Información!',
											text: 'Código incorrecto.',
											timer: 2000,
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
								text: 'Debe escribir el código que recibió en su correo.',
								timer: 2000,
								confirmButtonClass: 'btn btn-primary btn-lg',
								buttonsStyling: false
							});
						}
					}
					else {
						if($("#p1").val() != "" && $("#p2").val() != "") {
							if($("#p1").val() == $("#p2").val()) {
								$.ajax({
									type: 'POST',
									url: 'ajax/user.php',
									data: 'op=7&email=' + $("#f-email").val() + '&p=' + $("#p1").val(),
									dataType: 'json',
									success: function(data) {
										swal({
											title: 'Información!',
											text: 'Contraseña modificada satisfactoriamente, iniciando sesión.',
											timer: 2000,
											confirmButtonClass: 'btn btn-primary btn-lg',
											buttonsStyling: false
										});
										setTimeout(function() {
											window.location.assign("./");
										}, 2000);
									}
								});
							}
							else {
								swal({
									title: 'Información!',
									text: 'Las contraseñas no coinciden.',
									timer: 2000,
									confirmButtonClass: 'btn btn-primary btn-lg',
									buttonsStyling: false
								});
							}
						}
						else {
							swal({
								title: 'Información!',
								text: 'Las contraseñas son obligatorias.',
								timer: 2000,
								confirmButtonClass: 'btn btn-primary btn-lg',
								buttonsStyling: false
							});
						}
					}
				}
			});
			$("#login").click(function() {
				validateForm();
			});
		</script>
	</body>
</html>