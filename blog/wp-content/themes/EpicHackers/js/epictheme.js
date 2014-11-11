jQuery(document).ready(function ($) {

  again = 30 - window.ehacklast;
  window.setInterval(function(){
     again = again - 1;
  }, 1000);

  $("#sidebar-submit").bind("click", function (e) { 
    e.preventDefault();
    $(".maincontent").toggleClass('content-push-sidebar');
  });

  $("#icon-toggle-main-menu").bind("click", function (e){
    e.preventDefault();
    if($('.mobile-mike').hasClass('hide')){
      $('.mobile-mike').addClass('show').removeClass('hide');
      $('.toppad').css('margin-top', '0px');
    }else{
      $('.mobile-mike').addClass('hide').removeClass('show');
      $('.toppad').css('margin-top', '45px');
    }
  })

  $('input#submit').addClass('btn');
  $('#aw-whats-new-submit').addClass('btn');

  $('#profile-group-edit-submit').addClass('btn').removeAttr('id');

  $('.button-primary').addClass('btn');
  $('#wp-submit').addClass('btn');

  $(document).ready(function(){
   $(".author-tool, .ac-tog").tooltip();   
});

$('.expand-all').click( function (e){
  e.preventDefault();
  if($(this).hasClass('expand-all-now')){
  $('.post-details').addClass('show').removeClass('hide');
  $(this).addClass('hide-all-now').removeClass('expand-all-now');
  $(this).html('Hide All');
  }else{
  $('.post-details').addClass('hide').removeClass('show');
  $(this).addClass('expand-all-now').removeClass('hide-all-now');
  $(this).html('Expand All');
  }
});

function validateURL(textval) {
      var urlregex = new RegExp(
            "^(http|https|ftp)\://([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&amp;%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(\:[0-9]+)*(/($|[a-zA-Z0-9\.\,\?\'\\\+&amp;%\$#\=~_\-]+))*$");
      return urlregex.test(textval);
    }




  $('.link-details').click( function (e){
      e.preventDefault();
      show = $(this).data('show');
      if($('.post-details-' + show).hasClass('show')){
        $('.post-details-' + show).addClass('hide').removeClass('show');
      }else{
        $('.post-details-' + show).addClass('show').removeClass('hide');
      }
  });

 $('#submit-question-btn').click( function (e) {
        e.preventDefault();
          if(again > 0){
          $('.toosoon').html('Sorry you are doing this too much. Try again in ' + again + ' seconds');
          return false;
          }
        $(this).attr( 'value', 'Working...');

    var data = $('#submit-question-form').serialize() + "&action=epicred_submit";
    
    var request = $.ajax({
      url: EpicAjax.ajaxurl,
      type: "POST",
      data: data,
      dataType: "json",
    });
    
    request.done(function(msg) {
      $(".maincontent").toggleClass('content-push-sidebar');
      window.location.href = msg.perma;
    });
    
    request.fail(function(jqXHR, textStatus) {
      alert( "Request failed: " + textStatus );
    });

        setTimeout( function() {
            $('#submit-article-btn, #submit-question-btn').attr( 'value', 'Submit');
        }, 5000);
    });


    $('#submit-article-btn').click( function (e) {
      e.preventDefault();
        if(again > 0){
          $('.toosoon').html('Sorry you are doing this too much. Try again in ' + again + ' seconds');
          return false;
        }
        $(this).attr( 'value', 'Working...');
        //lets make it AJAX
    var data = $('#submit-article-form').serialize() + "&action=epicred_submit";

    var form = $( "#submit-article-form" );
    form.validate();
    if(form.valid()){
      
    }else{
// show the modal onload
      $('#modal-content').modal({
          show: true
      });
      $(this).attr( 'value', 'Submit');
      return false;
    }

    
    var request = $.ajax({
      url: EpicAjax.ajaxurl,
      type: "POST",
      data: data,
      dataType: "json",
    });

    
    request.done(function(msg) {
      $(".maincontent").toggleClass('content-push-sidebar');
      $(".maincontent").toggleClass('content-push-sidebar');
      window.location.href = msg.perma;

       

    });
    
    request.fail(function(jqXHR, textStatus) {
      alert( "Request failed: " + textStatus );
    });




        setTimeout( function() {
            $('#submit-article-btn, #submit-question-btn').attr( 'value', 'Submit');
        }, 5000);
    });

});

