/**
 * Required arguments
 *  - image: Name of icon's image {image_name}.png, see below for image names
 * 
 * Optional arguments:
 *  - align: left, right, center, none
 */
themeblvdShortcodeAtts={
	attributes:[
		{
			label:"Icon Image",
			id:"image",
			help:"Select the image you'd like used for the icon.", 
			controlType:"select-control", 
			selectValues:['accepted', 'add', 'app', 'arrow_down', 'arrow_down_green', 'arrow_left', 'arrow_left_green', 'arrow_right', 'arrow_right_green', 'arrow_up', 'arrow_up_green', 'beer', 'blue_speech_bubble', 'book', 'box', 'box_download', 'box_upload', 'camera', 'cancel', 'cd', 'circle_blue', 'circle_green', 'circle_orange', 'circle_red', 'clock', 'coffee', 'coffee_mug', 'comment', 'computer', 'cross', 'database', 'floppy_disk', 'folder', 'globe', 'heart', 'home', 'image', 'lightbulb', 'lock', 'lock_open', 'mail', 'mouse', 'navigate', 'newspaper', 'paper', 'paper_and_pencil', 'paper_content', 'paper_content_chart', 'paper_content_pencil', 'pencil', 'pie_chart', 'printer', 'questionmark', 'refresh', 'rss', 'search', 'smile_grin', 'smile_sad', 'spanner', 'speech_bubble', 'star', 'star_half', 'star_off', 'table', 'tabs', 'thumbs_down', 'thumbs_up', 'usb', 'user', 'users_two', 'warning', 'iphone', 'macbook', 'wordpress', 'html5', 'colors', 'support', 'google', 'analytics', 'billing', 'audio', 'movies', 'clipboard', 'bullseye', 'stop', 'monitor', 'news', 'calculator', 'direction', 'cart', 'megaphone', 'license', 'package', 'secure', 'award', 'mobile']
		},
		{
			label:"Alignment",
			id:"align",
			help:"Select how you'd like the icon aligned.", 
			controlType:"select-control", 
			selectValues:['left', 'right', 'center', 'none']
		}
	],
	defaultContent:"",
	shortcode:"icon"
};