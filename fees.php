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

        // Conectar a la base de datos (misma conexión que antes)

        // Insertar los datos en la tabla de ingresos
        $sql = "INSERT INTO financial_entries (date, course_id, category, item, subitem, detail, amount)
                VALUES ('$date', $course_id, $rubro_id, $item_id, $subitem_id, '$detail', $amount)";

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
        if(isset($_GET['action']) && @$_GET['action']=='expense') {
        ?>

<div class="row">
            <div class="col-sm-10 col-sm-offset-1">
               <div class="panel panel-primary">
						<form action="fees.php" method="post" id="signupForm1" class="form-horizontal">
                        <div class="panel-body">
						<fieldset class="scheduler-border" >
						 <legend  class="scheduler-border">Agregar gasto:</legend>
						<div class="form-group">
                <label class="col-sm-3 control-label" for="expense_date">Fecha* </label>
                <div class="col-sm-9">
                    <input type="date" class="form-control" id="expense_date" name="expense_date" value="<?php echo $expense_date; ?>" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="expense_course_select">Nombre del Curso*</label>
                <div class="col-sm-9">
                    <select class="form-control" id="expense_course_select" name="expense_course_select">
                        <!-- Opciones se cargarán dinámicamente usando JavaScript -->
                        <option value="">Selecciona un curso</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="expense_rubro_select">Rubro*</label>
                <div class="col-sm-9">
                    <select class="form-control" id="expense_rubro_select" name="expense_rubro_select">
                        <!-- Opciones se cargarán dinámicamente usando JavaScript -->
                        <option value="">Selecciona un rubro</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="expense_item_select">Ítem*</label>
                <div class="col-sm-9">
                    <select class="form-control" id="expense_item_select" name="expense_item_select">
                        <!-- Opciones se cargarán dinámicamente usando JavaScript -->
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="expense_subitem_select">Subítem*</label>
                <div class="col-sm-9">
                    <select class="form-control" id="expense_subitem_select" name="expense_subitem_select">
                        <!-- Opciones se cargarán dinámicamente usando JavaScript -->
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="expense_detail">Detalle*</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="expense_detail" name="expense_detail" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="expense_amount">Monto*</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="expense_amount" name="expense_amount" />
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
									<input type="date" class="form-control" id="date" name="date" value="<?php echo $date;?>"  />
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
                            <a href="reports.php?action=expense">
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


    });


    </script>

    

</body>
</html>
