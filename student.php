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

$first_name = mysqli_real_escape_string($conn,$_POST['first_name']);
$last_name = mysqli_real_escape_string($conn,$_POST['last_name']);
$card_number = mysqli_real_escape_string($conn,$_POST['card_number']);
$phone_number = mysqli_real_escape_string($conn,$_POST['phone_number']);
$email = mysqli_real_escape_string($conn,$_POST['email']);
$city = mysqli_real_escape_string($conn,$_POST['city']);
$zone = mysqli_real_escape_string($conn,$_POST['zone']);
$id = mysqli_real_escape_string($conn,$_POST['id']);

 $sql = "UPDATE students SET first_name = '$first_name', last_name = '$last_name', identity_card_number = '$card_number', phone_number = '$phone_number', email = '$email', city = '$city', zone = '$zone' WHERE student_id = '$id'";

 if ($conn->query($sql)) {
	 echo '<script type="text/javascript">window.location="student.php?act=2";</script>';
 } else {
	 echo "Error al actualizar la base de datos: " . $conn->error;
 }
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


if(isset($_GET['action']) && $_GET['action']=="delete"){

	$conn->query("UPDATE students set delete_status = '1'  WHERE student_id='".$_GET['id']."'");	
	$conn->query("UPDATE users set delete_status = '1'  WHERE id='".$_GET['userId']."'");	
	header("location: student.php?act=3");
	
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
                        <h1 class="page-head-line">Estudiantes  
						<?php
						echo (isset($_GET['action']) && @$_GET['action']=="add" || @$_GET['action']=="edit")?
						' <a href="student.php" class="btn btn-primary btn-sm pull-right">Volver <i class="glyphicon glyphicon-arrow-right"></i></a>':'<a href="register.php?action=add" class="btn btn-primary btn-sm pull-right"><i class="glyphicon glyphicon-plus"></i> Agregar Estudiante </a>';
						?>
						</h1>
                     
<?php

echo $errormsg;
?>
                    </div>
                </div>
				
				<?php 
		 if(isset($_GET['action']) && @$_GET['action']=="generate-carnet")
		 {
			// Incluye la librería TCPDF
			require_once('tcpdf/tcpdf.php');
			ob_clean();
			// Obtén el student_id de la URL
			$student_id = isset($_GET['id']) ? $_GET['id'] : null;

			// Verifica si se proporcionó un student_id válido
			if (!$student_id) {
				die('ID de estudiante no proporcionado.');
			}

			$sql = $conn->query("SELECT * FROM students WHERE student_id='".$student_id."'");
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
		$pdf->Cell(0, 0, $rowsEdit['first_name'].' '.$rowsEdit['last_name'], 0, 2, 'C');

		$pdf->SetFont('helvetica', 'B', 16);
		$pdf->SetXY(10, 130);
		$pdf->Cell(0, 0, 'Numero Carnet: ' . $rowsEdit['identity_card_number'], 0, 2, 'C');

		// ... Agrega más campos según necesites ...

		// Genera el contenido del PDF
		$output = $pdf->Output('carnet_estudiante.pdf', 'S');

		// Muestra el PDF en el navegador
		header('Content-Type: application/pdf');
		header('Content-Disposition: attachment; filename="carnet_estudiante.pdf"');
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
                           <?php echo ($action=="add")? "Agregar Estudiante": "Editar Estudiante"; ?>
                        </div>
						<form action="student.php" method="post" id="signupForm1" class="form-horizontal">
                        <div class="panel-body">
						<fieldset class="scheduler-border" >
						 <legend  class="scheduler-border">Información Personal:</legend>
						<div class="form-group">
								<label class="col-sm-3 control-label" for="first_name">Nombre* </label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $first_name;?>"  />
								</div>
							</div>
	
						<div class="form-group">
							<label class="col-sm-3 control-label" for="last_name">Apellidos* </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $last_name;?>"  />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="card_number">Número de Carnét de Identidad* </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="card_number" name="card_number" value="<?php echo $identity_card_number;?>"  />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="phone_number">N° de Celular* </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo $phone_number;?>"  />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="email">Correo Electrónico* </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="email" name="email" value="<?php echo $email;?>"  />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="city">Ciudad donde vive* </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="city" name="city" value="<?php echo $city;?>"  />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label" for="zone">Zona donde vive* </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="zone" name="zone" value="<?php echo $zone;?>"  />
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
                            Administrar Información de los Estudiantes  
                        </div>
                        <div class="panel-body">
                            <div class="table-sorting table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="tSortable22">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombres</th>
											<th>Apellidos</th>
                                            <th>Carnet</th>
                                            <th>Teléfono </th>
											<th>Email</th>
											<th>Ciudad</th>
											<th>Zona</th>
											<th>Acción</th>
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
                                            <td>'.$i.'</td>
                                            <td>'.$r['first_name'].'</td>
											<td>'.$r['last_name'].'</td>
											<td>'.$r['identity_card_number'].'</td>
											<td>'.$r['phone_number'].'</td>
											<td>'.$r['email'].'</td>
											<td>'.$r['city'].'</td>
											<td>'.$r['zone'].'</td>
											<td>
											<a href="student.php?action=edit&id='.$r['student_id'].'" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-edit"></span></a>
											<a onclick="return confirm(\'Deseas realmente eliminar este registro, este proceso es irreversible\');" href="student.php?action=delete&id='.$r['student_id'].'&userId='.$r['id_user'].'" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></a> 
											<a href="student.php?action=generate-carnet&id='.$r['student_id'].'" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-barcode"></span></a>
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
