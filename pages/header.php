<?php date_default_timezone_set('America/Sao_Paulo'); ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title></title>
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../css/style.css">

	<script type="text/javascript" src="../js/jquery-3.3.1.js"></script>
	<script type="text/javascript" src="../js/popper.min.js"></script>
	<script type="text/javascript" src="../js/bootstrap.min.js"></script>
</head>
<body>

	<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
		<!-- Brand -->
		<a class="navbar-brand" href="#">Logo</a>

		<!-- Links -->
		<ul class="navbar-nav">

			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="" id="navbardrop" data-toggle="dropdown">
					Jogos
				</a>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="cad_jogos.php">Cadastrar Jogo</a>
					<a class="dropdown-item" href="cad_copas.php">Cadastrar Copa</a>
					<a class="dropdown-item" href="atualizar_resultados.php">Atualizar Resultados de Jogos</a>
				</div>
			</li>

			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
					Bilhetes
				</a>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="jogos_apostar.php">Novo Bilhete</a>
					<a class="dropdown-item" href="ganhadores.php">Consultar Ganhadores</a>
					<a class="dropdown-item" href="perdedores.php">Perdedores</a>
				</div>
			</li>

			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
					Cliente
				</a>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="pagamentos.php">Pagamentos</a>
					<a class="dropdown-item" href="cad_usuario.php">Cadastrar Usuário</a>
				</div>				
			</li>						

		</ul>
	</nav>