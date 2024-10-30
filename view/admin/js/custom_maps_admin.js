jQuery(document).ready(function(){	
	jQuery('ul.tabs li').click(function(){
		var tab_id = jQuery(this).attr('data-tab');

		jQuery('ul.tabs li').removeClass('current');
		jQuery('.tab-content').removeClass('current');

		jQuery(this).addClass('current');
		jQuery("#"+tab_id).addClass('current');
	});
/* clone it */
jQuery('.add_social').click(function(){
	var totaladd = jQuery(".big_social_cont").children().length;
	if(totaladd >= 10)
	{
		jQuery('.add_social').hide();
		jQuery('#morethan10').text('You can add only 10 Locations. Buy Pro Version to add more.');
		alert('You can add only 10 Locations. For More Buy Pro Version');
	}
	else
	{
		jQuery('.big_social_cont').append( jQuery('.item_cliche').html() );
	}
	});
	jQuery('.delete_item').live('click', function(){
		jQuery('.add_social').show();
		jQuery('#morethan10').text('');
		var point = jQuery(this).parents('.mydata');
		point.fadeOut(500, function(){ jQuery(this).replaceWith(''); jQuery(this).remove();});
	});	
	
	
});

(function($) {
    $(function() {
        $.fn.munish = function(options) {
            var selector = $(this).selector;
            var defaults = {
                'preview' : '.preview-upload',
                'text'    : '.text-upload',
                'button'  : '.button-upload',
            };
            var options  = $.extend(defaults, options);

        	// When the Button is clicked...
            $(options.button).click(function() { 
                // Get the Text element.
                var text = $(this).siblings(options.text);
                // Show WP Media Uploader popup
                tb_show('Upload a logo', 'media-upload.php?referer=wptuts&type=image&TB_iframe=true&post_id=0', false);
        		// Re-define the global function 'send_to_editor'
        		// Define where the new value will be sent to
                window.send_to_editor = function(html) {
                	// Get the URL of new image
                    var src = $('img', html).attr('src');
                    // Send this value to the Text field.
                    text.attr('value', src).trigger('change'); 
					$('.showimg').show();
                    tb_remove(); // Then close the popup window
                }
                return false;
            });

            $(options.text).bind('change', function() {
            	// Get the value of current object
                var url = this.value;
                // Determine the Preview field
                var preview = $(this).siblings(options.preview);
                // Bind the value to Preview field
                $(preview).attr('src', url);
            });
        }

        // Usage
        $('.upload').munish(); // Use as default option.
    });		
}(jQuery));
/* Setting Import */
function importSettings() {
	alert('Feature Available in PRO version.');
}
/* Shortcodes Import */
function importShortcodes() {
	alert('Feature Available in PRO version.');
}
function custom_shortcode_Code(){
		var shortcode = jQuery("#custom_map_shortcode").val();
		if(shortcode == '')
		{
			alert("Please Select shortcode.");
		}
		else
		{
		window.send_to_editor(shortcode);
		}
	}