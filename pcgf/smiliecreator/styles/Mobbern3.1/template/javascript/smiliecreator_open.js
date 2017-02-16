// Place the create button
$('<br/><br/><input id="pcgf-smiliecreator-create" type="button" class="button2" value="' + pcgfSmilieCreatorCreateText + '"/>').insertBefore('div.bbcode-status');

var pcgfOpenSmilieCreatorButton = $('#pcgf-smiliecreator-create');

function assignPCGFSmiliecreatorEventHandlers() {
    // Append the event handler to the button
    pcgfOpenSmilieCreatorButton.on('click', function() {
        window.open(pcgfSmilieCreatorLink, '_pcgf_smiliecreator', 'width=500,height=650,menubar=no,resizable=yes,scrollbars=yes,titlebar=yes,toolbar=no');
    });
}

$(document).ready(function() {
    assignPCGFSmiliecreatorEventHandlers();
});