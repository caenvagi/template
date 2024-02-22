<?php
	session_start();

        require '../conexion/conexion.php';

        if (!isset($_SESSION['id'])) {
            header("Location: ../../index.php");
        }
        $id = $_SESSION['id'];
        $nombre = $_SESSION['nombre'];
        $tipo_usuario = $_SESSION['tipo_usuario'];
        $usuario = $_SESSION['usuario'];
        $foto = $_SESSION['avatar'];

        if ($tipo_usuario == 1) {
            $where = "";
        } else if ($tipo_usuario == 2) {
            $where = "WHERE id=$id";
        }
    
    date_default_timezone_set('America/Bogota');

    header('Content-Type: text/html; charset=ISO-8859-1');

    echo "<link rel='stylesheet' type='text/css' href='../css/styles.css'>";
    echo "<link rel='stylesheet' type='text/css' href='../css/estilos.css'>";
    
    // inicio consultas
    // fin consultas       

    
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require '../logs/head.php';?>
          <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    </head>
    <body>
        <?php require '../logs/nav-bar.php';?>
        <!-- inicio pagina -->
            <div id="layoutSidenav_content" class="bg-light">
                <main >
                    
                        <!-- FECHA Y HORA-->
                            <div class="container-fluid px-4">
                                <h5 class="mt-4 text-center" style="color:grey">
                                <?php  setlocale(LC_TIME,"spanish"); echo strftime("%A, %d de %B de %Y");?> 
                                </h5>
                            </div> 
                        <!--FIN  FECHA Y HORA-->
                        
                        <!-- grafico ventas -->
                                <div class="card m-3 border border-1 rounded-3">
                                    <div class="container-fluid px-4">
                                        <h4 class="mt-2 text-center text-grey">
                                            <i class='far fa-chart-bar' style='font-size:18x'></i>&nbsp;&nbsp;Grafico de ventas mes de <?php  setlocale(LC_TIME,"spanish"); echo strftime("%B");?>
                                        </h4>
                                        <div class="row">
                                            <div class="container-fluid px-3">
                                                <div class="row">
                                                    
                                                    <div class="col-xl-6 col-md-6 mt-2" >
                                                        <div class="cardes text-black m-1 border border-dark border border-1 rounded-3" style="background-color: #faf7f8"0 >
                                                            <div class="">&nbsp &nbsp Grafico ventas por mes </div>
                                                            
                                                                <div class="card-body m-1 p-2">
                                                                    <canvas id="grafica2"></canvas>                                                            
                                                                </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-6 col-md-6 mt-2" >
                                                        <div class="cardes text-black m-1 border border-dark border border-1 rounded-3" style="background-color: #faf7f8"0 >
                                                            <div class="">&nbsp &nbsp Grafico unidades por mes </div>
                                                            
                                                                <div class="card-body m-1 p-2">
                                                                    <canvas id="grafica3"></canvas>                                                            
                                                                </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                </div>    
                        <!-- grafico ventas -->
                        
                        <!-- ventas diarias por mes-->
                            <div class="card m-3">
                                <div class="card-header text-center">
                                       <h4 class="mt-2 text-center text-grey"> Ventas diarias mes de <?php  setlocale(LC_TIME,"spanish"); echo strftime("%B");?></h4>
                                </div>
                                <div class="card-body">
                                    <table id="ventasdiarias" class="display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>FECHA</th>
                                                <th>PRODUCTOS</th>
                                                <th>VENTA</th>
                                                <th>RANK</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>                                            
                                            <tr>
                                                <td><?php $inicio = strftime("%A, %d de %B del %Y", strtotime($fecha)); echo $inicio?></td>
                                                <td></td>
                                                <td>$ &nbsp;</td>
                                                <td></td>                                                
                                            </tr>                                           
                                    </table>      
                                </div>
                            </div>
                        <!-- ventas diarias por mes-->
                    
                        <!-- card ventas por categoria  2-->
                            <div class="card">
                                    <div class="card-header text-center">
                                    <h4><span class="material-icons">category</span>&nbsp;&nbsp;
                                    Ventas por categoria mes de <?php  setlocale(LC_TIME,"spanish"); echo strftime("%B");?>
                                    </div></h4>
                                    <ul class="ventasCat">                                    
                                        <li>                                            
                                            <img src="" alt="" class="logoCat">
                                            <span class="info"> <h7>$&nbsp;</h7>
                                            <p></p></span>                                                                                           
                                        </li>                                                                           
                                    </ul>
                                </div>    
                        <!-- card ventas por categoria -->
                </main>
                <?php require '../logs/nav-footer.php'; ?>
            </div>         
        <!-- FIN pagina -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@latest/dist/Chart.min.js"></script>
        <script>
            // Obtener una referencia al elemento canvas del DOM
            const $grafica2 = document.querySelector("#grafica2");
                // Las etiquetas son las que van en el eje X. 
                const etiquetas1 =['ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC']
                // Podemos tener varios conjuntos de datos. Comencemos con uno
                const datosVentas1 = {
                    label: "<?php setlocale(LC_TIME,"spanish"); echo strftime("%Y", strtotime("- 1 YEAR")); ?>",
                    data: [] // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
                    backgroundColor: 'rgba(54, 10, 250, 0.2)', // Color de fondo
                    borderColor: 'rgba(54, 45, 250, 1)', // Color del borde
                    borderWidth: 1, // Ancho del borde
                };
                const datosVentas2 = {
                        label: "<?php setlocale(LC_TIME,"spanish"); echo strftime("%Y"); ?>",
                        data: [], // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
                        backgroundColor: 'rgba(245, 115, 158, 0.5)', // Color de fondo
                        borderColor: 'rgba(245, 115, 158, 1)', // Color del borde
                        borderWidth: 1, // Ancho del borde
                    };
                    new Chart($grafica2, {
                        type: 'line', // Tipo de gráfica
                        data: {
                            labels: etiquetas1,
                            datasets: [
                                datosVentas1,
                                datosVentas2,               
                                // Aquí más datos...
                            ]
                        },
                        options: {
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }],
                            },
                        }
                    });
        </script>
        <script>
                    
                            // Obtener una referencia al elemento canvas del DOM
                            const $grafica3 = document.querySelector("#grafica3");
                            // Las etiquetas son las que van en el eje X. 
                            const etiquetas3 = ['ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC']
                            // Podemos tener varios conjuntos de datos. Comencemos con uno
                            const datosVentas3 = {
                                label:'<?php setlocale(LC_TIME,"spanish"); echo strftime("%Y", strtotime("- 1 YEAR")); ?>',
                                data: [], // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
                                backgroundColor: 'rgba(199, 248, 201, 0.8)', // Color de fondo
                                borderColor: 'rgba(17, 248, 27, 0.8)', // Color del borde
                                borderWidth: 1, // Ancho del borde
                            };
                            const datosVentas4 = {
                                label: '<?php setlocale(LC_TIME,"spanish"); echo strftime("%Y"); ?>',
                                data: [], // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
                                backgroundColor: 'rgba(246, 133, 131,1)', // Color de fondo
                                borderColor: 'rgba(251, 11, 6, 0.8)', // Color del borde
                                borderWidth: 1, // Ancho del borde
                            };
                            new Chart($grafica3, {
                                type: 'bar', // Tipo de gráfica
                                data: {
                                    labels: etiquetas3,
                                    datasets: [
                                        datosVentas3,
                                        datosVentas4,
                                        // Aquí más datos...
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    scales: {
                                        yAxes: [{
                                            ticks: {
                                                beginAtZero: true
                                            }
                                        }],
                                    },
                                }
                            });
        </script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function() {
                    var table = $('#ventasdiarias').DataTable( {
    
                        order: [[ 1, "desc" ]],
    
                        stateSave: true,                   
    
                        rowReorder: {
                        selector: 'td:nth-child(11)'
                        },
                        responsive: true,
                        pageLength: 50,
                        language: {
                        url: "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                        },
                        
                        select: {
                            style:    'os',
                            selector: 'td:first-child'
                            },
                                
                            
                    });
                    
                });      
        </script>
        
    </body>
</html>
                                                                            
                                                                            

                                                                            

                                                    
                                                
                                                
                                                
                                                
                                                
                                                




                        
                        

                       
						
                        
                        
                       
