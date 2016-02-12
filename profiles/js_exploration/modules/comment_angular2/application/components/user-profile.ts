import {Component} from 'angular2/core';
import {BaseUser} from '../base/user';

@Component({
  selector: 'angular2-user-picture',
  templateUrl: '/profiles/js_exploration/themes/baked/templates/user.html.twig'
})
export class UserProfile extends BaseUser {

  constructor() {
    super();

    this.attributes = {
      addClass: (cls) => ` class="${cls}" `,
      toString: () => [
        '',
        'data-quickedit-entity-id="user/1"',
        'typeof="schema:Person"',
        'about="/user/1"',
        'class="profile"',
        'data-quickedit-entity-instance-id="0"',
        ''
      ].join(' ')
    };

    // TODO(wassim) componentize this content
    this.content = `
      <div data-quickedit-field-id="user/1/user_picture/en/compact" class="field field--name-user-picture field--type-image field--label-hidden field__item">
        <a href="/user/1">
          <img src="/sites/default/files/styles/thumbnail/public/s01_1.jpg?itok=akN8l6h9" width="80" height="80" alt="Profile picture for user wchegham" typeof="foaf:Image" class="image-style-thumbnail">
        </a>
      </div>
    `;

  }

}
