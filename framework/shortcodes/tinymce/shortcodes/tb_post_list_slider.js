/**
 * Optional arguments
 *  - fx: Transition of slider - fade, slide
 *  - timeout: Seconds in between transitions, 0 for no auto-advancing
 *  - nav_standard: Show standard nav dots to control slider - true or false
 *  - nav_arrows: Show directional arrows to control slider - true or false
 *  - pause_play: Show pause/play button - true or false
 *  - categories: Categories to include, category slugs separated by commas, or blank for all categories
 *  - thumbs: Size of post thumbnails - default, small, full, hide
 *  - post_content: Show excerpts or full content - default, content, excerpt
 *  - posts_per_slide: Number of posts per slide.
 *  - numberposts: Total number of posts, -1 for all posts
 *  - orderby: post_date, title, comment_count, rand
 *  - order: DESC, ASC
 *  - offset: number of posts to offset off the start, defaults to 0
 */
themeblvdShortcodeAtts={
	attributes:[
		{
			label:"Transition Effect",
			id:"fx",
			help:"Select how you'd like the slides to transition.", 
			controlType:"select-control", 
			selectValues:['slide', 'fade']
		},
		{
			label:"Seconds in between transitions?",
			id:"timeout",
			value:"0",
			help:"Enter in the seconds in between slide transitions. Enter 0 for the slider to not auto-advance."
		},
		{
			label:"Show standard navigation?",
			id:"nav_standard",
			help:"These are the standard slider navigation dots.", 
			controlType:"select-control", 
			selectValues:['true', 'false']
		},
		{
			label:"Show directional navigation?",
			id:"nav_arrows",
			help:"These are the little left/right arrows that control the slider.", 
			controlType:"select-control", 
			selectValues:['true', 'false']
		},
		{
			label:"Show pause/play button?",
			id:"pause_play",
			help:"This option will be ignored if the slider is not set to auto-advance.", 
			controlType:"select-control", 
			selectValues:['true', 'false']
		},
		{
			label:"Categories",
			id:"categories",
			help:"List any category slugs you want to include separated by commas (no spaces!). Leave blank if you want to include all categories.<br><br>Ex: category-1,category-2,category-3"
		},
		{
			label:"Post Thumbnail Size",
			id:"thumbs",
			help:"Choose the size of post thumbnails for this list of posts. The \"default\" refers to your blog post thumbnail setting on your Theme Options page.", 
			controlType:"select-control", 
			selectValues:['default', 'small', 'full', 'hide']
		},
		{
			label:"Content",
			id:"post_content",
			help:"Choose whether to show excerpts or full content for each post. The \"default\" refers to your blog content setting on your Theme Options page.", 
			controlType:"select-control", 
			selectValues:['default', 'content', 'excerpt']
		},
		{
			label:"Post per slide",
			id:"posts_per_slide",
			value:"3",
			help:"Enter in the total number of posts per slide."
		},
		{
			label:"Number of Posts",
			id:"numberposts",
			value:"-1",
			help:"Total number of posts, -1 for all posts from the categories you've entered."
		},
		{
			label:"Order by",
			id:"orderby",
			help:"What to order the posts displayed by.", 
			controlType:"select-control", 
			selectValues:['post_date', 'title', 'comment_count', 'rand']
		},
		{
			label:"Order",
			id:"order",
			help:"How to order the posts displayed.", 
			controlType:"select-control", 
			selectValues:['DESC', 'ASC']
		},
		{
			label:"Offset",
			id:"offset",
			value:"0",
			help:"Number of posts to offset off the start, defaults to 0"
		}
	],
	defaultContent:"",
	shortcode:"post_list_slider"
};