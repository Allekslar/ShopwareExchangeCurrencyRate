import template from './sw-cms-el-currency-rate.html.twig';
import './sw-cms-el-currency-rate.scss';

const {Component, Mixin} = Shopware;

Component.register('sw-cms-el-currency-rate', {
    template,

    mixins: [
        Mixin.getByName('cms-element')
    ],

    watch: {
        cmsPageState: {
            deep: true,
            handler() {
                this.$forceUpdate();
            }
        },
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('currency-rate');
            this.initElementData('currency-rate');
        },
    }
});
