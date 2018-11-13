
var caruseldata = [];
var all_donation_is_scruiilng = false;
var canBeLoaded = true;
var count = 0;
var counter;
// this param allows to initiate the AJAX call only if 
jQuery( document ).ready(function() {
   // debugger;
  
if ( jQuery( "#timer" ).length ) {
    count = parseInt(jQuery("#timer").html());
    counter = setInterval(timer, 1000);
       }
    if(typeof donations != "undefined")
    setInterval(get_data_from_db, 10000); //1000 will  run it every 1 second
jQuery('.short').keyup(function() {
    debugger;
    if(this.value.length > 80){
        jQuery(this).css("background-color","#ff00001f");
        this.setCustomValidity('too many words');
    }
    else{
     
            jQuery(this).css("background-color","#e0e0e0");

       this.setCustomValidity('');

    }
    
   // console.log(this.value.length);
});
    jQuery("input[name='start']").change(function(e){
        var val = jQuery(this).val();
       //  var startdate = new Date(val);
        var tomorrow = new Date(val);
       tomorrow.setDate(tomorrow.getDate() + 1);
var dd = tomorrow.getDate();
var mm = tomorrow.getMonth() + 1; //January is 0!
var yyyy = tomorrow.getFullYear();
 if(dd<10){
        dd='0'+dd
    } 
    if(mm<10){
        mm='0'+mm
    } 

tomorrow = yyyy+'-'+mm+'-'+dd;
        
      jQuery("input[name='end']").attr("min",tomorrow);
      jQuery("input[name='end']").attr("value",'');
      
    });
	jQuery("#change_lang").change(function(e) {
		var lang = getUrlParameter('lang');
		var url = window.location.href;
		
		if(typeof lang === "undefined")
			url = url + "&lang=" + jQuery(e.target).find("option:selected").val();
		else if(lang === '')
			 url = url.replace("&lang=", "&lang=" + jQuery(e.target).find("option:selected").val());
		else 	url = url.replace("&lang="+lang, "&lang=" + jQuery(e.target).find("option:selected").val());
	  window.location.href = url;
		});
 jQuery("#checkout_form").closest("form").on("submit", function(e){
//	jQuery("#make_new_donation").click(function(e) {
     debugger;
		var form = jQuery(this).closest("form");
			e.stopPropagation();
			e.preventDefault();
			
		var post_id = form.find("input[name='post_id']").val();
		var amount = form.find("input[name='amount']").val();
		var currency = form.find("select[name='currency'] option:selected").val();
		var name = form.find("input[name='name']").val();
		var email = form.find("input[name='email']").val();
		var description = form.find("input[name='description']").val();
	    var doner_description = form.find("input[name='doner_description']").val();
        var lang = form.find("input[name='lang']").val();
       
        jQuery.ajax({
                type: 'POST',
                 dataType: 'json',
             crossDomain: true,
                url: "/wp-admin/admin-ajax.php",
                
                data: { action: 'make_donaction_ajax',
                       lang: lang,
					   post_id: post_id,
                       amount: amount,
                       currency: currency,
                       name: name,
                       email: email,
                       description: description,
                       doner_description: doner_description
                },       
                success: function (data) {
                    debugger;
                    form.find("input").attr("readonly",true);
					 
                  //  window.location.href = data['output']
					//var iframe = "<iframe style=\"overflow: visible; id=\"payment\"  src=\""+data['output']+"\" scrolling=\"no\"></iframe>";
			    //	form.append(iframe);
                }
              
            });
		
	});
	jQuery("#add_donation").click(function(e) {
		e.stopPropagation();
		e.preventDefault();
		
		
		var donation_form = jQuery("#checkout_form");
		donation_form.removeClass("unactiv");
		var form = jQuery(this).closest("form");
		
		var post_id = form.find("input[name='post_id']").val();
		var amount = form.find("input[name='fix_amount']:checked").val();
		if(amount == '')
		var amount = form.find("input[name='other_amount']").val();
		var currency = form.find("input[name='currency']").val();
		 jQuery([document.documentElement, document.body]).animate({
        scrollTop: donation_form.offset().top
    }, 2000);
		
		donation_form.find("input[name='post_id']").val(post_id);
		donation_form.find("input[name='amount']").val(amount);
	    donation_form.find("select[name='currency'] option[value='"+currency+"']").attr("selected",true);
	       
		});
jQuery("#addea_donation").click(function(e){

    //get_added_donation
    e.preventDefault();
    e.stopPropagation();
    get_data_from_db();
});
jQuery(".all_donation").scroll(function(e){
  
      get_data_from_db(true); 
});
jQuery(".donation_form label").click(function() {
	///
	if(jQuery(this).hasClass("donation_form_other")){
	
	//	jQuery(this).removeClass("hidden");
	jQuery(this).closest("form").find(".other").removeClass("hidden");
	jQuery(this).addClass("hidden");
		jQuery(this).closest("form").find(".other").focus();
		}
	else{
		jQuery(this).closest("form").find(".other").addClass("hidden");
	    jQuery(".donation_form_other").removeClass("hidden");
	}
	});
	jQuery('.owl-carousel').each(function( index ) {
		//var settings = jQuery(this).attr("data-carusel");
		var settings = get_carusel_settings(jQuery(this));
		var cruesel = jQuery(this).owlCarousel({
		rtl:jQuery("body").hasClass("rtl"),
	    lazyLoad: true,
			autoplayTimeout: 2500,
			autoplay: true,
			autoplayHoverPause: true,
			dots: false,
		nav: settings['nav'],
		 loop:false,
        margin: settings['margin'] || 10,
	    responsive:{
        0:{
            items:settings.responsive.mobile || 2
        },
        600:{
            items:settings.responsive.tablet || 2
        },
        1000:{
             items:settings.responsive.desktop || 3
        }
		},
        onChanged: change_this_slide
        
			
	});
       jQuery(this).closest(".crsl").find('.customNextBtn').click(function() {
          // debugger;
           var next = jQuery(this).closest(".crsl").find(".owl-next");
           next.click();
       
        });
        jQuery(this).closest(".crsl").find('.customPrevBtn').click(function() {
           // debugger;
            var prev = jQuery(this).closest(".crsl").find(".owl-prev");
           prev.click();
     
              });
  	});	
});

jQuery('.owl-carousel').on('changed.owl.carousel', function(event) {
	debugger;
    var element   = jQuery(event.target);
	element.removeClass("first");
 
   
})
jQuery('.circle_progress').each(function( index ) {
	set_circle(jQuery(this));

});
function change_this_slide(event){
                var element   = jQuery(event.target);

      if(element.closest(".crsl").find(".owl-nav").length == 0){
          element.closest(".crsl").find('.customPrevBtn').css("display","none");
          element.closest(".crsl").find('.customNextBtn').css("display","none");
          return true;
                }
    if(!element.closest(".crsl").find(".owl-nav").hasClass("disabled")){
    element.closest(".crsl").find('.customPrevBtn').css("display","block");
    element.closest(".crsl").find('.customNextBtn').css("display","block");
    }
              

            var prev = element.find(".owl-prev");
           if(prev.hasClass("disabled"))
               element.closest(".crsl").find('.customPrevBtn').addClass("disabled");
          else    element.closest(".crsl").find('.customPrevBtn').removeClass("disabled");
            var next = element.find(".owl-next");
           if(next.hasClass("disabled"))
               element.closest(".crsl").find('.customNextBtn').addClass("disabled");
          else    element.closest(".crsl").find('.customNextBtn').removeClass("disabled");
 
}
function get_data_from_db(after){
   
    after = after || false;
    	var ids = donations.ids;
	    var term = donations.term;
       var post_id = donations.pid;
       if(!canBeLoaded)
        return;
               jQuery.ajax({
                type: 'POST',
                 dataType: 'json',
                   crossDomain: true,
                url: "/wp-admin/admin-ajax.php",
                
                data: { action: 'get_added_donation',
                       pid: post_id,
					   ids: ids,
                       term: term,
                       scrull: after,
                       date: donations.date
                       
                },  
                   beforeSend: function( xhr ){
					// you can also add your own preloader here
					// you see, the AJAX call is in process, we shouldn't run it again until complete
					canBeLoaded = false; 
				},
                success: function (data) {
                //    debugger;
                    if(data.new === true){
                	 
                    if(after)
                    jQuery(".all_donation").append("<div class=\"new_content\" style=\"display: none;\"></div>");
                    else
                    jQuery(".all_donation").prepend("<div class=\"new_content\" style=\"display: none;\"></div>");
                   
                   
                        
					for (i = 0; i < data.posts.length; i++) { 
                        jQuery(".new_content").prepend(data.posts[i]);
                    }
                    jQuery(".new_content").fadeIn( "slow");
                    jQuery(".new_content").removeClass( "new_content");
                    donations.date  = data.date;
                    donations.ids = donations.ids.concat(data.ids); // Merges both arrays
               
                    
                    jQuery("#amount_donted").find("#amount").html(data.status.donated);
                        var procent = data.status.donated / data.status.amount * 100;
                        procent = procent.toFixed(2);
        //    var roundedString = num.toFixed(2);
                   jQuery("#donaters").html(data.status.donaters);
        var procent_width = procent;
            if(procent > 100)
                 procent_width = 100;    
                         jQuery("#pro_amount").html(procent);
                      jQuery( ".progres_pr" ).animate({
                          width: procent_width + "%"
                      }, 500);
                   
                    }
                    	canBeLoaded = true; 
				
                }
              
            });
	
}
var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};
function set_circle(trgt){
	 var current = trgt;
	 current.circleProgress({
		 thickness: 10,
    value: current.attr("data-value"), 
    size: current.attr("data-progres-size"),
    fill: { color: current.attr("data-fill-color") } 
  }).on('circle-animation-progress', function(e, v) {
		
	v =	 trgt.attr("data-value");
		var  color = jQuery(this).attr("data-text-color");
		 if( typeof trgt.data('circle-progress') == "undefined")
			 return;
      var obj = trgt.data('circle-progress');
      ctx = obj.ctx,
      s = obj.size,
      sv = (100 * v).toFixed(),
      fill = obj.arcFill;

  ctx.save();
  ctx.font = "bold " + s / 3 + "px sans-serif";
  ctx.fillStyle = color; 
  ctx.textAlign = 'center';
  ctx.textBaseline = 'middle';
//  ctx.fillStyle = fill;
  ctx.fillText(sv+"%", s / 2, s / 2);
  ctx.restore();
});
 // console.log( index + ": " + $( this ).text() );
}
function get_carusel_settings(trgt){
	
	var crusel = window[trgt.data("carusel")];
	return crusel['settings'];
}
function timer() {
    count = count - 1;
    

    var seconds = count % 60;
    var minutes = Math.floor(count / 60);
    var hours = Math.floor(minutes / 60);
    
    minutes %= 60;
    hours %= 60;
    if (count === 0) {
      var days =  parseInt(jQuery("#days").html());
        if(days > 0){
            days = days - 1;
            jQuery("#days").html(days);
            var seconds = 59;
            var minutes = 59;
            var hours = 23;
  
            count = 86399;
        }
        else {
                   
        clearInterval(counter);
        return;
     
        }
    }

    document.getElementById("timer").innerHTML = hours + ":" + minutes + ":" + seconds ; // watch for spelling
}

