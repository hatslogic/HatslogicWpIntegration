import template from './sw-cms-el-hats-wp-page.html.twig';
import './sw-cms-el-hats-wp-page.scss';

Shopware.Component.register('sw-cms-el-hats-wp-page', {
    template,

    mixins: [
        'cms-element'
    ],

    computed: {
        PageSlug() {
            return this.element.config.PageSlug.value;
        }
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('hats-wp-page');
        }
    }
});