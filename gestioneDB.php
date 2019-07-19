<?php
// Nelle variabili sotto riportate, sostituire i valori di db_server, db_database, db_uid, db_pwd
 // con i rispettivi valori che trovi nel tuo pannello di controllo: server, database, username, password

   $db_server = "IPDB" ;
   $db_database = "dbName" ;
   $db_uid = "UserName" ;
   $db_pw = "DBPAssword" ;
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   // Connessione al database
   $link = new mysqli($db_server,$db_uid,$db_pw,$db_database);


   //Ottenimento documenti

   if (isset($_GET['getalldocs'])){
     $queryOttenimento="SELECT * FROM Documenti";
     $result=$link->query($queryOttenimento);
        $allData=array();
         while($row = $result->fetch_assoc()) {
           $docData=array("ID"=>$row['ID'],"NomeDocumento"=>$row['NomeDocumento']);
           array_push($allData,$docData);

    }
    $allData=array("Documents"=>$allData);
    echo json_encode($allData);
   }

   //Ottenimento versione documento più recente

   if (isset($_GET['getdoc'])){
      $queryOttenimento="SELECT * FROM VersioniDoc where IDDocumentoFk=".$_GET['iddoc']." ORDER BY DataOraSalvataggio desc LIMIT 1";

      $result=$link->query($queryOttenimento);

      $doc = $result->fetch_assoc();
          $dati=array('ID'=>$doc['ID'],'Contenuto'=>$doc['Contenuto'],'DataOraSalvataggio'=>$doc['DataOraSalvataggio']);
          echo json_encode($dati);







   }

   //Salvataggio documento
   if (isset($_GET['savedoc'])){
      $querySalvataggio="INSERT INTO VersioniDoc (IDDocumentoFk,Contenuto,DataOraSalvataggio) VALUES (".
                              $_GET['iddoc'].",'".
                              $_GET['content']."','".
                              $_GET['datetimesave']."')";

                $result=$link->query($querySalvataggio);
                echo "ok";
   }

   //Creazione documento

   if (isset($_GET['createdoc'])){
     $queryCreazioneDOC="insert into Documenti (NomeDocumento) VALUES ('".$_GET['nomedoc']."')";
     $result=$link->query($queryCreazioneDOC);
     //Ci serve l'ID più recente della tabella documenti
     $queryOttenimentoDOCID="SELECT ID from Documenti order by DESC limit 1";
     $result=$link->query($queryOttenimentoDOCID);
     $IDDOC=0;
     if ($result)
     {
        while ($row = mysql_fetch_object($result))
        {
            $IDDOC=$row->ID;
        }
     }

     $querySalvataggio="INSERT INTO VersioniDoc (IDDocumentoFk,Contenuto,DataOraSalvataggio) VALUES (".
                             $IDDOC.",''".
                             $_GET['datetimesave'].")";

                             $result=$link->query($querySalvataggio);

                             echo "ok";

   }




?>
