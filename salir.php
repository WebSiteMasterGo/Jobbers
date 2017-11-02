<?php
	session_start();
	unset($_SESSION["ctc"]["empresa"]);
	unset($_SESSION["ctc"]);
	session_destroy();
	header('Location: ./');
?>
