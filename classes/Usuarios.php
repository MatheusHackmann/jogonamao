<?php
require_once("Sql.php");

class Usuarios
{
	public function gerarPagamento()
	{
		$sql = new Sql();

		$ganhadores = $sql->select("SELECT * FROM apostas WHERE situacao = :SITUACAO;", array(
			":SITUACAO" => "Ganhou"
		));

		$perdedores = $sql->select("SELECT * FROM apostas WHERE situacao = :SITUACAO;", array(
			":SITUACAO" => "Perdeu"
		));		

		if (count($ganhadores) != 0) {
			$infoGanhadores = $sql->select("SELECT cod_bilhete, retorno, data_bilhete FROM apostas WHERE situacao = :SITUACAO;", array(
				":SITUACAO" => "Ganhou"
			));

			foreach ($infoGanhadores as $ganhador) {
				$sql->query("INSERT INTO premios (situacao, valor_premio, data_aposta) VALUES (:SITUACAO, :VALORPREMIO, :DATA);", array(
					":SITUACAO" => "Concluido",
					":VALORPREMIO" => $ganhador['retorno'],
					":DATA" => $ganhador['data_bilhete'],
				));
			}

			$valorTotal = 0;

			$valorApostas = $sql->select("SELECT * FROM valor_apostas;");
			foreach ($valorApostas as $valorAposta) {
				$valorTotal += $valorAposta['valor_aposta'];
			}

			$valorTotalPremios = 0;

			$valorPremios = $sql->select("SELECT * FROM premios WHERE situacao = :SITUACAO;", array(":SITUACAO" => "Concluido"));
			foreach ($valorPremios as $valorPremio) {
				$valorTotalPremios += $valorPremio['valor_premio'];
			}

			$valorValido = $valorTotal - $valorTotalPremios;
			$comissao = ($valorValido*40)/100;
			$valorDivida = $valorValido - $comissao;

			$sql->query("INSERT INTO pagamentos (valor_total_apostas, valor_premios_pagos, valor_comissao, valor_divida, data_pagamento, status_pagamento) VALUES (:VALORTOTALAPOSTAS, :VALORPREMIOSPAGOS, :VALORCOMISSAO, :VALORDIVIDA, :DATA, :STATUS);", array(
				":VALORTOTALAPOSTAS" => $valorTotal,
				":VALORPREMIOSPAGOS" => $valorTotalPremios,
				":VALORCOMISSAO" => $comissao,
				":VALORDIVIDA" => $valorDivida,
				":DATA" => date('Y-m-d'),
				":STATUS" => "Pendente"
			));

			$sql->query("DELETE FROM apostas WHERE situacao = :SITUACAO;", array(
				":SITUACAO" => "Ganhou"
			));
			$sql->query("DELETE FROM apostas WHERE situacao = :SITUACAO;", array(
				":SITUACAO" => "Perdeu"
			));			
			$sql->query("DELETE FROM ap_jogos WHERE situacao = :SITUACAO;", array(
				":SITUACAO" => "Concluido"
			));			
			$sql->query("DELETE FROM valor_apostas WHERE situacao = :SITUACAO;", array(":SITUACAO" => "Concluido"));
			$sql->query("DELETE FROM premios WHERE situacao = :SITUACAO;", array(":SITUACAO" => "Concluido"));	

			return true;		
		}
		else if((count($ganhadores) == 0) && (count($perdedores) != 0)) {		//ULTIMA ALTERACAO FOI AQUI

			$valorTotal = 0;

			$valorApostas = $sql->select("SELECT * FROM valor_apostas;");
			foreach ($valorApostas as $valorAposta) {
				$valorTotal += $valorAposta['valor_aposta'];
			}

			$comissao = ($valorTotal*40)/100;
			$valorDivida = $valorTotal - $comissao;

			$sql->query("INSERT INTO pagamentos (valor_total_apostas, valor_premios_pagos, valor_comissao, valor_divida, data_pagamento, status_pagamento) VALUES (:VALORTOTALAPOSTAS, :VALORPREMIOSPAGOS, :VALORCOMISSAO, :VALORDIVIDA, :DATA, :STATUS);", array(
				":VALORTOTALAPOSTAS" => $valorTotal,
				":VALORPREMIOSPAGOS" => 0,
				":VALORCOMISSAO" => $comissao,
				":VALORDIVIDA" => $valorDivida,
				":DATA" => date('Y-m-d'),
				":STATUS" => "Pendente"
			));

			$sql->query("DELETE FROM apostas WHERE situacao = :SITUACAO;", array(
				":SITUACAO" => "Perdeu"
			));
			$sql->query("DELETE FROM ap_jogos WHERE situacao = :SITUACAO;", array(
				":SITUACAO" => "Concluido"
			));			
			$sql->query("DELETE FROM valor_apostas WHERE situacao = :SITUACAO;", array(":SITUACAO" => "Concluido"));
			
			return true;
		}
		else {
			return false;
		}

	}

	public function attStatusPagamento($id)
	{
		$sql = new Sql();

		$sql->query("UPDATE pagamentos SET status_pagamento = :STATUS WHERE id_pagamento = :ID;", array(
			":STATUS" => "Concluido",
			":ID" => $id
		));
	}	

	public function exibirPagamento()
	{
		$sql = new Sql();

		$pagamentos = $sql->select("SELECT * FROM pagamentos ORDER BY id_pagamento DESC;");

		return $pagamentos;

	}
}