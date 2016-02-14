import Ember from 'ember';
const { $, RSVP } = Ember;

export default Ember.Component.extend({
  drupalLinks: Ember.inject.service(),

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
      if (!page.prerendered) {
        this.get('drupalLinks').clearPage();
      }
      elt.empty();
      this.appendPage(elt, this.get('page')).then(() => {
        // After the server-rendered page has been inserted, we
        // re-enable any overlaid content so that it can wormhole
        // itself into the server-rendered DOM.
        this.set('showingOverlay', true);
      });
    }
  },

  appendPage($elt, page) {
    return new RSVP.Promise(resolve => {
      if (!page.prerendered) {
        $elt.html(`appended page ${page.get('id')}`);
      }
      resolve();
    });
  },

  click(event) {
    let target = $(event.target).closest('a');
    if (target.length > 0) {
      if (this.get('drupalLinks').maybeTransition(target.attr('href'))) {
        event.preventDefault();
        return false;
      }
    }
  }
});
