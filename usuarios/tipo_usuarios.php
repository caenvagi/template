<?php
    session_start();

    require '../conexion/conexion.php';

    if (!isset($_SESSION['id'])) {
        header("Location: index.php");
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

    echo "<link rel='stylesheet' type='text/css' href='../css/styles.css'>";
    echo "<link rel='stylesheet' type='text/css' href='../css/estilos.css'>";

    // Para guardar el usuario nuevo
    include '../conexion/conexion.php';

	//error_reporting(1);
	
    if(isset($_POST['register'])){
    
        if(strlen($_POST['rol_nombre']) >= 1){

            $idRol = trim($_POST['rol_id']);
            $nombreRol = trim($_POST['rol_nombre']);
            
            
            $consulta = "   INSERT INTO tipo_usuarios(tipo_usuario) 
                            VALUES ('$nombreRol')";
            $resultado = mysqli_query($mysqli,$consulta);
            if($resultado){
                header('location:tipo_usuarios.php?mensaje=guardado')
                ?>
                <?php                                
            } else {header('location:tipo_usuarios.php?mensaje=falta')
                ?>
                <?php
                } 
            } else {header('location:tipo_usuarios.php?mensaje=nada')
                ?>
                
                <h3 class="bad">ingrese los datos</h3>
                <?php 
        }
    }  

    // Para la lista de cargos
    $query = "  SELECT *
                FROM tipo_usuarios               
                ";
    $usuarios = $mysqli->query($query);

    // Para la lista de cargos
    $query = "  SELECT *
                FROM tipo_usuarios                
                ";
    $usuariosEditar = $mysqli->query($query);

    
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require '../logs/head.php';?>
    </head>
    <body>
        <?php require '../logs/nav-bar.php'; ?>
         <!-- inicio pagina -->       
        <div id="layoutSidenav_content">
            <main>
                <div class="card-header BG-primary mt-1"><b style="color: white;"><i class="fas fa-user-cog" style='font-size:24px'></i>&nbsp;&nbsp;Tipo roles usuarios del sistema</b></div>
                <div class="container-fluid px-2">
                    <div class="container mt-3">

                        <!-- inicio de alertas -->
                            <!-- inicio de falta -->
                                <?php
                                if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'nada') {
                                ?>
                                    <div class="alerta alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Error !</strong> Ingresa todos los datos
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php
                                }
                                ?>
                            <!-- Mensaje guardado -->
                                <?php
                                if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'guardado') {
                                ?>
                                    <div class="alerta alert alert-success alert-dismissible fade show text-center " role="alert">
                                        <h5><i class="far fa-thumbs-up" style="font-size:24px"></i>&nbsp;&nbsp;<strong>Ok !</strong> Usuario creado.</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php
                                }
                                ?>
                            <!-- Mensaje falta -->
                                <?php
                                if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'falta') {
                                ?>
                                    <div class="alerta alert alert-warning alert-dismissible fade show text-center" role="alert">
                                        <h5><strong>Error !</strong> no se pudo editar!</h5>
                                        Intenta de nuevo
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php
                                }
                                ?>
                            <!-- Mensaje error -->
                                <?php
                                if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'error') {
                                ?>
                                    <div class="alerta alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Error !</strong> Vuelve a intentar!
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php
                                }
                                ?>
                            <!-- Mensaje actualizar -->
                                <?php
                                if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'editado') {
                                ?>
                                    <div class="alerta alert alert-primary alert alert-dismissible fade show" role="alert">
                                        <strong>Actualizacion :</strong> Todos los datos fueron actualizados.
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php
                                }
                                ?>
                            <!-- Mensaje eliminar -->
                                <?php
                                if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'eliminado') {
                                ?>
                                    <div class="alerta alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Eliminar :</strong> El registro fue eliminado.
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php
                                }
                                ?>
                            <!-- Mensaje informe diario -->
                                <?php
                                if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'informe') {
                                ?>
                                    <div class="alerta alert alert-success alert alert-dismissible fade show" role="alert">
                                        <strong><h2 class="text-center">INFORME DIARIO C.E Y RECAUDO</h2></strong> <h3 class="text-center">Ha sido generado</h3>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php
                                }
                                ?>
                        <!-- fin alertas -->

                        <div class="row justify-content">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card p-2 "> 

                                    <!--  Modal trigger button  -->
                                        <div class="col col-sm-3 col-md-4">
                                            <button type="button" class="btn btn-outline-primary btn-md mb-2" data-bs-toggle="modal" data-bs-target="#modalId">
                                                <strong>+ Tipo Rol</strong>
                                            </button>
                                        </div>
                                    <!--  Modal trigger button  -->    
                                    
                                    <!-- modal ingreso roles -->
                                        <!-- Modal Body-->
                                        <div class="modal fade" id="modalId" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalTitleId"><i class="fas fa-user-cog" style='font-size:24px'></i>&nbsp;&nbsp;Ingresar Tipo de Usuarios</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="container-fluid">
                                                            <!-- formulario ingresar cargos -->
                                                                <div class="col-md-12">
                                                                    <div class="card border-4 rounded-3">
                                                                        <form id="rol" name="rol" class="row g-0 p-2" action="tipo_usuarios.php" method="POST">
                                                                            <div class="input-group mb-2">                                                                                
                                                                                <input type="hidden" class="form-control" id="rol_id" name="rol_id" placeholder="id" aria-label="cedula" aria-describedby="basic-addon1" required autofocus>
                                                                            </div>
                                                                            <div class="input-group mb-2">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-user-cog"></i>&nbsp;</span>
                                                                                </div>
                                                                                <input type="text" class="form-control" name="rol_nombre" placeholder="Nombre Rol" aria-label="nombre_rol" aria-describedby="basic-addon1" required autofocus>
                                                                            </div>                                                                           
                                                                                
                                                                            <div class="d-grid gap-2">
                                                                                <button type="submit" class="btn btn-secondary btn btn-block" name="register" href="tipo_usuarios.php"><i class="bi bi-plus-lg text-white">&nbsp;GUARDAR</i></button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            <!-- fin formulario ingresar cargos -->
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal Body-->
                                    <!-- modal ingreso roles -->
                                    
                                    <!-- tabla cargos -->
                                        <div class="justify-content-between m-0 col col-10 col-sm-10 col-md-12">
                                            <div>
                                                Roles usuarios:
                                            </div>
                                            <table id="tabla_cargos" class="table table table-sm table-borderless table-hover mt-3 table text-center table align-middle">
                                                <thead>
                                                    <tr>
                                                        <th align="center">ID</th> 
                                                        <th align="center">CARGO</th>
                                                        <th align="center">EDITAR</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        while ($fila = $usuarios->fetch_array()) {
                                                            $id = $fila['id_tipo_usuario'];
                                                            $nombrecargo = $fila['tipo_usuario'];                                                            
                                                    ?>                                                           
                                                        <tr>                                                            
                                                            <td align="center"><?php echo $id; ?></td>
                                                            <td align="center"><?php echo $nombrecargo; ?></td>
                                                            <td align="center"><a data-bs-toggle="modal" data-bs-target="#ModalRol<?php echo $id; ?>"
                                                                                title="Editar" class="btn btn-outline-success btn-xs">
                                                                                <i class="bi bi-pencil-square"></i></a></td>                                                   
                                                        </tr>
                                                    <?php } ?> 
                                                </tbody>
                                                <tfoot>                                                
                                                </tfoot>
                                            </table>
                                        </div>
                                    <!-- tabla cargos -->
                                    
                                    <!-- modal editar roles -->
                                        <!-- Modal -->
                                        <?php
                                                while ($fila = $usuariosEditar->fetch_array()) {
                                                    $idrol = $fila['id_tipo_usuario'];
                                                    $tiporol = $fila['tipo_usuario'];
                                                      ?>
                                        <form id="cargo" name="cargo" class="row g-0 p-2" action="rol_editarproceso.php" method="POST">
                                            <div class="modal fade" id="ModalRol<?php echo $idrol; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-user-cog" style='font-size:24px'></i>&nbsp;Modificar Rol <br></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>    
                                                        <div class="modal-body">
                                                            <div class="mb-1">
                                                            <input type="hidden" name="id_tipo_usuario" id="id_tipo_usuario" value="<?php echo $idrol; ?>" readonly></input>
                                                                <h6>Corregir Rol:</h6>
                                                                <h7 class="mb-1"><?php echo $tiporol; ?><br><br>
                                                                <h6>Por:</h6>
                                                                    
                                                                    <label class="form-label mt-1"> </label>
                                                                        <div class="input-group mb-1 mt-2">
                                                                            <div class="input-group-prepend">
                                                                                <label class="input-group-text" for="inputGroupSelect01"><i class='fas fa-user-cog'></i>&nbsp;</label>
                                                                            </div>
                                                                            <input type="text" name="tipo_usuario" id="tipo_usuario" value="<?php echo $tiporol; ?>"></input>
                                                                        </div>
                                                            </div>                                           
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                            <button type="submit" value="editar" class="btn btn-primary btn btn-block">Guardar cambios</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>    
                                        <?php } ;  ?>                                    
                                    <!-- modal editar empleados -->
                                    
                                                            
                                </div>                                            
                            </div>
                        </div>
                    </div>
                </div>   
            </main>
            <!-- footer -->
                <?php require '../logs/nav-footer.php'; ?>
            <!-- fin footer -->
        </div> 
        <script src="../js/mensajes.js"></script>
       
    </body>
</html>
                            
                            
