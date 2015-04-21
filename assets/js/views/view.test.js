/*
$(document).ready(function () {
        var oTable = $('#big_table').DataTable( {
            "processing": true,
            "serverSide": true,
            "jQueryUI": true,
            "ajax": {
                "url": "http://dev.phoenixva.org/lorathon/test/datatable",
                "type": "POST",
            },            
        });
    });*/

$(function(){
  $("#airports").autocomplete({
    source: "test/get_airports",
    select: function( event, ui ) {
            $( "#airports" ).val( ui.item.label);
            $( "#airports-id" ).val( ui.item.id);
            $( "#airports-icao" ).val( ui.item.icao);
            $( "#airports-iata" ).val( ui.item.iata);
    }
  });
});