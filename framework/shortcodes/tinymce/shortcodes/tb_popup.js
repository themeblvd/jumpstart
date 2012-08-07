/**
 * Required arguments
 *  - text: Text of button to popup
 * 
 * Optional arguments
 *  - title: Title tag of button to popup
 *  - color: Color of button
 *  - icon_before: Icon before text of button to popup
 *  - icon_after: Icon after text of button to popup
 *  - animate: Whether popup slides in or not - true, false
 *  - header: Header text for popup
 */
themeblvdShortcodeAtts={
	attributes:[
		{
			label:"Text",
			id:"text",
			help:"Enter in the text to be used for the button leading to the popup."
		},
		{
			label:"Title Tag (optional)",
			id:"text",
			help:"Enter in the anchor title tag to be used for the button leading to the popup. This will default to the previous 'Text' option if you leave this blank."
		},
		{
			label:"Color",
			id:"color",
			help:"Select the color for the button leading to the popup.", 
			controlType:"select-control", 
			selectValues:['default', 'primary', 'info', 'success', 'warning', 'danger', 'inverse', '----------', 'black', 'blue', 'brown', 'dark_blue', 'dark_brown', 'dark_green', 'green', 'mauve', 'orange', 'pearl', 'pink', 'purple', 'red', 'slate_grey', 'silver', 'steel_blue', 'teal', 'yellow', 'wheat', 'white']
		},
		{
			label:"Icon Before Button Text",
			id:"icon_before",
			help:"Enter an ID of a supported icon. You can view your theme's live demo for a full list of compatible vector icons."
		},
		{
			label:"Icon After Button Text",
			id:"icon_after",
			help:"Enter an ID of a supported icon. You can view your theme's live demo for a full list of compatible vector icons."
		},
		{
			label:"Popup Animation",
			id:"animate",
			help:"Select true if you'd like the popup to animate in when it appears.", 
			controlType:"select-control", 
			selectValues:['false', 'true']
		},
		{
			label:"Header Text (optional)",
			id:"header",
			help:"Enter in the text you'd like to show at the top of the popup. If you leave this blank, the popup will have no header."
		}
	],
	defaultContent:"<br>Content for your popup goes here...<br>",
	shortcode:"popup"
};
