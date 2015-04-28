<?php

$dados = "";
if (($handle = fopen("sequencial_candidatos.csv", "r")) !== FALSE) {
	while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
		$file = "files/receitas_".trim($data[2]).".html";
		echo trim($data[2])."; ".$file."\r\n";
		
		$handle2 = @fopen($file, "r");
		if ($handle2) {
			$valor = "-";
			while (!feof($handle2)) {
				$buffer = fgets($handle2, 4096);
				
				if (preg_match('/Total de Receitas R\$ (.*)/', $buffer, $arr)) {
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
		$dados .= $data[2]."; ".$valor."\r\n";
	}
}



$output = "output/output_".date("Ymd_His").".csv";
$handle3 = fopen($output, "w+");
if ($handle3) {
	$cabecalho = "Sequencial; Receitas\r\n";
	fwrite($handle3, $cabecalho.$dados);
	fclose($handle3);
}

?>