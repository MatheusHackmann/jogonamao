<?php 
require_once("header.php"); 
require_once("../classes/Apostas.php");
?>

<div class="container">
	<div class="row">
		<div class="col-4"></div>
		<div class="col-4 form-attJogos">
			<form method="POST">
				<div class="form-group col-12" style="text-align: center;">
					<h3>Verificar Bilhetes</h3>
				</div>
				<div class="row">
					<div class="offset-3 form-group col-6">
						<label for="id_cod_bilhete">Código do bilhete: </label>
						<input class="form-control" type="text" name="cod_bilhete" id="id_cod_bilhete">
					</div>	
				</div>	

				<div class="row">
					<div class="offset-4 col-4">
						<button class="btn btn-success">Verificar</button>
					</div>
				</div>		
			</form>
		</div>
	</div>
</div>

<?php

if ($_POST) {
	$verificarBilhete = new Apostas();
	$situacaoBilhete = $verificarBilhete->verificarBilhete($_POST['cod_bilhete']);

	echo "
	<div class='container' style='margin-top: 10px;'>
	<div class='row'>
	<div class='offset-3 col-6'>
	";
	if ($situacaoBilhete == true) {
		echo "
		<div class='alert alert-success alert-dismissible'>
		<button type='button' class='close' data-dismiss='alert'>&times;</button>
		<strong>Bilhete Disponível!</strong>
		</div>";
	}else {
		echo "
		<div class='alert alert-danger alert-dismissible'>
		<button type='button' class='close' data-dismiss='alert'>&times;</button>
		Bilhete Não Existe, Ou Não Está Disponível.<br>
		<i>Bilhetes só são liberados no próximo dia, ao do gerado o bilhete.</i>
		</div>";			
	}
	echo "
	</div>
	</div>
	</div>
	";
}



?>

<?php require_once("footer.php"); ?>