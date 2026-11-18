import template from './sw-product-detail-specifications.html.twig';

Shopware.Component.override('sw-product-detail-specifications', {
    template,

    computed: {
        product() {
            return Shopware.Store.get('swProductDetail').product;
        },

        parentProduct() {
            return Shopware.Store.get('swProductDetail').parentProduct;
        },
    },

    watch: {
        product: {
            immediate: true,
            handler(newProduct) {
                if (newProduct && (!newProduct.customFields || typeof newProduct.customFields !== 'object')) {
                    newProduct.customFields = {};
                }
            },
        },
    },
});
