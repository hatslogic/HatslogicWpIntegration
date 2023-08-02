import template from './sw-cms-el-config-hats-wp-post.html.twig';
import deDE from '../../../../snippet/de-DE';
import enGB from '../../../../snippet/en-GB';

Shopware.Component.register('sw-cms-el-config-hats-wp-post', {
    template,
    mixins: ['cms-element'],
    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
      },
    computed: {
        PagePostLimit: {
            get() {
                return this.element.config.PagePostLimit.value;
            },
            set(value) {
                this.element.config.PagePostLimit.value = value;
            },
        },
        PageTitleLimit: {
            get() {
                return this.element.config.PageTitleLimit.value;
            },
            set(value) {
                this.element.config.PageTitleLimit.value = value;
            },
        },
        DesktopColumnCount: {
            get() {
                return this.element.config.DesktopColumnCount.value;
            },
            set(value) {
                this.element.config.DesktopColumnCount.value = value;
            },
        },
        TabletColumnCount: {
            get() {
                return this.element.config.TabletColumnCount.value;
            },
            set(value) {
                this.element.config.TabletColumnCount.value = value;
            },
        },
        MobileColumnCount: {
            get() {
                return this.element.config.MobileColumnCount.value;
            },
            set(value) {
                this.element.config.MobileColumnCount.value = value;
            },
        },
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('hats-wp-post');
        },

        onElementUpdate() {
            this.$emit('element-update', this.element);
        },
    },
});
