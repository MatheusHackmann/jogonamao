<?php require_once("header.php"); ?>

<?php 
require_once("../classes/Jogos.php");

$cadCopa = new Jogos();

if ($_POST) {

	$cadastro = $cadCopa->cadastrarCopas($_POST['copa']);
}

?>

<div class="container-fluid">
	<div class="row">
		<div class="offset-md-2 col-12 col-md-8 col-lg-8">
			<form class="form-control form-cadastro-copa" method="POST">

				<?php 
				if($_POST && $cadastro == false)
				{
					echo "
					<div class='alert alert-danger alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert'>&times;</button>
					<strong>Copa já existe.</strong>
					</div>
					";
				}
				else if($_POST && $cadastro){
					echo "
					<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert'>&times;</button>
					<strong>Copa cadastrada!</strong>
					</div>
					";
				} 
				?>

				<div class="row">
					<div class="col-12 col-md-6 col-lg-6 form-group">
						<label for="id_copa">Copa: </label>
						<input class="form-control" type="text" name="copa" id="id_copa" required autocomplete="off">
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

<?php require_once("header.php"); ?>