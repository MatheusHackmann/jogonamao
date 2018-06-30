<?php 

require_once("header.php"); 
require_once("../classes/Apostas.php");

if (!isset($_SESSION['acesso'])) {
	session_destroy();
	session_unset();
	header("location: ../index.php");
}	

if ($_POST) {
	$verificarBilhete = new Apostas();
	$alert = $verificarBilhete->verificarBilhete($_POST['codigo_bilhete']);

	$infoBilhete = $verificarBilhete->exibirBilhete($_POST['codigo_bilhete']);
}

if($_GET && isset($_GET['cod'])) {
	$attStatusPagamento = new Apostas();
	$attStatusPagamento->attStatusPagamento($_GET['cod']);

	header("Location: bilhetes.php");
}

?>

<div class="container">
	<div class="row">
		<div class="offset-md-3 col-12 col-md-6 col-lg-6 table-ganhadores">
			<?php
			if ($_POST && $alert == false) {
				echo "<div class='alert alert-danger alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert'>&times;</button>
				Seu bilhete foi emitido hoje, consulte-o amanhã!
				</div>";				
			}
			else if ($_POST && $alert != false && $alert != "" && $alert != "1") {
				echo "<div class='alert alert-danger alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert'>&times;</button>
				$alert
				</div>";
			}		
			if ($_POST && $alert != false && count($infoBilhete) > 0) {

				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert'>&times;</button>
				Bilhete encontrado!
				</div>";
			}
			?>
			<form method="POST">
				<div class="row">
					<div class="form-group col-12">
						<label for="id_codigo_bilhete">Código do Bilhete: </label>
						<input class="form-control" type="text" name="codigo_bilhete" id="id_codigo_bilhete" autocomplete="off">
					</div>
				</div>	
				<div class="row">
					<div class="col-12">
						<button class="btn btn-success" type="submit" style="width: 100%;">Verificar bilhete</button>
					</div>
					<?php
					if ($_POST && $alert != false && count($infoBilhete) > 0) {				
						echo "
						<div class='col-12'>					
						<button class='btn btn-primary' type='button' data-toggle='modal' data-target='#myModal' style='width: 100%;'>Informações do Bilhete</button>					
						</div>					
						";
					}
					?>					
				</div>				
			</form>	

			<?php
			if ($_POST && $alert != false && count($infoBilhete) > 0) {
				echo "
				<div class='modal' id='myModal'>
				<div class='modal-dialog'>
				<div class='modal-content'>";

				if ($infoBilhete[0]['situacao'] === "Perdeu") {
					echo "
					<div class='modal-body'>
					<p>Código: ".utf8_encode($infoBilhete[0]['cod_bilhete'])."</p>
					<p>Cliente: ".utf8_encode($infoBilhete[0]['cliente'])."</p>
					<p>Cotas Totais: R$".$infoBilhete[0]['cotas']."</p>
					<p>Valor Apostado: R$".$infoBilhete[0]['valor_aposta'].".00</p>
					<p>Retorno: R$".$infoBilhete[0]['retorno']."</p>
					<p>Data do Bilhete: ".date('d/m/Y', strtotime($infoBilhete[0]['data_bilhete']))."</p>
					<p style='color: red;'>Status do Bilhete: ".$infoBilhete[0]['situacao']."</p>
					</div>
					";
				}
				else if($infoBilhete[0]['situacao'] === "Ganhou") {
					echo "
					<div class='modal-body'>
					<p>Código: ".utf8_encode($infoBilhete[0]['cod_bilhete'])."</p>
					<p>Cliente: ".utf8_encode($infoBilhete[0]['cliente'])."</p>
					<p>Cotas Totais: R$".$infoBilhete[0]['cotas']."</p>
					<p>Valor Apostado: R$".$infoBilhete[0]['valor_aposta'].".00</p>
					<p>Retorno: R$".$infoBilhete[0]['retorno']."</p>
					<p>Data do Bilhete: ".date('d/m/Y', strtotime($infoBilhete[0]['data_bilhete']))."</p>
					<p style='color: green;'>Status do Bilhete: ".$infoBilhete[0]['situacao']."</p>
					";
					if ($infoBilhete[0]['status_pagamento'] === "Pago") {
						echo "<p style='color: green;'>Status do Prêmio: ".$infoBilhete[0]['status_pagamento']."</p>";
					}

					if ($infoBilhete[0]['status_pagamento'] === "Pendente") {
						echo "
						<p style='color: red;'>Status do Prêmio: ".$infoBilhete[0]['status_pagamento']."</p>
						<p><button class='btn btn-primary' type='button' onclick=\"attStatusPagamento('".utf8_encode($infoBilhete[0]['cod_bilhete'])."')\">Pagar Prêmio</button></p>
						";
					}
					echo "</div>";
				}
				else {
					echo "
					<div class='modal-body'>
					<div class='alert alert-danger alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert'>&times;</button>
					Status do bilhete encontra-se </b>PENDENTE</b>. Verifique novamente mais tarde!
					</div>
					</div>
					";						
				}				

				echo "
				<div class='modal-footer'>
				<button type='button' class='btn btn-danger' data-dismiss='modal'>Close</button>
				</div>

				</div>
				</div>
				</div>
				";
			}
			?>				
		</div>
	</div>
</div>

<script>
	function attStatusPagamento(cod)
	{
		var confirmar = confirm("Realizar Pagamento?");
		if(confirmar)
		{
			location.href = "bilhetes.php?cod="+cod;
		}
	}
</script>

<?php require_once("footer.php"); ?>