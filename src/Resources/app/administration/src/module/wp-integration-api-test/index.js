const { Component, Mixin } = Shopware;
import template from './api-test-button.html.twig';
import deDE from '../snippet/de-DE';
import enGB from '../snippet/en-GB';

Component.register('wp-api-test-button', {
  template,
  inject: ['WPIntegrationApiService'],
  mixins: [
    Mixin.getByName('notification')
  ],
  snippets: {
    'de-DE': deDE,
    'en-GB': enGB
  },
  data() {
    return {
      isLoading: false,
      isSaveSuccessful: false
    };
  },
  methods: {
    saveFinish() {
      this.isSaveSuccessful = false;
    },

    checkApi() {
        this.isLoading = true;
      var wordpressHomeUrl = document.getElementById('HatslogicWpIntegration.config.wordpressHomeUrl').value;
      var wordpressApiPath = document.getElementById('HatslogicWpIntegration.config.wordpressApiPath').value;
      if (wordpressHomeUrl == '') {
        this.isLoading = false;
        this.createNotificationError({
          message: this.$tc('wp-integration.validation.sorry_wordpress_home_url_is_missing')
        });
      } else if (wordpressApiPath == '') {
        this.isLoading = false;
        this.createNotificationError({
          message: this.$tc('wp-integration.validation.sorry_wordpress_api_path_is_missing')
        });
      }  else if(!this.validURL(wordpressHomeUrl)){
        this.isLoading = false;
        this.createNotificationError({
          message: this.$tc('wp-integration.validation.sorry_wordpress_api_url_is_not_valid')
        });
      } else {
        var postApiUrl = wordpressHomeUrl + '/' + wordpressApiPath + '/posts?per_page=1';
        var pageApiUrl = wordpressHomeUrl + '/' + wordpressApiPath + '/pages?per_page=1';
        this.sendGetRequest(postApiUrl)
          .then(data => {
            if(data.length > 0){
                this.sendGetRequest(pageApiUrl)
                .then(data => {
                    this.isLoading = false;
                    if(data.length > 0){
                        this.createNotificationSuccess({
                            message: this.$tc('wp-integration.validation.perfect_wordpress_api_url_is_valid')
                        });
                    }else{
                        this.createNotificationWarning({
                            message: this.$tc('wp-integration.validation.success_but_page_can_not_be_fetch')
                        });
                        
                    }
                });
            }else{
                this.isLoading = false;
                this.createNotificationWarning({
                    message: this.$tc('wp-integration.validation.success_but_post_can_not_be_fetch')
                });
            }
          });
      }
    },
    validURL(str) {
      var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
        "(\\:\\d+)?(\\/[-a-z\\d%_.~+'()]*)*" + // port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
        '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator
      return !!pattern.test(str);
    },
    sendGetRequest(url) {
      return fetch(url)
        .then(response => {
            if (!response.ok) {
                this.isLoading = false;
                this.createNotificationError({
                  message: this.$tc('wp-integration.validation.sorry_wordpress_api_url_is_not_valid')
                });
            } else {
                return response.json();
            }
        })
        .catch(error => {
            this.isLoading = false;
            this.createNotificationError({
                message: this.$tc('wp-integration.validation.sorry_wordpress_api_url_is_not_valid')
            });
        });
    }
  }
})
