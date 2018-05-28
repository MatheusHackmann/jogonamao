<?php 
require_once("../classes/Usuarios.php");
require_once("header.php");

if ($_POST) {
	$cadUsuario = new Usuarios();
	$cadUsuario->cadastrarUsuario($_POST['nome_completo'], $_POST['login'], $_POST['senha']);
}
?>

<div class="container-fluid">
	<div class="row">
		<div class="offset-md-3 col-12 col-md-6 col-lg-6">
			<form class="form-control form-cadastro-usuario" method="POST">

				<div class="row">
					<div class="col-12 col-md-12 col-lg-12 form-group">
						<label for="id_nome_completo">Nome completo: </label>
						<input class="form-control" type="text" name="nome_completo" id="id_nome_completo" required autocomplete="off">
					</div>						
				</div>

				<div class="row">
					<div class="col-12 col-md-12 col-lg-12 form-group">
						<label for="id_login">Login: </label>
						<input class="form-control" type="text" name="login" id="id_login" required autocomplete="off">
					</div>
				</div>	

				<div class="row">
					<div class="col-12 col-md-12 col-lg-12 form-group">
						<label for="id_senha">Senha: </label>
						<input class="form-control" type="password" name="senha" id="id_senha" required autocomplete="off">
					</div>
				</div>							

				<div class="row">
					<div class="col-12 col-md-12 col-lg-12">
						<button class="btn btn-success" type="submit">Cadastrar</button>
					</div>					
				</div>

			</form>
		</div>
	</div>
</div>

<?php 
require_once("footer.php");
?>