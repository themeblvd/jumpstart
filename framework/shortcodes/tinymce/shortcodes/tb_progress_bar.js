/**
 * Required arguments
 * - percent: A percentage of how far the bar is - 25, 50, 80, etc.
 * 
 * Optional arguments
 *  - color: Color of bar - default, danger, success, info, warning
 *  - striped: Whether the bar is striped or not - true, false
 *  - animate: Whether the bar is animated or not - true, false
 */
themeblvdShortcodeAtts={
	attributes:[
		{
			label:"Percent",
			id:"percent",
			help:"Enter in a percentage of how far the progress bar is. Ex: 25, 50, 80, etc."
		},
		{
			label:"Color",
			id:"color",
			help:"Select the color of the progress bar.", 
			controlType:"select-control", 
			selectValues:['default', 'danger', 'success', 'info', 'warning']
		},
		{
			label:"Striped?",
			id:"striped",
			help:"Select whether you'd like the progress bar to be striped or not. (Note: Will not work in IE 8-9.)", 
			controlType:"select-control", 
			selectValues:['false', 'true']
		},
		{
			label:"Animated?",
			id:"animated",
			help:"Select whether you'd like the progress bar to be animated or not. This only can be applied if you selected the progress bar to be striped in the previous option. (Note: Will not work in IE 8-9.)", 
			controlType:"select-control", 
			selectValues:['false', 'true']
		}
	],
	defaultContent:"",
	shortcode:"progress_bar"
};
