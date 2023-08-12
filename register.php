<?php
include("php/dbconnect.php");
include("php/checklogin.php");
$errormsg = '';
$action = "add";

$id="";
$emailid='';
$username='';
$username='';
$password='';
$joindate = '';
$remark='';
$contact='';
$balance = 0;
$fees='';
$about = '';
$branch='';
$card_number='';


if(isset($_POST['save']))
{

$username = mysqli_real_escape_string($conn,$_POST['username']);
$password = mysqli_real_escape_string($conn,md5($_POST['password']));
$rol = mysqli_real_escape_string($conn,$_POST['rol']);

 if($_POST['action']=="add")
 {

  $q1 = $conn->query("INSERT INTO users (username,password,rol) VALUES ('$username','$password','$rol')") ;
  
// ... (código anterior)

if ($q1) {
    // Obtener el ID generado por la primera inserción
    $sid = $conn->insert_id;

    if ($rol == 'professor' || $rol == 'student') {
        // Variables para la segunda inserción
        $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
        $card_number = mysqli_real_escape_string($conn, $_POST['card_number']);
        $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
        $city = mysqli_real_escape_string($conn, $_POST['city']);
        $zone = mysqli_real_escape_string($conn, $_POST['zone']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);

        // Realizar la segunda inserción usando consultas preparadas
        $stmt = null;
        if ($rol == 'professor') {
            $stmt = $conn->prepare("INSERT INTO teachers (first_name, last_name, identity_card_number, phone_number, email ,city, zone, id_user) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        } else {
            $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, identity_card_number, phone_number, email ,city, zone, id_user) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        }
        
        if ($stmt) {
            $stmt->bind_param("sssssssi", $first_name, $last_name, $card_number, $phone_number, $email ,$city, $zone, $sid);
            if ($stmt->execute()) {
                echo "Ambas inserciones se realizaron con éxito.";
            } else {
                echo "Error en la segunda inserción: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error en la preparación de la consulta: " . $conn->error;
        }
    } else {
        echo "Rol no válido.";
    }
} else {
    echo "Error en la primera inserción: " . $conn->error;
}
   
    
echo '<script type="text/javascript">window.location="register.php?act=1";</script>';
 
 }else
  if($_POST['action']=="update")
 {
 $id = mysqli_real_escape_string($conn,$_POST['id']);	
   $sql = $conn->query("UPDATE  students  SET  branch  = '$branch', address  = '$address', detail  = '$detail'  WHERE  id  = '$id'");
   echo '<script type="text/javascript">window.location="student.php?act=2";</script>';
 }

}

if (isset($_GET['action']) && $_GET['action'] == "delete") {
    $userId = $_GET['id'];
    $userRole = $_GET['rol'];

    // Actualizar el estado de eliminación en la tabla de usuarios
    $updateUserQuery = $conn->prepare("UPDATE users SET delete_status = '1' WHERE id = ?");
    $updateUserQuery->bind_param("i", $userId);

    if ($updateUserQuery->execute()) {
        // Si el rol es 'professor' o 'student', realizar la actualización correspondiente
        if ($userRole == 'professor' || $userRole == 'student') {
            $tableName = ($userRole == 'professor') ? 'teachers' : 'students';
            
            $updateRoleTableQuery = $conn->prepare("UPDATE $tableName SET delete_status = '1' WHERE id_user = ?");
            $updateRoleTableQuery->bind_param("i", $userId);

            if (!$updateRoleTableQuery->execute()) {
                echo "Error al actualizar las tablas: " . $updateRoleTableQuery->error;
            }

            $updateRoleTableQuery->close();
        }

        $updateUserQuery->close();

        header("location: register.php?act=3");
        exit();
    } else {
        echo "Error al actualizar la tabla de usuarios: " . $updateUserQuery->error;
    }

    $updateUserQuery->close();
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
$errormsg = "<div class='alert alert-success'> <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Excelente!</strong> Usuario Agregado Exitósamente</div>";
}else if(isset($_REQUEST['act']) && @$_REQUEST['act']=="2")
{
$errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> <strong>Excelente!</strong> Usuario Editado Exitósamente</div>";
}
else if(isset($_REQUEST['act']) && @$_REQUEST['act']=="3")
{
$errormsg = "<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Excelente!</strong> Usuario Eliminado Exitósamente</div>";
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
                        <h1 class="page-head-line">Usuarios  
						<?php
						echo (isset($_GET['action']) && @$_GET['action']=="add" || @$_GET['action']=="edit")?
						' <a href="register.php" class="btn btn-primary btn-sm pull-right">Volver <i class="glyphicon glyphicon-arrow-right"></i></a>':'<a href="register.php?action=add" class="btn btn-primary btn-sm pull-right"><i class="glyphicon glyphicon-plus"></i> Agregar Usuarios </a>';
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
                           <?php echo ($action=="add")? "Agregar Usuario": "Editar Usuario"; ?>
                        </div>
						<form action="register.php" method="post" id="signupForm1" class="form-horizontal">
                        <div class="panel-body">
						<fieldset class="scheduler-border" >
						 <legend  class="scheduler-border">Información Usuario:</legend>
						<div class="form-group">
								<label class="col-sm-3 control-label" for="username">Nombre* </label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="username" name="username" value="<?php echo $username;?>" required  />
								</div>
							</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="password">Contraseña* </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="password" name="password" value="<?php echo $password;?>" required />
							</div>
						</div>	

                        <div class="form-group">
							<label class="col-sm-3 control-label" for="Rol">Rol* </label>
							<div class="col-sm-9">
                            <select id="rol" name="rol" required>
                                <option value="god">Dios</option>
                                <option value="admin">Administrador</option>
                                <option value="staff">Personal administrativo</option>
                                <option value="professor">Profesor</option>
                                <option value="student">Alumno</option>
                            </select>
							</div>
						</div>	
				
						 </fieldset>
                         <div id="additional-fields" style="display: none;">
                         <fieldset>

                         <div class="form-group">
							<label class="col-sm-3 control-label" for="first_name">Nombres* </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="first_name" name="first_name"  required/>
							</div>
						</div>	

                        <div class="form-group">
							<label class="col-sm-3 control-label" for="last_name">Apellidos* </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="last_name" name="last_name"  required/>
							</div>
						</div>	

                        <div class="form-group">
							<label class="col-sm-3 control-label" for="card_number">Numero de carnet* </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="card_number" name="card_number"  required/>
							</div>
						</div>	

                        <div class="form-group">
							<label class="col-sm-3 control-label" for="phone_number">Numero de celular* </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="phone_number" name="phone_number" required/>
							</div>
						</div>	

                        <div class="form-group">
							<label class="col-sm-3 control-label" for="email">Email* </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="email" name="email" required/>
							</div>
						</div>	

                        <div class="form-group">
							<label class="col-sm-3 control-label" for="city">ciudad* </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="city" name="city" required/>
							</div>
						</div>	

                        <div class="form-group">
							<label class="col-sm-3 control-label" for="zone">Zona* </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="zone" name="zone" required/>
							</div>
						</div>	

                         </fieldset>
                        </div>
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

        const rolSelect = document.getElementById('rol');
        const camposProfesor = document.getElementById('additional-fields');

        rolSelect.addEventListener('change', function() {
            if (rolSelect.value === 'professor' || rolSelect.value === 'student') {
                camposProfesor.style.display = 'block';
            } else {
                camposProfesor.style.display = 'none';
            }
        });

		$(document).ready(function () {
    $("#joindate").datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        yearRange: "1970:<?php echo date('Y');?>"
    });

    if ($("#signupForm1").length > 0) {
        <?php if($action == 'add') {?>
            $("#signupForm1").validate({
                rules: {
                    firs_name: "required",
                    last_name: "required",
                    card_number: {
                        required: true,
                        digits: true
                    },
                    phone_number: {
                        required: true,
                        digits: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    city: "required",
                    zone: "required",

                },
                errorElement: "em",
                errorPlacement: function (error, element) {
                    error.addClass("help-block");
                    element.parents(".col-sm-10").addClass("has-feedback");
                    if (element.prop("type") === "checkbox") {
                        error.insertAfter(element.parent("label"));
                    } else {
                        error.insertAfter(element);
                    }
                    if (!element.next("span")[0]) {
                        $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
                    }
                },
                success: function (label, element) {
                    if (!$(element).next("span")[0]) {
                        $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
                    }
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).parents(".col-sm-10").addClass("has-error").removeClass("has-success");
                    $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).parents(".col-sm-10").addClass("has-success").removeClass("has-error");
                    $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
                }
            });
        <?php } else {?>
            $("#signupForm1").validate({
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
                errorElement: "em",
                errorPlacement: function (error, element) {
                    error.addClass("help-block");
                    element.parents(".col-sm-10").addClass("has-feedback");
                    if (element.prop("type") === "checkbox") {
                        error.insertAfter(element.parent("label"));
                    } else {
                        error.insertAfter(element);
                    }
                    if (!element.next("span")[0]) {
                        $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
                    }
                },
                success: function (label, element) {
                    if (!$(element).next("span")[0]) {
                        $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
                    }
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).parents(".col-sm-10").addClass("has-error").removeClass("has-success");
                    $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).parents(".col-sm-10").addClass("has-success").removeClass("has-error");
                    $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
                }
            });
        <?php }?>
    }
});

$("#fees").keyup(function () {
    $("#advancefees").val("");
    $("#balance").val(0);
    var fee = $.trim($(this).val());
    if (fee != '' && !isNaN(fee)) {
        $("#advancefees").removeAttr("readonly");
        $("#balance").val(fee);
        $('#advancefees').rules("add", {
            max: parseInt(fee)
        });
    } else {
        $("#advancefees").attr("readonly", "readonly");
    }
});

$("#advancefees").keyup(function () {
    var advancefees = parseInt($.trim($(this).val()));
    var totalfee = parseInt($("#fees").val());
    if (advancefees != '' && !isNaN(advancefees) && advancefees <= totalfee) {
        var balance = totalfee - advancefees;
        $("#balance").val(balance);
    } else {
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
                            Administrar Información del inventario  
                        </div>
                        <div class="panel-body">
                            <div class="table-sorting table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="tSortable22">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre</th>
                                            <th>Rol</th>
											<th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
									$sql = "select * from users where delete_status='0'";
									$q = $conn->query($sql);
									$i=1;
									while($r = $q->fetch_assoc())
									{
									echo '<tr>
                                            <td>'.$i.'</td>
                                            <td>'.$r['username'].'</td>
                                            <td>'.$r['rol'].'</td>
											<td><a href="register.php?action=edit&id='.$r['id'].'" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-edit"></span></a>
											<a onclick="return confirm(\'Deseas realmente eliminar este registro, este proceso es irreversible\');" href="register.php?action=delete&id='.$r['id'].'&rol='.$r['rol'].'" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></a></td>	
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
