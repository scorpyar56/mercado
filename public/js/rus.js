
$(document).ready(function ()
{
	$("#phone").on("click",function(){
		var oldValue = $("#phone").val();
		if(oldValue == ""){
			$("#phone").focus().val("+245").val();
		}
	});
});

