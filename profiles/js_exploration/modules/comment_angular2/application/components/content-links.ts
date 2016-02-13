import {Component} from 'angular2/core';
import {BaseLinks} from '../base/links';
import {TwigTranslatePipe} from '../pipes/twig-translate';

@Component({
  selector: 'angular2-content-links',
  templateUrl: '/profiles/js_exploration/themes/baked/templates/links.html.twig',
  pipes: [TwigTranslatePipe]
})
export class ContentLinks extends BaseLinks {

  constructor() {
    super();

    this.attributes = ` class="links inline angular2-links" `;

    this.links = [{
      attributes: {addClass: (k) => ' class="comment-delete" '},
      link: `<a href="/comment/2/delete" hreflang="en">{{ 'Delete' | t }}</a>`
    }, {
      attributes: {addClass: (k) => ' class="comment-edit" '},
      link: `<a href="/comment/2/edit" hreflang="en">{{ 'Edit' | t }}</a>`
    }, {
      attributes: {addClass: (k) => ' class="comment-reply" '},
      link: `<a href="/comment/reply/node/2/comment/2">{{ 'Reply' | t }}</a>`
    }, {
      attributes: {addClass: (k) => ' class="comment-translations" '},
      link: `<a href="/comment/2/translations" hreflang="en">{{ 'Translate' | t }}</a>`
    }];
  }

}
