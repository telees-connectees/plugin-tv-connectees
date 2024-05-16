/**
 * Build the block
 */
(function(blocks, element, data)
{
    var el = element.createElement;

    blocks.registerBlockType('tvconnecteeamu/technicien', {
        title: 'Affiche toutes les vue technicien',
        icon: 'smiley',
        category: 'common',

        edit: function() {
            return "Affiche les vues technicien";
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