<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- include libraries(jQuery, bootstrap) -->
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous"></script>

    <!-- include summernote css/js -->
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js"></script>
  </head>
  <body>
    <button onclick="window.location.href='./documentList.php'">Indietro</button>
    <div style="width: 100%">
      <h5 id="docname"><?php echo $_GET['namedoc'] ?></h5>
      <h5 hidden id="iddoc"></h5>
      <h5 id="datasalvataggio"></h5>

    </div>
    <button onclick="createPDF()">Scarica in PDF</button>
    <button onclick="createDOC()">Scarica in Word</button>
    <div id="summernote">Hello Summernote</div>
    <script>

    function createDOC(){
      var testoDocumento=$("#summernote").summernote('code');
      Export2Doc(testoDocumento);
    }

    function Export2Doc(element, filename = ''){
        filename=$('#docname').html();
        var preHtml = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'><head><meta charset='utf-8'><title>Export HTML To Doc</title></head><body>";
        var postHtml = "</body></html>";
        var html = preHtml+element+postHtml;

        var blob = new Blob(['\ufeff', html], {
            type: 'application/msword'
        });

        // Specify link url
        var url = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(html);

        // Specify file name
        filename = filename?filename+'.doc':'document.doc';

        // Create download link element
        var downloadLink = document.createElement("a");

        document.body.appendChild(downloadLink);

        if(navigator.msSaveOrOpenBlob ){
            navigator.msSaveOrOpenBlob(blob, filename);
        }else{
            // Create a link to the file
            downloadLink.href = url;

            // Setting the file name
            downloadLink.download = filename;

            //triggering the function
            downloadLink.click();
        }

        document.body.removeChild(downloadLink);
    }


    function createPDF(){
      var doc = new jsPDF()
      var testoDocumento=$("#summernote").summernote('code');
      doc.fromHTML(testoDocumento, 10, 10)
      doc.save($('#docname').html()+'.pdf')
    }
    var body = document.body,
    html = document.documentElement;

    var heightPage = Math.max( body.scrollHeight, body.offsetHeight,
                               html.clientHeight, html.scrollHeight, html.offsetHeight );
        $(document).ready(function() {

          $("#summernote").summernote({
            height: heightPage,

            placeholder: '',

        });
          $.ajax({
            url: './gestioneDB.php?getdoc=0&iddoc='+<?php echo $_GET['iddoc']?>,
            type: 'GET',

              success: function(data) {
                var jsondata=JSON.parse(data);
                $('#datasalvataggio').html("Data dell'ultimo salvataggio: "+jsondata.DataOraSalvataggio);
                $('#iddoc').html(jsondata.ID);
                  getText(jsondata.Contenuto  );

              //Update periodico


            },
            error: function(e) {
            //called when there is an error
            //console.log(e.message);
            }




    });
  });
    function getText(testoIniziale){
        if ((testoIniziale!==null) && (testoIniziale!==undefined)){

            if ((testoIniziale.length>0)){
              $("#summernote").summernote("code", testoIniziale);
            }else{
              $("#summernote").summernote("code", "");
            }


      }else{
        var testoDocumento=$("#summernote").summernote('code');
        console.log(testoDocumento);
        var adesso=NOW();
        $.ajax({
          url: './gestioneDB.php?savedoc=0&iddoc='+<?php echo $_GET['iddoc']?>+'&content='+testoDocumento+'&datetimesave='+adesso,
          type: 'GET',

            success: function(data) {
              //Mostriamo il salvataggio avvenuto con successo e la data dello stesso
              if (data.indexOf('ok')>=0){
                $('#datasalvataggio').html("Il documento è stato salvato alle ore: "+adesso);
              }
              else{
                //C'è stato un errore
                $('#datasalvataggio').html("C'è stato un errore nel salvataggio del documento "+data);
              }

          },
          error: function(e) {
          //called when there is an error
          //console.log(e.message);
          }




  });



      }
      setTimeout(function(){ getText()}, 5000);






    }

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
  </body>
</html>
