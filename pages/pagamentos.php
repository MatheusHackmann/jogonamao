<?php 
require_once("../classes/Usuarios.php");
require_once("header.php");
$pagamentos = new Usuarios();
$confirmGerarPagamento = true;

if ($_POST) {
	$senha = "HackmannConfirm";

	if ($_POST['senha_confirmar_fechamento'] === $senha) {
		$confirmGerarPagamento = $pagamentos->gerarPagamento();
	}
	else {
		echo "<script> alert('Senha Incorreta!'); </script>";
	}
}

$infoPagamentos = $pagamentos->exibirPagamento();

if ($_GET) {
	$pagamentos->attStatusPagamento($_GET['data']);

	header("Location: pagamentos.php");
}

?>

<div class="container">
	<div class="row">
		<div class="offset-md-2 col-12 col-md-8 col-lg-8 table-ganhadores">
			<?php
			if ($_POST && $confirmGerarPagamento == false) {
				echo "
				<div class='alert alert-danger alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert'>&times;</button>
				Não há nenhum valor disponível no momento.
				</div>
				";				
			}
			?>

			<div class="row">
				<div class="col-12" style="margin-bottom: 10px;">
					<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modalInfoFechamento">Realizar Fechamento da semana</button>

					<!-- The Modal -->
					<div class="modal fade" id="modalInfoFechamento">
						<div class="modal-dialog">
							<div class="modal-content">

								
								<div class="modal-header" style="text-align: center;">
									<h4 class="modal-title">Confirmar fechamento da semana</h4>
									<button type="button" class="close" data-dismiss="modal">&times;</button>
								</div>

								
								<div class="modal-body">
									<form method="POST">
										<div class="form-group">
											<label for="id_senha_confirmar_fechamento">Senha: </label>
											<input class="form-control" type="password" name="senha_confirmar_fechamento" id="id_senha_confirmar_fechamento">
										</div>
										<div class="form-group">
											<button class="btn btn-primary" type="submit">Confirmar fechamento</button>
										</div>
									</form>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-12">
					<table class="table table-hover">
						<thead>
							<tr style="text-align: center;">
								<th>Data do Fechamento</th>
								<th>Status de Pagamento</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<?php
								foreach ($infoPagamentos as $infoPagamento) {
									echo "
									<div class='modal fade' id='modalInfoFechamento".$infoPagamento['valor_total_apostas']."'>
									<div class='modal-dialog'>
									<div class='modal-content'>

									<div class='modal-body'>
									<p>Data: ".date('d/m/Y', strtotime($infoPagamento['data_pagamento']))."</p>
									<p>Valor Total de Apostas: R$".$infoPagamento['valor_total_apostas'].",00</p>
									<p>Prêmios Pagos: ".$infoPagamento['valor_premios_pagos']."</p>
									<p>Comissão (40%): ".$infoPagamento['valor_comissao']."</p>
									<p>Valor á Pagar: ".$infoPagamento['valor_divida']."</p>
									<p>Status do Pagamento: ".$infoPagamento['status_pagamento']."</p>
									</div>

									</div>
									</div>
									</div>										
									";

									if ($infoPagamento['valor_comissao'] < 1 || $infoPagamento['valor_divida'] < 1) {
										echo "
										<tr style='color: red;'>
										<td data-toggle='modal' data-target='#modalInfoFechamento".$infoPagamento['valor_total_apostas']."'>".date('d/m/Y', strtotime($infoPagamento['data_pagamento']))."</td>
										<td>Prejuízo</td>
										<td><button class='btn btn-danger' disabled>Prejuízo</button></td>
										</tr>
										";
									}
									else if ($infoPagamento['status_pagamento'] === "Concluido") {
										echo "
										<tr>
										<td data-toggle='modal' data-target='#modalInfoFechamento".$infoPagamento['valor_total_apostas']."'>".date('d/m/Y', strtotime($infoPagamento['data_pagamento']))."</td>
										<td>".$infoPagamento['status_pagamento']."</td>
										<td><button class='btn btn-success' disabled>Concluido</button></td>
										</tr>
										";	
									}
									else {

										echo "
										<tr>
										<td data-toggle='modal' data-target='#modalInfoFechamento".$infoPagamento['valor_total_apostas']."'>".date('d/m/Y', strtotime($infoPagamento['data_pagamento']))."</td>
										<td>".$infoPagamento['status_pagamento']."</td>
										<td><button class='btn btn-primary' onclick=\" confirmarPagamento('".$infoPagamento['data_pagamento']."'); \">Realizar Pagamento</button></td>
										</tr>
										";									
									}
								}
								?>
							</tr>

						</tbody>
					</table>
				</div>
			</div>

		</div>
	</div>
</div>

<script>
	function confirmarPagamento(data)
	{
		var confirmar = confirm("Realizar pagamento?");

		if (confirmar) {
			location.href = "pagamentos.php?data="+data;
		}
	}
	
</script>

<?php 
require_once("footer.php");
?>