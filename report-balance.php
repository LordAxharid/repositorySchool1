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


if(isset($_POST['save']))
{

        if($_POST['action']=="add")
        {

            
        // Obtener los datos del formulario
        $date = $_POST['date'];
        $course_id = $_POST['course_select'];
        $rubro_id = $_POST['rubro_select'];
        $item_id = $_POST['item_select'];
        $subitem_id = $_POST['subitem_select'];
        $detail = $_POST['detail'];
        $amount = $_POST['amount'];
        $type = 'Ingreso';

        // Conectar a la base de datos (misma conexión que antes)

        // Insertar los datos en la tabla de ingresos
        $sql = "INSERT INTO financial_entries (date, course_id, category, item, subitem, detail, amount, type)
                VALUES ('$date', $course_id, $rubro_id, $item_id, $subitem_id, '$detail', $amount, '$type')";

        if ($conn->query($sql) === TRUE) {
            echo '<script type="text/javascript">window.location="fees.php?act=1";</script>';
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        }

        if($_POST['action']=="add-expense")
        {

            
        // Obtener los datos del formulario
        $date = $_POST['general_date'];
        $course_id = $_POST['expense_course_select'];
        $rubro_id = $_POST['expense_rubro_select'];
        $item_id = $_POST['expense_item_select'];
        $subitem_id = $_POST['expense_subitem_select'];
        $detail = $_POST['expense_detail'];
        $amount = $_POST['expense_amount'];
        $type = 'Gasto';

        // Conectar a la base de datos (misma conexión que antes)

        // Insertar los datos en la tabla de ingresos
        $sql = "INSERT INTO financial_entries (date, course_id, category, item, subitem, detail, amount, type)
                VALUES ('$date', $course_id, $rubro_id, $item_id, $subitem_id, '$detail', $amount, '$type')";

        if ($conn->query($sql) === TRUE) {
            echo '<script type="text/javascript">window.location="fees.php?act=1";</script>';
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        }

        
        if ($_POST['action'] == 'add-institution-expense-deposit') {
            // Obtener los datos del formulario
            $date = $_POST['general_date'];
            $rubro_id = $_POST['general_rubro_select'];
            $item_id = $_POST['general_item_select'];
            $subitem_id = $_POST['general_subitem_select'];
            $detail = $_POST['general_detail'];
            $amount = $_POST['general_amount'];
            $type = $_POST['general_type_select'];

            // Conectar a la base de datos (misma conexión que antes)

            // Insertar los datos en la tabla de ingresos
            $sql = "INSERT INTO financial_entries (date, general_category, general_item, general_subitem, detail, amount, type)
                    VALUES ('$date', '$rubro_id', '$item_id', '$subitem_id', '$detail', '$amount', '$type')";

            if ($conn->query($sql) === TRUE) {
                echo '<script type="text/javascript">window.location="fees.php?act=1";</script>';
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

}

if (isset($_GET['action']) && $_GET['action'] == 'get_courses') {

    // Realiza una consulta SQL para obtener los cursos filtrados
    $query = "SELECT course_code, course_name, course_id FROM courses";
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

if (isset($_GET['action']) && $_GET['action'] == 'get_rubros') {

    // Consulta para obtener los rubros
        $sql = "SELECT * FROM rubros";
        $result = $conn->query($sql);

        $rubros = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rubros[] = array(
                    'rubro_id' => $row['id'],
                    'rubro_name' => $row['name']
                );
            }
        }

        // Enviar la respuesta como JSON
        header('Content-Type: application/json');
        echo json_encode($rubros);
        exit();

}

if (isset($_GET['action']) && $_GET['action'] == 'get_items') {

    // Obtener el rubro seleccionado
        $rubro_id = $_GET['rubro_id'];

        // Conectar a la base de datos (misma conexión que antes)

        // Consulta para obtener los ítems del rubro específico
        $sql = "SELECT * FROM items WHERE rubro_id = $rubro_id";
        $result = $conn->query($sql);

        $items = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $items[] = array(
                    'item_id' => $row['id'],
                    'item_name' => $row['name']
                );
            }
        }

        // Cerrar la conexión a la base de datos

        // Enviar la respuesta como JSON
        header('Content-Type: application/json');
        echo json_encode($items);
        exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'get_subitems') {

        // Obtener el ítem seleccionado
        $item_id = $_GET['item_id'];

        // Conectar a la base de datos (misma conexión que antes)

        // Consulta para obtener los subítems del ítem específico
        $sql = "SELECT * FROM subitems WHERE item_id = $item_id";
        $result = $conn->query($sql);

        $subitems = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $subitems[] = array(
                    'subitem_id' => $row['id'],
                    'subitem_name' => $row['name']
                );
            }
        }

        // Cerrar la conexión a la base de datos

        // Enviar la respuesta como JSON
        header('Content-Type: application/json');
        echo json_encode($subitems);

        exit();

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
						<form action="fees.php" method="post" id="signupForm1" class="form-horizontal">
                        <div class="panel-body">
						<fieldset class="scheduler-border" >
						 <legend  class="scheduler-border">Filtrar:</legend>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="modalidad">Modalidad*</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="modalidad" name="modalidad">
                                        <!-- Options will be loaded dynamically using JavaScript -->
                                        <option value="">Selecciona una opcion</option>
                                        <option value="Virtual">Virtual</option>
                                        <option value="Presencial">Presencial</option>
                                    </select>
                                </div>
                            </div>

                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="rubro_select">Rubro*</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="rubro_select" name="rubro_select">
                                        <!-- Options will be loaded dynamically using JavaScript -->
                                        <option value="">Selecciona un rubro</option>
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


                    <?php 
                        if (isset($_GET['action']) && $_GET['action']=='balance') {

                               // Calcular el total de ingresos
                                $sql_ingresos = "SELECT SUM(amount) AS total_ingresos FROM financial_entries WHERE type = 'Ingreso'";
                                $result_ingresos = $conn->query($sql_ingresos);
                                $row_ingresos = $result_ingresos->fetch_assoc();
                                $total_ingresos = $row_ingresos["total_ingresos"];

                                // Calcular el total de gastos
                                $sql_gastos = "SELECT SUM(amount) AS total_gastos FROM financial_entries WHERE type = 'Gasto'";
                                $result_gastos = $conn->query($sql_gastos);
                                $row_gastos = $result_gastos->fetch_assoc();
                                $total_gastos = $row_gastos["total_gastos"];

                                // Calcular el balance general
                                $balance_general = $total_ingresos - $total_gastos;
                                echo '<div class="col-md-4"> <div class="main-box mb-blue">';
                                echo "<h5>Total de Ingresos: " . $total_ingresos . "</h5>";
                                echo "<h5>Total de Gastos: " . $total_gastos . "</h5>";
                                echo "<h5>Balance General: " . $balance_general . "</h5>";
                                echo '</div></div>';

                    ?>

                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="table-sorting table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="tSortable22">
                                    <thead>
                                        <tr>
                                            <th>#</th>
											<th>Curso</th>
											<th>Informe</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
									$sql = "select * from courses";
									$q = $conn->query($sql);
									$i=1;
									while($r = $q->fetch_assoc())
									{
                                    $sqlCourse = "select * from financial_entries WHERE course_id = ".$r['course_id']."";
                                    $qC = $conn->query($sqlCourse);
                                    $rC = $qC->fetch_assoc();
                           
									echo '<tr>
                                            <td>'.$i.'</td>
                                            <td>'.$r['course_code'].'</td>
											<td><a href="fees.php?action=see-balance-course&id='.$r['course_id'].'" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-edit"></span></a></td>
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
                        if (isset($_GET['action']) && $_GET['action']=='see-balance-course') {

                                // Obtener el código del curso
                                $id = $_GET['id'];

                               // Calcular el total de ingresos
                                $sql_ingresos = "SELECT SUM(amount) AS total_ingresos FROM financial_entries WHERE type = 'Ingreso' AND course_id = ".$id."";
                                $result_ingresos = $conn->query($sql_ingresos);
                                $row_ingresos = $result_ingresos->fetch_assoc();
                                $total_ingresos = $row_ingresos["total_ingresos"];

                                // Calcular el total de gastos
                                $sql_gastos = "SELECT SUM(amount) AS total_gastos FROM financial_entries WHERE type = 'Gasto' AND course_id = ".$id."";
                                $result_gastos = $conn->query($sql_gastos);
                                $row_gastos = $result_gastos->fetch_assoc();
                                $total_gastos = $row_gastos["total_gastos"];

                                // Calcular el balance general
                                $balance_general = $total_ingresos - $total_gastos;
                                echo '<div class="col-md-4"> <div class="main-box mb-blue">';
                                echo "<h5>Total de Ingresos: " . $total_ingresos . "</h5>";
                                echo "<h5>Total de Gastos: " . $total_gastos . "</h5>";
                                echo "<h5>Balance General: " . $balance_general . "</h5>";
                                echo '</div></div>';

                        ?>

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
