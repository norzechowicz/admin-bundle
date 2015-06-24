define(['jquery'], function($) {

    $.fn.formCollection = function(options) {

        options = $.extend({
            addButtonSelector: '> .collection-add',
            removeButtonSelector: '> div > div > .collection-remove',
            rowSelector: '.form-group',
            collectionsSelector: 'div[data-prototype]'
        }, options);

        var replacePrototypeName = function(template, prototypeName, index) {
            return template
                .replace(new RegExp(prototypeName + 'label__', 'gm'), index)
                .replace(new RegExp(prototypeName, 'gm'), index);
        };

        var removeCollectionItem = function(e) {
            e.preventDefault();
            var $removedItem = $(e.target).closest(options.rowSelector);
            $removedItem.trigger('remove.collection-item');
        };

        var addCollectionItem = function(e) {
            e.preventDefault();

            var $el = $(e.target).closest(options.collectionsSelector);
            var nextIndex = $el.data('current-index');
            var prototypeName = $el.data('prototype-name') || options.defaultPrototypeName;

            var template = $el.data('prototype');
            var newItemHtml = replacePrototypeName(template, prototypeName, nextIndex);
            var $newItem = $(newItemHtml);

            $el.find(options.addButtonSelector).before($newItem);
            $el.data('current-index', nextIndex + 1);
            $el.trigger('add.collection-item', [$newItem]);
        };

        this.each(function(index, element) {
            var $el = $(element);
            if (!$el.data('prototype') || $el.data('current-index')) {
                return;
            }

            var items = $el.children(options.rowSelector);
            $el.data('current-index', items.length);

            if ($el.data('allow-delete')) {
                $el.on('click', options.removeButtonSelector, removeCollectionItem);
            }

            if ($el.data('allow-add')) {
                $el.find(options.addButtonSelector).on('click', addCollectionItem);
            }
        });

        return this;
    }
});
