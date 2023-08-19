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
$student_name ='';


if(isset($_POST['save']))
{

// Obtener los valores de los campos del formulario
$studentName = mysqli_real_escape_string($conn, $_POST['student_names']);
$studentId = mysqli_real_escape_string($conn, $_POST['student_id']);
$modality = mysqli_real_escape_string($conn, $_POST['modality_select']);
$type = mysqli_real_escape_string($conn, $_POST['type_select']);
$area = mysqli_real_escape_string($conn, $_POST['area_select']);
$courseCode = mysqli_real_escape_string($conn, $_POST['course_select']);
$convenedCost = mysqli_real_escape_string($conn, $_POST['convened_cost']);
$advancePayment = mysqli_real_escape_string($conn, $_POST['advance_payment']);
$balance = mysqli_real_escape_string($conn, $_POST['balance']);
$teacherID = mysqli_real_escape_string($conn, $_POST['teacher_name']);
$courseCost = mysqli_real_escape_string($conn, $_POST['course_cost']);


 if($_POST['action']=="add")
 {
 
 	// Query de inserción
	$insertQuery = "INSERT INTO enrollments (student_id, identity_card_number, modality, type, area, course_name, teacher_id ,cost, agreed_cost, advance_payment, balance) 
	VALUES ('$studentName', '$studentId', '$modality', '$type', '$area', '$courseCode', '$teacherID' ,'$courseCost', '$convenedCost', '$advancePayment', '$balance')";
    $sid = $conn->insert_id;

	// Ejecutar el query
	if ($conn->query($insertQuery) === TRUE) {
		echo '<script type="text/javascript">window.location="enrollments.php?act=1";</script>';
	} else {
		echo "Error al realizar la inscripción: " . $conn->error;
	}
 
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

if (isset($_GET['action']) && $_GET['action'] == 'get_areas') {

	$query = "SELECT name, id FROM areas";
    $result = $conn->query($query);

    $areas = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $areas[] = array(
				'name' => $row['name'],
				'id' => $row['id']
			);
        }
    }

	header('Content-Type: application/json'); // Indicar que la respuesta es en formato JSON
    echo json_encode($areas);
    exit(); // Detener la ejecución del resto del código

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


if (isset($_GET['action']) && $_GET['action'] == 'search_students') {
    $filter = $_GET['filter'];

    $query = "SELECT student_id, first_name, last_name, identity_card_number FROM students WHERE CONCAT(first_name, ' ', last_name) LIKE '%$filter%'";
    $result = $conn->query($query);

    $students = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $students[] = array(
                'name' => $row['first_name'] . '-' . $row['last_name'],
                'id' => $row['identity_card_number'],
				'student_id' => $row['student_id']
            );
        }
    }

    header('Content-Type: application/json'); // Indicar que la respuesta es en formato JSON
    echo json_encode($students);
    exit(); // Detener la ejecución del resto del código
};

	if (isset($_GET['action']) && $_GET['action'] == 'get_filtered_courses') {
		$modalidad = $_GET['modalidad'];
		$tipo = $_GET['tipo'];
		$area = $_GET['area'];

		// Realiza una consulta SQL para obtener los cursos filtrados
		$query = "SELECT course_code, course_name, course_id FROM courses WHERE modality = '$modalidad' AND type = '$tipo' AND area = '$area'";
		$result = $conn->query($query);

		$courses = array();

		if ($result !== false && $result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$idCourse = $row['course_id'];
				$codigo = $row['course_name'];
				$codeCourse = $row['course_code'];
				
				// Obtener el nombre real del curso
				$queryName = "SELECT name FROM name_courses WHERE id = '$codigo'";
				$resultName = $conn->query($queryName);
	
				if ($resultName !== false && $resultName->num_rows > 0) {
					$realName = $resultName->fetch_assoc()['name'];
				} else {
					$realName = "Nombre no encontrado";
				}

				$courses[] = array(
					'codigo' => $codigo,
					'nombre' => $realName.'-'.$codeCourse,
					'idCourse' => $idCourse,
				);
			}
		}

		header('Content-Type: application/json'); // Indicar que la respuesta es en formato JSON
		echo json_encode($courses);
		exit();
	}

	if (isset($_GET['action']) && $_GET['action']=='teach-cost') {
		// Obtener el profesor y el costo del curso basado en el código del curso
		$courseCode = $_GET['course_code']; // Asegúrate de ajustar cómo obtienes el código del curso

		// Consulta para obtener el profesor y el costo del curso
		$query = "SELECT teacher_id, cost FROM courses WHERE course_id = '$courseCode'";
		$result = $conn->query($query);

		if ($result && $result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$teacherId = $row['teacher_id'];
			$courseCost = $row['cost'];

			// Consulta para obtener el nombre del profesor basado en su ID
			$teacherQuery = "SELECT first_name FROM teachers WHERE teacher_id = '$teacherId'";
			$teacherResult = $conn->query($teacherQuery);
			if ($teacherResult && $teacherResult->num_rows > 0) {
				$teacherRow = $teacherResult->fetch_assoc();
				$teacherName = $teacherRow['first_name'];

				// Enviar la información al cliente (JavaScript)
				$response = [
					'teacherName' => $teacherName,
					'courseCost' => $courseCost,
					'teacherID' => $teacherId,
				];
				header('Content-Type: application/json');
				echo json_encode($response);
			} else {
				echo "Error al obtener el nombre del profesor";
			}
		} else {
			echo "Error al obtener información del curso";
		}
		exit();
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
	
<script>

	// Cargar áreas desde el servidor
	function loadAreas() {
		fetch("enrollments.php?action=get_areas")
		.then(response => response.json())
		.then(data => {
			populateAreaSelect(data);
		})
		.catch(error => {
			console.error("Error fetching areas: ", error);
		});
	}


</script>


<script>
			let nameInput, studentNamesSelect, studentIdSelect;

			document.addEventListener("DOMContentLoaded", function() {
				// Asignar valores a las variables globales
				nameInput = document.getElementById("student_name");
				studentNamesSelect = document.getElementById("student_names");
				studentIdSelect = document.getElementById("student_id");
			});
			function filterStudents() {
				const filter = nameInput.value.toLowerCase();
			
				
				fetch(`enrollments.php?action=search_students&filter=${filter}`)
				.then(response => response.text())
				.then(text => {
					 // Ver la respuesta en la consola
					try {
						const data = JSON.parse(text);
						updateStudentSelects(data);
					} catch (error) {
						console.error("Error parsing JSON: ", error);
					}
				})
				.catch(error => {
					console.error("Error fetching data: ", error);
				});

			function updateStudentSelects(students) {
				studentNamesSelect.innerHTML = "";
				studentIdSelect.innerHTML = "";

				students.forEach(student => {
					const optionName = document.createElement("option");
					optionName.value = student.student_id;
					optionName.textContent = student.name;
					studentNamesSelect.appendChild(optionName);

					const optionId = document.createElement("option");
					optionId.value = student.id;
					optionId.textContent = student.id;
					studentIdSelect.appendChild(optionId);
				});
			}

		}
	
</script>


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
						' <a href="enrollments.php" class="btn btn-primary btn-sm pull-right">Volver <i class="glyphicon glyphicon-arrow-right"></i></a>':'<a href="enrollments.php?action=add" class="btn btn-primary btn-sm pull-right"><i class="glyphicon glyphicon-plus"></i> Agregar Incripcion </a>';
						?>
						</h1>
                     
<?php

echo $errormsg;
?>
                    </div>
                </div>
				
				

		<?php 

		 if(isset($_GET['action']) && @$_GET['action']=="generate-receipt")
		 {
			// Incluye la librería TCPDF
			require_once('tcpdf/tcpdf.php');
			ob_clean();
			// Obtén el student_id de la URL
			$enrollments = isset($_GET['id']) ? $_GET['id'] : null;

			// Verifica si se proporcionó un student_id válido
			if (!$enrollments) {
				die('ID de inscripcion no brindado.');
			}

			$sql = $conn->query("SELECT * FROM enrollments WHERE enrollment_id='".$enrollments."'");
			$rowsEdit = $sql->fetch_assoc();
			extract($rowsEdit);

		// Crea una instancia de TCPDF
		$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

		// Configura el título del documento
		$pdf->SetTitle('Carné de Estudiante');

		// Agrega una página al PDF
		$pdf->AddPage();

		// Dibuja las líneas alrededor del contenido
		$pdf->SetLineWidth(0.5); // Ancho de línea

		// Línea superior
		$pdf->Line(10, 10, 200, 10);

		// Línea izquierda
		$pdf->Line(10, 10, 10, 150);

		// Línea derecha
		$pdf->Line(200, 10, 200, 150);

		// Línea inferior
		$pdf->Line(10, 150, 200, 150);

		// Agrega el fondo del carné
		$pdf->Image('ruta_del_fondo.jpg', 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);

		// Agrega la imagen del estudiante (por ejemplo, foto)
	    $pdf->Image('img/colegioLogo.png', 40, 20, 80, 80, 'PNG', '', '', true, 150, '', false, false, 0);

		// Agrega los datos de la institucion
		$pdf->SetFont('helvetica', 'B', 16);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetXY(130, 20);
		$pdf->Cell(1, 10, 'Nombre Institución', 0);
		$pdf->Cell(0, 30, 'Dirección', 0, 1);
		$pdf->SetXY(130, 40);
		$pdf->Cell(1, 10, 'Teléfono', 0);
		$pdf->Cell(0, 30, 'Número correlativo', 0, 1);
		// Agrega los datos del estudiante
		$pdf->SetFont('helvetica', 'B', 18);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetXY(10, 120);
		$pdf->Cell(0, 0, 'sadasda', 0, 2, 'C');

		$pdf->SetFont('helvetica', 'B', 16);
		$pdf->SetXY(10, 130);
		$pdf->Cell(0, 0, 'Numero Carnet: ', 0, 2, 'C');

		// ... Agrega más campos según necesites ...

		// Genera el contenido del PDF
		$output = $pdf->Output('carnet_estudiante.pdf', 'S');

		// Muestra el PDF en el navegador
		header('Content-Type: application/pdf');
		header('Content-Disposition: inline; filename="reciboEstudiante.pdf"');
		echo $output;
		}
		?>
				
        <?php 
		 if(isset($_GET['action']) && @$_GET['action']=="add" || @$_GET['action']=="edit")
		 {
		?>
		
			<script type="text/javascript" src="js/validation/jquery.validate.min.js"></script>
                <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
               <div class="panel panel-primary">
                        <div class="panel-heading">
                           <?php echo ($action=="add")? "Agregar Profesor": "Editar Profesor"; ?>
                        </div>
						<form action="enrollments.php" method="post" id="signupForm1" class="form-horizontal">
                        <div class="panel-body">
						<fieldset class="scheduler-border" >
						 <legend  class="scheduler-border">Información Del Curso:</legend>

						 <div class="form-group">
							<label class="col-sm-3 control-label" for="studentName">Nombre del estudiante*</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="student_name" name="student_name" oninput="filterStudents()">
								<select id="student_names" name="student_names" class="form-control"></select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" for="studentID">Carnet del estudiante*</label>
							<div class="col-sm-9">
								<select id="student_id" name="student_id" class="form-control"></select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="modality_select">Modalidad*</label>
							<div class="col-sm-9">
								<select class="form-control" id="modality_select" name="modality_select">
									<option value="Virtual">Virtual</option>
									<option value="Presencial">Presencial</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="type_select">Tipo*</label>
							<div class="col-sm-9">
								<select class="form-control" id="type_select" name="type_select">
									<option value="Training">Capacitación</option>
									<option value="Short Courses">Talleres Cortos</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="area_select">Área*</label>
							<div class="col-sm-9">
								<select id="area_select" name="area_select">
									<!-- Options will be loaded dynamically using JavaScript -->
									<option value="">Selecciona un área</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="course_select">Nombre del Curso*</label>
							<div class="col-sm-9">
								<select class="form-control" id="course_select" name="course_select">
									<!-- Options will be loaded dynamically using JavaScript -->
									<option value="">Selecciona un curso</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Profesor</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="teacher_name" name="teacher_name" readonly>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Costo</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="course_cost" name="course_cost" readonly>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="convened_cost">Costo Convenido</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="convened_cost" name="convened_cost">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="advance_payment">Anticipo</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="advance_payment" name="advance_payment">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="balance">Saldo</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="balance" name="balance" readonly>
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
				// Obtener los elementos del formulario
				const costoConvenidoInput = document.getElementById('convened_cost');
				const anticipoInput = document.getElementById('advance_payment');
				const saldoInput = document.getElementById('balance');

				// Agregar eventos para escuchar los cambios en los campos de costo convenido y anticipo
				costoConvenidoInput.addEventListener('input', calcularSaldo);
				anticipoInput.addEventListener('input', calcularSaldo);

				function calcularSaldo() {
					const costoConvenido = parseFloat(costoConvenidoInput.value) || 0;
					const anticipo = parseFloat(anticipoInput.value) || 0;

					const saldo = costoConvenido - anticipo;

					// Actualizar el campo de saldo con el resultado del cálculo
					saldoInput.value = saldo.toFixed(2);
				}
				</script>



<script>

// Obtener el elemento del formulario para el campo de código de curso
const courseCodeInput = document.getElementById('course_select');

// Agregar un evento para escuchar los cambios en el campo de código de curso
courseCodeInput.addEventListener('change', () => {
    const selectedCourseCode = courseCodeInput.value;
    // Realizar una solicitud AJAX para obtener información del profesor y el costo
    fetch(`enrollments.php?action=teach-cost&course_code=${selectedCourseCode}`)
        .then(response => response.json())
        .then(data => {
			console.log(data.teacherName)
            // Actualizar los campos en el formulario con la información obtenida
			document.getElementById('teacher_name').textContent = data.teacherName;
			document.getElementById('teacher_name').value = data.teacherID;
            document.getElementById('course_cost').textContent = data.courseCost;
			document.getElementById('course_cost').value = data.courseCost;
        })
        .catch(error => {
            console.error('Error fetching course info:', error);
        });
});

</script>

<script>


document.addEventListener("DOMContentLoaded", function() {
    const courseSelect = document.getElementById("course_select");
    const modeSelect = document.getElementById("modality_select");
    const typeSelect = document.getElementById("type_select");
    const areaSelect = document.getElementById("area_select");

    // Función para cargar los cursos disponibles
    function loadAvailableCourses() {
        const selectedMode = modeSelect.value;
        const selectedType = typeSelect.value;
        const selectedArea = areaSelect.value;

        // Verificar si todas las opciones están seleccionadas antes de hacer la solicitud
        if (selectedMode && selectedType && selectedArea) {
            fetch(`enrollments.php?action=get_filtered_courses&modalidad=${selectedMode}&tipo=${selectedType}&area=${selectedArea}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then(courses => {
                    // Llena el menú desplegable "Nombre del Curso"
                    courseSelect.innerHTML = "<option value=''>Selecciona un curso</option>";
                    courses.forEach(course => {
                        const option = document.createElement("option");
                        option.value = course.idCourse;
                        option.textContent = course.nombre;
                        courseSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error("Error fetching courses: ", error);
                });
        } else {
            // Si alguna opción no está seleccionada, restablecer el menú desplegable "Nombre del Curso"
            courseSelect.innerHTML = "<option value=''>Selecciona un curso</option>";
        }
    }

    // Llama a la función para cargar cursos disponibles al cambiar las opciones de modalidad, tipo o área
    modeSelect.addEventListener("change", loadAvailableCourses);
    typeSelect.addEventListener("change", loadAvailableCourses);
    areaSelect.addEventListener("change", loadAvailableCourses);
});

			   </script>
			   
		<script type="text/javascript">

const areaSelect = document.getElementById("area_select");
	// Llenar el menú desplegable de áreas con las opciones
	function populateAreaSelect(areas) {
		areaSelect.innerHTML = '<option value="">Selecciona un área</option>';
		
		areas.forEach(area => {
			const option = document.createElement("option");
			option.value = area.id;
			option.textContent = area.name;
			areaSelect.appendChild(option);
		});
	}

	// Cargar las áreas cuando se cargue la página
	document.addEventListener("DOMContentLoaded", loadAreas, areaSelect);


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
		}else{
		?>
		
		 <link href="css/datatable/datatable.css" rel="stylesheet" />
		 
		
		 
		 
		<div class="panel panel-default">
                        <div class="panel-heading">
                            Inscribirme a cursos  
                        </div>
                        <div class="panel-body">
                            <div class="table-sorting table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="tSortable22">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombres Estudiante</th>
											<th>Carné de Identidad</th>
                                            <th>Modalidad</th>
                                            <th>Tipo de Curso </th>
											<th>Área del Curso</th>
											<th>Nombre del Curso</th>
											<th>Fecha de Inicio</th>
											<th>Duración</th>
											<th>Horario</th>
											<th>Días</th>
											<th>Profesor</th>
											<th>Costo</th>
											<th>Costo Convenido</th>
											<th>Anticipo</th>
											<th>Saldo</th>
											<th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
									$sql = "select * from enrollments where delete_status='0'";
									$q = $conn->query($sql);
									$i=1;

									while($r = $q->fetch_assoc())
									{
									$sqlStudent = "select * from students where student_id='".$r['student_id']."'";
									$qS = $conn->query($sqlStudent);
									$rS = $qS->fetch_assoc();

									$sqlArea = "select * from areas where id='".$r['area']."'";
									$qA = $conn->query($sqlArea);
									$rA = $qA->fetch_assoc();

									$sqlCourses = "select * from courses where course_id='".$r['course_name']."'";
									$qC = $conn->query($sqlCourses);
									$rC = $qC->fetch_assoc();

									$sqlNameCourse = "select * from name_courses where id='".$rC['course_name']."'";
									$qNC = $conn->query($sqlNameCourse);
									$rNC = $qNC->fetch_assoc();

									$sqlTeacher = "select * from teachers where teacher_id='".$rC['teacher_id']."'";
									$qT = $conn->query($sqlTeacher);
									$rT = $qT->fetch_assoc();


									if ($r['type'] == 'Short Courses') {
										$courseType = 'Curso corto';
									}elseif ($r['type'] == 'Training') {
										$courseType = 'Capacitacion';
									}
									
									echo '<tr>
                                            <td>'.$i.'</td>
											<td>'.$rS['first_name'].' '.$rS['last_name'].'</td>
											<td>'.$rS['identity_card_number'].'</td>
											<td>'.$r['modality'].'</td>
											<td>'.$courseType.'</td>
											<td>'.$rA['name'].'</td>
											<td>'.$rNC['name'].'</td>
											<td>'.$rC['start_date'].'</td>
											<td>'.$rC['duration'].'</td>
											<td>'.$rC['schedule'].'</td>
											<td>'.$rC['days'].'</td>
											<td>'.$rT['first_name'].' '.$rT['last_name'].'</td>
											<td>'.$r['cost'].'</td>
											<td>'.$r['agreed_cost'].'</td>
											<td>'.$r['advance_payment'].'</td>
											<td>'.$r['balance'].'</td>
											<td>
											<a href="student.php?action=edit&id='.$r['student_id'].'" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-edit"></span></a>
											<a href="enrollments.php?action=generate-receipt&id='.$r['enrollment_id'].'" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-barcode"></span></a>
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
