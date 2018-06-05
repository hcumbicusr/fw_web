<?php require_once 'templates/header.php'; ?>

  <?php require_once 'templates/topbar.php'; ?>
  <?php require_once 'templates/sidebar.php'; ?>
  
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">
      <div class="row">

      	<div class="col-xs-12">
          	<div class="box">
	            <div class="box-header">
	              <h3 class="box-title">Asistencias</h3>
	            </div>
	            <!-- /.box-header -->
	            <div class="box-body">
	            	<div class="col-md-6">
	            	 <!-- Date and time range -->
		              <div class="form-group">
		                <label>Date and time range:</label>

		                <div class="input-group">
		                  <div class="input-group-addon">
		                    <i class="fa fa-clock-o"></i>
		                  </div>
		                  <input type="text" class="form-control pull-right" id="rango_fecha">
		                </div>
		                <!-- /.input group -->
		              </div>
		              <!-- /.form group -->
		            </div>

	              <table id="tblAsistencias" class="table table-bordered table-hover">
	                <thead>
	                <tr>
	                  <th>Fecha y Hora</th>
	                  <th>Nro Doc</th>
	                  <th>Personal</th>
	                  <th>GPS</th>
	                  <th>Mensaje</th>
	                  <th>Dispositivo</th>
	                  <th>Ingreso/Salida</th>
	                  <th>F.Reg</th>
	                  <th>Options</th>
	                </tr>
	                </thead>
	                <tbody>
	                <!-- js -->
		            </tbody>
		        	</table>
		    	</div>
		    	<!-- /.box-body -->
        	</div>
        	<!-- /.box -->

    	</div>
      </div>
    </section>
    <!-- /.content -->

<?php require_once 'templates/footer.php'; ?>
