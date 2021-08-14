<?php
//
// Recommended way to include parent theme styles.
//  (Please see http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme)
//  
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('parent-style')
    );
}
//
// Your code goes below
//<?php
function rotating_banner_func( $atts ){
	return <<<EndOfString
<script language="JavaScript1.2"> 
var variableslide = new Array()

//variableslide[x]=["path to image", "OPTIONAL link for image", "OPTIONAL text description (supports HTML tags)"] 

variableslide[0] = ['/wp-content/uploads/2013/07/Banner-a.jpg', "", ""]
variableslide[1] = ['/wp-content/uploads/2013/07/Banner-b.jpg', "", ""]
variableslide[2] = ['/wp-content/uploads/2013/07/Banner-c.jpg', "", ""]
variableslide[3] = ['/wp-content/uploads/2013/07/Banner-d.jpg', "", ""]
variableslide[4] = ['/wp-content/uploads/2013/07/Banner-e.jpg', "", ""]

//configure the below 3 variables to set the dimension/background color of the slideshow 

var slidewidth = '531px' //set to width of LARGEST image in your slideshow 
var slideheight = '150px' //set to height of LARGEST image in your slideshow, plus any text description 
var slidebgcolor = '#E8DDC6'

//configure the below variable to determine the delay between image rotations (in miliseconds) 
var slidedelay = 10000

////Do not edit pass this line//////////////// 

var ie = document.all
var dom = document.getElementById

for (i = 0; i < variableslide.length; i++) {
    var cacheimage = new Image()
    cacheimage.src = variableslide[i][0]
}

var currentslide = 0

    function rotateimages() {
        contentcontainer = '<center>'
        if (variableslide[currentslide][1] != "")
            contentcontainer += '<a href="' + variableslide[currentslide][1] + '">'
        contentcontainer += '<img src="' + variableslide[currentslide][0] + '" border="0" vspace="3">'
        if (variableslide[currentslide][1] != "")
            contentcontainer += '</a>'
        contentcontainer += '</center>'
        if (variableslide[currentslide][2] != "")
            contentcontainer += variableslide[currentslide][2]

        if (document.layers) {
            crossrotateobj.document.write(contentcontainer)
            crossrotateobj.document.close()
        } else if (ie || dom)
            crossrotateobj.innerHTML = contentcontainer
        if (currentslide == variableslide.length - 1) currentslide = 0
        else currentslide++
        setTimeout("rotateimages()", slidedelay)
    }

if (ie || dom)
    document.write('<div id="slidedom" style="width:' + slidewidth + ';height:' + slideheight + '; background-color:' + slidebgcolor + '" class="hide-650"></div>')

function start_slider() {
    crossrotateobj = dom ? document.getElementById("slidedom") : ie ? document.all.slidedom : document.slidensmain.document.slidenssub

    if (document.layers)
        document.slidensmain.visibility = "show"
    rotateimages()
}

if (ie || dom)
    start_slider()
else if (document.layers)
    window.onload = start_slider
</script>
EndOfString;
}
add_shortcode( 'rotating_banner', 'rotating_banner_func' );

function custom_mtypes( $m ){
    $m['svg'] = 'image/svg+xml';
    $m['svgz'] = 'image/svg+xml';
    return $m;
}
add_filter( 'upload_mimes', 'custom_mtypes' );

function remark_remove_fetch_copyright() {
	remove_action('wp_head','fetch_copyright');
}
add_action('wp_head', 'remark_remove_fetch_copyright',0,0);

# This extends functionality in the Stripe Payments Wordpress plugin
# https://s-plugins.com/
# So that you can include dynamic tags in the subject of the seller email
# This code is based off of the asp_apply_dynamic_tags_on_email_body function
# found in the process_ipn.php file in the Stripe Payments plugin
function asp_apply_dynamic_tags_on_subject($subj, $post) {
	$tags = array(
		'{item_name}',
		'{item_short_desc}',
		'{item_quantity}',
		'{item_url}',
		'{payer_email}',
		'{customer_name}',
		'{transaction_id}',
		'{item_price_curr}',
		'{purchase_amt_curr}',
		'{purchase_date}',
		'{shipping_address}',
		'{billing_address}',
		'{coupon_code}'
	);
	$vals = array(
		$post['item_name'],
		$post['charge_description'],
		$post['item_quantity'],
		! empty( $post['item_url'] ) ? $post['item_url'] : '',
		$post['stripeEmail'],
		$post['customer_name'],
		$post['txn_id'],
		$post['item_price_curr'],
		$post['purchase_amt_curr'],
		$post['purchase_date'],
		isset( $post['shipping_address'] ) ? $post['shipping_address'] : '',
		isset( $post['billing_address'] ) ? $post['billing_address'] : '',
		! empty( $post['coupon_code'] ) ? $post['coupon_code'] : ''
	);
	return stripslashes( str_replace( $tags, $vals, $subj ) );
}
#      The tag defined in the Stripe plugin      Our function name     Priority number-of-arguments
add_filter('asp_seller_email_subject', 'asp_apply_dynamic_tags_on_subject', 10, 2);
# The tag defined in the Stripe plugin : This is hard coded in the Stripe Plugin
# Our function name : This is a function name I came up with, you can find it above here in this file
# This is the priority of the filter, the default is 10 : https://developer.wordpress.org/reference/functions/add_filter/#parameters
# This is the number of arguments passed to asp_seller_email_subject which is 2
# The two arguments are 1) The subject text and 2) the associative array of the values the user entered


?>
