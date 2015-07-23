$(document).ready(function() {
	
	//Select or unselect all checkbox
	$('.select_all').click(function() {
		
		if($(this).attr('checked')) {
			
			$('input:checkbox').attr('checked', true);
				
		} else {
			
			$('input:checkbox').attr('checked', false);
		
		}
			
	});

	//qTip
	$('a[title], img[title], span[title]').qtip({
		style: {
			classes: 'ui-tooltip-shadow ui-tooltip-plain'
		},
		position: {
			my: 'bottom center',
			at: 'top center'
		}
	});
	
	//Image preview
	$('a.image_preview').each(function() {
		
		$(this).qtip({
			content: {
				text: '<img src="' + $(this).attr("href") + '" alt="" />'
			},
			style: {
				classes: 'ui-tooltip-shadow ui-tooltip-plain'
			},
			position: {
				my: 'left center',
				at: 'right center'
			}
		});
		
	});

	//Alternate row colors
	$(".table tr:nth-child(odd)").addClass("odd");
	$(".table tr:nth-child(even)").addClass("even");
				
});
