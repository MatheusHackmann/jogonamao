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

require_once("header.php");
if (!isset($_SESSION['acesso'])) {
	session_destroy();
	session_unset();
	header("location: ../index.php");
}
?>

<div class="container">
	<div class="row">
		<div class="offset-md-3 col-12 col-md-6 col-lg-6 form-attResultados">
			<form method="POST">
				<div class="form-group col-12" style="text-align: center;">
					<span><?php echo $times."<br><i style='color: red;'>".$dataJogo." - ".$horaJogo."</i>"; ?></span>
				</div>
				<div class="row">
					<div class="form-group col-12 col-md-3 col-lg-3">
						<label for="id_gol_casa">Casa: </label>
						<input class="form-control" type="number" name="gol_casa" id="id_gol_casa">
					</div>

					<div class="form-group col-col-12 col-md-3 col-lg-3">
						<label for="id_gol_fora">Fora: </label>
						<input class="form-control" type="number" name="gol_fora" id="id_gol_fora">
					</div>	
				</div>	

				<div class="row">
					<div class="col-12 col-md-12 col-lg-12">
						<button class="btn btn-success">Atualizar</button>
					</div>
				</div>		
			</form>
		</div>
	</div>
</div>

<?php require_once("footer.php"); ?>