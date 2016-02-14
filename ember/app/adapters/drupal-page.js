import DS from 'ember-data';
import fetch from 'ember-network/fetch';
import { urlFromId } from '../models/drupal-page';

export default DS.Adapter.extend({
  findRecord(store, type, id /*, snapshot */) {
    return fetch(urlFromId(id)).then(response => response.text());
  }
});
