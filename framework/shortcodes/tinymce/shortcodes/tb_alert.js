/**
 * Optional arguments
 * - style: info, success, danger, message
 * - close: Show close button or not - true, false
 */
themeblvdShortcodeAtts={
	attributes:[
		{
			label:"Style",
			id:"style",
			help:"Select the style of the alert.", 
			controlType:"select-control", 
			selectValues:['info', 'success', 'danger', 'message']
		},
		{
			label:"Close Button",
			id:"close",
			help:"Select whether you\'d like the website visitor to be able to close the alert box or not.", 
			controlType:"select-control", 
			selectValues:['false', 'true']
		}
	],
	defaultContent:"Content...",
	shortcode:"alert"
};