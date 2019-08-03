	function add_extra_cost_to_total(){
		//alert("OK");
		var res;
		res = jQuery('#cartCheckbox').is(":checked");
		//alert(res);
		
		if(res){
			//alert("Start");
			jQuery(document).ready(function($){
				
				$.ajax({
					url: readmelater_ajax.ajax_url,
					type: 'post',
					data: {
						action: 'add_extra_charge_to_total',
					},
					success: function(){
						//alert("Added");
					},
					 
				});
			});
		}
	}