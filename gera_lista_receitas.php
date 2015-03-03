<?php

$dados = "";
if (($handle = fopen("sequencial_candidatos.csv", "r")) !== FALSE) {
	while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
		$file = "files/receitas_".trim($data[2]).".html";
		
		$handle2 = @fopen($file, "r");
		if ($handle2) {
			$valor = "-";
			while (!feof($handle2)) {
				$buffer = fgets($handle2, 4096);
				
				if (preg_match('/Total de Receitas R\$ (.*)/', $buffer, $arr)) {
					$valor = round(trim($arr[1]), 2);
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