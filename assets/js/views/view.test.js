$(function(){
  $("#dep_airport").autocomplete({
    source: "test/get_airports",
    select: function( event, ui ) {
            $( "#dep_airport" ).val( ui.item.name);
            $( "#dep_airport_id" ).val( ui.item.id);
    }
  });
});

$(function(){
  $("#arr_airport").autocomplete({
    source: "test/get_airports",
    select: function( event, ui ) {
            $( "#arr_airport" ).val( ui.item.name);
            $( "#arr_airport_id" ).val( ui.item.id);
    }
  });
});
