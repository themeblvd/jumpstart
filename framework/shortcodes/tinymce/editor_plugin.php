<?php 
header("Content-Type:text/javascript");

//Setup URL to WordPres
$absolute_path = __FILE__;
$path_to_wp = explode( 'wp-content', $absolute_path );
$wp_url = $path_to_wp[0];

//Access WordPress
require_once( $wp_url.'/wp-load.php' );

//URL to TinyMCE plugin folder
$plugin_url = get_template_directory_uri().'/framework/shortcodes/tinymce/';
?>
(function(){
	
	var icon_url = '<?php echo $plugin_url; ?>' + '/tb_icon.png';

	tinymce.create(
		"tinymce.plugins.ThemeBlvdShortcodes",
		{
			init: function(d,e) {
					
					
					
					d.addCommand( "themeblvdOpenDialog",function(a,c){
						
						// Grab the selected text from the content editor.
						selectedText = '';
					
						if ( d.selection.getContent().length > 0 ) {
					
							selectedText = d.selection.getContent();
							
						} // End IF Statement
						
						themeblvdSelectedShortcodeType = c.identifier;
						themeblvdSelectedShortcodeTitle = c.title;
						
						jQuery.get(e+"/dialog.php",function(b){
							
							var a;
							
							jQuery('#themeblvd-shortcode-options').addClass( 'shortcode-' + themeblvdSelectedShortcodeType );
							
							// Skip the popup on certain shortcodes.
							
							switch ( themeblvdSelectedShortcodeType ) {
								
								// -------------------------------------------------------------
								// Info Boxes
								// -------------------------------------------------------------
								
								// alert
								case 'alert':
								a = '[box style="alert"]'+selectedText+'[/box]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// approved
								case 'approved':
								a = '[box style="approved"]'+selectedText+'[/box]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// camera
								case 'camera':
								a = '[box style="camera"]'+selectedText+'[/box]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// cart
								case 'cart':
								a = '[box style="cart"]'+selectedText+'[/box]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// doc
								case 'doc':
								a = '[box style="doc"]'+selectedText+'[/box]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// download
								case 'download':
								a = '[box style="download"]'+selectedText+'[/box]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// media
								case 'media':
								a = '[box style="media"]'+selectedText+'[/box]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// note
								case 'note':
								a = '[box style="note"]'+selectedText+'[/box]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// notice
								case 'notice':
								a = '[box style="notice"]'+selectedText+'[/box]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// quote
								case 'quote':
								a = '[box style="quote"]'+selectedText+'[/box]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// warning
								case 'warning':
								a = '[box style="warning"]'+selectedText+'[/box]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// -------------------------------------------------------------
								// HTML Elements
								// -------------------------------------------------------------
								
								// highlight
								case 'highlight':
								a = '[highlight]'+selectedText+'[/highlight]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// dropcap
								case 'dropcap':
								a = '[dropcap]'+selectedText+'[/dropcap]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// -------------------------------------------------------------
								// Escape Auto WP
								// -------------------------------------------------------------
								
								// raw
								case 'raw':
								a = '[raw]'+selectedText+'[/raw]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// -------------------------------------------------------------
								// Individual Column
								// -------------------------------------------------------------
								
								// one-sixth
								case 'one-sixth':
								a = '[one_sixth]'+selectedText+'[/one_sixth]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// one-fifth
								case 'one-fifth':
								a = '[one_fifth]'+selectedText+'[/one_fifth]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// two-fifth
								case 'two-fifth':
								a = '[two_fifth]'+selectedText+'[/two_fifth]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// three-fifth
								case 'three-fifth':
								a = '[three_fifth]'+selectedText+'[/three_fifth]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// four-fifth
								case 'four-fifth':
								a = '[four_fifth]'+selectedText+'[/four_fifth]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// one-fourth
								case 'one-fourth':
								a = '[one_fourth]'+selectedText+'[/one_fourth]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// three-fourth
								case 'three-fourth':
								a = '[three_fourth]'+selectedText+'[/three_fourth]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// one-third
								case 'one-third':
								a = '[one_third]'+selectedText+'[/one_third]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// two-third
								case 'two-third':
								a = '[two_third]'+selectedText+'[/two_third]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// one-half
								case 'one-half':
								a = '[one_half]'+selectedText+'[/one_half]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// -------------------------------------------------------------
								// 2 Columns
								// -------------------------------------------------------------
								
								// 50% | 50%
								case '2-col-50-50':
								a  = '[raw]<br />';
								a += '[one_half] content... [/one_half]<br />';
								a += '[one_half last] content... [/one_half]<br />';
								a += '[clear]<br />';
								a += '[/raw]';		
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// 25% | 75%
								case '2-col-25-75':
								a  = '[raw]<br />';
								a += '[one_fourth]content...[/one_fourth]<br />';
								a += '[three_fourth last]content...[/three_fourth]<br />';
								a += '[clear]<br />';
								a += '[/raw]';				
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// 75% | 25%
								case '2-col-75-25':
								a  = '[raw]<br />';
								a += '[three_fourth]content...[/three_fourth]<br />';
								a += '[one_fourth last]content...[/one_fourth]<br />';
								a += '[clear]<br />';
								a += '[/raw]';					
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// 33% | 66%
								case '2-col-33-66':
								a  = '[raw]<br />';
								a += '[one_third]content...[/one_third]<br />';
								a += '[two_third last]content...[/two_third]<br />';
								a += '[clear]<br />';
								a += '[/raw]';				
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// 66% | 33%
								case '2-col-66-33':
								a  = '[raw]<br />';
								a += '[two_third]content...[/two_third]<br />';
								a += '[one_third last]content...[/one_third]<br />';
								a += '[clear]<br />';
								a += '[/raw]';	
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// 20% | 80%
								case '2-col-20-80':
								a  = '[raw]<br />';
								a += '[one_fifth]content...[/one_fifth]<br />';
								a += '[four_fifth last]content...[/four_fifth]<br />';
								a += '[clear]<br />';
								a += '[/raw]';	
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// 80% | 20%
								case '2-col-80-20':
								a  = '[raw]<br />';
								a += '[four_fifth]content...[/four_fifth]<br />';
								a += '[one_fifth last]content...[/one_fifth]<br />';
								a += '[clear]<br />';
								a += '[/raw]';		
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// -------------------------------------------------------------
								// 3 Columns
								// -------------------------------------------------------------
								
								// 33% | 33% | 33%
								case '3-col-33-33-33':
								a  = '[raw]<br />';
								a += '[one_third]content...[/one_third]<br />';
								a += '[one_third]content...[/one_third]<br />';
								a += '[one_third last]content...[/one_third]<br />';
								a += '[clear]<br />';
								a += '[/raw]';					
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// 25% | 25% | 50%
								case '3-col-25-25-50':
								a  = '[raw]<br />';
								a += '[one_fourth]content...[/one_fourth]<br />';
								a += '[one_fourth]content...[/one_fourth]<br />';
								a += '[one_half last]content...[/one_half]<br />';
								a += '[clear]<br />';
								a += '[/raw]';					
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// 25% | 50% | 25%
								case '3-col-25-50-25':
								a  = '[raw]<br />';
								a += '[one_fourth]content...[/one_fourth]<br />';
								a += '[one_half]content...[/one_half]<br />';
								a += '[one_fourth last]content...[/one_fourth]<br />';
								a += '[clear]<br />';
								a += '[/raw]';					
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// 50% | 25% | 25%
								case '3-col-50-25-25':
								a  = '[raw]<br />';
								a += '[one_half]content...[/one_half]<br />';
								a += '[one_fourth]content...[/one_fourth]<br />';
								a += '[one_fourth last]content...[/one_fourth]<br />';
								a += '[clear]<br />';
								a += '[/raw]';					
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// 20% | 20% | 60%
								case '3-col-20-20-60':
								a  = '[raw]<br />';
								a += '[one_fifth]content...[/one_fifth]<br />';
								a += '[one_fifth]content...[/one_fifth]<br />';
								a += '[three_fifth last]content...[/three_fifth]<br />';
								a += '[clear]<br />';
								a += '[/raw]';					
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// 20% | 60% | 20%
								case '3-col-20-60-20':
								a  = '[raw]<br />';
								a += '[one_fifth]content...[/one_fifth]<br />';
								a += '[three_fifth]content...[/three_fifth]<br />';
								a += '[one_fifth last]content...[/one_fifth]<br />';
								a += '[clear]<br />';
								a += '[/raw]';					
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// 60% | 20% | 20%
								case '3-col-60-20-20':
								a  = '[raw]<br />';
								a += '[three_fifth]content...[/three_fifth]<br />';
								a += '[one_fifth]content...[/one_fifth]<br />';
								a += '[one_fifth last]content...[/one_fifth]<br />';
								a += '[clear]<br />';
								a += '[/raw]';					
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;										
								
								// -------------------------------------------------------------
								// 4 Columns
								// -------------------------------------------------------------
								
								// 25% | 25% | 25% | 25%
								case '4-col-25-25-25-25':
								a  = '[raw]<br />';
								a += '[one_fourth]content...[/one_fourth]<br />';
								a += '[one_fourth]content...[/one_fourth]<br />';
								a += '[one_fourth]content...[/one_fourth]<br />';
								a += '[one_fourth last]content...[/one_fourth]<br />';
								a += '[clear]<br />';
								a += '[/raw]';					
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// 20% | 20% | 20% | 40%
								case '4-col-20-20-20-40':
								a  = '[raw]<br />';
								a += '[one_fifth]content...[/one_fifth]<br />';
								a += '[one_fifth]content...[/one_fifth]<br />';
								a += '[one_fifth]content...[/one_fifth]<br />';
								a += '[two_fifth last]content...[/two_fifth]<br />';
								a += '[clear]<br />';
								a += '[/raw]';					
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// 20% | 20% | 40% | 20%
								case '4-col-20-20-40-20':
								a  = '[raw]<br />';
								a += '[one_fifth]content...[/one_fifth]<br />';
								a += '[one_fifth]content...[/one_fifth]<br />';
								a += '[two_fifth]content...[/two_fifth]<br />';
								a += '[one_fifth last]content...[/one_fifth]<br />';
								a += '[clear]<br />';
								a += '[/raw]';					
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// 20% | 40% | 20% | 20%
								case '4-col-20-40-20-20':
								a  = '[raw]<br />';
								a += '[one_fifth]content...[/one_fifth]<br />';
								a += '[two_fifth]content...[/two_fifth]<br />';
								a += '[one_fifth]content...[/one_fifth]<br />';
								a += '[one_fifth last]content...[/one_fifth]<br />';
								a += '[clear]<br />';
								a += '[/raw]';					
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// 40% | 20% | 20% | 20%
								case '4-col-40-20-20-20"':
								a  = '[raw]<br />';
								a += '[two_fifth]content...[/two_fifth]<br />';
								a += '[one_fifth]content...[/one_fifth]<br />';
								a += '[one_fifth]content...[/one_fifth]<br />';
								a += '[one_fifth last]content...[/one_fifth]<br />';
								a += '[clear]<br />';
								a += '[/raw]';					
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// -------------------------------------------------------------
								// 5 Columns
								// -------------------------------------------------------------
								
								// 20% | 20% | 20% | 20% | 20%
								case '5-col-20-20-20-20-20':
								a  = '[raw]<br />';
								a += '[one_fifth]content...[/one_fifth]<br />';
								a += '[one_fifth]content...[/one_fifth]<br />';
								a += '[one_fifth]content...[/one_fifth]<br />';
								a += '[one_fifth]content...[/one_fifth]<br />';
								a += '[one_fifth last]content...[/one_fifth]<br />';
								a += '[clear]<br />';
								a += '[/raw]';	
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// -------------------------------------------------------------
								// 6 Columns
								// -------------------------------------------------------------
								
								// 15% | 15% | 15% | 15% | 15% | 15%
								case '6-col-15-15-15-15-15-15':
								a  = '[raw]<br />';
								a += '[one_sixth]content...[/one_sixth]<br />';
								a += '[one_sixth]content...[/one_sixth]<br />';
								a += '[one_sixth]content...[/one_sixth]<br />';
								a += '[one_sixth]content...[/one_sixth]<br />';
								a += '[one_sixth]content...[/one_sixth]<br />';
								a += '[one_sixth last]content...[/one_sixth]<br />';
								a += '[clear]<br />';
								a += '[/raw]';	
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
								
								// -------------------------------------------------------------
								// Tabs
								// -------------------------------------------------------------
								
								// tabs
								case 'tabs':
								a  = '[raw]<br />';
								a += '[tabs style="framed" tab_1="Title #1" tab_2="Title #2" tab_3="Title #3"]<br />';
								a += '[tab_1]Tab 1 content...[/tab_1]<br />'; 
								a += '[tab_2]Tab 2 content...[/tab_2]<br />';
								a += '[tab_3]Tab 3 content...[/tab_3]<br />';
								a += '[/tabs]<br />';
								a += '[/raw]';
								tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
								break;
							
								default:
								
								jQuery("#themeblvd-dialog").remove();
								jQuery("body").append(b);
								jQuery("#themeblvd-dialog").hide();
								var f=jQuery(window).width();
								b=jQuery(window).height() - 30;
								f=720<f?720:f;
								f-=80;
								b-=84;
							
								tb_show("Insert ThemeBlvd "+ themeblvdSelectedShortcodeTitle +" Shortcode", "#TB_inline?width="+f+"&height="+b+"&inlineId=themeblvd-dialog");jQuery("#themeblvd-shortcode-options h3:first").text(""+c.title+" Shortcode Settings");
								
								break;
							
							} // End SWITCH Statement
						
						}
												 
					)
					 
					} 
				);

				},
					
				createControl:function(d,e){
				
						if(d=="themeblvd_shortcodes_button"){
						
							d=e.createMenuButton("themeblvd_shortcodes_button",{
								title:"Insert ThemeBlvd Shortcode",
								image:icon_url,
								icons:false
								});
								
								var a=this;d.onRenderMenu.add(function(c,b){
									c=b.addMenu({title:"Info Boxes"});
										a.addWithDialog(c,"Alert","alert");
										a.addWithDialog(c,"Approved","approved");
										a.addWithDialog(c,"Camera","camera");
										a.addWithDialog(c,"Doc","doc");
										a.addWithDialog(c,"Download","download");
										a.addWithDialog(c,"Media","media");
										a.addWithDialog(c,"Note","note");
										a.addWithDialog(c,"Notice","notice");
										a.addWithDialog(c,"Quote","quote");
										a.addWithDialog(c,"Warning","warning");
									c=b.addMenu({title:"HTML Elements"});
										a.addWithDialog(c,"Button","button");
										a.addWithDialog(c,"Drop Cap","dropcap");
										a.addWithDialog(c,"Highlight","highlight");
										a.addWithDialog(c,"Icon Link","iconlink");
										a.addWithDialog(c,"Icon List","iconlist");
										a.addWithDialog(c,"Divider","divider");
									a.addWithDialog(b,"Icon","icon");
									a.addWithDialog(b,"Escape Auto WP","raw");
										
									// ----------------------
									b.addSeparator();
									// ----------------------
									
									c=b.addMenu({title:"Individual Column"});
										a.addWithDialog(c,"1/6 - [one_sixth]","one-sixth");
										c.addSeparator();
										a.addWithDialog(c,"1/5 - [one_fifth]","one-fifth");
										a.addWithDialog(c,"2/5 - [two_fifth]","two-fifth");
										a.addWithDialog(c,"3/5 - [three_fifth]","three-fifth");
										a.addWithDialog(c,"4/5 - [four_fifth]","four-fifth");
										c.addSeparator();
										a.addWithDialog(c,"1/4 - [one_fourth]","one-fourth");
										a.addWithDialog(c,"3/4 - [three_fourth]","three-fourth");
										c.addSeparator();
										a.addWithDialog(c,"1/3 - [one_third]","one-third");
										a.addWithDialog(c,"2/3 - [two_third]","two-third");
										c.addSeparator();
										a.addWithDialog(c,"1/2 - [one_half]","one-half");
									c=b.addMenu({title:"2 Columns"});
										a.addWithDialog(c,"50% | 50%","2-col-50-50");
										a.addWithDialog(c,"25% | 75%","2-col-25-75");
										a.addWithDialog(c,"75% | 25%","2-col-75-25");
										a.addWithDialog(c,"33% | 66%","2-col-33-66");
										a.addWithDialog(c,"66% | 33%","2-col-66-33");
										a.addWithDialog(c,"20% | 80%","2-col-20-80");
										a.addWithDialog(c,"80% | 20%","2-col-80-20");
									c=b.addMenu({title:"3 Columns"});
										a.addWithDialog(c,"33% | 33% | 33%","3-col-33-33-33");
										a.addWithDialog(c,"25% | 25% | 50%","3-col-25-25-50");
										a.addWithDialog(c,"25% | 50% | 25%","3-col-25-50-25");
										a.addWithDialog(c,"50% | 25% | 25%","3-col-50-25-25");
										a.addWithDialog(c,"20% | 20% | 60%","3-col-20-20-60");
										a.addWithDialog(c,"20% | 60% | 20%","3-col-20-60-20");
										a.addWithDialog(c,"60% | 20% | 20%","3-col-60-20-20");
									c=b.addMenu({title:"4 Columns"});
										a.addWithDialog(c,"25% | 25% | 25% | 25%","4-col-25-25-25-25");
										a.addWithDialog(c,"20% | 20% | 20% | 40%","4-col-20-20-20-40");
										a.addWithDialog(c,"20% | 20% | 40% | 20%","4-col-20-20-40-20");
										a.addWithDialog(c,"20% | 40% | 20% | 20%","4-col-20-40-20-20");
										a.addWithDialog(c,"40% | 20% | 20% | 20%","4-col-40-20-20-20");
									c=b.addMenu({title:"5 Columns"});
										a.addWithDialog(c,"20% | 20% | 20% | 20% | 20%","5-col-20-20-20-20-20");
									c=b.addMenu({title:"6 Columns"});
										a.addWithDialog(c,"15% | 15% | 15% | 15% | 15% | 15%","6-col-15-15-15-15-15-15");
									
									// ----------------------
									b.addSeparator();
									// ----------------------
									
									c=b.addMenu({title:"Sliders"});
										a.addWithDialog(c,"Custom Slider","slider");
										a.addWithDialog(c,"Post Grid Slider","post_grid_slider");
										a.addWithDialog(c,"Post List Slider","post_list_slider");
									c=b.addMenu({title:"Posts"});
										a.addWithDialog(c,"Post Grid","post_grid");
										a.addWithDialog(c,"Post List","post_list");
									a.addWithDialog(b,"Tabs","tabs");
									a.addWithDialog(b,"Toggle","toggle");

							});
							
							return d
						
						} // End IF Statement
						
						return null
					},
		
				addImmediate:function(d,e,a){d.add({title:e,onclick:function(){tinyMCE.activeEditor.execCommand("mceInsertContent",false,a)}})},
				
				addWithDialog:function(d,e,a){d.add({title:e,onclick:function(){tinyMCE.activeEditor.execCommand("themeblvdOpenDialog",false,{title:e,identifier:a})}})},
		
				getInfo:function(){ return{longname:"ThemeBlvd Shortcode Generator",author:"VisualShortcodes.com",authorurl:"http://visualshortcodes.com",infourl:"http://visualshortcodes.com/shortcode-ninja",version:"1.0"} }
			}
		);
		
		tinymce.PluginManager.add("ThemeBlvdShortcodes",tinymce.plugins.ThemeBlvdShortcodes)
	}
)();
