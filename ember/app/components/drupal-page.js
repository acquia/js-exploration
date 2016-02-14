import Ember from 'ember';
import ENV from '../config/environment';
const { $ } = Ember;


export default Ember.Component.extend({
  router: Ember.inject.service('routing'),

  didReceiveAttrs() {
    // If we have a new page model, we want to clear any overlaid
    // content when we rerender.
    let page = this.get('page');
    if (page !== this._lastPage) {
      this.set('showingOverlay', false);
    }
  },

  didRender() {
    let page = this.get('page');
    if (page !== this._lastPage) {
      this._lastPage = page;
      let elt = this.$('.drupal-content');
      elt.empty();
      this.get('page').appendTo(elt).then(() => {
        // After the server-rendered page has been inserted, we
        // re-enable any overlaid content so that it can wormhole
        // itself into the server-rendered DOM.
        this.set('showingOverlay', true);
      });
    }
  },

  didInsertElement() {
    // Here we're doing a global thing that would not be idiomatic in
    // a purely client-rendered Ember app. Our component's `click`
    // handler below will already capture clicks within this
    // component's own output, but we _also_ want to capture clicks on
    // any server-rendered links that were in the page before Ember
    // booted up, so we can decide which ones to handle within the
    // Ember app.
    //
    // Note that if somebody manages to click a link before the app
    // has booted, it will still do the right thing by degrading
    // gracefully to a full page reload.
    this._navigationInterceptor = (event) => this.click(event);
    $('body').on('click', 'a', this._navigationInterceptor);
  },

  willDestroyElement() {
    if (this._navigationInterceptor) {
      $('body').off('click', this._navigationInterceptor);
    }
  },

  click(event) {
    // The global click handler above was already delegated to only
    // <a> tags, but we will also get called as a normal
    // component-level `click` handler and we need to find any links
    // that appear within our own page model's output too.
    let target = $(event.target).closest('a');
    if (target.length > 0) {
      let router = this.get('router');
      let href = new URL(target.attr('href'), window.location.href);
      // Ensure that we only try to handle links that fall within the Drupal site.
      if (href.origin === window.location.origin && href.pathname.indexOf(ENV.baseURL) === 0) {
        let localPath = href.pathname.replace(ENV.baseUrl, '/');
        // Check the configuration for routes we're supposed to cede
        // to full Drupal page refreshes.
        if (!(ENV.cedeToDrupal || []).find(pattern => new RegExp(pattern).test(localPath))) {
          let { routeName, params } = router.recognize(localPath);
          router.transitionTo(routeName, ...params);
          event.preventDefault();
          return false;
        }
      }
    }
  }
});
