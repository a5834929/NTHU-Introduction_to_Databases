$(function(){
    loadPage('anonymous', 0, '');
    clickTab();
});

function clickTab(){
    loadPage('anonymous', 0, '');
}

function loadPage(curTab, sort, res){
    $.ajax({
        url: 'loadPage.php',
        dataType: 'json',
        method: 'POST',
        data: {pageType:curTab, sort:sort, res:res},
        success: function(response){
            $('.'+curTab+'-content').html(response);
            searching();
            sorting();
        },
        beforeSend: function(){
            //loading
            $('.loading-content').removeClass('hidden');
        },
        complete: function(){
            //hide loading
            $('.loading-content').addClass('hidden');
        },
        error: function(error){
            console.log(error);
        }
    })
}

function sorting(){
    
    $('#asc-btn').click(function(){
        var type=$(this).attr('value'); // 0:jobseeker before searching, 1:employer before searching
                                    // 2:jobseeker after searching,  3:employer after searching
        var pagetype='anonymous';
        var s;
        
        if(type==0) s=1; // before searching
        else  s=4; // after searching
        
        $.ajax({
            success: function(){
                loadPage(pagetype,s, '');
            },
            beforeSend: function(){
                //loading
                $('.loading-content').removeClass('hidden');
            },
            complete: function(){
                //hide loading
                $('.loading-content').addClass('hidden');
            },
            error: function(error){
                console.log(error);
            }
        })
    });
    
    $('#desc-btn').click(function(){
       var type=$(this).attr('value'); // 0:jobseeker before searching, 1:employer before searching
                                    // 2:jobseeker after searching,  3:employer after searching
        var pagetype='anonymous';
        var s;
        
        if(type==0) s=2; // before searching
        else  s=5; // after searching

        $.ajax({
            success: function(){
                loadPage(pagetype,s, '');
            },
            beforeSend: function(){
                //loading
                $('.loading-content').removeClass('hidden');
            },
            complete: function(){
                //hide loading
                $('.loading-content').addClass('hidden');
            },
            error: function(error){
                console.log(error);
            }
                
        })
    });
}

function searching(){
    $('#search').click(function(){
        var occupation = $("#occupation").find(":selected").val();
        var location = $("#location").find(":selected").val();
        var work = $("#working_time").find(":selected").val();
        var education = $("#education").find(":selected").val();
        var salary = $("#search_salary").find(":selected").val();
        var experience = $("#experience").find(":selected").val();
        var pagetype='anonymous';

        $.ajax({
            url: 'search.php',
            dataType: 'json',
            method: 'POST',
            data: {occupation:occupation, location:location, work:work, 
                    education:education, salary:salary, experience:experience},
            success: function(response){
                loadPage(pagetype,3,response);
            },
            beforeSend: function(){
               
            },
            complete: function(){
                //hide loading
            },
            error: function(error){
                console.log(error);
            }
        })
    });
  
}

