import template from './sw-cms-el-config-hats-wp-page.html.twig';
import deDE from '../../../../snippet/de-DE';
import enGB from '../../../../snippet/en-GB';

Shopware.Component.register('sw-cms-el-config-hats-wp-page', {
    template,
    inject: ['systemConfigApiService'],
    mixins: [
        'cms-element'
    ],
    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },
    data() {
        return {
            wordpressHomeUrl: '',
            wordpressApiPath: '',
            pages: [],
            currentPage: 1,
            perPage: 10,
        };
    },
    computed: {
        PageSlug: {
            get() {
                return this.element.config.PageSlug.value;
            },

            set(value) {
                this.element.config.PageSlug.value = value;
            }
        }
    },

    created() {
        this.createdComponent();
        this.loadWordpressConfig();
    },
    
    methods: {
        createdComponent() {
            this.initElementConfig('hats-wp-page');
        },

        onElementUpdate(value) {
            this.element.config.PageSlug.value = value;
            this.$emit('element-update', this.element);
        },

        async loadWordpressConfig() {
            try {
              const response = await this.systemConfigApiService.getValues('HatslogicWpIntegration.config');
              if (response) {
                for (var key in response) {
                  if (key.includes('wordpressHomeUrl')) {
                    this.wordpressHomeUrl = response[key];
                  }
                  if (key.includes('wordpressApiPath')) {
                    this.wordpressApiPath = response[key];
                  }
                }
                this.pageLoad();
              }
            } catch (error) {
              // Handle errors here
            }
          },
        
          async pageLoad() {
            const pageApiUrl = `${this.wordpressHomeUrl}/${this.wordpressApiPath}/pages?per_page=${this.perPage}&page=${this.currentPage}`;

            try {
              const response = await fetch(pageApiUrl);
              if (!response.ok) {
                return false;
              } else {
                const data = await response.json();
                this.pages = this.pages.concat(data);
                await this.loadNextPages();
              }
            } catch (error) {
              return false;
            }
          },

          async loadNextPages() {
            this.currentPage++;
            await this.pageLoad();
          }
    }
});