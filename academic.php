<?php
include("php/dbconnect.php");
include("php/checklogin.php");
$errormsg = '';
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


if(isset($_POST['save']))
{

$fname = mysqli_real_escape_string($conn,$_POST['fname']);
$lname = mysqli_real_escape_string($conn,$_POST['lname']);
$joindate = mysqli_real_escape_string($conn,$_POST['joindate']);

$contact = mysqli_real_escape_string($conn,$_POST['contact']);
$about = mysqli_real_escape_string($conn,$_POST['about']);
$emailid = mysqli_real_escape_string($conn,$_POST['emailid']);
$branch = mysqli_real_escape_string($conn,$_POST['branch']);


 if($_POST['action']=="add")
 {
 $remark = mysqli_real_escape_string($conn,$_POST['remark']);
 $fees = mysqli_real_escape_string($conn,$_POST['fees']);
 $advancefees = mysqli_real_escape_string($conn,$_POST['advancefees']);
 $balance = $fees-$advancefees;
 
  $q1 = $conn->query("INSERT INTO student (fname,lname,joindate,contact,about,emailid,branch,balance,fees) VALUES ('$fname','$lname','$joindate','$contact','$about','$emailid','$branch','$balance','$fees')") ;
  
  $sid = $conn->insert_id;
  
 $conn->query("INSERT INTO  fees_transaction (stdid,paid,submitdate,transcation_remark) VALUES ('$sid','$advancefees','$joindate','$remark')") ;
    
   echo '<script type="text/javascript">window.location="student.php?act=1";</script>';
 
 }else
  if($_POST['action']=="update")
 {
 $id = mysqli_real_escape_string($conn,$_POST['id']);	
   $sql = $conn->query("UPDATE  students  SET  branch  = '$branch', address  = '$address', detail  = '$detail'  WHERE  id  = '$id'");
   echo '<script type="text/javascript">window.location="student.php?act=2";</script>';
 }



}




if(isset($_GET['action']) && $_GET['action']=="delete"){

$conn->query("UPDATE students set delete_status = '1'  WHERE student_id='".$_GET['id']."'");	
header("location: student.php?act=3");

}




$action = "add";
if(isset($_GET['action']) && $_GET['action']=="edit" ){
$id = isset($_GET['id'])?mysqli_real_escape_string($conn,$_GET['id']):'';

$sqlEdit = $conn->query("SELECT * FROM students WHERE student_id='".$id."'");
if($sqlEdit->num_rows)
{
$rowsEdit = $sqlEdit->fetch_assoc();
extract($rowsEdit);
$action = "update";
}else
{
$_GET['action']="";
}

}


if(isset($_REQUEST['act']) && @$_REQUEST['act']=="1")
{
$errormsg = "<div class='alert alert-success'> <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Excelente!</strong> Estudiante Agregado Exitósamente</div>";
}else if(isset($_REQUEST['act']) && @$_REQUEST['act']=="2")
{
$errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> <strong>Excelente!</strong> Estudiante Editado Exitósamente</div>";
}
else if(isset($_REQUEST['act']) && @$_REQUEST['act']=="3")
{
$errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Excelente!</strong> Estudiante Eliminado Exitósamente</div>";
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sistema de Pago Escolar</title>

	<?php
	include("layout/head-links.php");
	include("layout/footer-links.php");
	?>
	
</head>
<?php
include("php/header.php");
?>
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-head-line">Cursos a cargo  
						<?php
						echo (isset($_GET['action']) && @$_GET['action']=="add" || @$_GET['action']=="edit")?
						' <a href="student.php" class="btn btn-primary btn-sm pull-right">Volver <i class="glyphicon glyphicon-arrow-right"></i></a>':'';
						?>
						</h1>
                     
<?php

echo $errormsg;
?>
                    </div>
                </div>

				<?php 
		 if(isset($_GET['action']) && @$_GET['action']=="edit-asistence")
		 {
		?>
		
			<script type="text/javascript" src="js/validation/jquery.validate.min.js"></script>
                <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
               <div class="panel panel-primary">
                        <div class="panel-heading">
                           <?php echo ($action=="add")? "Asistencia Estudiantes": "Asistencia Estudiantes"; ?>
                        </div>
						<form action="student.php" method="post" id="signupForm1" class="form-horizontal">
                        <div class="panel-body">
						<fieldset class="scheduler-border" >
						 <legend  class="scheduler-border">Información asistencia:</legend>
						<div class="form-group">
								<label class="col-sm-3 control-label" for="Old">Curso* </label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="fname" name="fname" value="<?php echo $fname;?>"  />
								</div>
							</div>
	
						<div class="form-group">
							<label class="col-sm-3 control-label" for="Old">Fecha de clase* </label>
							<div class="col-sm-9">
								<input type="date" class="form-control" id="date_class" name="lname" value="<?php echo $lname;?>"  />
							</div>
						</div>

						<div class="panel-body">
                            <div class="table-sorting table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="tSortable22">
							<thead>
								<tr>
									<th>Presente</th>
									<th>Nombre del Estudiante</th>
									<th>Número de Identificación</th>
								</tr>
							</thead>
							<tbody>
									<?php
									$idCourse = $_GET['id'];

									$sql = "select * from enrollments where course_name=".$idCourse."";
									$q = $conn->query($sql);
									$i=1;
									while($r = $q->fetch_assoc())
									{
									$sqlStudent = "select * from students where student_id=".$r['student_id']."";
									$qS = $conn->query($sqlStudent);
									$rS = $qS->fetch_assoc();
									echo '<tr>
											<td><input type="checkbox" name="attendance[]" value="' . $r['student_id'] . '"></td>
                                            <td>'.$rS['first_name'].'</td>
											<td>'.$rS['identity_card_number'].'</td>	
										</tr>';
										$i++;
									}
									?>
									
                            </tbody>
						</table>	
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

			<script>

$(document).ready(function () {
    // Cargar opciones del campo Modalidad
    $.ajax({
        url: 'obtener_modalidades.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            var modalidadSelect = $('#modalidad_select');
            $.each(data, function (index, option) {
                modalidadSelect.append($('<option>', {
                    value: option.value,
                    text: option.text
                }));
            });
        }
    });

    // Cargar opciones del campo Tipo
    $.ajax({
        url: 'obtener_tipos.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            var tipoSelect = $('#tipo_select');
            $.each(data, function (index, option) {
                tipoSelect.append($('<option>', {
                    value: option.value,
                    text: option.text
                }));
            });
        }
    });

    // Cargar opciones del campo Área
    $.ajax({
        url: 'obtener_areas.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            var areaSelect = $('#area_select');
            $.each(data, function (index, option) {
                areaSelect.append($('<option>', {
                    value: option.value,
                    text: option.text
                }));
            });
        }
    });

    // Cargar opciones del campo Nombre del Curso
    $('#area_select').change(function () {
        var selectedArea = $(this).val();
        $.ajax({
            url: 'obtener_cursos_por_area.php',
            method: 'GET',
            data: { area: selectedArea },
            dataType: 'json',
            success: function (data) {
                var cursoSelect = $('#curso_select');
                cursoSelect.empty();
                cursoSelect.append($('<option>', {
                    value: '',
                    text: 'Selecciona un curso'
                }));
                $.each(data, function (index, option) {
                    cursoSelect.append($('<option>', {
                        value: option.value,
                        text: option.text
                    }));
                });
            }
        });
    });
});

			</script>


			   
		<?php
		}
		?>


				
<!-- Calificaciones -->
<?php 
		 if(isset($_GET['action']) && @$_GET['action']=="enrollments")
		 {
		?>
			<div class="row">
            <div class="col-sm-10 col-sm-offset-1">
               <div class="panel panel-primary">
                        <div class="panel-heading">
                           <?php echo ($action=="add")? "Agregar Notas": "Asistencia Estudiantes"; ?>
                        </div>
						<form action="student.php" method="post" id="signupForm1" class="form-horizontal">
                        <div class="panel-body">
						<fieldset class="scheduler-border" >
						 <legend  class="scheduler-border">Información asistencia:</legend>
						<div class="form-group">
								<label class="col-sm-3 control-label" for="Old">Curso* </label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="fname" name="fname" value="<?php echo $fname;?>"  />
								</div>
							</div>
	
						<div class="form-group">
							<label class="col-sm-3 control-label" for="Old">Fecha de clase* </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="lname" name="lname" value="<?php echo $lname;?>"  />
							</div>
						</div>

						<div class="panel-body">
                            <div class="table-sorting table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="tSortable22">
							<thead>
								<tr>
									<th>Nota</th>
									<th>Nombre del Estudiante</th>
									<th>Número de Identificación</th>
									<th>Notas</th>
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
											<td><input type="number" name="attendance[]"></td>
                                            <td>'.$r['first_name'].'</td>
											<td>'.$r['identity_card_number'].'</td>
											<td>
											<a href="academic.php?action=edit-score&id-course='.$_GET['id'].'&id='.$r['student_id'].'" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-edit"></span></a>
											</td>	
										</tr>';
										$i++;
									}
									?>
									
                                        
                                        
                                    </tbody>
						</table>	
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
		 if(isset($_GET['action']) && @$_GET['action']=="edit-score")
		 {
		?>
			  <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
               <div class="panel panel-primary">
                        <div class="panel-heading">
                           <?php echo ($action=="add")? "Notas Estudiante": "Asistencia Estudiantes"; ?>
                        </div>
						<form action="student.php" method="post" id="signupForm1" class="form-horizontal">
                        <div class="panel-body">
						<fieldset class="scheduler-border" >
						 <legend  class="scheduler-border">Información notas:</legend>

						<div class="panel-body">
                            <div class="table-sorting table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="tSortable22">
							<thead>
								<tr>
									<th>Notas</th>
									<th>Curso</th>
									<th>Fecha de clase</th>
									<th>Acciones</th>
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
											<td><input type="number" value="1"></input></td>
                                            <td>'.$r['first_name'].'</td>
											<td>'.$r['identity_card_number'].'</td>
											<td>
											<a onclick="return confirm(\'Deseas realmente eliminar este registro, este proceso es irreversible\');" href="student.php?action=delete&id='.$r['student_id'].'" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></a> 
											</td>	
										</tr>';
										$i++;
									}
									?>
									
                                        
                                        
                                    </tbody>
						</table>	
		 				 </div>
						</div>
						

						 </fieldset>
						
						<div class="form-group">
								<div class="col-sm-8 col-sm-offset-2">
								<input type="hidden" name="id" value="<?php echo $id;?>">
								<input type="hidden" name="action" value="<?php echo $action;?>">
								
									<button type="submit" name="save" class="btn btn-primary">Actualizar </button>
								 
								   
								   
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
		 ?>
			 <link href="css/datatable/datatable.css" rel="stylesheet" />
		 
		
		 
		 
		 <div class="panel panel-default">
						 <div class="panel-heading">
							 Administrar Información de los Cursos  
						 </div>
						 <div class="panel-body">
							 <div class="table-sorting table-responsive">
								 <table class="table table-striped table-bordered table-hover" id="tSortable22">
									 <thead>
										 <tr>
											 <th>#</th>
											 <th>Nombre curso</th>
											 <th>Codigo del curso</th>
											 <th>Fecha de inicio </th>
											 <th>Duracion</th>
											 <th>Horarios</th>
											 <th>Asistencia</th>
											 <th>Notas</th>
										 </tr>
									 </thead>
									 <tbody>
									 <?php
									 $sql = "select * from courses where delete_status='0'";
									 $q = $conn->query($sql);
									 $i=1;
									 while($r = $q->fetch_assoc())
									 {
										$sqlNameCourse = "select * from name_courses where delete_status='0' AND id = ".$r['course_name']."";
										$qNC = $conn->query($sqlNameCourse);
										$rNC = $qNC->fetch_assoc();
									 
									 echo '<tr>
											 <td>'.$i.'</td>
											 <td>'.$rNC['name'].'</td>
											 <td>'.$r['course_code'].'</td>
											 <td>'.$r['start_date'].'</td>
											 <td>'.$r['duration'].'</td>
											 <td>'.$r['schedule'].'</td>
											 <td><a href="academic.php?action=edit-asistence&id='.$r['course_id'].'" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-edit"></span></a></td>	
											 <td><a href="academic.php?action=enrollments&id='.$r['course_id'].'" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-barcode"></span></a></td>	
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
		    
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->

    <div id="footer-sec">
	Para más desarrollos gratuitos, accede a <a href="https://www.configuroweb.com/" target="_blank">ConfiguroWeb</a>
	</div>

	<!-- BOOTSTRAP SCRIPTS -->
    <script src="js/bootstrap.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="js/jquery.metisMenu.js"></script>
       <!-- CUSTOM SCRIPTS -->
    <script src="js/custom1.js"></script>
    
</body>
</html>
