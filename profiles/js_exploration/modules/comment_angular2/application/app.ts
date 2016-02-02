import {bootstrap} from 'angular2/platform/browser';
import {CommentsBlock} from './components/comments-block';

if (window.location.search.indexOf('angular=true')>=0) {
	Twig.trace = true;
	bootstrap(CommentsBlock, [])
	  .catch(err => console.error(err));
}
