/**
 * Copyright © CO-WELL Co., LTD. All rights reserved.
 * Copyright © CO-WELL ASIA Co., LTD. All rights reserved.
 * 
 * Licensed under the Open Software License version 3.0
 * See LICENSE.txt and COPYING.txt for license details.
 */
require([
        'jquery',
        'Magento_Ui/js/modal/confirm',
        'mage/translate'
    ],
    function ($, confirmation, $t) {
        var delete_all = $('#delete-all-logs');
        var link = delete_all.attr('onclick');
        delete_all.removeAttr('onclick');
        delete_all.on('click', function () {
            confirmation({
                title: $t('Delete items'),
                content: $t('Are you sure you wan\'t to delete selected items?'),
                actions: {
                    confirm: function () {
                        eval(link);
                    },
                }
            });
            return false;
        });
        $('body').on('click', '[data-action="item-preview"]', function () {
            window.open($(this).attr('href'), "_blank", "scrollbars=1,resizable=1,top=100,left=100,width=1024,height=700");
            return false;
        });
    });
