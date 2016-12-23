var pcgfOpenSmilieCreatorButton = $('#pcgf-smiliecreator-create');

function assignEventHandlers() {
    // Append the event handler to the button
    pcgfOpenSmilieCreatorButton.on('click', function() {
        window.open(pcgfSmilieCreatorLink, '_pcgf_smiliecreator', 'width=400,height=500,menubar=no,resizable=yes,scrollbars=yes,titlebar=yes,toolbar=no');
    });
}

$(document).ready(function() {
    assignEventHandlers();
});