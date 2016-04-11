/* globals Craft, jQuery */

(function($) {
  Craft.ElementSelectEditableTable = Craft.EditableTable.extend({
    initElementSelect: function($tr, elementSelect) {
      var $col = $tr.find('td.has-elementselect');
      var rowId = $tr.data('id');
      var $input = $col.find(':input').addClass('hidden');
      var name = $input.attr('name');
      var html = elementSelect.bodyHtml;
      html = html.replace(/__ROW__/g, rowId);
      var id = $(html).attr('id');
      $col.append(html);

      new Craft.BaseElementSelectInput($.extend(elementSelect.config, {
        id: id,
        name: name,
        elementType: 'Asset',
      }));
    },
    getRowHtml: function(rowId, columns, baseName, values)  {
      var rowHtml = Craft.EditableTable.getRowHtml(rowId, columns, baseName, values);
      $(rowHtml).find('.has-elementselect').html('');

      for (var colId in columns)    {
        var col = columns[colId];
        var name = baseName + '[' + rowId + '][' + colId + ']';

        if (col.type == 'assets') {
          console.log(colId);
        }
      }

      return rowHtml;
    }
  });
})(jQuery);
