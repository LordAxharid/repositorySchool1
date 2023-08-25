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
$expense_date='now';

?>


<?php 
$queryAlumno = "SELECT * FROM students";
$resultAlumno = mysqli_query($conn, $queryAlumno);

$queryProfesor = "SELECT * FROM teachers";
$resultProfesor = mysqli_query($conn, $queryProfesor);

?>

<?php

$queryArea = "SELECT id, name FROM areas";
$resultArea = mysqli_query($conn, $queryArea);

if(isset($_REQUEST['act']) && @$_REQUEST['act']=="1")
{
$errormsg = "<div class='alert alert-success'> <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Excelente!</strong> Ingreso o Egreso Agregado Exitósamente</div>";
}else if(isset($_REQUEST['act']) && @$_REQUEST['act']=="2")
{
$errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> <strong>Excelente!</strong> Ingreso o Egreso Editado Exitósamente</div>";
}
else if(isset($_REQUEST['act']) && @$_REQUEST['act']=="3")
{
$errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Excelente!</strong> Ingreso o Egreso Eliminado Exitósamente</div>";
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
    ?>

</head>
<?php
include("php/header.php");
?>
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-head-line">Contable</h1>

                    </div>
                </div>
                <!-- /. ROW  -->
                <div class="row">

                <?php 
                    if (!isset($_GET['action'])) {
                        # code...
                ?>
				 <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
               <div class="panel panel-primary">
						<form action="report-balance.php" method="post" id="signupForm1" class="form-horizontal">
                        <div class="panel-body">
						<fieldset class="scheduler-border" >
						 <legend  class="scheduler-border">Filtrar:</legend>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="report_type">Seleccione el tipo de informe*</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="report_type" name="report_type">
                                        <!-- Options will be loaded dynamically using JavaScript -->
                                        <option value="">Selecciona un tipo de informe</option>
                                        <option value="curso">Informes por Curso</option>
                                        <option value="modalidad">Informes por Modalidad</option>
                                        <option value="area">Informes por Área</option>
                                        <option value="fechas">Informes por Fechas</option>
                                        <option value="estudiante">Informes por Estudiante</option>
                                        <option value="profesor">Informes por Profesor</option>
                                    </select>
                                </div>
                            </div>

                         <div id="curso_field" style="display: none;">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="course_select">Curso*</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="course_select" name="course_select">
                                        <!-- Options will be loaded dynamically using JavaScript -->
                                            <option value="">Selecciona un curso</option>
                                            <?php
                                            // Conectar a la base de datos y obtener la lista de cursos
                                            // ...
                                            $query = "SELECT * FROM courses";
                                            $result = $conn->query($query);
                                            // Iterar sobre los cursos y mostrarlos en las opciones del select
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<option value="' . $row["course_id"] . '">' . $row["course_code"] . '</option>';
                                            }
                                            
                                            // Cerrar la conexión a la base de datos
                                            // ...
                                            ?>
                                    </select>
                                </div>
                            </div>
                         </div>
                      

                         <div id="modalidad_options" style="display: none;">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="course_select">Curso*</label>
                                <div class="col-sm-9">
                                <select class="form-control" id="modalidad_select" name="modalidad_select">
                                    <!-- Opciones de cursos cargadas dinámicamente con JavaScript -->
                                    <option value="Presencial">Presencial</option>
                                    <option value="Virtual">Virtual</option>
                                </select>
                                </div>
                            </div>
                         </div>

                         <div id="area_options" style="display: none;">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="area_select">Area*</label>
                                <div class="col-sm-9">
                                <select class="form-control" id="area_select" name="area_select">
                                    <!-- Opciones de areas cargadas dinámicamente con JavaScript -->
                                    <?php while ($row = mysqli_fetch_assoc($resultArea)) { ?>
								        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
							        <?php } ?>
                                </select>
                                </div>
                            </div>
                         </div>

                         <div id="alumn_options" style="display: none;">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="alumno_select">Alumno*</label>
                                <div class="col-sm-9">
                                <select class="form-control" id="alumno_select" name="alumno_select">
                                    <!-- Opciones de areas cargadas dinámicamente con JavaScript -->
                                    <?php while ($rowStudent = mysqli_fetch_assoc($resultAlumno)) { ?>
								        <option value="<?php echo $rowStudent['student_id']; ?>"><?php echo $rowStudent['first_name']; ?></option>
							        <?php } ?>
                                </select>
                                </div>
                            </div>
                         </div>

                         <div id="teacher_options" style="display: none;">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="teacher_select">Profesor*</label>
                                <div class="col-sm-9">
                                <select class="form-control" id="teacher_select" name="teacher_select">
                                    <!-- Opciones de areas cargadas dinámicamente con JavaScript -->
                                    <?php while ($rowProfesor = mysqli_fetch_assoc($resultProfesor)) { ?>
								        <option value="<?php echo $rowProfesor['teacher_id']; ?>"><?php echo $rowProfesor['first_name']; ?></option>
							        <?php } ?>
                                </select>
                                </div>
                            </div>
                         </div>
                      
                         <div id="dates_options" style="display: none;">
                            <div class="form-group">
                            <label class="col-sm-3 control-label" for="Old">Fecha Inicio* </label>
								<div class="col-sm-9">
									<input type="date" class="form-control" id="start_date" name="start_date"  />
								</div>
							</div>
                       
                            <div class="form-group">
                            <label class="col-sm-3 control-label" for="Old">Fecha Fin* </label>
								<div class="col-sm-9">
									<input type="date" class="form-control" id="end_date" name="end_date" />
								</div>
							</div>
                         </div>

						 </fieldset>
						
						<div class="form-group">
								<div class="col-sm-8 col-sm-offset-2">
								<input type="hidden" name="id" value="<?php echo $id;?>">
								<input type="hidden" name="action" value="filter-report">
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
                    if ($_SERVER["REQUEST_METHOD"] === "POST") {
                    $reportType = $_POST["report_type"];

                    if ($reportType === "curso") {
                        // Obtiene el curso seleccionado
                        $selectedCourse = $_POST["course_select"];
                        
                        
                        $query = "SELECT * FROM courses WHERE course_id = $selectedCourse";
                        $result = $conn->query($query);
                        $curso = $result->fetch_assoc();

                        $teachID = $curso["teacher_id"];

                        $queryTeacherName = "SELECT * FROM teachers WHERE teacher_id = $teachID";
                        $resultTeach = $conn->query($queryTeacherName);
                        $teachRes = $resultTeach->fetch_assoc();
                        
                        // Consulta para obtener la cantidad de alumnos inscritos
                        $queryAlumnos = "SELECT COUNT(*) AS cantidad_alumnos FROM enrollments WHERE course_name = $selectedCourse";
                        $resultAlumnos = $conn->query($queryAlumnos);
                        $rowAlumnos = $resultAlumnos->fetch_assoc();
                        $cantidadAlumnos = $rowAlumnos["cantidad_alumnos"];

                        // Consulta para obtener la nómina de alumnos
                        $queryNomina = "SELECT * FROM enrollments WHERE course_name = $selectedCourse";
                        $resultNomina = $conn->query($queryNomina);
                    
                        // Consulta para obtener los ingresos para el curso
                        $queryIngresos = "SELECT SUM(amount) AS total_ingresos FROM financial_entries WHERE type = 'ingreso' AND course_id = $selectedCourse";
                        $resultIngresos = $conn->query($queryIngresos);
                        $rowIngresos = $resultIngresos->fetch_assoc();
                        $totalIngresos = $rowIngresos["total_ingresos"];
                        
                        // Consulta para obtener los egresos para el curso
                        $queryEgresos = "SELECT SUM(amount) AS total_egresos FROM financial_entries WHERE type = 'gasto' AND course_id = $selectedCourse";
                        $resultEgresos = $conn->query($queryEgresos);
                        $rowEgresos = $resultEgresos->fetch_assoc();
                        $totalEgresos = $rowEgresos["total_egresos"];
                        
                        // Calcula el resultado
                        $resultado = $totalIngresos - $totalEgresos;
                        
                        // Muestra los resultados en la interfaz
                        echo '<h2>Informes por Curso</h2>
                         <div class="table-sorting table-responsive">
                            <p>Curso: ' . $curso['course_code'] .'</p>
                            <p>Cantidad de alumnos inscritos:  '. $cantidadAlumnos .' </p>
                            <p>Fecha de inicio:  '. $curso['start_date'] .' </p>
                            <p>Profesor:  '. $teachRes['first_name'] .' '. $teachRes['last_name'] .'</p>
                            <p>Ingresos:  '. $totalIngresos . '</p>
                            <p>Egresos:  '. $totalEgresos .' </p>
                            <p>Resultado:  '. $resultado .' </p>
                         </div>';
                        // Muestra la nómina de alumnos
                        echo "<h3>Nómina de Alumnos</h3>";
                        while ($alumno = $resultNomina->fetch_assoc()) {
                            $studentQueryNomina = "SELECT * FROM students WHERE student_id = ".$alumno['student_id']."";
                            $studentNomina = $conn->query($studentQueryNomina);
                        while ($student = $studentNomina->fetch_assoc()) {
                            echo "<p>Nombre: " . $student["first_name"] . ' ' . $student["last_name"]."</p>";
                            echo "<p>Numero de identificacion: " . $student["identity_card_number"] . "</p>";
                            // Agrega otros campos de la nómina de alumnos
                            echo "<br>";
                        }
                    }

                    } elseif ($reportType === "modalidad") {
                               // Obtiene la modalidad seleccionada
                        $selectedModalidad = $_POST["modalidad_select"];
                       
                        $query = "SELECT * FROM courses WHERE modality = '$selectedModalidad'";
                        $result = $conn->query($query);
                        $curso = $result->fetch_assoc();
            
                        // Calcula los ingresos y egresos para la modalidad
                        // Consultas SQL y cálculos similares a los de informes por curso
                        $queryModalidad = "SELECT * FROM courses WHERE modality = '$selectedModalidad'";
                        $resultCursosModalidad = $conn->query($queryModalidad);

                        
                        // Muestra los resultados en la interfaz
                        echo "<h2>Informes por Modalidad</h2>";
                        echo "<p>Modalidad: " . $selectedModalidad . "</p>";
                        // Muestra los resultados de ingresos, egresos, etc.
                        
                        // Muestra los cursos de la modalidad
                        while ($curso = $resultCursosModalidad->fetch_assoc()) {

                        // Consulta para obtener los ingresos para el curso
                         $queryIngresos = "SELECT SUM(amount) AS total_ingresos FROM financial_entries WHERE type = 'ingreso' AND course_id = ".$curso['course_id']."";
                         $resultIngresos = $conn->query($queryIngresos);
                         $rowIngresos = $resultIngresos->fetch_assoc();
                         $totalIngresos = $rowIngresos["total_ingresos"];

                        // Consulta para obtener los egresos para el curso
                         $queryEgresos = "SELECT SUM(amount) AS total_egresos FROM financial_entries WHERE type = 'gasto' AND course_id = ".$curso['course_id']."";
                         $resultEgresos = $conn->query($queryEgresos);
                         $rowEgresos = $resultEgresos->fetch_assoc();
                         $totalEgresos = $rowEgresos["total_egresos"];

                        // Calcula el resultado
                         $resultado = $totalIngresos - $totalEgresos;

                            echo "<p>Curso: " . $curso["course_code"] . "</p>";
                            echo "<p>Ingresos: " . $totalIngresos . "</p>";
                            echo "<p>Egresos: " . $totalEgresos . "</p>";
                            echo "<p>Resultado: " . $resultado . "</p>";
                            echo "<br>";
                        }   
                    }elseif ($reportType === "area") {

                        $selectedModalidad = $_POST["area_select"];

                        $queryArea = "SELECT * FROM enrollments WHERE area = '$selectedModalidad'";
                        $resultArea = $conn->query($queryArea);
                        $area = $resultArea->fetch_assoc();
                        
                        // Calcula los ingresos y egresos para la modalidad
                        // Consultas SQL y cálculos similares a los de informes por curso
                        $queryModalidad = "SELECT * FROM courses WHERE area = '$selectedModalidad'";
                        $resultCursosModalidad = $conn->query($queryModalidad);
                        
                        // Muestra los resultados en la interfaz
                        echo "<h2>Informes por Area</h2>";
                        // Muestra los resultados de ingresos, egresos, etc.
                        
                        // Muestra los cursos de la modalidad
                        while ($curso = $resultCursosModalidad->fetch_assoc()) {

                        // Consulta para obtener los ingresos para el curso
                         $queryIngresos = "SELECT SUM(amount) AS total_ingresos FROM financial_entries WHERE type = 'ingreso' AND course_id = ".$curso['course_id']."";
                         $resultIngresos = $conn->query($queryIngresos);
                         $rowIngresos = $resultIngresos->fetch_assoc();
                         $totalIngresos = $rowIngresos["total_ingresos"];

                        // Consulta para obtener los egresos para el curso
                         $queryEgresos = "SELECT SUM(amount) AS total_egresos FROM financial_entries WHERE type = 'gasto' AND course_id = ".$curso['course_id']."";
                         $resultEgresos = $conn->query($queryEgresos);
                         $rowEgresos = $resultEgresos->fetch_assoc();
                         $totalEgresos = $rowEgresos["total_egresos"];

                         $queryAreaName = "SELECT * FROM areas WHERE id = ".$curso["area"]."";
                         $resultAreaName = $conn->query($queryAreaName);
                         $areaName = $resultAreaName->fetch_assoc();

                         $resultado = $totalIngresos - $totalEgresos;
                        
                            echo "<p>Area: " . $areaName["name"] . "</p>";
                            echo "<p>Ingresos: " . $totalIngresos . "</p>";
                            echo "<p>Egresos: " . $totalEgresos . "</p>";
                            echo "<p>Resultado: " . $resultado . "</p>";
                            echo "<br>";

                    }
                }elseif ($reportType === "fechas") {

                    $startDate = $_POST["start_date"];
                    $endDate = $_POST["end_date"];

                    $queryFechas = "SELECT * FROM financial_entries WHERE date BETWEEN '$startDate' AND '$endDate'";
                    $resultFechas = $conn->query($queryFechas);

                    // Muestra los resultados en la interfaz
                    echo "<h2>Informes por Fechas</h2>";
                    echo "<p>Fecha de inicio: $startDate</p>";
                    echo "<p>Fecha de fin: $endDate</p>";
                    // Muestra los resultados de ingresos, egresos, etc.
                    
                    // Muestra los registros en el rango de fechas
                    echo "<h3>Registros en el Rango de Fechas</h3>";
                    while ($registro = $resultFechas->fetch_assoc()) {
                        echo "<p>Fecha: " . $registro["date"] . "</p>";
                        echo "<p>Detalle: " . $registro["detail"] . "</p>";
                        echo "<p>Monto: " . $registro["amount"] . "</p>";
                        // Otros campos según la tabla financial_entries
                        echo "<br>";
                    }

                }elseif ($reportType === "estudiante") {

                    $selectedStudent = $_POST["alumno_select"];
                    $startDate = $_POST["start_date"];
                    $endDate = $_POST["end_date"];

                    $queryStudent = "SELECT * FROM students WHERE student_id = '$selectedStudent'";
                    $resultStudent = $conn->query($queryStudent);
                    $estudiante = $resultStudent->fetch_assoc();
  
                    $queryCursosEstudiante = "SELECT * FROM enrollments WHERE student_id = '$selectedStudent'";
                    $resultCursosEstudiante = $conn->query($queryCursosEstudiante);

                    // Muestra los resultados en la interfaz
                    echo "<h2>Informes por Estudiante</h2>";
                    echo "<p>Estudiante: " . $estudiante["first_name"] . "</p>";
                    
                    // Muestra los cursos en los que se ha inscrito y sus saldos por pagar
                    while ($cursoEstudiante = $resultCursosEstudiante->fetch_assoc()) {
    
                    // Calcula el saldo por pagar para el curso
                    $queryIngresosCurso = "SELECT SUM(amount) AS total_ingresos FROM financial_entries WHERE type = 'ingreso' AND course_id = ".$cursoEstudiante['course_name']."";
                    // Ejecuta la consulta y obtiene los ingresos
                    $resultIngresoCurso = $conn->query($queryIngresosCurso);
                    $ingresoCurso = $resultIngresoCurso->fetch_assoc();

                    $queryEgresosCurso = "SELECT SUM(amount) AS total_egresos FROM financial_entries WHERE type = 'gasto' AND course_id = $cursoEstudiante[course_name]";
                    // Ejecuta la consulta y obtiene los egresos
                    $resultEgresoCurso = $conn->query($queryEgresosCurso);
                    $egresoCurso = $resultEgresoCurso->fetch_assoc();
      
                    $totalIngresosCurso = $ingresoCurso['total_ingresos'];

                    $totalEgresosCurso = $egresoCurso['total_egresos'];

                    $saldoPorPagarCurso = $totalIngresosCurso - $totalEgresosCurso;

                        $queryNameCourse = "SELECT * FROM courses WHERE course_id = ".$cursoEstudiante["course_name"]."";
                        $resultNameCourse = $conn->query($queryNameCourse);
                        $cursoName = $resultNameCourse->fetch_assoc();

                        echo "<p>Curso: " . $cursoName["course_code"] . "</p>";
                        echo "<p>Saldo por Pagar: " . $saldoPorPagarCurso . "</p>";
                        echo "<br>";
                    }

                }elseif ($reportType === "profesor") {

                    // Obtiene las fechas seleccionadas
                    $startDate = $_POST["start_date"];
                    $endDate = $_POST["end_date"];
                    
                    // Obtiene el profesor seleccionado
                    $selectedProfesor = $_POST["teacher_select"];
                    
                    $queryProfesor = "SELECT * FROM teachers WHERE teacher_id = '$selectedProfesor'";
                    $resultProfesor = $conn->query($queryProfesor);
                    $profesor = $resultProfesor->fetch_assoc();
                    
                    // Consulta para obtener los cursos dictados por el profesor en las fechas seleccionadas
                    $queryCursosProfesor = "SELECT * FROM courses WHERE teacher_id = $selectedProfesor AND start_date BETWEEN '$startDate' AND '$endDate'";
                    $resultCursosProfesor = $conn->query($queryCursosProfesor);
                    
                    // Muestra los resultados en la interfaz
                    echo "<h2>Informes por profesor</h2>";
                    echo "<p>Profesor: " . $profesor["first_name"] . "</p>";
                    
                    // Muestra los cursos dictados por el profesor en las fechas seleccionadas
                    echo "<h3>Cursos dictados por el profesor</h3>";
                    while ($cursoProfesor = $resultCursosProfesor->fetch_assoc()) {

                        $queryNameCourse = "SELECT * FROM courses WHERE course_name = ".$cursoProfesor["course_name"]."";

                        $resultNameCourse = $conn->query($queryNameCourse);
                        $cursoName = $resultNameCourse->fetch_assoc();
         
                        echo "<p>Curso: " . $cursoName["course_code"] . "</p>";

                        $cursoId = $cursoProfesor["course_id"];
                        $queryIngresosCurso = "SELECT SUM(amount) AS total_ingresos FROM financial_entries WHERE course_id = $cursoId AND type = 'ingreso'";
                        $resultIngresosCurso = $conn->query($queryIngresosCurso);
                        $rowIngresosCurso = $resultIngresosCurso->fetch_assoc();
                        $totalIngresosCurso = $rowIngresosCurso["total_ingresos"];
                        
                        // Consulta para calcular los egresos por curso
                        $queryEgresosCurso = "SELECT SUM(amount) AS total_egresos FROM financial_entries WHERE course_id = $cursoId AND type = 'gasto'";
                        $resultEgresosCurso = $conn->query($queryEgresosCurso);
                        $rowEgresosCurso = $resultEgresosCurso->fetch_assoc();
                        $totalEgresosCurso = $rowEgresosCurso["total_egresos"];
                        
                        // Calcula los ingresos y egresos para el curso dictado
                        // Consultas SQL y cálculos similares a los de informes por curso
                        // ...
                        
                        echo "<p>Ingresos: " . $totalIngresosCurso . "</p>";
                        echo "<p>Egresos: " . $totalEgresosCurso . "</p>";
                        
                        $resultadoCurso = $totalIngresosCurso - $totalEgresosCurso;
                        echo "<p>Resultado: " . $resultadoCurso . "</p>";
                        
                        echo "<br>";
                    }

                }
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

    // Mostrar/ocultar el campo de selección de curso según el tipo de informe elegido
    const reportTypeSelect = document.querySelector('[name="report_type"]');
    const cursoField = document.getElementById('curso_field');
    
    reportTypeSelect.addEventListener('change', function() {
        if (reportTypeSelect.value === 'curso') {
            cursoField.style.display = 'block';
        } else {
            cursoField.style.display = 'none';
        }
    });

    document.getElementById("report_type").addEventListener("change", function() {
    var selectedValue = this.value;
    
    if (selectedValue === "curso") {
        document.getElementById("curso_field").style.display = "block";
        document.getElementById("modalidad_options").style.display = "none";
    } else if (selectedValue === "modalidad") {
        document.getElementById("curso_field").style.display = "none";
        document.getElementById("modalidad_options").style.display = "block";
    } else if (selectedValue === "area") {
        document.getElementById("curso_field").style.display = "none";
        document.getElementById("modalidad_options").style.display = "none";
        document.getElementById("area_options").style.display = "block";
    } else if (selectedValue === "fechas") {
        document.getElementById("curso_field").style.display = "none";
        document.getElementById("modalidad_options").style.display = "none";
        document.getElementById("area_options").style.display = "none";
        document.getElementById("dates_options").style.display = "block";
    }
    else if (selectedValue === "estudiante") {
        document.getElementById("curso_field").style.display = "none";
        document.getElementById("modalidad_options").style.display = "none";
        document.getElementById("area_options").style.display = "none";
        document.getElementById("dates_options").style.display = "block";
        document.getElementById("alumn_options").style.display = "block";
    }
    else if (selectedValue === "profesor") {
        document.getElementById("curso_field").style.display = "none";
        document.getElementById("modalidad_options").style.display = "none";
        document.getElementById("area_options").style.display = "none";
        document.getElementById("alumn_options").style.display = "none";
        document.getElementById("dates_options").style.display = "block";
        document.getElementById("teacher_options").style.display = "block";
    
    }
    
});

</script>

    

</body>
</html>
