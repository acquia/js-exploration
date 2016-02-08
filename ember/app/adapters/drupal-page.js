import DS from 'ember-data';
import fetch from 'ember-network/fetch';

export default DS.Adapter.extend({
  findRecord(store, type, id /*, snapshot */) {
    return fetch('/' + id.replace(/^\//, ''))
      .then(response => response.text());
  }
});
