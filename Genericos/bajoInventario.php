<div>
  <h5 class="centrado">Articulos con bajo inventario</h5>
  <div class="impArtBajoInventario">
  <table id="tbBajoInventario">

  </table>
  </div>
  <div id="editor"></div>
  <div class="row">
      <div class="col s5 offset-s7">
        <a id="btnImprimirBajoInventario" class="waves-effect waves-light btn green darken-2 "><i class="material-icons left">print</i>Imprimir</a>
      </div>
    </div>  
</div>
<script>
    $(function(){
      var l = {
         orientation: 'l',
         unit: 'mm',
         format: 'a3',
         compress: true,
         fontSize: 10,
         lineHeight: 1,
         autoSize: false,
         printHeaders: true}
         var doc = new jsPDF(l, '', '', '');
    var specialElementHandlers = {
        '#editor': function (element, renderer) {
            return true;
        }
    };

   $('#btnImprimirBajoInventario').click(function () {
      doc.margins = 1;
                doc.setFont("times");
   
        doc.fromHTML($('#impArtBajoInventario').html(), 50, 20, {
        'setFontSize':10,
        'width': 300,
            'elementHandlers': specialElementHandlers
    });
doc.text(185,20, "Reporte articulos con bajo inventario");
doc.text(180,27, "Instituto Tecnologico de Culiacan");
    doc.save('sample-file.pdf');
});
});
</script>