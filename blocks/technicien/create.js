/**
 * Build the block
 */
(function(blocks, element, data)
{
    var el = element.createElement;

    blocks.registerBlockType('tvconnecteeamu/technicien', {
        title: 'Ajoute la vue technicien',
        icon: 'smiley',
        category: 'common',

        edit: function() {
            return "Ajoute les vues technicien";
        },
        save: function() {
            return "Saved";
        },
    });
}(
    window.wp.blocks,
    window.wp.element,
    window.wp.data,
));