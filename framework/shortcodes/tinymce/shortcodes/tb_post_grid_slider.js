/**
 * Optional arguments
 *  - fx: Transition of slider - fade, slide
 *  - timeout: Seconds in between transitions, 0 for no auto-advancing
 *  - nav_standard: Show standard nav dots to control slider - true or false
 *  - nav_arrows: Show directional arrows to control slider - true or false
 *  - pause_play: Show pause/play button - true or false
 *  - categories: Categories to include, category slugs separated by commas (no spaces!), or blank for all categories
 *  - columns: Number of posts per row
 *  - rows: Number of rows per slide
 *  - numberposts: Total number of posts, -1 for all posts
 *  - orderby: post_date, title, comment_count, rand
 *  - order: DESC, ASC
 *  - offset: Number of posts to offset off the start, defaults to 0
 */
themeblvdShortcodeAtts={
	attributes:[
		{
			label:"Columns",
			id:"columns",
			value:"3",
			help:"Enter in the number of posts per row you'd like in the grid."
		},
		{
			label:"Rows",
			id:"rows",
			value:"3",
			help:"Enter in the number of rows you'd like in the grid for each slide."
		},
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
	shortcode:"post_grid_slider"
};