import Ember from 'ember';

export default Ember.Route.extend({
  drupalLinks: Ember.inject.service(),
  beforeModel() {
    this.get('drupalLinks').recordInitialHtml();
  }
});
