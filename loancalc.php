<?php
/**
 * The loancalc Plugin
 * @package Loan Calc
 * @subpackage Main
 */
/**
 * Plugin Name: Loan Calc
 * Plugin URI:  http://seojhb.co.za
 * Description: Loan calc is plugin for caclulating loan rate.
 * Author:      Madhav Poudel
 * Author URI:  http://madhavpoudel.com.np
 * Version:     0.1.1
 * Text Domain: loancalc
 * License:     GPLv2 or later (license.txt)
 */
if(!defined('LOANCALC_URL')) {
    define('LOANCALC_URL', plugin_dir_url( __FILE__ ));
}
class LoanCalcClass {
	// Constructor
	function __construct() {
	    add_action( 'init', array( $this, 'add_shortcodes' ) );
	    add_action( 'the_post', array( $this, 'loan_calc_script' ), 9999 );
  	}
  	// Enqueueing Script
  	function loan_calc_script()  {
	      global $post;
	      if( has_shortcode( $post->post_content, 'loancalc') || has_shortcode( $post->post_content, 'loancalcapply') ){
	      	wp_enqueue_style( 'noui_slider_css', LOANCALC_URL . 'css/nouislider.min.css' );
	      	wp_enqueue_style( 'noui_slider_pips', LOANCALC_URL . 'css/nouislider.pips.css' );
	      	wp_enqueue_style( 'noui_slider_tooltips', LOANCALC_URL . 'css/nouislider.tooltips.css' );
	      	wp_enqueue_style( 'loan_style_css', LOANCALC_URL . 'css/style.css' );
	      	wp_enqueue_style( 'bootstrap_css', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
	      	wp_enqueue_script( 'jquery_js', 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', array( 'jquery' ), false, true );
	      	wp_enqueue_script( 'bootstrap_js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array( 'jquery' ), false, true );
	        wp_enqueue_script( 'noui_slider_js', LOANCALC_URL . 'js/nouislider.min.js', array( 'jquery' ), false, true );
	        wp_enqueue_script( 'loan_scripts_js', LOANCALC_URL . 'js/scripts.js', array( 'jquery' ), false, true );
	      }
	  }
	// Adding Shortcode
	function add_shortcodes() {
	    add_shortcode( 'loancalc', array( $this, 'loancalc_shortcode') );
	    add_shortcode( 'loancalcapply', array( $this, 'loancalc_shortcode_apply') );
	}
	function html_form_code() {?>
	    <form action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" method="post" enctype="multipart/form-data">
			<div class="container first-form-group">
        <div class="col-md-12 loan-field">
          <p>How much do you want?</p>
          <div class="col-md-6">
                <div class="slider" id="slider-format"></div>
          </div>
          <div class="col-md-2">
            <input type="textfield" class="form-control loan-input" id="loan" name="loan" value="<?php echo esc_attr( $this->amt );?>">
          </div>
        </div>
        <div class="col-md-12 days-field">
          <p>When do you want to pay back?</p>
          <div class="col-md-6">
            <div class="slider" id="slider-format2"></div>
          </div>
          <div class="col-md-2">
            <input type="textfield" class="form-control loan-input" id="loanReturnDays" value="<?php echo esc_attr( $this->days );?>" name="loanReturnDays">
          </div>
          <div class="col-md-4">
            <span id="days">Wed 25 Aug 2016</span>
          </div>
        </div>
			  <div class="row">
			      <div class="col-md-6 loan-bottom">
			        <p class="loan-msg">Total amount to be paid:
			        Loan amount <span id="amount">R100</span> +Interest & Fee <span id="interest">10%</span> = <span id="total">101</span></p>
			         <input type="hidden" class="form-control" id="totalAmount" name="totalAmount">
			      </div>
            <div class="col-md-6 loan-bottom">
              <p class="loan-error" id="loan-error"></p>
            </div>
			  </div>
			</div>
			<?php if ( is_user_logged_in() ): ?>
			<div class="container second-form-group">
				<div class="col-md-8 col-md-offset-2 loan-inline-form">
					<div class="col-md-5">
						<h5>Name and Surname:</h5>
					</div>
					<div class="col-md-7">
						<input type="text" class="form-control" id="fullName" name="fullName" required>
					</div>
				</div>
				<div class="col-md-8 col-md-offset-2 loan-inline-form">
					<div class="col-md-5">
						<h5>ID Number:</h5>
					</div>
					<div class="col-md-7">
						<input type="text" class="form-control" id="IDnumber" name="IDnumber" required>
					</div>
				</div>
				<div class="col-md-8 col-md-offset-2 loan-inline-form">
					<div class="col-md-5">
						<h5>Bank account number:</h5>
					</div>
					<div class="col-md-7">
						<input type="text" class="form-control" id="aNumber" name="aNumber" required>
					</div>
				</div>
				<div class="col-md-8 col-md-offset-2 loan-inline-form">
					<div class="col-md-5">
						<h5>Cell phone number:</h5>
					</div>
					<div class="col-md-7">
						<input type="text" class="form-control" id="cNumber" name="cNumber" required>
					</div>
				</div>
				<div class="col-md-8 col-md-offset-2 loan-inline-form">
					<div class="col-md-5">
						<h5>3 months’ bank statement:</h5>
					</div>
					<div class="col-md-7">
						<input type="file" class="form-control" name="file-upload" id="file-upload" required/>
						<?php wp_nonce_field( plugin_basename( __FILE__ ), 'example-jpg-nonce' ); ?>
					</div>
				</div>
				<div class="col-md-8 col-md-offset-2 loan-inline-form">
					<div class="col-md-4 col-md-offset-8">
				        <button type="submit" class="loan-btn" name="form-submitted" id="submitbtn">Submit</button>
				        <a href="<?php echo home_url(); ?>"><button class="loan-btn">Cancel</button></a>
				    </div>
				</div>
			</div>
			<?php else: ?>
				<div class="container second-form-group">
					<div class="col-md-8 col-md-offset-2">
						<p>You need to login to request for loan. </p>
					</div>
				</div>
			<?php endif; ?>
		</form>
		<?php
	}
	function deliver_mail() {
		// if the submit button is clicked, send the email
			$this->amt = isset( $_POST['loan'] ) ? esc_html( trim( $_POST['loan'] ) ) : '';
			$this->days = isset( $_POST['loanReturnDays'] ) ? esc_html( trim( $_POST['loanReturnDays'] ) ) : '';
			if ( isset( $_POST['form-submitted'] )) {
				global $current_user;
				// sanitize form values
				$amount   = sanitize_text_field( $_POST["loan"] );
				$days     = sanitize_text_field( $_POST["loanReturnDays"] );
				$total    = sanitize_text_field( $_POST["totalAmount"] );
				$name     = sanitize_text_field( $_POST["fullName"] );
				$email    = $current_user->user_email;
				$idNumber = sanitize_text_field( $_POST["IDnumber"] );
				$aNumber  = sanitize_text_field( $_POST["aNumber"] );
				$cNumber  = sanitize_text_field( $_POST["cNumber"] );
				// Uploading and sending attachments
				if ( ! function_exists( 'wp_handle_upload' ) ) {
				    require_once( ABSPATH . 'wp-admin/includes/file.php' );
				}
				$uploadedfile = $_FILES['file-upload'];
				$upload_overrides = array( 'test_form' => false );
				$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

				if ( $movefile && ! isset( $movefile['error'] ) ) {
					$subject = "New Loan request";
					$message = "<h2>Loan Information</h2><p>Name and surname: ".$name." <br>Email: ".$email." <br>ID Number: ".$idNumber."<br>
					 Bank account Number: ".$aNumber." <br>Cell Phone Number: ".$cNumber." <br>Amount: ".$amount." <br>Days: ".$days."<br>
					 Total Amount: ".$total." <br>Document URL: ".$movefile[url]."</p>";
					// get the blog administrator's email address
					$to = get_option( 'admin_email' );
					$attachments = array( $movefile[url] );
					$headers = "From: $name <$email>" . "\r\n";
				    add_filter( 'wp_mail_content_type', array( $this, 'wpse27856_set_content_type') );
				    // If email has been process for sending, display a success message
					if ( wp_mail( $to, $subject, $message, $headers, $attachments ) ) {
						remove_filter( 'wp_mail_content_type', array( $this, 'wpse27856_set_content_type') );
						echo '<div class="alert alert-info">';
						echo 'Your Loan Request has been successfully submitted. <br>';
						echo 'Your loaned amount is '.$amount.'. Total amount is '.$total.' and your pay back period is'.$days.'days!!';
						echo '</div>';
					} else {
						echo 'An unexpected error occurred';
					}
				} else {
				    echo $movefile['error'];
				}
			}
	}
	// Shortcode template
  	function loancalc_shortcode( $atts ) {
		ob_start();
		$this->deliver_mail();
		$this->html_form_code();
		return ob_get_clean();
	}
	// html code for loan apply shortcode
	function html_form_code_apply($linkto) { ?>
	<form class="booking-form" method="post" action="<?php echo $linkto; ?>">
		<div class="container first-form-group">
      <div class="col-md-12 loan-field">
        <p>How much do you want?</p>
        <div class="col-md-6">
              <div class="slider" id="slider-format"></div>
        </div>
        <div class="col-md-2">
          <input type="textfield" class="form-control loan-input" id="loan" name="loan" value="<?php echo esc_attr( $this->amt );?>">
        </div>
      </div>
      <div class="col-md-12 days-field">
        <p>When do you want to pay back?</p>
        <div class="col-md-6">
          <div class="slider" id="slider-format2"></div>
        </div>
        <div class="col-md-2">
          <input type="textfield" class="form-control loan-input" id="loanReturnDays" value="<?php echo esc_attr( $this->days );?>" name="loanReturnDays">
        </div>
        <div class="col-md-4">
          <span id="days">Wed 25 Aug 2016</span>
        </div>
      </div>
		  <div class="row">
		      <div class="col-md-6 loan-bottom">
		        <p class="loan-msg">Total amount to be paid:
		        Loan amount <span id="amount">R100</span> +Interest & Fee <span id="interest">10%</span> = <span id="total">101</span></p>
		         <input type="hidden" class="form-control" id="totalAmount" name="totalAmount">
		      </div>
		      <div class="col-md-6 loan-bottom">
            <div class="col-md-5">
              <input type="submit" class="btn btn-warn" id="submitbtn" value="Apply Now" name="form-applied" />
            </div>
            <div class="col-md-7">
              <p class="loan-error" id="loan-error"></p>
            </div>
		      </div>
		  </div>
		</div>
	</form>
	<?php }
	// Shortcode template
  	function loancalc_shortcode_apply( $atts ) {
  		extract(shortcode_atts(array(
		      "linkto" => "#"
		   ), $atts));
		ob_start();
		$this->html_form_code_apply($linkto);
		return ob_get_clean();
	}
	// setting wp main content to html
	function wpse27856_set_content_type(){
	    return "text/html";
	}
}
new LoanCalcClass();
