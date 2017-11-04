<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Modelo Econométrico</title>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.css">
<link rel="stylesheet" type="text/css" href="css/estilo.css">
<link rel="stylesheet" type="text/css" href="amcharts/plugins/export/export.css">
<script src="js/jquery.js"></script>
<script src="js/bootstrap.js"></script>
<script src="amcharts/amcharts.js"></script>
<script src="amcharts/serial.js"></script>
<script src="amcharts/themes/light.js"></script>
<script src="amcharts/plugins/dataloader/dataloader.min.js"></script>
<script src="amcharts/plugins/export/export.js"></script>
</head>

<body>
<nav class="navbar navbar-default">
  <div class="container">
    <a href="#" class="navbar-brand">Modelo Econometrico</a>
  </div>
</nav>
<div class="container">
	<div class="row">
    	<div class="col-xs-12">
        	<div id="comandos">
                <form>
                  <div class="form-group">
                    <label for="producto">Producto</label>
                    <select class="form-control" id="producto">
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="periodo">Periodo</label>
                    <select class="form-control" id="periodo">
                      <option value="1">Mensual</option>
                      <option value="3">Trimestral</option>
                      <option value="12">Anual</option>
                    </select>
                  </div>
                  
                  <div class="form-group">
                  	<div class="radio">
                      <label>
                        <input type="radio" name="optionsRadios" id="opcion1" value="opcion1" checked>
                        	Excel
                      </label>
                    </div>
                    <div class="radio">
                      <label>
                        <input type="radio" name="optionsRadios" id="opcion2" value="opcion2">
                        	Base de Datos
                      </label>
                    </div>
                  </div>
            	</form>
            </div>
        </div>
    </div>
    <div class="row">
    	<div id="chartdiv">
        
        </div>
    </div>

</div>
<script>
$(document).ready(function(){
	var chart = AmCharts.makeChart("chartdiv", {
	  "type": "serial",
	  "theme": "light",
	  "rotate": true,
	  "marginBottom": 50,
	  "dataLoader": {
		"url": "calc.php?periodo=1"
	  },
	  "startDuration": 1,
	  "graphs": [{
		"fillAlphas": 0.8,
		"lineAlpha": 0.2,
		"type": "column",
		"valueField": "proyeccion",
		"title": "Proyección",
		"labelText": "[[value]]",
		"clustered": false,
		"labelFunction": function(item) {
		  return Math.abs(item.values.value);
		},
		"balloonFunction": function(item) {
		  return item.category + ": " + Math.abs(item.values.value);
		}
	  }, {
		"fillAlphas": 0.8,
		"lineAlpha": 0.2,
		"type": "column",
		"valueField": "venta",
		"title": "Ventas",
		"labelText": "[[value]]",
		"clustered": false,
		"labelFunction": function(item) {
		  return Math.abs(item.values.value);
		},
		"balloonFunction": function(item) {
		  return item.category + ": " + Math.abs(item.values.value);
		}
	  }],
	  "categoryField": "fecha",
	  "categoryAxis": {
		"gridPosition": "start",
		"gridAlpha": 0.2,
		"axisAlpha": 0
	  },
	  "valueAxes": [{
		"gridAlpha": 0,
		"ignoreAxisWidth": true,
		"labelFunction": function(value) {
		  return Math.abs(value);
		},
		"guides": [{
		  "value": 0,
		  "lineAlpha": 0.2
		}]
	  }],
	  "balloon": {
		"fixedPosition": true
	  },
	  "chartCursor": {
		"valueBalloonsEnabled": false,
		"cursorAlpha": 0.05,
		"fullWidth": true
	  },
	  "allLabels": [{
		"text": "Ventas",
		"x": "28%",
		"y": "97%",
		"bold": true,
		"align": "middle"
	  }, {
		"text": "Proyeccion",
		"x": "75%",
		"y": "97%",
		"bold": true,
		"align": "middle"
	  }],
	 "export": {
		"enabled": true
	  }

	});
	
	function setDataSet(dataset_url) {
	  AmCharts.loadFile(dataset_url, {}, function(data) {
		chart.dataProvider = AmCharts.parseJSON(data);
		chart.validateData();
	  });
	}
	
	$('#periodo').on('change', function() {
		var p = $(this).val();
	  	setDataSet("calc.php?periodo=" + p);
	});
	
});
</script>
</body>
</html>