<?php require_once("header.php"); ?>

<?php 
require_once("../classes/Jogos.php");

$cadJogo = new Jogos();

$copas = $cadJogo->buscarCopas();

//Se o botão CADASTRAR for clicado, chama o método para cadastrar o jogo
if ($_POST) {

	$cadJogo->cadastrarJogo($_POST['time_casa'], $_POST['time_fora'], $_POST['data_jogo'], $_POST['hora_jogo'], $_POST['aposta_casa'], $_POST['aposta_empate'], $_POST['aposta_fora'], $_POST['copas']); //Adicionar POST da copa selecionada
}

?>

<div class="container-fluid">
	<div class="row">
		<div class="offset-md-2 col-12 col-md-8 col-lg-8">
			<form class="form-control form-cadastro-jogo" method="POST">

				<?php
				if ($_POST) {
					echo "
					<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert'>&times;</button>
					Jogo cadastrado com sucesso!
					</div>
					";
				}
				?>

				<div class="row">
					<div class="col-12 col-md-6 col-lg-6 form-group">
						<label>Copa:</label>
						<select class="form-control" name="copas">
							<?php 
							//Tras todas as copas como opções
							foreach ($copas as $copa) {
								echo "<option value='".$copa['id_copa']."'>".utf8_encode($copa['nome_copa'])."</option>";
							} 
							?>
						</select>
					</div>
				</div>

				<div class="row">
					<div class="col-12 col-md-6 col-lg-6 form-group">
						<label for="id_time_casa">Time De Casa: </label>
						<input class="form-control" type="text" name="time_casa" id="id_time_casa" required autocomplete="off">
					</div>

					<div class="col-12 col-md-6 col-lg-6">
						<label for="id_time_fora">Time De Fora: </label>
						<input class="form-control" type="text" name="time_fora" id="id_time_fora" required autocomplete="off">
					</div>						
				</div>

				<div class="row">
					<div class="col-12 col-md-4 col-lg-4 form-group">
						<label for="id_aposta_casa">Casa: </label>
						<input class="form-control" type="text" name="aposta_casa" id="id_aposta_casa" autocomplete="off">
					</div>

					<div class="col-12 col-md-4 col-lg-4 form-group">
						<label for="id_aposta_empate">Empate: </label>
						<input class="form-control" type="text" name="aposta_empate" id="id_aposta_empate" required autocomplete="off">	
					</div>

					<div class="col-12 col-md-4 col-lg-4 form-group">
						<label for="id_aposta_fora">Fora: </label>
						<input class="form-control" type="text" name="aposta_fora" id="id_aposta_fora" required autocomplete="off">			
					</div>	
				</div>				

				<div class="row">
					<div class="col-12 col-md-3 col-lg-3 form-group">
						<label for="id_data_jogo">Data do jogo: </label>
						<input class="form-control" type="date" name="data_jogo" id="id_data_jogo" required>		
					</div>
					<div class="col-12 col-md-3 col-lg-3 form-group">
						<label for="id_hora_jogo">Hora do jogo: </label>
						<input class="form-control" type="text" name="hora_jogo" id="id_hora_jogo" required autocomplete="off">						
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