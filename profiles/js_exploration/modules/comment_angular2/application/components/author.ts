import {Component} from 'angular2/core';
import {BaseUsername} from '../base/username';

@Component({
  selector: 'angular2-author',
  templateUrl: '/profiles/js_exploration/themes/baked/templates/username.html.twig'
})
export class Author extends BaseUsername {

  constructor() {
    super();

    this.name = '<a title="View user profile." href="/user/1" lang="" about="/user/1" typeof="schema:Person" property="schema:name" datatype="" class="username">wchegham</a>';

    this.attributes = ' rel="schema:author" ';
  }
}
