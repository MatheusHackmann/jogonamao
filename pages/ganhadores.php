<?php 
require_once("header.php"); 
require_once("../classes/Apostas.php");	

if (isset($_GET) && isset($_GET['cod'])) {
	$attStatusPagamento = new Apostas();
	$ganhadores = $attStatusPagamento->attStatusPagamento($_GET['cod']);

	header("Location: ganhadores.php");
}
else if (isset($_GET) && isset($_GET['data_ganhadores'])) {
	$exibirGanhadoresData = new Apostas();
	$ganhadores = $exibirGanhadoresData->exibirGanhadores($_GET['data_ganhadores']);
}
else {
	$exibirGanhadores = new Apostas();
	$ganhadores = $exibirGanhadores->exibirGanhadores(date('Y-m-d'));
}

if (isset($_GET) && isset($_GET['gerar'])) {
	$gerarGanhadores = new Apostas();
	$gerarGanhadores->gerarGanhadores();

	$ganhadores = $gerarGanhadores->exibirGanhadores(date('Y-m-d'));
}

?>

<div class="container">
	<div class="row">
		<div class="col-12 col-md-3 col-lg-3 data-ganhadores">
			<div class="form-group">
				<label for="id_data_ganhadores">Selecione a data:</label>
				<input class="form-control" type="date" name="data_ganhadores" id="id_data_ganhadores" onchange="exibirGanhadoresData()">
			</div>				
			<div class="form-group">
				<label for="id_gerar_ganhadores">Gerar Ganhadores</label>
				<button class="btn btn-primary" id="id_gerar_ganhadores" onclick="gerarGanhadores()">Gerar Ganhadores</button>
			</div>			
		</div>

		<div class="col-12 col-md-8 col-lg-8 table-ganhadores">
			
			<table class="table table-hover">
				<thead>
					<tr style="text-align: center;">
						<th>Cód. Bilhete</th>
						<th>Cliente</th>
						<th>Data Bilhete</th>
						<th>Retorno</th>
						<th>Status de Pagamento</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php
					if (isset($_GET)) {
						

						$sql = new Sql();
						foreach ($ganhadores as $ganhador) {
							$idJogo = explode(',', $ganhador['ap_jogos']);
							$apJogos = $sql->select("SELECT * FROM ap_jogos WHERE fk_cod_bilhete = :FKBILHETE;", array(
								":FKBILHETE" => $ganhador['cod_bilhete']
							));

							echo "
							<tr data-toggle='modal' data-target='#myModal".$ganhador['cod_bilhete']."'>
							<td>".$ganhador['cod_bilhete']."</td>
							<td>".utf8_encode($ganhador['cliente'])."</td>
							<td>".date('d/m/Y', strtotime($ganhador['data_bilhete']))."</td>
							<td>R$".$ganhador['retorno']."</td>
							";

							if ($ganhador['status_pagamento'] === "Pendente") {
								echo "
								<td style='color: red;'><b>Pendente</b></td>
								<td><button class='btn btn-primary' type='button' onclick=\"confirmarPagamento('".$ganhador['cod_bilhete']."')\">Pagar</button></td>
								</tr>";
							}else {
								echo "
								<td style='color: green;'><b>Pago</b></td>
								<td><button class='btn btn-danger' type='button' disabled>PAGO</button></td>
								</tr>";
							}	

							echo "

							<div class='modal fade' id='myModal".$ganhador['cod_bilhete']."'>
							<div class='modal-dialog'>
							<div class='modal-content'>

							<div class='modal-body'>	
							";										

							for ($i=0; $i < count($idJogo); $i++) { 
								$jogos = $sql->select("SELECT * FROM jogos WHERE id = :ID;", array(
									":ID" => $idJogo[$i]
								));

								echo "
								<p>".utf8_encode($jogos[0]['time_casa'])."X".utf8_encode($jogos[0]['time_fora'])."<br>Escolha: ".utf8_encode($apJogos[$i]['escolha'])."<br>--------------------</p>
								";

							}
							echo "<p>Cotas: ".$ganhador['cotas']."
							<br>Valor Apostado: ".$ganhador['valor_aposta']."</p>";

							echo "
							</div>

							<div class='modal-footer'>
							<button type='button' class='btn btn-danger' data-dismiss='modal'>Close</button>
							</div>

							</div>
							</div>
							</div>
							";
						}
					}
					?>					
				</tbody>
			</table>

		</div>
	</div>
</div>

<script>
	function confirmarPagamento(codBilhete)
	{
		var confirmacao = confirm("Realizar Pagamento?");

		if (confirmacao == true) {
			location.href = "ganhadores.php?cod="+codBilhete;
		}
	}
	function exibirGanhadoresData()
	{
		var data = document.getElementById("id_data_ganhadores").value;
		location.href = "ganhadores.php?data_ganhadores="+data;
	}
	function gerarGanhadores()
	{
		location.href = "ganhadores.php?gerar=true";
	}
</script>

<?php require_once("footer.php"); ?>