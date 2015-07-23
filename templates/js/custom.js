$(document).ready(function() {

	//Add product
	$("form.add_product").submit(function() {
		
		var options = [];
		
		$(this).find('.option').each(function(i, selected) {
			options[i] = $(selected).val();
		});

		if (options.length === 0) {
			
			var data = {product_id: $(this).find('input[name=product_id]').val()};
		
		} else {
			
			var data = {product_id: $(this).find('input[name=product_id]').val(), 'option[]': options};
		
		}
		
		$('div.loading').block({ 
			message: 'Processing',
			css: { 
				border: 'none', 
				padding: '15px', 
				backgroundColor: '#000', 
				'-webkit-border-radius': '10px', 
				'-moz-border-radius': '10px',
				'border-radius': '8px',
				'font-weight': 'bold',
				opacity: .8, 
				color: '#FFF'
			} 
		});
						
		$.ajax({
			type: "POST",
			url: "cart.php?action=add_product",
			data: data,
			success: function() {
				
    			$.get("cart.php?action=get_cart", function(cart) {

  					$("#cart_items").fadeOut('slow').html(cart).fadeIn('slow');
  					
				});
								
				$('div.loading').unblock();

    			$.get("cart.php?action=show_errors", function(cart) {

  					$("#show_errors").fadeOut('slow').html(cart).fadeIn('slow');
  					
				});
															
			}
		});
				
		return false;
		
	});

	//Alternate row colors
	$(".table tr:nth-child(odd)").addClass("odd");
	$(".table tr:nth-child(even)").addClass("even");
			
});
