import Ember from 'ember';

export default Ember.Route.extend({
  model({ upstream_url }) {
    return this.store.find('drupal-page', upstream_url);
  }
});
