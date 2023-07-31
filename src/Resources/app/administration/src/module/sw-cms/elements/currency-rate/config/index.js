import template from './sw-cms-el-config-currency-rate.html.twig';
import './sw-cms-el-config-currency-rate.scss';

const {Component, Mixin} = Shopware;

Component.register('sw-cms-el-config-currency-rate', {
    template,

    mixins: [
        Mixin.getByName('cms-element')
    ],

    inject: ['repositoryFactory'],

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('currency-rate');
        },
    }
});

