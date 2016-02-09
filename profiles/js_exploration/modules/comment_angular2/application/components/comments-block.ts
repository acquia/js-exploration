//Need help how the angular-comments-block DOM tag can be placed in the template

var x = document.querySelector('#block-baked-content > article > div.node__content > section')
x.innerHTML = '<angular-comments-block> angular come on</angular-comments-block>'



import {Component} from 'angular2/core';
import {CommentItem} from'./comment-item';
import {CommentsService} from '../core/comments.service'
import {Http, HTTP_PROVIDERS} from 'angular2/http';
import {FieldComment} from './field-comment';
import {CommonCommentFields} './common-comment-fields';

@Component({
	selector: 'angular-comments-block',
	providers: [CommentsService, HTTP_PROVIDERS],
	directives:[CommentItem],
	template: `
	<i>-----<u>Angular Component</u>-------</i>
<hr/>
<div>
<pre>{{comments|json}}</pre>
<comment-item></comment-item>
</div>
<hr/>
<angular2-comment-field></angular2-comment-field>

`
	,
	styles: [`
		section { background: yellow; }
	`],
	directives: [FieldComment, CommentItem]
})
export class CommentsBlock  {
	public comments: Object;
	
	constructor(_commentsService: CommentsService) {

		this._commentsService = _commentsService
	}


ngOnInit(){
	this.getComments();

}

getComments(){

	// hardcoding the page node id, will make it dynamic later
	this._commentsService.getComments(2).subscribe(
data =>{this.comments = data})


}

//		this.comments = _commentsService.getComments(2)
//console.log(this.comments)
	

	
}
