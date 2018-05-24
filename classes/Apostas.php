<?php
require_once("Sql.php");

class Apostas {

	public function criarBilhete($cliente, $totalCotas, $valorApostado, $retorno, $situacao, $jogos){

		//Arredonda o valor das variaveis e limita para 2 decimais
		$totalCotas = number_format(floatval($totalCotas), 2, ".", ".");
		$retorno = number_format(floatval($retorno), 2, ".", ".");

		//Gera um código único de bilhete
		$codBilhete = strtoupper(substr(md5(date("YmdHis")), 1, 6));

		$sql = new Sql();

		$sql->query("INSERT INTO apostas (cod_bilhete, cliente, cotas, valor_aposta, retorno, situacao) VALUES (:CODBILHETE, :CLIENTE, :COTAS, :VALORAPOSTA, :RETORNO, :SITUACAO);", array(
			":CODBILHETE" => $codBilhete,
			":CLIENTE" => utf8_decode($cliente),
			":COTAS" => $totalCotas,
			":VALORAPOSTA" => $valorApostado,
			":RETORNO" => $retorno,
			":SITUACAO" => $situacao
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
		}
	}

	private function verificarJogosBilhete($codBilhete){
		
		$sql = new Sql();

		$verificarExistenciaBilhete = $sql->select("SELECT * FROM apostas WHERE cod_bilhete = :CODBILHETE;", array(
			":CODBILHETE" => $codBilhete
		));

		//Verifica se bilhete não existir, retorna false
		if (count($verificarExistenciaBilhete) == 0) {
			return false;
			exit();
		}else {

			$jogosBilhete = $sql->select("SELECT * FROM ap_jogos WHERE fk_cod_bilhete = :CODBILHETE;", array(
				":CODBILHETE" => $codBilhete
			));

			$situacaoJogo = true;

			for ($i=0; $i < count($jogosBilhete); $i++) { 
				if ($jogosBilhete[$i]['situacao'] === "Pendente") {
					$situacaoJogo = false;
				}
			}

			//Se todos os jogos da aposta estiverem concluidos, analisa qual a escolha ganhadora de cada jogo, verifica se a escolha do bilhete é diferente de todas, se for, seta o bilhete como perdedor
			if ($situacaoJogo == true) {
				for ($i=0; $i < count($jogosBilhete); $i++) { 
					$infoJogo = $sql->select("SELECT esc_1, esc_2, esc_3, esc_4 FROM jogos WHERE id = :ID;", array(
						":ID" => $jogosBilhete[$i]['jogo']
					));

					if (($jogosBilhete[$i]['escolha'] != $infoJogo[0]['esc_1']) 
						&&
						($jogosBilhete[$i]['escolha'] != $infoJogo[0]['esc_2']) 
						&& 
						($jogosBilhete[$i]['escolha'] != $infoJogo[0]['esc_3']) 
						&&
						($jogosBilhete[$i]['escolha'] != $infoJogo[0]['esc_4'])) {

						$sql->query("UPDATE apostas SET situacao = :SITUACAO WHERE cod_bilhete = :BILHETE;", array(
							":BILHETE" => $codBilhete,
							":SITUACAO" => "Perdeu"
						));						
				}else {
					$sql->query("UPDATE apostas SET situacao = :SITUACAO WHERE cod_bilhete = :BILHETE;", array(
						":BILHETE" => $codBilhete,
						":SITUACAO" => "Ganhou"
					));					
				}
			}				
			return true;
		}
	}

}

public function verificarBilhete($codBilhete){

	$existenciaBilhete = $this->verificarJogosBilhete($codBilhete);

		//Se retornar false, não existe nenhum bilhete com o código informado
	if ($existenciaBilhete == false) {
		return false;
		exit();
	}

	$sql = new Sql();

	$infoBilhete = $sql->select("SELECT * FROM apostas WHERE cod_bilhete = :CODBILHETE;", array(
		":CODBILHETE" => $codBilhete
	));

	foreach ($infoBilhete as $bilhete) {
			//Se situacao do bilhete for pendente, retorna false, se não retorna true
		if ($bilhete['situacao'] === "Pendente") {
			return 0;
		}else if ($bilhete['situacao'] === "Perdeu") {
			return false;
		}else {
			return true;
		}
	}

}

}