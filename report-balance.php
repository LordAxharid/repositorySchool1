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
    echo "<h2>Informes por Curso</h2>";
    echo "<p>Curso: " . $curso["course_name"] . "</p>";
    echo "<p>Cantidad de alumnos inscritos: " . $cantidadAlumnos . "</p>";
    echo "<p>Fecha de inicio: " . $curso["start_date"] . "</p>";
    echo "<p>Profesor: " . $teachRes["first_name"] .' '. $teachRes["last_name"] ."</p>";
    echo "<p>Ingresos: " . $totalIngresos . "</p>";
    echo "<p>Egresos: " . $totalEgresos . "</p>";
    echo "<p>Resultado: " . $resultado . "</p>";

} elseif ($reportType === "modalidad") {
    // Lógica para informes por modalidad
}
}

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

</script>

    <script>

$(document).ready(function () {
 // Obtener la referencia al campo de selección de cursos
 var courseSelect = $('#course_select');

// Realizar una petición AJAX para obtener la lista de cursos
$.ajax({
    url: 'fees.php?action=get_courses',
    method: 'GET',
    dataType: 'json',
    success: function (data) {
        $.each(data, function (index, course) {
            courseSelect.append($('<option>', {
                value: course.idCourse,
                text: course.nombre
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


$(document).ready(function () {
            // Obtener la referencia al campo de selección de cursos
            var courseSelect = $('#expense_course_select');

// Realizar una petición AJAX para obtener la lista de cursos
$.ajax({
    url: 'fees.php?action=get_courses',
    method: 'GET',
    dataType: 'json',
    success: function (data) {
        $.each(data, function (index, course) {
            courseSelect.append($('<option>', {
                value: course.idCourse,
                text: course.nombre
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
            var rubroSelect = $('#expense_rubro_select');
            $.each(data, function (index, rubro) {
                rubroSelect.append($('<option>', {
                    value: rubro.rubro_id,
                    text: rubro.rubro_name
                }));
            });
        }
    });
})

//All general expense deposit
$(document).ready(function () {
 // Obtener la referencia al campo de selección de cursos
 var courseSelect = $('#general_course_select');



    // Cargar opciones del campo Rubro
    $.ajax({
        url: 'fees.php?action=get_general_rubros',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            var rubroSelect = $('#general_rubro_select');
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
 $('#general_rubro_select').change(function () {
        var rubro_id = $(this).val();
        
        $.ajax({
            url: 'fees.php?action=get_general_items',
            method: 'GET',
            dataType: 'json',
            data: { rubro_id: rubro_id },
            success: function (data) {
                var itemSelect = $('#general_item_select');
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
    $('#general_item_select').change(function () {
        var item_id = $(this).val();
        
        $.ajax({
            url: 'fees.php?action=get_general_subitems',
            method: 'GET',
            dataType: 'json',
            data: { item_id: item_id },
            success: function (data) {
                var subitemSelect = $('#general_subitem_select');
                $.each(data, function (index, subitem) {
                    subitemSelect.append($('<option>', {
                        value: subitem.subitem_id,
                        text: subitem.subitem_name
                    }));
                });
            }
        });
    })


 // Cargar opciones del campo Ítem expense
 $('#rubro_select').change(function () {
        var rubro_id = $(this).val();
        
        $.ajax({
            url: 'fees.php?action=get_items',
            method: 'GET',
            dataType: 'json',
            data: { rubro_id: rubro_id },
            success: function (data) {
                var itemSelect = $('#item_select');
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
