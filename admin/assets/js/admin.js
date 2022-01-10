(function($) {
    'use strict';
    $(document).ready(function() {

        
        $('select#ext_product_ids,select#product_tag_ids,select.ext_select2,select.internal_select,select.ua_select product_includes_excludes').on('select2:select', function(e){
            extSelectItem(e.target, e.params.data.id);
          });
  
          var removeAjax = function(aaa,bb){
                  $('.button,button').removeClass('ext_ajax_update');
              };
        //code for Sortable
        $( ".ext_column_sortable" ).sortable({
            handle:'.handle',
            stop: removeAjax,
        });
        $( ".checkbox_parent" ).sortable({
            handle:this,
            stop: removeAjax,
        });
        $( ".ext_responsive_each_wraper" ).sortable({handle:this});
        
        
        $(document).on('click','.colum_data_input',function(){
            var parents = $(this).parents('.ext_shortable_data');
            var onOff = parents.children('.extra_all_on_off');
            var extras = parents.children('.ext_column_setting_extra');
            extras.toggle('medium',function(){
                var status = $(this).attr('data-status');
                if(status == 'expanded'){
                    onOff.removeClass('off_now');
                    onOff.addClass('on_now');
                    onOff.html("Expand");
                    onOff.attr('data-status','expanded');
                    extras.parents('li').removeClass('expanded_li');
                    $(this).attr('data-status','collapsed');
                }else{
                    
                    onOff.removeClass('on_now');
                    onOff.addClass('off_now');
                    onOff.html("Collapse");
                    onOff.attr('data-status','collapsed');
                    extras.parents('li').addClass('expanded_li');
                    $(this).attr('data-status','expanded');
                }
                
            });
        });
        
        $(document).on('click','span.extra_all_on_off',function(){
            var key = $(this).data('key');
            var thisExpand = $(this);
            $('.ext_column_setting_extra.extra_all_' + key).toggle('medium',function(){
                var status = thisExpand.attr('data-status');
                if(status == 'expanded'){
                    thisExpand.removeClass('off_now');
                    thisExpand.addClass('on_now');
                    thisExpand.parents('li').removeClass('expanded_li');
                    thisExpand.html("Expand");
                    thisExpand.attr('data-status','collapsed');
                }else{
                    thisExpand.removeClass('on_now');
                    thisExpand.addClass('off_now');
                    thisExpand.parents('li').addClass('expanded_li');
                    thisExpand.html("Collapse");
                    thisExpand.attr('data-status','expanded');
                }
                
                
                
            });
            
        });
        $(document).on('click','ul#ext_column_sortable>li.ext_sortable_peritem.enabled .ext_column_arrow',function(){
            var target = $(this).attr('data-target');
            var keyword = $(this).attr('data-keyword');
            var thisElement = $(this).closest('li.ext_sortable_peritem.enabled');
            var prev = thisElement.prev();
            var prevClass = prev.attr('class');
            var next = thisElement.next();
            var nextClass = next.attr('class');
            console.log(target);
            //console.log(typeof prev, typeof next, typeof thisElement);
            if( target == 'next' && typeof next.html() !== 'undefined'){
                thisElement.before('<li class="' + nextClass + '">'+next.html()+'</li>');
                next.remove();
            }
            if( target == 'prev' && typeof prev.html() !== 'undefined'){
                thisElement.after('<li class="' + prevClass + '">'+prev.html()+'</li>');
                prev.remove();
            }
        });
        
        $('.ext_copy_button_metabox').click(function(){
            var ID_SELECTOR = $(this).data('target_id');
            copyMySelectedITem(ID_SELECTOR);
        });
          
        $('.ext_copy_button_metabox').click(function(){
            var ID_SELECTOR = $(this).data('target_id');
            copyMySelectedITem(ID_SELECTOR);
        });
        //ext_metabox_copy_content
        function copyMySelectedITem(ID_SELECTOR) {
          var copyText = document.getElementById(ID_SELECTOR);
          copyText.select();
          document.execCommand("copy");
          $('.' + ID_SELECTOR).html("Copied");
          $('.' + ID_SELECTOR).fadeIn();
          
          var myInterVal = setInterval(function(){
              $('.' + ID_SELECTOR).html("");
              $('.' + ID_SELECTOR).fadeOut();
              clearInterval(myInterVal);
          },1000);
        }

        
        /**
         * Inside Tab of Column
         * 
         * @type String
         */
                $('body').on('click','#ext_configuration_form .inside-nav-tab-wrapper a', function(){
                $('.inside-nav-tab-wrapper a.nav-tab-active').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');
                var target_tab = $(this).data('target');
                $('.inside_tab_content.tab-content-active').removeClass('tab-content-active');
                $('.inside_tab_content#'+target_tab).addClass('tab-content-active');
            });
            /**************Admin Panel's Setting Tab Start Here For Tab****************/
            var selectLinkTabSelector = "body #ext_configuration_form a.ext_nav_tab";
            var selectTabContentSelector = "body #ext_configuration_form .ext_tab_content";
            var selectLinkTab = $(selectLinkTabSelector);
            var selectTabContent = $(selectTabContentSelector);
            var tabName = window.location.hash.substr(1);
            if (tabName) {
                removingActiveClass();
                $('body #ext_configuration_form #' + tabName).addClass('tab-content-active');
                $('body #ext_configuration_form .nav-tab-wrapper a.ext_nav_tab.ext_nav_for_' + tabName).addClass('nav-tab-active');
            }
            
            $('body').on('click','#ext_configuration_form a.ext_nav_tab',function(e){
                e.preventDefault(); //Than prevent for click action of hash keyword
                var targetTabContent = $(this).data('tab');//getting data value from data-tab attribute
                
                // Detect if pushState is available
                if(history.pushState) {
                    history.pushState(null, null, $(this).attr('href'));
                }
                removingActiveClass();
                $(this).addClass('nav-tab-active');
                $('body #ext_configuration_form #' + targetTabContent).addClass('tab-content-active');
                return false;
            });
    
            /**
             * Removing current active nav_tab and tab_content element
             * 
             * @returns {nothing}
             */
            function removingActiveClass() {
                selectLinkTab.removeClass('nav-tab-active');
                selectTabContent.removeClass('tab-content-active');
                return false;
            }
        
        /**************Admin Panel's Setting Tab End Here****************/
                
    });
})(jQuery);