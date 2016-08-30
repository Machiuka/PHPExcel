<?php

/**
 * XLS parsing uses php-excel-reader from http://code.google.com/p/php-excel-reader/
 */
header('Content-Type: text/plain');


// Excel reader from http://code.google.com/p/php-excel-reader/
require('proceduri/excel_reader2.php');
require('proceduri/SpreadsheetReader.php');

date_default_timezone_set('Europe/Bucharest');
//$azi = date("d.m.Y");   
$azi = date("d-m-Y");
$Filepath = "Echipamente SDV 13-17-  2015-Audit.xls"; //$_GET['File'];
$myfile = fopen("sdv.html", "w") or die("Unable to open file!");
$mesaj = "<html>
<head>
<title>ALERTA METROLOGICA</title>
</head>
<body><table>";
$i = 0;
$StartMem = memory_get_usage();


try {
    $Spreadsheet = new SpreadsheetReader($Filepath);
    $BaseMem = memory_get_usage();

    $Sheets = $Spreadsheet->Sheets();
   
    foreach ($Sheets as $Index => $Name) {

        //		$Time = microtime(true);

        $Spreadsheet->ChangeSheet($Index);

        foreach ($Spreadsheet as $Key => $Row) {
            //	echo 'Key='.$Key.': ';
            if ($Row) {

                //	print_r($Row);
                $scadenta = $Row[8]; //Data cand trebuie efectuata verificarea - coloana I
                $pozitie = $Row[0];
                $denumire = $Row[1];
                $dataActivitate = ($scadenta);
                $diff = (strtotime($dataActivitate) - strtotime($azi));
                $diff = floor($diff / (60 * 60 * 24));


                if ($diff <= 5 && $diff > 0) {
                    $i++;
                    if ($i % 2) {
                        $culoare = "A4A4A4";
                    } else {
                        $culoare = "FFFFFF";
                    }
             
                    $mesaj = $mesaj . "<tr style=' background-color:#".$culoare . "'> ";
                    $mesaj = $mesaj . "<td>" . $i . ")</td><td>" . $denumire . "</td><td> Pozitia -<b> " . $pozitie . "</b></td><td> Data scadentei : " . $scadenta . "</td>";
                    $mesaj = $mesaj . "</tr>";
               
             //    $mesaj=$mesaj." ".$i.") ".$denumire." ( Pozitia - ".$pozitie.") are scadenta in data de ".$scadenta."<br>";
              
                }
            } else {
                var_dump($Row);
            }
            $CurrentMem = memory_get_usage();
        }
    }
 //   echo($mesaj);
} catch (Exception $E) {
    echo $E->getMessage();
}
$mesaj = $mesaj . "</table></body>
</html>";
$to = "sorin.neagu@deltainvest.ro";
//$to="cdl_1996@yahoo.com";
$subject = "Scadentele metrologice de azi " . $azi;
$body = $mesaj;

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <casaclung@gmail.com>' . "\r\n";
$headers .= 'Cc: cdl_1996@yahoo.com' . "\r\n";
if (1 == 2) {
    if (mail($to, $subject, $body, $headers)) {
        echo("<p>Email successfully sent!</p>");
    } else {
        echo("<p>Email delivery failed…</p>");
    }
}
fwrite($myfile, $body);
fclose($myfile);
header("Location: http://localhost/phpexcel/sdv.html");
die();
?>
