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
$codigo='';
$saldo_deposito='';


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
	 // Obtener valores del formulario
	 $tipo = $_POST["tipo"];
	 $nombre_producto = $_POST["nombre_producto"];
	 $ingreso = $_POST["cantidad_ingreso"];
	 $egreso_automatico = $_POST["egreso_automatico"];
	 $egreso_manual = $_POST["egreso_manual"];
	 
	 // Calcular saldo de productos en depósito
	 $saldo_deposito = $ingreso - $egreso_automatico - $egreso_manual;
 
	 // Generar código aleatorio
	 $codigo = "COD-" . substr(md5(uniqid(mt_rand(), true)), 0, 8);
	 
	 // Insertar datos en la base de datos
	 $query = "INSERT INTO inventory (product_code, product_type, product_name, ingress, automatic_egress, manual_egress, balance)
			   VALUES (?, ?, ?, ?, ?, ?, ?)";
	 $stmt = $conn->prepare($query);
	 $stmt->bind_param("sssiidd", $codigo, $tipo, $nombre_producto, $ingreso, $egreso_automatico, $egreso_manual, $saldo_deposito);
	 
	 if ($stmt->execute()) {
		 // Éxito
		 echo '<script type="text/javascript">window.location="inventory.php?act=1";</script>';
	 } else {
		 // Error
		 echo "Error al insertar el registro: " . $stmt->error;
	 }
	 
	 $stmt->close();
 
 }else
  if($_POST['action']=="update")
 {
 $id = mysqli_real_escape_string($conn,$_POST['id']);	
   $sql = $conn->query("UPDATE  students  SET  branch  = '$branch', address  = '$address', detail  = '$detail'  WHERE  id  = '$id'");
   echo '<script type="text/javascript">window.location="student.php?act=2";</script>';
 }



}

if(isset($_GET['action']) && $_GET['action']=="get_tipos" ){

    $query = "SELECT id, nombre FROM tipos_productos";
    $result = $conn->query($query);
    $tiposProductos = array();
    while ($row = $result->fetch_assoc()) {
        $tiposProductos[] = $row;
    }
    echo json_encode($tiposProductos);
	exit();
}

if(isset($_GET['action']) && $_GET['action']=="get_productos" ){

    $tipoId = $_GET['tipo_id'];
    $query = "SELECT id, nombre FROM productos WHERE tipo_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $tipoId);
    $stmt->execute();
    $result = $stmt->get_result();
    $productos = array();
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
    $stmt->close();
    echo json_encode($productos);
	exit();

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
                        <h1 class="page-head-line">Inventario  
						<?php
						echo (isset($_GET['action']) && @$_GET['action']=="add" || @$_GET['action']=="edit")?
						' <a href="inventory.php" class="btn btn-primary btn-sm pull-right">Volver <i class="glyphicon glyphicon-arrow-right"></i></a>':'<a href="inventory.php?action=add" class="btn btn-primary btn-sm pull-right"><i class="glyphicon glyphicon-plus"></i> Agregar Producto </a>';
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
                           <?php echo ($action=="add")? "Agregar Producto": "Editar Producto"; ?>
                        </div>
						<form action="inventory.php" method="post" id="signupForm1" class="form-horizontal">
                        <div class="panel-body">
						<fieldset class="scheduler-border" >
						 <legend  class="scheduler-border">Información Producto:</legend>
						 <div class="form-group">
							<label class="col-sm-3 control-label" for="codigo">Código*</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="codigo" name="codigo" value="<?php echo $codigo;?>" readonly>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="tipo">Tipo*</label>
							<div class="col-sm-9">
								<select class="form-control" id="tipo" name="tipo">
									<!-- Options will be loaded dynamically using JavaScript -->
									<option value="">Selecciona un tipo</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="nombre_producto">Nombre del producto*</label>
							<div class="col-sm-9">
								<select class="form-control" id="nombre_producto" name="nombre_producto">
									<!-- Options will be loaded dynamically using JavaScript -->
									<option value="">Selecciona un producto</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="cantidad_ingreso">Ingreso*</label>
							<div class="col-sm-9">
								<input type="number" class="form-control" id="cantidad_ingreso" name="cantidad_ingreso" min="0" step="1">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="cantidad_egreso">Cantidad productos a egresar*</label>
							<div class="col-sm-9">
								<input type="number" class="form-control" id="cantidad_egreso" name="cantidad_egreso" min="0" step="1">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="egreso_manual">Egreso Manual</label>
							<div class="col-sm-9">
								<input type="number" class="form-control" id="egreso_manual" name="egreso_manual" min="0" step="1">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="saldo_deposito">Saldo de productos en depósito*</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="saldo_deposito" name="saldo_deposito" value="<?php echo $saldo_deposito;?>" readonly>
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


	// Generar y mostrar el código automáticamente
    var codigoGenerado = generarCodigoAleatorio(6); // Llamada a la función de generación
    $('#codigo').val(codigoGenerado); // Mostrar el código en el campo
    $('#codigo-generado').text('Código generado: ' + codigoGenerado); // Mostrar el código generado en el contenedor/span
    
    // Resto de tu código AJAX para cargar campos desplegables...

	// Función para generar un código aleatorio
	function generarCodigoAleatorio(length) {
		var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		var codigo = '';
		for (var i = 0; i < length; i++) {
			codigo += characters.charAt(Math.floor(Math.random() * characters.length));
		}
		return codigo;
	}


	    // Cargar opciones del campo Tipo
		$.ajax({
        url: 'inventory.php?action=get_tipos',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            var tipoSelect = $('#tipo');
            $.each(data, function (index, tipo) {
                tipoSelect.append($('<option>', {
                    value: tipo.id,
                    text: tipo.nombre
                }));
            });
        }
    });

    // Manejar el evento cuando se cambia la selección del tipo
    $('#tipo').on('change', function () {
        var tipoSeleccionado = $(this).val();

        // Cargar opciones del campo Nombre del producto basado en el tipo seleccionado
        $.ajax({
            url: 'inventory.php?action=get_productos',
            method: 'GET',
            dataType: 'json',
            data: { tipo_id: tipoSeleccionado }, // Pasar el tipo seleccionado como parámetro
            success: function (data) {
                var productoSelect = $('#nombre_producto');
                productoSelect.empty(); // Limpiar opciones anteriores
                $.each(data, function (index, producto) {
                    productoSelect.append($('<option>', {
                        value: producto.id,
                        text: producto.nombre
                    }));
                });
            }
        });
    });

	 // Manejar el evento cuando se cambia la selección del Nombre del producto
	 $('#nombre_producto').on('change', function () {
        var productoSeleccionado = $(this).val();

        // Realizar una llamada AJAX para obtener el saldo del producto seleccionado
        $.ajax({
            url: 'inventory.php?action=get_saldo',
            method: 'GET',
            dataType: 'json',
            data: { producto_id: productoSeleccionado },
            success: function (saldoData) {
                var saldoField = $('#saldo');
                saldoField.val(saldoData.saldo);
            }
        });
		



    });


	    // Manejar el evento cuando se cambian los campos relacionados
		$('#ingreso, #egreso_automatico, #egreso_manual, #cantidad_egresar').on('input', function () {
        var cantidadIngreso = parseFloat($('#ingreso').val()) || 0;
        var cantidadEgresoAutomatico = parseFloat($('#egreso_automatico').val()) || 0;
        var cantidadEgresoManual = parseFloat($('#egreso_manual').val()) || 0;
        var cantidadEgresar = parseFloat($('#cantidad_egresar').val()) || 0;

        var saldo = cantidadIngreso - cantidadEgresoAutomatico - cantidadEgresoManual - cantidadEgresar;
        $('#saldo_deposito').val(saldo.toFixed(2)); // Mostrar el saldo con 2 decimales
    });

});

			</script>

			   
		<script type="text/javascript">
		

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
                            Administrar Información del inventario  
                        </div>
                        <div class="panel-body">
                            <div class="table-sorting table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="tSortable22">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Codigo</th>
											<th>Tipo</th>
                                            <th>Nombre del Producto</th>
                                            <th>Egreso Automático </th>
											<th>Egreso Manual</th>
											<th>Saldo de Productos en Depósito</th>
											<th>Detalles</th>
											<th>Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
									$sql = "select * from inventory where delete_status='0'";
									$q = $conn->query($sql);
									$i=1;
									while($r = $q->fetch_assoc())
									{
									echo '<tr>
                                            <td>'.$i.'</td>
                                            <td>'.$r['product_code'].'</td>
											<td>'.$r['product_type'].'</td>
											<td>'.$r['product_name'].'</td>
											<td>'.$r['ingress'].'</td>
											<td>'.$r['automatic_egress'].'</td>
								
											<td>'.$r['manual_egress'].'</td>
											<td>'.$r['balance'].'</td>
											<td><a href="inventory.php?action=edit&id='.$r['product_id'].'" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-edit"></span></a></td>
											<td><a onclick="return confirm(\'Deseas realmente eliminar este registro, este proceso es irreversible\');" href="student.php?action=delete&id='.$r['product_id'].'" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></a></td>	
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
