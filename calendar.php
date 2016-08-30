
<?php error_reporting (E_ALL ^ E_NOTICE); ?>
<?php

include 'calculTermen.php';
include 'trimiteEmail.php';


$azi = date("d.m.Y");   

while($val = mysql_fetch_row($result))

{
   $id=$val[0];//Id
   $activitate = $val[1];//Activitate
   $dataActivitate= $val[2];//DataActivitate
   $nrAni= $val[3];//NrAni - la cati ani se repeta (ex.2 ani in cazul ITP)
   $recurenta= $val[4];//Recurenta
   $email= $val[5];//Email


if ($dataActivitate==$azi)
{
$k=k+1;
//Trimite email de avertizare
$mail=new TrimiteEmail($email,$activitate);

if ($recurenta=="")
{
//Sterge inregistrarea nerecurenta din baza de date
$querySterge="DELETE FROM `todo`  WHERE `Id`=$id";

$aBD->Interogare($bd,"query1.txt",$querySterge);	
}
else
{
//Calculeaza noul termen si actualizeaza termenul inregistrarii recurente
$timp=new CalculTermen( $dataActivitate, $recurenta,$nrAni);
$termen=$timp->termenNou;

$queryAdauga="UPDATE `todo` SET `DataActivitate`='$termen' WHERE `Id`=$id";

$aBD->Interogare($bd,"query1.txt",$queryAdauga);
}

}
}
if($k>0)
{echo("<h3>AZI ".$azi." au fost trimise pe email ".$k." alerte</h3>" ); }
else
{echo("<h3>AZI ".$azi." nu este memorat niciun eveniment</h3>" ); }

$aViz=new VizualDB($bd,"todo","*","");
$aViz;


?> 	
