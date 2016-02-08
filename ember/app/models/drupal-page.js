import DS from 'ember-data';
import Ember from 'ember';
const { RSVP } = Ember;

export default DS.Model.extend({
  text: DS.attr(),

  appendTo() {
    // TODO
    return RSVP.resolve();
  },

  embeddedComponents: Ember.computed(function() {
    // TODO
    return [];
  })
});
