 
function enableCellNavigation(ownobject){
    var arrow = {
      left: 37,
      up: 38,
      right: 39,
      down: 40
    };

    var e = event;
      if ($.inArray(e.which, [arrow.left, arrow.up, arrow.right, arrow.down]) < 0) {
        return;
      }

      var input = e.target;
      var td = $(e.target).closest('td');
      var moveTo = null;

      switch (e.which) {

        case arrow.left:
          {
            if (input.selectionStart == 0) {
              moveTo = td.prev('td:has(input[type=text],textarea)');
            }
            break;
          }
        case arrow.right:
          {
            if (input.selectionEnd == input.value.length) {
              moveTo = td.next('td:has(input[type=text],textarea)');
            }
            break;
          }

        case arrow.up:
        case arrow.down:
          {

            var tr = td.closest('tr');
            var tds = td[0].className.split(" ");
            var moveToRow = null;
            if (e.which == arrow.down) {
              moveToRow = tr.next('tr').find('.'+tds[0]);
            } else if (e.which == arrow.up) {
              moveToRow = tr.prev('tr').find('.'+tds[0]);
            }
            if (moveToRow.length) {
              moveTo = moveToRow;
            }

            break;
          }

      }

      if (moveTo && moveTo.length) {

        e.preventDefault();

        moveTo.find('input[type=text],textarea').each(function (i, input) {
          input.focus();
          input.select();
        });

      }

  }



