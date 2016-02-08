import Ember from 'ember';
import config from './config/environment';

const Router = Ember.Router.extend({
  location: config.locationType
});

Router.map(function() {
  // This is our catch-all route that can render any existing page
  // from Drupal. It will be used whenever we haven't chosen to
  // implement anything more specific in Ember.
  this.route('drupal-rendered', { path: '*upstream_url' });
});

export default Router;
