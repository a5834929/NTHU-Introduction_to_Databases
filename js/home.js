var curPage= "profile";
var prePage = "profile";

$(function(){
    loadAllPages();
    changePage(prePage, curPage);
    clickTab(prePage, curPage);
});

function initToolTip(){
    $('[data-toggle="tooltip"]').tooltip();
}

function loadAllPages(){
    loadPage('profile', 0, '');
    if($('.resume-list-content').length)
        loadPage('resume-list', 0, '');
    if($('.recruit-list-content').length)
        loadPage('recruit-list', 0, '');
    if($('.appliant-list-content').length)
        loadPage('appliant-list', 0, '');
    if($('.job-list-content').length)
        loadPage('job-list', 0, '');
    if($('.favorite-list-content').length)
        loadPage('favorite-list', 0, '');
}

function clickTab(prePage, curPage){
    $(".nav").children("li").click(function(){
        var curTab = $(this).children("a").attr("id");
        if(curTab!=curPage){
            $("#"+curPage).parent().removeClass("active");
            $("#"+curTab).parent().addClass("active");
            prePage = curPage;
            curPage = curTab;
            changePage(prePage, curPage);
        }
        else loadPage(curPage, 0, '');
    });
}

function changePage(prePage, curPage){
    $('.'+prePage+"-content").addClass('hidden');
    $('.'+curPage+"-content").removeClass('hidden');
}

function loadPage(curTab, sort, res){
    $.ajax({
        url: 'loadPage.php',
        dataType: 'json',
        method: 'POST',
        data: {pageType:curTab, sort:sort, res:res},
        success: function(response){
            $('.'+curTab+'-content').html(response);

            if(curTab=='profile')
                editProfile();
            if(curTab=='recruit-list'){
                searching();
                sorting();
                loadRecruitPage();
            }
            if(curTab=='appliant-list')
                hire();
            if(curTab=='job-list'){
                searching();
                sorting();
                addDelApply();
                addFavorite();
                initToolTip();
            }
            if(curTab=='favorite-list')
                delFavorite();
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

function loadRecruitPage(){
    $('#recruit-btn').click(function(){
        $.ajax({
            url: 'recruitPage.php',
            dataType: 'json',
            method: 'POST',
            data: {recType:0},
            success: function(response){
                $('.modal-content').html(response);
                $('.modal-content').slideDown();
                addNewJob();
            },
            beforeSend: function(){
                $('.modal-content').html('');
                $('.modal-content').hide();
            },
            complete: function(){
                //hide loading
            },
            error: function(error){
                console.log(error);
            }
        })

    });
    
    $('.edit-recruit-btn').click(function(){
        var edit_id = $(this).attr('value');
        $.ajax({
            url: 'recruitPage.php',
            dataType: 'json',
            method: 'POST',
            data: {recType:1, edit:edit_id},
            success: function(response){
                $('.modal-content').html(response);
                $('.modal-content').slideDown();
                editRecruit();
            },
            beforeSend: function(){
                $('.modal-content').html('');
                $('.modal-content').hide();
            },
            complete: function(){
                //hide loading
            },
            error: function(error){
                console.log(error);
            }
        })
    });
    $('.recruit-delete-btn').click(function(){
        var edit_id = $(this).attr('value');
        $.ajax({
            url: 'recruitPage.php',
            dataType: 'json',
            method: 'POST',
            data: {recType:2, edit:edit_id},
            success: function(response){
                loadPage("recruit-list", 0, '');  
                loadPage("appliant-list", 0, '');             
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

function addNewJob(){
    $('#add-job').click(function(){
        var sal = $('#sal').val();

        if($.isNumeric(sal)){
            var occ = $("#occ").find(":selected").val();
            var loc = $("#loc").find(":selected").val();
            var wrt = $("#wrt").find(":selected").val();
            var edu = $("#edu").find(":selected").val();
            var exp = $("#exp").find(":selected").val();

            $.ajax({
                url: 'recruit.php',
                dataType: 'json',
                method: 'POST',
                data: { occupation:occ,
                        location:loc,
                        working_time:wrt,
                        education:edu,
                        experience:exp,
                        salary:sal},
                success: function(response){
                    loadPage('recruit-list', 0, '');               
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
        }else{
            alert("Please enter number for salary");
            $('#sal').val('');
        }
    });
}

function editProfile(){
    $('#edit').click(function(){
        var phone = $("#profile-phone").text();
        var email = $("#profile-email").text();
        var education = $("#profile-education").attr("value");
        var education_text = $("#profile-education").text();
        var age = $("#profile-age").text();
        var salary = $("#profile-salary").text();

        $.ajax({
            url: 'editProfile.php',
            dataType: 'json',
            method: 'POST',
            data: { proEdit:0, 
                    education:education},
            success: function(response){
                $('#edit').addClass('hidden');
                $('#save-cancel').removeClass('hidden');

                $("#profile-education").html(response);
                $("#profile-phone").html('<input type="text" name="profile-phone" value="'+phone+'">');
                $("#profile-email").html('<input type="text" name="profile-email" value="'+email+'">');
                $("#profile-age").html('<input type="text" name="profile-age" value="'+age+'">');
                $("#profile-salary").html('<input type="text" name="profile-salary" value="'+salary+'">');
                
                saveProfile();
                cancelEdit(phone, email, education_text, age, salary);
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
    });
}

function saveProfile(){
    $('#save').click(function(){
        var phone = $("#profile-phone input").val();
        var email = $("#profile-email input").val();
        var education = $("#profile-education").find(":selected").index();
        var education_text = $("#profile-education").find(":selected").text();
        var age = $("#profile-age input").val();
        var salary = $("#profile-salary input").val();

        $.ajax({
            url: 'editProfile.php',
            dataType: 'json',
            method: 'POST',
            data: { proEdit:1, 
                    phone:phone,
                    email:email,
                    education:education,
                    age:age,
                    salary:salary},
            success: function(response){
                $('#save-cancel').addClass('hidden');
                $('#edit').removeClass('hidden');

                $("#profile-phone").html(phone);
                $("#profile-email").html(email);
                $("#profile-education").html(education_text);
                $("#profile-age").html(age);
                $("#profile-salary").html(salary);
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
    });
}

function cancelEdit(phone, email, education, age, salary){
    $('#cancel').click(function(){
        $.ajax({
            success: function(){
                $('#save-cancel').addClass('hidden');
                $('#edit').removeClass('hidden');

                $("#profile-phone").html(phone);
                $("#profile-email").html(email);
                $("#profile-education").html(education);
                $("#profile-age").html(age);
                $("#profile-salary").html(salary);     
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
    });
}

function addDelApply(){
    $('.btn-apply').click(function(){
        var recruit_id = $(this).attr('value');
        var addType = 0;

        if($(this).hasClass('add-apply'))
            addType = 0;
        else if($(this).hasClass('del-apply'))
            addType = 2;

        $.ajax({
            url: 'applyFavorite.php',
            dataType: 'json',
            method: 'POST',
            data: { addType:addType,
                    recruit_id:recruit_id},
            success: function(response){
                $('.btn-apply[value='+recruit_id+']').text(response[0]).removeClass(response[1]).addClass(response[2]);
                if(addType==0){
                    $('.btn-apply[value='+recruit_id+']').attr("data-toggle", "tooltip");
                    $('.btn-apply[value='+recruit_id+']').attr("data-placement", "left");
                    $('.btn-apply[value='+recruit_id+']').attr("data-original-title", "Wait for reply or click to cancel");
                    initToolTip();
                }else{
                    $('.btn-apply[value='+recruit_id+']').attr("data-toggle", "");
                    $('.btn-apply[value='+recruit_id+']').attr("data-placement", "");
                    $('.btn-apply[value='+recruit_id+']').attr("data-original-title", "");
                }
  
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
    });
}

function addFavorite(){
    $('.btn-favor').click(function(){
        var recruit_id = $(this).attr('value');

        if($(this).hasClass('add-favor')){
            $.ajax({
                url: 'applyFavorite.php',
                dataType: 'json',
                method: 'POST',
                data: { addType:1,
                        recruit_id:recruit_id},
                success: function(response){
                    $('.add-favor[value='+recruit_id+']').text(response).removeClass('btn-favor').addClass('btn-static');
                    loadPage('favorite-list', 0, '');
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
    });
}

function delFavorite(){
    $('.btn-favor').click(function(){
        var recruit_id = $(this).attr('value');
        
        if($(this).hasClass('del-favor')){
            $.ajax({
                url: 'applyFavorite.php',
                dataType: 'json',
                method: 'POST',
                data: { addType:3,
                        recruit_id:recruit_id},
                success: function(response){
                    $('.add-favor[value='+recruit_id+']').text(response).removeClass('btn-static').addClass('btn-favor');
                    loadPage('favorite-list', 0, '');
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
    });
}

function sorting(){
    
    $('#asc-btn').click(function(){
        var type=$(this).attr('value'); // 0:jobseeker before searching, 1:employer before searching
                                    // 2:jobseeker after searching,  3:employer after searching
        var pagetype,s;
        if(type==0 || type==2 ) pagetype='job-list';
        else if(type==1 || type==3)        pagetype='recruit-list';
        
        if(type==0 || type==1) s=1; // before searching
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
        var pagetype,s;
        if(type==0 || type==2 ) pagetype='job-list';
        else if(type==1 || type==3)        pagetype='recruit-list';
              
       if(type==0 || type==1) s=2; // before searching
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

function hire(){
    $('.btn-hire').click(function(){
        var recuit_id = $(this).attr('value');
        $.ajax({
            url: 'recruitPage.php',
            dataType: 'json',
            method: 'POST',
            data: {recType:2, edit:recuit_id},
            success: function(response){
                loadPage("recruit-list", 0, '');
                loadPage("appliant-list", 0, '');               
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

function searching(){
    $('#search').click(function(){
        var occupation = $("#occupation").find(":selected").val();
        var location = $("#location").find(":selected").val();
        var work = $("#working_time").find(":selected").val();
        var education = $("#education").find(":selected").val();
        var salary = $("#search_salary").find(":selected").val();
        var experience = $("#experience").find(":selected").val();
        
        var type = $(this).attr('value');
        
        if(type==0) var pagetype='job-list';
        else        var pagetype='recruit-list';
        
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

function editRecruit(){
  $('#save-edit').click(function(){
      var edit = $(this).val();
      var primary_key = $("#id").val();
      var occupation = $("#occ").find(":selected").val();
      var location = $("#loc").find(":selected").val();
      var work = $("#work").find(":selected").val();
      var education = $("#edu").find(":selected").val();
      var salary = $("#sal").val();
      var experience = $("#exp").find(":selected").val();
      
      $.ajax({
          url: 'editRecruit.php',
          dataType: 'json',
          method: 'POST',
          data: {
              primary_key:primary_key, edit:edit, occupation:occupation, location:location, work:work, 
              education:education, salary:salary, experience:experience},
          success: function(response){
            loadPage("appliant-list", 0, '');
            loadPage("recruit-list", 0, '');
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
