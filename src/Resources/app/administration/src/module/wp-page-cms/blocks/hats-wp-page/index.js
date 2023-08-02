import './component';
import './preview';
Shopware.Service('cmsService').registerCmsBlock({
    name: 'hats-wp-page',
    category: 'text-image',
    label: 'wp-integration.cms.wordpress_page',
    component: 'sw-cms-block-hats-wp-page',
    previewComponent: 'sw-cms-preview-hats-wp-page',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed'
    },
    slots: {
        content: 'hats-wp-page'
    }
});