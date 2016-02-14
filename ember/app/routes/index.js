import DrupalRendered from './drupal-rendered';

export default DrupalRendered.extend({
  model() {
    // This is just a special case of the "drupal-rendered" route
    // where upstream_url is the empty string.
    return this._super({ upstream_url: '' });
  }
});
