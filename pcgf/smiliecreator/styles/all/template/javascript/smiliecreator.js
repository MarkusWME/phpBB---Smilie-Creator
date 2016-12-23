var pcgfSmilieShieldPreview = $('#pcgf-smiliecreator-preview');
var pcgfSmilieCreatorCreate = $('#pcgf-smiliecreator-create');
var pcgfSmilieCreatorClose = $('#pcgf-smiliecreator-close');
var pcgfSmilieCreatorText = $('#pcgf-smiliecreator-shield-text');
var pcgfSmilieCreatorColor = $('#pcgf-smiliecreator-color');
var pcgfSmilieCreatorShadowColor = $('#pcgf-smiliecreator-shadow-color');
var pcgfSmilieCreatorShadow = $('#pcgf-smiliecreator-shield-shadow');
var pcgfSmilieCreatorMessage = $('#message', opener.document);

function assignEventHandlers() {
    pcgfSmilieCreatorCreate.on('click', function() {
        // Add the shield BBCode when the button has been clicked
        pcgfSmilieCreatorMessage.val(pcgfSmilieCreatorMessage.val() + '[shield=' + $('input[name=smilie]:checked').val() + ',' + (pcgfSmilieCreatorShadow.is(':checked') ? 1 : 0) + ',' + pcgfSmilieCreatorColor.val() + ',' + pcgfSmilieCreatorShadowColor.val() + ']' + pcgfSmilieCreatorText.val() + '[/shield]');
        pcgfSmilieCreatorClose.trigger('click');
    });
    pcgfSmilieCreatorClose.on('click', function() {
        // Close the window when the close button has been clicked
        window.close();
    });
    // Refresh the smilie shield preview when something changes
    pcgfSmilieCreatorText.on('input', refreshPreview);
    pcgfSmilieCreatorColor.on('change', refreshPreview);
    pcgfSmilieCreatorShadowColor.on('change', refreshPreview);
    pcgfSmilieCreatorShadow.on('change', refreshPreview);
    $('input[type=radio][name=smilie]').on('change', refreshPreview);
}

function refreshPreview() {
    // Refresh smilie shield preview
    pcgfSmilieShieldPreview.attr('src', pcgfGetShieldLink + '?text=' + escape(pcgfSmilieCreatorText.val()) + '&smilie=' + $('input[name=smilie]:checked').val() + '&color=' + pcgfSmilieCreatorColor.val() + '&scolor=' + pcgfSmilieCreatorShadowColor.val() + '&shadow=' + (pcgfSmilieCreatorShadow.is(':checked') ? 1 : 0));
}

$(document).ready(function() {
    // Assign all event handlers and load the smilie shield preview
    assignEventHandlers();
    refreshPreview();
});