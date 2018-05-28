<?php require_once("header.php"); ?>

<style type="text/css">
.bilhete {
	margin-top: 5px;
	border: 1px solid #000;
	border-radius: 5px;
	padding: 10px;
	font-size: 14px;
}
</style>

<div class="container">
	<div class="row">
		<div class="col-12 col-md-3 col-lg-3 form-cadastro-jogo" style="height: 600px; overflow: auto;">

			<?php
			require_once("../classes/Apostas.php");
			if ($_POST) {
				$criarBilhete = new Apostas();

				$criarBilhete->criarBilhete($_POST['cliente'], $_POST['valor_cotas'], $_POST['valor'], $_POST['possivel_retorno'], "Pendente", $_POST['apostas']);
			}
			?>

			<!-- DIV QUE EXIBE AS APOSTAS DINAMICAMENTE, CONFORME USUÁRIO CLICA NA OPÇÃO -->
			<div class="col-12" id="bilhete">
			</div>

			<div class="col-12">
				<form method="POST">

					<div class="form-group">
						<label for="id_numero_palpites">Palpites: </label>
						<input class="form-control" type="number" name="numero_palpites" id="id_numero_palpites" value="0" readonly="readonly">
						<div id="div_aviso_palpites" style="color: red; display: none;"><i>O número mínimo de apostas é 3.</i></div>
					</div>

					<div class="form-group">
						<label for="id_valor_cotas">Cotas: </label>
						<input class="form-control" type="text" name="valor_cotas" id="id_valor_cotas" value="0" readonly="readonly">
					</div>	

					<div class="form-group">
						<label for="id_valor">Valor: </label>
						<div class="row">
							<div class="col-3" style="padding-right: 0px !important;">
								<input class="form-control" type="text" disabled="" value="R$" style="background-color: rgb(238, 238, 238);">
							</div>

							<div class="col-6" style="padding-left: 0px !important; padding-right: 0px !important">
								<input class="form-control" type="text" name="valor" id="id_valor" pattern="[0-9]+$" placeholder="Mín R$5,00" min="5" onkeyup="calcularRetorno()" disabled>
							</div>

							<div class="col-3" style="padding-left: 0px !important">
								<input class="form-control" type="text" disabled="" value=",00" style="background-color: rgb(238, 238, 238);">
							</div>
						</div>
					</div>								

					<div class="form-group">
						<label for="id_cliente">Cliente:</label>
						<input class="form-control" type="text" name="cliente" id="id_cliente" required>
					</div>

					<div class="form-group" id="div_possivel_retorno">
						<label for="id_retorno">Possivel Retorno:</label>
						<input class="form-control" type="text" name="possivel_retorno" id="id_retorno" value="0" readonly="readonly">
						<div id="div_aviso" style="color: red; display: none;"><i>O valor máximo de retorno é R$5.000,00</i></div>
					</div>	

					<input type="hidden" name="apostas[]" id="apostas">
					<button class="btn btn-primary" id="id_btn_apostar" onclick="alert('Aposta realizada! Consulte seu bilhete no próximo dia.')" type="submit" disabled>Apostar</button>
				</form>
			</div>
		</div>
		<div class="offset-md-1 col-12 col-md-8 col-lg-8 form-cadastro-jogo">
			<h3>Partidas Disponíveis</h3>
			<h6><i><?php echo date("d/m/Y")." - ".date("H:i:s"); ?></i></h6>			


			<?php

			require_once("../classes/Jogos.php");

			$jogos = new Jogos();

			$copas = $jogos->jogosDisponiveis();
			$sql = new Sql(); 

			foreach ($copas as $copa) {
				$idJogo = explode(',', $copa['jogos']);

				echo "

				<div class='row' style='background-color: rgb(25, 118, 210); color: #fff; border-radius: 5px;'>
				<p><b>".utf8_encode($copa['nome_copa'])."</b></p>
				</div>
				<div class='row' style='margin-top: 10px;'>
				<table class='table e'>
				<thead>
				<tr>
				<th>+</th>
				<th>Casa</th>
				<th>Empate</th>
				<th>Fora</th>
				<th></th>
				</tr>
				</thead>
				<tbody>	
				";

				for ($i=0; $i < count($idJogo); $i++) { 
					$jogosDisponiveis = $sql->select("SELECT * FROM jogos WHERE id = :ID AND status_jogo = :STATUSJOGO;",
						array(
							":ID" => $idJogo[$i],
							":STATUSJOGO" => "Pendente"
						));

					foreach ($jogosDisponiveis as $jogo) {
						if ($copa['id_copa'] == $jogo['fk_copa']) {
							$dataHora = date('d/m/Y', strtotime($jogo['data_jogo'])) . " - " . $jogo['hora_jogo'];

							echo "
							<tr id='linha".$jogo['id']."'>
							";

							//Botão Para Mais Apostas
							echo "								
							<td>
							<div class='dropdown'>
							<button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>
							+ 20
							</button>
							<div class='dropdown-menu' style='width: 500px;'>
							
							<div class='col-12'>
							
							<table class='table' style='width: 100%;'>
							<thead>
							<tr>
							<th>Impar</ht>
							<th>Par</ht>
							</tr>									
							</thead>
							<tbody>
							<tr>
							<td><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','Impar','1.10','".$dataHora."','".$jogo['id']."')\">1.10</button></td>
							<td><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','Par','1.10','".$dataHora."','".$jogo['id']."')\">1.10</button></td>
							</tr>
							</tbody>

							<thead>
							<tr>
							<th>Ambas marcam: Sim</ht>
							<th>Ambas marcam: Não</ht>
							</tr>									
							</thead>
							<tbody>
							<tr>
							<td><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','Ambas marcam: Sim','1.50','".$dataHora."','".$jogo['id']."')\">1.50</button></td>
							<td><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','Ambas marcam: Não','1.30','".$dataHora."','".$jogo['id']."')\">1.30</button></td>
							</tr>
							</tbody>

							<thead>
							<tr>
							<th>Resultado Certo</ht>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							</tr>									
							</thead>
							<tbody>
							<tr>";

							echo "
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','0x0','6.70','".$dataHora."','".$jogo['id']."')\">0x0 - 6.70</button></td>
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','0x1','4.80','".$dataHora."','".$jogo['id']."')\">0x1 - 4.80</button></td>
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','0x2','5.60','".$dataHora."','".$jogo['id']."')\">0x2 - 5.60</button></td>
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','0x3','8.30','".$dataHora."','".$jogo['id']."')\">0x3 - 8.30</button></td>
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','0x4','25.40','".$dataHora."','".$jogo['id']."')\">0x4 - 25.40</button></td>
							</tr>
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','1x0','7.20','".$dataHora."','".$jogo['id']."')\">1x0 - 7.20</button></td>
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','1x1','3.60','".$dataHora."','".$jogo['id']."')\">1x1 - 3.60</button></td>
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','1x2','5.30','".$dataHora."','".$jogo['id']."')\">1x2 - 5.30</button></td>
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','1x3','10.30','".$dataHora."','".$jogo['id']."')\">1x3 - 10.30</button></td>
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','1x4','27.40','".$dataHora."','".$jogo['id']."')\">1x4 - 27.40</button></td>
							</tr>							
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','2x0','17.00','".$dataHora."','".$jogo['id']."')\">2x0 - 17.00</button></td>
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','2x1','9.40','".$dataHora."','".$jogo['id']."')\">2x1 - 9.40</button></td>
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','2x2','10.30','".$dataHora."','".$jogo['id']."')\">2x2 - 10.30</button></td>
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','2x3','22.70','".$dataHora."','".$jogo['id']."')\">2x3 - 22.70</button></td>
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','2x4','50.00','".$dataHora."','".$jogo['id']."')\">2x4 - 50.00</button></td>
							</tr>							
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','3x0','43.50','".$dataHora."','".$jogo['id']."')\">3x0 - 43.50</button></td>
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','3x1','30.20','".$dataHora."','".$jogo['id']."')\">3x1 - 30.20</button></td>
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','3x2','35.90','".$dataHora."','".$jogo['id']."')\">3x2 - 35.90</button></td>
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','3x3','60.70','".$dataHora."','".$jogo['id']."')\">3x3 - 60.70</button></td>
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','3x4','70.40','".$dataHora."','".$jogo['id']."')\">3x4 - 70.40</button></td>
							</tr>							
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','4x0','100.00','".$dataHora."','".$jogo['id']."')\">4x0 - 100.00</button></td>
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','4x1','120.50','".$dataHora."','".$jogo['id']."')\">4x1 - 120.50</button></td>
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','4x2','135.40','".$dataHora."','".$jogo['id']."')\">4x2 - 135.40</button></td>
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','4x3','150.90','".$dataHora."','".$jogo['id']."')\">4x3 - 150.90</button></td>
							<td style='width: 50px;'><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','4x4','160.60','".$dataHora."','".$jogo['id']."')\">4x4 - 160.60</button></td>
							</tr>							
							</tbody>														

							</table>

							</div>

							</div>
							</div>
							</td>";

								//Aposta Casa
							echo "
							<td><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','Casa','".$jogo['ap_casa']."','".$dataHora."','".$jogo['id']."')\">".$jogo['ap_casa']."</button></td>";

								//Aposta Empate
							echo "
							<td><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','Empate','".$jogo['ap_empate']."','".$dataHora."','".$jogo['id']."')\">".$jogo['ap_empate']."</button></td>";

								//Aposta Fora
							echo "
							<td><button class='options btn' onclick=\"addAposta('".$jogo['time_casa']."','".$jogo['time_fora']."','Fora','".$jogo['ap_fora']."','".$dataHora."','".$jogo['id']."')\">".$jogo['ap_fora']."</button></td>";

							echo "
							<td style='width: 70%;text-align: right;'><b>".utf8_encode($jogo['time_casa'])." X ".utf8_encode($jogo['time_fora'])."</b><br><i style='color: #1976D2;'>".$dataHora."</i></td>";							
							echo "
							</tr>
							";
						}
					}
				}

				echo "
				</tbody>
				</table>
				</div>
				";
			}

			?>
		</div>
	</div>
</div>

<script>

	function addAposta(timeCasa, timeFora, escolha, cota, dataHora, id){
		//Crio uma variavel com o id da linha
		var idLinhaOff = "linha"+id;

		//Setando o display como none, escondendo a linha ao clicar em alguma opção
		document.getElementById(idLinhaOff).style.display = 'none';

		//Adiciona o texto da aposta na div bilhete
		var divAposta = document.getElementById("bilhete");
		divAposta.innerHTML += '<div class="bilhete"><b>'+timeCasa+' X '+timeFora+'</b><br>'+dataHora+'<br>Escolha: <b>'+escolha+'</b> - Cota: <b>'+cota+'</b>';

		//Pega o valor do campo Numero de palpites, adiciona 1
		var numeroPalpites = document.getElementById("id_numero_palpites").value;

		numeroPalpites = parseInt(numeroPalpites) + parseInt(1);
		document.getElementById("id_numero_palpites").value = numeroPalpites;		

		//Pega o valor do campo Cotas
		var valorCampoCotas = document.getElementById("id_valor_cotas").value;
		parseFloat(valorCampoCotas).toFixed(2);
		var valorArredondado = parseFloat(cota).toFixed(2);

		//Adiciona o valor atualizado da cota na variavel valorAttCotas
		var valorAttCotas = parseFloat(valorCampoCotas) + parseFloat(valorArredondado);
		parseFloat(valorAttCotas).toFixed(2);

		//Seta o valor do campo de cotas com o valor atual
		document.getElementById("id_valor_cotas").value = parseFloat(valorAttCotas);

		//Cria um campo hidden com as informações de cada escolha de aposta
		let campo = document.createElement("input");
		campo.setAttribute("type", "hidden");
		campo.name = "apostas[]";
		campo.value = id+','+escolha;
		apostas.appendChild(campo);	

		verificaNumeroPalpites();

	}	

	function calcularRetorno(){
		var cotas = document.getElementById("id_valor_cotas").value;
		var valorAposta = document.getElementById("id_valor").value;

		var possivelRetorno = cotas * valorAposta;
		parseFloat(possivelRetorno).toFixed(2);

		if (parseFloat(possivelRetorno).toFixed(2) > 5000) {
			document.getElementById('div_aviso').style.display = 'block';
			document.getElementById("id_retorno").value = '5.000';
		}
		else{
			document.getElementById('div_aviso').style.display = 'none';
			document.getElementById("id_retorno").value = parseFloat(possivelRetorno);
		}
	}

	function verificaNumeroPalpites(){
		var numeroPalpites = document.getElementById("id_numero_palpites").value;
		
		if (numeroPalpites < 3) {
			document.getElementById("div_aviso_palpites").style.display = 'block';
		}
		else {
			document.getElementById("div_aviso_palpites").style.display = 'none';
			document.getElementById("id_btn_apostar").disabled = false;
			document.getElementById("id_valor").disabled = false;
		}
	}
</script>

<?php require_once("footer.php"); ?>