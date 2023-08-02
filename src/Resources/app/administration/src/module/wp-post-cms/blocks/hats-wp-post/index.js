import './component';
import './preview';
Shopware.Service('cmsService').registerCmsBlock({
    name: 'hats-wp-post',
    category: 'text-image',
    label: 'wp-integration.cms.wordpress_post',
    component: 'sw-cms-block-hats-wp-post',
    previewComponent: 'sw-cms-preview-hats-wp-post',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed'
    },
    slots: {
        content: 'hats-wp-post'
    }
});