<?php
require_once("Sql.php");

class Apostas {

	public function criarBilhete($cliente, $totalCotas, $valorApostado, $retorno, $situacao, $jogos){

		echo "
		<pre>
		$cliente
		$totalCotas
		$valorApostado
		$retorno
		$situacao
		</pre>";
		echo "<br><br>";
		print_r($jogos);

		//Arredonda o valor das variaveis e limita para 2 decimais
		$totalCotas = number_format(floatval($totalCotas), 2, ".", ".");
		$retorno1 = number_format(floatval($retorno), 2, ".", ".");

		//Gera um código único de bilhete
		$codBilhete = strtoupper(substr(md5(date("YmdHis")), 1, 6));

		$sql = new Sql();

		$sql->query("INSERT INTO apostas (cod_bilhete, cliente, cotas, valor_aposta, retorno, situacao, data_bilhete, status_pagamento) VALUES (:CODBILHETE, :CLIENTE, :COTAS, :VALORAPOSTA, :RETORNO, :SITUACAO, :DATA, :STATUS);", array(
			":CODBILHETE" => $codBilhete,
			":CLIENTE" => utf8_decode($cliente),
			":COTAS" => $totalCotas,
			":VALORAPOSTA" => $valorApostado,
			":RETORNO" => $retorno1,
			":SITUACAO" => $situacao,
			":DATA" => date('Y-m-d'),
			":STATUS" => "Pendente"
		));

		$sql->query("INSERT INTO valor_apostas (fk_cod_bilhete, valor_aposta, data_aposta) VALUES (:FKCODBILHETE, :VALORAPOSTA, :DATA);", array(
			":FKCODBILHETE" => $codBilhete,
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

	public function gerarGanhadores(){
		
		$sql = new Sql();

		//Seleciona todos os bilhetes
		$bilhetes = $sql->select("SELECT cod_bilhete FROM apostas;");

		foreach ($bilhetes as $codBilhete) {
			$jogosBilhete = $sql->select("SELECT * FROM ap_jogos WHERE fk_cod_bilhete = :CODBILHETE;", array(
				":CODBILHETE" => $codBilhete['cod_bilhete']
			));	

			$situacaoJogo = true;

			for ($i=0; $i < count($jogosBilhete); $i++) { 
				if ($jogosBilhete[$i]['situacao'] === "Pendente") {
					$situacaoJogo = false;
				}

				if ($situacaoJogo == true) {
					
					$infoJogo = $sql->select("SELECT esc_1, esc_2, esc_3, esc_4 FROM jogos WHERE id = :ID;", array(
						":ID" => $jogosBilhete[$i]['jogo']
					));

					if (($jogosBilhete[$i]['escolha'] != $infoJogo[0]['esc_1']) && ($jogosBilhete[$i]['escolha'] != $infoJogo[0]['esc_2']) && ($jogosBilhete[$i]['escolha'] != $infoJogo[0]['esc_3']) && ($jogosBilhete[$i]['escolha'] != $infoJogo[0]['esc_4'])) {

						$sql->query("UPDATE apostas SET situacao = :SITUACAO, status_pagamento = :STATUS WHERE cod_bilhete = :BILHETE;", array(
							":SITUACAO" => "Perdeu",
							":STATUS" => "Pendente",
							":BILHETE" => $codBilhete['cod_bilhete']
						));
						break;

					}
					else {
						$sql->query("UPDATE apostas SET situacao = :SITUACAO WHERE cod_bilhete = :BILHETE;", array(
							":SITUACAO" => "Ganhou",
							":BILHETE" => $codBilhete['cod_bilhete']
						));						
					}				
				}
			}					
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

	public function exibirGanhadores($data){

		$sql = new Sql();

		$ganhadores = $sql->select("SELECT a.*, GROUP_CONCAT(jogo SEPARATOR ',') AS ap_jogos FROM apostas as a INNER JOIN ap_jogos as aj ON a.cod_bilhete = aj.fk_cod_bilhete  WHERE a.situacao = :SITUACAO AND data_bilhete = :DATA GROUP BY a.cod_bilhete ORDER BY status_pagamento = :STATUS DESC;", array(
			":SITUACAO" => "Ganhou",
			":DATA" => $data,
			":STATUS" => "Pendente"
		));	

		return $ganhadores;
	}

	public function exibirPerdedores($data){

		$sql = new Sql();

		$perdedores = $sql->select("SELECT a.*, GROUP_CONCAT(jogo SEPARATOR ',') AS ap_jogos FROM apostas as a INNER JOIN ap_jogos as aj ON a.cod_bilhete = aj.fk_cod_bilhete  WHERE a.situacao = :SITUACAO AND data_bilhete = :DATA GROUP BY a.cod_bilhete ;", array(
			":SITUACAO" => "Perdeu",
			":DATA" => $data
		));	

		return $perdedores;
	}

}