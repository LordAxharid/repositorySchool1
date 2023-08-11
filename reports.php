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
                        <h1 class="page-head-line">Reportes</h1>

                    </div>
                </div>
                <!-- /. ROW  -->
                <div class="row">
                <?php 
        if(isset($_GET['action']) && @$_GET['action']=='per-course') {
        ?>
         <link href="css/datatable/datatable.css" rel="stylesheet" />
         <div class="panel panel-default">
                         <div class="panel-heading">
                             Generar reportes
                         </div>
                         <div class="panel-body">
                             <div class="table-sorting table-responsive">
                                 <table class="table table-striped table-bordered table-hover" id="tSortable22">
                                     <thead>
                                         <tr>
                                             <th>#</th>
                                             <th>Codigo</th>
                                             <th>Tipo</th>
                                             <th>Nombre del Producto</th>
                                             <th>Egreso Automático </th>
                                             <th>Egreso Manual</th>
                                             <th>Saldo de Productos en Depósito</th>
                                             <th>Generar reporte</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                     <?php
                                     $sql = "select * from students where delete_status='0'";
                                     $q = $conn->query($sql);
                                     $i=1;
                                     while($r = $q->fetch_assoc())
                                     {
                                     echo '<tr>
                                             <td>'.$i.'</td>
                                             <td>'.$r['first_name'].'</td>
                                             <td>'.$r['last_name'].'</td>
                                             <td>'.$r['identity_card_number'].'</td>
                                             <td>'.$r['phone_number'].'</td>
                                             <td>'.$r['email'].'</td>
                                             <td>'.$r['city'].'</td>
                                             <td><a href="inventory.php?action=edit&id='.$r['student_id'].'" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-edit"></span></a></td>
                                         </tr>';
                                         $i++;
                                     }
                                     ?>
                                     
                                         
                                         
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>
        <?php 
        }
        ?>

                <?php 
                    if (!isset($_GET['action'])) {
                        # code...
                ?>
				  <div class="col-md-4">
                        <div class="main-box mb-pink">
                            <a href="reports.php?action=per-course">
                                <i class="fa fa-users fa-5x"></i>
                                <h5>Informe por curso</h5>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="main-box mb-yellow">
                            <a href="reports.php?action=general-areas">
                                <i class="fa fa-users fa-5x"></i>
                                <h5>Informe de resultados generales por area</h5>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="main-box mb-blue">
                            <a href="reports.php?action=general-dates">
                                <i class="fa fa-book fa-5x"></i>
                                <h5>Informe de resultados generales por fechas</h5>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="main-box mb-blue">
                            <a href="reports.php?action=students">
                                <i class="fa fa-calendar fa-5x"></i>
                                <h5>Informe por Estudiante</h5>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="main-box mb-red">
                            <a href="reports.php?action=professor">
                                <i class="fa fa-usd fa-5x"></i>
                                <h5>Informe por profesor</h5>
                            </a>
                        </div>
                    </div>
                </div>
                <?php 
                    }
                ?>
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
