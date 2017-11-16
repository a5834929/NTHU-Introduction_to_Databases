
$(function(){
	loadRegisterPage();
});

function loadRegisterPage(){
	$.ajax({
        url: 'registerPage.php',
        dataType: 'json',
        success: function(response){
        	$('#employer-register').html(response[0]);
        	$('#jobseeker-register').html(response[1]);
        },
        beforeSend: function(){
            //loading
        },
        complete: function(){
            //hide loading
        },
        error: function(error){
            console.log(error);
        }
    })
}




