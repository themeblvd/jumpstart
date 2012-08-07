/**
 * Optional arguments
 * - style: blue, green, grey, orange, purple, red, teal, yellow
 * - icon: Optional icon in upper left corner
 */
themeblvdShortcodeAtts={
	attributes:[
		{
			label:"Style",
			id:"style",
			help:"Select the color of the info box.", 
			controlType:"select-control", 
			selectValues:['blue', 'green', 'grey', 'orange', 'purple', 'red', 'teal', 'yellow']
		},
		{
			label:"Icon (optional)",
			id:"icon",
			help:"Enter an ID of a supported icon for the upper left corner. You can view your theme's live demo for a full list of compatible vector icons."
		}
	],
	defaultContent:"Content...",
	shortcode:"box"
};