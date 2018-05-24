<?php require_once("header.php"); ?>

<?php
require_once("../classes/Jogos.php");

$times = $_GET['times'];
$idJogo = $_GET['idJogo'];
$dataJogo = date('d/m/Y', strtotime($_GET['dataJogo']));
$horaJogo = $_GET['horaJogo'];

if ($_POST) {
	$attResultado = new Jogos();
	$attResultado->atualizarResultado($idJogo, $_POST['gol_casa'], $_POST['gol_fora']);
}

?>

<div class="container">
	<div class="row">
		<div class="col-3"></div>
		<div class="col-6 form-attJogos">
			<form method="POST">
				<div class="form-group col-12" style="text-align: center;">
					<span><?php echo $times."<br><i style='color: red;'>".$dataJogo." - ".$horaJogo."</i>"; ?></span>
				</div>
				<div class="row">
					<div class="form-group col-3">
						<label for="id_gol_casa">Casa: </label>
						<input class="form-control" type="number" name="gol_casa" id="id_gol_casa">
					</div>

					<div class="form-group col-3">
						<label for="id_gol_fora">Fora: </label>
						<input class="form-control" type="number" name="gol_fora" id="id_gol_fora">
					</div>	
				</div>	

				<div class="row">
					<div class="col-3">
						<button class="btn btn-success">Atualizar</button>
					</div>
				</div>		
			</form>
		</div>
	</div>
</div>

<?php require_once("footer.php"); ?>