
<script>
	var urlCurrent = '<?php echo (strstr($_SERVER["REQUEST_URI"], "empresa/") ? "" : "empresa/"); ?>';
	var typeUser = '<?php echo isset($_SESSION["ctc"]) ? $_SESSION["ctc"]["type"] : 0; ?>';
</script>
<script type="text/javascript">
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

	function Logout(){
		FB.logout(function(){});
	}
</script>
<style>
	@media only screen and (max-width: 1050px) {
		.menuMovilTrabajador {
			display: block !important;
		}
		.menuPCTrabajador {
			display: none;
		}
		.menuMovilEmpresa {
			display: block !important;
		}
		.menuPCEmpresa {
			display: none;
		}
	}

	@media only screen and (max-width: 600px) {
		.movil{
			margin-top: 15px;
		}
	}
</style>

<div class="site-header">
	<nav class="navbar navbar-light">
		<ul class="nav navbar-nav">
			<li class="nav-item m-r-1 hidden-lg-up">
				<a class="nav-link collapse-button" href="#">
					<i class="ti-menu" style="display: none;"></i>
				</a>
			</li>
		</ul>
		<?php if(isset($_SESSION["ctc"]["id"])): ?>
			<?php if($_SESSION["ctc"]["type"] == 1):
					$color = "";
					switch($_SESSION["ctc"]["plan"]["id_plan"]) {
						case 2:
							$color = "background-color: #cd7f32;";
							break;
						case 3:
							$color = "background-color: #8a9597;";
							break;
						case 4:
							$color = "background-color: #FFD700;";
							break;
					}
				?>
				<ul class="nav navbar-nav pull-xs-right">
					<li class="nav-item dropdown menuPCEmpresa">
						<a class="nav-link" href="<?php echo (strstr($_SERVER["REQUEST_URI"], "empresa/") ? "../" : ""); ?>promociones.php">
							Promociones
						</a>
					</li>
					<li class="nav-item dropdown menuPCEmpresa">
						<a class="nav-link" href="<?php echo (strstr($_SERVER["REQUEST_URI"], "empresa/") ? "../" : ""); ?>trabajadores.php">
							Ver jobbers
						</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link" href="<?php echo strstr($_SERVER["REQUEST_URI"], "empresa/") ? "planes" : "empresa/planes"; ?>.php">
							<span>Plan:</span>
							<span class="tag tag-success top" style="top: 0; <?php echo $color; ?>"><?php echo $_SESSION["ctc"]["plan"]["nombre"]; ?></span>
						</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link" href="#" data-toggle="dropdown" aria-expanded="false">
							<div class="avatar box-32">
								<img src="<?php echo (strstr($_SERVER["REQUEST_URI"], "empresa/") ? "" : "empresa/")."img/".$_SESSION["ctc"]["pic"]; ?>" alt="">
							</div>
						</a>
						<div class="dropdown-menu dropdown-menu-right animated flipInY">
							<a class="dropdown-item" href="<?php echo strstr($_SERVER["REQUEST_URI"], "empresa/") ? "./" : "empresa/"; ?>">
								<i class="ti-folder m-r-0-5"></i> Panel
							</a>
							<a class="dropdown-item" href="<?php echo strstr($_SERVER["REQUEST_URI"], "empresa/") ? "perfil" : "empresa/perfil"; ?>.php">
								<i class="ti-user m-r-0-5"></i> Perfil
							</a>
							<!--
							<a class="dropdown-item" href="#">
								<i class="ti-settings m-r-0-5"></i> Ajustes
							</a>-->
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?php echo strstr($_SERVER["REQUEST_URI"], "empresa/") ? "salir" : "empresa/salir"; ?>.php"><i class="ti-power-off m-r-0-5"></i> Cerrar sesión</a>
						</div>
					</li>
					<li class="nav-item hidden-md-up">
						<a class="nav-link" href="#" data-toggle="collapse" data-target="#collapse-1">
							<i class="ti-more"></i>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link site-sidebar-second-toggle" href="#" data-toggle="collapse">
							<i class="ti-arrow-left" style="color: #b7b2b2;"></i>
						</a>
					</li>
				</ul>
			<?php else: ?>
				<?php if($_SESSION["ctc"]["type"] == 2): ?>
					<ul class="nav navbar-nav pull-xs-right">
						<li class="nav-item dropdown menuPCTrabajador">
							<a class="nav-link" href="promociones.php">
								Promociones
							</a>
						</li>
						<li class="nav-item dropdown menuPCTrabajador">
							<a class="nav-link" href="empleos.php">
								Ver ofertas de empleo
							</a>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link" href="#" data-toggle="dropdown" aria-expanded="false">
								<div class="row" style="margin-left: 0;margin-right: 0;">
									<div class="col-md-9 col-xs-9"><?php echo $_SESSION["ctc"]["name"]." ".$_SESSION["ctc"]["lastName"]; ?></div>
									<div style="" class="col-md-3 col-xs-3">
										<div class="avatar box-32">
											<img src="<?php echo strstr($_SERVER["REQUEST_URI"], "empresa") ? '../': ''; ?>img/<?php echo $_SESSION["ctc"]["pic"]; ?>" alt="" style="height: 30px;">
										</div>
									</div>
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-right animated flipInY">
								<a class="dropdown-item" href="trabajador-detalle.php?t=<?php echo $_SESSION["ctc"]["name"]."-".$_SESSION["ctc"]["lastName"]."-".$_SESSION["ctc"]["id"]; ?>">
									<i class="ti-user m-r-0-5"></i> Perfil
								</a>
								<a class="dropdown-item" href="cuenta.php">
									<i class="ti-pencil-alt m-r-0-5"></i> Mi cuenta
								</a>
								<a class="dropdown-item" href="curriculum.php">
									<i class="ti-book m-r-0-5"></i> Modificar currículum
								</a>
								<a class="dropdown-item" href="postulaciones.php">
									<i class="ti-check-box m-r-0-5"></i> Ver postulaciones realizadas
								</a>
								<a class="dropdown-item" href="publicaciones.php">
									<i class="ti-clipboard  m-r-0-5"></i> Mis servicios free lance
								</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item 1logout" href="salir.php" onclick="Logout();"><i class="ti-power-off m-r-0-5"></i> Cerrar sesión</a>
							</div>
						</li>
						<li class="nav-item hidden-md-up">
							<a class="nav-link" href="#" data-toggle="collapse" data-target="#collapse-1">
								<i class="ti-more"></i>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link site-sidebar-second-toggle" href="#" data-toggle="collapse">
								<i class="ti-arrow-left" style="color: #b7b2b2;"></i>
							</a>
						</li>
					</ul>
				<?php else: ?>
					<ul class="nav navbar-nav pull-xs-right">
						<li class="nav-item dropdown">
							<a class="nav-link" href="#" data-toggle="dropdown" aria-expanded="false">
								<div class="row" style="margin-left: 0;margin-right: 0;">
									<div class="col-md-9 col-xs-9"><?php echo $_SESSION["ctc"]["name"]." ".$_SESSION["ctc"]["lastName"]; ?></div>
									<div style="" class="col-md-3 col-xs-3">
										<div class="avatar box-32">
											<img src="<?php echo strstr($_SERVER["REQUEST_URI"], "admin") ? '../': ''; ?>img/<?php echo $_SESSION["ctc"]["pic"]; ?>" alt="" style="height: 30px;">
										</div>
									</div>
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-right animated flipInY">
								<a class="dropdown-item" href="<?php echo strstr($_SERVER["REQUEST_URI"], "admin") ? '': 'admin/'; ?>mis-noticias.php">
									<i class="ti-notepad m-r-0-5"></i> Mis noticias
								</a>
								<?php if($_SESSION["ctc"]["rol"] == "A"): ?>
									<a class="dropdown-item" href="<?php echo strstr($_SERVER["REQUEST_URI"], "admin") ? '': 'admin/'; ?>empresas.php">
										<i class="ti-briefcase m-r-0-5"></i> Empresas
									</a>
									<a class="dropdown-item" href="<?php echo strstr($_SERVER["REQUEST_URI"], "admin") ? '': 'admin/'; ?>publicidad.php">
										<i class="ti-thumb-up m-r-0-5"></i> Publicidad
									</a>
									<a class="dropdown-item" href="<?php echo strstr($_SERVER["REQUEST_URI"], "admin") ? '': 'admin/'; ?>usuarios.php">
										<i class="ti-user m-r-0-5"></i> Usuarios
									</a>
									<a class="dropdown-item" href="<?php echo strstr($_SERVER["REQUEST_URI"], "admin") ? '': 'admin/'; ?>trabajadores.php">
										<i class="ti-id-badge m-r-0-5"></i> Trabajadores
									</a>
									<a class="dropdown-item" href="<?php echo strstr($_SERVER["REQUEST_URI"], "admin") ? '': 'admin/'; ?>configuraciones.php">
										<i class="ti-settings m-r-0-5"></i> Configuraciones
									</a>
								<?php endif ?>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item 1logout" href="<?php echo strstr($_SERVER["REQUEST_URI"], "admin") ? '../': ''; ?>salir.php"><i class="ti-power-off m-r-0-5"></i> Cerrar sesión</a>
							</div>
						</li>
					</ul>
				<?php endif ?>
			<?php endif ?>
		<?php else: ?>

			<ul class="nav navbar-nav pull-xs-right movil">
				<li class="nav-item dropdown">
					<a class="nav-link" href="<?php echo strstr($_SERVER["REQUEST_URI"], "empresa/") ? '../': ''; ?>empleos.php">
						Empleos
					</a>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link" href="<?php echo strstr($_SERVER["REQUEST_URI"], "empresa/") ? '../': ''; ?>ingresar.php" style="color: white;">
						Ingresar
					</a>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link" href="<?php echo strstr($_SERVER["REQUEST_URI"], "empresa/") ? '../': ''; ?>registro.php">
						Registrarse
					</a>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link" href="<?php echo strstr($_SERVER["REQUEST_URI"], "empresa/") ? '': 'empresa/'; ?>acceder.php">
						Empresas
					</a>
				</li>
				<li class="nav-item hidden-md-up">
					<a class="nav-link" href="#" data-toggle="collapse" data-target="#collapse-1">
						<i class="ti-more"></i>
					</a>
				</li>
			</ul>
		<?php endif ?>

		<?php if(isset($_SESSION["ctc"])): ?>
			<?php if($_SESSION["ctc"]["type"] != 3): ?>
				<?php if($_SESSION["ctc"]["type"] == 2): ?>
					<div class="navbar-toggleable-sm collapse" id="collapse-1">
						<div class="header-form pull-md-left m-md-r-1">
							<form>
								<input type="text" id="search-input" class="form-control b-a" placeholder="Búsqueda">
								<a href="javascript:void(0)" id="search" class="btn bg-white b-a-0">
									<i class="ti-search"></i>
								</a>
							</form>
						</div>
						<ul class="nav navbar-nav">
							<li class="nav-item dropdown">
								<a class="nav-link" href="<?php echo strstr($_SERVER["REQUEST_URI"], "empresa") ? "../" : ""; ?>buscador.php?tipo=empleos">
									Búsqueda avanzada
								</a>
							</li>
							<li class="nav-item dropdown menuMovilTrabajador" style="display:none;">
								<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
									Menu
								</a>
								<div class="dropdown-menu animated flipInY">
									<a class="dropdown-item" href="promociones.php">
										Promociones
									</a>
									<a class="dropdown-item" href="empleos.php">Ver ofertas de empleo</a>
								</div>
							</li>
						</ul>

					</div>
				<?php else: ?>
					<div class="navbar-toggleable-sm collapse" id="collapse-1">
						<div class="header-form pull-md-left m-md-r-1">
							<form>
								<input type="text" id="search-input" class="form-control b-a" placeholder="Búsqueda">
								<a href="javascript:void(0)" id="search" class="btn bg-white b-a-0">
									<i class="ti-search"></i>
								</a>
							</form>
						</div>
						<?php if(isset($_SESSION["ctc"]["plan"])): ?>
							<?php if($_SESSION["ctc"]["servicio"]["filtros_personalizados"] == 1): ?>
								<ul class="nav navbar-nav">
									<li class="nav-item dropdown">
										<a class="nav-link" href="<?php echo strstr($_SERVER["REQUEST_URI"], "empresa") ? "../" : ""; ?>buscador.php?tipo=trabajadores" >
											Búsqueda avanzada
										</a>
									</li>
									<li class="nav-item dropdown menuMovilEmpresa" style="display:none;">
										<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
											Menu
										</a>
										<div class="dropdown-menu animated flipInY">
											<a class="dropdown-item" href="promociones.php">
												Promociones
											</a>
											<a class="dropdown-item" href="<?php echo (strstr($_SERVER["REQUEST_URI"], "empresa") ? "../" : ""); ?>trabajadores.php">Ver jobbers</a>
										</div>
									</li>
								</ul>
							<?php else: ?>
								<ul class="nav navbar-nav">
									<li class="nav-item dropdown menuMovilEmpresa" style="display:none;">
										<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
											Menu
										</a>
										<div class="dropdown-menu animated flipInY">
											<a class="dropdown-item" href="promociones.php">
												Promociones
											</a>
											<a class="dropdown-item" href="<?php echo (strstr($_SERVER["REQUEST_URI"], "empresa") ? "../" : ""); ?>trabajadores.php">Ver jobbers</a>
										</div>
									</li>
								</ul>
							<?php endif ?>
						<?php endif ?>
					</div>
				<?php endif ?>
			<?php endif ?>
		<?php else: ?>
			<div class="navbar-toggleable-sm collapse" id="collapse-1">
					<div class="header-form pull-md-left m-md-r-1">
						<form>
							<input type="text" id="search-input" class="form-control b-a" placeholder="Búsqueda">
							<a href="javascript:void(0)" id="search" class="btn bg-white b-a-0">
								<i class="ti-search"></i>
							</a>
						</form>
					</div>
					<ul class="nav navbar-nav">
						<li class="nav-item dropdown">
							<a class="nav-link" href="<?php echo strstr($_SERVER["REQUEST_URI"], "empresa") ? "../" : ""; ?>buscador.php?tipo=empleos">
								Búsqueda avanzada
							</a>
						</li>
					</ul>
			</div>
		<?php endif ?>
	</nav>
</div>