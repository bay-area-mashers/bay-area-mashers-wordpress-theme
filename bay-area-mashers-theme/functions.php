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

?>
