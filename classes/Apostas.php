<?php
require_once("Sql.php");

class Apostas {

	public function criarBilhete($cliente, $totalCotas, $valorApostado, $retorno, $jogos){

		//Gera um código único de bilhete
		$codBilhete = strtoupper(substr(md5(date("YmdHis")), 1, 6));		

		$sql = new Sql();

		$sql->query("INSERT INTO apostas (cod_bilhete, cliente, cotas, valor_aposta, retorno, situacao, data_bilhete, status_pagamento) VALUES (:CODBILHETE, :CLIENTE, :COTAS, :VALORAPOSTA, :RETORNO, :SITUACAO, :DATA, :STATUS);", array(
			":CODBILHETE" => utf8_decode($codBilhete),
			":CLIENTE" => utf8_decode($cliente),
			":COTAS" => $totalCotas,
			":VALORAPOSTA" => $valorApostado,
			":RETORNO" => $retorno,
			":SITUACAO" => "Pendente",
			":DATA" => date('Y-m-d'),
			":STATUS" => "Pendente"
		));

		$sql->query("INSERT INTO valor_apostas (situacao, valor_aposta, data_aposta) VALUES (:SITUACAO, :VALORAPOSTA, :DATA);", array(
			":SITUACAO" => "Concluido",
			":VALORAPOSTA" => $valorApostado,
			":DATA" => date('Y-m-d')
		));

		foreach ($jogos as $jogo) {
			if ($jogo != "") {
				$valor = explode(",", $jogo);

				$idJogo = $valor[0];
				$escolhaJogo = $valor[1];

				$sql->query("INSERT INTO ap_jogos (fk_cod_bilhete, jogo, escolha, situacao) VALUES (:FKCODBILHETE, :IDJOGO, :ESCOLHA, :SITUACAO);", array(
					":FKCODBILHETE" => $codBilhete,
					":IDJOGO" => $idJogo,
					":ESCOLHA" => $escolhaJogo,
					":SITUACAO" => "Pendente"
				));				
			}
			else {

			}
		}
	}

	public function verificarBilhete($codBilhete){
		
		$sql = new Sql();

		$bilheteExiste = $sql->select("SELECT * FROM apostas WHERE cod_bilhete = :CODBILHETE;", array(
			":CODBILHETE" => utf8_decode($codBilhete)
		));

		if (count($bilheteExiste) == 0) {
			$alert = "Código de bilhete não encontrado, verifique se digitou corretamente e tente novamente!";
			return $alert;
			exit();
		}		

		if ($bilheteExiste[0]['data_bilhete'] == date('Y-m-d')) {
			return false;
			exit();
		}
		else {

			$jogosBilhete = $sql->select("SELECT * FROM ap_jogos WHERE fk_cod_bilhete = :CODBILHETE;", array(
				":CODBILHETE" => utf8_decode($codBilhete)
			));	

			$situacaoJogo = true;

			for ($i=0; $i < count($jogosBilhete); $i++) { 
				if ($jogosBilhete[$i]['situacao'] === "Pendente") {
					$situacaoJogo = false;
					break;			
				}				

				if ($situacaoJogo == true) {

					$infoJogo = $sql->select("SELECT esc_1, esc_2, esc_3, esc_4 FROM jogos WHERE id = :ID;", array(
						":ID" => $jogosBilhete[$i]['jogo']
					));

					if (($jogosBilhete[$i]['escolha'] != $infoJogo[0]['esc_1']) && ($jogosBilhete[$i]['escolha'] != $infoJogo[0]['esc_2']) && ($jogosBilhete[$i]['escolha'] != $infoJogo[0]['esc_3']) && ($jogosBilhete[$i]['escolha'] != $infoJogo[0]['esc_4'])) {

						$sql->query("UPDATE apostas SET situacao = :SITUACAO, status_pagamento = :STATUS WHERE cod_bilhete = :BILHETE;", array(
							":SITUACAO" => "Perdeu",
							":STATUS" => "Pendente",
							":BILHETE" => utf8_decode($codBilhete)
						));
					
						break;

					}
					else {
						$sql->query("UPDATE apostas SET situacao = :SITUACAO WHERE cod_bilhete = :BILHETE;", array(
							":SITUACAO" => "Ganhou",
							":BILHETE" => utf8_decode($codBilhete)
						));																			
					}					
				}
			}
			return true;
		}
	}

	public function attStatusPagamento($codBilhete)
	{
		$sql = new Sql();

		$sql->query("UPDATE apostas SET status_pagamento = :STATUS WHERE cod_bilhete = :CODBILHETE;", array(
			":STATUS" => "Pago",
			":CODBILHETE" => $codBilhete
		));
	}

	public function exibirBilhete($codBilhete){

		$sql = new Sql();

		$bilhete = $sql->select("SELECT * FROM apostas WHERE cod_bilhete = :CODBILHETE;", array(
			":CODBILHETE" => utf8_decode($codBilhete)
		));	

		return $bilhete;
	}

}