<?php require_once("header.php"); ?>

<div class="container">
	<div class="row">
		<div class="col-12">

			<form class="form">
				<table class="table table-hover">
					<thead>
						<tr style="text-align: center;">
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php 
						require_once("../classes/Jogos.php");

						$jogosPendentes = new Jogos();
						$resultadoJogosPendentes = $jogosPendentes->jogosPendentes();

						for ($i=0; $i < count($resultadoJogosPendentes); $i++) { 
							$times = $resultadoJogosPendentes[$i]['time_casa']." X ".$resultadoJogosPendentes[$i]['time_fora'];
							$idJogo = $resultadoJogosPendentes[$i]['id'];
							$dataJogo = $resultadoJogosPendentes[$i]['data_jogo'];
							$horaJogo = $resultadoJogosPendentes[$i]['hora_jogo'];

							echo "
							<tr>
							<td>".$times."</td>
							<td style='width: 30px;'><a class='btn btn-primary' href='att_jogo.php?times=$times&idJogo=$idJogo&dataJogo=$dataJogo&horaJogo=$horaJogo'>Editar</a></td>
							</tr>		
							";
						}
						?>		
					</tbody >
				</table>
			</form>

		</div>
	</div>
</div>

<?php require_once("footer.php"); ?>