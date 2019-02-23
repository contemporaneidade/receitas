<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('Brazil/East');

//Parâmetros via linha de comando
if (sizeof($argv) == 1 || sizeof($argv) > 2) {
	echo "Comando invalido. Para ajuda: php download_receitas.php help\n";
	exit;
} elseif ($argv[1] == "help") {
	echo "Comandos para baixar receitas: php php download_receitas.php 1\n";
	echo "Comandos para baixar despesas: php php download_receitas.php 2\n";
	exit;
} else {
	if ($argv[1] == '1') {
		echo "Baixando receitas!\n";
		$post_url = 'http://inter01.tse.jus.br/spceweb.consulta.receitasdespesas2014/resumoReceitasByCandidato.action';
		$path_download = 'files/receitas/';
	} elseif ($argv[1] == '2') {
		echo "Baixando despesas!\n";
		$post_url = 'http://inter01.tse.jus.br/spceweb.consulta.receitasdespesas2014/resumoDespesasByCandidato.action';
		$path_download = 'files/despesas/';
	} else {
		echo "Comando invalido. Para ajuda: php download_receitas.php help\n";
		exit;
	}
}
//Fim Parâmetros

if (($handle = fopen("input/listagem_deputados.csv", "r")) !== FALSE) {
	while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
		$file = $path_download."receitas_".trim($data[0]).".html";
		if (!file_exists($file) || (file_exists($file) && filesize($file) < 17733)) {
			// Aqui entra o action do formulario - pra onde os dados serao enviados
			$cURL = curl_init($post_url);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
		 
			// Definimos um array seguindo o padrão:
			//  '<name do input>' => '<valor inserido>'
			$dados = array(
				'sqCandidato' => trim($data[0]),
				//'sgUe' => trim($data[1])
			);
			
			// Iremos usar o método POST
			curl_setopt($cURL, CURLOPT_POST, true);
			// Definimos quais informa��es ser�o enviadas pelo POST (array)
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $dados);
	 
			$resultado = curl_exec($cURL);
			
			//Criamos um arquivo com o nome
			$fp = fopen($file, 'w');
			//Escrevemos o conteúdo
			fwrite($fp, $resultado);
			//Fechamos o arquivo
			fclose($fp);
		
			curl_close($cURL);
			
			sleep(3);
		}
	}
}

