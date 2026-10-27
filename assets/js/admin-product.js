/* Atelier Products – Media Library pickers (hover image, sortable gallery). */
jQuery(function ($) {
  'use strict';
  $('.atelier-media-field').each(function () {
    var $f = $(this);
    var multi = String($f.data('multiple')) === '1';
    var $in = $f.find('input[type="hidden"]');
    var $list = $f.find('.atelier-media-list');
    var frame;

    function sync() {
      var ids = [];
      $list.children().each(function () { ids.push($(this).data('id')); });
      $in.val(ids.join(','));
    }

    $f.on('click', '.atelier-media-select', function (e) {
      e.preventDefault();
      if (!frame) {
        frame = wp.media({
          title: atelierProductAdmin.title,
          button: { text: atelierProductAdmin.button },
          library: { type: 'image' },
          multiple: multi ? 'add' : false
        });
        frame.on('select', function () {
          if (!multi) { $list.empty(); }
          frame.state().get('selection').each(function (att) {
            var a = att.toJSON();
            if ($list.find('[data-id="' + a.id + '"]').length) { return; }
            var url = (a.sizes && a.sizes.thumbnail) ? a.sizes.thumbnail.url : a.url;
            $list.append(
              $('<li>').attr('data-id', a.id)
                .append($('<img>').attr('src', url).attr('alt', ''))
                .append($('<button type="button" class="atelier-media-remove" aria-label="Remove">&times;</button>'))
            );
          });
          sync();
        });
      }
      frame.open();
    });

    $f.on('click', '.atelier-media-remove', function () {
      $(this).closest('li').remove();
      sync();
    });

    if (multi && $list.sortable) { $list.sortable({ update: sync, tolerance: 'pointer' }); }
  });
});
