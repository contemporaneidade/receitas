<?php

date_default_timezone_set('Brazil/East');

if (($handle = fopen("input/listagem_deputados.csv", "r")) !== FALSE) {
	while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
		$file = "files/receitas_".trim($data[0]).".html";
		if (!file_exists($file)) {
			// Aqui entra o action do formul�rio - pra onde os dados ser�o enviados
			$cURL = curl_init('http://inter01.tse.jus.br/spceweb.consulta.receitasdespesas2014/resumoReceitasByCandidato.action');
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
		 
			// Definimos um array seguindo o padr�o:
			//  '<name do input>' => '<valor inserido>'
			$dados = array(
				'sqCandidato' => trim($data[0]),
				//'sgUe' => trim($data[1])
			);
			
			// Iremos usar o m�todo POST
			curl_setopt($cURL, CURLOPT_POST, true);
			// Definimos quais informa��es ser�o enviadas pelo POST (array)
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $dados);
	 
			$resultado = curl_exec($cURL);
			
			//Criamos um arquivo com o nome
			$fp = fopen($file, 'w');
			//Escrevemos o conte�do
			fwrite($fp, $resultado);
			//Fechamos o arquivo
			fclose($fp);
		
			curl_close($cURL);
			
			sleep(10);
		}
	}
}

