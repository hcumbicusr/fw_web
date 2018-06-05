$(document).ready(function(){

	$.username = $("#username_").val();
	$.token = $("#token_").val();
	$.ws = 'core/helpers/Request.php';

	$("#logout").click(function(e){
		fnLogout();
	});

	fnLogout = function() {
		$.ajax({
	        url: $.ws,
	        type: 'POST',
	        data: {action: 'logout'},
	        dataType: 'json',
	        success: function(response) {
	          console.log("logout", response);
	          if ( response != null ) {
	            if ( response.status == 'success' ) {
	              location.href = "?view=login";
	            } else {
	              alert(response.message);
	            }
	          }
	        }
	      });
	}



	fnValidateToken = function() {
		$.ajax({
	        url: $.ws,
	        type: 'POST',
	        data: {
	        	controller: 'Usuario',
	        	action: 'validateToken'
	        },
	        dataType: 'json',
	        beforeSend: function(xhr){
	        	xhr.setRequestHeader ("Authorization", "Basic " + btoa($.username + ":" + $.token));
	        },
	        success: function(response) {
	          console.log("validate", response);
	          
	        }
	      });
	}

	fnValidateToken();
});