$(function(){
  $("#airline").autocomplete({
    source: "airlines/autocomplete",
    select: function( event, ui ) {
            $( "#airline" ).val( ui.item.name);
            $( "#airline_id" ).val( ui.item.id);
    }
  });
});

$(function(){
  $("#airport").autocomplete({
    source: "airports/autocomplete",
    select: function( event, ui ) {
            $( "#airport" ).val( ui.item.name);
            $( "#airport_id" ).val( ui.item.id);
    }
  });
});

$(function(){
  $("#dep_airport").autocomplete({
    source: "airports/autocomplete",
    select: function( event, ui ) {
            $( "#dep_airport" ).val( ui.item.name);
            $( "#dep_airport_id" ).val( ui.item.id);
    }
  });
});

$(function(){
  $("#arr_airport").autocomplete({
    source: "airports/autocomplete",
    select: function( event, ui ) {
            $( "#arr_airport" ).val( ui.item.name);
            $( "#arr_airport_id" ).val( ui.item.id);
    }
  });
});
