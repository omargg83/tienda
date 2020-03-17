
<input id="file" type="file" accept="txt/json" /><br/>
<textarea id="code" name="code" rows="5" cols="50"></textarea>


<script type="text/javascript">
  var input = document.getElementById('file');
  var json;
  input.addEventListener('change', function() {
  var file = input.files[0];

  var reader = new FileReader();
  reader.onload = function(e) {

    json = JSON.parse(e.target.result);
    for (i = 0; i < json.length; i++) {
      console.log(json[i].idProducto);

      $.ajax({
        data:  {
          "function":"carga",
          "idProducto":json[i].idProducto
        },
        url: "db_.php",
        type: "post",
        success:  function (response) {
          console.log(response);
        }
      });

    }
  };
  reader.readAsText(file);
  });

</script>
