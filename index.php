<!doctype>
<html>

<head>
	<title>_PDF</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="theme-color" content="#C355FF" />
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="./css/main.css">
	<link rel="stylesheet" type="text/css" href="./css/style.css">
	<style>
		.canvas-cont {
 			height: 100%;
			overflow: auto;
		}

		.canvas-cont {
			background: rgb(107, 107, 107);
			text-align: center;
			border: solid 3px;
		}
		.lblCodigo {
			position:absolute;
			z-index:1500;
			font-size: 40px;
			color:#333;
			animation: lbl-centro 15s ease-in-out; 
			right:190%;
			top: 0%;
			display:none;
   
		}
		.div-modal{
			z-index:2000;
			display:none;
			position:absolute;
			background-color: ivory;
			height: 100px;
			width: 250px;
			right:60px;
			bottom: 320px;
			-webkit-box-shadow: 10px 10px 33px 2px rgba(0,0,0,0.75);
			-moz-box-shadow: 10px 10px 33px 2px rgba(0,0,0,0.75);
			box-shadow: 10px 10px 33px 2px rgba(0,0,0,0.75);
			padding:5px;
		  }
		  .lapices{
			z-index:2000;
			display:none;
			position:absolute;
			background-color: ivory;
			height: 370px;
			width: 250px;
			right:60px;
			bottom: 0px;
			-webkit-box-shadow: 10px 10px 33px 2px rgba(0,0,0,0.75);
			-moz-box-shadow: 10px 10px 33px 2px rgba(0,0,0,0.75);
			box-shadow: 10px 10px 33px 2px rgba(0,0,0,0.75);
			padding:5px;
		  }
		  .color-paleta{
			z-index:2000;
			display:none;
			position:absolute;
			background-color: ivory;
			height: 55px;
			width: 250px;
			right:60px;
			bottom: 180px;
			-webkit-box-shadow: 10px 10px 33px 2px rgba(0,0,0,0.75);
			-moz-box-shadow: 10px 10px 33px 2px rgba(0,0,0,0.75);
			box-shadow: 10px 10px 33px 2px rgba(0,0,0,0.75);
			padding:5px;
		  }
		  .tamano{
			z-index:2000;
			display:none;
			position:absolute;
			background-color: ivory;
			height: 55px;
			width: 250px;
			right:60px;
			bottom: 125px;
			-webkit-box-shadow: 10px 10px 33px 2px rgba(0,0,0,0.75);
			-moz-box-shadow: 10px 10px 33px 2px rgba(0,0,0,0.75);
			box-shadow: 10px 10px 33px 2px rgba(0,0,0,0.75);
			padding:5px;
		  }
		  .opacidad{
			z-index:2000;
			display:none;
			position:absolute;
			background-color: ivory;
			height: 55px;
			width: 250px;
			right:60px;
			bottom: 72px;
			-webkit-box-shadow: 10px 10px 33px 2px rgba(0,0,0,0.75);
			-moz-box-shadow: 10px 10px 33px 2px rgba(0,0,0,0.75);
			box-shadow: 10px 10px 33px 2px rgba(0,0,0,0.75);
			padding:5px;
  		}
	</style>
</head>

<body oninit="setUrl('<?php echo "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>')">
	 
	<div class="fixed-action-btn">

		<div class="opacidad" id="opacidad">
			<input type="range" id="transparencia"
			ontouchend="cambiaTransp()" onclick="cambiaTransp()" name="trans" min="00"
			max="255">
		</div>
		<div class="tamano" id="tamano">
			<input type="range" id="tamano-in" ontouchend="cambiaTama()"
			onclick="cambiaTama()" name="tam" min="00" max="100">
		</div>
		<div class="color-paleta" id="color-paleta">
			<input id="paletaColor" class="jscolor"	onchange="cambiarColor()" value="ab2567">
		</div>
		
		<!--modal para ingresar el código-->
		<div class="lapices" id="lapices">
			<div class="dropdown-item"
					onclick="setLapiz(2,'414141','3F','Lápiz negro','assets/lapiz.png')"><img
						style="width: 70px;" src="assets/lapiz.png"> Lápiz negro</div>
				<div class="dropdown-divider"></div>
				<div class="dropdown-item"
					onclick="setLapiz(36,'DEFF00','05','Marcador Flúor','assets/fluor.png')"><img
						style="width: 70px;" src="assets/fluor.png"> Marcador Flúor</div>
				<div class="dropdown-divider"></div>
				<div class="dropdown-item"
					onclick="setLapiz(36,'FFA900','05','Marcador Naranja','assets/anaranjado.png')"><img
						style="width: 70px;" src="assets/anaranjado.png"> Marcador Naranja</div>
				<div class="dropdown-divider"></div>
				<div class="dropdown-item"
					onclick="setLapiz(36,'1BFF0D','05','Marcador Verde','assets/verde.png')"><img
						style="width: 70px;" src="assets/verde.png"> Marcador Verde</div>
		</div>
		<div id="div-modal" class="div-modal">
			<input id="txt-codigo" placeholder="Ingrese su código" maxlength="6" type="text" data-length="6">
			 
			<button id="div-get-btn" onclick="getPdf()" class="btn btn-info">Cargar</button>
		 </div>
		<label class="lblCodigo" id="lblCodigo" >Hola</label>
		
		<a title="Menú de opciones" class="btn-floating btn-large deep-purple accent-1">
		  <i class="large material-icons">menu</i>
		</a>
		<ul>
		  <li><a onclick="menu('abrir')" title="Abrir Pdf" class="btn-floating orange lighten-3"><i class="material-icons">insert_drive_file</i></a></li>
		  <li><a onclick="sendPdf()" title="Compartir" class="btn-floating orange lighten-2"><i class="material-icons">share</i></a></li>
		  <li><a onclick="menu('get')" title="Cargar código"  class="btn-floating orange lighten-1"><i class="material-icons">input</i></a></li>
		  <li><a onclick="guardarPDF()" title="Descargar" class="btn-floating orange"><i class="material-icons">file_download</i></a></li>
		  <li><a onclick="menu('pen')" title="Elegir lápiz" class="btn-floating orange darken-1"><i class="material-icons">edit</i></a></li>
		  <li><a onclick="menu('color')"title="Color de lápiz" class="btn-floating orange darken-2"><i class="material-icons">color_lens</i></a></li>
		  <li><a onclick="menu('tamano')" title="Tamaño de lápiz" class="btn-floating orange darken-3"><i class="material-icons">lens</i></a></li>
		  <li><a onclick="menu('opacidad')" title="Opacidad de lápiz" class="btn-floating orange darken-4"><i class="material-icons">opacity</i></a></li>	   
		</ul>
				
		</div>
   	<div id="drop_zone" >
			<output id="list"></output>
 
			<div id="my_pdf_viewer">
				<div class="canvas-cont" id="canvas_container">
					<!-- <canvas id="render"></canvas> -->
				</div>
			</div>
		</div>

		<!-- controles -->
		<input style="display:none;" #file type="file" id="file1" onchange="abrir()" accept="application/pdf" lang="es">
		
	 
 	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.1/jspdf.debug.js" ></script>
	<script src="http://cdn.jsdelivr.net/g/filesaver.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.4.456/pdf.js"></script>
 	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.slim.js"></script>
 	<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jscolor/2.0.4/jscolor.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
	<script type="text/javascript" src="js/script.js"></script>
</body>

</html>