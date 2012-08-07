/**
 * Required arguments
 * - icon: Icon to be used. (view icons)
 *
 * Optional arguments
 * - color: Color CSS value to get applied to icon, will default to current text color. Ex: #660000
 */
themeblvdShortcodeAtts={
	attributes:[
		{
			label:"Icon",
			id:"icon",
			help:"Enter an ID of a supported icon. You can view your theme's live demo for a full list of compatible vector icons." 
		},
		{
			label:"Color",
			id:"color",
			help:"Enter a color CSS value to get applied to the icon. This will default to current text color. Ex: #660000"
		}
	],
	defaultContent:" ",
	shortcode:"icon_list"
};
