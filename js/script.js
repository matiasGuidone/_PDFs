//Variables********
    var frame; 
    var ctx; 
    var cw; 
    var ch; 
    var dibujar = false;
    var urlPdf;
    var n;
    var dragging =false;
    var hue =0;
    var transp='02';
    var color = '#ab2567'; 
    var width = 7;
    var anchor;
    var largor;
    var myState = {
        pdf: null,
        currentPage: 1,
        zoom: 2,
        cantidadPaginas:0,
        arrayPaginas :[],}
    var dropZone = document.getElementById('drop_zone');
        dropZone.addEventListener('dragover', handleDragOver, false);
        dropZone.addEventListener('drop', handleFileSelect, false);
      


//**Apertura de archivos */
function handleFileSelect(evt) {
    evt.stopPropagation();
    evt.preventDefault();
    var files = evt.dataTransfer.files;
    var file = files[0];
    if (file.length === 0 )
      return;
      borrarCanvas();
      readThis(file);
  }

function handleDragOver(evt) {
    evt.stopPropagation();
    evt.preventDefault();
    evt.dataTransfer.dropEffect = 'copy';  
  }

function abrir() {
    var file =document.querySelector('input[type=file]').files[0];
    if (file.length === 0 )
      return;
      borrarCanvas();
      readThis(file);
      
}

function readThis(inputValue) {
    var file = inputValue;
    var myReader = new FileReader();
    myReader.readAsDataURL(file);
    myReader.onloadend = (e) => {
        var string = myReader.result;
        cargarDocumento(string); } 
    }

function cargarDocumento(base64){
    var loadingTask = pdfjsLib.getDocument({ data: atob(base64.substring('data:application/pdf;base64,'.length)) });
    loadingTask.promise.then(function (pdf) {
        myState.cantidadPaginas = pdf.numPages;
        myState.pdf=pdf;
        render();
        });
      }


//**funcion para el Dibujo */ 
function inicializaDibujo(){ 
    for (var i = 1;i<=myState.cantidadPaginas;i++)  {
    var iframe = document.getElementById('render'+i);
    ctx = iframe.getContext('2d');
    cw = iframe.width = anchor, cx = cw / 2;
    ch = iframe.height = largor, cy = ch / 2;

    //**Eventos Mouse */
    iframe.addEventListener('mousedown', function(evt){
        dibujar=true;
        ctx=evt.target.getContext('2d');
        ctx.beginPath();
    }, false);

    iframe.addEventListener('mousemove', function(evt){
        if(dibujar){
            ctx = evt.target.getContext('2d');
            var m = oMousePosition(evt);
            ctx.lineTo(m.x,m.y);
            ctx.strokeStyle = color+transp;
            ctx.lineJoin = 'round';
            ctx.lineCap = 'round';
            ctx.lineWidth = width;
            ctx.stroke(); }
        }, false);

    iframe.addEventListener('mouseup', function (evt){
        dibujar=false;
        }, false);

    iframe.addEventListener('mouseout', function (evt){
        dibujar=false;
        }, false);

    //**Eventos Touch */
    iframe.addEventListener('touchstart', function(evt){
    if(evt.touches.length==1){
        document.getElementById('canvas_container').style.overflow="hidden";
        dibujar=true;
        ctx.beginPath();
        return;}
        else{document.getElementById('canvas_container').style.overflow="auto";
        dibujar=false;
        return;} }, false);

    iframe.addEventListener('touchmove', function(evt){
        if(dibujar){
            var m = oTouchPosition(iframe, evt);
            ctx.lineTo(m.x,m.y);
            ctx.strokeStyle = color+transp;
            ctx.lineJoin = 'round';
            ctx.lineCap = 'round';
            ctx.lineWidth = width;
            ctx.stroke();
            document.getElementById('canvas_container').style.overflow="hidden";}
        }, false);

    iframe.addEventListener('touchend', function(evt){
            document.getElementById('canvas_container').style.overflow="auto";
        }, false);
    }
}
//**Configuracion del lápiz */
function cambiarColor(){
    color = '#'+document.getElementById('paletaColor').value;
}
function cambiaTransp(){
    var n = parseInt(document.getElementById('transparencia').value);
    var str ='0'+n.toString(16);
    if (str.length===2){
    transp = str;}
    else transp = str.substring(1,3);
  
}
function cambiaTama(){
    width = document.getElementById('tamano').value;
 
}
function setLapiz(tamano, col, transparencia, nombre, img){
    var pen = document.getElementById('lapices');
    pen.style.display = "none";
    width = tamano;
    color = '#'+col;
    transp = transparencia;
    document.getElementById('tamano').value = width;
    document.getElementById('transparencia').value = transp.toString(10);
    document.getElementById('paletaColor').value = col;
    document.getElementById('lapizpre').innerHTML= '<img style=" padding:0; width: 23px;" src="'+img+'">  '+nombre;
}

//**Posiciones del puntero */
  function oMousePosition(evt){
      var componente = evt.target;
      var posPuntero = componente.getBoundingClientRect();
      return{
          x: Math.round(evt.clientX-posPuntero.left),
          y: Math.round(evt.clientY-posPuntero.top)
      }
  }
  function oTouchPosition(iframe, evt){
    var posPuntero = iframe.getBoundingClientRect();
    let correxionX=posPuntero.x;
    let correxionY=posPuntero.y;
    if(evt.changedTouches == undefined){
        return {
            x: evt.layerX,
            y: evt.layerY
        }
    }
    return{
        x:  evt.changedTouches[0].pageX- correxionX,
        y:  evt.changedTouches[0].pageY- correxionY
        }
    }

//**Renderizado de página */
function render(){
      n = 1;
      var canvasContainer = document.getElementById('canvas_container');
        for (var i =1 ;i<=myState.cantidadPaginas;i++ ){
            myState.pdf.getPage(i).then(function (page) {
            renderPage(page, canvasContainer);
        });
    }
}
function renderPage(page, canvasContainer){
    var scale = 1.5;
    var viewport = page.getViewport({ scale: myState.zoom, });
    var canvas = document.createElement('canvas');
    canvas.id = 'render'+n;n++;
    var context = canvas.getContext('2d');
    canvas.height = viewport.height;
    canvas.width = viewport.width;
    var renderContext = {
        canvasContext: context,
        viewport: viewport,
    };
    canvasContainer.appendChild(document.createElement('br'));
    canvasContainer.appendChild(document.createElement('br'));
    canvasContainer.appendChild(canvas);
    page.render(renderContext);
    anchor=canvas.width;
    largor=canvas.height;
    
    if(n>myState.cantidadPaginas){inicializaDibujo();}
  }
   
 function borrarCanvas(){
    var canvasContainer = document.getElementById('canvas_container');
    canvasContainer.innerHTML=null;
}

//**guardar PDF */
function guardarPDF(){
    var pdf = new jsPDF("p","mm","a4");
     for (var i=1;i<=myState.cantidadPaginas;i++){
      var n = document.getElementById('render'+i);
      var imgData = n.toDataURL("image/jpeg", 1.0);
      pdf.addImage(imgData,'JPEG',0,0,210,297);
      if(i<myState.cantidadPaginas){
      pdf.addPage();}
    }
    pdf.save("./download.pdf");
 }

  //**set Url */
  var baseurl = ''; 
  function setUrl(url){
      baseurl = url;
  }

 //**send PDF */
 function sendPdf(){
    var pdfs = {
        usuario:'Usuario',
        blobPdf:''
    }
    
    var pdf = new jsPDF("p","mm","a4");
    for (var i=1;i<=myState.cantidadPaginas;i++){
     var n = document.getElementById('render'+i);
     var imgData = n.toDataURL("image/jpeg", 1.0);
     pdf.addImage(imgData,'JPEG',0,0,210,297);
     if(i<myState.cantidadPaginas){
     pdf.addPage();}
   }
   
  var blob = new Blob([pdf.output('blob')], {type :'application/pdf'});
  var myReader = new FileReader();
  myReader.readAsDataURL(blob);
  myReader.onloadend = (e) => {
      var string = myReader.result;
      pdfs.blobPdf = string;
   var xhr = new XMLHttpRequest();
   var url = baseurl+'Controller/pdfs_controlador.php';
   xhr.open("POST", url, true);
   xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
   xhr.onreadystatechange = function () {
       if( xhr.readyState == 4 && xhr.status == 200 ){
           var lblCodigo = document.getElementById("lblCodigo");
           lblCodigo.innerText = xhr.responseText;
           lblCodigo.style.display = 'block';
           lblCodigo.style.opacity = '1';
           setTimeout( function(){lblCodigo.style.opacity = '.3'},15000);
       }
   }
   
   var data = JSON.stringify(pdfs); 
   xhr.send(data);
    }
 }
 
  //**get PDF */
  function getPdf(){
    var div = document.getElementById('div-modal');
    div.style.display = 'none';
    var xhr = new XMLHttpRequest();
    var url = baseurl+'Controller/pdfs_controlador.php';
    url = url + '?codPdf='+document.getElementById("txt-codigo").value;
    xhr.open("GET", url, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
       if( xhr.readyState == 4 && xhr.status == 200 ){
            borrarCanvas();
            var json = JSON.parse(xhr.response);
            cargarDocumento(json['blobPdf']);
       }
   }
    
   xhr.send(null);
}

//** Materialize botones */
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.fixed-action-btn');
    var instances = M.FloatingActionButton.init(elems, {
      direction: 'buttom',
      hoverEnabled: false
    });
  });

 //** Materialize input */
 $(document).ready(function() {
    $('input#txt-codigo').characterCounter(); 
    
  });
       
  //**Menu redireccionamiento de botones */  
  var pen = document.getElementById('lapices');
   pen.addEventListener("mouseleave", function( event ) {
    pen.style.display = 'none';
  });

  var col = document.getElementById('color-paleta');
  col.addEventListener("mouseleave", function( event ) {
    col.style.display = 'none';
  });

  var tam = document.getElementById('tamano');
  tam.addEventListener("mouseleave", function( event ) {
    tam.style.display = 'none';
  });

  var opa = document.getElementById('opacidad');
  opa.addEventListener("mouseleave", function( event ) {
    opa.style.display = 'none';
  });

  var div = document.getElementById('div-modal');
  div.addEventListener("mouseleave", function( event ) {
   div.style.display = 'none';
 });
  function menu(item){
      switch (item){
        case 'abrir' : 
        $('#file1').trigger('click');
        break;
        case 'get' :  
            div.style.display = 'block';
            break;
        case 'pen' :  
            pen.style.display = 'block';
            break;
        case 'color' :  
            col.style.display = 'block';
            break;
        case 'tamano' :  
            tam.style.display = 'block';
            break;
        case 'opacidad' :  
            opa.style.display = 'block';
            break;
        default : return;

    }
  }

