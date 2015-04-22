$(function(){
  $("#dep_airport_name").autocomplete({
    source: "test/get_airports_name",
    select: function( event, ui ) {
            $( "#dep_airport_name" ).val( ui.item.name);
            $( "#dep_airport_id" ).val( ui.item.id);
            $( "#dep_airport_icao" ).val( ui.item.icao);
            $( "#dep_airport_iata" ).val( ui.item.iata);
    }
  });
});

$(function(){
  $("#dep_airport_iata").autocomplete({
    source: "test/get_airports_iata",
    select: function( event, ui ) {
            $( "#dep_airport_name" ).val( ui.item.name);
            $( "#dep_airport_id" ).val( ui.item.id);
            $( "#dep_airport_icao" ).val( ui.item.icao);
            $( "#dep_airport_iata" ).val( ui.item.iata);
    }
  });
});

$(function(){
  $("#dep_airport_icao").autocomplete({
    source: "test/get_airports_icao",
    select: function( event, ui ) {
            $( "#dep_airport_name" ).val( ui.item.name);
            $( "#dep_airport_id" ).val( ui.item.id);
            $( "#dep_airport_icao" ).val( ui.item.icao);
            $( "#dep_airport_iata" ).val( ui.item.iata);
    }
  });
});