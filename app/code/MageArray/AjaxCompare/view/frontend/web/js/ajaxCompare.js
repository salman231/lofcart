define([
    "jquery",
	'Magento_Ui/js/modal/modal',
    "jquery/ui"
], function (jQuery,modal) {
    "use strict";
    jQuery.widget('ajaxCompare.js', {

        _create: function () {
			var showbox=this.options.showbox;
			if(showbox){
				 this.createCompareBox(); 
			}
			jQuery(".tocompare, .comparisonProductBox .action.delete, .table-comparison .action.delete").on('click', this, this.addCompare);
			
        },
		createCompareBox: function () {
                var actionUrl = this.options.url;
               	
                jQuery.ajax({
                    url: actionUrl,
                    data: '',
                    type: 'post',
                    dataType: 'json',
                    showLoader: true,
                    success: function (result) {
						if(result.compare_popup!=null){
							jQuery('.get_compare_list_bar').html(result.compare_popup);
						}else{
							jQuery('.get_compare_list_bar').html("");
						}
                    }
                }); 
        },
		addCompare: function (event) {
			event.stopImmediatePropagation();
            jQuery('#popup_content').modal('closeModal');
            if (jQuery(this).data('post')) {

                var actionUrl = jQuery(this).data('post').action;
                var params = jQuery(this).data('post').data;
                params.form_key = jQuery('input[name=form_key]').val();
                var self = this,
                    modelClass = "cartDetails cartBox";
								
                var options =
                {
                    type: 'popup',
                    modalClass: modelClass,
                    responsive: true,
                    innerScroll: true,
                    title: false,
                    buttons: false
                };
                jQuery.ajax({
                    url: actionUrl,
                    data: params,
                    type: 'post',
                    dataType: 'json',
                    showLoader: true,
                    success: function (result) {
						if(result.compare_popup!=null){
							jQuery('.get_compare_list_bar').html(result.compare_popup);
						}else{
							jQuery('.get_compare_list_bar').html("");
						}
						if(result.product_remain==0){
							jQuery('.catalog-product_compare-index').find(".column.main").hide();
						}else{
							if(result.product){
								var elm=jQuery('body').find('[data-product-id="'+result.product+'"]').parent("td");
								elm.hide();
								jQuery('[data-product="'+result.product+'"]').hide();
							}
						}
						
                    }
                }); 
            }
        },
		addDelete: function (elm) {
            jQuery('#popup_content').modal('closeModal'); 
            if (elm.data('post')) {
                var actionUrl = elm.data('post').action;
                var params = elm.data('post').data;
                params.form_key = jQuery('input[name=form_key]').val();
                var self = elm,
                    modelClass = "cartDetails cartBox";
								
                var options =
                {
                    type: 'popup',
                    modalClass: modelClass,
                    responsive: true,
                    innerScroll: true,
                    title: false,
                    buttons: false
                };
                jQuery.ajax({
                    url: actionUrl,
                    data: params,
                    type: 'post',
                    dataType: 'json',
                    showLoader: true,
                    success: function (result) {
						if(result.compare_popup!=null){
							jQuery('.get_compare_list_bar').html(result.compare_popup);
						}else{
							jQuery('.get_compare_list_bar').html("");
						}
						
                    }
                }); 
            }
        }
    });
    return jQuery.ajaxCompare.js;
});