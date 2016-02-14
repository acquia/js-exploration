import DS from 'ember-data';
import Ember from 'ember';
import ENV from '../config/environment';

export default DS.Model.extend({
  text: DS.attr(),

  embeddedComponents: Ember.computed(function() {
    // TODO
    return [];
  })
});

// A Drupal page's ID is its path relative to the Drupal site / Ember
// application, like "/node/123" or "/".
export function idFromUrl(href) {
  return new URL(href, window.location.href).pathname.replace(ENV.baseUrl, '/');
}
export function urlFromId(id) {
  return new URL(window.location.href).origin + ENV.baseURL + id;
}
