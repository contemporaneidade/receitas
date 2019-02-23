<?php

function returnValue($file) {
	$valor = "-";

	$handle2 = @fopen($file, "r");
	if ($handle2) {
		while (!feof($handle2)) {
			$buffer = fgets($handle2, 4096);
			
			if (preg_match('/Total de Receitas R\$ (.*)/', $buffer, $arr)) {
				$valor = round(trim($arr[1]), 2);
			}
			
			if (preg_match('/Total de Despesas: R\$ (.*)/', $buffer, $arr)) {
				$valor = round(trim($arr[1]), 2);
			}

			if (preg_match('/N&atilde;o h&aacute; entrega de presta&ccedil;&atilde;o de contas &agrave; Justi&ccedil;a Eleitoral/', $buffer, $arr)) {
				$valor = "Sem prestacao";
			}
			
			if (preg_match('/A presta&ccedil;&atilde;o de contas foi entregue sem lan&ccedil;amentos  de receitas/', $buffer, $arr)) {
				$valor = "Sem lancamento";
			}
			
			if (preg_match('/Presta&ccedil;&atilde;o de contas entregue. Arquivo corrompido. Impossibilidade de apresentar receitas/', $buffer, $arr)) {
				$valor = "Arquivo corrompido";
			}
		}
	}
	return $valor;
}

$dados = "";
if (($handle = fopen("input/listagem_deputados.csv", "r")) !== FALSE) {
	while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
		$file = "files/receitas/receitas_".trim($data[0]).".html";
		$file2 = "files/despesas/receitas_".trim($data[0]).".html";

		echo "Receita: ".trim($data[0])."; ".$file."\r\n";
		$valor = returnValue($file);

		echo "Despesas: ".trim($data[0])."; ".$file2."\r\n";
		$valor2 = returnValue($file2);

		$dados .= $data[0]."; ".$valor."; ".$valor2."\r\n";
	}
}



$output = "output/output_".date("Ymd_His").".csv";
$handle3 = fopen($output, "w+");
if ($handle3) {
	$cabecalho = "Sequencial; Receitas; Despesas\r\n";
	fwrite($handle3, $cabecalho.$dados);
	fclose($handle3);
}

?>