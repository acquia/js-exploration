import {bootstrap} from 'angular2/platform/browser';
import {CommentSection} from './components/comments-section';

function boot(){
	Twig.trace = true;
	bootstrap(CommentSection, [])
	.catch(err => console.error(err));
}

// debugging purpose

let search = window.location.search;
if (search.indexOf('angular=true')>=0) {
	boot();
}
else if (search.indexOf('angular=delay')>=0) {
	setTimeout(boot, 3000);
}
else {
	// don't bootstrap angular2
}
