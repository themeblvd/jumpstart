/**
 * Optional arguments
 * - style: default, success, warning, important, info, inverse
 * - icon: Icon code to display before text
 */
themeblvdShortcodeAtts={
	attributes:[
		{
			label:"Style",
			id:"style",
			help:"Select the style, which determines the color of the label.", 
			controlType:"select-control", 
			selectValues:['default', 'success', 'warning', 'important', 'info', 'inverse']
		},
		{
			label:"Icon (optional)",
			id:"icon",
			help:"Enter an ID of a supported icon. You can view your theme's live demo for a full list of compatible vector icons."
		}
	],
	defaultContent:"Label",
	shortcode:"label"
};
