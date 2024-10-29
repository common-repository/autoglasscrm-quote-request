<style type="text/css">
    .widget_autoglasscrm_quote_widget .widget-title{
		text-align: center;
	}
	
    .agcrm-form-container {
        max-width: 1000px;
		margin: 0 auto;
    }
    .autoglasscrm-quote input[type=text],
    .autoglasscrm-quote input[type=password] {
        width: 100%;
    }
	.agcrm-form-container button{
		padding: 10px 15px;
		min-width: 150px;
	}
	
	.autoglasscrm-quote{
		display: flex;
		flex-wrap: wrap;
	}
	
	.form_row{
		width: 50%;
		padding-right: 15px;
		margin-bottom: 5px;
	}
	
	.form_row.full_width{
		width: 100%;
	}
	
	.autoglasscrm-quote .crm-quote-success, .autoglasscrm-quote .crm-quote-error{
		width: 100%;
	}
	
	.autoglasscrm-quote select{
		width: 100%;
		padding: 3px 10px;
	}
	
	.autoglasscrm-quote input{
		padding: 5px 10px;
	}
	
	.autoglasscrm-quote button{
		padding: 10px 15px;
	}
	
	.vin_search_wrap{
		display: flex;
		flex-wrap: wrap;
		margin-top: 50px;
	}
	
	.vin_search_wrap #btnRunVIN{
		padding: 10px 10px;
	}
	
	.vin_search_wrap input{
		padding: 10px 10px;
	}
	
	.vin_search_status{
		margin: 0;
		color: red;
	}
	
	.vin_search_result .part_item{
		background: #d6d6d6;
		margin-bottom: 10px;
		padding: 15px;
	}
	
	.vin_search_result .part_item .part_item_description{
		line-height: 1;
		font-size: 16px;
		margin-bottom: 10px;
		color: black;
	}
	
	.vin_search_result .part_item .part_item_videos{
		display: flex;
		flex-wrap: wrap;
		justify-content: space-between;
	}
	
	.vin_search_result .part_item .part_item_videos .part_item_video{
		width: calc(50% - 10px);
		position: relative;
	}
	
	.vin_search_result .part_item .part_item_videos .part_item_video iframe{
		border: none;
		width: 100%;
	}
	
	.part_item_photos{
		display: flex;
		flex-wrap: wrap;
		justify-content: space-between;
	}
	
	.part_item_photos .part_item_photo{
		width: calc(50% - 10px);
		position: relative;
	}
	
	.part_item_photos .part_item_photo img{
		width: 100%;
		max-width: 100%;
	}
	
	.part_item_header{
		line-height: 1;
		margin-bottom: 5px;
		font-weight: bold;
		font-size: 18px;
		position: relative;
		
	}
	
	.part_item_header .part_item_check{
		position: absolute;
		right: 10px;
		top: 0px;
		cursor: pointer;
		opacity: 0.2;
		color: red;
	}
	
	.part_item.selected .part_item_check{
		opacity: 1;
	}
	
	.part_item_header .part_item_check:after{
		content: '✔';
		position: absolute;
		left:0; top: 2px;
		width: 20px; 
		height: 20px;
		text-align: center;
		border: 1px solid #aaa;
		background: #f8f8f8;
		border-radius: 50%;
		box-shadow: inset 0 1px 3px rgba(0,0,0,.3);
	}
	
	#btnUploadPhoto{
		margin-bottom: 20px;
		padding: 10px 10px;
	}
	
	.upload_photo_row{
		background: #d6d6d6;
		margin-bottom: 10px;
		padding: 15px;
	}
	
	.btn_remove_photo{
		display: inline-block;
		margin-top: 10px;
		font-size: 16px;
	}
	
	#btnDetectShape{
		margin-bottom: 20px;
		padding: 10px 10px;
	}
	
	.detect_result_wrap p{
		margin-bottom: 5px;
	}
	
	.detect_result_wrap span{
		font-weight: bold;
		min-width: 110px;
		display: inline-block;
	}
	
	.hidden{
		display: none !important;
	}
	
	#messagePopup{
		display: flex;
		align-items: center;
		justify-content: center;
		background: rgba(0,0,0,0.4);
		position: fixed;
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		z-index: 99999;
	}
	
	#messagePopup .modal_inner{
		background: white;
		box-shadow: 0px 3px 10px rgba(0,0,0,0.2);
		width: 400px;
		max-width: 100%;
		padding: 30px 40px;
		max-height: 90%;
		overflow: auto;
		border-radius: 10px;
		text-align: center;
	}
	
	
	
	#modalUploadPhotoTip{
		display: flex;
		align-items: center;
		justify-content: center;
		background: rgba(0,0,0,0.4);
		position: fixed;
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		z-index: 99999;
		border-radius: 10px;
	}
	
	#modalUploadPhotoTip .modal_inner{
		background: white;
		box-shadow: 0px 3px 10px rgba(0,0,0,0.2);
		min-width: 350px;
		max-width: 350px;
		padding: 30px 40px;
		max-height: 80%;
		overflow: scroll;
	}
	
	#modalUploadPhotoTip .modal_bottom{
		text-align: center;
		padding-top: 10px;
		padding-bottom: 10px;
	}
	
	#modalUploadPhotoTip .modal_bottom .btn{
		padding: 10px 15px;
		min-width: 150px;
	}
	
	form.autoglasscrm-quote .required_mark{
		color: red;
		font-weight: bold;
	}
	
	#formUploadPhoto .btn{
		color: white;
		padding: 5px 15px;
		text-decoration: none;
		font-weight: bold;
		line-height: 1.6;
	}
	
	#btnStep3{
		display: inline-block;
		margin-top: 10px;
		
	}
	
	#featureFilterModal{
		display: none;
	}
	
	#featureFilterModal.modal-active{
		display: block;
		position: fixed;
		top: 0;
		bottom: 0;
		right: 0;
		left: 0;
		background-color: rgba(0,0,0,0.4);
		z-index: 99999;
	}
	
	#featureFilterModal .modal-content{
		max-width: 700px;
		overflow-y: auto;
		max-height: calc(100vh - 140px);
		position: relative;
		background-color: #fefefe;
		margin: 60px auto;
		padding: 20px;
		border: 1px solid #888;
		width: 80%;
		box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
		box-sizing: border-box;
		border-radius: 5px;
	}
	
	#featureFilterModal .modal-close-span{
		color: #aaa;
		display: inline-block;
		position: absolute;
		font-size: 28px;
		top: 10px;
		right: 14px;
		font-weight: bold;
		vertical-align: top;
		cursor: pointer;
	}
	
	
	#featureFilterModal .modal-title{
		font-size: 24px;
		text-align: center;
		margin-top: 10px;
	}
	
	
	
	.filter-question-box{
		background-color: #dae2ed;
		margin: 0px 0;
		padding: 1rem;
	}
	
	.filter-question-photos{
		display: flex;
		flex-wrap: wrap;
		justify-content: space-between;
		align-items: stretch;
		align-content: stretch;
	}
	
	.filter-question-photos .card{
		width: 300px;
		box-shadow: 0 1px 2px rgba(0,0,0,.1);
		padding: 1rem;
		margin: 1rem 0;
		background-color: #F2F5F9;
	}
	
	.filter-function-buttons{
		display: flex;
		justify-content: space-around;
		margin-top: 10px;
	}
	
	.filter-function-buttons button{
		padding: 10px 15px;
	}
	
	#featureFilterModal .modal-footer{
		text-align: center;
		margin-top: 20px;
	}
	
	#featureFilterModal .modal-footer button{
		padding: 10px 10px;
		background: transparent;
		color: black;
		border: 1px solid black;
	}
	
	#btnQuoteSubmit{
		padding:
	}
</style>

<?php $addressLocalizeHelper = new \QuoteHelpers\AddressLocalize($countryCode); ?>

<div class="agcrm-form-container">

	<div class="modal" id="featureFilterModal">
		<div class="modal-content">
			<span class="modal-close-span" onclick="closeFeatureFilterModal();">×</span>
			<div class="modal-header">
				<h2 class="modal-title">Filter Parts by Feature</h2>
			</div>
			
			<div class="modal-body">
				<div id="vd-filter-questions">
				</div>
			</div>
			
			<div class="modal-footer">
				<button type="button" id="start-filter-again" class="btn-pale">Start Over</button>
				<button type="button" class="btnCloseFeatureFilterModal" onclick="closeFeatureFilterModal();">Cancel</button>
			</div>
		</div>
	</div>

	<div id="messagePopup" class="hidden">
		<div class="modal_inner">
			<div class="modal_body">
			</div>
			<button type="button class="btn" onclick="document.getElementById('messagePopup').classList.add('hidden');">Ok</button>
		</div>
	</div>
	
	<div id="modalUploadPhotoTip" class="hidden" data-index="1">
		<div class="modal_inner">
			<div class="modal_body">
				<div class="row hidden" data-index="1">
					<p>Take a photo of the rear view mirror area like this.</p>
					<img src="<?php echo plugins_url( 'autoglasscrm-quote-request/assets/mirror-view.jpg', _FILE_ );?>">
				</div>
				
				<div class="row hidden" data-index="2">
					<p>There is a chance you vehicle may have Heads Up Display (HUD). Take a picture in front of your vehicle standing directly in front of the driver side like this.</p>
					<img src="<?php echo plugins_url( 'autoglasscrm-quote-request/assets/hud-view.jpg', _FILE_ );?>">
				</div>
				
				<div class="row hidden" data-index="3">
					<p>Take full photo of <?php echo strtolower($addressLocalizeHelper->windshieldText()); ?>>.</p>
					<img src="<?php echo plugins_url( 'autoglasscrm-quote-request/assets/whole-windshield-view.jpg', _FILE_ );?>">
				</div>
				
			</div>
			<div class="modal_bottom">
				<input type="button" value="OK" class="btn btn_ok">
			</div>
		</div>
	</div>

	
    <form class="autoglasscrm-quote" method="post" action="">
		<h5 class="form_row full_width">Step1: Contact Information</h5>

        <input type="hidden" name="country_code" value="<?php echo $countryCode; ?>">

		<div class="form_row">
			<label>First Name <span class="required_mark">*</span></label>
			<input type="text" name="first_name" placeholder="First Name" />
		</div>
		
		<div class="form_row">
			<label>Last Name</label>
			<input type="text" name="last_name" placeholder="Last Name" />
		</div>
		
		<div class="form_row">
			<label>Phone <span class="required_mark">*</span></label>
			<input type="text" name="phone" placeholder="Phone" />
		</div>
		
		<div class="form_row">
			<label>Email <span class="required_mark">*</span></label>
			<input type="text" name="email" placeholder="Email" />
		</div>
		
		<div class="form_row">
			<label>Street Address</label>
			<input type="text" name="address" placeholder="Street Address" />
		</div>
		
		<div class="form_row">
			<label>City</label>
			<input type="text" name="city" placeholder="City" />
		</div>

        <?php if($addressLocalizeHelper->stateText()): // localization has state/province ?>
            <div class="form_row">
                <label><?php echo $addressLocalizeHelper->stateText() ?></label>
                <select name="state">
                    <option value="" selected>Select a <?php echo $addressLocalizeHelper->stateText(3) ?>...</option>
                    <?php $addressLocalizeHelper->stateDropdown($countryCode) ?>

                </select>
            </div>
        <?php endif; ?>
		
		<div class="form_row">
			<label><?php echo $addressLocalizeHelper->zipText() ?></label>
			<input type="text" name="zip" placeholder="<?php echo $addressLocalizeHelper->zipText() ?>" />
		</div>
		
		<div class="form_row">
			<label>Glass Type</label>
			<select name="glass_type">
                <option value="1">Back Glass</option>
                <option value="2">Door Glass</option>
                <option value="21">Partition Glass</option>
                <option value="3">Quarter Glass</option>
                <option value="19">Roof Glass</option>
                <option value="22">Vent Glass</option>
                <option value="4" selected=""><?php echo $addressLocalizeHelper->windshieldText(); ?> Glass</option>
                <option value="23">Heavy Duty Truck <?php echo $addressLocalizeHelper->windshieldText(); ?></option>
            </select>
			
			<select name="glass_option" class="hidden">
				<option value="0">Options...</option>
				<option value="1">1 Piece (Car/Minivan/SUV/Truck)</option>
				<option value="2">Rear Left (Van Only)</option>
				<option value="3">Rear Right (Van Only)</option>
			</select>
			
			<input type="text" name="glass_other" class="hidden">
		</div>
		
		<div class="form_row">
            <?php if ($addressLocalizeHelper->supportsRegistrationPlate()): ?>
                <label>VIN (if no reg. available)</label>
            <?php else: ?>
                <label>Vehicle VIN <span class="required_mark">*</span></label>
            <?php endif; ?>
			<input type="text" name="vehicle_vin" placeholder="VIN" />
		</div>

        <?php if ($addressLocalizeHelper->supportsRegistrationPlate()): ?>
            <div class="form_row">
                <label>Reg. plate number <span class="required_mark">*</span></label>
                <input type="text" name="vehicle_reg_plate_number" placeholder="Reg. plate number" />
            </div>
        <?php endif; ?>

		<div class="form_row full_width">
			<br>
			<button type="button" id="btnRunVIN">Go to Step 2</button>
			<!--
			<button type="button" id="btnQuote">Quote</button>
			-->
		</div>
		
		<div class="form_row full_width">
			<p class="vin_search_status"></p>
		</div>
    </form>
	
	<!--
	<div class="vin_search_wrap">
		<div class="form_row">
			<input type="text" name="vin" placeholder="VIN" />
		</div>
		
		<div class="form_row">
			<button type="button" id="btnRunVIN">Search VIN</button>
		</div>	
	</div>
	-->
	
	
	<form id="formUploadPhoto" action="<?php echo admin_url();?>admin-ajax.php?action=quote_detect_shape" method="post" enctype="multipart/form-data">
		<input type="hidden" name="first_name"/>
		<input type="hidden" name="last_name"/>
		<input type="hidden" name="phone"/>
		<input type="hidden" name="email"/>
		<input type="hidden" name="address"/>
		<input type="hidden" name="city"/>
		<input type="hidden" name="state"/>
		<input type="hidden" name="zip"/>
		<input type="hidden" name="vehicle_id"/>
		<input type="hidden" name="vehicle_vin"/>
		<input type="hidden" name="vehicle_year"/>
		<input type="hidden" name="vehicle_make"/>
		<input type="hidden" name="vehicle_model"/>
		<input type="hidden" name="vehicle_body"/>
		<input type="hidden" name="glass_type"/>
		<input type="hidden" name="possible_parts"/>
		
		<div class="upload_photo_wrap hidden">
			<h5>Step2: Upload photo</h5>
			<p>These photos will help us determine the correct piece of glass that goes into your vehicle. If you do not have photos click the check box below.</p>
			
			<p class="upload_photo_status"></p>
			<input type="button" id="btnUploadPhoto" value="Upload Photo">
			
			
			<div class="upload_photo_row hidden" data-index="1">
				<input type='file' class="hidden" id="fileUpload_1" name="upload_photos[]">
				<img id="previewFileUpload_1" src="#">
				<a href="javascript:void(0);" class="btn_remove_photo">Remove</a>
			</div>
			
			<div class="upload_photo_row hidden" data-index="2">
				<input type='file' class="hidden" id="fileUpload_2" name="upload_photos[]">
				<img id="previewFileUpload_2" src="#">
				<a href="javascript:void(0);" class="btn_remove_photo">Remove</a>
			</div>
			
			<div class="upload_photo_row hidden" data-index="3">
				<input type='file' class="hidden" id="fileUpload_3" name="upload_photos[]">
				<img id="previewFileUpload_3" src="#">
				<a href="javascript:void(0);" class="btn_remove_photo">Remove</a>
			</div>
			
			<div class="">
				<input id="chkNoPhoto" type="checkbox" name="non_photo" value=""><label for="chkNoPhoto">I don't have photos but I want a call/email with a quote from my VIN number.</label>
			</div>
						
			<button id="btnStep3" class="btn hidden">Go to Step 3</button>
		</div>
		
		<div id="step3Wrapper" class="hidden">
			<h5>Step3: Confirm Features</h5>
			<p>Help us determine which features you have.</p>
			
			<div class="vin_search_result">
			</div>
			
			<button id="btnFeatureFilter" class="btn">Feature Filter</button>
			
			<div style="margin-top: 10px;">
				<button type="submit" id="btnQuoteSubmit" class="btn hidden">Submit</button>
			</div>
			
			
		</div>
	</form>

	<div class="quote_submit_result_wrap">
	</div>
	
</div>

<script>
	function closeFeatureFilterModal(){
		var partItems = document.querySelectorAll(".vin_search_result .part_item");
		for(var i=0;i<partItems.length;i++){
			partItems[i].classList.remove("hidden");
		}
		document.getElementById("featureFilterModal").classList.remove("modal-active");
	}
</script>
