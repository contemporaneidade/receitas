<?php

$row = 1;
if (($handle = fopen("consulta_cand_2014_BRASIL.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        if ($row > 1) {
            if ($data[13] == '6' || $data[13] == '7' || $data[13] == '8') {
                echo $data[15].";\n";
            }
        }

        $row++;
    }
    fclose($handle);
}


?>