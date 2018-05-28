<?php 
require_once("header.php"); 
require_once("../classes/Apostas.php");	

if (isset($_GET) && isset($_GET['data_perdedores'])) {
	$exibirPerdedoresData = new Apostas();
	$perdedores = $exibirPerdedoresData->exibirPerdedores($_GET['data_perdedores']);
}
else {
	$exibirPerdedores = new Apostas();
	$perdedores = $exibirPerdedores->exibirPerdedores(date('Y-m-d'));
}

?>

<div class="container">
	<div class="row">
		<div class="col-12 col-md-3 col-lg-3 data-perdedores">
			<div class="form-group">
				<label for="id_data_perdedores">Selecione a data:</label>
				<input class="form-control" type="date" name="data_perdedores" id="id_data_perdedores" onchange="exibirPerdedoresData()">
			</div>							
		</div>

		<div class="col-12 col-md-8 col-lg-8 table-ganhadores">
			
			<table class="table table-hover">
				<thead>
					<tr style="text-align: center;">
						<th>Cód. Bilhete</th>
						<th>Cliente</th>
						<th>Data Bilhete</th>
						<th>Situação</th>
					</tr>
				</thead>
				<tbody>
					<?php						

					$sql = new Sql();
					foreach ($perdedores as $perdedor) {
						$idJogo = explode(',', $perdedor['ap_jogos']);
						$apJogos = $sql->select("SELECT * FROM ap_jogos WHERE fk_cod_bilhete = :FKBILHETE;", array(
							":FKBILHETE" => $perdedor['cod_bilhete']
						));

						echo "
						<tr data-toggle='modal' data-target='#myModal".$perdedor['cod_bilhete']."'>
						<td>".$perdedor['cod_bilhete']."</td>
						<td>".utf8_encode($perdedor['cliente'])."</td>
						<td>".date('d/m/Y', strtotime($perdedor['data_bilhete']))."</td>
						<td style='color: red;'><b>Perdeu<b></td>
						</tr>
						";	

						echo "

						<div class='modal fade' id='myModal".$perdedor['cod_bilhete']."'>
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
						echo "<p>Cotas: ".$perdedor['cotas']."
						<br>Valor Apostado: ".$perdedor['valor_aposta']."</p>";

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
					
					?>					
				</tbody>
			</table>

		</div>
	</div>
</div>

<script>
	function exibirPerdedoresData()
	{
		var data = document.getElementById("id_data_perdedores").value;
		location.href = "perdedores.php?data_perdedores="+data;
	}
</script>

<?php require_once("footer.php"); ?>