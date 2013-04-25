$(document).ready(function() {
  var flag = false;
  $('.tab-menu>nav>ul>li').click(function(){
    var x = $(this).data('name');
    if(x == 'add-article'){
        $('.add-article').show();
        $('.add-categories').hide();
    }else{
       $('.add-article').hide();
        $('.add-categories').show();
    }
  
    if(flag == false){
        $('li').removeClass('active');
    }
     $(this).addClass('active');
     flag = false;
  });

});