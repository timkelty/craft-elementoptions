Craft.ElementSelectEditableTable = Craft.EditableTable.extend({
});

// {
//   getRowHtml: function(rowId, columns, baseName, values)  {
//
//     return '';
//     // var rowHtml = Craft.EditableTable.getRowHtml(rowId, columns, baseName, values);
//     // console.log($(rowHtml));
//     // $(rowHtml).find('.has-elementselect').html('');
//     // for (var colId in columns)    {
//     //     var col = columns[colId];
//     //     var name = baseName + '[' + rowId + '][' + colId + ']';
//     //
//     //     if (col.type == 'assets') {
//     //       // console.log(colId);
//     //     }
//     // }
//     //
//     //
//     // return rowHtml;
//   },
// });


// Craft.ElementSelectEditableTable.initElementSelect = function($tr) {
//   console.log($tr);
//   new Craft.BaseElementSelectInput({
//     id: 'types-AssetsSelect_AssetsSelect-4-assets'
//   });
// };
// Craft.ElementSelectEditableTable.getRowHtml = function(rowId, columns, baseName, values)  {
//   var rowHtml = Craft.EditableTable.getRowHtml(rowId, columns, baseName, values);
//   return rowHtml;
//
  // var rowHtml = '<tr data-id="' + rowId + '">';
  //
  // for (var colId in columns)    {
  //   var col = columns[colId],
  //   name = baseName + '[' + rowId + '][' + colId + ']',
  //   value = (typeof values[colId] != 'undefined' ? values[colId] : ''),
  //   textual = Craft.inArray(col.type, Craft.EditableTable.textualColTypes);
  //
  //   rowHtml += '<td class="' + (textual ? 'textual' : '') + ' ' + (typeof col['class'] != 'undefined' ? col['class'] : '') + '"' +
  //   (typeof col['width'] != 'undefined' ? ' width="' + col['width'] + '"' : '') +
  //   '>';
  //
  //   switch (col.type)      {
  //     case 'checkbox': {
  //       rowHtml += '<input type="hidden" name="' + name + '">' +
  //       '<input type="checkbox" name="' + name + '" value="1"' + (value ? ' checked' : '') + '>';
  //
  //       break;
  //     }
  //     case 'assets': {
  //       rowHtml += ''
  //       break;
  //     }
  //
  //     default: {
  //       rowHtml += '<textarea name="' + name + '" rows="1">' + value + '</textarea>';
  //     }
  //   }
  //
  //   rowHtml += '</td>';
  // }
  //
  // rowHtml += '<td class="thin action"><a class="move icon" title="' + Craft.t('Reorder') + '"></a></td>' +
  // '<td class="thin action"><a class="delete icon" title="' + Craft.t('Delete') + '"></a></td>' +
  // '</tr>';

// };
