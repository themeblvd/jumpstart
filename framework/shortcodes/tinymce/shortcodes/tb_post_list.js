/**
 * Optional arguments
 * - categories: Categories to include, category slugs separated by commas (no spaces!), or blank for all categories
 * - thumbs: Size of post thumbnails - default, small, full, hide
 * - post_content: Show excerpts or full content - default, content, excerpt
 * - numberposts: Total number of posts, -1 for all posts
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
	shortcode:"post_list"
};