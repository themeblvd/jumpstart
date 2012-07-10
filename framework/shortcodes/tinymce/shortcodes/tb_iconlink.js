/**
 * Required arguments
 *  - icon: alert, approved, attention, camera, cart, doc, download, media, note, notice, quote
 *  - link: The full URL of where you want the link to go
 * 
 * Optional arguments
 *  - target: _self or _blank
 *  - class: Any CSS classes you want to add
 *  - title: Title of link, will default to button's text
 */
themeblvdShortcodeAtts={
	attributes:[
		{
			label:"Icon",
			id:"icon",
			help:"This will determine the icon located next to your link.", 
			controlType:"select-control", 
			selectValues:['alert', 'approved', 'attention', 'camera', 'cart', 'doc', 'download', 'media', 'note', 'notice', 'quote']
		},
		{
			label:"Link URL",
			id:"link",
			help:"ex: http://google.com"
		},
		{
			label:"Link Target",
			id:"target",
			help:"Select how you'd like the link to open - \"_self\" would be in the same window, while \"_blank\" would be in a new window. ", 
			controlType:"select-control", 
			selectValues:['_self', '_blank']
		},
		{
			label:"CSS Classes",
			id:"class",
			help:"Enter in any CSS classes you want to add to the button."
		},
		{
			label:"Title",
			id:"title",
			help:"Enter in the title of the link's anchor tag. If you leave this blank, it will just default to the text of the link."
		}
		
	],
	defaultContent:"Link Text",
	shortcode:"icon_link"
};
