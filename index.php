<?php
include("php/dbconnect.php");
include("php/checklogin.php");


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sistema de Pago Escolar</title>

    
    <?php  
    include("layout/head-links.php");
    ?>


</head>
<?php
include("php/header.php");
?>
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-head-line">Panel de Control</h1>
                        <h2 style="text-align:center;"> Has accedido al <strong>Sistema Definitivo de Pago Escolar</strong> </h2>

                    </div>
                </div>
                <!-- /. ROW  -->
                <div class="row">
				
				  <div class="col-md-4">
                        <div class="main-box mb-pink">
                            <a href="student.php">
                                <i class="fa fa-users fa-5x"></i>
                                <h5>Estudiantes</h5>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="main-box mb-yellow">
                            <a href="student.php">
                                <i class="fa fa-users fa-5x"></i>
                                <h5>Profesores</h5>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="main-box mb-blue">
                            <a href="student.php">
                                <i class="fa fa-book fa-5x"></i>
                                <h5>Cursos</h5>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="main-box mb-blue">
                            <a href="student.php">
                                <i class="fa fa-calendar fa-5x"></i>
                                <h5>Inscripción a Cursos</h5>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="main-box mb-red">
                            <a href="student.php">
                                <i class="fa fa-usd fa-5x"></i>
                                <h5>Contable</h5>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="main-box mb-pink">
                            <a href="student.php">
                                <i class="fa fa-file-text fa-5x"></i>
                                <h5>Académico</h5>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="main-box mb-dull">
                            <a href="student.php">
                                <i class="fa fa-archive fa-5x"></i>
                                <h5>Inventario</h5>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="main-box mb-blue">
                            <a href="student.php">
                                <i class="fa fa-calendar fa-5x"></i>
                                <h5>Reservas</h5>
                            </a>
                        </div>
                    </div>
                   
					
                    <div class="col-md-4">
                        <div class="main-box mb-dull">
                            <a href="fees.php">
                                <i class="fa fa-usd fa-5x"></i>
                                <h5>Recibir Pagos</h5>
                            </a>
                        </div>
                    </div>
					
					
					 <div class="col-md-4">
                        <div class="main-box mb-red">
                            <a href="report.php">
                                <i class="fa fa-file-text fa-5x"></i>
                                <h5>Reportes</h5>
                            </a>
                        </div>
                    </div>
                  

                </div>
                <!-- /. ROW  -->

            
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->

    <div id="footer-sec">
    Para más desarrollos gratuitos, accede a <a href="https://www.configuroweb.com/" target="_blank">ConfiguroWeb</a>
    </div>

    
    <?php  
    
    include("layout/footer-links.php");

    ?>

</body>
</html>
