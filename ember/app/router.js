import Ember from 'ember';
import config from './config/environment';

const Router = Ember.Router.extend({
  location: config.locationType
});

Router.map(function() {
  // This is our catch-all route that can render any existing page
  // from Drupal. It will be used whenever we haven't chosen to
  // implement anything more specific in Ember. The `upstream_url` here
  // is a string like "node/123".
  this.route('drupal-rendered', { path: '*upstream_url' });

  // Every Ember app gets an "index" route by default, and that's
  // what's handling our homepage. It delegates to Drupal the same way
  // the `drupal-rendered` route does.
});

export default Router;
