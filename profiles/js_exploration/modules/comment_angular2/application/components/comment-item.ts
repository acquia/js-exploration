console.log('comment-item')
import {Component} from 'angular2/core';
import {ViewEncapsulation} from 'angular2/core';


@Component({
	selector: 'comment-item',
	template: `
<div>
Comment block items
</div>

	`,
	

	styles:[`
	div {border: thin solid #f00;
	padding:15px;
	}
	`],
	encapsulation: ViewEncapsulation.Emulated
})
export class CommentItem {}
