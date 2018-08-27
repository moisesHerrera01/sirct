
<style type="text/css">
    
#container
{
    position: absolute;
    top: 0px;
    right: 0px;
    bottom: 0px;
    left: 0px;
    height: 640px;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    -o-user-select: none;
    user-select: none;
}

#myCanvas
{
    width: 640px;
    cursor: move;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    -o-user-select: none;
    user-select: none;
}

#minus, #plus
{
    font-family: 'Ubuntu';
    font-size: 24px;
    font-weight: bold;
    cursor: pointer;
}

#minus:hover, #plus:hover
{
    background-color: Yellow;
}

</style>
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="align-self-center" align="center">
                <h3 class="text-themecolor m-b-0 m-t-0">
                    <?php 
                        echo $titulo = ucfirst("Lo sentimos, la página está en mantenimiento"); 
                    ?>
                    </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-1">
            </div>
            <div class="col-lg-2">
                <span class="mdi mdi-worker" style="font-size: 150px;"></span>
            </div>
            <div class="col-lg-8">
                <br><br>
                <h2> Estamos trabajando para reparar esta página lo más pronto posible. Por favor espere hasta que terminemos todas las reparaciones. Juegue con el mapita si quiere dele clic</h2>

                <div id="container">
                    <canvas id="myCanvas"></canvas>
                    <div>
                        <span id="minus">-Zoom Out</span>&nbsp;&nbsp;<span id="plus">+Zoom In</span>
                    </div>
                </div>

                <script type="text/javascript">
    var canvas = document.getElementById('myCanvas');
      var context = canvas.getContext('2d');
    canvas.width = 800;
canvas.height = 600;

var xc = 0;
var yc = 0;


    function writeMessage(canvas, message) {
        /*var context = canvas.getContext('2d');
        context.clearRect(0, 0, canvas.width, canvas.height);
        context.font = '18pt Calibri';
        context.fillStyle = 'black';
        context.fillText(message, 10, 25);

        context.beginPath();
        context.arc(100,75,50,0,2*Math.PI);
        context.stroke();*/

        

      }
      function getMousePos(canvas, evt) {
        var rect = canvas.getBoundingClientRect();
        xc = evt.clientX - rect.left;
        yx = evt.clientY - rect.top;
        return {
          x: evt.clientX - rect.left,
          y: evt.clientY - rect.top
        };
      }

      canvas.addEventListener('click', function(evt) {
        var mousePos = getMousePos(canvas, evt);
        var message = 'Mouse position: ' + mousePos.x + ',' + mousePos.y;

        xc = evt.offsetX || (evt.pageX - canvas.offsetLeft);
        yc = evt.offsetY || (evt.pageY - canvas.offsetTop);

        writeMessage(canvas, message);
      }, false);

    var gkhead = new Image;

    window.onload = function(){     
    
            var ctx = canvas.getContext('2d');
            trackTransforms(ctx);
          
    function redraw(){

          // Clear the entire canvas
          var p1 = ctx.transformedPoint(0,0);
          var p2 = ctx.transformedPoint(canvas.width,canvas.height);
          ctx.clearRect(p1.x,p1.y,p2.x-p1.x,p2.y-p1.y);
          ctx.save();
          ctx.setTransform(1,0,0,1,0,0);
          ctx.clearRect(0,0,canvas.width,canvas.height);
          ctx.restore();

          ctx.drawImage(gkhead,0,0);      

          ctx.beginPath();
          ctx.arc(xc,yc,25,0,2*Math.PI);
          ctx.stroke();    

        }
        redraw();

      var lastX=canvas.width/2, lastY=canvas.height/2;

      var dragStart,dragged;

      canvas.addEventListener('mousedown',function(evt){
          document.body.style.mozUserSelect = document.body.style.webkitUserSelect = document.body.style.userSelect = 'none';
          lastX = evt.offsetX || (evt.pageX - canvas.offsetLeft);
          lastY = evt.offsetY || (evt.pageY - canvas.offsetTop);
          dragStart = ctx.transformedPoint(lastX,lastY);
          dragged = false;
      },false);

      canvas.addEventListener('mousemove',function(evt){
          lastX = evt.offsetX || (evt.pageX - canvas.offsetLeft);
          lastY = evt.offsetY || (evt.pageY - canvas.offsetTop);
          dragged = true;
          if (dragStart){
            var pt = ctx.transformedPoint(lastX,lastY);
            ctx.translate(pt.x-dragStart.x,pt.y-dragStart.y);
            redraw();
                }
      },false);

      canvas.addEventListener('mouseup',function(evt){
          dragStart = null;
          if (!dragged) zoom(evt.shiftKey ? -1 : 1 );
      },false);

      var scaleFactor = 1.1;

      var zoom = function(clicks){
          var pt = ctx.transformedPoint(lastX,lastY);
          ctx.translate(pt.x,pt.y);
          var factor = Math.pow(scaleFactor,clicks);
          ctx.scale(factor,factor);
          ctx.translate(-pt.x,-pt.y);
          redraw();
      }

      var handleScroll = function(evt){
          var delta = evt.wheelDelta ? evt.wheelDelta/40 : evt.detail ? -evt.detail : 0;
          if (delta) zoom(delta);
          return evt.preventDefault() && false;
      };
    
      canvas.addEventListener('DOMMouseScroll',handleScroll,false);
      canvas.addEventListener('mousewheel',handleScroll,false);
    };

    gkhead.src = '<?php echo base_url(); ?>assets/images/big/img.png';
    
    // Adds ctx.getTransform() - returns an SVGMatrix
    // Adds ctx.transformedPoint(x,y) - returns an SVGPoint
    function trackTransforms(ctx){
      var svg = document.createElementNS("http://www.w3.org/2000/svg",'svg');
      var xform = svg.createSVGMatrix();
      ctx.getTransform = function(){ return xform; };

      var savedTransforms = [];
      var save = ctx.save;
      ctx.save = function(){
          savedTransforms.push(xform.translate(0,0));
          return save.call(ctx);
      };
    
      var restore = ctx.restore;
      ctx.restore = function(){
        xform = savedTransforms.pop();
        return restore.call(ctx);
              };

      var scale = ctx.scale;
      ctx.scale = function(sx,sy){
        xform = xform.scaleNonUniform(sx,sy);
        return scale.call(ctx,sx,sy);
              };
    
      var rotate = ctx.rotate;
      ctx.rotate = function(radians){
          xform = xform.rotate(radians*180/Math.PI);
          return rotate.call(ctx,radians);
      };
    
      var translate = ctx.translate;
      ctx.translate = function(dx,dy){
          xform = xform.translate(dx,dy);
          return translate.call(ctx,dx,dy);
      };
    
      var transform = ctx.transform;
      ctx.transform = function(a,b,c,d,e,f){
          var m2 = svg.createSVGMatrix();
          m2.a=a; m2.b=b; m2.c=c; m2.d=d; m2.e=e; m2.f=f;
          xform = xform.multiply(m2);
          return transform.call(ctx,a,b,c,d,e,f);
      };
    
      var setTransform = ctx.setTransform;
      ctx.setTransform = function(a,b,c,d,e,f){
          xform.a = a;
          xform.b = b;
          xform.c = c;
          xform.d = d;
          xform.e = e;
          xform.f = f;
          return setTransform.call(ctx,a,b,c,d,e,f);
      };
    
      var pt  = svg.createSVGPoint();
      ctx.transformedPoint = function(x,y){
          pt.x=x; pt.y=y;
          return pt.matrixTransform(xform.inverse());
      }
    }



      

      

                </script>

            </div>
            <div class="col-lg-1">
            </div>
        </div>
        
        <script type="text/javascript">
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>

    </div> 
</div>