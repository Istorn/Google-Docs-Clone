<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h1>Google Docs Clone</h1>
  <button onclick="createNewDoc()">Crea un nuovo documento</button>
  <div id="createdocdiv"></div>
  <h4 id="documentsLink"></h4>
</div>

</body>
<script>
  function createNewDoc(){
    var htmlCreate="</br><button onclick='annulla()'>Annulla</button></br>Inserisci il nome del nuovo documento: <input type='text' id='namenewdoc'></br><button onclick='createNewDocument()'>Crea</button>";
    $('#documentsLink').hide();
    $('#createdocdiv').html(htmlCreate);
  }
  function annulla(){
    $('#documentsLink').show();
    $('#createdocdiv').html("");
  }

  function createNewDocument(){
    if ($('#namenewdoc').val().length>0){
      $.ajax({
        url: './gestioneDB.php?createdoc=0&nomedoc='+$('#namenewdoc').val()+"&datetimesave="+NOW(),
        type: 'GET',

          success: function(data) {
            window.location.reload();



        },
        error: function(e) {
        //called when there is an error
        //console.log(e.message);
        }




  });
}else{
  alert("Inserisci un nome per il file.");
}

  }
  $(document).ready(function() {

    $.ajax({
      url: './gestioneDB.php?getalldocs=0',
      type: 'GET',

        success: function(data) {
          var jsondata=JSON.parse(data);
          var documents=jsondata.Documents;
          var htmlList="";
          for (i=0;i<documents.length;i++){
              htmlList+="- <a href='./index.php?iddoc="+documents[i].ID+"&namedoc="+documents[i].NomeDocumento+"'>"+documents[i].NomeDocumento+"</a></br>";
          }
          $('#documentsLink').html(htmlList);




      },
      error: function(e) {
      //called when there is an error
      //console.log(e.message);
      }




});
  });


  function NOW() {

  var date = new Date();
  var aaaa = date.getUTCFullYear();
  var gg = date.getUTCDate();
  var mm = (date.getUTCMonth() + 1);

  if (gg < 10)
      gg = "0" + gg;

  if (mm < 10)
      mm = "0" + mm;

  var cur_day = aaaa + "-" + mm + "-" + gg;

  var hours = date.getUTCHours()
  var minutes = date.getUTCMinutes()
  var seconds = date.getUTCSeconds();

  if (hours < 10)
      hours = "0" + hours;

  if (minutes < 10)
      minutes = "0" + minutes;

  if (seconds < 10)
      seconds = "0" + seconds;

  return cur_day + " " + hours + ":" + minutes + ":" + seconds;

}
</script>
</html>
