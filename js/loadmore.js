$(document).ready(function(){
	var flag = 0;
	$.ajax({
		type: "POST",
		url: "php/get_data.php",
		data:{
			'offset': 0,
			'limit': 3
		},
		success: function(data){
			$('.append_articles').append(data);
			flag += 3;
		}
	});
	$('body').on('click','#submit_ajax',function(){
		$.ajax({
			type: "POST",
			url: "php/get_data.php",
			data:{
				'offset': flag,
				'limit': 3
			},
			success: function(data){
				$('.append_articles').append(data);
				flag += 3;
			}
		});
	});
});
