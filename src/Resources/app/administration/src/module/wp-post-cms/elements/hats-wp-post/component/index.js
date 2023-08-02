import template from './sw-cms-el-hats-wp-post.html.twig';
import './sw-cms-el-hats-wp-post.scss';

Shopware.Component.register('sw-cms-el-hats-wp-post', {
    template,

    mixins: [
        'cms-element'
    ],

    computed: {
        PagePostLimit() {
            return this.element.config.PagePostLimit.value;
        },
        PageTitleLimit() {
            return this.element.config.PageTitleLimit.value;
        },
        DesktopColumnCount() {
            return this.element.config.DesktopColumnCount.value;
        },
        TabletColumnCount() {
            return this.element.config.TabletColumnCount.value;
        },
        MobileColumnCount() {
            return this.element.config.MobileColumnCount.value;
        }
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('hats-wp-post');
        }
    }
});