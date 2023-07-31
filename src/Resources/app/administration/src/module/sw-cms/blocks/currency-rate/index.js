import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'currency-rate',
    label: 'aleks-wsdev.blocks.currency-rate.label',
    category: 'aleks-wsdev',
    component: 'sw-cms-block-currency-rate',
    previewComponent: 'sw-cms-preview-currency-rate',
    defaultConfig: {
        marginBottom: '10px',
        marginTop: '10px',
        marginLeft: '0',
        marginRight: '0',
        sizingMode: 'boxed'
    },
    slots: {
        content: 'currency-rate'
    },

});
