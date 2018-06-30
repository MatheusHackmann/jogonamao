<?php
require_once("../classes/Sql.php");
session_start();

$login = $_POST['login'];
$senha = $_POST['senha'];

$sql = new Sql();
$infoUsuario = $sql->select("SELECT * FROM usuarios WHERE login_usuario = :LOGIN AND senha_usuario = :SENHA;", array(
":LOGIN" => $login,
":SENHA" => $senha
));

if (count($infoUsuario) > 0) {
	$_SESSION['acesso'] = $infoUsuario[0]['acesso'];

	header("location: jogos_apostar.php");
}
else {
	unset($_SESSION['acesso']);
	session_destroy();

	header("location: ../index.php?login=false");
}


?>