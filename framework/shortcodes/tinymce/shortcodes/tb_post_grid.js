/**
 * Optional arguments
 * - categories: Categories to include, category slugs separated by commas (no spaces!), or blank for all categories
 * - columns: Number of posts per row
 * - rows: Number of rows
 * - orderby: post_date, title, comment_count, rand
 * - order: DESC, ASC
 * - offset: Number of posts to offset off the start, defaults to 0
 * - link: Show link after posts, true or false
 * - link_text: Text for the link
 * - link_url: URL where link should go
 * - link_target: Where link opens - _self, _blank
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
			help:"Enter in the number of rows you'd like in the grid."
		},
		{
			label:"Categories",
			id:"categories",
			help:"List any category slugs you want to include separated by commas (no spaces!). Leave blank if you want to include all categories.<br><br>Ex: category-1,category-2,category-3"
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
		},
		{
			label:"Link",
			id:"link",
			help:"Select whether to show a link after your posts or not.", 
			controlType:"select-control", 
			selectValues:['true', 'false']
		},
		{
			label:"Link Text",
			id:"link_text",
			help:"If you've selected for a link to show, enter in the text for the link."
		},
		{
			label:"Link URL",
			id:"link_url",
			help:"If you've selected for a link to show, enter in the URL where you want the link to go."
		},
		{
			label:"Link Target",
			id:"link_target",
			help:"If you've selected for a link to show, select the target of the link. _self means the link will open in the same window while _blank means the link will open in a new window.",
			controlType:"select-control", 
			selectValues:['_self', '_blank']
		}
	],
	defaultContent:"",
	shortcode:"post_grid"
};