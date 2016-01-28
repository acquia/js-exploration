import {bootstrap} from 'angular2/platform/browser';
import {
	Component,
	Pipe,
	PipeTransform
} from 'angular2/core';

@Pipe({
	name: 't'
})
class TwigTranslatePipe implements PipeTransform {
	transform(value: string, args: string[]): any {
		return value;
	}
}

@Component({
	selector: 'angular2-comment-list',
	template: 'list of comments'
})
class DrupalCommentList {

}

@Component({
	selector: 'angular2-comment-form',
	template: 'angular2-comment-form'
})
class DrupalCommentForm {

}

@Component({
	// selector: 'angular2-comment-field',
	selector: 'angular2-comment-field',

	templateUrl: '/profiles/js_exploration/themes/baked/templates/field--comment.html.twig',
	// template: TwigHtml,

	directives: [DrupalCommentList, DrupalCommentForm],
	pipes: [TwigTranslatePipe]
})
class DrupalCommentField {

	private comments: string = '<angular2-comment-list></angular2-comment-list>';
	private label: string = 'Comments';
	private title_suffix: string = '(from Angular 2)';
	private label_hidden: boolean = false;
	private field_name: string = 'field_name_1';
	private field_type: string = 'field_type_1';
	private label_display: string = 'visually_hidden';

	// Drupal Template Function
	private attributes = {
		addClass: (args) => {
			return ` class="${args.join(' ')}" `;
		}
	}
	private title_attributes = {
		addClass: (args) => {
			return ` class="${args.join(' ')}" `;
		}
	}

	constructor() {

	}

}

@Component({
	selector: 'angular2-comment',
	template: '<angular2-comment-field></angular2-comment-field>',
	directives: [DrupalCommentField]
})
class DrupalCommentsApp { }

bootstrap(DrupalCommentsApp, [])
  .catch(err => console.error(err));