import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'hats-wp-post',
    label: "wp-integration.cms.wordpress_post",
    component: 'sw-cms-el-hats-wp-post',
    configComponent: 'sw-cms-el-config-hats-wp-post',
    previewComponent: 'sw-cms-el-preview-hats-wp-post',
    defaultConfig: {
        PagePostLimit: {
            source: 'static',
            value: 24
        },
        PageTitleLimit: {
            source: 'static',
            value: 150
        },
        DesktopColumnCount: {
            source: 'static',
            value: 3
        },
        TabletColumnCount: {
            source: 'static',
            value: 2
        },
        MobileColumnCount: {
            source: 'static',
            value: 1
        }
    }
});