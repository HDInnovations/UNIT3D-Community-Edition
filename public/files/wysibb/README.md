#WysiBB - WYSIWYG BBcode editor

WysiBB is a jQuery visual WYSIWYG editor for BBcode.
For more information please visit [wysibb.com](http://www.wysibb.com) 

## Usage

Include the JQuery and WysiBB files

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="http://cdn.wysibb.com/js/jquery.wysibb.min.js"></script>
	<link rel="stylesheet" href="http://cdn.wysibb.com/css/default/wbbtheme.css" />

Activate WysiBB on an existing textarea

	<script>
	$(document).ready(function() {
	  $("#editor").wysibb()
	})
	</script>
	<textarea id="editor" name="editor_name">My text</textarea>

To see how it works, you can try [the official demo](http://www.wysibb.com/).


## Options

####BBcodes
WysiBB comes with all BBCodes by default (allButtons). You can configure BBCode you want.

	<script>
	$(document).ready(function() {
	var wbbOpt = {
	  buttons: "bold,italic,underline,|,img,link,|,code,quote"
	}
	$("#editor").wysibb(wbbOpt);
	});
	</script>

####Language
WysiBB comes in russian by default, but you can set a different language 
	
	<head>
	...
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="http://cdn.wysibb.com/js/jquery.wysibb.min.js"></script>
	<link rel="stylesheet" href="http://cdn.wysibb.com/css/default/wbbtheme.css" />
	<script src="/js/lang/fr.js"></script>
	...
	</head>
	
	<script>
	$(document).ready(function() {
	var wbbOpt = {
		lang : 	 "fr",
		buttons: "bold,italic,underline,|,img,link,|,code,quote"
	}
	$("#editor").wysibb(wbbOpt);
	});
	</script>

(languages available: Arabic (ar), Chinese (cn), English (en), French (fr), Polish (pl), Turkish (tr) & Vietnamese (ci))


####Shortkeys

You can assign any keyboard shortcuts for BBcode. By default WysiBB set some hotkeys. You can add or change their combinations for existing BBcodes. 
Consider hook up hotkeys for example.

	var wbbOpt = {
	  allButtons: {
		img: {
		  hotkey: "ctrl+shift+5"
		},
		link: {
		  hotkey: "ctrl+shift+k"
		}
	 }
	}
	$("#editor").wysibb(wbbOpt);

Note that certain key combinations are already used by browsers, so they might not work.

	var wbbOpt = {
	  hotkeys: false, //disable hotkeys (native browser combinations will work)
	  showHotkeys: false //hide combination in the tooltip when you hover.
	}
	$("#editor").wysibb(wbbOpt);


####Custom BBCodes

You can set custom BBcode transformation, or add your own BBCodes

	var wbbOpt = {
	  buttons: "bold,italic,underline,|,img,link,|,code,myquote",
	  allButtons: {
		code: {
		  transform: {
			'<div class="mycode"><div class="codetop">This program code:</div><div class="codemain">{SELTEXT}</div></div>':'[code]{SELTEXT}[/code]'
		  }
		},
		myquote: {
		  title: 'Insert a quote',
		  buttonText: 'myquote',
		  transform: {
			'<div class="myquote">{SELTEXT}</div>':'[myquote]{SELTEXT}[/myquote]'
		  }
		}
	  }
	}
	$("#editor").wysibb(wbbOpt);

In this configuration by using the buttons we described what BBcodes will be connected to our editor. I want to note that this option was added at once and our own BBcode myquote.

Later, using the parameter allButtons, we have changed the conclusion BBcode code and added our own, describing its title (tooltip when you hover), buttonText (text button in the toolbar).

{SELTEXT} - is the only predefined parameter.


See [the documentation](http://www.wysibb.com/ru/docs/) for more features like Sophisticated BBCodes, handlers, modal window with tabs, ...



## Browser support

WysiBB supports modern browsers, including Google Chrome, Firefox, Safari, Opera & IE8+.
It also works fine on modern smartphone & tablet browsers.



## API

Get to document editor

	$("#editor").getDoc()

Get highlighted text

	$("#editor").getSelectText()

Get / replace BBcode editor content

	$("#editor").bbcode(); //get BBcode editor content
	$("#editor").bbcode(bbdata); //set BBcode editor content

Get / replace HTML editor content

	$("#editor").htmlcode(); //get HTML editor content
	$("#editor").htmlcode(htmlcode); //set HTML editor content

getHTMLByCommand (command, params) 
Outputs the editor content as HTML. Where command - the command name, params - object variable

	$("#editor").getHTMLByCommand("code",{seltext:"this code"});

getBBCodeByCommand (command, params) 
Get an outcome of the execution of commands in BB code form. Where command - the command name, params - object variable

	$("#editor").getBBCodeByCommand("code",{seltext:"this code"});

insertAtCursor(data)
Insert a text where the typing cursor is

	$("#editor").insertAtCursor("this code");

execCommand(command,value)
Execute the command. Where command - the command name, value - value

	$("#editor").execCommand("bold");

sync()
Synchronize data editor and textarea

	$("#editor").sync();




## License

WysiBB is licensed under the [MIT](http://www.opensource.org/licenses/mit-license.php) license.
If you use WysiBB a link back or a donation would be appreciated, but not required.



## Contribute

Any contributions and/or pull requests would be welcome.
Themes, translations, bug reports and bug fixes are greatly appreciated.


## Support 

[Support forum](http://www.wysibb.com/forum/) (mostly in Russian)


