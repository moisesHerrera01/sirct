
<style type="text/css">
    
title {
  fill: black;
}

circle:hover
  {
    stroke-width: 4px;
    fill: red;
  }

circle:hover
  {
    height: 120%;
    width: 120%;
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

                <div class="row">
                    <div class="col-lg-12" style="display: block;">
                        <svg id="svg_chuco" xmlns="http://www.w3.org/2000/svg" width="800" height="400" style="background-image: url('<?php echo base_url(); ?>/assets/images/croquis/Imagen1.png'); background-size: cover; background-repeat: no-repeat;"/>
                        <script type="text/javascript">
                            function makeSVG(tag, attrs) {
                                var el= document.createElementNS('http://www.w3.org/2000/svg', tag);
                                for (var k in attrs)
                                    el.setAttribute(k, attrs[k]);
                                return el;
                            }

                        </script>
                    </div>
                    
                </div>

        	</div>
        	<div class="col-lg-1">
        	</div>
        </div>
        <div class="card">
            <div class="table-responsive">
                <table  id="myTable" class="table table-hover table-bordered" width="100%">
                    <thead class="bg-inverse text-white">
                        <tr>
                            <th>NR</th>
                            <th>Nombre completo</th>
                            <th>id_cargo</th>
                            <th>descripcion funcional</th>
                            <th>nivel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            $viaticos_ruta = $this->db->query("SELECT e.nr, UPPER(CONCAT_WS(' ', e.primer_nombre, e.segundo_nombre, e.tercer_nombre, e.primer_apellido, e.segundo_apellido, e.apellido_casada)) AS nombre_completo, f.* FROM sir_empleado AS e JOIN sir_empleado_informacion_laboral AS i ON e.id_empleado = i.id_empleado JOIN sir_cargo_funcional AS f ON f.id_cargo_funcional = i.id_cargo_funcional AND e.id_estado = '00001' GROUP BY e.id_empleado ORDER BY e.primer_nombre");
                            if($viaticos_ruta->num_rows() > 0){ 
                                foreach ($viaticos_ruta->result() as $fila) {
                        ?>
                            <tr>
                                <td><?php echo $fila->nr; ?></td>
                                <td><?php echo $fila->nombre_completo; ?></td>
                                <td><?php echo $fila->id_cargo_funcional; ?></td>
                                <td><?php echo $fila->funcional; ?></td>
                                <td><?php echo $fila->id_nivel; ?></td>
                            </tr>
                        <?php
                                }
                            }else{
                        ?>
                            <tr>
                                <td colspan="3">Ningún registro de viático asociado...</td>
                            </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <script type="text/javascript">
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>

    </div> 
</div>

<script type="text/javascript">
    
    $(function(){
        $(document).ready(function(){
            $("#svg_chuco").click(function(e){
                 getPosition(e); 
            });

            var pointSize = 10;
            var contador = 0;

            function getPosition(event){
                 var rect = svg_chuco.getBoundingClientRect();
                 var x = event.clientX - rect.left;
                 var y = event.clientY - rect.top;
                    
                 drawCoordinates(x,y);
            }

            function drawCoordinates(x,y){  
                //var ctx = document.getElementById("canvas").getContext("2d");

                //$("#svg_chuco").append('<rect x="1" y="1" height="50" width="50" rx="20" ry="20"/>')


                /*ctx.fillStyle = "#26c6da"; // Red color
                ctx.strokeStyle = "#595c5feb"; // Red color

                ctx.beginPath();
                ctx.arc(x, y, pointSize, 0, Math.PI * 2, true);

                //void arc(in float x, in float y, in float radius, in float startAngle, in float endAngle, in boolean anticlockwise Optional );

                ctx.fill();
                ctx.lineWidth=2;
                ctx.stroke();*/

                contador++;

                var circle = makeSVG('circle', {cx: x, cy: y, r:10, stroke: '#595c5feb', 'stroke-width': 2, fill: '#26c6da', class: "algo", id: "hola"+contador, oncontextmenu: "alert('hola"+contador+"')", style: "cursor: pointer;"});

                document.getElementById('svg_chuco').appendChild(circle);

                /*var title = makeSVG('rect', {id: "rect_title"+contador});

                document.getElementById("hola"+contador).appendChild(title);*/

                var titulo = makeSVG('title', {id: "titulo"+contador});

                titulo.textContent = "Hola";

                document.getElementById("hola"+contador).appendChild(titulo)

                //$("#titulo"+contador).text("Hola");

                    //$("#hola"+contador).append('<text x="15" y="16">Hello</text>');
            }

        });
    });

</script>