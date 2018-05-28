<?php
require_once("Sql.php");

class Usuarios
{
	public function cadastrarUsuario($nomeCompleto, $login, $senha)
	{
		echo $nomeCompleto."<br>".$login."<br>".md5($senha);
		$sql = new Sql();

		$sql->query("INSERT INTO usuarios (nome_usuario, login_usuario, senha_usuario) VALUES (:NOME, :LOGIN, :SENHA);", array(
			":NOME" => utf8_decode($nomeCompleto),
			":LOGIN" => utf8_decode($login),
			":SENHA" => md5($senha)
		));
	}

	public function gerarPagamento()
	{
		$sql = new Sql();

		$ganhadores = $sql->select("SELECT * FROM apostas WHERE situacao = :SITUACAO;", array(
			":SITUACAO" => "Ganhou"
		));
		if (count($ganhadores) < 1) {
			return false;
		}
		else {

			$infoGanhadores = $sql->select("SELECT cod_bilhete, retorno, data_bilhete FROM apostas WHERE situacao = :SITUACAO;", array(
				":SITUACAO" => "Ganhou"
			));

			foreach ($infoGanhadores as $ganhador) {
				$sql->query("INSERT INTO premios (fk_cod_bilhete, valor_premio, data_aposta, situacao) VALUES (:FKCODBILHETE, :VALORPREMIO, :DATA, :SITUACAO);", array(
					":FKCODBILHETE" => $ganhador['cod_bilhete'],
					":VALORPREMIO" => $ganhador['retorno'],
					":DATA" => $ganhador['data_bilhete'],
					":SITUACAO" => "Pendente"
				));

				$sql->query("UPDATE apostas SET situacao = :SITUACAO WHERE cod_bilhete = :CODBILHETE;", array(
					":SITUACAO" => "Concluido",
					":CODBILHETE" => $ganhador['cod_bilhete']
				));
			}

			$valorPremiosPagos = $sql->select("SELECT valor_premio, fk_cod_bilhete FROM premios WHERE situacao = :SITUACAO;", array(
				":SITUACAO" => "Pendente"
			));

			$valorTotalPremiosPagos = 0;	

			foreach ($valorPremiosPagos as $valorPremioPago) {
				$valorTotalPremiosPagos = $valorTotalPremiosPagos + $valorPremioPago['valor_premio'];
				$sql->query("UPDATE premios SET situacao = :SITUACAO WHERE fk_cod_bilhete = :FKCODBILHETE;", array(
					":SITUACAO" => "Concluido",
					":FKCODBILHETE" => $valorPremioPago['fk_cod_bilhete']
				));
			}


			$valorApostas = $sql->select("SELECT * FROM valor_apostas WHERE situacao = :SITUACAO;", array(
				":SITUACAO" => "Pendente"
			));

			$valorTotal = 0;

			foreach ($valorApostas as $valorAposta) {
				$valorTotal = $valorTotal + $valorAposta['valor_aposta'];

				$sql->query("UPDATE valor_apostas SET situacao = :SITUACAO WHERE fk_cod_bilhete = :FKCODBILHETE;", array(
					":SITUACAO" => "Concluido",
					":FKCODBILHETE" => $valorAposta['fk_cod_bilhete']
				));
			}

			$valorValido = $valorTotal - $valorTotalPremiosPagos;
			$comissao = ($valorValido*40)/100;

			$valorDivida = $valorValido - $comissao;

			$sql->query("INSERT INTO pagamentos (fk_id_usuario, valor_total_apostas, valor_premios_pagos, valor_comissao, valor_divida, data_pagamento, status_pagamento) VALUES (:FKUSUARIO, :VALORTOTALAPOSTAS, :VALORPREMIOSPAGOS, :VALORCOMISSAO, :VALORDIVIDA, :DATA, :STATUS);", array(
				":FKUSUARIO" => 1,
				":VALORTOTALAPOSTAS" => $valorTotal,
				":VALORPREMIOSPAGOS" => $valorTotalPremiosPagos,
				":VALORCOMISSAO" => $comissao,
				":VALORDIVIDA" => $valorDivida,
				":DATA" => date('Y-m-d'),
				":STATUS" => "Pendente"
			));

			return true;
		}
		

	}

	public function attStatusPagamento($data)
	{
		$sql = new Sql();

		$sql->query("UPDATE pagamentos SET status_pagamento = :STATUS WHERE data_pagamento = :DATA;", array(
			":STATUS" => "Concluido",
			":DATA" => date('Y-m-d', strtotime($data))
		));
	}	

	public function exibirPagamento()
	{
		$sql = new Sql();

		$pagamentos = $sql->select("SELECT * FROM pagamentos;");

		return $pagamentos;

	}
}