<!DOCTYPE html>
<meta charset="utf-8">
<head>
<title>SubteMania</title>
<style>

H1 {font-family:Tahoma; font-size:15pt; line-height:1.5em;margin:0;margin-top:0.4em;margin-bottom:0.15em; text-shadow:#D0D0D0 2px 3px 4px;}
p {font-family:Tahoma; font-size:10pt; line-height:1.5em;margin:0;margin-top:0.4em;margin-bottom:0.15em; text-shadow:#D0D0D0 2px 3px 4px;}

path {
  fill: none;
  stroke-width: 8px;
}

circle {
  stroke: #f0f0f0;
  stroke-width: 2px;
}

		
 .tooltip{color:#F7F7F7;
	  font-family:Tahoma;
	  font-size: 120%;
	  font-weight: 800; 
	  background-color:#424242;
          margin: 10px;
          padding-right: 10px;
          padding-left: 10px;
          padding-top: 10px;
		  padding-bottom: 10px;
	  text-align: center;
      -webkit-border-radius:10px;
      -moz-border-radius:10px;
      border-radius:10px;
        }
		
 .tooltip_train{color:#F7F7F7;
	  font-family:Verdana;
	  font-size: 100%;
	  font-weight: 800; 
	  background-color:#999999;
          margin: 10px;
          padding-right: 10px;
          padding-left: 10px;
          padding-top: 10px;
		  padding-bottom: 10px;
	  text-align: center;
      -webkit-border-radius:10px;
      -moz-border-radius:10px;
      border-radius:10px;
        }

input[type=range] {
    /*removes default webkit styles*/
    -webkit-appearance: none;
    
    /*fix for FF unable to apply focus style bug */
    border: 1px solid white;
    
    /*required for proper track sizing in FF*/
    width: 10px;
}
input.vertical { -webkit-appearance: slider-vertical; writing-mode: bt-lr; }

input[type=range]::-webkit-slider-runnable-track {
    width: 10px;
    height: 5px;
    background: #ddd;
    border: none;
    border-radius: 3px;
}
input[type=range]::-webkit-slider-thumb {
    -webkit-appearance: none;
    border: none;
    height: 16px;
    width: 12px;
    border-radius: 50%;
    background: goldenrod;
    margin-top: -4px;
}
input[type=range]:focus {
    outline: none;
}
input[type=range]:focus::-webkit-slider-runnable-track {
    background: #ccc;
}
input[type=range]::-moz-range-track {
    width: 10px;
    height: 5px;
    background: #ddd;
    border: none;
    border-radius: 3px;
}
input[type=range]::-moz-range-thumb {
    border: none;
    height: 16px;
    width: 12px;
    border-radius: 50%;
    background: goldenrod;
}

/*hide the outline behind the border*/
input[type=range]:-moz-focusring{
    outline: 1px solid white;
    outline-offset: -1px;
}
input[type=range]::-ms-track {
    width: 10px;
    height: 12px;
    background: #ddd;
    border: none;
    border-radius: 10px;
    /*remove default tick marks*/
    color: transparent;
}
input[type=range]::-ms-fill-lower {
    outline: none;
    background: #777;
    border-radius: 10px 0 0 10px;
}
input[type=range]::-ms-thumb {
    border: none;
    height: 16px;
    width: 12px;
    border-radius: 50%;
    background: goldenrod;
}
input[type=range]:focus::-ms-track {
    background: #ccc;
}
input[type=range]:focus::-ms-fill-lower {
    background: #888;
}	
</style>
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7/leaflet.css"/>
<script src="http://cdn.leafletjs.com/leaflet-0.7/leaflet.js"></script>
<script type="text/javascript" src="d3.min.js"></script>
<script type="text/javascript" src="tooltip.js"></script> 
<body>
<div id="loading"><img src="loading.gif"> Cargando...<br>
</div>
	<div id="map" style="width: 1500px; height: 1400px"></div>

<script type="text/javascript">

var planes = [
<?php


// conectamos a la base de datos
require('qs_connection.php');
require('qs_functions.php');		

$rsube = 0;
$resultsube = mysql_query("SELECT direccion, lat, lon FROM sucursalessube");
$numsube = mysql_numrows($resultsube);
while ($rsube < $numsube) {
$subid=mysql_result($resultsube,$rsube,"direccion");
$subelat=mysql_result($resultsube,$rsube,"lat");
$subelon=mysql_result($resultsube,$rsube,"lon");
?>

["<?echo $subid;?>",<?echo $subelat;?>,<?echo $subelon;?>]<?if ($rsube < ($numsube - 1)) {echo",";}

$rsube++;
}
?>
];
        var map = L.map('map', { zoomControl:false });
		map.setView([-34.60,-58.43], 14),
        mapLink = 
            '<a href="http://openstreetmap.org">OpenStreetMap</a>';
        L.tileLayer(
            'http://{s}.www.toolserver.org/tiles/bw-mapnik/{z}/{x}/{y}.png', {
			attribution: '&copy; ' + mapLink + ' Contributors',
            maxZoom: 18,
            }).addTo(map);	

var greenIcon = L.icon({
    iconUrl: 'SUBEobtencionweb.png',
    shadowUrl: 'SUBEobtencionweb.png',

});
			for (var i = 0; i < planes.length; i++) {
			marker = new L.marker([planes[i][1],planes[i][2]],{icon: greenIcon})
				.bindPopup(planes[i][0])
				.addTo(map);
				}


			
			map.touchZoom.disable();
			map.doubleClickZoom.disable();
			map.scrollWheelZoom.disable();
			
var width = 1500,
    height = 1400;
	
speedA = 8667;
speedB = 9529;
speedC = 8667;
speedD = 9750;
speedE = 9600;
speedH = 9750;

frequencyA = 50;
frequencyB = 50;
frequencyC = 50;
frequencyD = 50;
frequencyE = 50;
frequencyH = 50;

<?php
$r=0;

// puntos de estacion
$resultplaces = mysql_query("SELECT DISTINCT linea, punto FROM lineas ORDER BY tramo ASC");
$num = mysql_numrows($resultplaces);
while ($r < $num) {
$linea=mysql_result($resultplaces,$r,"linea");
$punto=mysql_result($resultplaces,$r,"punto");

?>
var places<?echo $linea;?>_<?echo $punto;?> = {
<?
// pares de estaciones en cada punto
$rr=0;
$resultplaces_pairs = mysql_query("SELECT linea, punto, station, lon, lat FROM lineas WHERE linea = '$linea' AND punto = '$punto' ORDER BY tramo ASC");
$numm = mysql_numrows($resultplaces_pairs);
while ($rr < $numm) {
$lineaplaces=mysql_result($resultplaces_pairs,$rr,"linea");
$puntoplaces=mysql_result($resultplaces_pairs,$rr,"punto");
$stationplaces=mysql_result($resultplaces_pairs,$rr,"station");
$inicio=mysql_result($resultplaces_pairs,$rr,"lon");
$final=mysql_result($resultplaces_pairs,$rr,"lat");
?>
<?echo $lineaplaces;?>_<?echo $puntoplaces;?>_<?echo $stationplaces;?> : [<?echo $inicio;?>, <?echo $final;?>],
<?$rr++;
}
$r++;
?>
};
<?
}

$rd=0;

// puntos de estacion
$resultdepart = mysql_query("SELECT DISTINCT linea, punto FROM lineas ORDER BY tramo ASC");
$numd = mysql_numrows($resultdepart);
while ($rd < $numd) {
$linead=mysql_result($resultdepart,$rd,"linea");
$puntod=mysql_result($resultdepart,$rd,"punto");

?>
var depart<?echo $linead;?>_<?echo $puntod;?> = [
<?
// pares de estaciones en cada punto
$rrd=0;
$resultplaces_pairsd = mysql_query("SELECT lon, lat FROM lineas WHERE linea = '$linead' AND punto = '$puntod' ORDER BY tramo ASC LIMIT 1");
$nummd = mysql_numrows($resultplaces_pairsd);
while ($rrd < $nummd) {
$iniciod=mysql_result($resultplaces_pairsd,$rrd,"lon");
$finald=mysql_result($resultplaces_pairsd,$rrd,"lat");
?>
<?echo $iniciod;?>, <?echo $finald;?>
<?$rrd++;
}
$rd++;
?>
];
<?
}
?>
    var tramoa0 = ["San Pedrito a Plaza de Mayo"];
    var tramoa1 = ["Plaza de Mayo a San Pedrito"];
    var tramoa2 = ["San Pedrito a Plaza de Mayo"];
    var tramoa3 = ["Plaza de Mayo a San Pedrito"];
    var tramoa4 = ["San Pedrito a Plaza de Mayo"];
    var tramoa5 = ["Plaza de Mayo a San Pedrito"];
    var tramoa6 = ["San Pedrito a Plaza de Mayo"];
    var tramoa7 = ["Plaza de Mayo a San Pedrito"];
    var tramob0 = ["Juan Manuel de Rosas a Leandro N. Alem"];
    var tramob1 = ["Leandro N. Alem a Juan Manuel de Rosas"];
    var tramob2 = ["Juan Manuel de Rosas a Leandro N. Alem"];
    var tramob3 = ["Leandro N. Alem a Juan Manuel de Rosas"];
    var tramob4 = ["Juan Manuel de Rosas a Leandro N. Alem"];
    var tramob5 = ["Leandro N. Alem a Juan Manuel de Rosas"];
    var tramob6 = ["Juan Manuel de Rosas a Leandro N. Alem"];
    var tramob7 = ["Leandro N. Alem a Juan Manuel de Rosas"];
    var tramoc0 = ["Constituci&oacute;n a Retiro"];
    var tramoc1 = ["Retiro a Constituci&oacute;n"];
    var tramoc2 = ["Constituci&oacute;n a Retiro"];
    var tramoc3 = ["Retiro a Constituci&oacute;n"];
    var tramoc4 = ["Constituci&oacute;n a Retiro"];
    var tramoc5 = ["Retiro a Constituci&oacute;n"];
    var tramoc6 = ["Constituci&oacute;n a Retiro"];
    var tramoc7 = ["Retiro a Constituci&oacute;n"];
    var tramod0 = ["Congreso de Tucum&aacute;n a Catedral"];
    var tramod1 = ["Catedral a Congreso de Tucum&aacute;n"];
    var tramod2 = ["Congreso de Tucum&aacute;n a Catedral"];
    var tramod3 = ["Catedral a Congreso de Tucum&aacute;n"];
    var tramod4 = ["Congreso de Tucum&aacute;n a Catedral"];
    var tramod5 = ["Catedral a Congreso de Tucum&aacute;n"];
    var tramod6 = ["Congreso de Tucum&aacute;n a Catedral"];
    var tramod7 = ["Catedral a Congreso de Tucum&aacute;n"];
    var tramoe0 = ["Plaza de los Virreyes a Bol&iacute;var"];
    var tramoe1 = ["Bol&iacute;var a Plaza de los Virreyes"];
    var tramoe2 = ["Plaza de los Virreyes a Bol&iacute;var"];
    var tramoe3 = ["Bol&iacute;var a Plaza de los Virreyes"];
    var tramoe4 = ["Plaza de los Virreyes a Bol&iacute;var"];
    var tramoe5 = ["Bol&iacute;var a Plaza de los Virreyes"];
    var tramoe6 = ["Plaza de los Virreyes a Bol&iacute;var"];
    var tramoe7 = ["Bol&iacute;var a Plaza de los Virreyes"];
    var tramoh0 = ["Hospitales a Corrientes"];
    var tramoh1 = ["Corrientes a Hospitales"];
    var tramoh2 = ["Hospitales a Corrientes"];
    var tramoh3 = ["Corrientes a Hospitales"];
    var tramoh4 = ["Hospitales a Corrientes"];
    var tramoh5 = ["Corrientes a Hospitales"];
    var tramoh6 = ["Hospitales a Corrientes"];
    var tramoh7 = ["Corrientes a Hospitales"];
<?

$rrr = 0;
$resultroute = mysql_query("SELECT DISTINCT linea, punto FROM lineas ORDER BY tramo ASC");
$nummm = mysql_numrows($resultroute);
while ($rrr < $nummm) {
$linearoute=mysql_result($resultroute,$rrr,"linea");
$puntoroute=mysql_result($resultroute,$rrr,"punto");

?>
var route<?echo $linearoute;?>_<?echo $puntoroute;?> = {
  type: "LineString",
  coordinates: [
<?
$rrro = 0;
$resultroute_pairs = mysql_query("SELECT linea, punto, station, lon, lat FROM lineas WHERE linea = '$linearoute' AND punto = '$puntoroute' ORDER BY tramo ASC");
$nummmo = mysql_numrows($resultroute_pairs);
while ($rrro < $nummmo) {
$lineapair=mysql_result($resultroute_pairs,$rrro,"linea");
$puntopair=mysql_result($resultroute_pairs,$rrro,"punto");
$stationpair=mysql_result($resultroute_pairs,$rrro,"station");
?>places<?echo $lineapair;?>_<?echo $puntopair;?>.<?echo $lineapair;?>_<?echo $puntopair;?>_<?echo $stationpair;?>,
<?
$rrro++;
}
$rrr++;
?>]
};
<?
}
?>
var projection = d3.geo.mercator()
    .scale(813000)
    .rotate([58.4942,34.5507])
    .translate([0, 0]);

var path = d3.geo.path()
    .projection(projection);

var svg = d3.select(map.getPanes().overlayPane).append("svg")
    .attr("width", width)
    .attr("height", height);

<?
$rrrr = 0;
$resultpaths = mysql_query("SELECT DISTINCT linea, punto, proxima, anterior, color, title FROM lineas WHERE tipo = 'via' ORDER BY tramo ASC");
$nummmm = mysql_numrows($resultpaths);
while ($rrrr < $nummmm) {
$lineapath=mysql_result($resultpaths,$rrrr,"linea");
$puntopath=mysql_result($resultpaths,$rrrr,"punto");
$proximapath=mysql_result($resultpaths,$rrrr,"proxima");
$anteriorpath=mysql_result($resultpaths,$rrrr,"anterior");
$colorpath=mysql_result($resultpaths,$rrrr,"color");
$titlepath=mysql_result($resultpaths,$rrrr,"title");
?>
var path<?echo $lineapath;?>_<?echo $puntopath;?> =  svg.append("path")
    .datum(route<?echo $lineapath;?>_<?echo $puntopath;?>)
    .attr("class", "route")
    .attr("d", path)
    .attr("stroke", "<?echo $colorpath;?>");

var point<?echo $lineapath;?>_<?echo $puntopath;?> = svg.append("g")
    .attr("class", "points")
  .selectAll("g")
    .data(d3.entries(places<?echo $lineapath;?>_<?echo $puntopath;?>))
  .enter().append("g")
    .attr("transform", function(d) { return "translate(" + projection(d.value) + ")"; });	

point<?echo $lineapath;?>_<?echo $puntopath;?>.append("circle")
    .attr("r", 6);

function transition<?echo $lineapath;?>_<?echo $puntopath;?>() {
	d3.select("#circle<?echo $lineapath;?>_<?echo $anteriorpath;?>").remove()
var circle<?echo $lineapath;?>_<?echo $puntopath;?> = svg.append("circle")
    .attr("r", 10)
	.attr("fill", "<?echo $colorpath;?>")
	.attr("opacity", 0.8)
    .attr("id", "circle<?echo $lineapath;?>_<?echo $puntopath;?>")
    .data(depart<?echo $lineapath;?>_<?echo $puntopath;?>)
    .attr("transform", "translate(" + projection(depart<?echo $lineapath;?>_<?echo $puntopath;?>) + ")")
	.data(tramo<?echo $lineapath;?>)
     .call(d3.helper.tooltip(function(d)
		{return d;}));	
	
  circle<?echo $lineapath;?>_<?echo $puntopath;?>.transition()
      .duration((speed<?echo $titlepath;?>) + (Math.random()*18))
      .attrTween("transform", translateAlong(path<?echo $lineapath;?>_<?echo $puntopath;?>.node()))
      .each("end", transition<?echo $lineapath;?>_<?echo $proximapath;?>);
}

function translateAlong(path<?echo $lineapath;?>_<?echo $puntopath;?>) {
  var l = path<?echo $lineapath;?>_<?echo $puntopath;?>.getTotalLength();
  return function(d, i, a) {
    return function(t) {
      var p = path<?echo $lineapath;?>_<?echo $puntopath;?>.getPointAtLength(t * l);
      return "translate(" + p.x + "," + p.y + ")";
    };
  };
}
<?$rrrr++;
}

$rrrrr = 0;
$resultpathst = mysql_query("SELECT DISTINCT linea, punto, proxima, anterior, title FROM lineas WHERE tipo = 'terminal' ORDER BY tramo ASC");
$nummmmm = mysql_numrows($resultpathst);
while ($rrrrr < $nummmmm) {
$lineapatht=mysql_result($resultpathst,$rrrrr,"linea");
$puntopatht=mysql_result($resultpathst,$rrrrr,"punto");
$proximapatht=mysql_result($resultpathst,$rrrrr,"proxima");
$anteriorpatht=mysql_result($resultpathst,$rrrrr,"anterior");
$titlepatht=mysql_result($resultpathst,$rrrrr,"title");
?>

function transition<?echo $lineapatht;?>_<?echo $puntopatht;?>() {
d3.select("#circle<?echo $lineapatht;?>_<?echo $anteriorpatht;?>").remove()
setTimeout('transition<?echo $lineapatht;?>_<?echo $proximapatht;?>()', frequency<?echo $titlepatht;?> * 1000);
}

<?$rrrrr++;
}
?>
  function updateFrequenciesA() {
	setTimeout('transitiona2_1()',frequencyA * 1000);
	setTimeout('transitiona3_1()',frequencyA * 1000);
	setTimeout('transitiona4_1()',frequencyA * 2000);
	setTimeout('transitiona5_1()',frequencyA * 2000);
	setTimeout('transitiona6_1()',frequencyA * 3000);
	setTimeout('transitiona7_1()',frequencyA * 3000);
}
  function updateFrequenciesB() {
	setTimeout('transitionb2_1()',frequencyB * 1000);
	setTimeout('transitionb3_1()',frequencyB * 1000);
	setTimeout('transitionb4_1()',frequencyB * 2000);
	setTimeout('transitionb5_1()',frequencyB * 2000);
	setTimeout('transitionb6_1()',frequencyB * 3000);
	setTimeout('transitionb7_1()',frequencyB * 3000);
}
  function updateFrequenciesC() {
	setTimeout('transitionc2_1()',frequencyC * 1000);
	setTimeout('transitionc3_1()',frequencyC * 1000);
	setTimeout('transitionc4_1()',frequencyC * 2000);
	setTimeout('transitionc5_1()',frequencyC * 2000);
	setTimeout('transitionc6_1()',frequencyC * 3000);
	setTimeout('transitionc7_1()',frequencyC * 3000);
}
  function updateFrequenciesD() {
	setTimeout('transitiond2_1()',frequencyD * 1000);
	setTimeout('transitiond3_1()',frequencyD * 1000);
	setTimeout('transitiond4_1()',frequencyD * 2000);
	setTimeout('transitiond5_1()',frequencyD * 2000);
	setTimeout('transitiond6_1()',frequencyD * 3000);
	setTimeout('transitiond7_1()',frequencyD * 3000);
}
  function updateFrequenciesE() {
	setTimeout('transitione2_1()',frequencyE * 1000);
	setTimeout('transitione3_1()',frequencyE * 1000);
	setTimeout('transitione4_1()',frequencyE * 2000);
	setTimeout('transitione5_1()',frequencyE * 2000);
	setTimeout('transitione6_1()',frequencyE * 3000);
	setTimeout('transitione7_1()',frequencyE * 3000);
}
  function updateFrequenciesH() {
	setTimeout('transitionh2_1()',frequencyH * 1000);
	setTimeout('transitionh3_1()',frequencyH * 1000);
	setTimeout('transitionh4_1()',frequencyH * 2000);
	setTimeout('transitionh5_1()',frequencyH * 2000);
	setTimeout('transitionh6_1()',frequencyH * 3000);
	setTimeout('transitionh7_1()',frequencyH * 3000);
}

// iniciamos la red
transitiona0_1();
transitiona1_1();
transitionb0_1();
transitionb1_1();
transitionc0_1();
transitionc1_1();
transitiond0_1();
transitiond1_1();
transitione0_1();
transitione1_1();
transitionh0_1();
transitionh1_1();

updateFrequenciesA();
updateFrequenciesB();
updateFrequenciesC();
updateFrequenciesD();
updateFrequenciesE();
updateFrequenciesH();


 var loading = document.getElementById('loading'); 
 loading.style.display = "none";
</script>

<h1>Subte virtual <sup>by <a href="http://www.andytow.com">Andy Tow</a></sup></h1>
<p><small>Creado con <a href="http://d3js.org/" target="_blank">D3.js</a> con datos de <a href="http://data.buenosaires.gob.ar/dataset/subterraneos" target="_blank">Buenos Aires Data</a> y <a href="https://github.com/palamago/sucursales-sube" target="_blank">Pablo Paladino</a>.</small></p>

</body>
</html>
