import {Component} from 'angular2/core';
import {FormChildren} from './form-children';

@Component({
	selector: 'angular2-form-container',
	templateUrl: '/profiles/js_exploration/themes/baked/templates/form.html.twig',
  directives: [FormChildren]
})
export class FormContainer {

	constructor() {
		this.attributes = [
      '',
      'class="comment-comment-form comment-form"',
      'data-drupal-selector="comment-form"',
      'action="/comment/reply/node/2/comment"',
      'method="post"',
      'id="comment-form"',
      'accept-charset="UTF-8"',
      'data-drupal-form-fields="edit-comment-body-0-value,edit-comment-body-0-format--2,edit-submit,edit-preview"',
      ''
    ].join(' ');
		this.children = '<angular2-form-children></angular2-form-children>';
	}

}
