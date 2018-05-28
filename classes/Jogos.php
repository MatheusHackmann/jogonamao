<?php
require_once("Sql.php");

class Jogos{

	public function cadastrarCopas($nomeCopa)
	{	
		$sql = new Sql();

		$copaExiste = $sql->select("SELECT * FROM copas WHERE nome_copa = :NOME;", array(
			":NOME" => utf8_decode($nomeCopa)
		));

		if (count($copaExiste) > 0) {
			return false;
			exit();
		}
		else {
			$sql->query("INSERT INTO copas (nome_copa) VALUES (:NOME);", array(
				":NOME" => utf8_decode($nomeCopa)
			));

			return true;
		}

	}

	public function buscarCopas()
	{
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM copas;");

		return $results;
	}

	public function cadastrarJogo($timeCasa, $timeFora, $dataJogo, $horaJogo, $apCasa, $apEmpate, $apFora, $fkCopa)
	{

		$sql = new Sql();

		$sql->query("INSERT INTO jogos (status_jogo, time_casa, time_fora, data_jogo, hora_jogo, ap_casa, ap_empate, ap_fora, gol_casa, gol_fora, esc_1, esc_2, esc_3, esc_4, fk_copa) 
			VALUES (
			:STATUSJOGO,
			:TIMECASA, 
			:TIMEFORA, 
			:DATAJOGO,
			:HORAJOGO,
			:APCASA,
			:APEMPATE,
			:APFORA,
			:GOLCASA,
			:GOLFORA,
			:ESC1,
			:ESC2,
			:ESC3,
			:ESC4,
			:FKCOPA);",
			array(
				":STATUSJOGO" => "Pendente",
				":TIMECASA" => utf8_decode($timeCasa),
				":TIMEFORA" => utf8_decode($timeFora),
				":DATAJOGO" => $dataJogo,
				":HORAJOGO" => $horaJogo,
				":APCASA" => $apCasa,
				":APEMPATE" => $apEmpate,
				":APFORA" => $apFora,
				":GOLCASA" => 0,
				":GOLFORA" => 0,
				":ESC1" => "",
				":ESC2" => "",
				":ESC3" => "",
				":ESC4" => "",
				":FKCOPA" => $fkCopa
			));
	}

	public function jogosDisponiveis()
	{
		$sql = new Sql();

		$copas = $sql->select("SELECT c.*, GROUP_CONCAT(id SEPARATOR ',') AS jogos FROM copas as c INNER JOIN jogos as j ON c.id_copa = j.fk_copa AND j.status_jogo = 'Pendente' WHERE data_jogo = :DATA AND (hora_jogo >= :HORA) GROUP BY c.id_copa;", array(
			":DATA" => date('Y-m-d'),
			":HORA" => date('H:i:s')));

		return $copas;
	}

	public function jogosPendentes()
	{
		$sql = new Sql();

		$jogosPendentes = $sql->select("SELECT * FROM jogos WHERE status_jogo = :STATUSJOGO;", array(
			":STATUSJOGO" => "Pendente"
		));

		return $jogosPendentes;
	}

	public function atualizarResultado($id, $golCasa, $golFora)
	{
		//Escolha 1
		if ($golCasa > $golFora) {
			$esc1 = "Casa";
		}else if ($golFora > $golCasa) {
			$esc1 = "Fora";
		}else {
			$esc1 = "Empate";
		}

		//Escolha 2
		if (($golCasa % 2 == 0) && ($golFora % 2 == 0)) {
			$esc2 = "Par";
		}else {
			$esc2 = "Impar";
		}

		//Escolha 3
		if ($golCasa != 0 && $golFora != 0) {
			$esc3 = "Sim";
		}else {
			$esc3 = "Não";
		}

		//Escolha 4
		$esc4 = $golCasa."x".$golFora;


		$sql = new Sql();

		$sql->query("UPDATE jogos SET status_jogo = :STATUSJOGO, gol_casa = :GOLCASA, gol_fora = :GOLFORA, esc_1 = :ESC1, esc_2 = :ESC2, esc_3 = :ESC3, esc_4 = :ESC4 WHERE id = :IDJOGO;", array(
			":STATUSJOGO" => "Concluido",
			":GOLCASA" => $golCasa,
			":GOLFORA" => $golFora,
			":ESC1" => $esc1,
			":ESC2" => $esc2,
			":ESC3" => utf8_decode($esc3),
			":ESC4" => $esc4,
			":IDJOGO" => $id
		));

		$sql->query("UPDATE ap_jogos SET situacao = :SITUACAO WHERE jogo = :IDJOGO;", array(
			":SITUACAO" => "Concluido",
			":IDJOGO" => $id
		));		


		header("Location: atualizar_resultados.php");
	}
}
