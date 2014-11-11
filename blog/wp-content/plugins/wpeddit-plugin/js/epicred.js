jQuery(document).ready(function ($) {
	      
   $(".arrow").bind("click", function () { 
   	
   	var logged = window.loggedin;
   	if(logged == 'false'){
   		return false;
   	}
   	
   	var like = $(this).attr("data-red-like");
   	var id = $(this).attr("data-red-id");
   	var curr = $(this).attr("data-red-current");
   	
   	
   	
   	if(like == 'up'){
   		$(this).removeClass("up").addClass("upmod");
   		$(".arrow-down-" + id).removeClass("downmod").addClass("down");
   		$(".score-" + id).removeClass("unvoted").addClass("likes");
   		$(".score-" + id).removeClass("dislikes").addClass("likes");
   		var vote = 1;
   	}
   	if(like == 'down'){
   		$(this).removeClass("down").addClass("downmod");
   		$(".arrow-up-" + id).removeClass("upmod").addClass("up");
   		$(".score-" + id).removeClass("unvoted").addClass("dislikes");
   		$(".score-" + id).removeClass("likes").addClass("dislikes");
   		var vote = -1;
   	}
        
        var j = {
            action: "epicred_vote",
            poll: id,
            option: vote,
            current: curr,
        };
        
        var l = $.ajax({
            url: EpicAjax.ajaxurl,
            type: "POST",
            data: j,
            dataType: "json",
        });
        
        l.done(function (c) {
            var id = c.poll;
			$(".score-" + id).html(c.vote);
        });
        
        l.fail(function (d, c) {
            alert("Request failed: " + c)
        });
        
        return true
    });
    
    
     $('#thumbnail').change(function() {
     var thumb = $('#thumbnail').val();
	 $('#thumbprev').html("<img src = " + thumb + ">");
	 });


    
    

});