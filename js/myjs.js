   (function($){ 
   $(window).load(function(){
      console.log("test");
   });
            $(document).ready(function() {
  $.ajaxSetup({ cache: true });
  $.getScript('https://connect.facebook.net/en_US/sdk.js', function(){
    FB.init({
      appId: '305740536627239',
      version: 'v2.7' // or v2.1, v2.2, v2.3, ...
    });     
    $('#loginbutton,#feedbutton').removeAttr('disabled');
                    FB.getLoginStatus(function(response) {
  if (response.status === 'connected') {
    console.log('Logged in.');
  }
  else {
      console.log('Logged out.');
    FB.login(function(response) {
  // handle the response
}, {scope: 'public_profile,email,manage_pages,publish_pages'});
  }
                     //   FB.login();
  });

});
                
});
})(jQuery);