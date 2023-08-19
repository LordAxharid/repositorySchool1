<?php
include("php/dbconnect.php");
include("php/checklogin.php");
$errormsg = '';
$action = "add";

$id="";
$course_code="";
$cost="";
$duration="";
$start_date="";
$days="";
$schedule="";


if(isset($_POST['save']))
{
	$mode = mysqli_real_escape_string($conn, $_POST['mode']);
	$type = mysqli_real_escape_string($conn, $_POST['type']);
	$area = mysqli_real_escape_string($conn, $_POST['area']);
	$course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
	$course_code = mysqli_real_escape_string($conn, $_POST['course_code']);
	$professor = mysqli_real_escape_string($conn, $_POST['professor']);
	$cost = mysqli_real_escape_string($conn, $_POST['cost']);
	$duration = mysqli_real_escape_string($conn, $_POST['duration']);
	$start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
	$days = mysqli_real_escape_string($conn, $_POST['days']);
	$schedule = mysqli_real_escape_string($conn, $_POST['schedule']);

	if($_POST['action']=="add")
	{
		// Prepare SQL query
		$sql = "INSERT INTO courses (modality, type, area, course_name, course_code, teacher_id, cost, duration, start_date, days, schedule)
		VALUES ('$mode', '$type', '$area', '$course_name', '$course_code', '$professor', '$cost', '$duration', '$start_date', '$days', '$schedule')";

		// Execute the SQL query
		if ($conn->query($sql) === TRUE) {
		echo '<script type="text/javascript">window.location="courses.php?act=1";</script>';
		} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
		}
	
	}else
	if($_POST['action']=="update")
	{
		$id = mysqli_real_escape_string($conn,$_POST['id']);	
		$sql = "UPDATE  courses  SET  modality  = '$mode', type  = '$type', area = '$area', course_name  = '$course_name', course_code  = '$course_code', teacher_id  = '$professor', cost  = '$cost', duration  = '$duration', start_date  = '$start_date', days  = '$days', schedule  = '$schedule' WHERE  course_id  = '$id'";

					if ($conn->query($sql) === TRUE) {
						// Éxito
						echo '<script type="text/javascript">window.location="courses.php?act=2";</script>';
					} else {
						// Error
						echo "Error al actualizar el registro: " . $conn->error;
					}
					
	}
}

if(isset($_GET['action']) && $_GET['action']=="delete"){

	$conn->query("UPDATE courses set delete_status = '1'  WHERE course_id='".$_GET['id']."'");	
	header("location: courses.php?act=3");

}


$action = "add";
if(isset($_GET['action']) && $_GET['action']=="edit" ){

	$queryArea = "SELECT id, name FROM areas";
	$resultArea = mysqli_query($conn, $queryArea);

	$queryCourses = "SELECT id, name FROM name_courses";
	$resultCourses = mysqli_query($conn, $queryCourses);

	$queryTeachers = "SELECT * FROM teachers";
	$resultTeachers = mysqli_query($conn, $queryTeachers);

	$id = isset($_GET['id'])?mysqli_real_escape_string($conn,$_GET['id']):'';

	$sqlEdit = $conn->query("SELECT * FROM courses WHERE course_id='".$id."'");
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

if (isset($_GET['action']) && $_GET['action']=="add") {
	$queryArea = "SELECT id, name FROM areas";
	$resultArea = mysqli_query($conn, $queryArea);

	$queryCourses = "SELECT id, name FROM name_courses";
	$resultCourses = mysqli_query($conn, $queryCourses);

	$queryTeachers = "SELECT * FROM teachers";
	$resultTeachers = mysqli_query($conn, $queryTeachers);
}


if(isset($_REQUEST['act']) && @$_REQUEST['act']=="1")
{
$errormsg = "<div class='alert alert-success'> <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Excelente!</strong> Curso Agregado Exitósamente</div>";
}else if(isset($_REQUEST['act']) && @$_REQUEST['act']=="2")
{
$errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> <strong>Excelente!</strong> Curso Editado Exitósamente</div>";
}
else if(isset($_REQUEST['act']) && @$_REQUEST['act']=="3")
{
$errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Excelente!</strong> Curso Eliminado Exitósamente</div>";
}
else if(isset($_REQUEST['act']) && @$_REQUEST['act']=="4")
{
$errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Excelente!</strong> Area Agregada Exitósamente</div>";
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
                        <h1 class="page-head-line">Cursos  
						<?php
						echo (isset($_GET['action']) && @$_GET['action']=="add" || @$_GET['action']=="edit")?
						' <a href="courses.php" class="btn btn-primary btn-sm pull-right">Volver <i class="glyphicon glyphicon-arrow-right"></i></a>':'<a href="areas.php" class="btn btn-success btn-sm pull-right"><i class="glyphicon glyphicon-plus"></i>Ver Areas </a> <a href="courses.php?action=add" class="btn btn-primary btn-sm pull-right"><i class="glyphicon glyphicon-plus"></i> Agregar Cursos </a> <a href="name-courses.php" class="btn btn-success btn-sm pull-right"><i class="glyphicon glyphicon-plus"></i> Ver Nombres Cursos </a>';
						?>
						</h1>
                     
<?php

echo $errormsg;
?>
                    </div>
                </div>				
				
        <?php 
		 if(isset($_GET['action']) && @$_GET['action']=="add" || @$_GET['action']=="edit")
		 {
		?>
		
			<script type="text/javascript" src="js/validation/jquery.validate.min.js"></script>
                <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
               <div class="panel panel-primary">
                        <div class="panel-heading">
                           <?php echo ($action=="add")? "Agregar Curso": "Editar Curso"; ?>
                        </div>
						<form action="courses.php" method="post" id="signupForm1" class="form-horizontal">
                        <div class="panel-body">
						<fieldset class="scheduler-border" >
						 <legend  class="scheduler-border">Información Del Curso:</legend>
						 <div class="form-group">
						<label class="col-sm-3 control-label" for="mode">Modalidad*</label>
						<div class="col-sm-9">
							<select class="form-control" id="mode" name="mode">
							<?php  if(isset($modality)){ ?>
									<option selected="selected" value="<?php echo $modality; ?>"><?php echo $modality; ?></option>
								<?php } ?>
								<option value="Virtual">Virtual</option>
								<option value="Presencial">Presencial</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" for="type">Tipo*</label>
						<div class="col-sm-9">
							<select class="form-control" id="type" name="type">
								<?php  if(isset($type)){ ?>
									<option selected="selected" value="<?php echo $type; ?>"><?php if($type == 'Training'){ ?> Capacitación <?php }else{ ?> Talleres Cortos <?php }; ?></option>
								<?php } ?>
								<option value="Training">Capacitación</option>
								<option value="Short Courses">Talleres Cortos</option>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label" for="Area">Área*</label>
						<div class="col-sm-9">
							<!-- Aquí debes generar dinámicamente las opciones según las áreas disponibles -->
							<select class="form-control" id="area" name="area">
							<?php while ($row = mysqli_fetch_assoc($resultArea)) { ?>
								<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
							<?php } ?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" for="course_name">Nombre del Curso*</label>
						<div class="col-sm-9">
							<!-- Aquí debes generar dinámicamente las opciones según los nombres de los cursos disponibles -->
							<select class="form-control" id="course_name" name="course_name">
							<?php while ($rowCourses = mysqli_fetch_assoc($resultCourses)) { ?>
								<option value="<?php echo $rowCourses['id']; ?>"><?php echo $rowCourses['name']; ?></option>
							<?php } ?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" for="course_code">Código del Curso*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="course_code" name="course_code" value="<?php echo $course_code; ?>" readonly/>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" for="Professor">Profesor*</label>
						<div class="col-sm-9">
							<!-- Aquí debes generar dinámicamente las opciones según los profesores disponibles -->
							<select class="form-control" id="professor" name="professor">
							<?php while ($row = mysqli_fetch_assoc($resultTeachers)) { ?>
								<option value="<?php echo $row['teacher_id']; ?>">
									<?php echo $row['first_name'] . ' ' . $row['last_name']; ?>
								</option>
							<?php } ?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" for="cost">Costo*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="cost" name="cost" value="<?php echo $cost; ?>" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" for="duration">Duración*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="duration" name="duration" value="<?php echo $duration; ?>" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" for="FechaInicio">Fecha de inicio*</label>
						<div class="col-sm-9">
							<input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $start_date; ?>" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" for="days">Días*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="days" name="days" value="<?php echo $days; ?>" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" for="schedule">Horario*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="schedule" name="schedule" value="<?php echo $schedule; ?>" />
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
               

			   
			   
		<script type="text/javascript">
			document.addEventListener("DOMContentLoaded", function() {
    // Add an event listener to the start date input
    document.getElementById("start_date").addEventListener("change", updateCourseCode);
	document.getElementById("course_name").addEventListener("change", updateCourseCode);
});

function updateCourseCode() {
    var courseNameSelect = document.getElementById("course_name"); // Get the select element
    var selectedCourseName = courseNameSelect.options[courseNameSelect.selectedIndex].text; // Get the selected course name
	console.log(selectedCourseName)
    var startDate = document.getElementById("start_date").value; // Get the start date
    // Update the input field with the generated course code
    document.getElementById("course_code").value = selectedCourseName+"-"+startDate;
}	

		$( document ).ready( function () {			
			
		$( "#joindate" ).datepicker({
			dateFormat:"yy-mm-dd",
			changeMonth: true,
			changeYear: true,
			yearRange: "1970:<?php echo date('Y');?>"
			});	
		

		
		if($("#signupForm1").length > 0)
         {
		 
		 <?php if($action=='add')
		 {
		 ?>
		 
			$( "#signupForm1" ).validate( {
				rules: {
					fname: "required",
					lname: "required",
					joindate: "required",
					emailid: "email",
					branch: "required",
					
					
					contact: {
						required: true,
						digits: true
					},
					
					fees: {
						required: true,
						digits: true
					},
					
					
					advancefees: {
						required: true,
						digits: true
					},
				
					
				},
			<?php
			}else
			{
			?>
			
			$( "#signupForm1" ).validate( {
				rules: {
					sname: "required",
					joindate: "required",
					emailid: "email",
					branch: "required",
					
					
					contact: {
						required: true,
						digits: true
					}
					
				},
			
			
			
			<?php
			}
			?>
				
				errorElement: "em",
				errorPlacement: function ( error, element ) {
					// Add the `help-block` class to the error element
					error.addClass( "help-block" );

					// Add `has-feedback` class to the parent div.form-group
					// in order to add icons to inputs
					element.parents( ".col-sm-10" ).addClass( "has-feedback" );

					if ( element.prop( "type" ) === "checkbox" ) {
						error.insertAfter( element.parent( "label" ) );
					} else {
						error.insertAfter( element );
					}

					// Add the span element, if doesn't exists, and apply the icon classes to it.
					if ( !element.next( "span" )[ 0 ] ) {
						$( "<span class='glyphicon glyphicon-remove form-control-feedback'></span>" ).insertAfter( element );
					}
				},
				success: function ( label, element ) {
					// Add the span element, if doesn't exists, and apply the icon classes to it.
					if ( !$( element ).next( "span" )[ 0 ] ) {
						$( "<span class='glyphicon glyphicon-ok form-control-feedback'></span>" ).insertAfter( $( element ) );
					}
				},
				highlight: function ( element, errorClass, validClass ) {
					$( element ).parents( ".col-sm-10" ).addClass( "has-error" ).removeClass( "has-success" );
					$( element ).next( "span" ).addClass( "glyphicon-remove" ).removeClass( "glyphicon-ok" );
				},
				unhighlight: function ( element, errorClass, validClass ) {
					$( element ).parents( ".col-sm-10" ).addClass( "has-success" ).removeClass( "has-error" );
					$( element ).next( "span" ).addClass( "glyphicon-ok" ).removeClass( "glyphicon-remove" );
				}
			} );
			
			}
			
		} );
		
		
		
		$("#fees").keyup( function(){
		$("#advancefees").val("");
		$("#balance").val(0);
		var fee = $.trim($(this).val());
		if( fee!='' && !isNaN(fee))
		{
		$("#advancefees").removeAttr("readonly");
		$("#balance").val(fee);
		$('#advancefees').rules("add", {
            max: parseInt(fee)
        });
		
		}
		else{
		$("#advancefees").attr("readonly","readonly");
		}
		
		});
		
		
		
		
		$("#advancefees").keyup( function(){
		
		var advancefees = parseInt($.trim($(this).val()));
		var totalfee = parseInt($("#fees").val());
		if( advancefees!='' && !isNaN(advancefees) && advancefees<=totalfee)
		{
		var balance = totalfee-advancefees;
		$("#balance").val(balance);
		
		}
		else{
		$("#balance").val(totalfee);
		}
		
		});
		
		
	</script>


			   
		<?php
		}elseif (!isset($_GET['action'])) {
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
                                            <th>Modalidad</th>
											<th>Tipo</th>
                                            <th>Área</th>
                                            <th>Nombre del Curso </th>
											<th>Código del Curso</th>
											<th>Profesor</th>
											<th>Costo</th>
											<th>Duración</th>
											<th>Fecha de inicio</th>
											<th>Días</th>
											<th>Horario</th>
											<th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
									$sql = "select * from courses where delete_status='0'";
									$q = $conn->query($sql);
									$i=1;
									while($r = $q->fetch_assoc())
									{
									$sqlTeacher = "select * from teachers where teacher_id='".$r['teacher_id']."'";
									$qt = $conn->query($sqlTeacher);
									$rt = $qt->fetch_assoc();

									$sqlArea = "select * from areas where id='".$r['area']."'";
									$qa = $conn->query($sqlArea);
									$ra = $qa->fetch_assoc();

									$sqlCourses = "select * from name_courses where id='".$r['course_name']."'";
									$qc = $conn->query($sqlCourses);
									$rc = $qc->fetch_assoc();

									if ($r['type'] == 'Short Courses') {
										$typeName = 'Curso corto';
									}elseif ($r['type'] == 'Training') {
										$typeName = 'Taller';
									}{

									}

									echo '<tr>
                                            <td>'.$i.'</td>
                                            <td>'.$r['modality'].'</td>
											<td>'.$typeName.'</td>
											<td>'.$ra['name'].'</td>
											<td>'.$rc['name'].'</td>
											<td>'.$r['course_code'].'</td>
											<td>'.$rt['first_name'].'</td>
											<td>'.$r['cost'].'</td>
											<td>'.$r['duration'].'</td>
											<td>'.$r['start_date'].'</td>
											<td>'.$r['days'].'</td>
											<td>'.$r['schedule'].'</td>
											<td>
											<a href="courses.php?action=edit&id='.$r['course_id'].'" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-edit"></span></a>
											<a onclick="return confirm(\'Deseas realmente eliminar este registro, este proceso es irreversible\');" href="courses.php?action=delete&id='.$r['course_id'].'" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></a> 
											</td>	
										</tr>';
										$i++;
									}
									?>
									
                                        
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                     
	<script src="js/dataTable/jquery.dataTables.min.js"></script>
    
     <script>
         $(document).ready(function () {
             $('#tSortable22').dataTable({
    "bPaginate": true,
    "bLengthChange": true,
    "bFilter": true,
    "bInfo": false,
    "bAutoWidth": true });
	
         });
		 
	
    </script>
		
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
