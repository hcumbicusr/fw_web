$(document).ready(function(){

	$.f_inicio = '';
	$.h_inicio = '';
	$.f_fin = '';
	$.h_fin = '';
	//Date range picker with time picker
    $('#rango_fecha').daterangepicker(
    	{ 
    		timePicker: true, 
    		timePickerIncrement: 10,
    		locale: {
		        format: 'DD/MM/YYYY H:mm'
			}
    	},
    	function(start, end, label) {
	        $.f_inicio = start.format("YYYY-MM-DD");
	        $.h_inicio = start.format("H:mm");
	        $.f_fin = end.format("YYYY-MM-DD");
	        $.h_fin = end.format("H:mm");
	        console.log($.ws+"?controller=Asistencia&action=search&type=datetime&"+
	          	"f_inicio="+$.f_inicio+"&"+
	          	"h_inicio="+$.h_inicio+"&"+
	          	"f_fin="+$.f_fin+"&"+
	          	"h_fin="+$.h_fin);
	        tblAsistencias.ajax.url($.ws+"?controller=Asistencia&action=search&type=datetime&"+
	          	"f_inicio="+$.f_inicio+"&"+
	          	"h_inicio="+$.h_inicio+"&"+
	          	"f_fin="+$.f_fin+"&"+
	          	"h_fin="+$.h_fin
	          ).load();
		}
    	);

    var tblAsistencias = $('#tblAsistencias').DataTable({    
    	ajax: { 
          url: $.ws+"?controller=Asistencia&action=search&type=datetime&"+
	          	"f_inicio="+$.f_inicio+"&"+
	          	"h_inicio="+$.h_inicio+"&"+
	          	"f_fin="+$.f_fin+"&"+
	          	"h_fin="+$.h_fin ,
          cache: true,
          type: 'GET',
          dataType: 'json',
          dataSrc: "data",
          beforeSend: function(xhr){
	        	xhr.setRequestHeader ("Authorization", "Basic " + btoa($.username + ":" + $.token));
	      },
        },
		'paging'      : true,
		'lengthChange': false,
		'searching'   : false,
		'ordering'    : true,
		'info'        : true,
		'autoWidth'   : false,    
        pageLength: 25,
        responsive: true,
        columns: [
          {'data': 'fecha_hora'},
          {'data': 'nro_documento'},
          {'data': 'apenom'},
          {'data': 'lat_lng'},
          {'data': 'mensaje'},
          {'data': 'dispositivo'},
          {'data': 'in_out'},
          {'data': 'created_at'},
          {'data': 'id'},
        ],
        order: [[ 1, "asc" ]],
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            {extend: 'copy'},
            {extend: 'csv'},
            {extend: 'excel', title: 'Export Excel'},
            {extend: 'pdf', title: 'Export PDF'},

            {extend: 'print',
             customize: function (win){
                    $(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');

                    $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
            }
            }
        ],
        columnDefs: [ 
        {
            'targets': 3, //columna de GPS
            'searchable': false,
            'orderable': false,
            'className': 'dt-body-center',
            'render': function (data, type, full, meta){
              // $(".delete-item").bind("click", deleteItem);
              return data;                
            }
        },
        {
            'targets': 8, //columna de options
            'searchable': false,
            'orderable': false,
            'className': 'dt-body-center',
            'render': function (data, type, full, meta){
              // $(".delete-item").bind("click", deleteItem);
              return data;                
            }
        } ],
        fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            // $(nRow).data("id", aData.id);
            // $(nRow).addClass("tr-cliente");
            // $(nRow).attr("onclick", "fnClickRowClient(this)");
            if ( aData.is_ok == 0 ) {
            	$(nRow).addClass("warning");
            }
            var msg = aData.mensaje;
            if ( msg.indexOf("NO_REGISTRADO") != -1 ) {
            	$(nRow).removeClass("warning");
            	$(nRow).addClass("info");
            }
            // console.log("aData",nRow,aData);
            return nRow;
        },
        language: {
		    "decimal":        "",
		    "emptyTable":     "No se encontró registros",
		    "info":           "Mostrando _START_ a _END_ de _TOTAL_ registros",
		    "infoEmpty":      "Mostrando 0 a 0 de 0 registros",
		    "infoFiltered":   "(filtered from _MAX_ total entries)",
		    "infoPostFix":    "",
		    "thousands":      ",",
		    "lengthMenu":     "Ver _MENU_ registros",
		    "loadingRecords": "Cargando...",
		    "processing":     "Procesando...",
		    "search":         "Buscar:",
		    "zeroRecords":    "No se encontraron coincidencias",
		    "paginate": {
		        "first":      "Primero",
		        "last":       "Último",
		        "next":       "Siguiente",
		        "previous":   "Anterior"
		    },
		    "aria": {
		        "sortAscending":  ": activate to sort column ascending",
		        "sortDescending": ": activate to sort column descending"
		    }
		}
    });

});