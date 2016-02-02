import {Component} from 'angular2/core';
import {FieldComment} './field-comment';
import {CommonCommentFields} './common-comment-fields';

@Component({
	selector: '#block-baked-content > article > div.node__content > section',
	templateUrl: '/profiles/js_exploration/themes/baked/templates/comment.html.twig',
	host: {
		'class': 'angular-component'
	},
	styles: [`
		section { background: yellow; }
	`],
	directives: [FieldComment]
})
export class CommentsBlock extends CommonCommentFields {

	constructor() {
		super();

		this.status = 'published';

		this.comment = {
			owner: {
				anonymous: false
			}
		};

		this.user_picture = `
<article data-quickedit-entity-id="user/1" typeof="schema:Person" about="/user/1" class="profile" data-quickedit-entity-instance-id="0">
  <div data-quickedit-field-id="user/1/user_picture/en/compact" class="field field--name-user-picture field--type-image field--label-hidden field__item">
  	<a href="/user/1"><img src="/sites/default/files/styles/thumbnail/public/s01_1.jpg?itok=akN8l6h9" width="80" height="80" alt="Profile picture for user wchegham" typeof="foaf:Image" class="image-style-thumbnail"></a>
	</div>
</article>
		`;

		this.author = `
<span rel="schema:author">
	<a title="View user profile." href="/user/1" lang="" about="/user/1" typeof="schema:Person" property="schema:name" datatype="" class="username">wchegham</a>
</span>
		`;

		this.created = '2 days ago';

		this.title = 'My feedback';

		this.content = {
			links: {
				toString: () => `
<ul class="links inline">
	<li class="comment-delete">
		<a href="/comment/2/delete" hreflang="en">Delete</a>
	</li>
	<li class="comment-edit">
		<a href="/comment/2/edit" hreflang="en">Edit</a>
	</li>
	<li class="comment-reply">
		<a href="/comment/reply/node/2/comment/2">Reply</a>
	</li>
	<li class="comment-translations">
		<a href="/comment/2/translations" hreflang="en">Translate</a>
	</li>
</ul>
				`
			},
			toString: () => `
<div property="schema:text" data-quickedit-field-id="comment/2/comment_body/en/full" class="clearfix text-formatted field field--name-comment-body field--type-text-long field--label-hidden field__item">
	<p>Viral organic ugh pickled squid authentic. 
Try-hard selfies cornhole hammock flannel, dreamcatcher chillwave yuccie before they sold out. 
Disrupt mlkshk food truck four loko kinfolk. 
Scenester VHS pinterest cardigan meh. 
Selvage lumbersexual pop-up, food truck affogato four loko fanny pack four dollar toast leggings drinking vinegar gastropub master cleanse +1 skateboard. 
Kale chips beard thundercats austin. Health goth single-origin coffee kale chips, next level sartorial scenester jean shorts vice four loko squid ugh tattooed pork belly.
	</p>
</div>
		`
		};


	}

}
