var airports = new Bloodhound({
		  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('typeAhead'),
		  queryTokenizer: Bloodhound.tokenizers.whitespace,
		  limit: 15,
		  prefetch: '../assets/data/airports.json'
		});
		 
		 
		airports.initialize();

		 
		$('#airports .typeahead').typeahead({
		  highlight: true
		},
		{
		  name: 'airports',
		  displayKey: 'typeAhead',
		  valueKey: 'fs',
		  source: airports.ttAdapter(),
		});
		
var airlines = new Bloodhound({
		  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('typeAhead'),
		  queryTokenizer: Bloodhound.tokenizers.whitespace,
		  limit: 15,
		  prefetch: '../assets/data/airlines.json'
		});
		 
		 
		airlines.initialize();

		 
		$('#airlines .typeahead').typeahead({
		  highlight: true
		},
		{
		  name: 'airlines',
		  displayKey: 'typeAhead',
		  valueKey: 'fs',
		  source: airlines.ttAdapter(),
		});