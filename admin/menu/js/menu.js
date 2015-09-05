/**
 * The script for the settings menu.
 *
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin/menu/js
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

(function ( $ ){
    'use strict';

    $(document).ready(function (){

        /*--------------------------------------------------
         * Color Picker
         *------------------------------------------------*/
        $('.frontend_cursorcolor, .backend_cursorcolor, .frontend_cursorbordercolor, .backend_cursorbordercolor').wpColorPicker();

        /*--------------------------------------------------
         * iPhone-like Checkboxes
         *------------------------------------------------*/
        // Prepends a div to the label element.
        var switches = $('.ios-switch');

        for (var i = 0, sw; sw = switches[i++];){
            var div = document.createElement('div');
            div.className = 'switch';
            sw.parentNode.insertBefore(div, sw.nextSibling);
        }

        $('.form-table td').wrapInner('<div class="form-table-td-wrap"></div>');

        /*--------------------------------------------------
         * Fancy Select
         *------------------------------------------------*/
        $('.nsr-fancy-select').fancySelect();

        /*--------------------------------------------------
         * Upper Settings Toggle
         *------------------------------------------------*/
        var toggles = $('form#nsr_form h3');
        var tables = $("table.form-table");
        // Set the button
        var upperToggle = toggles.eq(0);
        upperToggle.addClass('nicescrollr_settings_toggle');

        // Set the element to toggle
        var upperPanel = tables.eq(0);

        //
        upperPanel.addClass('upper-panel');

        // Set the initial display
        upperPanel.css('display', 'inline-block');

        // Toggle the extended settings panel
        upperToggle.click(function (){
            upperPanel.slideToggle(400);
        });

        /*--------------------------------------------------
         * Lower Settings Toggle
         *------------------------------------------------*/
        // Set the button
        var lowerToggle = toggles.eq(1);
        lowerToggle.addClass('nicescrollr_settings_toggle');

        // Set the element to toggle
        var lowerPanel = tables.eq(1);

        // Wrap it for styling purposes
        lowerPanel.addClass('lower-panel');

        // Set the initial display
        lowerPanel.css('display', 'none');

        // Toggle the extended settings panel
        lowerToggle.click(function (){
            lowerPanel.slideToggle(400);
            lowerPanel.css('display', 'inline-block');
        });

        /*--------------------------------------------------
         * backTop
         *------------------------------------------------*/
        if (Options.plugin_backtop_enabled){

            // Adds the element for the button
            $('.settings_page_nicescrollr_settings').after("<a id='backTop' class='dp-backTop'></a>");

            $('#backTop').backTop({ 'position': 400, 'speed': 500, 'color': 'black' });
        }

        /*--------------------------------------------------
         * scrollTo
         *------------------------------------------------*/
        if (Options.plugin_scrollto_enabled){

            if ($('.error a').hasClass('nsr-validation-error')){

                lowerPanel.css('display', 'inline-block');

                var error_link = $('.nsr-validation-error');

                error_link.click(function ( e ){

                    e.preventDefault();

                    if ($(this).index() > Options.basic_options_count){

                        if (lowerPanel.css('display', 'none')){

                            lowerPanel.css('display', 'inline-block');
                        }
                    } else{

                        if (lowerPanel.css('display', 'none')){

                            lowerPanel.css('display', 'inline-block');
                        }
                    }

                    var address = $(this).attr('href');
                    $('input' + address).focus();
                    $(window).scrollTo('input' + address, 400, {offset: -160});
                });
            }

        } else if ($('.error a').hasClass('nsr-validation-error-no-scrollto')){

            lowerPanel.css('display', 'inline-block');
        } else{

            lowerPanel.css('display', 'none');
        }

        /*--------------------------------------------------
         * Localisation for the text on the switches (checkboxes). @todo: May find a less ugly solution...
         *------------------------------------------------*/
        if( Options.locale != 'default') {

            $('<style>:root input[type="checkbox"].ios-switch + div:before{content:"' + Options.On + '";}</style>').appendTo('head');
            $('<style>:root input[type="checkbox"].ios-switch + div:after{content:"' + Options.Off + '";}</style>').appendTo('head');

        }
    });

})(jQuery);
