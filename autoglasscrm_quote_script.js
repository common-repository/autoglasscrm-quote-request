jQuery(document).ready(function($) {
	var isQuoting = false;
	
	// Glass Option
	$("form.autoglasscrm-quote select[name='glass_type']").change(function(e){
		var glassType = $(this).val();
		var glassOptionHtml = "";
		if ( glassType == "1" ){
			glassOptionHtml  = '<option value="0">Options...</option>';
			glassOptionHtml += '<option value="1">1 Piece (Car/Minivan/SUV/Truck)</option>';
			glassOptionHtml += '<option value="2">Rear Left (Van Only)</option>';
            glassOptionHtml += '<option value="3">Rear Right (Van Only)</option>';
		}
		else if ( glassType == "2" ){
			glassOptionHtml  = '<option value="0">Options...</option>';
			glassOptionHtml += '<option value="1">Front Left</option>';
			glassOptionHtml += '<option value="2">Front Right</option>';
			glassOptionHtml += '<option value="3">Rear Left</option>';
			glassOptionHtml += '<option value="4">Rear Right</option>';
			glassOptionHtml += '<option value="5">Other</option>';
		}
		else if ( glassType == "21" ){
			glassOptionHtml = "";
		}
		else if ( glassType == "3" ){
			glassOptionHtml  = '<option value="0">Options...</option>';
			glassOptionHtml += '<option value="1">Rear Left (Car/Minivan/SUV/Truck)</option>';
			glassOptionHtml += '<option value="2">Rear Right (Car/Minivan/SUV/Truck)</option>';
			glassOptionHtml += '<option value="3">Left (Van Only)</option>';
			glassOptionHtml += '<option value="4">Right (Van Only)</option>';
		}
		else if ( glassType == "19" ){
			glassOptionHtml = "";
		}
		else if ( glassType == "22" ){
			glassOptionHtml  = '<option value="0">Options...</option>';
			glassOptionHtml += '<option value="1">Front Left</option>';
			glassOptionHtml += '<option value="2">Front Right</option>';
		}
		else if ( glassType == "4" ){
			glassOptionHtml = "";
		}
		else if ( glassType == "23" ){
			glassOptionHtml  = '<option value="0">Options...</option>';
			glassOptionHtml += '<option value="1">1 Piece</option>';
            glassOptionHtml += '<option value="2">Left Side</option>';
            glassOptionHtml += '<option value="3">Right Side</option>';
			glassOptionHtml += '<option value="4">Both Sides</option>';
		}
		
		$("form.autoglasscrm-quote select[name='glass_option']").html(glassOptionHtml);
		if ( glassOptionHtml == "" ){
			$("form.autoglasscrm-quote select[name='glass_option']").addClass("hidden");
		}else{
			$("form.autoglasscrm-quote select[name='glass_option']").removeClass("hidden");
			$("form.autoglasscrm-quote select[name='glass_option']").val("0");
		}
		$("form.autoglasscrm-quote input[name='glass_other']").addClass("hidden");
		$("form.autoglasscrm-quote input[name='glass_other']").val("");
	});
	
	$("form.autoglasscrm-quote select[name='glass_option']").change(function(e){
		var glassType = $("form.autoglasscrm-quote select[name='glass_type']").val();
		var glassOption = $(this).val();
		
		if ( (glassType == 2 && glassOption == 5) ){
			$("form.autoglasscrm-quote input[name='glass_other']").removeClass("hidden");
		}
		else{
			$("form.autoglasscrm-quote input[name='glass_other']").addClass("hidden");
		}
		$("form.autoglasscrm-quote input[name='glass_other']").val("");
	});
	
	

	// Run VIN Search
	var isSearchingVIN = false;
	var vindecoderData;
	var lastInvalidVIN = "";
	var ffIns;
	$("#btnRunVIN").click(function(e){
		if ( isSearchingVIN == true ){
			return;
		}
				
		var first_name = $('.autoglasscrm-quote input[name="first_name"]').val().trim();
		var last_name = $('.autoglasscrm-quote input[name="last_name"]').val().trim();
		var phone = $('.autoglasscrm-quote input[name="phone"]').val().trim();
		var email = $('.autoglasscrm-quote input[name="email"]').val().trim();
		var address = $('.autoglasscrm-quote input[name="address"]').val().trim();
		var city = $('.autoglasscrm-quote input[name="city"]').val().trim();
		var state = $('.autoglasscrm-quote select[name="state"]').val();
		var zip = $('.autoglasscrm-quote input[name="zip"]').val().trim();
		var vehicle_vin = $('.autoglasscrm-quote input[name="vehicle_vin"]').val().trim();
		var is_vehicle_reg_plate_number_present = $('.autoglasscrm-quote input[name="vehicle_reg_plate_number"]').length > 0;
		var vehicle_reg_plate_number = '';

		if (is_vehicle_reg_plate_number_present) {
			vehicle_reg_plate_number = $('.autoglasscrm-quote input[name="vehicle_reg_plate_number"]').val().trim();
		}
		
		if ( first_name.length == 0 ){
			$(".vin_search_status").html("Please type first name.");
			return;
		}
				
		if ( phone.length == 0 ){
			$(".vin_search_status").html("Please type phone number.");
			return;
		}
		
		if ( email.length == 0 || validateEmail(email) == false ){
			$(".vin_search_status").html("Please type valid email.");
			return;
		}

		if (is_vehicle_reg_plate_number_present) {
			if ( vehicle_vin.length == 0 && vehicle_reg_plate_number.length == 0 ){
				$(".vin_search_status").html("Please type VIN or Reg. plate number.");
				return;
			}
		} else {
			if ( vehicle_vin.length == 0 ){
				$(".vin_search_status").html("Please type Vehicle VIN.");
				return;
			}
		}
		
		$(".vin_search_status").html("");
		$("#btnRunVIN").html("See Below");
		
		
		isSearchingVIN = true;
		
		var should_validate_vin = 1;
		if ( lastInvalidVIN.length > 0 && lastInvalidVIN == vehicle_vin ){
			alert("We will confirm the VIN number later.");
			should_validate_vin = 0;
		}

		if (vehicle_vin.length == 0 && vehicle_reg_plate_number.length > 0) {
			should_validate_vin = 0;
		}

		if (!['CA', 'US', 'MX'].includes($('input[name="country_code"]').val())) {
			should_validate_vin = 0;
		}
		
		var glass_type = $("form.autoglasscrm-quote select[name='glass_type']").val();
		var glass_option = $("form.autoglasscrm-quote select[name='glass_option']").val();
		var glass_other = $("form.autoglasscrm-quote input[name='glass_other']").val();
		
		runVIN({"vin": vehicle_vin, "tag": vehicle_reg_plate_number,"glass_type": glass_type, "glass_option": glass_option, "glass_other": glass_other, "validate_vin": should_validate_vin})
			.done(function(data){
			isSearchingVIN = false;
			$('#btnRunVIN').html("Go to Step 2");
			if ( typeof data.success !== 'undefined' && data.success == 1){
				vindecoderData = JSON.parse(data.response);
				if ( vindecoderData.success !== 'undefined' && vindecoderData.success == 0 ){
					$(".vin_search_status").html(vindecoderData.message);
				}
				else{
					lastInvalidVIN = "";
					populateVINData(data.account_prefix);
					
					
					try{
						var jsonVehicleData = JSON.parse(data.response);
						$("#formUploadPhoto input[name='vehicle_id']").val("");
						$("#formUploadPhoto input[name='vehicle_year']").val(jsonVehicleData.year);
						$("#formUploadPhoto input[name='vehicle_make']").val(jsonVehicleData.make);
						$("#formUploadPhoto input[name='vehicle_model']").val(jsonVehicleData.model);
						$("#formUploadPhoto input[name='vehicle_body']").val(jsonVehicleData.body);
					}catch(e){
					}
					
					var glass_type_text = $("form.autoglasscrm-quote select[name='glass_type'] option:selected").text();
					var glass_option_text = $("form.autoglasscrm-quote select[name='glass_option'] option:selected").text()					
					
					var glass_type_string = "";
					if ( glass_type != "" ){
						glass_type_string = "Glass Type: " + glass_type_text;
					}
					if ( glass_option != "0" ){
						if ( glass_type_string == "" ){
							glass_type_string = "Glass Option: " + glass_option_text;
						}
						else{
							glass_type_string += ", Glass Option: " + glass_option_text;
						}
						
					}
					if ( glass_other != "" ){
						if ( glass_type_string == "" ){
							glass_type_string = "Glass Other: " + glass_other;
						}
						else{
							glass_type_string += ", Glass Other: " + glass_other;
						}
						
					}
					
					$("#formUploadPhoto input[name='first_name']").val(first_name);
					$("#formUploadPhoto input[name='last_name']").val(last_name);
					$("#formUploadPhoto input[name='phone']").val(phone);
					$("#formUploadPhoto input[name='email']").val(email);
					$("#formUploadPhoto input[name='address']").val(address);
					$("#formUploadPhoto input[name='city']").val(city);
					$("#formUploadPhoto input[name='state']").val(state);
					$("#formUploadPhoto input[name='zip']").val(zip);
					$("#formUploadPhoto input[name='glass_type']").val(glass_type_string);
					$("#formUploadPhoto input[name='possible_parts']").val("");
					
					$("#formUploadPhoto input[name='vehicle_vin']").val(vehicle_vin);
					
					$("#step3Wrapper").addClass("hidden");
					$("#chkNoPhoto").prop("checked", false);
				}
			}				
			else if ( typeof data.message !== 'undefined'){
				$(".vin_search_status").html(data.message);
				if ( typeof data.error_type !== 'undefined' && data.error_type == "invalid_vin" ){
					lastInvalidVIN = vehicle_vin;
				}
			}
			
		}).fail(function(){
			isSearchingVIN = false;
			$('#btnRunVIN').html("Go to Step 2");
			$(".vin_search_status").html('We\'re sorry, we couldn\'t search vin.');
		});
	});
	
	var isQuoteSubmitting = false;
	$("#btnQuoteSubmit").click(function(e){
		e.preventDefault();
		
		if ( isQuoteSubmitting ){
			return;
		}
		
		isQuoteSubmitting = true;
		$(".quote_submit_result_wrap").html("");
		$("#btnQuoteSubmit").html("Sending....");
		$("#formUploadPhoto").submit();
	});
	
	$("#formUploadPhoto").ajaxForm(function(data){
		isQuoteSubmitting = false;
		$("#btnQuoteSubmit").html("Submit");
		
		var jsonData = JSON.parse(data);
		if ( jsonData.error == 1 ){
			$(".quote_submit_result_wrap").html("<p style='color:#ff0000;'>" + jsonData.msg + "</p>");
		}else{
			var html = "";
			
			if ( jsonData.data.success == 0 ){
				html += "<p style='font-weight:bold;margin-top: 10px;line-height:1;'>" + jsonData.data.message + "</p>";
			}
			else{
				html += "<p style='font-weight:bold;margin-top: 10px;line-height:1;'>You have submitted quote successfully. You will receive an email or call back shortly.</p>";
				
				$(".widget_autoglasscrm_quote_widget form.autoglasscrm-quote").remove();
				$(".widget_autoglasscrm_quote_widget #formUploadPhoto").remove();
			}

			
			$(".quote_submit_result_wrap").html(html);
		}
		
	});
	
	// click Event from upload button
	$("#btnUploadPhoto").click(function(e){
		uploadPhoto();
	});
	
	$("#modalUploadPhotoTip .btn_ok").click(function(e){
		$("#modalUploadPhotoTip").addClass("hidden");
		var dataIndex = $("#modalUploadPhotoTip").data("index");
		$("#fileUpload_" + dataIndex).trigger("click");
	});
	
	// change event of file choose
	$(".upload_photo_row input[type='file']").change(function(){
		readPhotoURL(this);
	});
	
	// click Event from remove photo button
	$(".btn_remove_photo").click(function(e){
		var dataIndex = $(this).parent().data("index");
		$(this).parent().addClass("hidden");
		$(this).parent().find("input[type='file']").val("");
		
		updateStep3ButtonVisible();
		updateUploadPhotoButtonVisible();
	});
	
	$("#chkNoPhoto").change(function(e){
		updateStep3ButtonVisible();
		updateUploadPhotoButtonVisible();
	});
	
	$("#btnStep3").click(function(e){
		e.preventDefault();
		
		$("#step3Wrapper").removeClass("hidden");
		$("#btnStep3").addClass("hidden");
		
		
		if ( ffIns != null && ffIns.canFilter() ){
			$("#btnFeatureFilter").removeClass("hidden");
			$("#btnQuoteSubmit").addClass("hidden");
		}else{
			$("#btnFeatureFilter").addClass("hidden");
			$("#btnQuoteSubmit").removeClass("hidden");
			
			$("#messagePopup").removeClass("hidden");
			$("#messagePopup .modal_inner .modal_body").html("<p>We will use your VIN to determine which features your glass has.</p><p>Click the submit button to send in your request.</p>");
		}
				
		$(".vin_search_result .part_item").removeClass("hidden");
	});
	
	$("#btnFeatureFilter").click(function(e){
		e.preventDefault();
		
		if ( ffIns != null ){
			ffIns.showFilterModal();
		}
	});
	
	function uploadPhoto(){
		var hiddenRows = $(".upload_photo_row.hidden");
		if ( (checkVinHasHUDFeature()==true && hiddenRows.length > 0) || 
			 (checkVinHasHUDFeature()==false && hiddenRows.length > 1)
			){
			$("#chkNoPhoto").prop("checked", false);
			updateStep3ButtonVisible();
			
			var dataIndex = $(hiddenRows[0]).data("index");
			
			$("#modalUploadPhotoTip .modal_body .row").addClass("hidden");
			$("#modalUploadPhotoTip .modal_body .row[data-index='" + dataIndex + "']").removeClass("hidden");
			$("#modalUploadPhotoTip").data("index", dataIndex);
			
			
			if ( dataIndex == 2 ){
				if ( checkVinHasHUDFeature() == false ){
					$("#modalUploadPhotoTip .modal_body .row").addClass("hidden");
					$("#modalUploadPhotoTip .modal_body .row[data-index='3']").removeClass("hidden");
					$("#modalUploadPhotoTip").data("index", 3);
				}
			}
			
			$("#modalUploadPhotoTip").removeClass("hidden");
		}
	}
	
	// Send Help Request
	function sendHelpRequest(){
		
	}
	
	// return 1 or 0 (check parts has hud feature)
	function checkVinHasHUDFeature(){
		if ( vindecoderData == null || vindecoderData.parts == null ){
			return false;
		}
		
		var hasHudFeature = 0;
		$.each(vindecoderData.parts, function(key, value){
			$.each(value.features, function(key2, value2){
				if ( value2 == "1" && key2 == "hud" ){
					hasHudFeature = true;
				}
			});
		});
		return hasHudFeature;
	}
	
	// Update submit button's visibility
	function updateStep3ButtonVisible(){
		if (  $(".upload_photo_row").not(".hidden").length == 0 ){
			$("#btnStep3").addClass("hidden");
			$("#step3Wrapper").addClass("hidden");
		}
		else{
			$("#btnStep3").removeClass("hidden");
		}
		
		var checked = $('#chkNoPhoto').prop("checked");
		if ( checked ){
			$("#btnStep3").removeClass("hidden");
			resetPhotoRows();
		}else{
			$("#step3Wrapper").addClass("hidden");
		}
	}
	
	// Update upload button's visibility
	function updateUploadPhotoButtonVisible(){
		if ( (checkVinHasHUDFeature()==true && $(".upload_photo_row.hidden").length == 0) ||  
			 (checkVinHasHUDFeature()==false && $(".upload_photo_row.hidden").length <= 1)   ){
			$("#btnUploadPhoto").addClass("hidden");
		}
		else{
			$("#btnUploadPhoto").removeClass("hidden");
		}
	}
	
	// Read photo content and preview it on the page.
	function readPhotoURL(input) {
	  if (input.files && input.files[0]) {
		var reader = new FileReader();
		var dataIndex = $(input).parent().data("index");
		
		
		reader.onload = function(e) {
			$('#previewFileUpload_' + dataIndex).attr('src', e.target.result);		
		}
		
		reader.readAsDataURL(input.files[0]); // convert to base64 string
		
		$(".upload_photo_row[data-index='" + dataIndex + "']").removeClass("hidden");
		updateStep3ButtonVisible();
		updateUploadPhotoButtonVisible();
		uploadPhoto();
	  }
	}
	
	// Display vin part data
	function populateVINData(_accountPrefix){
		if ( vindecoderData == null || vindecoderData.parts == null ){
			
			return;
		}
		
		
		var resultHTML = "";
		
		$.each(vindecoderData.parts, function(key, value){
			
			var partData = value;
			var partHTML = "<div class='part_item' data-part-number='" + partData.part_number + "' data-part-id='" + partData.id + "'>";
			//partHTML += '<div class="part_item_header"><span class="partnum">Part Number: ' +  partData.part_number + '</span><i class="part_item_check"></i>';
			//partHTML += '</div>';
			partHTML += '<div class="part_item_description">' + partData.description + '</div>';
			
			partHTML += '<div class="part_item_photos">';
			for(var i=0;i<partData.photos.length;i++){
				partHTML += '<div class="part_item_photo">';
				partHTML += '<img src="' + partData.photos[i].url + '">';
				partHTML += '</div>';
			}
			partHTML += '</div>';
			
			partHTML += '<div class="part_item_videos">';
			for(var i=0;i<partData.videos.length;i++){
				partHTML += '<div class="part_item_video">';
				partHTML += '<iframe src="https://www.youtube.com/embed/' + partData.videos[i].playid + '"></iframe>';
				partHTML += '</div>';
			}
			partHTML += '</div>';
			
			partHTML += "</div>";
			
			
			resultHTML = resultHTML + partHTML;
			
		});
		
		if ( vindecoderData.parts.length == 0 ){
			resultHTML += "<p>There is no part data.</p>";
		}
		
		resultHTML += '<div class="hidden"><input id="chkNonMatch" type="checkbox" name="non_match" value=""><label for="chkNonMatch">Non Match</label></div>';
		resultHTML = "";
		$(".vin_search_result").html(resultHTML);
		$(".vin_search_result").removeClass("hidden");
		$(".upload_photo_wrap").addClass("hidden");
		
		$(".part_item_check").click(function(e){
			$(".part_item").removeClass("selected");
			$(this).parent().parent().addClass("selected");
			
			//$(".upload_photo_wrap").addClass("hidden");
			//$('#chkNonMatch').prop("checked", false);
		});
		
		$('#chkNonMatch').change(function(e){
			var checked = $('#chkNonMatch').prop("checked");
			if ( checked ){
				$(".part_item").removeClass("selected");
				$(".upload_photo_wrap").removeClass("hidden");
			}else{
				$(".upload_photo_wrap").addClass("hidden");
			}
			updateUploadPhotoButtonVisible();
			updateUploadPhotoButtonVisible();
		});
		
		$('#chkNonMatch').prop("checked", true);
		$(".upload_photo_wrap").removeClass("hidden");
		$(".quote_submit_result_wrap").html("");
		$("#btnQuoteSubmit").addClass("hidden");
		$(".upload_photo_row").addClass("hidden");
		$("#fileUpload_1").val("");
		$("#fileUpload_2").val("");
		$("#fileUpload_3").val("");
		$("#previewFileUpload_1").attr("src", "#");
		$("#previewFileUpload_2").attr("src", "#");
		$("#previewFileUpload_3").attr("src", "#");
		$("#btnUploadPhoto").removeClass("hidden");
		
		
		$([document.documentElement, document.body]).animate({
			scrollTop: ($(".upload_photo_wrap").offset().top - 100)
		}, 500);
		
		ffIns = new FeatureFilter(_accountPrefix);
		ffIns.load(vindecoderData.parts, "", "", "");
		
		if ( ffIns.canFilter() ){
			$("#btnFeatureFilter").removeClass("hidden");
			$("#btnQuoteSubmit").addClass("hidden");
		}else{
			$("#btnFeatureFilter").addClass("hidden");
			$("#btnQuoteSubmit").removeClass("hidden");
		}
	}
	
	// Reset photo rows
	function resetPhotoRows(){
		$(".upload_photo_row input[type='file']").val("");
		$(".upload_photo_row").addClass("hidden");
	}
	
	
			
	// Search VIN
	function runVIN(params)
	{
		var url = WPURLS.admin_url + 'admin-ajax.php?action=search_vin';
		return jQuery.ajax(url, {
			type: "POST",
			dataType: "json",
			data: params
		})
	}
	
	// Validate Email
	function validateEmail(email) {
		const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(String(email).toLowerCase());
	}
	
	function qutoeError(msg)
	{
		jQuery('.crm-quote-error').remove();
		jQuery('.crm-quote-success').remove();
		jQuery('.autoglasscrm-quote').prepend(
			jQuery('<p></p>')
			.text(msg)
			.addClass('crm-quote-error')
			.css('color', 'red')
		);
	}
	
	function qutoeSuccess(msg)
	{
		jQuery('.crm-quote-error').remove();
		jQuery('.crm-quote-success').remove();
		jQuery('.autoglasscrm-quote').prepend(
			jQuery('<p></p>')
			.text(msg)
			.addClass('crm-quote-success')
			.css('color', 'black')
		);
	}

});





