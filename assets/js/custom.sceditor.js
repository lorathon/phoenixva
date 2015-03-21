// Customizations for the SCEditor BBCode editor

// Editor display
$(function() {
	$('#pagebody').sceditor({
		plugins: 'bbcode',
		style: '../css/sceditor-custom.css',
		toolbar: 'headers|bold,italic,underline,strike,subscript,superscript|left,center,right,justify|color,removeformat|bulletlist,orderedlist,indent,outdent|code,quote|horizontalrule,image,link,unlink|date,time|maximize,source',
	});
});

// Support header tags (ref: http://www.sceditor.com/posts/how-to-add-custom-commands/)
$.sceditor.command.set("headers", {
    exec: function(caller) {
    // Store the editor instance so it can be used
    // in the click handler
    var editor = this,
    $content = $("<div />");
     
    // Create the 1-6 header options
    for (var i=1; i<= 6; i++) {
    $(
    '<a class="sceditor-header-option" href="#">' +
    '<h' + i + '>Heading ' + i + '</h' + i + '>' +
    '</a>'
    )
    .data('headersize', i)
    .click(function (e) {
    // When the option is clicked call the native contenteditable
    // formatblock to format this block to the header
    //
    // It's nearly always better to use the editors methods like
    // insert() over the browsers native execCommand as
    // execCommand has many browser incompatibilites.
    editor.execCommand("formatblock", "<h" + $(this).data('headersize') + ">");
    editor.closeDropDown(true);
     
    e.preventDefault();
    })
    .appendTo($content);
    }
     
    editor.createDropDown(caller, "header-picker", $content);
    },
    tooltip: "Format Headers"
    });

$.sceditor.plugins.bbcode.bbcode
 .set("h1", { tags: { h1: null }, format: "[h1]{0}[/h1]", html: "<h1>{0}</h1>" })
 .set("h2", { tags: { h2: null }, format: "[h2]{0}[/h2]", html: "<h2>{0}</h2>" })
 .set("h3", { tags: { h3: null }, format: "[h3]{0}[/h3]", html: "<h3>{0}</h3>" })
 .set("h4", { tags: { h4: null }, format: "[h4]{0}[/h4]", html: "<h4>{0}</h4>" })
 .set("h5", { tags: { h5: null }, format: "[h5]{0}[/h5]", html: "<h5>{0}</h5>" })
 .set("h6", { tags: { h6: null }, format: "[h6]{0}[/h6]", html: "<h6>{0}</h6>" });