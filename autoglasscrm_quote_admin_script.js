jQuery(document).ready(function($) {
    
	
	var isGettingToken = false;
    $('.agc_token_page_wrap input[name="agc_submit"]').on('click', function() {
		if ( isGettingToken ){
			return;
		}
		
		var email = $('.agc_token_page_wrap input[name="agc_email"]').val().trim();
		var password = $('.agc_token_page_wrap input[name="agc_password"]').val().trim();
		
		if(!email.length) {
			quote_token_error('Please enter your email.');
			return;
		}
		
		isGettingToken = true;
		$('.agc_token_page_wrap input[name="agc_submit"]').val("Processing...");
		getAccountPrefix(email).done(function(prefix) {
            if(!prefix) {
                quote_token_error('We\'re sorry, the email you entered is not associated with any AutoGlassCRM account.');
				isGettingToken = false;
				$('.agc_token_page_wrap input[name="agc_submit"]').val("Submit");
                return false;
            } else {
                getAGCToken(prefix, email, password).done(function(data){
                	console.log(data);
					isGettingToken = false;
					$('.agc_token_page_wrap input[name="agc_submit"]').val("Submit");
										
					if ( typeof data.token !== 'undefined' ){
						window.location.reload();
					}
					else{
						quote_token_error(data.message);
					}
					
				}).fail(function(){
					isGettingToken = false;
					$('.agc_token_page_wrap input[name="agc_submit"]').val("Submit");
					quote_token_error('We\'re sorry, we couldn\'t get user token.');
				});
            }
        }).fail(function() {
			isGettingToken = false;
			$('.agc_token_page_wrap input[name="agc_submit"]').val("Submit");
            quote_token_error('We\'re sorry, there was a temporary error with our login portal. If you know your account URL prefix, you can go directly to your own login page by typing it in your browser address bar. For example, for URL prefix "glassshop", go to glassshop.autoglasscrm.com.');
        });
    });

	$('.agc_token_page_wrap .agc_logout').on('click', logoutAGC);

	/**
	 * Call CRM to get URL prefix for this email
	 */
	function getAccountPrefix(email)
	{
		var url = $(".agc_token_page_wrap").attr("data-action1-url");
		return jQuery.ajax(url, {
			dataType: "json",
			data: {email: email}
		})
	}
	
	function getAGCToken(domainPrefix, email, password)
	{	
		var url = $(".agc_token_page_wrap").attr("data-action2-url");
		return jQuery.ajax(url, {
			dataType: "json",
			data: {prefix: domainPrefix, email: email, password: password}
		});
	}

	function logoutAGC()
	{
		var url = $(this).data('logout-url');

		jQuery.ajax(url).done(function() {
			window.location.reload();
		});
	}

	function quote_token_error(msg)
	{
		jQuery('.agc_token_error').remove();
		jQuery('.agc_token_success').remove();
		jQuery('.agc_token_page_wrap').prepend(
			jQuery('<p></p>')
			.text(msg)
			.addClass('agc_token_error')
		);
	}
	
	function quote_token_success(msg)
	{
		jQuery('.agc_token_error').remove();
		jQuery('.agc_token_success').remove();
		jQuery('.agc_token_page_wrap').prepend(
			jQuery('<p></p>')
			.text(msg)
			.addClass('agc_token_success')
		);
	}
});




