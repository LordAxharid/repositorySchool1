<?php
include("php/dbconnect.php");
include("php/checklogin.php");

$action = "add";
$id="";
$emailid='';
$fname='';
$lname='';
$joindate = '';
$remark='';
$contact='';
$balance = 0;
$fees='';
$about = '';
$branch='';

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
                        <h1 class="page-head-line">Ingreso</h1>

                    </div>
                </div>
                <!-- /. ROW  -->
                <div class="row">
                <?php 
        if(isset($_GET['action']) && @$_GET['action']=='deposit') {
        ?>
        
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
               <div class="panel panel-primary">
						<form action="student.php" method="post" id="signupForm1" class="form-horizontal">
                        <div class="panel-body">
						<fieldset class="scheduler-border" >
						 <legend  class="scheduler-border">Agregar ingreso:</legend>
						<div class="form-group">
								<label class="col-sm-3 control-label" for="Old">Fecha* </label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="fname" name="fname" value="<?php echo $fname;?>"  />
								</div>
							</div>
	
						<div class="form-group">
							<label class="col-sm-3 control-label" for="Old">Curso* </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="lname" name="lname" value="<?php echo $lname;?>"  />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="Old">Rubro* </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="lname" name="lname" value="<?php echo $lname;?>"  />
							</div>
						</div>	

						 <div class="form-group">
							<label class="col-sm-3 control-label" for="Old">Ítem * </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="lname" name="lname" value="<?php echo $lname;?>"  />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="Old">Subítem * </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="lname" name="lname" value="<?php echo $lname;?>"  />
							</div>
						</div>	

            <div class="form-group">
							<label class="col-sm-3 control-label" for="Old">Detalle * </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="lname" name="lname" value="<?php echo $lname;?>"  />
							</div>
						</div>	

            <div class="form-group">
							<label class="col-sm-3 control-label" for="Old">Monto * </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="lname" name="lname" value="<?php echo $lname;?>"  />
							</div>
						</div>	

						 </fieldset>
						
						<div class="form-group">
								<div class="col-sm-8 col-sm-offset-2">
								<input type="hidden" name="id" value="<?php echo $id;?>">
								<input type="hidden" name="action" value="<?php echo $action;?>">
									<button type="submit" name="save" class="btn btn-primary">Guardar </button>
								</div>
							</div>
                         
                           
                           
                         
                           
                         </div>
							</form>
							
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
                            <a href="fees.php?action=deposit">
                                <i class="fa fa-users fa-5x"></i>
                                <h5>Agregar ingresos</h5>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="main-box mb-yellow">
                            <a href="reports.php?action=general-areas">
                                <i class="fa fa-users fa-5x"></i>
                                <h5>Agregar gastos</h5>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="main-box mb-blue">
                            <a href="reports.php?action=general-dates">
                                <i class="fa fa-book fa-5x"></i>
                                <h5>Ver balance general</h5>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="main-box mb-blue">
                            <a href="reports.php?action=students">
                                <i class="fa fa-calendar fa-5x"></i>
                                <h5>Informes</h5>
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
