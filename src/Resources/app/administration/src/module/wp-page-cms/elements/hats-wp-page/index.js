import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'hats-wp-page',
    label: 'wp-integration.cms.wordpress_page',
    component: 'sw-cms-el-hats-wp-page',
    configComponent: 'sw-cms-el-config-hats-wp-page',
    previewComponent: 'sw-cms-el-preview-hats-wp-page',
    defaultConfig: {
        PageSlug: {
            source: 'static',
            value: ''
        }
    }
});