/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   BSS
 * @package    Bss_MultiStoreViewPricing
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/view/minicart': {
                'Bss_MultiStoreViewPricing/js/view/minicart': true
            },
            'Magento_Wishlist/js/view/wishlist': {
                'Bss_MultiStoreViewPricing/js/view/wishlist_mixin': true
            },
        }
    }
};
