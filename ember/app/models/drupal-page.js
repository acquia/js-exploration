import DS from 'ember-data';
import Ember from 'ember';
import ENV from '../config/environment';

export default DS.Model.extend({
  htmlParser: Ember.inject.service(),
  text: DS.attr(),

  document: Ember.computed('text', function() {
    return this.get('htmlParser').parse(this.get('text'));
  }),

  title: Ember.computed('document', function() {
    let titleTag = this.get('document').querySelector('title');
    if (titleTag) {
      return titleTag.innerHTML;
    }
  }),

  content: Ember.computed('document', function() {
    let body = importNode(this.get('document').querySelector('body'));
    return Array.from(body.childNodes);
  }),

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

function importNode(node) {
  const DEEP_COPY = true;
  return window.document.importNode(node, DEEP_COPY);
}
