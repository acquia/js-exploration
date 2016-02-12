import {Component} from 'angular2/core';
import {BaseLinks} from '../base/links';

@Component({
  selector: 'angular2-content-links',
  templateUrl: '/profiles/js_exploration/themes/baked/templates/links.html.twig'
})
export class ContentLinks extends BaseLinks {

  constructor() {
    super();

    this.attributes = ` class="links inline angular2-links" `;

    this.links = [{
      attributes: { addClass: (k) => ' class="comment-delete" ' },
      link: '<a href="/comment/2/delete" hreflang="en">Delete</a>'
    }, {
      attributes: { addClass: (k) => ' class="comment-edit" ' },
      link: '<a href="/comment/2/edit" hreflang="en">Edit</a>'
    }, {
      attributes: { addClass: (k) => ' class="comment-reply" ' },
      link: '<a href="/comment/reply/node/2/comment/2">Reply</a>'
    }, {
      attributes: { addClass: (k) => ' class="comment-translations" ' },
      link: '<a href="/comment/2/translations" hreflang="en">Translate</a>'
    }];
  }

}
