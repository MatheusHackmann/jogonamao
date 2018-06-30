<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Login Jogo Na Mão</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">

	<script type="text/javascript" src="js/jquery-3.3.1.js"></script>
	<script type="text/javascript" src="js/popper.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
</head>
<body>

	<div class="container">
		<div class="row">
			
			<div class="offset-md-4 col-12 col-md-4 col-lg-4 formLogin">

				<?php
				if ($_GET && $_GET['login'] == 'false') {
					echo "
					<div class='alert alert-danger alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert'>&times;</button>
					<b>Usuário</b> ou <b>Senha</b> inválidos.
					</div>
					";						
				}
				?>

				<form method="post" class="form-control" action="pages/login.php">
					<div class="form-group col-12">
						<label for="id_login">Login:</label>
						<input type="text" name="login" class="form-control" id="id_login">
					</div>
					<div class="form-group col-12">
						<label for="id_senha">Senha:</label>
						<input type="password" name="senha" class="form-control" id="id_senha">
					</div>					
					<div class="form-group col-12">
						<button class="btn btn-primary" type="submit">Entrar</button>
					</div>
				</form>
			</div>

		</div>
	</div>

</body>
</html>
