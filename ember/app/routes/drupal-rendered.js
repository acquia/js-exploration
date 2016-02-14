import Ember from 'ember';

export default Ember.Route.extend({
  drupalLinks: Ember.inject.service(),

  model({ upstream_url }) {
    let id = '/' + upstream_url;
    if (this.get('drupalLinks').alreadyOnPage(id)) {
      return this.store.createRecord('drupal-page', { prerendered: true });
    } else {
      return this.store.find('drupal-page', id);
    }
  }
});
