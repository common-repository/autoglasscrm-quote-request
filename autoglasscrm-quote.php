<?php
/**
 * Plugin Name: Quote Widget
 * Description: Custom plugin to create quote widget for AutoGlassCRM
 * Version: 1.0.8
 * Author: Sean Zahrae
 */

session_start();

require __DIR__.'/Auth/AGCRM.php'; // get auth constants
require __DIR__.'/Helpers/AddressLocalize.php';

/* ENQUEUE JAVASCRIPT */
add_action('wp_enqueue_scripts', 'agcrm_quote_enqueue_scripts');
function agcrm_quote_enqueue_scripts() {


    wp_enqueue_script('jquery-form-js', plugin_dir_url(__FILE__).'jquery.form.js', array('jquery'), '1.1.1');
	wp_enqueue_script('quote-scripts-js-featurefilter', plugin_dir_url(__FILE__).'FeatureFilter.js?v='.time(), array('jquery'), '1.1.1');
    wp_enqueue_script('quote-custom-scripts-js', plugin_dir_url(__FILE__).'autoglasscrm_quote_script.js?v='.time(), array('jquery'), '1.1.1');

	wp_localize_script('quote-scripts-js-featurefilter', 'WPURLS', array( 'admin_url' => admin_url() ));
	wp_localize_script('quote-custom-scripts-js', 'WPURLS', array( 'admin_url' => admin_url() ));
}
add_action('admin_enqueue_scripts', 'agcrm_qutote_admin_enqueu_scripts');
function agcrm_qutote_admin_enqueu_scripts(){
	wp_enqueue_script('custom-admin-scripts-js', plugin_dir_url(__FILE__).'autoglasscrm_quote_admin_script.js?v='.time(), array('jquery'), '1.1.1');
}

wp_enqueue_style('agc_admin_style', plugin_dir_url(__FILE__).'autoglasscrm_quote_admin_style.css?v='.time());

function add_agc_admin_pages(){
	add_menu_page('Theme page title', 'AGCRM', 'manage_options', 'theme-options', 'agc_token_page_content', 'dashicons-book-alt');
}

function agc_token_page_content(){
	global $wpdb;
	$results = get_option('agc_access');

	$nonce = wp_create_nonce("check_email");
	$link1 = admin_url('admin-ajax.php?action=check_email&nonce='.$nonce);

	$nonce = wp_create_nonce("get_user_token");
	$link2 = admin_url('admin-ajax.php?action=get_user_token&nonce='.$nonce);

	echo '<div class="agc_token_page_wrap" data-action1-url="'.$link1.'" data-action2-url="'.$link2.'">';

	if ($results != false){
		echo '<p class="agc_token_success">Your AutoGlassCRM token is successfully retrieved</p>';

        $nonce = wp_create_nonce('logout');
        $link3 = admin_url('admin-ajax.php?action=logout&nonce='.$nonce);

		echo '<button type="button" class="agc_logout" data-logout-url="'.$link3.'">Logout</button>';
	} else {

        echo '<h2>AutoGlassCRM Login</h2>';
        echo '<div class="form_row">';
        echo	'<label>Username:</label>';
        echo	'<input type="email" name="agc_email" placeholder="Email">';
        echo '</div>';

        echo '<div class="form_row">';
        echo	'<label>Password:</label>';
        echo	'<input type="password" name="agc_password" placeholder="Password">';
        echo '</div>';

        echo '<div class="form_row">';
        echo	'<input type="submit" name="agc_submit" value="Submit">';
        echo '</div>';
    }

    echo '<div class="form_row instructions">';
    echo   '<h3>Instructions:</h3>';
    echo   '<ul>';
    echo   '<li>Step 1: Create a new page and name the page quote request and publish the page.</li>';
    echo   '<li>Step 2: Go to Appearance->Widgets, under available widgets you will see Request Quote.<br>Before going to step 3 make sure you see Widgets for Shortcodes on the right side of this page. If you don\'t see Widgets for Shortcodes you should download the plugin amr shortcode any widget. Install and activate that plugin and do Step 2 again.</li>';
    echo   '<li>Step 3: Drag Request Quote widget to widget for shortcodes. Once added, you will click on Request Quote and you will see the shortcode. Copy and paste short code to the page you created in Step 1.  Publish the page and then try to go to your website and go to that page. You should see the quote tool.</li>';
    echo   '</ul>';
    echo '</div>';
    echo '</div>';
}

add_action('admin_menu', 'add_agc_admin_pages');

/* CREATE WIDGET */
add_action('widgets_init', 'agcrm_load_quote_widget');
function agcrm_load_quote_widget() {
    register_widget('autoglasscrm_quote_widget');
}

class autoglasscrm_quote_widget extends WP_Widget {
    // Widget setup
    public function __construct() {
        parent::__construct(
            'autoglasscrm_quote_widget',
            'Request Quote',
            array('description'=>'AutoGlassCRM Quote Widget')
        );
    }

    // Create widget on page
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        echo $args['before_widget'];
        if(!empty($title)) echo $args['before_title'] . $title . $args['after_title'];

        require plugin_dir_path(__FILE__) . 'data/states.php';

        global $wpdb;

        // Get country code.
        $countryCode = null;
        $results = get_option('agc_access');
        if($results != false){
            $json_data = json_decode($results);

            if ( $json_data != null ){
                $countryCode = $json_data->agc_user_country;
            }
        }

        ob_start();
        require plugin_dir_path(__FILE__) . 'templates/form.php';
        echo ob_get_clean();

        echo $args['after_widget'];
    }

    // Create form for admin to instantiate widget
    public function form($instance) {
        if(isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = 'AutoGlassCRM Quote';
        }
        echo '<p>
        <label for="' . $this->get_field_id('title') . '">Title:</label>
        <input type="text" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.esc_attr($title).'" />
        </p>';
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ? strip_tags($new_instance['title']) : '' );
        return $instance;
    }
}


/*** Ajax Functions ***/
function recursive_sanitize_text_field($array) {
    foreach ( $array as $key => &$value ) {
        if ( is_array( $value ) ) {
            $value = recursive_sanitize_text_field($value);
        }
        else {
            $value = sanitize_text_field( $value );
        }
    }

    return $array;
}

// logout
add_action("wp_ajax_logout", "ajax_logout");
function ajax_logout() {
    if ( !wp_verify_nonce( $_REQUEST['nonce'], "logout")) {
        exit("No naughty business please");
    }
	delete_option('agc_access');
}

// check email
add_action("wp_ajax_check_email", "ajax_check_email");
function ajax_check_email(){
	if (!wp_verify_nonce( $_REQUEST['nonce'], "check_email")) {
      exit("No naughty business please");
	}

	$email = "";
	if ( isset($_GET['email']) ){
		$email = sanitize_email($_GET['email']);
	}

	$uri = 'https://admin.autoglasscrm.com/account/prefixfromemail?email='.urlencode($email);
	$args = array(
		'headers' => array(
			'WP_AUTH_ID' => \QuoteAuth\AGCRM::AUTH_ID,
			'WP_AUTH_KEY' =>\QuoteAuth\AGCRM::AUTH_KEY,
		)
	);
	$response = wp_remote_get( $uri, $args );
	$body     = wp_remote_retrieve_body( $response );

	echo $body;
	die();
}

// get user token
add_action("wp_ajax_get_user_token", "ajax_get_user_token");
function ajax_get_user_token(){
	if ( !wp_verify_nonce( $_REQUEST['nonce'], "get_user_token")) {
      exit("No naughty business please");
	}

	global $wpdb;

	$prefix = "";
	if ( isset($_GET['prefix']) ){
		$prefix = sanitize_text_field($_GET['prefix']);
	}

	$email = "";
	if ( isset($_GET['email']) ){
		$email = urlencode(sanitize_email($_GET['email']));
	}

	$password = "";
	if ( isset($_GET['password']) ){
		$password = urlencode(sanitize_text_field($_GET['password']));
	}

	$uri = 'https://'.$prefix.'.autoglasscrm.com/login/appauth?email='.$email.'&password='.$password;

	$args = array(
		'headers' => array(
			'WP_AUTH_ID' => \QuoteAuth\AGCRM::AUTH_ID,
			'WP_AUTH_KEY' =>\QuoteAuth\AGCRM::AUTH_KEY,
		),
        'timeout' => 15
	);

	$response = wp_remote_get( $uri, $args );
	$body     = wp_remote_retrieve_body( $response );

	$json_response = json_decode($body);
	if ( $json_response != null && isset($json_response->token) ){
		$data = array(
			'agc_domain_prefix' => $prefix,
			'agc_token' => $json_response->token,
			'agc_vindecoderAuth' => $json_response->vindecoderAuth,
			'agc_subscription' => $json_response->subscription,
			'agc_access' => $json_response->access,
            'agc_user_country' => $json_response->country // ISO2 code
			);

		update_option('agc_access', json_encode($data));

		echo json_encode(array('token'=> 'valid'));
	}

	die();
}

// search VIN
add_action("wp_ajax_search_vin", "ajax_search_vin");
add_action("wp_ajax_nopriv_search_vin", "ajax_search_vin");
function ajax_search_vin(){
	global $wpdb;

	$agc_token = "";
	$agc_access = "";
	$agc_domain_prefix = "";

	$data = get_option('agc_access');
	if ($data != false) {
		$json_data = json_decode($data);

		if ( $json_data != null ){
			$agc_token = $json_data->agc_token;
			$agc_access = $json_data->agc_access;
			$agc_domain_prefix = $json_data->agc_domain_prefix;
		}

	}

	if ( $agc_token == "" || $agc_access == "" || $agc_domain_prefix == "" ){
		$return = array(
			'success' => 0,
			'error_type' => 'invalid_token',
			'message' => 'Token is invalid.'
		);

		echo json_encode($return);
		die();
	}

	$validate_vin = "";
	if ( isset($_POST["validate_vin"]) ){
		$validate_vin = sanitize_text_field($_POST["validate_vin"]);
	}

	$vin = "";
	if ( isset($_POST['vin']) ){
		$vin = sanitize_text_field($_POST['vin']);
	}

	$tag = '';
    if ( isset($_POST['tag']) ){
        $tag = sanitize_text_field($_POST['tag']);
    }

	if ( $validate_vin == 1 ){
		$uri = "https://www.decodethis.com/webservices/decodes/".$vin."/_vSxUkWSvHaDnxUcsaAs/0.jsonp";

		$args = array(
			'headers' => array(
				'Content-Type' => 'application/json',
			)
		);
		$response = wp_remote_get( $uri, $args );
		$body     = wp_remote_retrieve_body( $response );

		$response = $body;

		$validatedVIN = false;
		try{
			$jsonResponse = json_decode($response);
			if ( $jsonResponse->decode->status == "SUCCESS" ){
				$validatedVIN = true;
			}
		}catch(Exception $e){}

		if ( $validatedVIN == false ){
			$return = array(
				'success' => 0,
				'error_type' => 'invalid_vin',
				'message' => 'This VIN is invalid.'
			);

			echo json_encode($return);
			die();
		}
	}


	$glass_type = "";
	$glass_option = "";
	$glass_other = "";

	if ( isset($_POST['glass_type']) ) $glass_type = sanitize_text_field($_POST['glass_type']);
	if ( isset($_POST['glass_option']) ) $glass_option = sanitize_text_field($_POST['glass_option']);
	if ( isset($_POST['glass_other']) ) $glass_other = sanitize_text_field($_POST['glass_other']);


	$uriQueryString = '';
	if (!empty($vin)) {
	    $uriQueryString = 's=' . $vin;
    } elseif (empty($vin) && !empty($tag)) {
        $uriQueryString = 'tag=' . $tag;
    }

	$uri = 'https://'.$agc_domain_prefix.'.autoglasscrm.com/vindecoder/search?'.$uriQueryString."&type=".$glass_type."&opt=".$glass_option."&other=".$glass_other;

	$args = array(
		'headers' => array(
			'Content-Type' => 'application/json',
			'Authorization' => $agc_token,
			'Access' => $agc_access
		)
	);
	$response = wp_remote_get( $uri, $args );
	$body     = wp_remote_retrieve_body( $response );
	$response = $body;

	$return =array(
		'success' => 1,
		'account_prefix' => $agc_domain_prefix,
		'response' => $response,
	);

	echo json_encode($return);
	die();
}


// search VIN
add_action("wp_ajax_quote_get_questions", "ajax_quote_get_questions");
add_action("wp_ajax_nopriv_quote_get_questions", "ajax_quote_get_questions");
function ajax_quote_get_questions(){
	global $wpdb;

	$agc_token = "";
	$agc_access = "";
	$agc_domain_prefix = "";

	$data = get_option('agc_access');
	if ($data){
		$json_data = json_decode($data);

		if ( $json_data != null ){
			$agc_token = $json_data->agc_token;
			$agc_access = $json_data->agc_access;
			$agc_domain_prefix = $json_data->agc_domain_prefix;
		}

	}

	if ( $agc_token == "" || $agc_access == "" || $agc_domain_prefix == "" ){
		$return = array(
			'success' => 0,
			'error_type' => 'invalid_token',
			'message' => 'Token is invalid.'
		);

		echo json_encode($return);
		die();
	}

	$params = "";
	if ( isset($_POST['params']) ){
		$params = sanitize_text_field($_POST['params']);
	}

	$uri = 'https://'.$agc_domain_prefix.'.autoglasscrm.com/vindecoder/getfilterquestions'.$params;

	$args = array(
		'headers' => array(
			'Content-Type' => 'application/json',
			'Authorization' => $agc_token,
			'Access' => $agc_access
		)
	);

	$response = wp_remote_get( $uri, $args );
	$body     = wp_remote_retrieve_body( $response );

	echo $body;
	die();
}


// detect shape
add_action("wp_ajax_quote_detect_shape", "ajax_quote_detect_shape");
add_action("wp_ajax_nopriv_quote_detect_shape", "ajax_quote_detect_shape");
function ajax_quote_detect_shape(){
	global $wpdb;

	$files = array();

	if ( isset($_FILES["upload_photos"]["tmp_name"]) ){
		$files = recursive_sanitize_text_field($_FILES["upload_photos"]["tmp_name"]);
	}

	$result = array();
	$result['error'] = 1;
	$result['msg'] = 'Sorry, we are not able to detect shapes.';

	$agc_token = "";
	$agc_access = "";
	$agc_domain_prefix = "";

	$data = get_option('agc_access');
	if ($data){
		$json_data = json_decode($data);

		if ( $json_data != null ){
			$agc_token = $json_data->agc_token;
			$agc_access = $json_data->agc_access;
			$agc_domain_prefix = $json_data->agc_domain_prefix;
		}

	}

	if ( $agc_token == "" || $agc_access == "" || $agc_domain_prefix == "" ){
		$result['msg'] = 'Token is invalid';
		echo json_encode($result);
		die();
	}

	{

		$vehicle_id = "";
		if ( isset($_POST['vehicle_id']) ){
			$vehicle_id = sanitize_text_field($_POST['vehicle_id']);
		}


		$vehicle_vin = "";
		if ( isset($_POST['vehicle_vin']) ){
			$vehicle_vin = sanitize_text_field($_POST['vehicle_vin']);
		}

		$vehicle_year = "";
		if ( isset($_POST['vehicle_year']) ){
			$vehicle_year = sanitize_text_field($_POST['vehicle_year']);
		}

		$vehicle_make = "";
		if ( isset($_POST['vehicle_make']) ){
			$vehicle_make = sanitize_text_field($_POST['vehicle_make']);
		}

		$vehicle_model = "";
		if ( isset($_POST['vehicle_model']) ){
			$vehicle_model = sanitize_text_field($_POST['vehicle_model']);
		}

		$vehicle_body = "";
		if ( isset($_POST['vehicle_body']) ){
			$vehicle_body = sanitize_text_field($_POST['vehicle_body']);
		}

		$customer_first_name = "";
		if ( isset($_POST['first_name']) ){
			$customer_first_name = sanitize_text_field($_POST['first_name']);
		}

		$customer_last_name = "";
		if ( isset($_POST['last_name']) ){
			$customer_last_name = sanitize_text_field($_POST['last_name']);
		}

		$customer_phone = "";
		if ( isset($_POST['phone']) ){
			$customer_phone = sanitize_text_field($_POST['phone']);
		}

		$customer_email = "";
		if ( isset($_POST['email']) ){
			$customer_email = sanitize_text_field($_POST['email']);
		}

		$customer_address = "";
		if ( isset($_POST['address']) ){
			$customer_address = sanitize_text_field($_POST['address']);
		}

		$customer_city = "";
		if ( isset($_POST['city']) ){
			$customer_city = sanitize_text_field($_POST['city']);
		}

		$customer_state = "";
		if ( isset($_POST['state']) ){
			$customer_state = sanitize_text_field($_POST['state']);
		}

		$customer_zip = "";
		if ( isset($_POST['zip']) ){
			$customer_zip = sanitize_text_field($_POST['zip']);
		}


		$glass_type = "";
		if ( isset($_POST['glass_type']) ){
			$glass_type = sanitize_text_field($_POST['glass_type']);
		}

		$possible_parts = "";
		if ( isset($_POST['possible_parts']) ){
			$possible_parts = sanitize_text_field($_POST['possible_parts']);
		}


		$detected_data = array();
		$detected_data['triangle'] = 0;
		$detected_data['circle'] = 0;
		$detected_data['square'] = 0;
		$detected_data['HUD'] = 0;
		$detected_data['eye'] = 0;
		$detected_data['fca'] = 0;
		$detected_data['mldws'] = 0;
		$detected_data['tldws'] = 0;
		$detectedShape = false;
		$headers = ['Content-Type', 'application/json'];

		for($i=0;$i<count($files);$i++){
			$file = $files[$i];
			$body = file_get_contents($file);

			$uri = "https://pwhqni6edi.execute-api.us-east-1.amazonaws.com/prod";
			$args = array(
				'headers' => array(
					'Content-Type' => 'application/json'
				),
				'body' => $body
			);

			$response = wp_remote_post( $uri, $args );
			$body     = wp_remote_retrieve_body( $response );

			$json_response = json_decode($body);

			if ( $json_response != null ){
				$detectedShape = true;
				if ( $detected_data['triangle'] < $json_response->triangle ){
					$detected_data['triangle'] = $json_response->triangle;
				}

				if ( $detected_data['circle'] < $json_response->circle ){
					$detected_data['circle'] = $json_response->circle;
				}

				if ( $detected_data['square'] < $json_response->square ){
					$detected_data['square'] = $json_response->square;
				}

				if ( $detected_data['HUD'] < $json_response->HUD ){
					$detected_data['HUD'] = $json_response->HUD;
				}

				if ( $detected_data['eye'] < $json_response->eye ){
					$detected_data['eye'] = $json_response->eye;
				}

				if ( $detected_data['fca'] < $json_response->fca ){
					$detected_data['fca'] = $json_response->fca;
				}

				if ( $detected_data['mldws'] < $json_response->mldws ){
					$detected_data['mldws'] = $json_response->mldws;
				}

				if ( $detected_data['tldws'] < $json_response->tldws ){
					$detected_data['tldws'] = $json_response->tldws;
				}
			}
		}
		$note = "";

		if ( $glass_type != null ){
			$note = $glass_type."             ";
		}

		if ( $detectedShape ){
			if ( $detected_data['triangle'] >= 0.25 ){
				$note .= "Triangle: ".round($detected_data['triangle'] * 100.0, 2)."%,  ";
			}

			if ( $detected_data['circle'] >= 0.25 ) {
				$note .= "Circle: ".round($detected_data['circle'] * 100.0, 2)."%,  ";
			}

			if ( $detected_data['square'] >= 0.25 ) {
				$note .= "Square: ".round($detected_data['square'] * 100.0, 2)."%,  ";
			}

			if ( $detected_data['HUD'] >= 0.25 ) {
				$note .= "HUD: ".round($detected_data['HUD'] * 100.0, 2)."%,  ";
			}

			if ( $detected_data['eye'] >= 0.25 ) {
				$note .= "Eye: ".round($detected_data['eye'] * 100.0, 2)."%,  ";
			}

			if ( $detected_data['fca'] >= 0.25 ) {
				$note .= "FCA: ".round($detected_data['fca'] * 100.0, 2)."%,  ";
			}

			if ( $detected_data['mldws'] >= 0.25 ) {
				$note .= "MLDWS: ".round($detected_data['mldws'] * 100.0, 2)."%,  ";
			}

			if ( $detected_data['tldws'] >= 0.25 ) {
				$note .= "TLDWS: ".round($detected_data['tldws'] * 100.0, 2)."%";
			}
		}

		if ( $possible_parts != "" ){
			$note .= " Possible Parts: ".$possible_parts;
		}


		$uri = 'https://'.$agc_domain_prefix.'.autoglasscrm.com/quote/save?from=plugin';

		$params = array(
			"id" => "",
			"vehicle" => array(
				"id" => null,
				"vin" => $vehicle_vin,
				"year" => $vehicle_year,
				"make" => $vehicle_make,
				"model" => $vehicle_model,
				"body" => $vehicle_body
			),
			"customer" => array(
				"id" => null,
				"firstName" => $customer_first_name,
				"lastName" => $customer_last_name,
				"phone" => $customer_phone,
				"email" => $customer_email,
				"address1" => $customer_address,
				"address2" => "",
				"city" => $customer_city,
				"state" => $customer_state,
				"zip" => $customer_zip
			),
			"partNumber" => "",
			"dealerPartNum" => "",
			"totalDue" => "",
			"salesperson" => "",
			"notes" => array($note)
		);

		$args = array(
			'headers' => array(
				'Content-Type' => 'application/json',
				'Authorization' => $agc_token,
				'Access' => $agc_access
			),
			'body' => json_encode($params)
		);

		$response = wp_remote_post( $uri, $args );
		$body     = wp_remote_retrieve_body( $response );

		$response = $body;

		$result['error'] = 0;
		$result['msg'] = "";
		try{
			if ( $response != null && $response != "" )
			{
				$result['data'] = json_decode($response);

				if ( isset($result['data']->id) )
				{

					$quoteId = $result['data']->id;

					for($i=0;$i<count($files);$i++){
						$uri = 'https://'.$agc_domain_prefix.'.autoglasscrm.com/file/save/'.$quoteId."/quote";

						$file = $files[$i];

						$boundary = wp_generate_password( 24 );

						$payload = '';
						$payload .= '--' . $boundary;
						$payload .= "\r\n";
						$payload .= 'Content-Disposition: form-data; name="' . 'file-upload' .
							'"; filename="' . $_FILES['upload_photos']['name'][$i] . '"' . "\r\n";
						//$payload .= 'Content-Type: image/jpeg' . "\r\n";
						$payload .= "\r\n";
						$payload .= file_get_contents( $file );
						$payload .= "\r\n";
						$payload .= '--' . $boundary . '--';

						$args = array(
							'headers' => array(
								'Content-type' => 'multipart/form-data; boundary=' . $boundary,
								'Authorization' => $agc_token,
								'Access' => $agc_access
							),
							'body' => $payload
						);

						$response = wp_remote_post( $uri, $args );
						$body     = wp_remote_retrieve_body( $response );

					}

					$result['file_num'] = count($files);
				}
			}
		}catch(Exception $e){
			$result['data'] = "";
		}
	}


	echo json_encode($result);
	die();
}
