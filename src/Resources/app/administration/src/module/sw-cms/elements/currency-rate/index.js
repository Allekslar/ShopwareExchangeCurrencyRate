import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'currency-rate',
    label: 'currency-rate.label',
    component: 'sw-cms-el-currency-rate',
    configComponent: 'sw-cms-el-config-currency-rate',
    previewComponent: 'sw-cms-el-preview-currency-rate',
    defaultConfig: {
        thumbnailurl: {
            source: 'static',
            value: ''
        },
        textAlign: {
            source: 'static',
            value: null
        },
        titleTextAlign: {
            source: 'static',
            value: null
        }

    }
});
