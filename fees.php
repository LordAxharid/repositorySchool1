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
$date='';

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
						<form action="fees.php" method="post" id="signupForm1" class="form-horizontal">
                        <div class="panel-body">
						<fieldset class="scheduler-border" >
						 <legend  class="scheduler-border">Agregar ingreso:</legend>
						<div class="form-group">
								<label class="col-sm-3 control-label" for="Old">Fecha* </label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="date" name="date" value="<?php echo $date;?>"  />
								</div>
							</div>
	
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="course_id">Curso*</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="course_id" name="course_id">
                                        <!-- Options will be loaded dynamically using JavaScript -->
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="rubro_select">Rubro*</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="rubro_select" name="rubro_select">
                                        <!-- Options will be loaded dynamically using JavaScript -->
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="item_select">Ítem*</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="item_select" name="item_select">
                                        <!-- Options will be loaded dynamically using JavaScript -->
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="subitem_select">Subítem*</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="subitem_select" name="subitem_select">
                                        <!-- Options will be loaded dynamically using JavaScript -->
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="detail">Detalle*</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="detail" name="detail" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="amount">Monto*</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="amount" name="amount" />
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

    <script>

$(document).ready(function () {
    // Cargar opciones del campo Curso
    $.ajax({
        url: 'fees.php?action=get_courses',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            var courseSelect = $('#course_id');
            $.each(data, function (index, course) {
                courseSelect.append($('<option>', {
                    value: course.course_id,
                    text: course.course_name
                }));
            });
        }
    });

    // Cargar opciones del campo Rubro
    $.ajax({
        url: 'fees.php?action=get_rubros',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            var rubroSelect = $('#rubro_select');
            $.each(data, function (index, rubro) {
                rubroSelect.append($('<option>', {
                    value: rubro.rubro_id,
                    text: rubro.rubro_name
                }));
            });
        }
    });

})

 // Cargar opciones del campo Ítem
 $('#rubro_select').change(function () {
        var rubro_id = $(this).val();
        
        $.ajax({
            url: 'fees.php?action=get_items',
            method: 'GET',
            dataType: 'json',
            data: { rubro_id: rubro_id },
            success: function (data) {
                var itemSelect = $('#item_select');
                itemSelect.empty();
                $.each(data, function (index, item) {
                    itemSelect.append($('<option>', {
                        value: item.item_id,
                        text: item.item_name
                    }));
                });
            }
        });
    });

    // Cargar opciones del campo Subítem
    $('#item_select').change(function () {
        var item_id = $(this).val();
        
        $.ajax({
            url: 'fees.php?action=get_subitems',
            method: 'GET',
            dataType: 'json',
            data: { item_id: item_id },
            success: function (data) {
                var subitemSelect = $('#subitem_select');
                subitemSelect.empty();
                $.each(data, function (index, subitem) {
                    subitemSelect.append($('<option>', {
                        value: subitem.subitem_id,
                        text: subitem.subitem_name
                    }));
                });
            }
        });
    });


    </script>

</body>
</html>
