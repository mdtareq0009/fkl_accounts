$(function () {
  if ($('.content-title-big').length > 0) {
    var getData = $('.content-title-big').data('group');
    var getMenu = $('.content-title-big').data('page');
    $('.' + getData).css('display', 'block');
    $('.' + getData + 's').addClass('active-toggle active-control rotateicon');
    $('.' + getData + 's').parent('li').addClass('active-container');
    // $('.'+getData+'s').addClass('bg-darkCrimson');
    $('.' + getMenu.replace(/[^0-9A-Za-z-]/g, "")).css({ 'background': '#006d77', 'font-weight': 'bold' });
  }

  $('.navview-menu .dropdown-toggle').on('click', function (e) {
    e.preventDefault();
    //$(this).removeClass('rotateicon');
    if ($(this).hasClass('rotateicon') == true) {
      $(this).removeClass('rotateicon');
    } else {
      $('.dropdown-toggle').removeClass('rotateicon');
      $(this).addClass('rotateicon');
    }
  });


  /*
  **---------------------------------------------------
  **   Order Search
  **---------------------------------------------------
  */

  $('.search-ordernumber input').on('keypress', function (evt) {
    var keycode = (evt.keyCode ? evt.keyCode : evt.which);
    if (keycode == '13') {
      preloaderStart();
      var getOrderNumber = $(this).val();
      getOrderNumber = getOrderNumber.replace(/(['"])/g, "\\$1");
      var getOrderType = $('.input-ordernumber-type select').val();
      var csrf = $('.csrf').val();
      $('.content-visible').addClass('accessories-content-hidden');
      $('.maingridheader').css('display', 'none');
      $('.gridappender').empty();
      $('.accessories-disable').addClass('disabled');
      $('.accessories-disable').attr('disabled', true);
      $('.accessories-disable').find('input').attr('disabled', true);
      $('.accessories-disable').find('select').attr('disabled', true);
      $('.lotappend').empty();
      $('#stylename').html('');
      $('#fklno').html('');
      $('#buyername').html('');
      $('#season').html('');
      $('#dept').html('');
      $('#kimble').html('');
      $('#qty').html('');
      $('.allcountry').val('');
      $('.allsize').val('');
      $('.gmtqty').val('');
      $('.workorder-submit').css('display', 'none');
      $('.workorder-submit').prop('disabled', true);
      $('.extra-notes').empty();
      $('.workorder-draft').css('display', 'none');
      $('.workorder-draft').prop('disabled', true);
      $('.orderfklcommon').css('display', 'none');
      if (getOrderNumber == '') {
        accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! ' + getOrderType.toLowerCase() + ' is empty.<p>', 'alert');
        preloaderClose();
      } else if (getOrderNumber.toLowerCase() == 'block' || getOrderNumber.toLowerCase() == 'store') {
        $('.content-visible').removeClass('accessories-content-hidden');
        $('.accessories-disable').removeClass('disabled');
        $('.accessories-disable').attr('disabled', false);
        $('.accessories-disable').find('input').attr('disabled', false);
        $('.accessories-disable').find('select').attr('disabled', false);
        $('.allcountry').val('');
        $('.allsize').val('');
        $('#stylename').html("<input type='text' class='stylename-input input-small' style='max-width: 170px; display: inline;' value=''>");
        $('#buyername').html("<input type='text' class='buyername-input input-small required-field' style='max-width: 150px; display: inline;' value=''><span class='invalid_feedback'>Buyer name is required.</span>");
        // // console.log(getResponse['data']);
        if (getOrderType == 'Order number') {
          $('.fklnumbersection').css('display', '');
        } else if (getOrderType == 'FKL number') {
          $('.ordernumbersection').css('display', '');
        }
        $('#ordernumber').html("<input type='text' class='ordernumber-input input-small' style='max-width: 150px; display: inline;' value='TBC'>");
        $('#fklno').html("<input type='text' class='fklno-input input-small' style='max-width: 150px; display: inline;' value='TBC'>");
        $('#season').html("<input type='text' class='season-input input-small' style='max-width: 150px; display: inline;' value=''>");
        $('#dept').html("<input type='text' class='dept-input input-small' style='max-width: 170px; display: inline;' value=''>");
        $('#kimble').html("<input type='text' class='kimble-input input-small' style='max-width: 150px; display: inline;' value=''>");
        preloaderClose();
      } else {
        $.ajax({
          type: 'POST',
          url: 'action/workorder-action.php',
          dataType: 'json',
          data: { 'formName': 'ordersearch', 'ordertype': getOrderType, 'ordernumber': getOrderNumber, 'csrf': csrf },
          success: function (getResponse) {
            preloaderClose();
            if (getResponse['status'] == 'notfound') {
              accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! ' + getOrderType.toLowerCase() + ' <strong>(' + getOrderNumber + ')</strong> is invalid or already shipped.<p>', 'alert');
            } else if (getResponse['status'] == 'success') {
              $('.content-visible').removeClass('accessories-content-hidden');
              $('.accessories-disable').removeClass('disabled');
              $('.accessories-disable').attr('disabled', false);
              $('.accessories-disable').find('input').attr('disabled', false);
              $('.accessories-disable').find('select').attr('disabled', false);
              $('.allcountry').val(getResponse['data'][0]['COUNTRY'].split('+').join(','));
              $('.allsize').val(unique(getResponse['data'][0]['SIZEBREAKDOWN'].replace('##', ',').split(',')).join(','));
              $('#stylename').html(getResponse['data'][0]['STYLENAME']);
              $('#buyername').html(getResponse['data'][0]['VNAME']);
              // console.log(getResponse['data']);
              if (getOrderType == 'Order number') {
                $('.fklnumbersection').css('display', '');
              } else if (getOrderType == 'FKL number') {
                $('.ordernumbersection').css('display', '');
              }
              $('#ordernumber').html(getResponse['data'][0]['VORDERNUMBER']);
              $('#fklno').html(getResponse['data'][0]['KSID']);
              $('#season').html(getResponse['data'][0]['SEASON']);
              $('#dept').html(getResponse['data'][0]['VDEPTNAME']);
              $('#kimble').html(getResponse['data'][0]['KIMBALLNO']);
            } else if (getResponse['status'] == 'csrfmissing') {
              accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! CSRF Token verification faild. Refresh your browser and try again.<p>', 'alert');
            } else {
              accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Something went wrong! Refresh your browser and try again.<p>', 'alert');
            }
          }
        });
      }
    }
  });

  /*
  **---------------------------------------------------
  **   Goods select events
  **---------------------------------------------------
  */

  $('.itemsevent select').on('change', function (evt) {
    evt.preventDefault();
    evt.stopImmediatePropagation();
    var ownobject = $(this);
    var tempArr1 = [];
    $('.itemsevent select :selected').each(function (index) {
      var id = $(this).val();
      var data = $(this).text();
      var trid = data.replace(/[_\W]+/g, "-").toLowerCase() + id;
      tempArr1.push(trid);
      var background = '';
      if (index % 2 == 0) {
        background = "custom-table-bg";
        backgroundCell = "bg-darkCyan fg-white";
      } else {
        background = "custom-table-bg1";
        backgroundCell = "bg-darkGrayBlue fg-white";
      }
      if ($('.gridappender').find('#' + trid).length == 0) {
        var perameters = $(this).data('perameters').split(',');
        perameters.push('Code No.');
        perameters.push('Garments Qty');
        perameters.push('Garments Qty (Size)');
        perameters.push('Addition');
        perameters.push('Converter');
        perameters.push('Upload');
        if ($('.workorder-table-main:last').hasClass('custom-table-bg')) {
          background = 'custom-table-bg1';
          backgroundCell = "bg-darkGrayBlue fg-white";
        } else {
          background = 'custom-table-bg';
          backgroundCell = "bg-darkCyan fg-white";
        }
        var dataTableCreate = "";
        dataTableCreate += "<div class='workorder-table-main pt-1 mb-2 " + $.trim(background) + "'  id='" + trid + "' style='padding-bottom:1px;'>";
        dataTableCreate += "";
        dataTableCreate += "<table class='subcompact cell-border table searchordertable' style='margin-top: 0.5rem; margin-bottom: 0.5rem;'>";
        dataTableCreate += "<tr>";
        dataTableCreate += "<td style='background: #e0f0f1; width:132px; font-weight:bold;' class='text-left'>Available Columns</td>";
        var cellData1 = "";
        var cellData2 = "";
        var cellData3 = "";
        $.each(perameters, function (index, val) {
          if (($.trim(val.toLowerCase()) == 'color wise qty' || $.trim(val.toLowerCase()) == 'color & size wise qty' || $.trim(val.toLowerCase()) == 'kimball/color/size wise qty' || $.trim(val.toLowerCase()) == 'size wise qty' || $.trim(val.toLowerCase()) == 'kimball & color wise qty')) {
            cellData1 += "<input type='checkbox' data-role='checkbox' value='" + $.trim(val) + "' data-parentid='" + id + "' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border  bd-white rounded fg-white parameters-disabled parameters " + $.trim(val).replace(/[_\W]+/g, "-").toLowerCase() + "' data-caption='" + $.trim(val) + "' data-cls-check='bd-white myCheck'>";
          } else if ($.trim(val.toLowerCase()) == 'addition') {
            cellData3 += "<input type='checkbox' data-role='checkbox' disabled value='" + $.trim(val) + "' data-parentid='" + id + "' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border bg-crimson rounded bd-white addition-enable fg-white parameters " + $.trim(val).replace(/[_\W]+/g, "-").toLowerCase() + "' data-caption='" + $.trim(val) + "'>";
          } else if ($.trim(val.toLowerCase()) == 'converter') {
            cellData3 += "<input type='checkbox' data-role='checkbox' disabled value='" + $.trim(val) + "' data-parentid='" + id + "' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border bg-taupe rounded bd-white converter-enable fg-white parameters " + $.trim(val).replace(/[_\W]+/g, "-").toLowerCase() + "' data-caption='" + $.trim(val) + "'>";
          } else if ($.trim(val.toLowerCase()) == 'upload') {
            if (data == 'Hang Tag' && ($.trim($('#buyername').text()) == 'Primark' || $.trim($('#buyername').text()) == 'Penneys' || $.trim($('#buyername').text()) == 'Primark & Penneys')) {
              cellData3 += "<div class='row d-flex flex-justify-center'><button type='button' class='mt-1 image-button secondary excel-upload' data-type='hangtag' style='height: 28px;' data-parentid='" + id + "' data-name='" + data + "' data-tableclass='" + trid + "'><span class='mif-upload icon' style='height: 28px; line-height: 24px; font-size: .9rem; width: 23px;'></span><span class='caption'>Upload (.xlsx or .csv)</span></button></div>";
            }
            if (data == 'Kimball Tag' && ($.trim($('#buyername').text()) == 'Primark' || $.trim($('#buyername').text()) == 'Penneys' || $.trim($('#buyername').text()) == 'Primark & Penneys')) {
              cellData3 += "<div class='row d-flex flex-justify-center'><button type='button' class='mt-1 image-button secondary excel-upload' data-type='kimballtag' style='height: 28px;' data-parentid='" + id + "' data-name='" + data + "' data-tableclass='" + trid + "'><span class='mif-upload icon' style='height: 28px; line-height: 24px; font-size: .9rem; width: 23px;'></span><span class='caption'>Upload (.xlsx or .csv)</span></button></div>";
            }
          } else if ($.trim(val.toLowerCase()) == 'grmnts color') {
            cellData2 += "<input type='checkbox' data-role='checkbox' value='" + $.trim(val) + "' data-parentid='" + id + "' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border  bd-white rounded fg-white bg-darkCyan parameters-disabled parameters-groupcommon parameters " + $.trim(val).replace(/[_\W]+/g, "-").toLowerCase() + "' data-caption='" + $.trim(val) + "' data-cls-check='bd-white myCheck'>";
          } else if ($.trim(val.toLowerCase()) == 'grmnts color/kimball/lot') {
            cellData2 += "<input type='checkbox' data-role='checkbox' value='" + $.trim(val) + "' data-parentid='" + id + "' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border  bd-white rounded fg-white bg-darkCyan parameters-disabled parameters-groupcommon parameters " + $.trim(val).replace(/[_\W]+/g, "-").toLowerCase() + "' data-caption='" + $.trim(val) + "' data-cls-check='bd-white myCheck'>";
          } else if ($.trim(val.toLowerCase()) == 'size name') {
            cellData2 += "<input type='checkbox' data-role='checkbox' value='" + $.trim(val) + "' data-parentid='" + id + "' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border  bd-white rounded fg-white bg-darkCyan parameters-disabled parameters-groupcommon parameters " + $.trim(val).replace(/[_\W]+/g, "-").toLowerCase() + "' data-caption='" + $.trim(val) + "' data-cls-check='bd-white myCheck'>";
          } else if ($.trim(val.toLowerCase()) == 'garments qty') {
            cellData2 += "<input type='checkbox' data-role='checkbox' value='" + $.trim(val) + "' data-parentid='" + id + "' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border  bd-white rounded fg-white bg-darkCyan parameters-disabled parameters parameters-groupcommon " + $.trim(val).replace(/[_\W]+/g, "-").toLowerCase() + "' data-caption='" + $.trim(val) + "' data-cls-check='bd-white myCheck'>";
          } else if ($.trim(val.toLowerCase()) == 'garments qty (size)') {
            cellData2 += "<input type='checkbox' data-role='checkbox' value='" + $.trim(val) + "' data-parentid='" + id + "' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border  bd-white rounded fg-white bg-darkCyan parameters-disabled parameters parameters-groupcommon " + $.trim(val).replace(/[_\W]+/g, "-").toLowerCase() + "' data-caption='" + $.trim(val) + "' data-cls-check='bd-white myCheck'>";
          } else {
            console.log(val);

            cellData2 += "<input type='checkbox' data-role='checkbox' value='" + $.trim(val) + "' data-parentid='" + id + "' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border bd-white rounded bg-white general-parameters parameters " + $.trim(val).replace(/[_\W]+/g, "-").toLowerCase() + "' data-caption='" + $.trim(val) + "'>";
          }
        });
        var isReadonly = 'readonly';
        if ($('.search-ordernumber input').val() == undefined) {
          if ($('.vordernumberorfklnumber input').val().toLowerCase() == 'block' || $('.vordernumberorfklnumber input').val().toLowerCase() == 'store') {
            cellData1 = '';
            isReadonly = '';
          }
        } else {
          if ($('.search-ordernumber input').val().toLowerCase() == 'block' || $('.search-ordernumber input').val().toLowerCase() == 'store') {
            cellData1 = '';
            isReadonly = '';
          }
        }
        dataTableCreate += "<td class='text-left' style='background: #e0f0f1; vertical-align: top !important;'><div class='columheader text-center'>Knitting Shit Data</div><div>" + cellData1 + "</div></td><td class='text-left' style='background: #e0f0f1; vertical-align: top !important;'><div class='columheader text-center'>Customize Options</div><div>" + cellData2 + "</div></td><td class='text-left' style='background: #e0f0f1; vertical-align: top !important;'><div class='columheader text-center'>Extra Operation</div><div style='width: 221px; margin: 0 auto;'>" + cellData3 + "</div></td>";
        dataTableCreate += "</td></tr></table>";
        dataTableCreate += "<table class='table subcompact cell-border table maingrid bg-white itemtable " + trid + " manual-data' data-uniqueid='" + id + "' onkeydown='enableCellNavigation($(this))'>";
        dataTableCreate += "<tr>";
        dataTableCreate += "<th class='items-header'>Name of Item</th>";
        dataTableCreate += "<th class='totalqty-header'>W.O. Required Qty.</th>";
        dataTableCreate += "<th class='remarks-header'>Remarks</th>";
        dataTableCreate += "</tr>";
        dataTableCreate += "<tr class='data-row-hidden appended-row' style='display:none;'>";
        dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' " + isReadonly + " class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + trid + "\")' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold' onclick='appendCommon($(this))'>" + $(this).data('qtyunit') + "</span></div></div></td>";
        dataTableCreate += "<td class='remarks' style='position:relative; overflow:hidden;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + trid + "\")'><span class='mif-copy'></span></a><div class='row-removeBtn ribbed-darkRed' onclick='rowRemover($(this), \"" + trid + "\");'><span class='mif-cross'></span></div>";
        dataTableCreate += "</tr>";
        dataTableCreate += "<tr class='data-row'>";
        dataTableCreate += "<td class='text-center text-bold maingrid-rowspan items-name items-" + id + "' data-itemid='" + id + "' rowspan='1' data-itemname='" + data + "'>" + data + "</td>";
        dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' " + isReadonly + " class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + trid + "\")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold' onclick='appendCommon($(this))'>" + $(this).data('qtyunit') + "</span></div></div></td>";
        dataTableCreate += "<td class='remarks' style='position:relative;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + trid + "\")'><span class='mif-copy'></span></a></td>";
        dataTableCreate += "</tr>";
        dataTableCreate += "<tr style='background: #e0f0f1;'>";
        dataTableCreate += "<td style='font-weight:bold;' class='text-right grandQtyCell'><button onclick='rowAdder($(this), \"" + trid + "\")' type='button' class='tool-button ribbed-teal success' style='position: absolute;width: 20px;height: 20px;line-height: 18px;top: 6px;z-index: 1083;right: 3px;'><span class='mif-plus' style='font-size: 13px;'></span></button><p style='width: 155px;margin: 0px;'>Quantity Grand Total</p></td>";
        dataTableCreate += "<td class='grandtotalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center grand-totalqty-input' name='grandqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold grandunit' onclick='appendCommon($(this))'>" + $(this).data('qtyunit') + "</span></div></div></td>";
        dataTableCreate += "<td class='itemqty-errors text-left'><span class='invalid_feedback'>W.O. required quantity grand total must be greater than zero(0).</span></td>";
        dataTableCreate += "</tr>";
        dataTableCreate += "</table>";
        dataTableCreate += "</div>";
        $('.gridappender').append(dataTableCreate);
      }
    });
    $('.gridappender .workorder-table-main').each(function () {
      if ($.inArray($(this).attr('id'), tempArr1) == -1) {
        $(this).remove();
      }
    });
    if (tempArr1.length == 0) {
      $('.maingridheader').css('display', 'none');
      $('.workorder-submit').css('display', 'none');
      $('.workorder-submit').prop('disabled', true);
      $('.workorder-draft').css('display', 'none');
      $('.workorder-draft').prop('disabled', true);
      $('.extra-notes').empty();
    } else {
      $('.maingridheader').css('display', 'block');
      $('.workorder-submit').css('display', '');
      $('.workorder-submit').prop('disabled', false);
      $('.workorder-draft').css('display', '');
      $('.workorder-draft').prop('disabled', false);
      if ($('.extraNotes-table').length == 0) {
        var extraNotes = '';
        extraNotes += "<table class='subcompact cell-border table searchordertable extraNotes-table' style='margin-top: 0.5rem; margin-bottom: 0.5rem;'>";
        extraNotes += "<tr>";
        extraNotes += "<td style='background: #e0f0f1; width:130px; font-weight:bold; text-align:center;'>Note</td>";
        extraNotes += "<td class='text-center bg-white'><textarea data-role='textarea' width='100%' name='extranotes' class='extranotes'></textarea></td>";
        extraNotes += "</tr></table>";
        $('.extra-notes').append(extraNotes);
      }
    }
    tempArr1 = [];
  });

  /*============================================================================================
              ------------------Goods checkbox Click Event-------------------------
  ==============================================================================================*/

  $('.gridappender').on('click', '.parameters input', function (evt) {
    evt.stopImmediatePropagation();
    var tempArr = [];
    var ownObj = $(this);
    var data = ownObj.val();
    var id = ownObj.data('parentid');
    var trid = data.replace(/[_\W]+/g, "-").toLowerCase() + id;
    var parentDivId = ownObj.closest('.workorder-table-main').attr('id');
    // Modified By SPN Start
    var itemName = $('.items-name.items-' + id).data('itemname');

    if ($.trim($('#buyername').text()) == 'Peacocks' && itemName == 'Folded Card Board') {
      var dataVar = presetData('color&sizewise', itemName);

      var qtySum = 0;
      $.each(dataVar.colorsizeQty, function (index, val) {
        $.each(val, function (indexx, vall) {
          qtySum += vall;
        });
      });
      // console.log(Math.round(qtySum + (qtySum * 0.01)));
    }
    
    // Modified By SPN End

    // if event is checked
    if (ownObj.is(':checked')) {
      if (ownObj.parent('label').hasClass('parameters-disabled')) {
        $('#' + parentDivId).find('.parameters-disabled input').prop('disabled', true);
        ownObj.prop('disabled', false);
      }
      if (ownObj.parent('label').hasClass('parameters-groupcommon')) {
        $('#' + parentDivId).find('.parameters-disabled input').prop('disabled', true);
        $('#' + parentDivId).find('.parameters-groupcommon.parameters-disabled input').prop('disabled', true);
        ownObj.prop('disabled', false);
        $('#' + parentDivId).find('.garments-qty input').prop('disabled', false);
        $('#' + parentDivId).find('.garments-qty-size- input').prop('disabled', false);
      }
      if ($('#' + parentDivId).find('.grmnts-color input').is(':checked')) {
        $('#' + parentDivId).find('.grmnts-color input').prop('disabled', false);
        $('#' + parentDivId).find('.garments-qty input').prop('disabled', false);
        $('#' + parentDivId).find('.garments-qty-size- input').prop('disabled', false);
      }
      if ($('#' + parentDivId).find('.grmnts-color-kimball-lot input').is(':checked')) {
        $('#' + parentDivId).find('.grmnts-color-kimball-lot input').prop('disabled', false);
        $('#' + parentDivId).find('.garments-qty input').prop('disabled', false);
        $('#' + parentDivId).find('.garments-qty-size- input').prop('disabled', false);
      }
      if ($('#' + parentDivId).find('.size-name input').is(':checked')) {
        $('#' + parentDivId).find('.size-name input').prop('disabled', false);
        $('#' + parentDivId).find('.garments-qty input').prop('disabled', false);
        $('#' + parentDivId).find('.garments-qty-size- input').prop('disabled', false);
      }

      if (data.toLowerCase() == 'addition') {
        $('.addition-popup').css('display', 'block');
        $('.tableclass').val(parentDivId);
        $('.columnclass').val(trid);
      }
      if (data.toLowerCase() == 'converter') {
        $('.converter-popup').css('display', 'block');
        var getItemName = $.trim($('.items-' + id).text());
        var extraFieldAdded = '';
        if ($('.' + parentDivId).find('.grandunit').text() == 'GG' || $('.' + parentDivId).find('.grandunit').text() == 'Gros') {
          var conUnit = 'Pcs';
          extraFieldAdded += '<div class="row no-gap"><div class="cell">';
          extraFieldAdded += '<input type="text" class="input-small text-center" readonly name="" value="1 ' + $('.' + parentDivId).find('.grandunit').text() + '"></div>';
          extraFieldAdded += '<div class="cell-1 text-center text-bold">=</div><div class="cell">';
          extraFieldAdded += '<input type="text"  class="input-small text-center converter-rules" name="" value="1"></div>';
          extraFieldAdded += '<div class="cell-1">' + conUnit + '</div></div>';
        } else if ($('.' + parentDivId).find('.grandunit').text() == "Con's") {
          var conUnit = 'Mtr';
          extraFieldAdded += '<div class="row no-gap"><div class="cell">';
          extraFieldAdded += '<input type="text" class="input-small text-center" readonly name="" value="1 Cons"></div>';
          extraFieldAdded += '<div class="cell-1 text-center text-bold">=</div><div class="cell">';
          // Modified by SPN Start
          if ($.trim($('#buyername').text()) == 'Peacocks') {

            extraFieldAdded += '<input type="text"  class="input-small text-center converter-rules" name="" value="4000"></div>';
          } else {

            extraFieldAdded += '<input type="text"  class="input-small text-center converter-rules" name="" value="1"></div>';
          }
          // Modified by SPN End

          extraFieldAdded += '<div class="cell-1">' + conUnit + '</div></div>';
        } else if ($('.' + parentDivId).find('.grandunit').text() == "Rolls") {
          var conUnit = 'Yrds';
          extraFieldAdded += '<div class="row no-gap"><div class="cell">';
          extraFieldAdded += '<input type="text" class="input-small text-center" readonly name="" value="1 Rolls"></div>';
          extraFieldAdded += '<div class="cell-1 text-center text-bold">=</div><div class="cell">';
          extraFieldAdded += '<input type="text"  class="input-small text-center converter-rules" name="" value="1"></div>';
          extraFieldAdded += '<div class="cell-1">' + conUnit + '</div></div>';
        } else if ($('.' + parentDivId).find('.grandunit').text() == "KG") {
          var conUnit = 'Kg';
          extraFieldAdded += '<div class="row no-gap"><div class="cell">';
          extraFieldAdded += '<input type="text" class="input-small text-center" readonly name="" value="1 dzn"></div>';
          extraFieldAdded += '<div class="cell-1 text-center text-bold">=</div><div class="cell">';
          extraFieldAdded += '<input type="text"  class="input-small text-center converter-rules" name="" value="12"></div>';
          extraFieldAdded += '<div class="cell-1">' + 'pcs' + '</div></div>';
        } else {
          var conUnit = $('.' + parentDivId).find('.grandunit').text();
        }
        // alert(getItemName+' ('+conUnit+') Per Garment');
        // ====================Original Start=========================
        // $('#convertiontype1').text(getItemName+' ('+$.trim(conUnit)+') Per Garment');
        // $('#convertiontype2').text('Garment(s) Per '+getItemName+' ('+$.trim(conUnit)+')');
        // $('#convertiontype1').attr('value', getItemName+' ('+$.trim(conUnit)+') Per Garment');
        // $('.extraCalAdded').empty();
        // $('.extraCalAdded').append(extraFieldAdded);
        // extraFieldAdded = '';
        // $('#convertiontype2').attr('value', 'Garment(s) Per '+getItemName+' ('+$.trim(conUnit)+')');
        // $('.contableclass').val(parentDivId);
        // $('.concolumnclass').val(trid);
        // ====================Original End=========================
        // ===============For Fabric Consumption==============

        if (getItemName.toLowerCase().includes('fabric')) {
          $('#convertiontype1').text('Consumption');
        } else {
          $('#convertiontype1').text(getItemName + ' (' + $.trim(conUnit) + ') Per Garment / Consumption');

        }

        if (getItemName.toLowerCase().includes('fabric')) {
          $('#convertiontype2').text('Consumption');
        } else {
          $('#convertiontype2').text('Garment(s) Per ' + getItemName + ' (' + $.trim(conUnit) + ') / Consumption');
        }

        if (getItemName.toLowerCase().includes('fabric')) {
          $('#convertiontype1').attr('value', 'Consumption');
        } else {
          // Modified by SPN Start
          // if ($.trim($('#buyername').text()) == 'Peacocks') {
          //   $('#convertiontype1').attr('value', 'Consumption');

          // } else {

          // }
          $('#convertiontype1').attr('value', getItemName + ' (' + $.trim(conUnit) + ') Per Garment / Consumption');
          // Modified by SPN End

        }

        $('.extraCalAdded').empty();
        $('.extraCalAdded').append(extraFieldAdded);
        extraFieldAdded = '';
        if (getItemName.toLowerCase().includes('fabric')) {
          $('#convertiontype2').attr('value', 'Consumption');
        } else {
          $('#convertiontype2').attr('value', 'Garment(s) Per ' + getItemName + ' (' + $.trim(conUnit) + ') / Consumption');
        }
        $('.contableclass').val(parentDivId);
        $('.concolumnclass').val(trid);
        // ===============For Fabric Consumption End================
      }
      var tableRowsCount = $('#' + parentDivId).find('.maingrid tr').length;
      var presetDataVar = [];
      if (data.toLowerCase() == 'color wise qty') {
        $('#' + parentDivId).find('.addition-enable input').prop('disabled', false);
        $('#' + parentDivId).find('.converter-enable input').prop('disabled', false);
        $('.datafill-popup').css('display', 'block');
        $('.datafilltableclass').val(parentDivId);
        $('.datafillcolumnclass').val(trid);
        $('.datafilldataname').val(data.toLowerCase());
        presetDataVar = presetData('colorwise');
      } else if (data.toLowerCase() == 'color & size wise qty') {
        $('#' + parentDivId).find('.addition-enable input').prop('disabled', false);
        $('#' + parentDivId).find('.converter-enable input').prop('disabled', false);
        $('.datafill-popup').css('display', 'block');
        $('.datafilltableclass').val(parentDivId);
        $('.datafillcolumnclass').val(trid);
        $('.datafilldataname').val(data.toLowerCase());
        presetDataVar = presetData('color&sizewise');
        localStorage.setItem("colorsizewisedata", JSON.stringify(presetDataVar));
        //console.log(JSON.parse(localStorage.getItem("colorsizewisedata")));  
      } else if (data.toLowerCase() == 'grmnts color') {
        $('.datafill-popup1').css('display', 'block');
        $('.datafilltableclass1').val(parentDivId);
        $('.datafillcolumnclass1').val(trid);
        $('.datafilldataname1').val(data.toLowerCase());
        presetDataVar = presetData('colorwise');
      } else if (data.toLowerCase() == 'size wise qty') {
        $('#' + parentDivId).find('.addition-enable input').prop('disabled', false);
        $('#' + parentDivId).find('.converter-enable input').prop('disabled', false);
        $('.datafill-popup').css('display', 'block');
        $('.datafilltableclass').val(parentDivId);
        $('.datafillcolumnclass').val(trid);
        $('.datafilldataname').val(data.toLowerCase());
        presetDataVar = presetData('sizewise');
      } else if (data.toLowerCase() == 'size name') {
        $('.datafill-popup1').css('display', 'block');
        $('.datafilltableclass1').val(parentDivId);
        $('.datafillcolumnclass1').val(trid);
        $('.datafilldataname1').val(data.toLowerCase());
      } else if (data.toLowerCase() == 'kimball & color wise qty') {
        $('#' + parentDivId).find('.addition-enable input').prop('disabled', false);
        $('#' + parentDivId).find('.converter-enable input').prop('disabled', false);
        $('.datafill-popup').css('display', 'block');
        $('.datafilltableclass').val(parentDivId);
        $('.datafillcolumnclass').val(trid);
        $('.datafilldataname').val(data.toLowerCase());
        presetDataVar = presetData('kimballsizewise');
      } else if (data.toLowerCase() == 'kimball/color/size wise qty') {
        $('#' + parentDivId).find('.addition-enable input').prop('disabled', false);
        $('#' + parentDivId).find('.converter-enable input').prop('disabled', false);
        $('.datafill-popup').css('display', 'block');
        $('.datafilltableclass').val(parentDivId);
        $('.datafillcolumnclass').val(trid);
        $('.datafilldataname').val(data.toLowerCase());
        presetDataVar = presetData('kimball&sizewise');
        localStorage.setItem("kimballcolorsizewisedata", JSON.stringify(presetDataVar));
        //console.log(JSON.parse(localStorage.getItem("kimballcolorsizewisedata")));  
      } else if (data.toLowerCase() == 'grmnts color/kimball/lot') {
        $('.datafill-popup1').css('display', 'block');
        $('.datafilltableclass1').val(parentDivId);
        $('.datafillcolumnclass1').val(trid);
        $('.datafilldataname1').val(data.toLowerCase());
        presetDataVar = presetData('kimballsizewise');
      } else if (data.toLowerCase() == 'garments qty') {
        $('#' + parentDivId).find('.addition-enable input').prop('disabled', false);
        $('#' + parentDivId).find('.converter-enable input').prop('disabled', false);
        $('#' + parentDivId).find('.garments-qty-size- input').prop('disabled', true);
      } else if (data.toLowerCase() == 'garments qty (size)') {
        $('#' + parentDivId).find('.addition-enable input').prop('disabled', false);
        $('#' + parentDivId).find('.converter-enable input').prop('disabled', false);
        $('#' + parentDivId).find('.garments-qty input').prop('disabled', true);
      }
      $('#' + parentDivId).find('.maingrid tr').each(function (index) {
        var gridsl = index;
        //Table ColumnsHeader
        if (index == 0) {
          if (data.toLowerCase() == 'size wise qty') {
            if ($('#' + parentDivId).find('.symbol-cell').length > 0) {
              $(this).find('th:nth-last-child(3)').before("<th class='" + trid + "-header' data-columnname='Size Name'>Size Name</th><th class='" + trid + "-qty-header' data-columnname='Garments Qty.'>Garments Qty.</th>");
            } else {
              $(this).find('th:nth-last-child(2)').before("<th class='" + trid + "-header' data-columnname='Size Name'>Size Name</th><th class='" + trid + "-qty-header' data-columnname='Garments Qty.'>Garments Qty.</th>");
            }
          } else if (data.toLowerCase() == 'symbol') {
            $(this).find('th:nth-last-child(1)').after("<th class='" + trid + "-header' data-columnname='" + data + "'>" + data + "</th>");
          } else if (data.toLowerCase() == 'color wise qty') {
            if ($('#' + parentDivId).find('.symbol-cell').length > 0) {
              $(this).find('th:nth-last-child(3)').before("<th class='" + trid + "-header' data-columnname='Color Name'>Color Name</th><th class='" + trid + "-qty-header' data-columnname='Garments Qty.'>Garments Qty.</th>");
            } else {
              $(this).find('th:nth-last-child(2)').before("<th class='" + trid + "-header' data-columnname='Color Name'>Color Name</th><th class='" + trid + "-qty-header' data-columnname='Garments Qty.'>Garments Qty.</th>");
            }
          } else if (data.toLowerCase() == 'grmnts color') {
            if ($('#' + parentDivId).find('.symbol-cell').length > 0) {
              $(this).find('th:nth-last-child(3)').before("<th class='" + trid + "-header' data-columnname='Color Name'>Color Name</th>");
            } else {
              $(this).find('th:nth-last-child(2)').before("<th class='" + trid + "-header' data-columnname='Color Name'>Color Name</th>");
            }
          } else if (data.toLowerCase() == 'grmnts color/kimball/lot') {
            if ($('#' + parentDivId).find('.symbol-cell').length > 0) {
              $(this).find('th:nth-last-child(3)').before("<th class='" + trid + "-header' data-columnname='Color Name'>Color Name</th><th class='" + trid + "-header' data-columnname='Kimball No.'>Kimball No.</th><th class='" + trid + "-header' data-columnname='Lot No.'>Lot No.</th>");
            } else {
              $(this).find('th:nth-last-child(2)').before("<th class='" + trid + "-header' data-columnname='Color Name'>Color Name</th><th class='" + trid + "-header' data-columnname='Kimball No.'>Kimball No.</th><th class='" + trid + "-header' data-columnname='Lot No.'>Lot No.</th>");
            }
          } else if (data.toLowerCase() == 'garments qty') {
            if ($('#' + parentDivId).find('.symbol-cell').length > 0) {
              $(this).find('th:nth-last-child(3)').before("<th class='" + trid + "-qty-header' data-columnname='Garments Qty.'>Garments Qty.</th>");
            } else {
              $(this).find('th:nth-last-child(2)').before("<th class='" + trid + "-qty-header' data-columnname='Garments Qty.'>Garments Qty.</th>");
            }
          } else if (data.toLowerCase() == 'kimball & color wise qty') {
            if ($('#' + parentDivId).find('.symbol-cell').length > 0) {
              $(this).find('th:nth-last-child(3)').before("<th class='" + trid + "-header' data-columnname='Color Name'>Color Name</th><th class='" + trid + "-header kimballcelll-header' data-columnname='Kimball No.' ondblclick='columnRemove(\"" + parentDivId + "\", \"kimballcelll\")'>Kimball No.</th><th class='" + trid + "-header' data-columnname='Lot No.'>Lot No.</th><th class='" + trid + "-qty-header' data-columnname='Garments Qty.'>Garments Qty.</th>");
            } else {
              $(this).find('th:nth-last-child(2)').before("<th class='" + trid + "-header' data-columnname='Color Name'>Color Name</th><th class='" + trid + "-header kimballcelll-header' data-columnname='Kimball No.' ondblclick='columnRemove(\"" + parentDivId + "\", \"kimballcelll\")'>Kimball No.</th><th class='" + trid + "-header' data-columnname='Lot No.'>Lot No.</th><th class='" + trid + "-qty-header' data-columnname='Garments Qty.'>Garments Qty.</th>");
            }
          } else if (data.toLowerCase() == 'kimball/color/size wise qty') {
            var ksize = '<div class="row no-gap">';
            $.each(presetDataVar.sizename, function (index, val) {
              ksize += '<div class="cell size-' + val.replace(/[_\W]+/g, "-") + '-header bg-gray border bd-light csize-header" style="min-width: 60px;">' + val + '</div>';
            });
            ksize += '</div>';
            if ($('#' + parentDivId).find('.symbol-cell').length > 0) {
              $(this).find('th:nth-last-child(3)').before("<th class='" + trid + "-header' data-columnname='Color Name'>Color Name</th><th class='" + trid + "-header kimballcelll-header' data-columnname='Kimball No.' ondblclick='columnRemove(\"" + parentDivId + "\", \"kimballcelll\")'>Kimball No.</th><th class='" + trid + "-header' data-columnname='Lot No.'>Lot No.</th><th class='" + trid + "-header colorsizeqty-header' data-columnname=''><div>Size Wise Qty</div>" + ksize + "</th>");
            } else {
              $(this).find('th:nth-last-child(2)').before("<th class='" + trid + "-header' data-columnname='Color Name'>Color Name</th><th class='" + trid + "-header kimballcelll-header' data-columnname='Kimball No.' ondblclick='columnRemove(\"" + parentDivId + "\", \"kimballcelll\")'>Kimball No.</th><th class='" + trid + "-header' data-columnname='Lot No.'>Lot No.</th><th class='" + trid + "-header colorsizeqty-header' data-columnname=''><div>Size Wise Qty</div>" + ksize + "</th>");
            }
          } else if (data.toLowerCase() == 'garments qty (size)') {
            var ksize = '<div class="row no-gap">';
            var getSizeName = $('.allsize').val().split(',');
            $.each(getSizeName, function (index, val) {
              ksize += '<div class="cell size-' + val.replace(/[_\W]+/g, "-") + '-header bg-gray border bd-light csize-header" style="min-width: 60px;">' + val + '</div>';
            });
            ksize += '</div>';
            if ($('#' + parentDivId).find('.symbol-cell').length > 0) {
              $(this).find('th:nth-last-child(3)').before("<th class='" + trid + "-header colorsizeqty-header' data-columnname=''><div>Size Wise Qty</div>" + ksize + "</th>");
            } else {
              $(this).find('th:nth-last-child(2)').before("<th class='" + trid + "-header colorsizeqty-header' data-columnname=''><div>Size Wise Qty</div>" + ksize + "</th>");
            }
          } else if (data.toLowerCase() == 'color & size wise qty') {
            var csize = '<div class="row no-gap">';
            $.each(presetDataVar.sizename, function (index, val) {
              csize += '<div class="cell size-' + val.replace(/[_\W]+/g, "-") + '-header bg-gray border bd-light csize-header" style="min-width: 60px;">' + val + '</div>';
            });
            csize += '</div>';
            if ($('#' + parentDivId).find('.symbol-cell').length > 0) {
              $(this).find('th:nth-last-child(3)').before("<th class='" + trid + "-header' data-columnname='Color Name'>Color Name</th><th class='" + trid + "-header colorsizeqty-header' data-columnname=''><div>Size Wise Qty</div>" + csize + "</th>");
            } else {
              $(this).find('th:nth-last-child(2)').before("<th class='" + trid + "-header' data-columnname='Color Name'>Color Name</th><th class='" + trid + "-header colorsizeqty-header' data-columnname=''><div>Size Wise Qty</div>" + csize + "</th>");
            }
          } else if (data.toLowerCase() == 'addition') {
            if ($('#' + parentDivId).find('.symbol-cell').length > 0) {
              $(this).find('th:nth-last-child(3)').before("<th class='" + trid + "-header' data-columnname='Garments Qty With 0%'><input type='hidden' class='addition-columnname' value='Garments Qty With 0%'>Garments Qty With 0%</th>");
            } else {
              $(this).find('th:nth-last-child(2)').before("<th class='" + trid + "-header' data-columnname='Garments Qty With 0%'><input type='hidden' class='addition-columnname' value='Garments Qty With 0%'>Garments Qty With 0%</th>");
            }
          } else if (data.toLowerCase() == 'converter') {
            if ($('#' + parentDivId).find('.symbol-cell').length > 0) {
              $(this).find('th:nth-last-child(3)').before("<th class='" + trid + "-header' data-columnname=''></th>");
            } else {
              $(this).find('th:nth-last-child(2)').before("<th class='" + trid + "-header' data-columnname=''></th>");
            }
          } else {
            if ($('#' + parentDivId).find('.symbol-cell').length > 0) {
              $(this).find('th:nth-last-child(3)').before("<th class='" + trid + "-header' data-columnname='" + data + "'>" + data + "</th>");
            } else {
              $(this).find('th:nth-last-child(2)').before("<th class='" + trid + "-header' data-columnname='" + data + "'>" + data + "</th>");
            }
          }
        } else if (index == 1) {
          if (data.toLowerCase() == 'country') {
            var dataForCountry = '';
            var countryName = unique($('.allcountry').val().split(','));
            dataForCountry += "<select multiple style='max-width:110px; min-height:100px; margin: 0 auto;' class='custom-column-value'>";
            $.each(countryName, function (indext, val) {
              if (val != '') {
                dataForCountry += "<option value='" + val + "'>" + val + "</option>";
              }
            });
            //dataForCountry += "<div class='tally ml-2 text-center'><input type='checkbox' value='"+val+"' id='"+val+"'> <label for='"+val+"'>"+val+"</label></div>";
            dataForCountry += "</select>";
            // if($('#'+parentDivId).find('.symbol-cell').length > 0){
            //   $(this).find('td:nth-last-child(3)').before("<td class='"+trid+"'>"+dataForCountry+"</td>");
            // }else{
            $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "'>" + dataForCountry + "</td>");
            //}
          } else if (data.toLowerCase() == 'color wise qty') {
            var colorQty = '';
            colorQty += '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
            colorQty += '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
            colorQty += '<div class="row no-gap">';
            $('.addrowdisabler').val(presetDataVar.color.length);
            $.each(presetDataVar.color, function (indexx, val) {
              colorQty += '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 colornameDiv' + indexx + '"><input type="checkbox" id="colorselect-' + indexx + '-' + gridsl + '-' + id + '" name="colorname' + indexx + '" class="colorwiseqty colorname' + indexx + '" value="' + val + '" data-qty="' + presetDataVar.qty[indexx] + '" onchange="colorWiseQty($(this), \'' + indexx + '\', \'' + parentDivId + '\')" style="cursor:pointer;"><label for="colorselect-' + indexx + '-' + gridsl + '-' + id + '" style="cursor:pointer;">' + val + '</label></div>';
            });
            colorQty += '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
            colorQty += ' <div class="cell">';
            colorQty += '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
            colorQty += '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
            colorQty += '<span class="caption">Continue</span>';
            colorQty += '</a></div></div></div></div>';
            // if($('#'+parentDivId).find('.symbol-cell').length > 0){
            //   $(this).find('td:nth-last-child(3)').before("<td class='"+trid+" color-celll-manual' style='position:relative;'>"+colorQty+"<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \""+parentDivId+"\");'><span class='icon mif-yelp'></span></span></td><td class='"+trid+"-qty'><input type='hidden' class='addition-qty-hidden' value='0'><input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\""+parentDivId+"\", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0'></td>");
            // }else{
            $(this).find('td:nth-last-child(2)').before("<td class='" + trid + " color-celll-manual' style='position:relative;'>" + colorQty + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td><td class='" + trid + "-qty'><input type='hidden' class='addition-qty-hidden' value='0'><input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\"" + parentDivId + "\", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0'></td>");
            //}
          } else if (data.toLowerCase() == 'color & size wise qty') {
            var colorSizeQty = '';
            colorSizeQty += '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
            colorSizeQty += '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
            colorSizeQty += '<div class="row no-gap">';
            $('.addrowdisabler').val(Object.keys(presetDataVar.colorsizeQty).length);
            var counter = 0;
            $.each(presetDataVar.colorsizeQty, function (indexx) {
              var count = counter++;
              colorSizeQty += '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 colorsnameDiv' + count + '"><input type="checkbox" id="colorsselect-' + count + '-' + gridsl + '-' + id + '" name="colorsname' + count + '" class="colorswiseqty colorsname' + count + '" value="' + indexx + '" onchange="colorsWiseQty($(this), \'' + count + '\', \'' + parentDivId + '\')" style="cursor:pointer;"><label for="colorsselect-' + count + '-' + gridsl + '-' + id + '" style="cursor:pointer;">' + indexx + '</label></div>';
            });
            colorSizeQty += '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
            colorSizeQty += ' <div class="cell">';
            colorSizeQty += '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
            colorSizeQty += '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
            colorSizeQty += '<span class="caption">Continue</span>';
            colorSizeQty += '</a></div></div></div></div>';
            var csize = '<div class="row no-gap">';
            $.each(presetDataVar.sizename, function (index, val) {
              csize += '<div class="cell size-' + val.replace(/[_\W]+/g, "-") + ' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="csize-input input-small text-center size-' + val.replace(/[_\W]+/g, "-") + '-input colorsizewiseqtyinput" data-sizename="' + val + '" readonly value="0" style="width: 100%;" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty(\'' + parentDivId + '\', $(this));"><input type="hidden" class="csaddition-qty-hidden additionsize-' + val.replace(/[_\W]+/g, "-") + '-input" value="0"></div>';
            });
            csize += '</div>';
            // if($('#'+parentDivId).find('.symbol-cell').length > 0){
            //   $(this).find('td:nth-last-child(3)').before("<td class='"+trid+" color-celll-manual' style='position:relative;'>"+colorSizeQty+"<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \""+parentDivId+"\");'><span class='icon mif-yelp'></span></span></td><td class='"+trid+" colorsizeqty'>"+csize+"</td>");
            // }else{
            $(this).find('td:nth-last-child(2)').before("<td class='" + trid + " color-celll-manual' style='position:relative;'>" + colorSizeQty + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td><td class='" + trid + " colorsizeqty'>" + csize + "</td>");
            // }
          } else if (data.toLowerCase() == 'kimball/color/size wise qty') {
            var kColorSizeQty = '';
            kColorSizeQty += '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
            kColorSizeQty += '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Select Data</p>';
            kColorSizeQty += '<div class="row no-gap">';
            $('.addrowdisabler').val(Object.keys(presetDataVar.colorsizeQty).length);
            var counter = 0;
            $.each(presetDataVar.colorsizeQty, function (indexx) {
              var count = counter++;
              kColorSizeQty += '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 kcolorsnameDiv' + count + '"><input type="checkbox" id="kcolorsselect-' + count + '-' + gridsl + '-' + id + '" name="kcolorsname' + count + '" class="kcolorswiseqty kcolorsname' + count + '" data-kimball="' + presetDataVar.kimball[count] + '" data-lot="' + presetDataVar.lot[count] + '" value="' + indexx + '" onchange="kcolorsWiseQty($(this), \'' + count + '\', \'' + parentDivId + '\')" style="cursor:pointer;"><label for="kcolorsselect-' + count + '-' + gridsl + '-' + id + '" style="cursor:pointer;">' + indexx.replace('*lot*' + presetDataVar.lot[count] + '*lot*' + presetDataVar.kimball[count], '') + ' / ' + presetDataVar.kimball[count] + ' / ' + presetDataVar.lot[count] + '</label></div>';
            });
            kColorSizeQty += '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
            kColorSizeQty += ' <div class="cell">';
            kColorSizeQty += '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
            kColorSizeQty += '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
            kColorSizeQty += '<span class="caption">Continue</span>';
            kColorSizeQty += '</a></div></div></div></div>';
            var ksize = '<div class="row no-gap">';
            $.each(presetDataVar.sizename, function (index, val) {
              ksize += '<div class="cell size-' + val.replace(/[_\W]+/g, "-") + ' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="csize-input input-small text-center size-' + val.replace(/[_\W]+/g, "-") + '-input colorsizewiseqtyinput" data-sizename="' + val + '" readonly value="0" style="width: 100%;" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty(\'' + parentDivId + '\', $(this));"><input type="hidden" class="csaddition-qty-hidden additionsize-' + val.replace(/[_\W]+/g, "-") + '-input" value="0"></div>';
            });
            ksize += '</div>';
            // if($('#'+parentDivId).find('.symbol-cell').length > 0){
            //   $(this).find('td:nth-last-child(3)').before("<td class='"+trid+" color-celll-manual' style='position:relative;'>"+kColorSizeQty+"<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \""+parentDivId+"\");'><span class='icon mif-yelp'></span></span></td><td class='"+trid+" text-center kimball-cell kimballcelll'></td><td class='"+trid+" text-center lot-cell'></td><td class='"+trid+" colorsizeqty'>"+ksize+"</td>");
            // }else{
            $(this).find('td:nth-last-child(2)').before("<td class='" + trid + " color-celll-manual' style='position:relative;'>" + kColorSizeQty + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td><td class='" + trid + " text-center kimball-cell kimballcelll'></td><td class='" + trid + " text-center lot-cell'></td><td class='" + trid + " colorsizeqty'>" + ksize + "</td>");
            //}
          } else if (data.toLowerCase() == 'garments qty (size)') {
            var ksize = '<div class="row no-gap">';
            var getSizeName = $('.allsize').val().split(',');
            $.each(getSizeName, function (index, val) {
              ksize += '<div class="cell size-' + val.replace(/[_\W]+/g, "-") + ' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="csize-input input-small text-center size-' + val.replace(/[_\W]+/g, "-") + '-input colorsizewiseqtyinput" data-sizename="' + val + '" value="0" style="width: 100%;" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty(\'' + parentDivId + '\', $(this));"><input type="hidden" class="csaddition-qty-hidden additionsize-' + val.replace(/[_\W]+/g, "-") + '-input" value="0"></div>';
            });
            ksize += '</div>';

            $(this).find('td:nth-last-child(2)').before("<td class='" + trid + " colorsizeqty'>" + ksize + "</td>");
          } else if (data.toLowerCase() == 'kimball & color wise qty') {
            var kColorQty = '';
            kColorQty += '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
            kColorQty += '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Select Data</p>';
            kColorQty += '<div class="row no-gap">';
            $('.addrowdisabler').val(presetDataVar.color.length);
            $.each(presetDataVar.color, function (indexx, val) {
              kColorQty += '<div class="mt-1 mb-1 p-1 border bd-light cell-3 kcolornameDiv' + indexx + '"><input type="checkbox" id="kcolorselect-' + indexx + '-' + gridsl + '-' + id + '" name="kcolorname' + indexx + '" class="kcolorwiseqty kcolorname' + indexx + '" value="' + val + '" data-qty="' + presetDataVar.qty[indexx] + '" data-kimball="' + presetDataVar.kimball[indexx] + '" data-lot="' + presetDataVar.lot[indexx] + '" onchange="kimballColorWiseQty($(this), \'' + indexx + '\', \'' + parentDivId + '\')" style="cursor:pointer;"><label for="kcolorselect-' + indexx + '-' + gridsl + '-' + id + '" style="cursor:pointer;">' + val + ' / ' + presetDataVar.kimball[indexx] + ' / ' + presetDataVar.lot[indexx] + '</label></div>';
            });
            kColorQty += '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
            kColorQty += ' <div class="cell">';
            kColorQty += '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
            kColorQty += '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
            kColorQty += '<span class="caption">Continue</span>';
            kColorQty += '</a></div></div></div></div>';
            // if($('#'+parentDivId).find('.symbol-cell').length > 0){
            //   $(this).find('td:nth-last-child(3)').before("<td class='"+trid+" color-celll-manual' style='position:relative;'>"+kColorQty+"<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \""+parentDivId+"\");'><span class='icon mif-yelp'></span></span></td><td class='"+trid+" text-center kimball-cell kimballcelll'></td><td class='"+trid+" text-center lot-cell'></td><td class='"+trid+"-qty'><input type='hidden' class='addition-qty-hidden' value='0'><input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\""+parentDivId+"\", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0'></td>");
            // }else{
            $(this).find('td:nth-last-child(2)').before("<td class='" + trid + " color-celll-manual' style='position:relative;'>" + kColorQty + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td><td class='" + trid + " text-center kimball-cell kimballcelll'></td><td class='" + trid + " text-center lot-cell'></td><td class='" + trid + "-qty'><input type='hidden' class='addition-qty-hidden' value='0'><input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\"" + parentDivId + "\", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0'></td>");
            // }
          } else if (data.toLowerCase() == 'grmnts color/kimball/lot') {
            var kcolorG = '';
            kcolorG += '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
            kcolorG += '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
            kcolorG += '<div class="row no-gap">';
            $.each(presetDataVar.color, function (indexx, val) {
              kcolorG += '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6"><input type="checkbox" id="kcolorselectg-' + indexx + '-' + gridsl + '-' + id + '" name="kcolornameg' + indexx + '" class="kcolornameg" value="' + val + '" data-kimball="' + presetDataVar.kimball[indexx] + '" data-lot="' + presetDataVar.lot[indexx] + '" onchange="kimballColorWiseQtyGeneral($(this), \'' + parentDivId + '\')" style="cursor:pointer;"><label for="kcolorselectg-' + indexx + '-' + gridsl + '-' + id + '" style="cursor:pointer;">' + val + ' / ' + presetDataVar.kimball[indexx] + ' / ' + presetDataVar.lot[indexx] + '</label></div>';
            });
            kcolorG += '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
            kcolorG += ' <div class="cell">';
            kcolorG += '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
            kcolorG += '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
            kcolorG += '<span class="caption">Continue</span>';
            kcolorG += '</a></div></div></div></div>';
            // if($('#'+parentDivId).find('.symbol-cell').length > 0){
            //   $(this).find('td:nth-last-child(3)').before("<td class='"+trid+" color-celll-manual' style='position:relative;'>"+kcolorG+"<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \""+parentDivId+"\");'><span class='icon mif-yelp'></span></span></td><td class='"+trid+" text-center kimball-cell kimballcelll'></td><td class='"+trid+" text-center lot-cell'></td>");
            // }else{
            $(this).find('td:nth-last-child(2)').before("<td class='" + trid + " color-celll-manual' style='position:relative;'>" + kcolorG + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td><td class='" + trid + " text-center kimball-cell kimballcelll'></td><td class='" + trid + " text-center lot-cell'></td>");
            // }
          } else if (data.toLowerCase() == 'garments qty') {
            // if($('#'+parentDivId).find('.symbol-cell').length > 0){
            //   $(this).find('td:nth-last-child(3)').before("<td class='"+trid+"-qty'><input type='hidden' class='addition-qty-hidden' value='0'><input type='text' class='input-small text-center garments-qty-input' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\""+parentDivId+"\", $(this));' name='garmentsqty' style='max-width:120px; margin: 0 auto;' value='0'></td>");
            // }else{
            $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "-qty'><input type='hidden' class='addition-qty-hidden' value='0'><input type='text' class='input-small text-center garments-qty-input' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\"" + parentDivId + "\", $(this));' name='garmentsqty' style='max-width:120px; margin: 0 auto;' value='0'></td>");
            // }
          } else if (data.toLowerCase() == 'addition') {
            // if($('#'+parentDivId).find('.symbol-cell').length > 0){
            //   $(this).find('td:nth-last-child(3)').before("<td class='"+trid+"' style='position:relative;'><input type='text' readonly class='input-small text-center row-garmentsqtyextra-input' name='garmentsextraqty' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualExtraGarmentsQty(\""+parentDivId+"\", $(this));' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></td>");
            // }else{
            $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "' style='position:relative;'><input type='text' readonly class='input-small text-center row-garmentsqtyextra-input' name='garmentsextraqty' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualExtraGarmentsQty(\"" + parentDivId + "\", $(this));' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></td>");
            // } 
          } else if (data.toLowerCase() == 'converter') {
            var dataCopyBtn = "<a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopyWithCalculation($(this), \"" + trid + "\", \"" + parentDivId + "\")'><span class='mif-copy'></span></a>";
            // if($('#'+parentDivId).find('.symbol-cell').length > 0){
            //   $(this).find('td:nth-last-child(3)').before("<td class='"+trid+"' style='position:relative;'><input type='text'  class='data-copier input-small text-center row-convertion-input' name='convertioninput' oninput='numberValidate($(this), $(this).val()); convertionCalculate(\""+parentDivId+"\", $(this));' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='1'>"+dataCopyBtn+"</td>");
            // }else{
            $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "' style='position:relative;'><input type='text'  class='data-copier input-small text-center row-convertion-input' name='convertioninput' oninput='numberValidate($(this), $(this).val()); convertionCalculate(\"" + parentDivId + "\", $(this));' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='1'>" + dataCopyBtn + "</td>");
            // } 
          } else if (data.toLowerCase() == 'size wise qty') {
            var sizeQty = '';
            sizeQty += '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
            sizeQty += '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
            sizeQty += '<div class="row no-gap">';
            $('.addrowdisabler').val(Object.keys(presetDataVar.sizeQty).length);
            var counter = 0;
            $.each(presetDataVar.sizeQty, function (indexx, val) {
              var count = counter++;
              sizeQty += '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 sizenameDiv' + count + '"><input type="checkbox" id="sizeselect-' + count + '-' + gridsl + '-' + id + '" name="sizename' + count + '" class="sizewiseqty sizename' + count + '" value="' + indexx + '" data-qty="' + val + '" onchange="sizeWiseQty($(this), \'' + count + '\', \'' + parentDivId + '\')" style="cursor:pointer;"><label for="sizeselect-' + count + '-' + gridsl + '-' + id + '" style="cursor:pointer;">' + indexx + '</label></div>';
            });
            sizeQty += '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
            sizeQty += ' <div class="cell">';
            sizeQty += '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
            sizeQty += '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
            sizeQty += '<span class="caption">Continue</span>';
            sizeQty += '</a></div></div></div></div>';
            // if($('#'+parentDivId).find('.symbol-cell').length > 0){
            //   $(this).find('td:nth-last-child(3)').before("<td class='"+trid+" size-celll-manual' style='position:relative;'>"+sizeQty+"<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \""+parentDivId+"\");'><span class='icon mif-yelp'></span></span></td><td class='"+trid+"-qty'><input type='hidden' class='addition-qty-hidden' value='0'><input type='text' class='input-small text-center garments-qty-input' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0'></td>");
            // }else{
            $(this).find('td:nth-last-child(2)').before("<td class='" + trid + " size-celll-manual' style='position:relative;'>" + sizeQty + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td><td class='" + trid + "-qty'><input type='hidden' class='addition-qty-hidden' value='0'><input type='text' class='input-small text-center garments-qty-input' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\"" + parentDivId + "\", $(this));'></td>");
            // }
          } else if (data.toLowerCase() == 'size name') {
            var sizeG = '';
            sizeG += '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
            sizeG += '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Size Name</p>';
            sizeG += '<div class="row no-gap">';
            var getSizeName = $('.allsize').val().split(',');
            $.each(getSizeName, function (indexx, val) {
              sizeG += '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6"><input type="checkbox" id="sizeselectg-' + indexx + '-' + gridsl + '-' + id + '" name="sizenameg' + indexx + '" class="sizenameg" value="' + val + '" onchange="sizeWiseQtyGeneral($(this), \'' + parentDivId + '\')" style="cursor:pointer;"><label for="sizeselectg-' + indexx + '-' + gridsl + '-' + id + '" style="cursor:pointer;">' + val + '</div>';
            });
            sizeG += '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
            sizeG += ' <div class="cell">';
            sizeG += '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
            sizeG += '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
            sizeG += '<span class="caption">Continue</span>';
            sizeG += '</a></div></div></div></div>';
            // if($('#'+parentDivId).find('.symbol-cell').length > 0){
            //   $(this).find('td:nth-last-child(3)').before("<td class='"+trid+" size-celll-manual' style='position:relative;'>"+sizeG+"<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \""+parentDivId+"\");'><span class='icon mif-yelp'></span></span></td>");
            // }else{
            $(this).find('td:nth-last-child(2)').before("<td class='" + trid + " size-celll-manual' style='position:relative;'>" + sizeG + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td>");
            // }
          } else if (data.toLowerCase() == 'grmnts color') {
            var colorG = '';
            colorG += '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
            colorG += '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
            colorG += '<div class="row no-gap">';
            $.each(presetDataVar.color, function (indexx, val) {
              colorG += '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6"><input type="checkbox" id="colorselectg-' + indexx + '-' + gridsl + '-' + id + '" name="colornameg' + indexx + '" class="colornameg" value="' + val + '" onchange="colorWiseQtyGeneral($(this), \'' + parentDivId + '\')" style="cursor:pointer;"><label for="colorselectg-' + indexx + '-' + gridsl + '-' + id + '" style="cursor:pointer;">' + val + '</div>';
            });
            colorG += '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
            colorG += ' <div class="cell">';
            colorG += '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
            colorG += '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
            colorG += '<span class="caption">Continue</span>';
            colorG += '</a></div></div></div></div>';
            // if($('#'+parentDivId).find('.symbol-cell').length > 0){
            //   $(this).find('td:nth-last-child(3)').before("<td class='"+trid+" color-celll-manual' style='position:relative;'>"+colorG+"<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \""+parentDivId+"\");'><span class='icon mif-yelp'></span></span></td>");
            // }else{
            $(this).find('td:nth-last-child(2)').before("<td class='" + trid + " color-celll-manual' style='position:relative;'>" + colorG + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td>");
            // }
          } else if (data.toLowerCase() == 'pn no.') {
            //Silence is better than being right. Dont remove this condition.
          } else if (data.toLowerCase() == 'order no.') {
            //Silence is better than being right. Dont remove this condition.         
          } else if (data.toLowerCase() == 'symbol') {
            //Silence is better than being right. Dont remove this condition.         
          } else if (data.toLowerCase() == 'code no.') {
            var dataCopyBtn = "<a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"" + trid + "\", \"" + parentDivId + "\")'><span class='mif-copy'></span></a>";
            //if($('#'+parentDivId).find('.code-no-cell').length == 0 && index == 2){
            // if($('#'+parentDivId).find('.symbol-cell').length > 0){
            //   $(this).find('td:nth-last-child(3)').before("<td class='"+trid+"' style='position:relative;'><textarea class='code-no-input custom-column-value data-copier' name='codenumber' style='max-width:100%; height: 40px; margin: 0 auto;'></textarea>"+dataCopyBtn+"</td>");
            // }else{
            $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "' style='position:relative;'><textarea class='code-no-input custom-column-value data-copier' name='codenumber' style='max-width:100%; height: 40px; margin: 0 auto;'></textarea>" + dataCopyBtn + "</td>");
            // }
          } else {
            // Modified By SPN Start
            var dataCopyBtn = "<a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"" + trid + "\", \"" + parentDivId + "\")'><span class='mif-copy'></span></a>";
            // if($('#'+parentDivId).find('.symbol-cell').length > 0){
            //   $(this).find('td:nth-last-child(3)').before("<td class='"+trid+"' style='position:relative;'><textarea class='custom-column-value data-copier'  style='max-width:100%; margin: 0 auto; height: 40px;'></textarea>"+dataCopyBtn+"</td>");
            // }else{
            $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "' style='position:relative;'><textarea class='custom-column-value data-copier' style='max-width:100%; margin: 0 auto; height: 40px;'></textarea>" + dataCopyBtn + "</td>");
            // }
            // Modified By SPN End
          }
        } else if (index > 1 && index < tableRowsCount - 1) { //Table ColumnsData
             
          if (data.toLowerCase() == 'country') {
            var dataForCountry = '';
            var countryName = unique($('.allcountry').val().split(','));
            //console.log(countryName);
            dataForCountry += "<select multiple style='max-width:110px; min-height:100px; margin: 0 auto;' class='custom-column-value'>";
            $.each(countryName, function (indext, val) {
              if (val != '') {
                //alert(val);
                dataForCountry += "<option value='" + val + "'>" + val + "</option>";
              }
            });
            //dataForCountry += "<div class='tally ml-2 text-center'><input type='checkbox' value='"+val+"' id='"+val+"'> <label for='"+val+"'>"+val+"</label></div>";
            dataForCountry += "</select>";
            if ($('#' + parentDivId).find('.symbol-cell').length > 0 && index == 2) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + "'>" + dataForCountry + "</td>");
            } else {
              $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "'>" + dataForCountry + "</td>");
            }
          } else if (data.toLowerCase() == 'color wise qty') {
            var colorQty = '';
            colorQty += '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
            colorQty += '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
            colorQty += '<div class="row no-gap">';
            $('.addrowdisabler').val(presetDataVar.color.length);
            $.each(presetDataVar.color, function (indexx, val) {
              colorQty += '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 colornameDiv' + indexx + '"><input type="checkbox" id="colorselect-' + indexx + '-' + gridsl + '-' + id + '" name="colorname' + indexx + '" class="colorwiseqty colorname' + indexx + '" value="' + val + '" data-qty="' + presetDataVar.qty[indexx] + '" onchange="colorWiseQty($(this), \'' + indexx + '\', \'' + parentDivId + '\')" style="cursor:pointer;"><label for="colorselect-' + indexx + '-' + gridsl + '-' + id + '" style="cursor:pointer;">' + val + '</label></div>';
            });
            colorQty += '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
            colorQty += ' <div class="cell">';
            colorQty += '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
            colorQty += '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
            colorQty += '<span class="caption">Continue</span>';
            colorQty += '</a></div></div></div></div>';
            if ($('#' + parentDivId).find('.symbol-cell').length > 0 && index == 2) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + " color-celll-manual' style='position:relative;'>" + colorQty + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td><td class='" + trid + "-qty'><input type='hidden' class='addition-qty-hidden' value='0'><input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\"" + parentDivId + "\", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0'></td>");
            } else {
              $(this).find('td:nth-last-child(2)').before("<td class='" + trid + " color-celll-manual' style='position:relative;'>" + colorQty + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td><td class='" + trid + "-qty'><input type='hidden' class='addition-qty-hidden' value='0'><input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\"" + parentDivId + "\", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0'></td>");
            }
          } else if (data.toLowerCase() == 'kimball & color wise qty') {
            var kColorQty = '';
            kColorQty += '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
            kColorQty += '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
            kColorQty += '<div class="row no-gap">';
            $('.addrowdisabler').val(presetDataVar.color.length);
            $.each(presetDataVar.color, function (indexx, val) {
              kColorQty += '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 kcolornameDiv' + indexx + '"><input type="checkbox" id="kcolorselect-' + indexx + '-' + gridsl + '-' + id + '" name="kcolorname' + indexx + '" class="kcolorwiseqty kcolorname' + indexx + '" value="' + val + '" data-qty="' + presetDataVar.qty[indexx] + '" data-kimball="' + presetDataVar.kimball[indexx] + '" data-lot="' + presetDataVar.lot[indexx] + '" onchange="kimballColorWiseQty($(this), \'' + indexx + '\', \'' + parentDivId + '\')" style="cursor:pointer;"><label for="kcolorselect-' + indexx + '-' + gridsl + '-' + id + '" style="cursor:pointer;">' + val + ' / ' + presetDataVar.kimball[indexx] + ' / ' + presetDataVar.lot[indexx] + '</label></div>';
            });
            kColorQty += '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
            kColorQty += ' <div class="cell">';
            kColorQty += '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
            kColorQty += '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
            kColorQty += '<span class="caption">Continue</span>';
            kColorQty += '</a></div></div></div></div>';
            if ($('#' + parentDivId).find('.symbol-cell').length > 0 && index == 2) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + " color-celll-manual' style='position:relative;'>" + kColorQty + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td><td class='" + trid + " text-center kimball-cell kimballcelll'></td><td class='" + trid + " text-center lot-cell'></td><td class='" + trid + "-qty'><input type='hidden' class='addition-qty-hidden' value='0'><input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\"" + parentDivId + "\", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0'></td>");
            } else {
              $(this).find('td:nth-last-child(2)').before("<td class='" + trid + " color-celll-manual' style='position:relative;'>" + kColorQty + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td><td class='" + trid + " text-center kimball-cell kimballcelll'></td><td class='" + trid + " text-center lot-cell'></td><td class='" + trid + "-qty'><input type='hidden' class='addition-qty-hidden' value='0'><input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\"" + parentDivId + "\", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0'></td>");
            }
          } else if (data.toLowerCase() == 'color & size wise qty') {
            var colorSizeQty = '';
            colorSizeQty += '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
            colorSizeQty += '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
            colorSizeQty += '<div class="row no-gap">';
            $('.addrowdisabler').val(Object.keys(presetDataVar.colorsizeQty).length);
            var counter = 0;
            $.each(presetDataVar.colorsizeQty, function (indexx) {
              var count = counter++;
              colorSizeQty += '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 colorsnameDiv' + count + '"><input type="checkbox" id="colorsselect-' + count + '-' + gridsl + '-' + id + '" name="colorsname' + count + '" class="colorswiseqty colorsname' + count + '" value="' + indexx + '" onchange="colorsWiseQty($(this), \'' + count + '\', \'' + parentDivId + '\')" style="cursor:pointer;"><label for="colorsselect-' + count + '-' + gridsl + '-' + id + '" style="cursor:pointer;">' + indexx + '</label></div>';
            });
            colorSizeQty += '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
            colorSizeQty += ' <div class="cell">';
            colorSizeQty += '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
            colorSizeQty += '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
            colorSizeQty += '<span class="caption">Continue</span>';
            colorSizeQty += '</a></div></div></div></div>';
            var csize = '<div class="row no-gap">';
            $.each(presetDataVar.sizename, function (index, val) {
              csize += '<div class="cell size-' + val.replace(/[_\W]+/g, "-") + ' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="input-small csize-input text-center size-' + val.replace(/[_\W]+/g, "-") + '-input colorsizewiseqtyinput" data-sizename="' + val + '" readonly value="0" style="width: 100%;" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty(\'' + parentDivId + '\', $(this));"><input type="hidden" class="csaddition-qty-hidden additionsize-' + val.replace(/[_\W]+/g, "-") + '-input" value="0"></div>';
            });
            csize += '</div>';
            if ($('#' + parentDivId).find('.symbol-cell').length > 0 && index == 2) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + " color-celll-manual' style='position:relative;'>" + colorSizeQty + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td><td class='" + trid + " colorsizeqty'>" + csize + " <input type='hidden' class='addition-qty-hidden' value='0'></td>");
            } else {
              $(this).find('td:nth-last-child(2)').before("<td class='" + trid + " color-celll-manual' style='position:relative;'>" + colorSizeQty + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td><td class='" + trid + " colorsizeqty'>" + csize + " <input type='hidden' class='addition-qty-hidden' value='0'></td>");
            }
          } else if (data.toLowerCase() == 'garments qty (size)') {
            var ksize = '<div class="row no-gap">';
            var getSizeName = $('.allsize').val().split(',');
            $.each(getSizeName, function (index, val) {
              ksize += '<div class="cell size-' + val.replace(/[_\W]+/g, "-") + ' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="csize-input input-small text-center size-' + val.replace(/[_\W]+/g, "-") + '-input colorsizewiseqtyinput" data-sizename="' + val + '" value="0" style="width: 100%;" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty(\'' + parentDivId + '\', $(this));"><input type="hidden" class="csaddition-qty-hidden additionsize-' + val.replace(/[_\W]+/g, "-") + '-input" value="0"></div>';
            });
            ksize += '</div>';
            if ($('#' + parentDivId).find('.symbol-cell').length > 0 && index == 2) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + " colorsizeqty'>" + ksize + "</td>");
            } else {
              $(this).find('td:nth-last-child(2)').before("<td class='" + trid + " colorsizeqty'>" + ksize + "</td>");
            }
          } else if (data.toLowerCase() == 'kimball/color/size wise qty') {
            var kColorSizeQty = '';
            kColorSizeQty += '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
            kColorSizeQty += '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Select Data</p>';
            kColorSizeQty += '<div class="row no-gap">';
            $('.addrowdisabler').val(Object.keys(presetDataVar.colorsizeQty).length);
            var counter = 0;
            $.each(presetDataVar.colorsizeQty, function (indexx) {
              var count = counter++;
              kColorSizeQty += '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 kcolorsnameDiv' + count + '"><input type="checkbox" id="kcolorsselect-' + count + '-' + gridsl + '-' + id + '" name="kcolorsname' + count + '" class="kcolorswiseqty kcolorsname' + count + '" data-kimball="' + presetDataVar.kimball[count] + '" data-lot="' + presetDataVar.lot[count] + '" value="' + indexx + '" onchange="kcolorsWiseQty($(this), \'' + count + '\', \'' + parentDivId + '\')" style="cursor:pointer;"><label for="kcolorsselect-' + count + '-' + gridsl + '-' + id + '" style="cursor:pointer;">' + indexx.replace('*lot*' + presetDataVar.lot[count] + '*lot*' + presetDataVar.kimball[count], '') + ' / ' + presetDataVar.kimball[count] + ' / ' + presetDataVar.lot[count] + '</label></div>';
            });
            kColorSizeQty += '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
            kColorSizeQty += ' <div class="cell">';
            kColorSizeQty += '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
            kColorSizeQty += '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
            kColorSizeQty += '<span class="caption">Continue</span>';
            kColorSizeQty += '</a></div></div></div></div>';
            var ksize = '<div class="row no-gap">';
            $.each(presetDataVar.sizename, function (index, val) {
              ksize += '<div class="cell size-' + val.replace(/[_\W]+/g, "-") + ' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="csize-input input-small text-center size-' + val.replace(/[_\W]+/g, "-") + '-input colorsizewiseqtyinput" data-sizename="' + val + '" readonly value="0" style="width: 100%;" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty(\'' + parentDivId + '\', $(this));"><input type="hidden" class="csaddition-qty-hidden additionsize-' + val.replace(/[_\W]+/g, "-") + '-input" value="0"></div>';
            });
            ksize += '</div>';
            if ($('#' + parentDivId).find('.symbol-cell').length > 0 && index == 2) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + " color-celll-manual' style='position:relative;'>" + kColorSizeQty + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td><td class='" + trid + " text-center kimball-cell kimballcelll'></td><td class='" + trid + " text-center lot-cell'></td><td class='" + trid + " colorsizeqty'>" + ksize + "</td>");
            } else {
              $(this).find('td:nth-last-child(2)').before("<td class='" + trid + " color-celll-manual' style='position:relative;'>" + kColorSizeQty + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td><td class='" + trid + " text-center kimball-cell kimballcelll'></td><td class='" + trid + " text-center lot-cell'></td><td class='" + trid + " colorsizeqty'>" + ksize + "</td>");
            }
          } else if (data.toLowerCase() == 'grmnts color') {
            var colorG = '';
            colorG += '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
            colorG += '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
            colorG += '<div class="row no-gap">';
            $.each(presetDataVar.color, function (indexx, val) {
              colorG += '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6"><input type="checkbox" id="colorselectg-' + indexx + '-' + gridsl + '-' + id + '" name="colornameg' + indexx + '" class="colornameg" value="' + val + '" onchange="colorWiseQtyGeneral($(this), \'' + parentDivId + '\')" style="cursor:pointer;"><label for="colorselectg-' + indexx + '-' + gridsl + '-' + id + '" style="cursor:pointer;">' + val + '</div>';
            });
            colorG += '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
            colorG += ' <div class="cell">';
            colorG += '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
            colorG += '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
            colorG += '<span class="caption">Continue</span>';
            colorG += '</a></div></div></div></div>';
            if ($('#' + parentDivId).find('.symbol-cell').length > 0 && index == 2) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + " color-celll-manual' style='position:relative;'>" + colorG + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td>");
            } else {
              $(this).find('td:nth-last-child(2)').before("<td class='" + trid + " color-celll-manual' style='position:relative;'>" + colorG + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td>");
            }
          } else if (data.toLowerCase() == 'size name') {
            var sizeG = '';
            sizeG += '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
            sizeG += '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Size Name</p>';
            sizeG += '<div class="row no-gap">';
            var getSizeName = $('.allsize').val().split(',');
            $.each(getSizeName, function (indexx, val) {
              sizeG += '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6"><input type="checkbox" id="sizeselectg-' + indexx + '-' + gridsl + '-' + id + '" name="sizenameg' + indexx + '" class="sizenameg" value="' + val + '" onchange="sizeWiseQtyGeneral($(this), \'' + parentDivId + '\')" style="cursor:pointer;"><label for="sizeselectg-' + indexx + '-' + gridsl + '-' + id + '" style="cursor:pointer;">' + val + '</div>';
            });
            sizeG += '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
            sizeG += ' <div class="cell">';
            sizeG += '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
            sizeG += '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
            sizeG += '<span class="caption">Continue</span>';
            sizeG += '</a></div></div></div></div>';
            if ($('#' + parentDivId).find('.symbol-cell').length > 0 && index == 2) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + " size-celll-manual' style='position:relative;'>" + sizeG + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td>");
            } else {
              $(this).find('td:nth-last-child(2)').before("<td class='" + trid + " size-celll-manual' style='position:relative;'>" + sizeG + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td>");
            }
          } else if (data.toLowerCase() == 'grmnts color/kimball/lot') {
            var kcolorG = '';
            kcolorG += '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
            kcolorG += '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
            kcolorG += '<div class="row no-gap">';
            $.each(presetDataVar.color, function (indexx, val) {
              kcolorG += '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6"><input type="checkbox" id="kcolorselectg-' + indexx + '-' + gridsl + '-' + id + '" name="kcolornameg' + indexx + '" class="kcolornameg" value="' + val + '" data-kimball="' + presetDataVar.kimball[indexx] + '" data-lot="' + presetDataVar.lot[indexx] + '" onchange="kimballColorWiseQtyGeneral($(this), \'' + parentDivId + '\')" style="cursor:pointer;"><label for="kcolorselectg-' + indexx + '-' + gridsl + '-' + id + '" style="cursor:pointer;">' + val + ' / ' + presetDataVar.kimball[indexx] + ' / ' + presetDataVar.lot[indexx] + '</label></div>';
            });
            kcolorG += '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
            kcolorG += ' <div class="cell">';
            kcolorG += '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
            kcolorG += '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
            kcolorG += '<span class="caption">Continue</span>';
            kcolorG += '</a></div></div></div></div>';
            if ($('#' + parentDivId).find('.symbol-cell').length > 0 && index == 2) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + " color-celll-manual' style='position:relative;'>" + kcolorG + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td><td class='" + trid + " text-center kimball-cell kimballcelll'></td><td class='" + trid + " text-center lot-cell'></td>");
            } else {
              $(this).find('td:nth-last-child(2)').before("<td class='" + trid + " color-celll-manual' style='position:relative;'>" + kcolorG + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td><td class='" + trid + " text-center kimball-cell kimballcelll'></td><td class='" + trid + " text-center lot-cell'></td>");
            }
          } else if (data.toLowerCase() == 'size wise qty') {
            var sizeQty = '';
            sizeQty += '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
            sizeQty += '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
            sizeQty += '<div class="row no-gap">';
            $('.addrowdisabler').val(Object.keys(presetDataVar.sizeQty).length);
            var counter = 0;
            $.each(presetDataVar.sizeQty, function (indexx, val) {
              var count = counter++;
              sizeQty += '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 sizenameDiv' + count + '"><input type="checkbox" id="sizeselect-' + count + '-' + gridsl + '-' + id + '" name="sizename' + count + '" class="sizewiseqty sizename' + count + '" value="' + indexx + '" data-qty="' + val + '" onchange="sizeWiseQty($(this), \'' + count + '\', \'' + parentDivId + '\')" style="cursor:pointer;"><label for="sizeselect-' + count + '-' + gridsl + '-' + id + '" style="cursor:pointer;">' + indexx + '</label></div>';
            });
            sizeQty += '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
            sizeQty += ' <div class="cell">';
            sizeQty += '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
            sizeQty += '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
            sizeQty += '<span class="caption">Continue</span>';
            sizeQty += '</a></div></div></div></div>';
            if ($('#' + parentDivId).find('.symbol-cell').length > 0 && index == 2) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + " size-celll-manual' style='position:relative;'>" + sizeQty + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td><td class='" + trid + "-qty'><input type='hidden' class='addition-qty-hidden' value='0'><input type='text' class='input-small text-center garments-qty-input' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\"" + parentDivId + "\", $(this));'></td>");
            } else {
              $(this).find('td:nth-last-child(2)').before("<td class='" + trid + " size-celll-manual' style='position:relative;'>" + sizeQty + "<div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), \"" + parentDivId + "\");'><span class='icon mif-yelp'></span></span></td><td class='" + trid + "-qty'><input type='hidden' class='addition-qty-hidden' value='0'><input type='text' class='input-small text-center garments-qty-input' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\"" + parentDivId + "\", $(this));'></td>");
            }
          } else if (data.toLowerCase() == 'garments qty') {
            if ($('#' + parentDivId).find('.symbol-cell').length > 0 && index == 2) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + "-qty'><input type='hidden' class='addition-qty-hidden' value='0'><input type='text' class='input-small text-center garments-qty-input' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\"" + parentDivId + "\", $(this));' name='garmentsqty' style='max-width:120px; margin: 0 auto;' value='0'></td>");
            } else {
              // Modified By SPN Start
              if ($.trim($('#buyername').text()) == 'Peacocks' && itemName == 'Folded Card Board') {
                $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "-qty'><input type='hidden' class='addition-qty-hidden' value='0'><input type='text' class='input-small text-center garments-qty-input' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\"" + parentDivId + "\", $(this));' name='garmentsqty' style='max-width:120px; margin: 0 auto;' value='"+Math.round(qtySum + (qtySum * 0.01))+"'></td>");
                $('.' + parentDivId).find('.grand-totalqty-input').val(Math.round(qtySum + (qtySum * 0.01)));
                $('.' + parentDivId).find('.row-totalqty-input').val(Math.round(qtySum + (qtySum * 0.01)));
              }else{
              $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "-qty'><input type='hidden' class='addition-qty-hidden' value='0'><input type='text' class='input-small text-center garments-qty-input' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\"" + parentDivId + "\", $(this));' name='garmentsqty' style='max-width:120px; margin: 0 auto;' value='0'></td>");
              }     
              // Modified By SPN End

            }
          } else if (data.toLowerCase() == 'addition') {
            if ($('#' + parentDivId).find('.symbol-cell').length > 0 && index == 2) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + "' style='position:relative;'><input type='text' readonly class='input-small text-center row-garmentsqtyextra-input' name='garmentsextraqty' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualExtraGarmentsQty(\"" + parentDivId + "\", $(this));' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></td>");
            } else {
              $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "' style='position:relative;'><input type='text' readonly class='input-small text-center row-garmentsqtyextra-input' name='garmentsextraqty' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualExtraGarmentsQty(\"" + parentDivId + "\", $(this));' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></td>");
            }
          } else if (data.toLowerCase() == 'converter') {
            var dataCopyBtn = "<a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopyWithCalculation($(this), \"" + trid + "\", \"" + parentDivId + "\")'><span class='mif-copy'></span></a>";
            if ($('#' + parentDivId).find('.symbol-cell').length > 0 && index == 2) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + "' style='position:relative;'><input type='text'  class='data-copier input-small text-center row-convertion-input' name='convertioninput' oninput='numberValidate($(this), $(this).val()); convertionCalculate(\"" + parentDivId + "\", $(this));' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='1'>" + dataCopyBtn + "</td>");
            } else {
              $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "' style='position:relative;'><input type='text'  class='data-copier input-small text-center row-convertion-input' name='convertioninput' oninput='numberValidate($(this), $(this).val()); convertionCalculate(\"" + parentDivId + "\", $(this));' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='1'>" + dataCopyBtn + "</td>");
            }
          } else if (data.toLowerCase() == 'pn no.') {
            if ($('#' + parentDivId).find('.pn-no-cell').length == 0 && index == 2) {
              if ($('#' + parentDivId).find('.symbol-cell').length > 0 && index == 2) {
                $(this).find('td:nth-last-child(3)').before("<td class='pn-no-cell " + trid + "'><input type='text' class='input-small pn-no-input' name='pnnumber' style='max-width:120px; margin: 0 auto;'></td>");
              } else {
                $(this).find('td:nth-last-child(2)').before("<td class='pn-no-cell " + trid + "'><input type='text' class='input-small pn-no-input' name='pnnumber' style='max-width:120px; margin: 0 auto;'></td>");
              }
            }
          } else if (data.toLowerCase() == 'order no.') {
            if ($('#' + parentDivId).find('.order-no-cell').length == 0 && index == 2) {
              if ($('#' + parentDivId).find('.symbol-cell').length > 0 && index == 2) {
                $(this).find('td:nth-last-child(3)').before("<td class='order-no-cell " + trid + "'><input type='text' class='input-small order-no-input' name='ordernumber' style='max-width:120px; margin: 0 auto;' value='" + $('. input').val() + "'></td>");
              } else {
                $(this).find('td:nth-last-child(2)').before("<td class='order-no-cell " + trid + "'><input type='text' class='input-small order-no-input' name='ordernumber' style='max-width:120px; margin: 0 auto;' value='" + $('.search-ordernumber input').val() + "'></td>");
              }
            }
          } else if (data.toLowerCase() == 'code no.') {
            var dataCopyBtn = "<a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"" + trid + "\", \"" + parentDivId + "\")'><span class='mif-copy'></span></a>";
            //if($('#'+parentDivId).find('.code-no-cell').length == 0 && index == 2){
            if ($('#' + parentDivId).find('.symbol-cell').length > 0 && index == 2) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + "' style='position:relative;'><textarea class='code-no-input custom-column-value data-copier' name='codenumber' style='max-width:100%; height: 40px; margin: 0 auto;'></textarea>" + dataCopyBtn + "</td>");
            } else {
              $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "' style='position:relative;'><textarea class='code-no-input custom-column-value data-copier' name='codenumber' style='max-width:100%; height: 40px; margin: 0 auto;'></textarea>" + dataCopyBtn + "</td>");
            }
            //}
          } else if (data.toLowerCase() == 'symbol') {
            if ($('#' + parentDivId).find('.symbol-cell').length == 0 && index == 2) {
              $(this).find('td:nth-last-child(1)').after("<td class='symbol-cell " + trid + " text-center'><input type='file' name='attachment[]' multiple data-role='file' data-mode='drop' onchange='checkimage($(this), 4); checkvalidformat($(this));' class='symbol-input'><small>Allowed file format: jpg/png/jpeg/gif, Max allowed : 4 images.</small></td>");
            }
          } else {
            var dataCopyBtn = "<a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"" + trid + "\", \"" + parentDivId + "\")'><span class='mif-copy'></span></a>";
            if ($('#' + parentDivId).find('.symbol-cell').length > 0 && index == 2) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + "' style='position:relative;'><textarea class='custom-column-value data-copier' style='max-width:100%; margin: 0 auto; height: 40px;'></textarea>" + dataCopyBtn + "</td>");
            } else {
              // Modified By SPN Start
              if ($.trim($('#buyername').text()) == 'Peacocks' && itemName == 'Folded Card Board') {
                $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "' style='position:relative;'><textarea class='custom-column-value data-copier' style='max-width:100%; margin: 0 auto; height: 40px;'>"+dataVar['orderNo']+"</textarea>" + dataCopyBtn + "</td>");
              } else {
                $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "' style='position:relative;'><textarea class='custom-column-value data-copier' style='max-width:100%; margin: 0 auto; height: 40px;'></textarea>" + dataCopyBtn + "</td>");
              }
              console.log(dataVar);
              
              // Modified By SPN End
            }
          }
        } else if (index == tableRowsCount - 1) {
          if (data.toLowerCase() == 'color wise qty' || data.toLowerCase() == 'size wise qty') {
            if ($('#' + parentDivId).find('.symbol-cell').length > 0) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + "'></td><td class='" + trid + "-qty' style='position:relative;'><input type='text' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='0' readonly></td>");
            } else {
              $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "'></td><td class='" + trid + "-qty' style='position:relative;'><input type='text' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='0' readonly></td>");
            }
          } else if (data.toLowerCase() == 'grmnts color' || data.toLowerCase() == 'size name') {
            if ($('#' + parentDivId).find('.symbol-cell').length > 0) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + "'></td>");
            } else {
              $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "'></td>");
            }
          } else if (data.toLowerCase() == 'kimball & color wise qty') {
            if ($('#' + parentDivId).find('.symbol-cell').length > 0) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + "'></td><td class='" + trid + "'></td><td class='" + trid + " kimballcelll'></td><td class='" + trid + "-qty' style='position:relative;'><input type='text' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='0' readonly></td>");
            } else {
              $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "'></td><td class='" + trid + "'></td><td class='" + trid + " kimballcelll'></td><td class='" + trid + "-qty' style='position:relative;'><input type='text' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='0' readonly></td>");
            }
          } else if (data.toLowerCase() == 'grmnts color/kimball/lot') {
            if ($('#' + parentDivId).find('.symbol-cell').length > 0) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + "'></td><td class='" + trid + "'></td><td class='" + trid + "'></td>");
            } else {
              $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "'></td><td class='" + trid + "'></td><td class='" + trid + "'></td>");
            }
          } else if (data.toLowerCase() == 'addition') {
            if ($('#' + parentDivId).find('.symbol-cell').length > 0) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + "' style='position:relative;'><input type='text' class='input-small text-center garmentsextragrandtotal' name='garmentsextragrandtotal' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0' readonly></td>");
            } else {
              $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "' style='position:relative;'><input type='text' class='input-small text-center garmentsextragrandtotal' name='garmentsextragrandtotal' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0' readonly></td>");
            }
          } else if (data.toLowerCase() == 'garments qty') {
            if ($('#' + parentDivId).find('.symbol-cell').length > 0) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + "-qty' style='position:relative;'><input type='text' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='0' readonly></td>");
            } else {
              if ($.trim($('#buyername').text()) == 'Peacocks' && itemName == 'Folded Card Board') {
                $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "-qty' style='position:relative;'><input type='text' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='"+Math.round(qtySum + (qtySum * 0.01))+"' readonly></td>");
              }else{
                $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "-qty' style='position:relative;'><input type='text' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='0' readonly></td>");
              }
            }
          } else if (data.toLowerCase() == 'garments qty (size)') {
            if ($('#' + parentDivId).find('.symbol-cell').length > 0) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + "-qty' style='position:relative;'><input type='hidden' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='0' readonly></td>");
            } else {
              $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "-qty' style='position:relative;'><input type='hidden' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='0' readonly></td>");
            }
          } else if (data.toLowerCase() == 'symbol') {
            // if($('#'+parentDivId).find('.symbol-cell').length == 0){
            $(this).find('td:nth-last-child(1)').after("<td class='" + trid + " text-center'></td>");
            // }
          } else if (data.toLowerCase() == 'color & size wise qty') {
            if ($('#' + parentDivId).find('.symbol-cell').length > 0) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + "' style='position:relative;'></td><td class='" + trid + "' style='position:relative;'><input type='hidden' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='0'></td>");
            } else {
              $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "' style='position:relative;'></td><td class='" + trid + "' style='position:relative;'><input type='hidden' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='0'></td>");
            }
          } else if (data.toLowerCase() == 'kimball/color/size wise qty') {
            if ($('#' + parentDivId).find('.symbol-cell').length > 0) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + "' style='position:relative;'></td><td class='" + trid + "' style='position:relative;'></td><td class='" + trid + " kimballcelll' style='position:relative;'></td><td class='" + trid + "' style='position:relative;'><input type='hidden' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='0'></td>");
            } else {
              $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "' style='position:relative;'></td><td class='" + trid + "' style='position:relative;'></td><td class='" + trid + " kimballcelll' style='position:relative;'></td><td class='" + trid + "' style='position:relative;'><input type='hidden' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='0'></td>");
            }
          } else {
            if ($('#' + parentDivId).find('.symbol-cell').length > 0) {
              $(this).find('td:nth-last-child(3)').before("<td class='" + trid + "' style='position:relative;'></td>");
            } else {
              $(this).find('td:nth-last-child(2)').before("<td class='" + trid + "' style='position:relative;'></td>");
            }
          }
        }
      });
    } else {
      $("." + trid).remove();
      $("." + trid + "-header").remove();
      $("." + trid + "-qty-header").remove();
      $("." + trid + "-qty").remove();

      if (ownObj.parent('label').hasClass('parameters-disabled')) {
        $('#' + parentDivId).find('.parameters-disabled.parameters input').prop('disabled', false);
        $('#' + parentDivId).find('.parameters-disabled input').prop('disabled', false);
        $('#' + parentDivId).find('.general-parameters input').prop('checked', false);
      }
      if (ownObj.parent('label').hasClass('parameters-groupcommon')) {
        var commonGroupArr = [];
        $('#' + parentDivId).find('.parameters-groupcommon').each(function (index) {
          if ($(this).find('input').is(':checked')) {
            commonGroupArr.push('checked');
          }
        });
        if (commonGroupArr.length > 0) {
          $('#' + parentDivId).find('.parameters-disabled input').prop('disabled', true);
          if ($('#' + parentDivId).find('.grmnts-color input').is(':checked')) {
            $('#' + parentDivId).find('.grmnts-color input').prop('disabled', false);
            $('#' + parentDivId).find('.garments-qty input').prop('disabled', false);
            $('#' + parentDivId).find('.garments-qty-size- input').prop('disabled', false);
          } else if ($('#' + parentDivId).find('.grmnts-color-kimball-lot input').is(':checked')) {
            $('#' + parentDivId).find('.grmnts-color-kimball-lot input').prop('disabled', false);
            $('#' + parentDivId).find('.garments-qty input').prop('disabled', false);
            $('#' + parentDivId).find('.garments-qty-size- input').prop('disabled', false);
          } else if ($('#' + parentDivId).find('.size-name input').is(':checked')) {
            $('#' + parentDivId).find('.size-name input').prop('disabled', false);
            $('#' + parentDivId).find('.garments-qty input').prop('disabled', false);
            $('#' + parentDivId).find('.garments-qty-size- input').prop('disabled', false);
          } else {
            $('#' + parentDivId).find('.parameters-groupcommon input').prop('disabled', false);
          }
        } else {
          $('#' + parentDivId).find('.parameters-groupcommon input').prop('disabled', false);
        }
      }

      if (ownObj.val().toLowerCase() == 'addition') {
        $('.addition-popup').css('display', 'none');
        $('.' + parentDivId).find('.data-row .row-totalqty-input').each(function () {
          if ($('.' + parentDivId).find('.colorsizeqty-header').length == 0) {
            $(this).val($(this).closest('tr').find('.addition-qty-hidden').val());
          } else {
            var getValSum = 0;
            $(this).closest('tr').find('.csize-input').each(function (index, el) {
              $(this).val($(this).closest('div').find('.csaddition-qty-hidden').val());
              getValSum += parseInt($(this).closest('div').find('.csaddition-qty-hidden').val());
            });
            $(this).val(getValSum);
          }
          if ($('.converter' + id).length > 0) {
            convertionCalculate(parentDivId, $(this));
          }
        });
        rowSum(parentDivId);
      }
      if (ownObj.val().toLowerCase() == 'converter') {
        $('.converter-popup').css('display', 'none');
        $('.' + parentDivId).find('.row-totalqty-input').each(function () {
          if ($('.addition' + id).length > 0) {
            var val = $(this).closest('tr').find('.row-garmentsqtyextra-input').val();
          } else {
            if ($('.' + parentDivId).find('.colorsizeqty-header').length == 0) {
              var val = $(this).closest('tr').find('.addition-qty-hidden').val();
            } else {
              var getValSum = 0;
              $(this).closest('tr').find('.csize-input').each(function (index, el) {
                getValSum += parseInt($(this).closest('div').find('.csaddition-qty-hidden').val());
              });
              var val = getValSum;
            }
          }
          $(this).val(val);
        });
        rowSum(parentDivId);
      }
      if (ownObj.val().toLowerCase() == 'color wise qty' || ownObj.val().toLowerCase() == 'grmnts color' || ownObj.val().toLowerCase() == 'size name' || ownObj.val().toLowerCase() == 'grmnts color/kimball/lot' || ownObj.val().toLowerCase() == 'size wise qty' || ownObj.val().toLowerCase() == 'kimball & color wise qty' || ownObj.val().toLowerCase() == 'color & size wise qty' || ownObj.val().toLowerCase() == 'kimball/color/size wise qty' || ownObj.val().toLowerCase() == 'garments qty' || ownObj.val().toLowerCase() == 'garments qty (size)') {
        $('#' + parentDivId).find('.addition-enable input').prop({ 'disabled': true, 'checked': false });
        $('#' + parentDivId).find('.converter-enable input').prop({ 'disabled': true, 'checked': false });
        localStorage.removeItem("colorsizewisedata");
        localStorage.removeItem("kimballcolorsizewisedata");
        var itemName = $('.items-' + id).text();
        var dataTableCreate = "";
        dataTableCreate += "<tr>";
        dataTableCreate += "<th class='items-header'>Name of Item</th>";
        dataTableCreate += "<th class='totalqty-header'>W.O. Required Qty.</th>";
        dataTableCreate += "<th class='remarks-header'>Remarks</th>";
        dataTableCreate += "</tr>";
        dataTableCreate += "<tr class='data-row-hidden appended-row' style='display:none;'>";
        dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + parentDivId + "\")' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + $('.' + parentDivId).find('.grandunit').text() + "</span></div></div></td>";
        dataTableCreate += "<td class='remarks' style='position:relative; overflow:hidden;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + parentDivId + "\")'><span class='mif-copy'></span></a><div class='row-removeBtn ribbed-darkRed' onclick='rowRemover($(this), \"" + parentDivId + "\");'><span class='mif-cross'></span></div>";
        dataTableCreate += "</tr>";
        dataTableCreate += "<tr class='data-row'>";
        dataTableCreate += "<td class='text-center text-bold maingrid-rowspan items-name items-" + id + "' data-itemid='" + id + "' rowspan='1' data-itemname='"+itemName+"'>" + itemName + "</td>";
        dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + parentDivId + "\")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + $('.' + parentDivId).find('.grandunit').text() + "</span></div></div></td>";
        dataTableCreate += "<td class='remarks' style='position:relative;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + parentDivId + "\")'><span class='mif-copy'></span></a></td>";
        dataTableCreate += "</tr>";
        dataTableCreate += "<tr style='background: #e0f0f1;'>";
        dataTableCreate += "<td style='font-weight:bold;' class='text-right grandQtyCell'><button onclick='rowAdder($(this), \"" + parentDivId + "\")' type='button' class='tool-button ribbed-teal success' style='position: absolute;width: 20px;height: 20px;line-height: 18px;top: 6px;z-index: 1083;right: 3px;'><span class='mif-plus' style='font-size: 13px;'></span></button><p style='width: 155px;margin: 0px;'>Quantity Grand Total</p></td>";
        dataTableCreate += "<td class='grandtotalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center grand-totalqty-input' name='grandqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold grandunit'>" + $('.' + parentDivId).find('.grandunit').text() + "</span></div></div></td>";
        dataTableCreate += "<td class='itemqty-errors text-left'><span class='invalid_feedback'>W.O. required quantity grand total must be greater than zero(0).</span></td>";
        dataTableCreate += "</tr>";
        $('.' + parentDivId).empty();
        $('.' + parentDivId).append(dataTableCreate);
        if ($('.' + parentDivId).hasClass('fixed-data')) {
          $('.' + parentDivId).removeClass('fixed-data');
        }
        $('.' + parentDivId).addClass('manual-data');
      }

    }
    $('#' + parentDivId).find('.pn-no-cell').attr('rowspan', $('#' + parentDivId).find('.data-row').length);
    $('#' + parentDivId).find('.order-no-cell').attr('rowspan', $('#' + parentDivId).find('.data-row').length);
    //$('#'+parentDivId).find('.code-no-cell').attr('rowspan', $('#'+parentDivId).find('.data-row').length);
    $('#' + parentDivId).find('.symbol-cell').attr('rowspan', $('#' + parentDivId).find('.data-row').length);
  });
  // =====================
  // Parameters End
  // ======================


  $('.addition-cancel').on('click', function (evt) {
    evt.preventDefault();
    var tableClass = $('.tableclass').val();
    var cellClass = $('.columnclass').val();
    $('.' + tableClass).find('.' + cellClass + '-header').remove();
    $('.' + tableClass).find('.' + cellClass).remove();
    $('.addition-popup').css('display', 'none');
    $('.addition-qty').val(0);
    $('.addition-enable input').prop('checked', false);
  });

  $('.converter-cancel').on('click', function (evt) {
    evt.preventDefault();
    var tableClass = $('.contableclass').val();
    var cellClass = $('.concolumnclass').val();
    $('.' + tableClass).find('.' + cellClass + '-header').remove();
    $('.' + tableClass).find('.' + cellClass).remove();
    $('.converter-popup').css('display', 'none');
    $('.converter-enable input').prop('checked', false);
  });
  $('.excel-cancel').on('click', function (evt) {
    evt.preventDefault();
    $('.excel-popup').css('display', 'none');
    $('.excel-file').find('.files').html('0 file(s) selected');
    $('.excel-file input').val('');
  });
  $('.datafill-cancel').on('click', function (evt) {
    evt.preventDefault();
    var tableClass = $('.datafilltableclass').val();
    var cellClass = $('.datafillcolumnclass').val();
    $('.' + tableClass).find('.' + cellClass + '-header').remove();
    $('.' + tableClass).find('.' + cellClass).remove();
    $('.' + tableClass).find('.' + cellClass + '-qty-header').remove();
    $('.' + tableClass).find('.' + cellClass + '-qty').remove();
    $('.datafill-popup').css('display', 'none');
    $('.parameters-disabled input').prop('checked', false);
    $('.parameters-disabled input').prop('disabled', false);
    $('.addition-enable input').prop({ 'checked': false, 'disabled': true });
    $('.converter-enable input').prop({ 'checked': false, 'disabled': true });
  });

  $('.datafill-customize-cancel').on('click', function (evt) {
    evt.preventDefault();
    var tableClass = $('.datafilltableclass1').val();
    var cellClass = $('.datafillcolumnclass1').val();
    $('.' + tableClass).find('.' + cellClass + '-header').remove();
    $('.' + tableClass).find('.' + cellClass).remove();
    $('.datafill-popup1').css('display', 'none');
    $('.parameters-disabled input').prop('checked', false);
    $('.parameters-disabled input').prop('disabled', false);
  });

  $('.datafill-customize-setting').on('click', function (evt) {
    evt.preventDefault();
    var tableClass = $('.datafilltableclass1').val();
    var cellClass = $('.datafillcolumnclass1').val();
    var cellName = $('.datafilldataname1').val();
    var type = $('.datafill1-type').val();
    var dataRepeat = $('.datarepeat-type').val();
    var itemid = $('.' + tableClass).data('uniqueid');
    var itemName = $('.items-' + itemid).text();
    var qtyUnit = $('.' + tableClass).find('.grandunit').text();
    // alert(cellName);
    if (type == 'Fixed') {
      if (cellName == 'size name') {
        var getSizeName = $('.allsize').val().split(',');
        var sizeRowSpan = parseInt(getSizeName.length) * parseInt(dataRepeat);
        var dataTableCreate = "";
        dataTableCreate += "<tr>";
        dataTableCreate += "<th class='items-header'>Name of Item</th>";
        dataTableCreate += "<th class='" + cellClass + "-header' data-columnname='Size Name'>Size Name</th>";
        dataTableCreate += "<th class='totalqty-header'>W.O. Required Qty.</th>";
        dataTableCreate += "<th class='remarks-header'>Remarks</th>";
        dataTableCreate += "</tr>";
        dataTableCreate += "<tr class='data-row-hidden appended-row' style='display:none;'>";
        dataTableCreate += "<td class='" + cellClass + " size-celll-manual'></td>";
        dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
        dataTableCreate += "<td class='remarks' style='position:relative; overflow:hidden;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a><div class='row-removeBtn ribbed-darkRed' onclick='rowRemover($(this), \"" + tableClass + "\");'><span class='mif-cross'></span></div>";
        dataTableCreate += "</tr>";
        for ($i = 0; $i < dataRepeat; $i++) {
          $.each(getSizeName, function (index, val) {
            if (index == 0 && $i == 0) {
              dataTableCreate += "<tr class='data-row'>";
              dataTableCreate += "<td class='text-center text-bold maingrid-rowspan items-name items-" + itemid + "' data-itemid='" + itemid + "' rowspan='" + sizeRowSpan + "'>" + itemName + "</td>";
              dataTableCreate += "<td class='" + cellClass + " size-celll-manual'><div class='data-content' onclick='inputFieldAppend($(this), \"data-content\")'>" + val + "</div></td>";
              dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
              dataTableCreate += "<td class='remarks' style='position:relative;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a></td>";
              dataTableCreate += "</tr>";
            } else {
              dataTableCreate += "<tr class='data-row appended-row'>";
              dataTableCreate += "<td class='" + cellClass + " size-celll-manual'><div class='data-content' onclick='inputFieldAppend($(this), \"data-content\")'>" + val + "</div></td>";
              dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
              dataTableCreate += "<td class='remarks' style='position:relative; overflow:hidden;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a><div class='row-removeBtn ribbed-darkRed' onclick='rowRemover($(this), \"" + tableClass + "\");'><span class='mif-cross'></span></div></td>";
              dataTableCreate += "</tr>";
            }
          });
        }
        dataTableCreate += "<tr style='background: #e0f0f1;'>";
        dataTableCreate += "<td style='font-weight:bold; position:relative;' class='text-right grandQtyCell'><p style='width: 155px;margin: 0px;'>Quantity Grand Total</p></td>";
        dataTableCreate += "<td class='" + cellClass + "'></td>";
        dataTableCreate += "<td class='grandtotalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center grand-totalqty-input' name='grandqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold grandunit'>" + qtyUnit + "</span></div></div></td>";
        dataTableCreate += "<td class='itemqty-errors text-left'><span class='invalid_feedback'>W.O. required quantity grand total must be greater than zero(0).</span></td>";
        dataTableCreate += "</tr>";
        $('.' + tableClass).empty();
        $('.' + tableClass).append(dataTableCreate);
      } else if (cellName == 'grmnts color') {
        var presetDataVar = presetData('colorwise');
        var colorRowSpan = parseInt(presetDataVar.color.length) * parseInt(dataRepeat);
        var dataTableCreate = "";
        dataTableCreate += "<tr>";
        dataTableCreate += "<th class='items-header'>Name of Item</th>";
        dataTableCreate += "<th class='" + cellClass + "-header' data-columnname='Color Name'>Color Name</th>";
        dataTableCreate += "<th class='totalqty-header'>W.O. Required Qty.</th>";
        dataTableCreate += "<th class='remarks-header'>Remarks</th>";
        dataTableCreate += "</tr>";
        dataTableCreate += "<tr class='data-row-hidden appended-row' style='display:none;'>";
        dataTableCreate += "<td class='" + cellClass + " color-celll-manual'></td>";
        dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
        dataTableCreate += "<td class='remarks' style='position:relative; overflow:hidden;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a><div class='row-removeBtn ribbed-darkRed' onclick='rowRemover($(this), \"" + tableClass + "\");'><span class='mif-cross'></span></div>";
        dataTableCreate += "</tr>";
        for ($i = 0; $i < dataRepeat; $i++) {
          $.each(presetDataVar.color, function (index, val) {
            if (index == 0 && $i == 0) {
              dataTableCreate += "<tr class='data-row'>";
              dataTableCreate += "<td class='text-center text-bold maingrid-rowspan items-name items-" + itemid + "' data-itemid='" + itemid + "' rowspan='" + colorRowSpan + "'>" + itemName + "</td>";
              dataTableCreate += "<td class='" + cellClass + " color-celll-manual'><div class='data-content'>" + val + "</div></td>";
              dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
              dataTableCreate += "<td class='remarks' style='position:relative;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a></td>";
              dataTableCreate += "</tr>";
            } else {
              dataTableCreate += "<tr class='data-row appended-row'>";
              dataTableCreate += "<td class='" + cellClass + " color-celll-manual'><div class='data-content'>" + val + "</div></td>";
              dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
              dataTableCreate += "<td class='remarks' style='position:relative; overflow:hidden;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a><div class='row-removeBtn ribbed-darkRed' onclick='rowRemover($(this), \"" + tableClass + "\");'><span class='mif-cross'></span></div></td>";
              dataTableCreate += "</tr>";
            }
          });
        }
        dataTableCreate += "<tr style='background: #e0f0f1;'>";
        dataTableCreate += "<td style='font-weight:bold; position:relative;' class='text-right grandQtyCell'><p style='width: 155px;margin: 0px;'>Quantity Grand Total</p></td>";
        dataTableCreate += "<td class='" + cellClass + "'></td>";
        dataTableCreate += "<td class='grandtotalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center grand-totalqty-input' name='grandqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold grandunit'>" + qtyUnit + "</span></div></div></td>";
        dataTableCreate += "<td class='itemqty-errors text-left'><span class='invalid_feedback'>W.O. required quantity grand total must be greater than zero(0).</span></td>";
        dataTableCreate += "</tr>";
        $('.' + tableClass).empty();
        $('.' + tableClass).append(dataTableCreate);
      } else if (cellName == 'grmnts color/kimball/lot') {
        var presetDataVar = presetData('kimballsizewise');
        var colorRowSpan = parseInt(presetDataVar.color.length) * parseInt(dataRepeat);
        var dataTableCreate = "";
        dataTableCreate += "<tr>";
        dataTableCreate += "<th class='items-header'>Name of Item</th>";
        dataTableCreate += "<th class='" + cellClass + "-header' data-columnname='Color Name'>Color Name</th>";
        dataTableCreate += "<th class='" + cellClass + "-header kimballcelll-header' data-columnname='Kimball No.' ondblclick='columnRemove(\"" + tableClass + "\", \"kimballcelll\")'>Kimball No.</th>";
        dataTableCreate += "<th class='" + cellClass + "-header' data-columnname='Lot No.'>Lot No.</th>";
        dataTableCreate += "<th class='totalqty-header'>W.O. Required Qty.</th>";
        dataTableCreate += "<th class='remarks-header'>Remarks</th>";
        dataTableCreate += "</tr>";
        dataTableCreate += "<tr class='data-row-hidden appended-row' style='display:none;'>";
        dataTableCreate += "<td class='" + cellClass + " color-celll-manual'></td><td class='" + cellClass + " kimballcelll'></td><td class='" + cellClass + "'></td>";
        dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
        dataTableCreate += "<td class='remarks' style='position:relative; overflow:hidden;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a><div class='row-removeBtn ribbed-darkRed' onclick='rowRemover($(this), \"" + tableClass + "\");'><span class='mif-cross'></span></div>";
        dataTableCreate += "</tr>";
        for ($i = 0; $i < dataRepeat; $i++) {
          $.each(presetDataVar.color, function (index, val) {
            if (index == 0 && $i == 0) {
              dataTableCreate += "<tr class='data-row'>";
              dataTableCreate += "<td class='text-center text-bold maingrid-rowspan items-name items-" + itemid + "' data-itemid='" + itemid + "' rowspan='" + colorRowSpan + "'>" + itemName + "</td>";
              dataTableCreate += "<td class='" + cellClass + " color-celll-manual'><div class='data-content'>" + val + "</div></td><td class='" + cellClass + " kimball-cell kimballcelll text-center'>" + presetDataVar.kimball[index] + "</td><td class='" + cellClass + " lot-cell text-center'>" + presetDataVar.lot[index] + "</td>";
              dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
              dataTableCreate += "<td class='remarks' style='position:relative;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a></td>";
              dataTableCreate += "</tr>";
            } else {
              dataTableCreate += "<tr class='data-row appended-row'>";
              dataTableCreate += "<td class='" + cellClass + " color-celll-manual'><div class='data-content'>" + val + "</div></td><td class='" + cellClass + " kimball-cell kimballcelll text-center'>" + presetDataVar.kimball[index] + "</td><td class='" + cellClass + " lot-cell text-center'>" + presetDataVar.lot[index] + "</td>";
              dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
              dataTableCreate += "<td class='remarks' style='position:relative; overflow:hidden;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a><div class='row-removeBtn ribbed-darkRed' onclick='rowRemover($(this), \"" + tableClass + "\");'><span class='mif-cross'></span></div></td>";
              dataTableCreate += "</tr>";
            }
          });
        }
        dataTableCreate += "<tr style='background: #e0f0f1;'>";
        dataTableCreate += "<td style='font-weight:bold; position:relative;' class='text-right grandQtyCell'><p style='width: 155px;margin: 0px;'>Quantity Grand Total</p></td>";
        dataTableCreate += "<td class='" + cellClass + "'></td><td class='" + cellClass + " kimballcelll'></td><td class='" + cellClass + "'></td>";
        dataTableCreate += "<td class='grandtotalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center grand-totalqty-input' name='grandqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold grandunit'>" + qtyUnit + "</span></div></div></td>";
        dataTableCreate += "<td class='itemqty-errors text-left'><span class='invalid_feedback'>W.O. required quantity grand total must be greater than zero(0).</span></td>";
        dataTableCreate += "</tr>";
        $('.' + tableClass).empty();
        $('.' + tableClass).append(dataTableCreate);
      }
      $('.datafill-popup1').css('display', 'none');
    } else {
      $('.datafill-popup1').css('display', 'none');
    }


  });

  $('.datafill1-type').on('change', function () {
    if ($(this).val() == 'Fixed') {
      $('.repeater-content').css('display', 'block');
    } else {
      $('.repeater-content').css('display', 'none');
    }
  });

  $('.datafill-setting').on('click', function (evt) {
    evt.preventDefault();
    var tableClass = $('.datafilltableclass').val();
    var cellClass = $('.datafillcolumnclass').val();
    var cellName = $('.datafilldataname').val();
    var type = $('.datafill-type').val();
    var itemid = $('.' + tableClass).data('uniqueid');
    var itemName = $('.items-' + itemid).text();
    var qtyUnit = $('.' + tableClass).find('.grandunit').text();
    if (type == 'Fixed') {
      if ($('.' + tableClass).hasClass('manual-data')) {
        $('.' + tableClass).removeClass('manual-data');
      }
      $('#' + tableClass).find('.general-parameters input').prop('checked', false);
      $('.' + tableClass).addClass('fixed-data');

      if (cellName == 'kimball & color wise qty') {
        var presetDataVar = presetData('kimballsizewise');
        var dataTableCreate = "";
        dataTableCreate += "<tr>";
        dataTableCreate += "<th class='items-header'>Name of Item</th>";
        dataTableCreate += "<th class='" + cellClass + "-header' data-columnname='Color Name'>Color Name</th>";
        dataTableCreate += "<th class='" + cellClass + "-header kimballcelll-header' data-columnname='Kimball No.' ondblclick='columnRemove(\"" + tableClass + "\", \"kimballcelll\")'>Kimball No.</th>";
        dataTableCreate += "<th class='" + cellClass + "-header' data-columnname='Lot No.'>Lot No.</th>";
        dataTableCreate += "<th class='" + cellClass + "-qty-header' data-columnname='Garments Qty.'>Garments Qty.</th>";
        dataTableCreate += "<th class='totalqty-header'>W.O. Required Qty.</th>";
        dataTableCreate += "<th class='remarks-header'>Remarks</th>";
        dataTableCreate += "</tr>";
        $.each(presetDataVar.color, function (index, val) {
          if (index == 0) {
            dataTableCreate += "<tr class='data-row-hidden appended-row' style='display:none;'>";
            dataTableCreate += "<td class='" + cellClass + "'></td><td class='" + cellClass + " kimballcelll'></td><td class='" + cellClass + "'></td><td class='" + cellClass + "-qty'><input type='hidden' class='addition-qty-hidden' value='0'><input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\"" + tableClass + "\", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0'></td>";
            dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
            dataTableCreate += "<td class='remarks' style='position:relative; overflow:hidden;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a><div class='row-removeBtn ribbed-darkRed' onclick='rowRemover($(this), \"" + tableClass + "\");'><span class='mif-cross'></span></div>";
            dataTableCreate += "</tr>";
            dataTableCreate += "<tr class='data-row'>";
            dataTableCreate += "<td class='text-center text-bold maingrid-rowspan items-name items-" + itemid + "' data-itemid='" + itemid + "' rowspan='" + presetDataVar.color.length + "'>" + itemName + "</td>";
            dataTableCreate += "<td class='" + cellClass + " color-celll-fixed'>" + val + "</td><td class='" + cellClass + " kimball-cell kimballcelll text-center'>" + presetDataVar.kimball[index] + "</td><td class='" + cellClass + " lot-cell text-center'>" + presetDataVar.lot[index] + "</td><td class='" + cellClass + "-qty'><input type='hidden' class='addition-qty-hidden' value='" + parseInt(presetDataVar.qty[index]) + "'><input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\"" + tableClass + "\", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='" + parseInt(presetDataVar.qty[index]) + "'></td>";
            dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='" + parseInt(presetDataVar.qty[index]) + "'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
            dataTableCreate += "<td class='remarks' style='position:relative;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a></td>";
            dataTableCreate += "</tr>";
          } else {
            dataTableCreate += "<tr class='data-row appended-row'>";
            dataTableCreate += "<td class='" + cellClass + " color-celll-fixed'>" + val + "</td><td class='" + cellClass + " kimball-cell kimballcelll text-center'>" + presetDataVar.kimball[index] + "</td><td class='" + cellClass + " lot-cell text-center'>" + presetDataVar.lot[index] + "</td><td class='" + cellClass + "-qty'><input type='hidden' class='addition-qty-hidden' value='" + parseInt(presetDataVar.qty[index]) + "'><input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\"" + tableClass + "\", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='" + parseInt(presetDataVar.qty[index]) + "'></td>";
            dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='" + parseInt(presetDataVar.qty[index]) + "'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
            dataTableCreate += "<td class='remarks' style='position:relative; overflow:hidden;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a><div class='row-removeBtn ribbed-darkRed' onclick='rowRemover($(this), \"" + tableClass + "\");'><span class='mif-cross'></span></div></td>";
            dataTableCreate += "</tr>";
          }
        });
        dataTableCreate += "<tr style='background: #e0f0f1;'>";
        dataTableCreate += "<td style='font-weight:bold; position:relative;' class='text-right grandQtyCell'><p style='width: 155px;margin: 0px;'>Quantity Grand Total</p></td>";
        dataTableCreate += "<td class='" + cellClass + "'></td><td class='" + cellClass + " kimballcelll'></td><td class='" + cellClass + "'></td><td class='" + cellClass + "-qty' style='position:relative;'><input type='text' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='0' readonly></td>";
        dataTableCreate += "<td class='grandtotalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center grand-totalqty-input' name='grandqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold grandunit'>" + qtyUnit + "</span></div></div></td>";
        dataTableCreate += "<td class='itemqty-errors text-left'><span class='invalid_feedback'>W.O. required quantity grand total must be greater than zero(0).</span></td>";
        dataTableCreate += "</tr>";
        $('.' + tableClass).empty();
        $('.' + tableClass).append(dataTableCreate);
        $('.datafill-popup').css('display', 'none');
        rowSumCommon('garments-qty-input', 'garmentsgrandtotal', tableClass);
        rowSum(tableClass);
      } else if (cellName == 'color wise qty') {
        var presetDataVar = presetData('colorwise');
        var dataTableCreate = "";
        dataTableCreate += "<tr>";
        dataTableCreate += "<th class='items-header'>Name of Item</th>";
        dataTableCreate += "<th class='" + cellClass + "-header' data-columnname='Color Name'>Color Name</th>";
        dataTableCreate += "<th class='" + cellClass + "-qty-header' data-columnname='Garments Qty.'>Garments Qty.</th>";
        dataTableCreate += "<th class='totalqty-header'>W.O. Required Qty.</th>";
        dataTableCreate += "<th class='remarks-header'>Remarks</th>";
        dataTableCreate += "</tr>";
        $.each(presetDataVar.color, function (index, val) {
          if (index == 0) {
            dataTableCreate += "<tr class='data-row-hidden appended-row' style='display:none;'>";
            dataTableCreate += "<td class='" + cellClass + "'></td><td class='" + cellClass + "-qty'><input type='hidden' class='addition-qty-hidden' value='0'><input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\"" + tableClass + "\", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0'></td>";
            dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
            dataTableCreate += "<td class='remarks' style='position:relative; overflow:hidden;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a><div class='row-removeBtn ribbed-darkRed' onclick='rowRemover($(this), \"" + tableClass + "\");'><span class='mif-cross'></span></div>";
            dataTableCreate += "</tr>";
            dataTableCreate += "<tr class='data-row'>";
            dataTableCreate += "<td class='text-center text-bold maingrid-rowspan items-name items-" + itemid + "' data-itemid='" + itemid + "' rowspan='" + presetDataVar.color.length + "'>" + itemName + "</td>";
            dataTableCreate += "<td class='" + cellClass + " color-celll-fixed'>" + val + "</td><td class='" + cellClass + "-qty'><input type='hidden' class='addition-qty-hidden' value='" + parseInt(presetDataVar.qty[index]) + "'><input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\"" + tableClass + "\", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='" + parseInt(presetDataVar.qty[index]) + "'></td>";
            dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='" + parseInt(presetDataVar.qty[index]) + "'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
            dataTableCreate += "<td class='remarks' style='position:relative;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a></td>";
            dataTableCreate += "</tr>";
          } else {
            dataTableCreate += "<tr class='data-row appended-row'>";
            dataTableCreate += "<td class='" + cellClass + " color-celll-fixed'>" + val + "</td><td class='" + cellClass + "-qty'><input type='hidden' class='addition-qty-hidden' value='" + parseInt(presetDataVar.qty[index]) + "'><input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\"" + tableClass + "\", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='" + parseInt(presetDataVar.qty[index]) + "'></td>";
            dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='" + parseInt(presetDataVar.qty[index]) + "'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
            dataTableCreate += "<td class='remarks' style='position:relative; overflow:hidden;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a><div class='row-removeBtn ribbed-darkRed' onclick='rowRemover($(this), \"" + tableClass + "\");'><span class='mif-cross'></span></div></td>";
            dataTableCreate += "</tr>";
          }
        });
        dataTableCreate += "<tr style='background: #e0f0f1;'>";
        dataTableCreate += "<td style='font-weight:bold; position:relative;' class='text-right grandQtyCell'><p style='width: 155px;margin: 0px;'>Quantity Grand Total</p></td>";
        dataTableCreate += "<td class='" + cellClass + "'></td><td class='" + cellClass + "-qty' style='position:relative;'><input type='text' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='0' readonly></td>";
        dataTableCreate += "<td class='grandtotalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center grand-totalqty-input' name='grandqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold grandunit'>" + qtyUnit + "</span></div></div></td>";
        dataTableCreate += "<td class='itemqty-errors text-left'><span class='invalid_feedback'>W.O. required quantity grand total must be greater than zero(0).</span></td>";
        dataTableCreate += "</tr>";
        $('.' + tableClass).empty();
        $('.' + tableClass).append(dataTableCreate);
        $('.datafill-popup').css('display', 'none');
        rowSumCommon('garments-qty-input', 'garmentsgrandtotal', tableClass);
        rowSum(tableClass);
      } else if (cellName == 'color & size wise qty') {
        presetDataVar = presetData('color&sizewise', itemName);

        var dataTableCreate = "";
        dataTableCreate += "<tr>";
        dataTableCreate += "<th class='items-header'>Name of Item</th>";
        dataTableCreate += "<th class='" + cellClass + "-header' data-columnname='Color Name'>Color Name</th>";
        var csize = '<div class="row no-gap">';
        $.each(presetDataVar.sizename, function (index, val) {
          csize += '<div class="cell size-' + val + '-header bg-gray border bd-light csize-header" style="min-width: 60px;">' + val + '</div>';
        });
        csize += '</div>';

        // Modified by SPN Start
        if ($.trim($('#buyername').text()) == 'Peacocks') {

          dataTableCreate += "<th class='" + cellClass + "-header colorsizeqty-header'><div>Pack Size Qty</div>" + csize + "</th>";
        } else {

          dataTableCreate += "<th class='" + cellClass + "-header colorsizeqty-header'><div>Size Wise Qty</div>" + csize + "</th>";
        }
        // Modified by SPN End

        dataTableCreate += "<th class='totalqty-header'>W.O. Required Qty.</th>";
        dataTableCreate += "<th class='remarks-header'>Remarks</th>";
        dataTableCreate += "</tr>";
        var grandTotal = 0;
        var counter = 0;
        $.each(presetDataVar.colorsizeQty, function (index, val) {

          if (counter == 0) {
            dataTableCreate += "<tr class='data-row-hidden appended-row' style='display:none;'>";
            dataTableCreate += "<td class='" + cellClass + "'></td><td class='" + cellClass + "'></td>";
            dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
            dataTableCreate += "<td class='remarks' style='position:relative; overflow:hidden;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a><div class='row-removeBtn ribbed-darkRed' onclick='rowRemover($(this), \"" + tableClass + "\");'><span class='mif-cross'></span></div>";
            dataTableCreate += "</tr>";
            dataTableCreate += "<tr class='data-row'>";
            dataTableCreate += "<td class='text-center text-bold maingrid-rowspan items-name items-" + itemid + "' data-itemid='" + itemid + "' rowspan='" + Object.keys(presetDataVar.colorsizeQty).length + "'>" + itemName + "</td>";
            var csize = '<div class="row no-gap">';
            $.each(presetDataVar.sizename, function (indexx, vall) {
              var value = 0;
              if (val[vall] != undefined) {
                value = val[vall];
              }
              grandTotal += parseInt(value);
              // console.log(presetDataVar);
              // console.log(value);
              //alert(value);
              csize += '<div class="cell size-' + vall + ' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="input-small text-center size-' + vall + '-input csize-input" data-sizename="' + vall + '" readonly value="' + value + '" style="width: 100%;" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty(\'' + tableClass + '\', $(this));"><input type="hidden" class="csaddition-qty-hidden additionsize-' + vall + '-input" value="' + value + '"></div>';
            });
            csize += '</div>';
            dataTableCreate += "<td class='" + cellClass + " color-celll-fixed'>" + index + "</td><td  class='" + cellClass + " colorsizeqty'>" + csize + "</td>";
            dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='" + arraySum(val) + "'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
            dataTableCreate += "<td class='remarks' style='position:relative;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a></td>";
            dataTableCreate += "</tr>";
          } else {
            dataTableCreate += "<tr class='data-row appended-row'>";
            var csize = '<div class="row no-gap">';
            $.each(presetDataVar.sizename, function (indexx, vall) {
              var value = 0;
              if (val[vall] != undefined) {
                value = val[vall];
              }
              grandTotal += parseInt(value);
              csize += '<div class="cell size-' + vall + ' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="input-small text-center csize-input" data-sizename="' + vall + '" readonly value="' + value + '" style="width: 100%;" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty(\'' + tableClass + '\', $(this));"><input type="hidden" class="csaddition-qty-hidden additionsize-' + vall + '-input" value="' + value + '"></div>';
            });
            csize += '</div>';
            dataTableCreate += "<td class='" + cellClass + " color-celll-fixed'>" + index + "</td><td  class='" + cellClass + " colorsizeqty'>" + csize + "</td>";
            dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='" + arraySum(val) + "'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
            dataTableCreate += "<td class='remarks' style='position:relative; overflow:hidden;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a><div class='row-removeBtn ribbed-darkRed' onclick='rowRemover($(this), \"" + tableClass + "\");'><span class='mif-cross'></span></div></td>";
            dataTableCreate += "</tr>";
          }
          counter++;
        });
        dataTableCreate += "<tr style='background: #e0f0f1;'>";
        dataTableCreate += "<td style='font-weight:bold; position:relative;' class='text-right grandQtyCell'><p style='width: 155px;margin: 0px;'>Quantity Grand Total</p></td>";
        dataTableCreate += "<td class='" + cellClass + "'></td><td class='" + cellClass + "' style='position:relative;'><input type='hidden' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='" + grandTotal + "'></td>";
        dataTableCreate += "<td class='grandtotalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center grand-totalqty-input' name='grandqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold grandunit'>" + qtyUnit + "</span></div></div></td>";
        dataTableCreate += "<td class='itemqty-errors text-left'><span class='invalid_feedback'>W.O. required quantity grand total must be greater than zero(0).</span></td>";
        dataTableCreate += "</tr>";
        $('.' + tableClass).empty();
        $('.' + tableClass).append(dataTableCreate);
        $('.datafill-popup').css('display', 'none');
        // rowSumCommon('garments-qty-input', 'garmentsgrandtotal', tableClass);
        rowSum(tableClass);

      } else if (cellName == 'kimball/color/size wise qty') {
        var presetDataVar = presetData('kimball&sizewise');
        var dataTableCreate = "";
        dataTableCreate += "<tr>";
        dataTableCreate += "<th class='items-header'>Name of Item</th>";
        dataTableCreate += "<th class='" + cellClass + "-header' data-columnname='Color Name'>Color Name</th>";
        dataTableCreate += "<th class='" + cellClass + "-header kimballcelll-header' data-columnname='Kimball No.' ondblclick='columnRemove(\"" + tableClass + "\", \"kimballcelll\")'>Kimball No.</th>";
        dataTableCreate += "<th class='" + cellClass + "-header' data-columnname='Lot No.'>Lot No.</th>";
        var csize = '<div class="row no-gap">';
        $.each(presetDataVar.sizename, function (index, val) {
          csize += '<div class="cell size-' + val + '-header bg-gray border bd-light csize-header" style="min-width: 60px;">' + val + '</div>';
        });
        csize += '</div>';
        dataTableCreate += "<th class='" + cellClass + "-header colorsizeqty-header'><div>Size Wise Qty</div>" + csize + "</th>";
        dataTableCreate += "<th class='totalqty-header'>W.O. Required Qty.</th>";
        dataTableCreate += "<th class='remarks-header'>Remarks</th>";
        dataTableCreate += "</tr>";
        var grandTotal = 0;
        var counter = 0;
        $.each(presetDataVar.colorsizeQty, function (index, val) {
          if (counter == 0) {
            dataTableCreate += "<tr class='data-row-hidden appended-row' style='display:none;'>";
            dataTableCreate += "<td class='" + cellClass + "'></td><td class='" + cellClass + " kimballcelll'></td><td class='" + cellClass + "'></td><td class='" + cellClass + "'></td>";
            dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
            dataTableCreate += "<td class='remarks' style='position:relative; overflow:hidden;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a><div class='row-removeBtn ribbed-darkRed' onclick='rowRemover($(this), \"" + tableClass + "\");'><span class='mif-cross'></span></div>";
            dataTableCreate += "</tr>";
            dataTableCreate += "<tr class='data-row'>";
            dataTableCreate += "<td class='text-center text-bold maingrid-rowspan items-name items-" + itemid + "' data-itemid='" + itemid + "' rowspan='" + Object.keys(presetDataVar.colorsizeQty).length + "'>" + itemName + "</td>";
            var csize = '<div class="row no-gap">';
            $.each(presetDataVar.sizename, function (indexx, vall) {
              var value = 0;
              if (val[vall] != undefined) {
                value = val[vall];
              }
              grandTotal += parseInt(value);
              //alert(value);
              csize += '<div class="cell size-' + vall + ' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="input-small text-center size-' + vall + '-input csize-input" data-sizename="' + vall + '" readonly value="' + value + '" style="width: 100%;" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty(\'' + tableClass + '\', $(this));"><input type="hidden" class="csaddition-qty-hidden additionsize-' + vall + '-input" value="' + value + '"></div>';
            });
            csize += '</div>';
            dataTableCreate += "<td class='" + cellClass + " color-celll-fixed'>" + index.replace('*lot*' + presetDataVar.lot[counter] + '*lot*' + presetDataVar.kimball[counter], '') + "</td><td class='text-center " + cellClass + " kimball-cell kimballcelll'>" + presetDataVar.kimball[counter] + "</td><td class='text-center " + cellClass + " lot-cell'>" + presetDataVar.lot[counter] + "</td><td  class='" + cellClass + " colorsizeqty'>" + csize + "</td>";
            dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='" + arraySum(val) + "'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
            dataTableCreate += "<td class='remarks' style='position:relative;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a></td>";
            dataTableCreate += "</tr>";
          } else {
            dataTableCreate += "<tr class='data-row appended-row'>";
            var csize = '<div class="row no-gap">';
            $.each(presetDataVar.sizename, function (indexx, vall) {
              var value = 0;
              if (val[vall] != undefined) {
                value = val[vall];
              }
              grandTotal += parseInt(value);
              csize += '<div class="cell size-' + vall + ' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="input-small text-center csize-input" data-sizename="' + vall + '" readonly value="' + value + '" style="width: 100%;" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty(\'' + tableClass + '\', $(this));"><input type="hidden" class="csaddition-qty-hidden additionsize-' + vall + '-input" value="' + value + '"></div>';
            });
            csize += '</div>';
            dataTableCreate += "<td class='" + cellClass + " color-celll-fixed'>" + index.replace('*lot*' + presetDataVar.lot[counter] + '*lot*' + presetDataVar.kimball[counter], '') + "</td><td class='text-center " + cellClass + " kimball-cell kimballcelll'>" + presetDataVar.kimball[counter] + "</td><td class='text-center " + cellClass + " lot-cell'>" + presetDataVar.lot[counter] + "</td><td  class='" + cellClass + " colorsizeqty'>" + csize + "</td>";
            dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='" + arraySum(val) + "'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
            dataTableCreate += "<td class='remarks' style='position:relative; overflow:hidden;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a><div class='row-removeBtn ribbed-darkRed' onclick='rowRemover($(this), \"" + tableClass + "\");'><span class='mif-cross'></span></div></td>";
            dataTableCreate += "</tr>";
          }
          counter++;
        });
        dataTableCreate += "<tr style='background: #e0f0f1;'>";
        dataTableCreate += "<td style='font-weight:bold; position:relative;' class='text-right grandQtyCell'><p style='width: 155px;margin: 0px;'>Quantity Grand Total</p></td>";
        dataTableCreate += "<td class='" + cellClass + "'></td><td class='" + cellClass + " kimballcelll'></td><td class='" + cellClass + "'></td><td class='" + cellClass + "' style='position:relative;'><input type='hidden' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='" + grandTotal + "'></td>";
        dataTableCreate += "<td class='grandtotalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center grand-totalqty-input' name='grandqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold grandunit'>" + qtyUnit + "</span></div></div></td>";
        dataTableCreate += "<td class='itemqty-errors text-left'><span class='invalid_feedback'>W.O. required quantity grand total must be greater than zero(0).</span></td>";
        dataTableCreate += "</tr>";
        $('.' + tableClass).empty();
        $('.' + tableClass).append(dataTableCreate);
        $('.datafill-popup').css('display', 'none');
        // rowSumCommon('garments-qty-input', 'garmentsgrandtotal', tableClass);
        rowSum(tableClass);

      } else if (cellName == 'size wise qty') {
        var presetDataVar = presetData('sizewise');
        var dataTableCreate = "";
        dataTableCreate += "<tr>";
        dataTableCreate += "<th class='items-header'>Name of Item</th>";
        dataTableCreate += "<th class='" + cellClass + "-header' data-columnname='Size Name'>Size Name</th>";
        dataTableCreate += "<th class='" + cellClass + "-qty-header' data-columnname='Garments Qty.'>Garments Qty.</th>";
        dataTableCreate += "<th class='totalqty-header'>W.O. Required Qty.</th>";
        dataTableCreate += "<th class='remarks-header'>Remarks</th>";
        dataTableCreate += "</tr>";
        var count = 0;
        $.each(presetDataVar.sizeQty, function (index, val) {
          var counter = count++;
          if (counter == 0) {
            dataTableCreate += "<tr class='data-row-hidden appended-row' style='display:none;'>";
            dataTableCreate += "<td class='" + cellClass + "'></td><td class='" + cellClass + "-qty'><input type='hidden' class='addition-qty-hidden' value='0'><input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\"" + tableClass + "\", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0'></td>";
            dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
            dataTableCreate += "<td class='remarks' style='position:relative; overflow:hidden;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a><div class='row-removeBtn ribbed-darkRed' onclick='rowRemover($(this), \"" + tableClass + "\");'><span class='mif-cross'></span></div>";
            dataTableCreate += "</tr>";
            dataTableCreate += "<tr class='data-row'>";
            dataTableCreate += "<td class='text-center text-bold maingrid-rowspan items-name items-" + itemid + "' data-itemid='" + itemid + "' rowspan='" + Object.keys(presetDataVar.sizeQty).length + "'>" + itemName + "</td>";
            dataTableCreate += "<td class='" + cellClass + " size-celll-fixed'>" + index + "</td><td class='" + cellClass + "-qty'><input type='hidden' class='addition-qty-hidden' value='" + val + "'><input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\"" + tableClass + "\", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='" + val + "'></td>";
            dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='" + val + "'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
            dataTableCreate += "<td class='remarks' style='position:relative;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a></td>";
            dataTableCreate += "</tr>";
          } else {
            dataTableCreate += "<tr class='data-row appended-row'>";
            dataTableCreate += "<td class='" + cellClass + " size-celll-fixed'>" + index + "</td><td class='" + cellClass + "-qty'><input type='hidden' class='addition-qty-hidden' value='" + val + "'><input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty(\"" + tableClass + "\", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='" + val + "'></td>";
            dataTableCreate += "<td class='totalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum(\"" + tableClass + "\")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='" + val + "'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'>" + qtyUnit + "</span></div></div></td>";
            dataTableCreate += "<td class='remarks' style='position:relative; overflow:hidden;'><textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), \"remarks\", \"" + tableClass + "\")'><span class='mif-copy'></span></a><div class='row-removeBtn ribbed-darkRed' onclick='rowRemover($(this), \"" + tableClass + "\");'><span class='mif-cross'></span></div></td>";
            dataTableCreate += "</tr>";
          }
        });
        dataTableCreate += "<tr style='background: #e0f0f1;'>";
        dataTableCreate += "<td style='font-weight:bold; position:relative;' class='text-right grandQtyCell'><p style='width: 155px;margin: 0px;'>Quantity Grand Total</p></td>";
        dataTableCreate += "<td class='" + cellClass + "'></td><td class='" + cellClass + "-qty' style='position:relative;'><input type='text' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='0' readonly></td>";
        dataTableCreate += "<td class='grandtotalqty'><div class='row no-gap'><div class='cell'><input type='text' readonly class='input-small text-center grand-totalqty-input' name='grandqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'></div><div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold grandunit'>" + qtyUnit + "</span></div></div></td>";
        dataTableCreate += "<td class='itemqty-errors text-left'><span class='invalid_feedback'>W.O. required quantity grand total must be greater than zero(0).</span></td>";
        dataTableCreate += "</tr>";
        $('.' + tableClass).empty();
        $('.' + tableClass).append(dataTableCreate);
        $('.datafill-popup').css('display', 'none');
        rowSumCommon('garments-qty-input', 'garmentsgrandtotal', tableClass);
        rowSum(tableClass);
      }
    } else {
      $('.datafill-popup').css('display', 'none');
      //$('#'+tableClass).find('.general-parameters input').prop('checked', false);
    }
  });

  $('.gridappender').on('click', '.excel-upload', function (ev) {
    ev.preventDefault();
    ev.stopPropagation();
    var uniqueId = $(this).data('parentid'), dataName = $(this).data('name'), tableClass = $(this).data('tableclass');
    $('.exceltableclass').val(tableClass);
    $('.exceldataid').val(uniqueId);
    $('.excelitemname').val(dataName);
    $('.excelitemunit').val($('.' + tableClass).find('.grandunit').text());
    $('.excel-popup').css('display', 'block');

  });

  $('.excel-setting').on('click', function (evt) {
    evt.preventDefault();
    if ($('.excel-file input')[0].files[0] == undefined) {
      accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! A valid .xlsx or .csv file is required.<p>', 'alert');
    } else {
      var formData = new FormData();
      formData.append('excelfile', $('.excel-file input')[0].files[0]);
      formData.append('formName', 'excell-export');
      formData.append('itemname', $('.excelitemname').val());
      formData.append('itemid', $('.exceldataid').val());
      formData.append('tableclass', $('.exceltableclass').val());
      formData.append('tableunit', $('.excelitemunit').val());
      tableClass = $('.exceltableclass').val();
      //formData.append('csrf', $('.csrf').val());
      $.ajax({
        url: 'action/workorder-action.php',
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
          if (response == 'errors') {
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! A valid .xlsx or .csv file is required.<p>', 'alert');
          } else {
            //alert(tableClass);
            $('.' + tableClass).empty();
            $('.' + tableClass).addClass('excel-grid');
            if ($('.' + tableClass).hasClass('fixed-data')) {
              $('.' + tableClass).removeClass('fixed-data');
            }
            if ($('.' + tableClass).hasClass('manual-data')) {
              $('.' + tableClass).removeClass('manual-data');
            }
            $('.' + tableClass).html(response);
            $('#' + tableClass).find('.parameters input').prop('disabled', true);
            $('#' + tableClass).find('.addition-enable input').prop('disabled', false);
            $('#' + tableClass).find('.kimball-color-size-wise-qty input').prop({
              'disabled': false,
              'checked': true,
            });
            $('.excel-popup').css('display', 'none');
            $('.excel-file').find('.files').html('0 file(s) selected');
            $('.excel-file input').val('');
          }
        }
      });
    }
  });

  $('.addition-setting').on('click', function (evt) {
    evt.preventDefault();
    var tableClass = $('.tableclass').val();
    var cellClass = $('.columnclass').val();
    var additionType = $('.addition-type').val();
    var additionQty = $('.addition-qty').val();
    if (additionQty > 0) {
      if ($('.' + tableClass).find('.colorsizeqty-header').length == 0) {

        $('.' + tableClass).find('.data-row .row-garmentsqtyextra-input').each(function (index) {
          var garmentsQty = $(this).closest('tr').find('.addition-qty-hidden').val();
          if (additionType == 'parcent') {
            var getValueInitial = (parseFloat(garmentsQty) / 100) * parseFloat(additionQty);
            getValue = parseFloat(garmentsQty) + parseFloat(getValueInitial);
          }
          if (additionType == 'qty') {
            getValue = parseFloat(garmentsQty) + parseFloat(additionQty);
          }
          $(this).val(Math.round(getValue));
          $(this).attr('data-additionval', additionQty);
          $(this).attr('data-additiontype', additionType);
          $(this).closest('tr').find('.row-totalqty-input').val(Math.round(getValue));
          $('.addition-popup').css('display', 'none');
          $('.addition-qty').val(0);
          if ($('.' + tableClass).find('.row-convertion-input').length > 0 && index > 0) {
            convertionCalculate(tableClass, $(this));
          }
        });
        if (additionType == 'parcent') {
          $('.' + cellClass + '-header').html("<input type='hidden' name='additioncolumnname' class='addition-columnname' value='Garments Qty With " + additionQty + "% Extra'>Garments Qty With " + additionQty + "% Extra");
          $('.' + cellClass + '-header').attr("data-columnname", "Garments Qty With " + additionQty + "% Extra");
          // $('.'+cellClass+'-header .addition-columnname').val("Garments Qty With "+additionQty+"% Extra");
        }
        if (additionType == 'qty') {
          $('.' + cellClass + '-header').html("<input type='hidden' name='additioncolumnname' class='addition-columnname' value='Garments Qty With " + additionQty + " Pcs. Extra'>Garments Qty With " + additionQty + " Pcs. Extra");
          $('.' + cellClass + '-header').attr("Total Qty With " + additionQty + " Pcs. Extra");
          // $('.'+cellClass+'-header .').val("Total Qty With "+additionQty+" Pcs. Extra");
        }

      } else {
        $('.' + tableClass).find('.data-row .row-garmentsqtyextra-input').each(function (index) {
          var getValSum = 0;
          $(this).closest('tr').find('.csize-input').each(function (indexx) {
            var garmentsQty = $(this).closest('div').find('.csaddition-qty-hidden').val();
            if (additionType == 'parcent') {
              var getValueInitial = (parseFloat(garmentsQty) / 100) * parseFloat(additionQty);
              getValue = parseFloat(garmentsQty) + parseFloat(getValueInitial);
            }
            if (additionType == 'qty') {
              getValue = parseFloat(garmentsQty) + parseFloat(additionQty);
            }
            $(this).val(Math.round(getValue));
            getValSum += Math.round(getValue);
          });
          $(this).val(Math.round(getValSum));
          $(this).attr('data-additionval', additionQty);
          $(this).attr('data-additiontype', additionType);
          $(this).closest('tr').find('.row-totalqty-input').val(Math.round(getValSum));
          $('.addition-popup').css('display', 'none');
          $('.addition-qty').val(0);
          if ($('.' + tableClass).find('.row-convertion-input').length > 0 && index > 0) {
            convertionCalculate(tableClass, $(this));
          }
        });
        if (additionType == 'parcent') {
          $('.' + cellClass + '-header').html("<input type='hidden' name='additioncolumnname' class='addition-columnname' value='Total Qty With " + additionQty + "% Extra'>Total Qty With " + additionQty + "% Extra");
          $('.' + cellClass + '-header').attr("data-columnname", "Total Qty With " + additionQty + "% Extra");
        }
        if (additionType == 'qty') {
          $('.' + cellClass + '-header').html("<input type='hidden' name='additioncolumnname' class='addition-columnname' value='Total Qty With " + additionQty + " Pcs. Extra'>Total Qty With " + additionQty + " Pcs. Extra");
          $('.' + cellClass + '-header').attr("Total Qty With " + additionQty + " Pcs. Extra");
        }
      }
      rowSumCommon('row-garmentsqtyextra-input', 'garmentsextragrandtotal', tableClass);
      rowSum(tableClass);
    } else {
      accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Additional quantity must be greater than 0.</p>', 'alert');
    }
  });

  $('.convertion-setting').on('click', function (evt) {
    evt.preventDefault();
    var convertionType = $('.convertion-type').val();
    var tableClass = $('.contableclass').val();
    var cellClass = $('.concolumnclass').val();
    var getType = $('.convertion-type :selected').data('caltype');
    var qtyUnit = $('.' + tableClass).find('.grandunit').text().toLowerCase();
    //alert(getType);

    var uniqueId = $('.' + tableClass).data('uniqueid');
    // Modified by SPN Start
    // if ($.trim($('#buyername').text()) == 'Peacocks') {
    //   $('.'+tableClass).find('.'+cellClass+'-header').text('Consumption');
    // } else {

    // }
    $('.' + tableClass).find('.' + cellClass + '-header').text(convertionType);
    // Modified by SPN End
    $('.' + tableClass).find('.' + cellClass + '-header').attr('data-columnname', convertionType);

    $('.' + tableClass).find('.row-convertion-input').each(function (index) {
      if ($('.extraCalAdded').find('.converter-rules').length > 0) {
        $(this).attr('data-convertionval', $('.converter-rules').val());
      }
      $(this).attr('data-calinputtype', getType);
    });
    $('.' + tableClass).find('.row-convertion-input').each(function (index) {
      var data = $(this).val();
      if ($('.addition' + uniqueId).length > 0) {
        var garmentsQty = $(this).closest('tr').find('.row-garmentsqtyextra-input').val();
      } else {
        if ($('.' + tableClass).find('.colorsizeqty-header').length > 0) {
          var getValSum = 0;
          $(this).closest('tr').find('.csize-input').each(function (indexx) {
            getValSum += parseInt($(this).val());
          });
          var garmentsQty = getValSum;
        } else {
          var garmentsQty = $(this).closest('tr').find('.garments-qty-input').val();
        }
      }
      if ($(this).data('convertionval') == undefined) {
        if (getType == 'divided') {
          var totalQty = parseFloat(garmentsQty) / parseFloat(data);
        } else {
          var totalQty = parseFloat(garmentsQty) * parseFloat(data);
        }
      } else {
        if (getType == 'divided') {
          var totalQty = parseFloat(garmentsQty) / parseFloat(data);
        } else {
          var totalQty = parseFloat(garmentsQty) * parseFloat(data);
        }
        // var totalQty = parseFloat(garmentsQty) * parseFloat(data);

        totalQty = totalQty / parseFloat($(this).data('convertionval'));
      }
      if (qtyUnit == 'yrds' || qtyUnit == 'gg') {
        $(this).closest('tr').find('.row-totalqty-input').val(precise_round(totalQty, 2));
      } else {
        $(this).closest('tr').find('.row-totalqty-input').val(Math.round(totalQty));
      }
    });
    rowSum(tableClass);
    $('.converter-popup').css('display', 'none');
  });

  $('.addition-qty').on('keypress', function (evt) {
    var keycode = (evt.keyCode ? evt.keyCode : evt.which);
    if (keycode == '13') {
      var tableClass = $('.tableclass').val();
      var cellClass = $('.columnclass').val();
      var additionType = $('.addition-type').val();
      var additionQty = $('.addition-qty').val();
      if (additionQty > 0) {
        if ($('.' + tableClass).find('.colorsizeqty-header').length == 0) {

          $('.' + tableClass).find('.row-garmentsqtyextra-input').each(function (index) {
            var garmentsQty = $(this).closest('tr').find('.addition-qty-hidden').val();
            if (additionType == 'parcent') {
              var getValueInitial = (parseFloat(garmentsQty) / 100) * parseFloat(additionQty);
              getValue = parseFloat(garmentsQty) + parseFloat(getValueInitial);
            }
            if (additionType == 'qty') {
              getValue = parseFloat(garmentsQty) + parseFloat(additionQty);
            }
            $(this).val(Math.round(getValue));
            $(this).attr('data-additionval', additionQty);
            $(this).attr('data-additiontype', additionType);
            $(this).closest('tr').find('.row-totalqty-input').val(Math.round(getValue));
            $('.addition-popup').css('display', 'none');
            $('.addition-qty').val(0);
            if ($('.' + tableClass).find('.row-convertion-input').length > 0 && index > 0) {
              convertionCalculate(tableClass, $(this));
            }
          });
          if (additionType == 'parcent') {
            $('.' + cellClass + '-header').html("<input type='hidden' name='additioncolumnname' class='addition-columnname' value='Total Qty With " + additionQty + "% Extra'>Garments Qty With " + additionQty + "% Extra");
            $('.' + cellClass + '-header').attr("data-columnname", "Garments Qty With " + additionQty + "% Extra");
          }
          if (additionType == 'qty') {
            $('.' + cellClass + '-header').html("<input type='hidden' name='additioncolumnname' class='addition-columnname' value='Total Qty With " + additionQty + " Pcs. Extra'>Total Qty With " + additionQty + " Pcs. Extra");
            $('.' + cellClass + '-header').attr("Total Qty With " + additionQty + " Pcs. Extra");
          }

        } else {
          $('.' + tableClass).find('.row-garmentsqtyextra-input').each(function (index) {
            var getValSum = 0;
            $(this).closest('tr').find('.csize-input').each(function (indexx) {
              var garmentsQty = $(this).closest('div').find('.csaddition-qty-hidden').val();
              if (additionType == 'parcent') {
                var getValueInitial = (parseFloat(garmentsQty) / 100) * parseFloat(additionQty);
                getValue = parseFloat(garmentsQty) + parseFloat(getValueInitial);
              }
              if (additionType == 'qty') {
                getValue = parseFloat(garmentsQty) + parseFloat(additionQty);
              }
              $(this).val(Math.round(getValue));
              getValSum += Math.round(getValue);
            });
            $(this).val(Math.round(getValSum));
            $(this).attr('data-additionval', additionQty);
            $(this).attr('data-additiontype', additionType);
            $(this).closest('tr').find('.row-totalqty-input').val(Math.round(getValSum));
            $('.addition-popup').css('display', 'none');
            $('.addition-qty').val(0);
            if ($('.' + tableClass).find('.row-convertion-input').length > 0 && index > 0) {
              convertionCalculate(tableClass, $(this));
            }
          });
          if (additionType == 'parcent') {
            $('.' + cellClass + '-header').html("<input type='hidden' name='additioncolumnname' class='addition-columnname' value='Total Qty With " + additionQty + "% Extra'>Total Qty With " + additionQty + "% Extra");
            $('.' + cellClass + '-header').attr("data-columnname", "Total Qty With " + additionQty + "% Extra");
          }
          if (additionType == 'qty') {
            $('.' + cellClass + '-header').html("<input type='hidden' name='additioncolumnname' class='addition-columnname' value='Total Qty With " + additionQty + " Pcs. Extra'>Total Qty With " + additionQty + " Pcs. Extra");
            $('.' + cellClass + '-header').attr("Total Qty With " + additionQty + " Pcs. Extra");
          }
        }
        rowSumCommon('row-garmentsqtyextra-input', 'garmentsextragrandtotal', tableClass);
        rowSum(tableClass);
      } else {
        accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Additional quantity must be greater than 0.</p>', 'alert');
      }
    }
  });


  $('.workorder-submit').on('click', function (evt) {
    evt.preventDefault();
    evt.stopImmediatePropagation();
    var formData = new FormData();
    var getType = $(this).data('type').toLowerCase();
    preloaderStart();
    $('.success-notification').css('display', 'none');
    var workOrderMaster = [], workOrderItems = [], workOrderItemsData = [], customColumns = [], err = [], images = [];
    $('.invalid_feedback').css('display', 'none');
    if ($('.appended-input').length > 0) {
      $('.appended-input').each(function (index, el) {
        $(this).parent().html($(this).val());
      });
    }
    $('.required-field').each(function (index) {
      if ($(this).val() == 0 && $(this).attr('value') != undefined) {
        err.push('error');
        $(this).next('span').css('display', 'block');
      } else if ($(this).find('input').val() == '') {
        err.push('error');
        $(this).closest('.form-group').find('.invalid_feedback').css('display', 'block');
      } else if ($(this).find('textarea').val() == '') {
        err.push('error');
        $(this).closest('.form-group').find('.invalid_feedback').css('display', 'block');
      }
    });
    $('.maingrid').each(function (index) {
      if ($(this).find('.grand-totalqty-input').val() < 0) {
        err.push('error');
        $(this).find('.itemqty-errors span').css('display', 'block');
      }
    });
    if (err.length > 0) {

      preloaderClose();
      accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! One or more error(s) found.<p>', 'alert');
    } else {
      var block = '';
      var blockArr = [];


      if ($('.search-ordernumber input').val() == undefined) {
        if ($('.vordernumberorfklnumber input').val().toLowerCase() == 'block' || $('.vordernumberorfklnumber input').val().toLowerCase() == 'store') {
          if ($('.input-ordernumber-type select').val() == 'Order number') {
            blockArr.push($('.fklno-input').val());
          } else {
            blockArr.push($('.ordernumber-input').val());
          }
          blockArr.push($('.stylename-input').val());
          blockArr.push($('.buyername-input').val());
          blockArr.push($('.season-input').val());
          blockArr.push($('.dept-input').val());
          blockArr.push($('.kimble-input').val());
        }
      } else {
        if ($('.search-ordernumber input').val().toLowerCase() == 'block' || $('.search-ordernumber input').val().toLowerCase() == 'store') {
          if ($('.input-ordernumber-type select').val() == 'Order number') {
            blockArr.push($('.fklno-input').val());
          } else {
            blockArr.push($('.ordernumber-input').val());
          }
          blockArr.push($('.stylename-input').val());
          blockArr.push($('.buyername-input').val());
          blockArr.push($('.season-input').val());
          blockArr.push($('.dept-input').val());
          blockArr.push($('.kimble-input').val());
        }
      }
      block = blockArr.join("##");
      var garmentsQtyMain = [];
      $('.maingrid').each(function (index) {
        if ($(this).find('.garmentsgrandtotal').length == 0) {
          var garmentsQtyGrand = 'N/A';
        } else {
          var garmentsQtyGrand = $(this).find('.garmentsgrandtotal').val();
        }
        garmentsQtyMain.push(garmentsQtyGrand);
      });
      workOrderMaster.push({
        'VORDERNUMBERORFKLNUMBER': $('.search-ordernumber input').val() == undefined ? $('.vordernumberorfklnumber input').val() : $('.search-ordernumber input').val(),
        'VTYPE': $('.input-ordernumber-type select').val(),
        'NSUPLLIERID': $('.suppliername select').val(),
        'VATTN': $('.attnetion-name input').val(),
        'VFORM': $('.form-name input').val(),
        'VDELIVERYDATE': $('.deliverydate input').val(),
        'VGARMENTSQTY': unique(garmentsQtyMain).join(', '),
        'VORDERDETAILS': $('.order-detils textarea').val(),
        'VEXTRANOTES': $('.extranotes textarea').val(),
        'VSTATUS': getType,
        'VBLOCKORDERINFO': block
      });

      $('.maingrid').each(function (indexmain) {
        var ordernumber = '', pnnumber = '', addition = '', convertion = '', garmetsqty = 0, garmentsqtywithextra = 0, columnname = '', columnsTempArr = [],
          itemDataTempArr = {}, customDataTempArr = {}, customData = [], sizeWiseQtyTempArr = {}, sizeWiseQtyData = {}, itemsData = {}, gridtype = '', sizename = [], settingData = {};
        var goodsId = $(this).find('.items-name').data('itemid');
        if ($(this).find('.order-no-input').length > 0) {
          ordernumber = $(this).find('.order-no-input').val();
        }
        if ($(this).find('.pn-no-input').length > 0) {
          pnnumber = $(this).find('.pn-no-input').val();
        }
        if ($(this).find('.symbol-input').length > 0) {
          $.each($(this).find('.symbol-input input')[0].files, function (indeximg, val) {
            images.push('img');
            formData.append('file-' + indeximg + '-' + indexmain, val);
          });
        }
        //$(this).find('')
        if ($(this).find('.color-size-wise-qty' + goodsId + '-header').length > 0 || $(this).find('.kimball-color-size-wise-qty' + goodsId + '-header').length > 0 || $(this).find('.garments-qty-size-' + goodsId + '-header').length > 0) {
          gridtype = 'colornsize';
        } else {
          gridtype = 'straight';
        }
        $(this).find('th').each(function (index) {
          if ($(this).hasClass('items-header')) {

          } else if ($(this).hasClass('pn-no-' + goodsId + '-header')) {

          } else if ($(this).hasClass('order-no-' + goodsId + '-header')) {

          } else if ($(this).hasClass('symbol' + goodsId + '-header')) {

          } else if ($(this).hasClass('colorsizeqty-header')) {
            $(this).find('.csize-header').each(function () {
              sizename.push($(this).text());
            });
            garmetsqty = $(this).closest('.maingrid').find('.garmentsgrandtotal').val();
          } else if ($(this).hasClass('color-wise-qty' + goodsId + '-qty-header')) {
            garmetsqty = $(this).closest('.maingrid').find('.garmentsgrandtotal').val();
          } else if ($(this).hasClass('size-wise-qty' + goodsId + '-qty-header')) {
            garmetsqty = $(this).closest('.maingrid').find('.garmentsgrandtotal').val();
          } else if ($(this).hasClass('kimball-color-wise-qty' + goodsId + '-qty-header')) {
            garmetsqty = $(this).closest('.maingrid').find('.garmentsgrandtotal').val();
          } else if ($(this).hasClass('garments-qty' + goodsId + '-qty-header')) {
            garmetsqty = $(this).closest('.maingrid').find('.garmentsgrandtotal').val();
          } else if ($(this).hasClass('garments-qty-size-' + goodsId + '-header')) {
            garmetsqty = $(this).closest('.maingrid').find('.garmentsgrandtotal').val();
          } else if ($(this).hasClass('addition' + goodsId + '-header')) {
            // alert($(this).find('.addition-columnname').val());
            addition = $(this).find('.addition-columnname').val();
            garmentsqtywithextra = $(this).closest('.maingrid').find('.garmentsextragrandtotal').val();
          } else if ($(this).hasClass('converter' + goodsId + '-header')) {
            convertion = $(this).data('columnname');
          } else if ($(this).hasClass('totalqty-header')) {

          } else if ($(this).hasClass('reamrks-header')) {

          } else {
            columnsTempArr.push($(this).data('columnname'));
          }
        });
        $(this).find('.data-row').each(function (indexOut) {
          var ownRow = $(this);
          $(this).find('td').each(function (indexInner) {
            if ($(this).hasClass('totalqty')) {
              itemsData['NROWTOTALQTY'] = $(this).find('.row-totalqty-input').val();
            } else if ($(this).hasClass('items-name')) {

            } else if ($(this).hasClass('pn-no-cell')) {

            } else if ($(this).hasClass('order-no-cell')) {

            } else if ($(this).hasClass('symbol-cell')) {
              //Work start here..            
            } else if ($(this).hasClass('color-wise-qty' + goodsId + '-qty')) {
              itemsData['NROWGARMENTSQTY'] = $(this).find('.garments-qty-input').val();
            } else if ($(this).hasClass('size-wise-qty' + goodsId + '-qty')) {
              itemsData['NROWGARMENTSQTY'] = $(this).find('.garments-qty-input').val();
            } else if ($(this).hasClass('kimball-color-wise-qty' + goodsId + '-qty')) {
              itemsData['NROWGARMENTSQTY'] = $(this).find('.garments-qty-input').val();
            } else if ($(this).hasClass('garments-qty' + goodsId + '-qty')) {
              itemsData['NROWGARMENTSQTY'] = $(this).find('.garments-qty-input').val();
            } else if ($(this).hasClass('addition' + goodsId)) {
              itemsData['NROWGARMENTSQTYWITHEXTRA'] = $(this).find('.row-garmentsqtyextra-input').val();
            } else if ($(this).hasClass('converter' + goodsId)) {
              itemsData['NCONVERTERQTY'] = $(this).find('.row-convertion-input').val();
            } else if ($(this).hasClass('color-wise-qty' + goodsId)) {
              if ($(this).hasClass('color-celll-manual')) {
                customData.push($(this).find('.data-content').text());
              } else if ($(this).hasClass('color-celll-fixed')) {
                customData.push($(this).text());
              }
            } else if ($(this).hasClass('grmnts-color' + goodsId)) {
              customData.push($(this).find('.data-content').text());
            } else if ($(this).hasClass('size-wise-qty' + goodsId)) {
              if ($(this).hasClass('size-celll-manual')) {
                customData.push($(this).find('.data-content').text());
              } else if ($(this).hasClass('size-celll-fixed')) {
                customData.push($(this).text());
              }
            } else if ($(this).hasClass('size-name' + goodsId)) {
              customData.push($(this).find('.data-content').text());
            } else if ($(this).hasClass('kimball-color-wise-qty' + goodsId)) {
              if ($(this).hasClass('color-celll-manual')) {
                customData.push($(this).find('.data-content').text());
              } else if ($(this).hasClass('color-celll-fixed')) {
                customData.push($(this).text());
              } else if ($(this).hasClass('kimball-cell')) {
                customData.push($(this).text());
              } else if ($(this).hasClass('lot-cell')) {
                customData.push($(this).text());
              }
            } else if ($(this).hasClass('garments-qty-size-' + goodsId)) {
              if ($(this).hasClass('colorsizeqty')) {
                $(this).find('.csize-input').each(function () {
                  var sizeKey = "size-" + $(this).data('sizename').toString();
                  sizeWiseQtyData[sizeKey] = $(this).val();
                });
              }
            } else if ($(this).hasClass('color-size-wise-qty' + goodsId)) {
              if ($(this).hasClass('color-celll-manual')) {
                customData.push($(this).find('.data-content').text());
              } else if ($(this).hasClass('color-celll-fixed')) {
                customData.push($(this).text());
              } else if ($(this).hasClass('colorsizeqty')) {
                $(this).find('.csize-input').each(function () {
                  var sizeKey = "size-" + $(this).data('sizename').toString();
                  sizeWiseQtyData[sizeKey] = $(this).val();
                });
              }
            } else if ($(this).hasClass('kimball-color-size-wise-qty' + goodsId)) {
              if ($(this).hasClass('color-celll-manual')) {
                customData.push($(this).find('.data-content').text());
              } else if ($(this).hasClass('color-celll-fixed')) {
                customData.push($(this).text());
              } else if ($(this).hasClass('kimball-cell')) {
                customData.push($(this).text());
              } else if ($(this).hasClass('lot-cell')) {
                customData.push($(this).text());
              } else if ($(this).hasClass('colorsizeqty')) {
                $(this).find('.csize-input').each(function () {
                  var sizeKey = "size-" + $(this).data('sizename').toString();
                  sizeWiseQtyData[sizeKey] = $(this).val();
                });
              }
            } else if ($(this).hasClass('grmnts-color-kimball-lot' + goodsId)) {
              if ($(this).hasClass('color-celll-manual')) {
                customData.push($(this).find('.data-content').text());
              } else if ($(this).hasClass('kimball-cell')) {
                customData.push($(this).text());
              } else if ($(this).hasClass('lot-cell')) {
                customData.push($(this).text());
              }
            } else if ($(this).hasClass('remarks')) {
              itemsData['VREMARKS'] = $(this).find('textarea[name=vremarks]').val();
            } else {
              customData.push($.isArray($(this).find('.custom-column-value').val()) ? $(this).find('.custom-column-value').val().join(',') : $(this).find('.custom-column-value').val());
            }
          });
          itemDataTempArr[indexOut] = itemsData;
          itemsData = {};
          customDataTempArr[indexOut] = customData;
          customData = [];
          sizeWiseQtyTempArr[indexOut] = sizeWiseQtyData;
          sizeWiseQtyData = {};
        });
        var parameterStore = [];
        $(this).parent('div').find('.searchordertable .parameters').each(function (indexsearchtable) {
          if ($(this).find('input').is(':checked')) {
            parameterStore.push($(this).find('input').val());
          }
        });

        settingData['VCHECKEDOPTIONS'] = parameterStore.join(',');
        settingData['NCONVERSIONVALUE'] = 0;
        settingData['VCONVERTIOMNTYPE'] = '';
        if ($(this).find('.converter' + goodsId).length != undefined) {
          settingData['NCONVERSIONVALUE'] = $(this).find('.converter' + goodsId + ' .row-convertion-input').last().data('convertionval');
          settingData['VCONVERTIOMNTYPE'] = $(this).find('.converter' + goodsId + ' .row-convertion-input').last().data('calinputtype');
        }
        if ($(this).find('.addition' + goodsId).length != undefined) {
          settingData['NADDITIONVALUE'] = $(this).find('.addition' + goodsId + ' .row-garmentsqtyextra-input').last().data('additionval');
          settingData['VADDITIONTYPE'] = $(this).find('.addition' + goodsId + ' .row-garmentsqtyextra-input').last().data('additiontype');
        }
        if ($(this).hasClass('excel-grid')) {
          settingData['NEXCELUPLOAD'] = 1;
        } else {
          settingData['NEXCELUPLOAD'] = 0;
        }
        settingData['VDATAFILLTYPE'] = '';
        // alert($('.addrowdisabler').val());
        // settingData['NMAXALLOWEDROW'] = $('.addrowdisabler').val();
        if ($(this).hasClass('fixed-data')) {
          settingData['VDATAFILLTYPE'] = 'fixed-data';
        }
        if ($(this).hasClass('manual-data')) {
          settingData['VDATAFILLTYPE'] = 'manual-data';
        }


        workOrderItems.push({
          'NTOTALQTY': $(this).find('.grand-totalqty-input').val(),
          'NGOODSID': goodsId,
          'VORDERNUMBER': ordernumber,
          'VPNNUMBER': pnnumber,
          'VCOLUMNNAME': columnsTempArr.join(',').replace(/,\s*$/, ""),
          'VQTYUNIT': $(this).find('.grandunit').text(),
          'NTOTALGARMENTSQTY': garmetsqty,
          'NTOTALGARMENTSQTYWITHEXTRA': garmentsqtywithextra,
          'VADDITION': addition,
          'VCONVERTION': convertion,
          'VGRIDTYPE': gridtype,
          'VSIZENAME': sizename.join(','),
          'itemData': itemDataTempArr,
          'customData': customDataTempArr,
          'sizeWiseQty': sizeWiseQtyTempArr,
          'settingData': settingData
        });
        itemDataTempArr = {};
        customDataTempArr = {};
        sizeWiseQtyTempArr = {};
      });
      // console.log(workOrderItems);
      $.ajax({
        type: 'POST',
        url: 'action/workorder-action.php',
        dataType: 'json',
        data: { 'formName': 'workorderpublish', 'csrf': $('.csrf').val(), 'masterdata': workOrderMaster, 'itemsdata': workOrderItems },
        success: function (getResponse) {
          if (getResponse['status'] == 'success') {
            if (images.length > 0) {
              formData.append('formName', 'imagesupload');
              formData.append('itemid', getResponse['itemid']);
              $.ajax({
                type: 'POST',
                url: 'action/workorder-action.php',
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function (getResponseImage) {
                  preloaderClose();
                  $('.success-notification').css('display', 'block');
                  var notifyDiv = '';
                  notifyDiv += '<p style="font-size: 15px;"><span class="mif-done_all mif-lg"></span> Workorder created successfully.<p>';
                  notifyDiv += '<div class="d-flex flex-justify-between">';
                  notifyDiv += '<a href="workorder.php?page=details&id=' + getResponse['masterid'] + '" target="_blank" class="image-button dark fg-white" style="height:30px;"><span class="mif-eye icon" style="height: 30px; line-height: 30px; font-size: .9rem; width: 32px;"></span><span class="caption">View Detials</span></a>';
                  if (getType == 'publish') {
                    notifyDiv += '<a href="reports/workorder/' + getResponse['masterid'] + '" target="_blank" class="image-button info" style="height:30px;"><span class="mif-printer icon" style="height: 30px; line-height: 30px; font-size: .9rem; width: 32px;"></span><span class="caption">Print Now</span></a>';
                  }
                  notifyDiv += '<a href="workorder.php?page=create-new" class="image-button success" style="height:30px;"><span class="mif-plus icon" style="height: 30px; line-height: 30px; font-size: .9rem; width: 32px;"></span><span class="caption">Create New</span></a>';
                  notifyDiv += '</div>';
                  accessoriesNotify(notifyDiv, 'success', 400);
                }
              });
            } else {
              preloaderClose();
              $('.success-notification').css('display', 'block');
              var notifyDiv = '';
              notifyDiv += '<p style="font-size: 15px;"><span class="mif-done_all mif-lg"></span> Workorder created successfully.<p>';
              notifyDiv += '<div class="d-flex flex-justify-between">';
              notifyDiv += '<a href="workorder.php?page=details&id=' + getResponse['masterid'] + '" target="_blank" class="image-button dark fg-white" style="height: 30px;"><span class="mif-eye icon" style="height: 30px; line-height: 30px; font-size: .9rem; width: 32px;"></span><span class="caption">View Detials</span></a>';
              if (getType == 'publish') {
                notifyDiv += '<a href="reports/workorder/' + getResponse['masterid'] + '" target="_blank" class="image-button info" style="height: 30px;"><span class="mif-printer icon" style="height: 30px; line-height: 30px; font-size: .9rem; width: 32px;"></span><span class="caption">Print Now</span></a>';
              }
              notifyDiv += '<a href="workorder.php?page=create-new" class="image-button success" style="height: 30px;"><span class="mif-plus icon" style="height: 30px; line-height: 30px; font-size: .9rem; width: 32px;"></span><span class="caption">Create New</span></a>';
              notifyDiv += '</div>';
              accessoriesNotify(notifyDiv, 'success', 400);
            }
          } else if (getResponse['status'] == 'csrfmissing') {
            preloaderClose();
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! CSRF Token verification faild. Refresh your browser and try again.<p>', 'alert');
          } else if (getResponse['status'] == 'failed') {
            preloaderClose();
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Data insertion failure. Try again or contact for developer support.<p>', 'alert');
          } else {
            preloaderClose();
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Something went wrong! Refresh your browser and try again.<p>', 'alert');
          }
        }
      });
    }
  });

  $('.workorder-update').on('click', function (evt) {
    evt.preventDefault();
    evt.stopImmediatePropagation();
    var formData = new FormData();
    var getType = $(this).data('type').toLowerCase();
    preloaderStart();
    $('.success-notification').css('display', 'none');
    var workOrderMaster = [], workOrderItems = [], workOrderItemsData = [], customColumns = [], err = [], images = [];
    $('.invalid_feedback').css('display', 'none');
    if ($('.appended-input').length > 0) {
      $('.appended-input').each(function (index, el) {
        $(this).parent().html($(this).val());
      });
    }
    $('.required-field').each(function (index) {
      if ($(this).val() == 0 && $(this).attr('value') != undefined) {
        err.push('error');
        $(this).next('span').css('display', 'block');
      } else if ($(this).find('input').val() == '') {
        err.push('error');
        $(this).closest('.form-group').find('.invalid_feedback').css('display', 'block');
      } else if ($(this).find('textarea').val() == '') {
        err.push('error');
        $(this).closest('.form-group').find('.invalid_feedback').css('display', 'block');
      }
    });
    $('.maingrid').each(function (index) {
      if ($(this).find('.grand-totalqty-input').val() < 0) {
        err.push('error');
        $(this).find('.itemqty-errors span').css('display', 'block');
      }
    });
    if (err.length > 0) {
      preloaderClose();
      accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! One or more error(s) found.<p>', 'alert');
    } else {
      var garmentsQtyMain = [];
      $('.maingrid').each(function (index) {
        if ($(this).find('.garmentsgrandtotal').length == 0) {
          var garmentsQtyGrand = 'N/A';
        } else {
          var garmentsQtyGrand = $(this).find('.garmentsgrandtotal').val();
        }
        garmentsQtyMain.push(garmentsQtyGrand);
      });
      var block = '';
      var blockArr = [];
      if ($('.vordernumberorfklnumber input').val().toLowerCase() == 'block' || $('.vordernumberorfklnumber input').val().toLowerCase() == 'store') {
        if ($('.input-ordernumber-type select').val() == 'Order number') {
          blockArr.push($('.fklno-input').val());
        } else {
          blockArr.push($('.ordernumber-input').val());
        }
        blockArr.push($('.stylename-input').val());
        blockArr.push($('.buyername-input').val());
        blockArr.push($('.season-input').val());
        blockArr.push($('.dept-input').val());
        blockArr.push($('.kimble-input').val());
      }
      block = blockArr.join("##");
      workOrderMaster.push({
        'NID': $('.masterid').val(),
        'VCREATEDAT': $('.createdat').val(),
        'VCREATEDUSER': $('.createduser').val(),
        'VTODATE': $('.todate input').val(),
        'VPONUMBER': $('.ponumber').val(),
        'VISSUE': $('.issuenumber').val(),
        'VORDERNUMBERORFKLNUMBER': $('.vordernumberorfklnumber input').val(),
        'VTYPE': $('.input-ordernumber-type select').val(),
        'NSUPLLIERID': $('.suppliername select').val(),
        'VATTN': $('.attnetion-name input').val(),
        'VFORM': $('.form-name input').val(),
        'VDELIVERYDATE': $('.deliverydate input').val(),
        'VGARMENTSQTY': unique(garmentsQtyMain).join(', '),
        'VORDERDETAILS': $('.order-detils textarea').val(),
        'VEXTRANOTES': $('.extranotes textarea').val(),
        'VSTATUS': getType,
        'VBLOCKORDERINFO': block
      });

      $('.maingrid').each(function (indexmain) {
        var ordernumber = '', pnnumber = '', addition = '', convertion = '', garmetsqty = 0, garmentsqtywithextra = 0, columnname = '', columnsTempArr = [],
          itemDataTempArr = {}, customDataTempArr = {}, customData = [], sizeWiseQtyTempArr = {}, sizeWiseQtyData = {}, itemsData = {}, gridtype = '', sizename = [], settingData = {};
        var goodsId = $(this).find('.items-name').data('itemid');
        if ($(this).find('.order-no-input').length > 0) {
          ordernumber = $(this).find('.order-no-input').val();
        }
        if ($(this).find('.pn-no-input').length > 0) {
          pnnumber = $(this).find('.pn-no-input').val();
        }
        if ($(this).find('.symbol-input').length > 0) {
          $.each($(this).find('.symbol-input input')[0].files, function (indeximg, val) {
            images.push('img');
            formData.append('file-' + indeximg + '-' + indexmain, val);
          });
        }
        //$(this).find('')
        if ($(this).find('.color-size-wise-qty' + goodsId + '-header').length > 0 || $(this).find('.kimball-color-size-wise-qty' + goodsId + '-header').length > 0 || $(this).find('.garments-qty-size-' + goodsId + '-header').length > 0) {
          gridtype = 'colornsize';
        } else {
          gridtype = 'straight';
        }
        $(this).find('th').each(function (index) {
          if ($(this).hasClass('items-header')) {

          } else if ($(this).hasClass('pn-no-' + goodsId + '-header')) {

          } else if ($(this).hasClass('order-no-' + goodsId + '-header')) {

          } else if ($(this).hasClass('symbol' + goodsId + '-header')) {

          } else if ($(this).hasClass('colorsizeqty-header')) {
            $(this).find('.csize-header').each(function () {
              sizename.push($(this).text());
            });
            garmetsqty = $(this).closest('.maingrid').find('.garmentsgrandtotal').val();
          } else if ($(this).hasClass('color-wise-qty' + goodsId + '-qty-header')) {
            garmetsqty = $(this).closest('.maingrid').find('.garmentsgrandtotal').val();
          } else if ($(this).hasClass('size-wise-qty' + goodsId + '-qty-header')) {
            garmetsqty = $(this).closest('.maingrid').find('.garmentsgrandtotal').val();
          } else if ($(this).hasClass('kimball-color-wise-qty' + goodsId + '-qty-header')) {
            garmetsqty = $(this).closest('.maingrid').find('.garmentsgrandtotal').val();
          } else if ($(this).hasClass('garments-qty' + goodsId + '-qty-header')) {
            garmetsqty = $(this).closest('.maingrid').find('.garmentsgrandtotal').val();
          } else if ($(this).hasClass('garments-qty-size-' + goodsId + '-header')) {
            garmetsqty = $(this).closest('.maingrid').find('.garmentsgrandtotal').val();
          } else if ($(this).hasClass('addition' + goodsId + '-header')) {
            addition = $(this).find('.addition-columnname').val();
            garmentsqtywithextra = $(this).closest('.maingrid').find('.garmentsextragrandtotal').val();
          } else if ($(this).hasClass('converter' + goodsId + '-header')) {
            convertion = $(this).data('columnname');
          } else if ($(this).hasClass('totalqty-header')) {

          } else if ($(this).hasClass('reamrks-header')) {

          } else {
            columnsTempArr.push($(this).data('columnname'));
          }
        });
        $(this).find('.data-row').each(function (indexOut) {
          var ownRow = $(this);
          $(this).find('td').each(function (indexInner) {
            if ($(this).hasClass('totalqty')) {
              itemsData['NROWTOTALQTY'] = $(this).find('.row-totalqty-input').val();
            } else if ($(this).hasClass('items-name')) {

            } else if ($(this).hasClass('pn-no-cell')) {

            } else if ($(this).hasClass('order-no-cell')) {

            } else if ($(this).hasClass('symbol-cell')) {
              //Work start here..            
            } else if ($(this).hasClass('color-wise-qty' + goodsId + '-qty')) {
              itemsData['NROWGARMENTSQTY'] = $(this).find('.garments-qty-input').val();
            } else if ($(this).hasClass('size-wise-qty' + goodsId + '-qty')) {
              itemsData['NROWGARMENTSQTY'] = $(this).find('.garments-qty-input').val();
            } else if ($(this).hasClass('kimball-color-wise-qty' + goodsId + '-qty')) {
              itemsData['NROWGARMENTSQTY'] = $(this).find('.garments-qty-input').val();
            } else if ($(this).hasClass('garments-qty' + goodsId + '-qty')) {
              itemsData['NROWGARMENTSQTY'] = $(this).find('.garments-qty-input').val();
            } else if ($(this).hasClass('addition' + goodsId)) {
              itemsData['NROWGARMENTSQTYWITHEXTRA'] = $(this).find('.row-garmentsqtyextra-input').val();
            } else if ($(this).hasClass('converter' + goodsId)) {
              itemsData['NCONVERTERQTY'] = $(this).find('.row-convertion-input').val();
            } else if ($(this).hasClass('color-wise-qty' + goodsId)) {
              if ($(this).hasClass('color-celll-manual')) {
                customData.push($(this).find('.data-content').text());
              } else if ($(this).hasClass('color-celll-fixed')) {
                customData.push($(this).text());
              }
            } else if ($(this).hasClass('grmnts-color' + goodsId)) {
              customData.push($(this).find('.data-content').text());
            } else if ($(this).hasClass('size-wise-qty' + goodsId)) {
              if ($(this).hasClass('size-celll-manual')) {
                customData.push($(this).find('.data-content').text());
              } else if ($(this).hasClass('size-celll-fixed')) {
                customData.push($(this).text());
              }
            } else if ($(this).hasClass('size-name' + goodsId)) {
              customData.push($(this).find('.data-content').text());
            } else if ($(this).hasClass('kimball-color-wise-qty' + goodsId)) {
              if ($(this).hasClass('color-celll-manual')) {
                customData.push($(this).find('.data-content').text());
              } else if ($(this).hasClass('color-celll-fixed')) {
                customData.push($(this).text());
              } else if ($(this).hasClass('kimball-cell')) {
                customData.push($(this).text());
              } else if ($(this).hasClass('lot-cell')) {
                customData.push($(this).text());
              }
            } else if ($(this).hasClass('color-size-wise-qty' + goodsId)) {
              if ($(this).hasClass('color-celll-manual')) {
                customData.push($(this).find('.data-content').text());
              } else if ($(this).hasClass('color-celll-fixed')) {
                customData.push($(this).text());
              } else if ($(this).hasClass('colorsizeqty')) {
                $(this).find('.csize-input').each(function () {
                  var sizeKey = "size-" + $(this).data('sizename').toString();
                  sizeWiseQtyData[sizeKey] = $(this).val();
                });
              }
            } else if ($(this).hasClass('garments-qty-size-' + goodsId)) {
              if ($(this).hasClass('colorsizeqty')) {
                $(this).find('.csize-input').each(function () {
                  var sizeKey = "size-" + $(this).data('sizename').toString();
                  sizeWiseQtyData[sizeKey] = $(this).val();
                });
              }
            } else if ($(this).hasClass('kimball-color-size-wise-qty' + goodsId)) {
              if ($(this).hasClass('color-celll-manual')) {
                customData.push($(this).find('.data-content').text());
              } else if ($(this).hasClass('color-celll-fixed')) {
                customData.push($(this).text());
              } else if ($(this).hasClass('destination-celll')) {
                customData.push($(this).find('.custom-column-value').val());
              } else if ($(this).hasClass('country-celll')) {
                customData.push($(this).find('.custom-column-value').val());
              } else if ($(this).hasClass('kimball-cell')) {
                customData.push($(this).text());
              } else if ($(this).hasClass('lot-cell')) {
                customData.push($(this).text());
              } else if ($(this).hasClass('colorsizeqty')) {
                $(this).find('.csize-input').each(function () {
                  var sizeKey = "size-" + $(this).data('sizename').toString();
                  sizeWiseQtyData[sizeKey] = $(this).val();
                });
              }
            } else if ($(this).hasClass('grmnts-color-kimball-lot' + goodsId)) {
              if ($(this).hasClass('color-celll-manual')) {
                customData.push($(this).find('.data-content').text());
              } else if ($(this).hasClass('kimball-cell')) {
                customData.push($(this).text());
              } else if ($(this).hasClass('lot-cell')) {
                customData.push($(this).text());
              }
            } else if ($(this).hasClass('remarks')) {
              itemsData['VREMARKS'] = $(this).find('textarea[name=vremarks]').val();
            } else {
              customData.push($.isArray($(this).find('.custom-column-value').val()) ? $(this).find('.custom-column-value').val().join(',') : $(this).find('.custom-column-value').val());
            }
          });
          itemDataTempArr[indexOut] = itemsData;
          itemsData = {};
          customDataTempArr[indexOut] = customData;
          customData = [];
          sizeWiseQtyTempArr[indexOut] = sizeWiseQtyData;
          sizeWiseQtyData = {};
        });
        var parameterStore = [];
        $(this).parent('div').find('.searchordertable .parameters').each(function (indexsearchtable) {
          if ($(this).find('input').is(':checked')) {
            parameterStore.push($(this).find('input').val());
          }
        });

        settingData['VCHECKEDOPTIONS'] = parameterStore.join(',');
        settingData['NCONVERSIONVALUE'] = 0;
        settingData['VCONVERTIOMNTYPE'] = '';
        if ($(this).find('.converter' + goodsId).length != undefined) {
          settingData['NCONVERSIONVALUE'] = $(this).find('.converter' + goodsId + ' .row-convertion-input').last().data('convertionval');
          settingData['VCONVERTIOMNTYPE'] = $(this).find('.converter' + goodsId + ' .row-convertion-input').last().data('calinputtype');
        }
        if ($(this).find('.addition' + goodsId).length != undefined) {
          settingData['NADDITIONVALUE'] = $(this).find('.addition' + goodsId + ' .row-garmentsqtyextra-input').last().data('additionval');
          settingData['VADDITIONTYPE'] = $(this).find('.addition' + goodsId + ' .row-garmentsqtyextra-input').last().data('additiontype');
        }
        if ($(this).hasClass('excel-grid')) {
          settingData['NEXCELUPLOAD'] = 1;
        } else {
          settingData['NEXCELUPLOAD'] = 0;
        }
        settingData['VDATAFILLTYPE'] = '';
        // alert($('.addrowdisabler').val());
        // settingData['NMAXALLOWEDROW'] = $('.addrowdisabler').val();
        if ($(this).hasClass('fixed-data')) {
          settingData['VDATAFILLTYPE'] = 'fixed-data';
        }
        if ($(this).hasClass('manual-data')) {
          settingData['VDATAFILLTYPE'] = 'manual-data';
        }


        workOrderItems.push({
          'NID': $(this).data('itemid'),
          'NTOTALQTY': $(this).find('.grand-totalqty-input').val(),
          'NGOODSID': goodsId,
          'VORDERNUMBER': ordernumber,
          'VPNNUMBER': pnnumber,
          'VCOLUMNNAME': columnsTempArr.join(',').replace(/,\s*$/, ""),
          'VQTYUNIT': $(this).find('.grandunit').text(),
          'NTOTALGARMENTSQTY': garmetsqty,
          'NTOTALGARMENTSQTYWITHEXTRA': garmentsqtywithextra,
          'VADDITION': addition,
          'VCONVERTION': convertion,
          'VGRIDTYPE': gridtype,
          'VSIZENAME': sizename.join(','),
          'itemData': itemDataTempArr,
          'customData': customDataTempArr,
          'sizeWiseQty': sizeWiseQtyTempArr,
          'settingData': settingData
        });
        itemDataTempArr = {};
        customDataTempArr = {};
        sizeWiseQtyTempArr = {};
      });

      $.ajax({
        type: 'POST',
        url: 'action/workorder-action.php',
        dataType: 'json',
        data: { 'formName': 'workorderupdate', 'csrf': $('.csrf').val(), 'masterdata': workOrderMaster, 'itemsdata': workOrderItems },
        success: function (getResponse) {
          if (getResponse['status'] == 'success') {
            if (images.length > 0) {
              formData.append('formName', 'imagesupload');
              formData.append('itemid', getResponse['itemid']);
              $.ajax({
                type: 'POST',
                url: 'action/workorder-action.php',
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function (getResponseImage) {
                  preloaderClose();
                  $('.success-notification').css('display', 'block');
                  var notifyDiv = '';
                  notifyDiv += '<p style="font-size: 15px;"><span class="mif-done_all mif-lg"></span> Workorder updated successfully.<p>';
                  notifyDiv += '<div class="d-flex flex-justify-between">';
                  notifyDiv += '<a href="workorder.php?page=details&id=' + getResponse['masterid'] + '" target="_blank" class="image-button dark fg-white" style="height:30px;"><span class="mif-eye icon" style="height: 30px; line-height: 30px; font-size: .9rem; width: 32px;"></span><span class="caption">View Detials</span></a>';
                  if (getType == 'publish') {
                    notifyDiv += '<a href="reports/workorder/' + getResponse['masterid'] + '" target="_blank" class="image-button info" style="height:30px;"><span class="mif-printer icon" style="height: 30px; line-height: 30px; font-size: .9rem; width: 32px;"></span><span class="caption">Print Now</span></a>';
                  }
                  notifyDiv += '<a href="workorder.php?page=create-new" class="image-button success" style="height:30px;"><span class="mif-pencil icon" style="height: 30px; line-height: 30px; font-size: .9rem; width: 32px;"></span><span class="caption">Edit Page</span></a>';
                  notifyDiv += '</div>';
                  accessoriesNotify(notifyDiv, 'success', 400);
                }
              });
            } else {
              preloaderClose();
              $('.success-notification').css('display', 'block');
              var notifyDiv = '';
              notifyDiv += '<p style="font-size: 15px;"><span class="mif-done_all mif-lg"></span> Workorder updated successfully.<p>';
              notifyDiv += '<div class="d-flex flex-justify-between">';
              notifyDiv += '<a href="workorder.php?page=details&id=' + getResponse['masterid'] + '" target="_blank" class="image-button dark fg-white" style="height: 30px;"><span class="mif-eye icon" style="height: 30px; line-height: 30px; font-size: .9rem; width: 32px;"></span><span class="caption">View Detials</span></a>';
              if (getType == 'publish') {
                notifyDiv += '<a href="reports/workorder/' + getResponse['masterid'] + '" target="_blank" class="image-button info" style="height: 30px;"><span class="mif-printer icon" style="height: 30px; line-height: 30px; font-size: .9rem; width: 32px;"></span><span class="caption">Print Now</span></a>';
              }
              notifyDiv += '<a href="workorder.php?page=edit&id=' + getResponse['masterid'] + '" class="image-button success" style="height: 30px;"><span class="mif-pencil icon" style="height: 30px; line-height: 30px; font-size: .9rem; width: 32px;"></span><span class="caption">Edit Page</span></a>';
              notifyDiv += '</div>';
              accessoriesNotify(notifyDiv, 'success', 400);
            }
          } else if (getResponse['status'] == 'csrfmissing') {
            preloaderClose();
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! CSRF Token verification faild. Refresh your browser and try again.<p>', 'alert');
          } else if (getResponse['status'] == 'failed') {
            preloaderClose();
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Data insertion failure. Try again or contact for developer support.<p>', 'alert');
          } else {
            preloaderClose();
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Something went wrong! Refresh your browser and try again.<p>', 'alert');
          }
        }
      });
    }
  });

  $('.workorder-newissue').on('click', function (evt) {
    evt.preventDefault();
    evt.stopImmediatePropagation();
    var formData = new FormData();
    var getType = $(this).data('type').toLowerCase();
    preloaderStart();
    $('.success-notification').css('display', 'none');
    var workOrderMaster = [], workOrderItems = [], workOrderItemsData = [], customColumns = [], err = [], images = [];
    $('.invalid_feedback').css('display', 'none');
    if ($('.appended-input').length > 0) {
      $('.appended-input').each(function (index, el) {
        $(this).parent().html($(this).val());
      });
    }
    $('.required-field').each(function (index) {
      if ($(this).val() == 0 && $(this).attr('value') != undefined) {
        err.push('error');
        $(this).next('span').css('display', 'block');
      } else if ($(this).find('input').val() == '') {
        err.push('error');
        $(this).closest('.form-group').find('.invalid_feedback').css('display', 'block');
      } else if ($(this).find('textarea').val() == '') {
        err.push('error');
        $(this).closest('.form-group').find('.invalid_feedback').css('display', 'block');
      }
    });
    $('.maingrid').each(function (index) {
      if ($(this).find('.grand-totalqty-input').val() < 0) {
        err.push('error');
        $(this).find('.itemqty-errors span').css('display', 'block');
      }
    });
    if (err.length > 0) {
      preloaderClose();
      accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! One or more error(s) found.<p>', 'alert');
    } else {
      var garmentsQtyMain = [];
      $('.maingrid').each(function (index) {
        var qtyUnit = $(this).find('.grandunit').text().toLowerCase();
        if ($(this).find('.garmentsgrandtotal').length == 0) {
          var garmentsQtyGrand = 'N/A';
        } else {
          var garmentsQtyGrand = $(this).find('.garmentsgrandtotal').val();
        }
        garmentsQtyMain.push(garmentsQtyGrand);
      });
      var block = '';
      var blockArr = [];
      if ($('.vordernumberorfklnumber input').val().toLowerCase() == 'block' || $('.vordernumberorfklnumber input').val().toLowerCase() == 'store') {
        if ($('.input-ordernumber-type select').val() == 'Order number') {
          blockArr.push($('.fklno-input').val());
        } else {
          blockArr.push($('.ordernumber-input').val());
        }
        blockArr.push($('.stylename-input').val());
        blockArr.push($('.buyername-input').val());
        blockArr.push($('.season-input').val());
        blockArr.push($('.dept-input').val());
        blockArr.push($('.kimble-input').val());
      }
      block = blockArr.join("##");
      workOrderMaster.push({
        'masterid': $('.masterid').val(),
        'VTODATE': $('.todate input').val(),
        'VPONUMBER': $('.ponumber').val(),
        'VISSUE': $('.issuenumber').val(),
        'VORDERNUMBERORFKLNUMBER': $('.vordernumberorfklnumber input').val(),
        'VTYPE': $('.input-ordernumber-type select').val(),
        'NSUPLLIERID': $('.suppliername select').val(),
        'VATTN': $('.attnetion-name input').val(),
        'VFORM': $('.form-name input').val(),
        'VDELIVERYDATE': $('.deliverydate input').val(),
        'VGARMENTSQTY': unique(garmentsQtyMain).join(', '),
        'VORDERDETAILS': $('.order-detils textarea').val(),
        'VEXTRANOTES': $('.extranotes textarea').val(),
        'VSTATUS': getType,
        'VBLOCKORDERINFO': block
      });

      $('.maingrid').each(function (indexmain) {
        var ordernumber = '', pnnumber = '', addition = '', convertion = '', garmetsqty = 0, garmentsqtywithextra = 0, columnname = '', columnsTempArr = [],
          itemDataTempArr = {}, customDataTempArr = {}, customData = [], sizeWiseQtyTempArr = {}, sizeWiseQtyData = {}, itemsData = {}, gridtype = '', sizename = [], settingData = {};
        var goodsId = $(this).find('.items-name').data('itemid');
        if ($(this).find('.order-no-input').length > 0) {
          ordernumber = $(this).find('.order-no-input').val();
        }
        if ($(this).find('.pn-no-input').length > 0) {
          pnnumber = $(this).find('.pn-no-input').val();
        }
        if ($(this).find('.symbol-input').length > 0) {
          $.each($(this).find('.symbol-input input')[0].files, function (indeximg, val) {
            images.push('img');
            formData.append('file-' + indeximg + '-' + indexmain, val);
          });
        }
        //$(this).find('')
        if ($(this).find('.color-size-wise-qty' + goodsId + '-header').length > 0 || $(this).find('.kimball-color-size-wise-qty' + goodsId + '-header').length > 0 || $(this).find('.garments-qty-size-' + goodsId + '-header').length > 0) {
          gridtype = 'colornsize';
        } else {
          gridtype = 'straight';
        }
        $(this).find('th').each(function (index) {
          if ($(this).hasClass('items-header')) {

          } else if ($(this).hasClass('pn-no-' + goodsId + '-header')) {

          } else if ($(this).hasClass('order-no-' + goodsId + '-header')) {

          } else if ($(this).hasClass('symbol' + goodsId + '-header')) {

          } else if ($(this).hasClass('colorsizeqty-header')) {
            $(this).find('.csize-header').each(function () {
              sizename.push($(this).text());
            });
            garmetsqty = $(this).closest('.maingrid').find('.garmentsgrandtotal').val();
          } else if ($(this).hasClass('color-wise-qty' + goodsId + '-qty-header')) {
            garmetsqty = $(this).closest('.maingrid').find('.garmentsgrandtotal').val();
          } else if ($(this).hasClass('size-wise-qty' + goodsId + '-qty-header')) {
            garmetsqty = $(this).closest('.maingrid').find('.garmentsgrandtotal').val();
          } else if ($(this).hasClass('kimball-color-wise-qty' + goodsId + '-qty-header')) {
            garmetsqty = $(this).closest('.maingrid').find('.garmentsgrandtotal').val();
          } else if ($(this).hasClass('garments-qty' + goodsId + '-qty-header')) {
            garmetsqty = $(this).closest('.maingrid').find('.garmentsgrandtotal').val();
          } else if ($(this).hasClass('garments-qty-size-' + goodsId + '-header')) {
            garmetsqty = $(this).closest('.maingrid').find('.garmentsgrandtotal').val();
          } else if ($(this).hasClass('addition' + goodsId + '-header')) {
            addition = $(this).find('.addition-columnname').val();
            garmentsqtywithextra = $(this).closest('.maingrid').find('.garmentsextragrandtotal').val();
          } else if ($(this).hasClass('converter' + goodsId + '-header')) {
            convertion = $(this).data('columnname');
          } else if ($(this).hasClass('totalqty-header')) {

          } else if ($(this).hasClass('reamrks-header')) {

          } else {
            columnsTempArr.push($(this).data('columnname'));
          }
        });
        $(this).find('.data-row').each(function (indexOut) {
          var ownRow = $(this);
          $(this).find('td').each(function (indexInner) {
            if ($(this).hasClass('totalqty')) {
              itemsData['NROWTOTALQTY'] = $(this).find('.row-totalqty-input').val();
            } else if ($(this).hasClass('items-name')) {

            } else if ($(this).hasClass('pn-no-cell')) {

            } else if ($(this).hasClass('order-no-cell')) {

            } else if ($(this).hasClass('symbol-cell')) {
              //Work start here..            
            } else if ($(this).hasClass('color-wise-qty' + goodsId + '-qty')) {
              itemsData['NROWGARMENTSQTY'] = $(this).find('.garments-qty-input').val();
            } else if ($(this).hasClass('size-wise-qty' + goodsId + '-qty')) {
              itemsData['NROWGARMENTSQTY'] = $(this).find('.garments-qty-input').val();
            } else if ($(this).hasClass('kimball-color-wise-qty' + goodsId + '-qty')) {
              itemsData['NROWGARMENTSQTY'] = $(this).find('.garments-qty-input').val();
            } else if ($(this).hasClass('garments-qty' + goodsId + '-qty')) {
              itemsData['NROWGARMENTSQTY'] = $(this).find('.garments-qty-input').val();
            } else if ($(this).hasClass('addition' + goodsId)) {
              itemsData['NROWGARMENTSQTYWITHEXTRA'] = $(this).find('.row-garmentsqtyextra-input').val();
            } else if ($(this).hasClass('converter' + goodsId)) {
              itemsData['NCONVERTERQTY'] = $(this).find('.row-convertion-input').val();
            } else if ($(this).hasClass('color-wise-qty' + goodsId)) {
              if ($(this).hasClass('color-celll-manual')) {
                customData.push($(this).find('.data-content').text());
              } else if ($(this).hasClass('color-celll-fixed')) {
                customData.push($(this).text());
              }
            } else if ($(this).hasClass('grmnts-color' + goodsId)) {
              customData.push($(this).find('.data-content').text());
            } else if ($(this).hasClass('size-wise-qty' + goodsId)) {
              if ($(this).hasClass('size-celll-manual')) {
                customData.push($(this).find('.data-content').text());
              } else if ($(this).hasClass('size-celll-fixed')) {
                customData.push($(this).text());
              }
            } else if ($(this).hasClass('size-name' + goodsId)) {
              customData.push($(this).find('.data-content').text());
            } else if ($(this).hasClass('kimball-color-wise-qty' + goodsId)) {
              if ($(this).hasClass('color-celll-manual')) {
                customData.push($(this).find('.data-content').text());
              } else if ($(this).hasClass('color-celll-fixed')) {
                customData.push($(this).text());
              } else if ($(this).hasClass('kimball-cell')) {
                customData.push($(this).text());
              } else if ($(this).hasClass('lot-cell')) {
                customData.push($(this).text());
              }
            } else if ($(this).hasClass('color-size-wise-qty' + goodsId)) {
              if ($(this).hasClass('color-celll-manual')) {
                customData.push($(this).find('.data-content').text());
              } else if ($(this).hasClass('color-celll-fixed')) {
                customData.push($(this).text());
              } else if ($(this).hasClass('colorsizeqty')) {
                $(this).find('.csize-input').each(function () {
                  var sizeKey = "size-" + $(this).data('sizename').toString();
                  sizeWiseQtyData[sizeKey] = $(this).val();
                });
              }
            } else if ($(this).hasClass('garments-qty-size-' + goodsId)) {
              if ($(this).hasClass('colorsizeqty')) {
                $(this).find('.csize-input').each(function () {
                  var sizeKey = "size-" + $(this).data('sizename').toString();
                  sizeWiseQtyData[sizeKey] = $(this).val();
                });
              }
            } else if ($(this).hasClass('kimball-color-size-wise-qty' + goodsId)) {
              if ($(this).hasClass('color-celll-manual')) {
                customData.push($(this).find('.data-content').text());
              } else if ($(this).hasClass('color-celll-fixed')) {
                customData.push($(this).text());
              } else if ($(this).hasClass('destination-celll')) {
                customData.push($(this).find('.custom-column-value').val());
              } else if ($(this).hasClass('country-celll')) {
                customData.push($(this).find('.custom-column-value').val());
              } else if ($(this).hasClass('kimball-cell')) {
                customData.push($(this).text());
              } else if ($(this).hasClass('lot-cell')) {
                customData.push($(this).text());
              } else if ($(this).hasClass('colorsizeqty')) {
                $(this).find('.csize-input').each(function () {
                  var sizeKey = "size-" + $(this).data('sizename').toString();
                  sizeWiseQtyData[sizeKey] = $(this).val();
                });
              }
            } else if ($(this).hasClass('grmnts-color-kimball-lot' + goodsId)) {
              if ($(this).hasClass('color-celll-manual')) {
                customData.push($(this).find('.data-content').text());
              } else if ($(this).hasClass('kimball-cell')) {
                customData.push($(this).text());
              } else if ($(this).hasClass('lot-cell')) {
                customData.push($(this).text());
              }
            } else if ($(this).hasClass('remarks')) {
              itemsData['VREMARKS'] = $(this).find('textarea[name=vremarks]').val();
            } else {
              customData.push($.isArray($(this).find('.custom-column-value').val()) ? $(this).find('.custom-column-value').val().join(',') : $(this).find('.custom-column-value').val());
            }
          });
          itemDataTempArr[indexOut] = itemsData;
          itemsData = {};
          customDataTempArr[indexOut] = customData;
          customData = [];
          sizeWiseQtyTempArr[indexOut] = sizeWiseQtyData;
          sizeWiseQtyData = {};
        });
        var parameterStore = [];
        $(this).parent('div').find('.searchordertable .parameters').each(function (indexsearchtable) {
          if ($(this).find('input').is(':checked')) {
            parameterStore.push($(this).find('input').val());
          }
        });

        settingData['VCHECKEDOPTIONS'] = parameterStore.join(',');
        settingData['NCONVERSIONVALUE'] = 0;
        settingData['VCONVERTIOMNTYPE'] = '';
        if ($(this).find('.converter' + goodsId).length != undefined) {
          settingData['NCONVERSIONVALUE'] = $(this).find('.converter' + goodsId + ' .row-convertion-input').last().data('convertionval');
          settingData['VCONVERTIOMNTYPE'] = $(this).find('.converter' + goodsId + ' .row-convertion-input').last().data('calinputtype');
        }
        if ($(this).find('.addition' + goodsId).length != undefined) {
          settingData['NADDITIONVALUE'] = $(this).find('.addition' + goodsId + ' .row-garmentsqtyextra-input').last().data('additionval');
          settingData['VADDITIONTYPE'] = $(this).find('.addition' + goodsId + ' .row-garmentsqtyextra-input').last().data('additiontype');
        }
        if ($(this).hasClass('excel-grid')) {
          settingData['NEXCELUPLOAD'] = 1;
        } else {
          settingData['NEXCELUPLOAD'] = 0;
        }
        settingData['VDATAFILLTYPE'] = '';
        // alert($('.addrowdisabler').val());
        // settingData['NMAXALLOWEDROW'] = $('.addrowdisabler').val();
        if ($(this).hasClass('fixed-data')) {
          settingData['VDATAFILLTYPE'] = 'fixed-data';
        }
        if ($(this).hasClass('manual-data')) {
          settingData['VDATAFILLTYPE'] = 'manual-data';
        }


        workOrderItems.push({
          'NTOTALQTY': $(this).find('.grand-totalqty-input').val(),
          'NGOODSID': goodsId,
          'VORDERNUMBER': ordernumber,
          'VPNNUMBER': pnnumber,
          'VCOLUMNNAME': columnsTempArr.join(',').replace(/,\s*$/, ""),
          'VQTYUNIT': $(this).find('.grandunit').text(),
          'NTOTALGARMENTSQTY': garmetsqty,
          'NTOTALGARMENTSQTYWITHEXTRA': garmentsqtywithextra,
          'VADDITION': addition,
          'VCONVERTION': convertion,
          'VGRIDTYPE': gridtype,
          'VSIZENAME': sizename.join(','),
          'itemData': itemDataTempArr,
          'customData': customDataTempArr,
          'sizeWiseQty': sizeWiseQtyTempArr,
          'settingData': settingData
        });
        itemDataTempArr = {};
        customDataTempArr = {};
        sizeWiseQtyTempArr = {};
      });

      $.ajax({
        type: 'POST',
        url: 'action/workorder-action.php',
        dataType: 'json',
        data: { 'formName': 'workordernewissue', 'csrf': $('.csrf').val(), 'masterdata': workOrderMaster, 'itemsdata': workOrderItems },
        success: function (getResponse) {
          if (getResponse['status'] == 'success') {
            if (images.length > 0) {
              formData.append('formName', 'imagesupload');
              formData.append('itemid', getResponse['itemid']);
              $.ajax({
                type: 'POST',
                url: 'action/workorder-action.php',
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function (getResponseImage) {
                  preloaderClose();
                  $('.success-notification').css('display', 'block');
                  var notifyDiv = '';
                  notifyDiv += '<p style="font-size: 15px;"><span class="mif-done_all mif-lg"></span> Workorder re-issued successfully.<p>';
                  notifyDiv += '<div class="d-flex flex-justify-between">';
                  notifyDiv += '<a href="workorder.php?page=details&id=' + getResponse['masterid'] + '" target="_blank" class="image-button dark fg-white" style="height:30px;"><span class="mif-eye icon" style="height: 30px; line-height: 30px; font-size: .9rem; width: 32px;"></span><span class="caption">View Detials</span></a>';
                  if (getType == 'publish') {
                    notifyDiv += '<a href="reports/workorder/' + getResponse['masterid'] + '" target="_blank" class="image-button info" style="height:30px;"><span class="mif-printer icon" style="height: 30px; line-height: 30px; font-size: .9rem; width: 32px;"></span><span class="caption">Print Now</span></a>';
                  }
                  notifyDiv += '<a href="workorder.php?page=create-new" class="image-button success" style="height:30px;"><span class="mif-plus icon" style="height: 30px; line-height: 30px; font-size: .9rem; width: 32px;"></span><span class="caption">Create New</span></a>';
                  notifyDiv += '</div>';
                  accessoriesNotify(notifyDiv, 'success', 400);
                }
              });
            } else {
              preloaderClose();
              $('.success-notification').css('display', 'block');
              var notifyDiv = '';
              notifyDiv += '<p style="font-size: 15px;"><span class="mif-done_all mif-lg"></span> Workorder re-issued successfully.<p>';
              notifyDiv += '<div class="d-flex flex-justify-between">';
              notifyDiv += '<a href="workorder.php?page=details&id=' + getResponse['masterid'] + '" target="_blank" class="image-button dark fg-white" style="height:30px;"><span class="mif-eye icon" style="height: 30px; line-height: 30px; font-size: .9rem; width: 32px;"></span><span class="caption">View Detials</span></a>';
              if (getType == 'publish') {
                notifyDiv += '<a href="reports/workorder/' + getResponse['masterid'] + '" target="_blank" class="image-button info" style="height:30px;"><span class="mif-printer icon" style="height: 30px; line-height: 30px; font-size: .9rem; width: 32px;"></span><span class="caption">Print Now</span></a>';
              }
              notifyDiv += '<a href="workorder.php?page=create-new" class="image-button success" style="height:30px;"><span class="mif-plus icon" style="height: 30px; line-height: 30px; font-size: .9rem; width: 32px;"></span><span class="caption">Create New</span></a>';
              notifyDiv += '</div>';
              accessoriesNotify(notifyDiv, 'success', 400);
            }
          } else if (getResponse['status'] == 'csrfmissing') {
            preloaderClose();
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! CSRF Token verification faild. Refresh your browser and try again.<p>', 'alert');
          } else if (getResponse['status'] == 'failed') {
            preloaderClose();
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Data insertion failure. Try again or contact for developer support.<p>', 'alert');
          } else {
            preloaderClose();
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Something went wrong! Refresh your browser and try again.<p>', 'alert');
          }
        }
      });
    }
  });



  $('.accessories-table-common').on('click', 'tbody tr', function (event) {
    event.stopPropagation();
    event.stopImmediatePropagation();
    if (window.event.ctrlKey) {
      if ($(this).hasClass('row-highlight')) {
        $(this).removeClass('row-highlight');
      } else {
        $(this).addClass('row-highlight');
      }
    } else {
      if ($(this).hasClass('row-highlight')) {
        $('.accessories-table-common').find('tr').removeClass('row-highlight');
      } else {
        $('.accessories-table-common').find('tr').removeClass('row-highlight');
        $(this).addClass('row-highlight');
      }
    }
  });
  // $('.accessories-table-common').on('hover', 'tr', function(){
  //     $('.accessories-table-common').find('tr').removeClass('row-highlight');
  //     $(this).addClass('row-highlight');
  //   }
  // });
  // Modified By SPN Start
  function presetData(type, item = null) {
    var getFKLNo = $('#fklno').text();
    var buyer = $.trim($('#buyername').text());
    var data;
    $.ajax({
      type: 'POST',
      url: 'action/workorder-action.php',
      dataType: 'json',
      async: false,
      data: { 'formName': 'presetdata', 'csrf': $('.csrf').val(), 'fklNumber': getFKLNo, 'preSetOpton': type, 'buyer': buyer, 'itemName': item },
      success: function (getResponse) {
        data = getResponse;
      }
    });
    return data;
  }
  // Modified By SPN End
  $('.login-form').on('submit', function (event) {
    event.preventDefault();
    var form = $(this);
    var error = [];
    $('.invalid_feedback').css('display', 'none');
    form.find('.faildlogin').empty();
    form.find('input').each(function (index, el) {
      if ($(this).val().length == 0) {
        error.push('1');
        $(this).closest('.form-group').find('.invalid_feedback').css('display', 'block');
      }
    });
    if (error.length == 0) {
      preloaderStart();
      var formData = form.serializeArray();
      $.ajax({
        type: 'POST',
        url: 'action/login-action.php',
        dataType: 'json',
        data: formData,
        success: function (getloginresponse) {
          preloaderClose();
          if (getloginresponse['loginStats'] == 'success') {
            window.location.href = getloginresponse['redirecturl'];
          } else if (getloginresponse['loginStats'] == 'failed') {
            if (getloginresponse['errorval'] == 'not permit') {
              form.find('.faildlogin').append('<p style="padding: 10px;">Oops! You do not have permission to use this module.</p>');
              form.addClass("ani-ring");
              setTimeout(function () {
                form.removeClass("ani-ring");
              }, 2000);
            } else {
              form.find('.faildlogin').append('<p style="padding: 10px;">Invalid FKLID or Password !</p>');
              form.addClass("ani-ring");
              setTimeout(function () {
                form.removeClass("ani-ring");
              }, 2000);
            }
          }
        }
      });
    } else {
      form.addClass("ani-ring");
      setTimeout(function () {
        form.removeClass("ani-ring");
      }, 2000);
    }
  });

  $('.userfinder-input').on('keypress', function (evt) {
    var keycode = (evt.keyCode ? evt.keyCode : evt.which);
    if (keycode == '13') {
      var fklid = $(this).val();
      preloaderStart();
      $.ajax({
        type: 'POST',
        url: 'action/users-action.php',
        dataType: 'json',
        data: { 'formName': 'userfinder', 'fklid': fklid },
        success: function (getresponse) {
          preloaderClose();
          if (getresponse['status'] == 'success') {
            $('.user-finder').text(getresponse['name']);
            $('.poprefix').prop('disabled', false);
            $('.userrole').prop('disabled', false);
            $('.manager-finder-input').prop('disabled', false);
            $('.subordinate-finder-input').prop('disabled', false);
            $('.subordinate-finder-input-2').prop('disabled', false);
            $('.permission-table').css('display', '');
          } else {
            $('.user-finder').text('');
            $('.poprefix').prop('disabled', true);
            $('.userrole').prop('disabled', true);
            $('.manager-finder-input').prop('disabled', true);
            $('.manager-finder').text('');
            $('.subordinate-finder').text('');
            $('.subordinate-finder-2').text('');
            $('.subordinate-finder-input').prop('disabled', true);
            $('.subordinate-finder-input-2').prop('disabled', true);
            $('.poprefix').val('');
            $('.permission-table').css('display', 'none');
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Invalid FKL id.<p>', 'alert');
          }
        }
      });
    }
  });

  $('.userfinder-input').on('blur', function (evt) {
    var fklid = $(this).val();
    preloaderStart();
    $.ajax({
      type: 'POST',
      url: 'action/users-action.php',
      dataType: 'json',
      data: { 'formName': 'userfinder', 'fklid': fklid },
      success: function (getresponse) {
        preloaderClose();
        if (getresponse['status'] == 'success') {
          $('.user-finder').text(getresponse['name']);
          $('.poprefix').prop('disabled', false);
          $('.userrole').prop('disabled', false);
          $('.permission-table').css('display', '');
          $('.manager-finder-input').prop('disabled', false);
          $('.subordinate-finder-input').prop('disabled', false);
          $('.subordinate-finder-input-2').prop('disabled', false);
        } else {
          $('.user-finder').text('');
          $('.poprefix').prop('disabled', true);
          $('.userrole').prop('disabled', true);
          $('.manager-finder-input').prop('disabled', true);
          $('.subordinate-finder-input').prop('disabled', true);
          $('.subordinate-finder-input-2').prop('disabled', true);
          $('.manager-finder').text('');
          $('.subordinate-finder').text('');
          $('.subordinate-finder-2').text('');
          $('.poprefix').val('');
          $('.permission-table').css('display', 'none');
          accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Invalid FKL id.<p>', 'alert');
        }
      }
    });
  });

  $('.manager-finder-input').on('blur', function (evt) {
    var fklid = $(this).val();
    var ownObj = $(this);
    preloaderStart();
    $.ajax({
      type: 'POST',
      url: 'action/users-action.php',
      dataType: 'json',
      data: { 'formName': 'managerfinder', 'fklid': fklid },
      success: function (getresponse) {
        preloaderClose();
        if (getresponse['status'] == 'success') {
          $('.manager-finder').text(getresponse['name']);
        } else {
          $('.manager-finder').text('');
          ownObj.val('');
          accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Invalid manager id.<p>', 'alert');
        }
      }
    });
  });

  $('.manager-finder-input').on('keypress', function (evt) {
    var keycode = (evt.keyCode ? evt.keyCode : evt.which);
    if (keycode == '13') {
      var fklid = $(this).val();
      var ownObj = $(this);
      preloaderStart();
      $.ajax({
        type: 'POST',
        url: 'action/users-action.php',
        dataType: 'json',
        data: { 'formName': 'managerfinder', 'fklid': fklid },
        success: function (getresponse) {
          preloaderClose();
          if (getresponse['status'] == 'success') {
            $('.manager-finder').text(getresponse['name']);
          } else {
            $('.manager-finder').text('');
            $(this).val('');
            ownObj.val('');
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Invalid manager id.<p>', 'alert');
          }
        }
      });
    }
  });

  $('.subordinate-finder-input').on('keypress', function (evt) {
    var keycode = (evt.keyCode ? evt.keyCode : evt.which);
    if (keycode == '13') {
      var fklid = $(this).val();
      preloaderStart();
      $.ajax({
        type: 'POST',
        url: 'action/users-action.php',
        dataType: 'json',
        data: { 'formName': 'subordinatefinder', 'fklid': fklid },
        success: function (getresponse) {
          preloaderClose();
          if (getresponse['status'] == 'success') {
            $('.subordinate-finder').text(getresponse['name']);
            $('.poprefix').prop('disabled', false);
            $('.userrole').prop('disabled', false);
            $('.manager-finder-input').prop('disabled', false);
            $('.subordinate-finder-input').prop('disabled', false);
            $('.subordinate-finder-input-2').prop('disabled', false);
            $('.permission-table').css('display', '');
          } else {
            $('.subordinate-finder').text('');
            $('.poprefix').prop('disabled', true);
            $('.userrole').prop('disabled', true);
            $('.manager-finder-input').prop('disabled', true);
            $('.subordinate-finder-input').prop('disabled', true);
            $('.subordinate-finder-input-2').prop('disabled', true);
            $('.manager-finder').text('');
            $('.subordinate-finder').text('');
            $('.subordinate-finder-2').text('');
            $('.poprefix').val('');
            $('.permission-table').css('display', 'none');
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Invalid FKL id.<p>', 'alert');
          }
        }
      });
    }
  });

  $('.subordinate-finder-input').on('blur', function (evt) {
    var fklid = $(this).val();
    preloaderStart();
    $.ajax({
      type: 'POST',
      url: 'action/users-action.php',
      dataType: 'json',
      data: { 'formName': 'subordinatefinder', 'fklid': fklid },
      success: function (getresponse) {
        preloaderClose();
        if (getresponse['status'] == 'success') {
          $('.subordinate-finder').text(getresponse['name']);
          $('.poprefix').prop('disabled', false);
          $('.userrole').prop('disabled', false);
          $('.permission-table').css('display', '');
          $('.manager-finder-input').prop('disabled', false);
          $('.subordinate-finder-input').prop('disabled', false);
          $('.subordinate-finder-input-2').prop('disabled', false);
        } else {
          $('.subordinate-finder').text('');
          $('.poprefix').prop('disabled', true);
          $('.userrole').prop('disabled', true);
          $('.manager-finder-input').prop('disabled', true);
          $('.subordinate-finder-input').prop('disabled', true);
          $('.subordinate-finder-input-2').prop('disabled', true);
          $('.manager-finder').text('');
          $('.subordinate-finder').text('');
          $('.subordinate-finder-2').text('');
          $('.poprefix').val('');
          $('.permission-table').css('display', 'none');
          accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Invalid FKL id.<p>', 'alert');
        }
      }
    });
  });
  $('.subordinate-finder-input-2').on('keypress', function (evt) {
    var keycode = (evt.keyCode ? evt.keyCode : evt.which);
    if (keycode == '13') {
      var fklid = $(this).val();
      preloaderStart();
      $.ajax({
        type: 'POST',
        url: 'action/users-action.php',
        dataType: 'json',
        data: { 'formName': 'subordinatefinder', 'fklid': fklid },
        success: function (getresponse) {
          preloaderClose();
          if (getresponse['status'] == 'success') {
            $('.subordinate-finder-2').text(getresponse['name']);
            $('.poprefix').prop('disabled', false);
            $('.userrole').prop('disabled', false);
            $('.manager-finder-input').prop('disabled', false);
            $('.subordinate-finder-input-2').prop('disabled', false);
            $('.subordinate-finder-input').prop('disabled', false);
            $('.permission-table').css('display', '');
          } else {
            $('.subordinate-finder-2').text('');
            $('.poprefix').prop('disabled', true);
            $('.userrole').prop('disabled', true);
            $('.manager-finder-input').prop('disabled', true);
            $('.subordinate-finder-input-2').prop('disabled', true);
            $('.subordinate-finder-input').prop('disabled', true);
            $('.manager-finder').text('');
            $('.subordinate-finder').text('');
            $('.subordinate-finder-2').text('');
            $('.poprefix').val('');
            $('.permission-table').css('display', 'none');
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Invalid FKL id.<p>', 'alert');
          }
        }
      });
    }
  });

  $('.subordinate-finder-input-2').on('blur', function (evt) {
    var fklid = $(this).val();
    preloaderStart();
    $.ajax({
      type: 'POST',
      url: 'action/users-action.php',
      dataType: 'json',
      data: { 'formName': 'subordinatefinder-2', 'fklid': fklid },
      success: function (getresponse) {
        preloaderClose();
        if (getresponse['status'] == 'success') {
          $('.subordinate-finder-2').text(getresponse['name']);
          $('.poprefix').prop('disabled', false);
          $('.userrole').prop('disabled', false);
          $('.permission-table').css('display', '');
          $('.manager-finder-input').prop('disabled', false);
          $('.subordinate-finder-input-2').prop('disabled', false);
          $('.subordinate-finder-input').prop('disabled', false);
        } else {
          $('.subordinate-finder-2').text('');
          $('.poprefix').prop('disabled', true);
          $('.userrole').prop('disabled', true);
          $('.manager-finder-input').prop('disabled', true);
          $('.subordinate-finder-input-2').prop('disabled', true);
          $('.subordinate-finder-input').prop('disabled', true);
          $('.manager-finder').text('');
          $('.subordinate-finder-2').text('');
          $('.subordinate-finder').text('');
          $('.poprefix').val('');
          $('.permission-table').css('display', 'none');
          accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Invalid FKL id.<p>', 'alert');
        }
      }
    });
  });

  $('.userrole select').on('change', function (evt) {
    evt.preventDefault();
    $('.permission-checkbox-common').find('input').prop({ 'checked': false, 'readonly': false });
    $('.only-super-admin').find('input').prop('disabled', true);
    if ($(this).val() == 'super admin') {
      $('.super-adminpermission').find('input').prop({ 'checked': true, 'readonly': true });
      $('.only-super-admin').find('input').prop('disabled', false);
    }
  });

  $('.permission-data-submit').on('click', function (evt) {
    evt.preventDefault();
    $('.success-notification').css('display', 'none');
    var err = [];
    $('.invalid_feedback').css('display', 'none');
    $('.required-field').each(function () {
      if ($(this).val() == '') {
        err.push('error');
        $(this).closest('div').find('.invalid_feedback').css('display', 'block');
      }
    });
    if (err.length == 0) {
      var data = $('.userformsubmit').serializeArray();
      data.push({ 'name': 'formName', 'value': 'user-insert' })
      $.ajax({
        type: 'POST',
        url: 'action/users-action.php',
        dataType: 'json',
        data: data,
        success: function (getresponse) {
          $('.success-notification').css('display', 'block');
          if (getresponse['status'] == 'success') {
            var notifyDiv = '';
            notifyDiv += '<p style="font-size: 15px;"><span class="mif-done_all mif-lg"></span> User added successfully.<p>';
            notifyDiv += '<div class="d-flex flex-justify-between">';
            notifyDiv += '<a href="user-permission.php?page=create-new" class="image-button success" style="height:30px;"><span class="mif-plus icon" style="height: 30px; line-height: 30px; font-size: .9rem; width: 32px;"></span><span class="caption">Add New</span></a>';
            notifyDiv += '</div>';
            accessoriesNotify(notifyDiv, 'success', 400);
          } else {
            $('.success-notification').css('display', 'none');
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! User already exists.<p>', 'alert');
          }
        }
      });
    } else {
      $('.success-notification').css('display', 'none');
      accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! One or more errors found.<p>', 'alert');
    }


  });

  $('.permission-data-update').on('click', function (evt) {
    evt.preventDefault();
    $('.success-notification').css('display', 'none');
    var err = [];
    $('.invalid_feedback').css('display', 'none');
    $('.required-field').each(function () {
      if ($(this).val() == '') {
        err.push('error');
        $(this).closest('div').find('.invalid_feedback').css('display', 'block');
      }
    });
    if (err.length == 0) {
      var data = $('.userformupdate').serializeArray();
      data.push({ 'name': 'formName', 'value': 'user-update' })
      $.ajax({
        type: 'POST',
        url: 'action/users-action.php',
        dataType: 'json',
        data: data,
        success: function (getresponse) {
          $('.success-notification').css('display', 'block');
          if (getresponse['status'] == 'success') {
            alert('User permission rules updated successfully');
            window.location.href = "user-permission.php?page=edit&id=" + getresponse['id'];
          } else {
            $('.success-notification').css('display', 'none');
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Invalid user id.<p>', 'alert');
          }
        }
      });
    } else {
      $('.success-notification').css('display', 'none');
      accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! One or more errors found.<p>', 'alert');
    }


  });

  $('.drm-submit').on('click', function (e) {
    e.preventDefault();
    var itemId = $(this).data('tableid');
    var tableClass = '.drm-table-' + itemId;
    $(tableClass).find('.invalid_feedback').css('display', 'none');
    $('.invalid_feedback-' + itemId).css('display', 'none');
    var error = [];
    if ($(tableClass).find('.recive-qty-grand').val() == 0) {
      error.push('error');
      $(tableClass).find('.recive-qty-grand').closest('td').find('.invalid_feedback').css('display', 'block');
    }
    if ($('.godownid-' + itemId + ' select').val().length == 0) {
      error.push('error');
      $('.invalid_feedback-' + itemId).css('display', 'block');
    }
    // alert($('.godownid-'+itemId+' select').val().length);
    if (error.length == 0) {
      var receiveMasterData = [], recievedData = [], sizeWiseQtyTempArr = {};
      receiveMasterData.push({
        'NWORKORDERITEMSID': itemId,
        'NDIID': $('.diid').val(),
        'masterid': $('.masterid').val(),
        'VREMARKS': $('.drm-remarks-' + itemId + ' textarea').val(),
        'VCHALLANNUMBER': $('.challano').val(),
        'VDIDATE': $('.didate').val(),
        'VDINO': $('.dino').val(),
        'VTALLYMAN': $('.tallyman').val(),
        'VRECEIVEDATE': $(tableClass).find('.receivedate input').val(),
        'VGODOWNID': $('.godownid-' + itemId + ' select').val().join()
      });
      console.log(receiveMasterData);
      if ($(tableClass).data('gridtype') == 'straight') {
        $(tableClass).find('.drm-data-row').each(function (index, el) {
          recievedData.push({
            'NWORKORDERITEMDATAID': $(this).find('.datagridid').val(),
            'NTOTALRECEIVEQTY': $(this).find('.recive-qty').val(),
            'sizedata': ''
          });
        });
      }
      if ($(tableClass).data('gridtype') == 'colornsize') {
        $(tableClass).find('.data-gridid').each(function (index, el) {
          var sizeWiseQtyData = {};
          var dataGridId = $(this).val();
          $(tableClass).find('.receive-qty-grid-' + dataGridId).each(function (index, el) {
            var getsize = $(this).closest('tr').find('.sizename-grid-' + dataGridId).text();
            var receiveqty = $(this).val();
            sizeWiseQtyData[getsize] = receiveqty;
          });
          recievedData.push({
            'NWORKORDERITEMDATAID': dataGridId,
            'sizedata': sizeWiseQtyData
          });
        });
      }

      console.log(recievedData);
      $.ajax({
        type: 'POST',
        url: 'action/daily-receive-materials-action.php',
        dataType: 'json',
        data: { 'formName': 'drmsubmit', 'masterData': receiveMasterData, 'recieveQty': recievedData },
        success: function (getresponse) {
          if (getresponse['status'] == 'success') {
            $('.drm-content-' + itemId).load('daily-receive-material.php?page=new-receive&di=' + $('.diid').val() + '&po=' + $('.ponumber').val() + ' ' + tableClass);
            $('.drm-remarks-' + itemId + ' textarea').val('');
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-done_all mif-lg"></span> Material received successfully.<p>', '', 400);
          } else {
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Something went wrong, refresh your browser.<p>', 'alert');
          }
        }
      });

    } else {
      accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! One or more errors found.<p>', 'alert');
    }
  });

});

/*============================================================================================
          ------------------Custom alert popup function-------------------------
==============================================================================================*/

function accessoriesNotify(msg, type = '', width = 300) {
  var notify = Metro.notify;
  if (type == 'alert') {
    notify.setup({
      width: width,
      duration: 500,
      timeout: 6000,
      animation: 'easeOutBounce',
    });
    notify.create(msg, 'Alert', { cls: 'alert' });
  } else if (type == 'success') {
    notify.setup({
      width: width,
      duration: 500,
      timeout: 6000,
      animation: 'easeOutCubic',
    });
    notify.create(msg, 'Done', {
      cls: 'success', keepOpen: true,
      onClose: function () {
        history.go(0);
      }
    });
  } else if (type == 'success-common') {
    notify.setup({
      width: width,
      duration: 500,
      timeout: 6000,
      animation: 'easeOutCubic',
    });
    notify.create(msg, 'Done', { cls: 'success', keepOpen: true });
  } else {
    notify.setup({
      width: width,
      duration: 500,
      timeout: 6000,
      animation: 'easeOutCubic',
    });
    notify.create(msg, 'Done', { cls: 'success' });
  }
  notify.reset();
}

/*============================================================================================
            ------------------Number Format function-------------------------
==============================================================================================*/

function format(n, sep, decimals) {
  sep = sep || "."; // Default to period as decimal separator
  decimals = decimals || 2; // Default to 2 decimals

  return n.toLocaleString().split(sep)[0];
  // return n.toLocaleString().split(sep)[0]
  //     + sep
  //     + n.toFixed(decimals).split(sep)[1];
}

/*============================================================================================
           ------------------Array Unique Filter Function-------------------------
==============================================================================================*/
function unique(array) {
  if (array) {
    var found = {};
    array = array.join(",").split(",").filter(function (x) {
      x = x.trim();
      return (found[x] ? false : (found[x] = x));
    })
  }
  return array;
}




function numberValidate(className, val, defaultval = 0) {
  if (!/^\d+\.?\d*$/.test(className.val()) && val.length != 0) {
    accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Qty is invalid.<br>Enter valid numeric digit.<p>', 'alert');
    className.val(defaultval);
    return false;
  } else {
    return true;
  }
}

function rowAdder(ownClass, tableClass) {
  var uniqueId = $('.' + tableClass).data('uniqueid');
  var btnDeactive = false;
  if ($('.' + tableClass).find('.color-wise-qty' + uniqueId).length > 0 || $('.' + tableClass).find('.size-wise-qty' + uniqueId).length > 0 || $('.' + tableClass).find('.kimball-color-wise-qty' + uniqueId).length > 0 || $('.' + tableClass).find('.color-size-wise-qty' + uniqueId).length > 0 || $('.' + tableClass).find('.kimball-color-size-wise-qty' + uniqueId).length > 0) {
    if ($('.' + tableClass).find('.data-row-hidden').find('input[type="checkbox"]:disabled').length == $('.addrowdisabler').val()) {
      btnDeactive = true;
    }
  }
  if (btnDeactive) {
    accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Maximum row number is reached.', 'alert');
  } else {
    cloneData = $('.' + tableClass).find('.data-row-hidden').clone().removeClass('data-row-hidden').addClass('data-row').removeAttr('style');
    if (ownClass.closest('tr').find('.color-wise-qty' + uniqueId).length > 0) {
      cloneData.find('.colorwiseqty').each(function (index, el) {
        $(this).removeAttr('id');
        var rand = Math.floor((Math.random() * 1000) + 1);
        $(this).attr('id', 'colorselect-' + index + '-' + ($('.' + tableClass).find('.data-row').length + 2 + rand) + '-' + uniqueId);
        $(this).next('label').attr('for', 'colorselect-' + index + '-' + ($('.' + tableClass).find('.data-row').length + 2 + rand) + '-' + uniqueId);
      });
    }
    if (ownClass.closest('tr').find('.grmnts-color' + uniqueId).length > 0) {
      cloneData.find('.colornameg').each(function (index, el) {
        $(this).removeAttr('id');
        var rand = Math.floor((Math.random() * 1000) + 1);
        $(this).attr('id', 'colorselectg-' + index + '-' + ($('.' + tableClass).find('.data-row').length + 2 + rand) + '-' + uniqueId);
        $(this).next('label').attr('for', 'colorselectg-' + index + '-' + ($('.' + tableClass).find('.data-row').length + 2 + rand) + '-' + uniqueId);
      });
    }
    if (ownClass.closest('tr').find('.grmnts-color-kimball-lot' + uniqueId).length > 0) {
      cloneData.find('.kcolornameg').each(function (index, el) {
        $(this).removeAttr('id');
        var rand = Math.floor((Math.random() * 1000) + 1);
        $(this).attr('id', 'kcolorselectg-' + index + '-' + ($('.' + tableClass).find('.data-row').length + 2 + rand) + '-' + uniqueId);
        $(this).next('label').attr('for', 'kcolorselectg-' + index + '-' + ($('.' + tableClass).find('.data-row').length + 2 + rand) + '-' + uniqueId);
      });
    }
    if (ownClass.closest('tr').find('.size-wise-qty' + uniqueId).length > 0) {
      cloneData.find('.sizewiseqty').each(function (index, el) {
        $(this).removeAttr('id');
        var rand = Math.floor((Math.random() * 1000) + 1);
        $(this).attr('id', 'sizeselect-' + index + '-' + ($('.' + tableClass).find('.data-row').length + 2 + rand) + '-' + uniqueId);
        $(this).next('label').attr('for', 'sizeselect-' + index + '-' + ($('.' + tableClass).find('.data-row').length + 2 + rand) + '-' + uniqueId);
      });
    }
    if (ownClass.closest('tr').find('.size-name' + uniqueId).length > 0) {
      cloneData.find('.sizenameg').each(function (index, el) {
        $(this).removeAttr('id');
        var rand = Math.floor((Math.random() * 1000) + 1);
        $(this).attr('id', 'sizeselectg-' + index + '-' + ($('.' + tableClass).find('.data-row').length + 2 + rand) + '-' + uniqueId);
        $(this).next('label').attr('for', 'sizeselectg-' + index + '-' + ($('.' + tableClass).find('.data-row').length + 2 + rand) + '-' + uniqueId);
      });
    }
    if (ownClass.closest('tr').find('.kimball-color-wise-qty' + uniqueId).length > 0) {
      cloneData.find('.kcolorwiseqty').each(function (index, el) {
        $(this).removeAttr('id');
        var rand = Math.floor((Math.random() * 1000) + 1);
        $(this).attr('id', 'kcolorselect-' + index + '-' + ($('.' + tableClass).find('.data-row').length + 2 + rand) + '-' + uniqueId);
        $(this).next('label').attr('for', 'kcolorselect-' + index + '-' + ($('.' + tableClass).find('.data-row').length + 2 + rand) + '-' + uniqueId);
      });
    }
    if (ownClass.closest('tr').find('.color-size-wise-qty' + uniqueId).length > 0) {
      cloneData.find('.colorswiseqty').each(function (index, el) {
        $(this).removeAttr('id');
        var rand = Math.floor((Math.random() * 1000) + 1);
        $(this).attr('id', 'colorsselect-' + index + '-' + ($('.' + tableClass).find('.data-row').length + 2 + rand) + '-' + uniqueId);
        $(this).next('label').attr('for', 'colorsselect-' + index + '-' + ($('.' + tableClass).find('.data-row').length + 2 + rand) + '-' + uniqueId);
      });
    }
    if (ownClass.closest('tr').find('.kimball-color-size-wise-qty' + uniqueId).length > 0) {
      cloneData.find('.kcolorswiseqty').each(function (index, el) {
        $(this).removeAttr('id');
        var rand = Math.floor((Math.random() * 1000) + 1);
        $(this).attr('id', 'kcolorsselect-' + index + '-' + ($('.' + tableClass).find('.data-row').length + 2 + rand) + '-' + uniqueId);
        $(this).next('label').attr('for', 'kcolorsselect-' + index + '-' + ($('.' + tableClass).find('.data-row').length + 2 + rand) + '-' + uniqueId);
      });
    }
    ownClass.closest('tr').before(cloneData);
    $('.' + tableClass).find('.maingrid-rowspan').attr('rowspan', $('.' + tableClass).find('.data-row').length);
    $('.' + tableClass).find('.pn-no-cell').attr('rowspan', $('.' + tableClass).find('.data-row').length);
    $('.' + tableClass).find('.order-no-cell').attr('rowspan', $('.' + tableClass).find('.data-row').length);
    $('.' + tableClass).find('.symbol-cell').attr('rowspan', $('.' + tableClass).find('.data-row').length);
    //$('#'+tableClass).find('.code-no-cell').attr('rowspan', $('#'+tableClass).find('.data-row').length);
  }
}
function rowRemover(ownClass, tableClass) {
  var getUniqueId = ownClass.closest('table').data('uniqueid');
  if (ownClass.closest('tr').find('.color-wise-qty' + getUniqueId).length > 0) {
    ownClass.closest('tr').find('.color-wise-qty' + getUniqueId).find('.colorwiseqty').each(function (index, el) {
      if ($(this).is(':checked')) {
        var getClass = $(this).attr('name');
        $('.' + tableClass).find('.' + getClass).prop('disabled', false);
        $('.' + tableClass).find('.' + getClass).parent('div').css({ 'display': '', 'background': '', 'color': '', 'font-weight': '' });
      }
    });
  }
  if (ownClass.closest('tr').find('.kimball-color-wise-qty' + getUniqueId).length > 0) {
    ownClass.closest('tr').find('.kimball-color-wise-qty' + getUniqueId).find('.kcolorwiseqty').each(function (index, el) {
      if ($(this).is(':checked')) {
        var getClass = $(this).attr('name');
        $('.' + tableClass).find('.' + getClass).prop('disabled', false);
        $('.' + tableClass).find('.' + getClass).parent('div').css({ 'display': '', 'background': '', 'color': '', 'font-weight': '' });
      }
    });
  }
  if (ownClass.closest('tr').find('.color-size-wise-qty' + getUniqueId).length > 0) {
    ownClass.closest('tr').find('.color-size-wise-qty' + getUniqueId).find('.colorswiseqty').each(function (index, el) {
      if ($(this).is(':checked')) {
        var getClass = $(this).attr('name');
        $('.' + tableClass).find('.' + getClass).prop('disabled', false);
        $('.' + tableClass).find('.' + getClass).parent('div').css({ 'display': '', 'background': '', 'color': '', 'font-weight': '' });
      }
    });
  }
  if (ownClass.closest('tr').find('.kimball-color-size-wise-qty' + getUniqueId).length > 0) {
    ownClass.closest('tr').find('.kimball-color-size-wise-qty' + getUniqueId).find('.kcolorswiseqty').each(function (index, el) {
      if ($(this).is(':checked')) {
        var getClass = $(this).attr('name');
        $('.' + tableClass).find('.' + getClass).prop('disabled', false);
        $('.' + tableClass).find('.' + getClass).parent('div').css({ 'display': '', 'background': '', 'color': '', 'font-weight': '' });
      }
    });
  }
  if (ownClass.closest('tr').find('.size-wise-qty' + getUniqueId).length > 0) {
    ownClass.closest('tr').find('.size-wise-qty' + getUniqueId).find('.sizewiseqty').each(function (index, el) {
      if ($(this).is(':checked')) {
        var getClass = $(this).attr('name');
        $('.' + tableClass).find('.' + getClass).prop('disabled', false);
        $('.' + tableClass).find('.' + getClass).parent('div').css({ 'display': '', 'background': '', 'color': '', 'font-weight': '' });
      }
    });
  }
  ownClass.closest('tr').remove();
  $('.' + tableClass).find('.maingrid-rowspan').attr('rowspan', $('.' + tableClass).find('.data-row').length);
  $('.' + tableClass).find('.pn-no-cell').attr('rowspan', $('.' + tableClass).find('.data-row').length);
  $('.' + tableClass).find('.order-no-cell').attr('rowspan', $('.' + tableClass).find('.data-row').length);
  $('.' + tableClass).find('.symbol-cell').attr('rowspan', $('.' + tableClass).find('.data-row').length);
  //$('#'+tableClass).find('.code-no-cell').attr('rowspan', $('#'+tableClass).find('.data-row').length);
  if ($('.' + tableClass).find('.garments-qty-input').length > 0) {
    rowSumCommon('garments-qty-input', 'garmentsgrandtotal', tableClass);
  }
  if ($('.' + tableClass).find('.row-garmentsqtyextra-input').length > 0) {
    rowSumCommon('row-garmentsqtyextra-input', 'garmentsextragrandtotal', tableClass);
  }
  rowSum(tableClass);
}

function checkimage(ownClass, count) {
  //alert(count);
  // console.log(ownClass[0].files);
  if (ownClass[0].files.length > parseInt(count)) {
    accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! You can not select more than four (4) images.', 'alert');
    setTimeout(function () {
      ownClass.parent('label').find('.files').html('0 file(s) selected');
      ownClass.val('');
    }, 500);
    return false;
  }
}

function checkvalidformat(ownClass) {
  var format = ['jpg', 'png', 'gif'];
  var tempArr = [];
  for (var i = 0; i < ownClass[0].files.length; i++) {
    if ($.inArray(ownClass[0].files[i].name.substr(-3).toLowerCase(), format) == -1) {
      tempArr.push('invalid');
    }
  }
  if (tempArr.length > 0) {
    accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Invalid file format.', 'alert');
    setTimeout(function () {
      ownClass.parent('label').find('.files').html('0 file(s) selected');
      ownClass.val('');
    }, 500);
    return false;
  }
}

function deleteAttchment(imageId, ownClass, tableClass) {
  preloaderStart();
  if (confirm('Are you sure to delete this attachment?')) {
    $.ajax({
      type: 'POST',
      url: "action/workorder-action.php",
      data: { 'formName': 'delete-attachment', 'id': imageId },
      dataType: 'json',
      success: function (getresponse) {
        preloaderClose();
        if (getresponse['status'] == 'success') {
          var notifyDiv = '';
          notifyDiv += '<p style="font-size: 15px;"><span class="mif-done_all mif-lg"></span> Attachment deleted successfully.<p>';
          accessoriesNotify(notifyDiv, '', 350);
          ownClass.closest('td').fadeOut('fast');
          var counter = parseInt($('.' + tableClass).find('.symbol-count').val()) + 1;
          var imageNo = (4 - parseInt($('.' + tableClass).find('.symbol-count').val())) - 1;
          $('.' + tableClass).find('.symbol-count').val(counter)
          var appender = "";
          appender += "<input type='file' name='attachment[]' multiple data-role='file' data-mode='drop' onchange='checkimage($(this), " + counter + "); checkvalidformat($(this));' class='symbol-input'><small>Allowed file format: jpg/png/jpeg/gif, Max allowed : 4 images.</small>";
          appender += "<br><small class='fg-red'>" + imageNo + " symbol(s) added.</small>";
          $('.' + tableClass).find('.symbol-cell').html(appender);
        } else {
          accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Something went wrong. Refresh your browser and try again.<p>', 'alert');
        }
      }
    });
  } else {
    preloaderClose();
  }

}

function checkValidFile(ownClass) {
  var getFileExtension = ownClass[0].files[0].name.substring(ownClass[0].files[0].name.lastIndexOf('.') + 1);
  switch (getFileExtension) {
    case 'xlsx':
      return true;
    case 'csv':
      return true;
    default:
      accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Invalid file format.', 'alert');
      setTimeout(function () {
        ownClass.parent('label').find('.files').html('0 file(s) selected');
        ownClass.val('');
      }, 500);
      return false;
  }
}

function qtysum(parentDivId, ownClass) {
  var cellQty = 0;
  ownClass.closest('tr').find('.size-qty-input').each(function (index) {
    var ownValue = parseInt($(this).val() == '' || isNaN($(this).val()) ? 0 : $(this).val());
    cellQty += ownValue;
  });
  ownClass.closest('tr').find('.row-totalqty-input').val(cellQty);
  rowSum(parentDivId);
}

function requiredQty(tableClass, ownobject) {
  var val = parseInt(ownobject.val() == '' || isNaN(ownobject.val()) ? 0 : ownobject.val());
  ownobject.closest('tr').find('.row-totalqty-input').val(val);
  rowSum(tableClass);
}

function rowSum(tableClass) {
  var rowQty = 0;
  $('.' + tableClass).find('.data-row .row-totalqty-input').each(function (index) {
    var ownValue = parseFloat($(this).val() == '' || isNaN($(this).val()) ? 0 : $(this).val());
    rowQty += ownValue;
  });
  if ($('.' + tableClass).find('.grandunit').text().toLowerCase() == "con's" || $('.' + tableClass).find('.grandunit').text().toLowerCase() == "rolls") {
    $('.' + tableClass).find('.grand-totalqty-input').val(precise_round(Math.round(rowQty), 2));
  } else {
    $('.' + tableClass).find('.grand-totalqty-input').val(precise_round(rowQty, 2));
  }
}

function rowSumCommon(rowClass, sumOutputClass, tableClass, csize = '') {
  var rowQty = 0;
  if (csize.length == 0) {
    $('.' + tableClass).find('.data-row .' + rowClass).each(function (index) {
      var ownValue = parseInt($(this).val() == '' || isNaN($(this).val()) ? 0 : $(this).val());
      rowQty += ownValue;
    });
  } else {
    $('.' + tableClass).find('.data-row').each(function (index) {
      var ownVal = 0;
      $(this).find('.csaddition-qty-hidden').each(function (indexx, ell) {
        var ownValue = parseInt($(this).val() == '' || isNaN($(this).val()) ? 0 : $(this).val());
        // alert('cell value = '+ownValue);
        ownVal += ownValue;
      });
      // alert('row value = '+ownVal);
      rowQty += ownVal;
    });
    // alert('total value = '+rowQty);
  }
  $('.' + tableClass).find('.' + sumOutputClass).val(rowQty);
}

function dataCopy(ownClass, cellClass, tableClass) {
  var uniqueId = $('.' + tableClass).data('uniqueid');
  var data = ownClass.parent('td').find('.data-copier').val();
  if (data.length == 0) {
    accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Field value is required.', 'alert');
  } else {
    $('.' + tableClass).find('.' + cellClass + ' .data-copier').each(function (index) {
      if (index > $('.' + tableClass + ' .' + cellClass).find('.data-copier').index(ownClass.parent('td').find('.data-copier'))) {
        $(this).val('');
        $(this).html();
        $(this).val(data);
        $(this).html(data);
      }
    });
  }
}


function dataCopyWithCalculation(ownClass, cellClass, tableClass) {
  var uniqueId = $('.' + tableClass).data('uniqueid');
  var data = ownClass.parent('td').find('.data-copier').val();
  var getType = ownClass.closest('td').find('.row-convertion-input').data('calinputtype');
  var qtyUnit = $('.' + tableClass).find('.grandunit').text().toLowerCase();
  var totalQty = 0;
  if (data.length == 0) {
    accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Field value is required.', 'alert');
  } else {
    $('.' + tableClass).find('.' + cellClass + ' .data-copier').each(function (index) {
      if (index > $('.' + tableClass + ' .' + cellClass).find('.data-copier').index(ownClass.parent('td').find('.data-copier'))) {
        $(this).val(data);
        if ($('.addition' + uniqueId).length > 0) {
          var garmentsQty = $(this).closest('tr').find('.row-garmentsqtyextra-input').val();
        } else {
          if ($('.' + tableClass).find('.colorsizeqty-header').length > 0) {
            var getValSum = 0;
            $(this).closest('tr').find('.csize-input').each(function (indexx) {
              getValSum += parseInt($(this).val());
            });
            var garmentsQty = getValSum;
          } else {
            var garmentsQty = $(this).closest('tr').find('.garments-qty-input').val();
          }
          // var garmentsQty = $(this).closest('tr').find('.garments-qty-input').val();
        }
        if ($(this).data('convertionval') == undefined) {
          if (getType == 'divided') {
            var totalQty = parseFloat(garmentsQty) / parseFloat(data);
          } else {
            var totalQty = parseFloat(garmentsQty) * parseFloat(data);
          }
        } else {
          if (getType == 'divided') {
            var totalQty = parseFloat(garmentsQty) / parseFloat(data);
          } else {
            var totalQty = parseFloat(garmentsQty) * parseFloat(data);
          }
          totalQty = totalQty / parseFloat($(this).data('convertionval'));
        }
        // if(getType == 'divided'){
        //   var totalQty = parseFloat(garmentsQty) / parseFloat(data);
        // }
        if (qtyUnit == 'yrds' || qtyUnit == 'gg') {
          $(this).closest('tr').find('.row-totalqty-input').val(precise_round(totalQty, 2));
        } else {
          $(this).closest('tr').find('.row-totalqty-input').val(Math.round(totalQty));
        }
      }
    });
    rowSum(tableClass);
  }
}

function colorWiseQtyGeneral(ownClass, tableClass) {
  if (ownClass.is(':checked')) {
    ownClass.parent('div').css({ 'background': '#006d77', 'color': '#fff', 'font-weight': 'bold' });
  } else {
    ownClass.parent('div').css({ 'background': '', 'color': '', 'font-weight': '' });
  }
  var tempColorName = [];
  ownClass.closest('td').find('.colornameg').each(function (index, el) {
    if ($(this).is(':checked')) {
      tempColorName.push($(this).val());
    }
  });
  ownClass.closest('td').find('.data-content').text(unique(tempColorName).join(', '));
}
function sizeWiseQtyGeneral(ownClass, tableClass) {
  if (ownClass.is(':checked')) {
    ownClass.parent('div').css({ 'background': '#006d77', 'color': '#fff', 'font-weight': 'bold' });
  } else {
    ownClass.parent('div').css({ 'background': '', 'color': '', 'font-weight': '' });
  }
  var tempSizeName = [];
  ownClass.closest('td').find('.sizenameg').each(function (index, el) {
    if ($(this).is(':checked')) {
      tempSizeName.push($(this).val());
    }
  });
  ownClass.closest('td').find('.data-content').text(unique(tempSizeName).join(', '));
}
function kimballColorWiseQtyGeneral(ownClass, tableClass) {
  if (ownClass.is(':checked')) {
    ownClass.parent('div').css({ 'background': '#006d77', 'color': '#fff', 'font-weight': 'bold' });
  } else {
    ownClass.parent('div').css({ 'background': '', 'color': '', 'font-weight': '' });
  }
  var tempKimball = [];
  var tempLot = [];
  var tempColorName = [];
  ownClass.closest('td').find('.kcolornameg').each(function (index, el) {
    if ($(this).is(':checked')) {
      tempColorName.push($(this).val());
      tempKimball.push($(this).data('kimball'));
      tempLot.push($(this).data('lot'));
    }
  });
  ownClass.closest('td').find('.data-content').text(unique(tempColorName).join(', '));
  ownClass.closest('tr').find('.kimball-cell').text(unique(tempKimball).join(', '));
  ownClass.closest('tr').find('.lot-cell').text(unique(tempLot).join(', '));
}
function colorWiseQty(ownClass, classIndex, tableClass) {
  var getUniqueId = $('.' + tableClass).data('uniqueid');
  if (ownClass.is(':checked')) {
    $('.' + tableClass).find('.colorname' + classIndex).prop('disabled', true);
    $('.' + tableClass).find('.colornameDiv' + classIndex).css({ 'background': '#aeaeae', 'color': '#808080', 'font-weight': '' });
    $('.' + tableClass).find('.colornameDiv' + classIndex + ' input[type="checkbox"]').css('cursor', '');
    $('.' + tableClass).find('.colornameDiv' + classIndex + ' label').css('cursor', '');
    ownClass.prop('disabled', false);
    ownClass.parent('div').css({ 'background': '#006d77', 'color': '#fff', 'font-weight': 'bold' });
    ownClass.parent('div').find('input[type="checkbox"]').css('cursor', 'pointer');
    ownClass.parent('div').find('label').css('cursor', 'pointer');
    ownClass.closest('.' + tableClass).find('.data-row').each(function (index) {
      if ($(this).find('input[type="checkbox"]:disabled').length == $('.addrowdisabler').val() && index > 0) {
        $(this).remove();
      }
    });
  } else {
    $('.' + tableClass).find('.colorname' + classIndex).prop('disabled', false);
    $('.' + tableClass).find('.colornameDiv' + classIndex).css({ 'background': '', 'color': '', 'font-weight': '' });
    $('.' + tableClass).find('.colornameDiv' + classIndex + ' input[type="checkbox"]').css('cursor', 'pointer');
    $('.' + tableClass).find('.colornameDiv' + classIndex + ' label').css('cursor', 'pointer');
  }
  var totalQty = 0;
  var tempColorName = [];
  ownClass.closest('td').find('.colorwiseqty').each(function (index, el) {
    if ($(this).is(':checked')) {
      tempColorName.push($(this).val());
      totalQty += $(this).data('qty');
    }
  });
  ownClass.closest('td').find('.data-content').text(unique(tempColorName).join(', '));
  ownClass.closest('tr').find('.garments-qty-input').val(parseInt(totalQty));
  ownClass.closest('tr').find('.addition-qty-hidden').val(parseInt(totalQty));
  if ($('#' + tableClass).find('.addition' + getUniqueId).length > 0) {
    additionalQty(ownClass.closest('tr').find('.row-garmentsqtyextra-input').data('additionval'), ownClass.closest('tr').find('.row-garmentsqtyextra-input').data('additiontype'), ownClass.closest('tr').find('.addition-qty-hidden').val(), ownClass);
  } else {
    ownClass.closest('tr').find('.row-totalqty-input').val(parseInt(totalQty));
  }
  rowSum(tableClass);
  rowSumCommon('garments-qty-input', 'garmentsgrandtotal', tableClass);
  if ($('.' + tableClass).find('.row-convertion-input').length > 0) {
    convertionCalculate(tableClass, ownClass);
  }
  $('.' + tableClass).find('.maingrid-rowspan').attr('rowspan', $('.' + tableClass).find('.data-row').length);
  $('.' + tableClass).find('.pn-no-cell').attr('rowspan', $('.' + tableClass).find('.data-row').length);
  $('.' + tableClass).find('.order-no-cell').attr('rowspan', $('.' + tableClass).find('.data-row').length);
  $('.' + tableClass).find('.symbol-cell').attr('rowspan', $('.' + tableClass).find('.data-row').length);
}

function colorsWiseQty(ownClass, classIndex, tableClass) {

  var getUniqueId = $('.' + tableClass).data('uniqueid');
  if (ownClass.is(':checked')) {
    $('.' + tableClass).find('.colorsname' + classIndex).prop('disabled', true);
    $('.' + tableClass).find('.colorsnameDiv' + classIndex).css({ 'background': '#aeaeae', 'color': '#808080', 'font-weight': '' });
    $('.' + tableClass).find('.colorsnameDiv' + classIndex + ' input[type="checkbox"]').css('cursor', '');
    $('.' + tableClass).find('.colorsnameDiv' + classIndex + ' label').css('cursor', '');
    ownClass.prop('disabled', false);
    ownClass.parent('div').css({ 'background': '#006d77', 'color': '#fff', 'font-weight': 'bold' });
    ownClass.parent('div').find('input[type="checkbox"]').css('cursor', 'pointer');
    ownClass.parent('div').find('label').css('cursor', 'pointer');
    ownClass.closest('.' + tableClass).find('.data-row').each(function (index) {
      if ($(this).find('input[type="checkbox"]:disabled').length == $('.addrowdisabler').val() && index > 0) {
        $(this).remove();
      }
    });
  } else {
    $('.' + tableClass).find('.colorsname' + classIndex).prop('disabled', false);
    $('.' + tableClass).find('.colorsnameDiv' + classIndex).css({ 'background': '', 'color': '', 'font-weight': '' });
    $('.' + tableClass).find('.colorsnameDiv' + classIndex + ' input[type="checkbox"]').css('cursor', 'pointer');
    $('.' + tableClass).find('.colorsnameDiv' + classIndex + ' label').css('cursor', 'pointer');
  }
  var totalQty = 0;
  var tempColorName = [];
  if (localStorage.getItem('colorsizewisedata') === null) {
    presetDataVar = presetData('color&sizewise');
    localStorage.setItem("colorsizewisedata", JSON.stringify(presetDataVar));
  }
  var colorSizeData = JSON.parse(localStorage.getItem('colorsizewisedata'));
  // console.log(colorSizeData);
  var tempSizeSum = {};
  ownClass.closest('td').find('.colorswiseqty').each(function (index, el) {
    if ($(this).is(':checked')) {
      var ownVal = $(this).val();
      $.each(colorSizeData.colorsizeQty[ownVal], function (indexx, val) {
        // alert(indexx+'---'+val);
        tempSizeSum[indexx.toString()] = tempSizeSum[indexx] != undefined ? parseInt(tempSizeSum[indexx.toString()]) + parseInt(val) : parseInt(val);
      });
      tempColorName.push($(this).val());
    }
  });
  ownClass.closest('tr').find('.csize-input').val(0);
  ownClass.closest('tr').find('.csaddition-qty-hidden').val(0);
  ownClass.closest('td').find('.data-content').text(unique(tempColorName).join(', '));
  if ($('#' + tableClass).find('.addition' + getUniqueId).length > 0) {
    var additionQty = ownClass.closest('tr').find('.row-garmentsqtyextra-input').data('additionval');
    var additionType = ownClass.closest('tr').find('.row-garmentsqtyextra-input').data('additiontype');
    var getValSum = 0;
    $.each(tempSizeSum, function (indexlast, vall) {
      var getValue = 0;
      if (additionType == 'parcent') {
        var getValueInitial = (parseFloat(vall) / 100) * parseFloat(additionQty);
        getValue = parseFloat(vall) + parseFloat(getValueInitial);
      }
      if (additionType == 'qty') {
        getValue = parseFloat(vall) + parseFloat(additionQty);
      }

      ownClass.closest('tr').find('.size-' + indexlast.replace(/[_\W]+/g, "-") + '-input').val(Math.round(getValue));

      ownClass.closest('tr').find('.additionsize-' + indexlast.replace(/[_\W]+/g, "-") + '-input').val(vall);
      getValSum += Math.round(getValue);
    });
    ownClass.closest('tr').find('.row-totalqty-input').val(Math.round(getValSum));
    ownClass.closest('tr').find('.row-garmentsqtyextra-input').val(Math.round(getValSum));
    rowSumCommon('row-garmentsqtyextra-input', 'garmentsextragrandtotal', tableClass);
    // rowSumCommon('garments-qty-input', 'garmentsgrandtotal', tableClass);   
  } else {
    var getValSum = 0;
    $.each(tempSizeSum, function (indexlast, vall) {
      // alert('.size-'+indexlast.replace(/[_\W]+/g, "-")+'-input');
      ownClass.closest('tr').find('.size-' + indexlast.replace(/[_\W]+/g, "-") + '-input').val(vall);
      ownClass.closest('tr').find('.additionsize-' + indexlast.replace(/[_\W]+/g, "-") + '-input').val(vall);
      getValSum += parseInt(vall);
    });
    ownClass.closest('tr').find('.row-totalqty-input').val(parseInt(getValSum));
  }
  if ($('.' + tableClass).find('.row-convertion-input').length > 0) {
    convertionCalculate(tableClass, ownClass);
  }
  var totalSum = 0;
  $('.' + tableClass).find('.csaddition-qty-hidden').each(function () {
    totalSum += parseInt($(this).val());
  });
  $('.' + tableClass).find('.garmentsgrandtotal').val(totalSum);
  rowSum(tableClass);

  $('.' + tableClass).find('.maingrid-rowspan').attr('rowspan', $('.' + tableClass).find('.data-row').length);
  $('.' + tableClass).find('.pn-no-cell').attr('rowspan', $('.' + tableClass).find('.data-row').length);
  $('.' + tableClass).find('.order-no-cell').attr('rowspan', $('.' + tableClass).find('.data-row').length);
  $('.' + tableClass).find('.symbol-cell').attr('rowspan', $('.' + tableClass).find('.data-row').length);
}

function kcolorsWiseQty(ownClass, classIndex, tableClass) {
  var getUniqueId = $('.' + tableClass).data('uniqueid');
  if (ownClass.is(':checked')) {
    $('.' + tableClass).find('.kcolorsname' + classIndex).prop('disabled', true);
    $('.' + tableClass).find('.kcolorsnameDiv' + classIndex).css({ 'background': '#aeaeae', 'color': '#808080', 'font-weight': '' });
    $('.' + tableClass).find('.kcolorsnameDiv' + classIndex + ' input[type="checkbox"]').css('cursor', '');
    $('.' + tableClass).find('.kcolorsnameDiv' + classIndex + ' label').css('cursor', '');
    ownClass.prop('disabled', false);
    ownClass.parent('div').css({ 'background': '#006d77', 'color': '#fff', 'font-weight': 'bold' });
    ownClass.parent('div').find('input[type="checkbox"]').css('cursor', 'pointer');
    ownClass.parent('div').find('label').css('cursor', 'pointer');
    ownClass.closest('.' + tableClass).find('.data-row').each(function (index) {
      if ($(this).find('input[type="checkbox"]:disabled').length == $('.addrowdisabler').val() && index > 0) {
        $(this).remove();
      }
    });
  } else {
    $('.' + tableClass).find('.kcolorsname' + classIndex).prop('disabled', false);
    $('.' + tableClass).find('.kcolorsnameDiv' + classIndex).css({ 'background': '', 'color': '', 'font-weight': '' });
    $('.' + tableClass).find('.kcolorsnameDiv' + classIndex + ' input[type="checkbox"]').css('cursor', 'pointer');
    $('.' + tableClass).find('.kcolorsnameDiv' + classIndex + ' label').css('cursor', 'pointer');
  }
  var totalQty = 0;
  var tempColorName = [];
  var tempKimball = [];
  var tempLot = [];
  if (localStorage.getItem('kimballcolorsizewisedata') === null) {
    presetDataVar = presetData('kimball&sizewise');
    localStorage.setItem("kimballcolorsizewisedata", JSON.stringify(presetDataVar));
  }
  //console.log(localStorage.getItem('kimballcolorsizewisedata'));
  var colorSizeData = JSON.parse(localStorage.getItem('kimballcolorsizewisedata'));
  //console.log(colorSizeData);
  var tempSizeSum = {};
  ownClass.closest('td').find('.kcolorswiseqty').each(function (index, el) {
    if ($(this).is(':checked')) {
      var ownVal = $(this).val();
      $.each(colorSizeData.colorsizeQty[ownVal], function (indexx, val) {
        tempSizeSum[indexx.toString()] = tempSizeSum[indexx] != undefined ? parseInt(tempSizeSum[indexx.toString()]) + parseInt(val) : parseInt(val);
        // console.log(indexx);
      });

      tempColorName.push($(this).val().replace('*lot*' + $(this).data('lot') + '*lot*' + $(this).data('kimball'), ''));
      tempKimball.push($(this).data('kimball'));
      tempLot.push($(this).data('lot'));
    }
  });
  ownClass.closest('tr').find('.csize-input').val(0);
  ownClass.closest('tr').find('.csaddition-qty-hidden').val(0);
  ownClass.closest('td').find('.data-content').text(unique(tempColorName).join(', '));
  ownClass.closest('tr').find('.kimball-cell').text(unique(tempKimball).join(', '));
  ownClass.closest('tr').find('.lot-cell').text(unique(tempLot).join(', '));
  if ($('#' + tableClass).find('.addition' + getUniqueId).length > 0) {
    var additionQty = ownClass.closest('tr').find('.row-garmentsqtyextra-input').data('additionval');
    var additionType = ownClass.closest('tr').find('.row-garmentsqtyextra-input').data('additiontype');
    var getValSum = 0;
    $.each(tempSizeSum, function (indexlast, vall) {
      var getValue = 0;
      if (additionType == 'parcent') {
        var getValueInitial = (parseFloat(vall) / 100) * parseFloat(additionQty);
        getValue = parseFloat(vall) + parseFloat(getValueInitial);
      }
      if (additionType == 'qty') {
        getValue = parseFloat(vall) + parseFloat(additionQty);
      }
      ownClass.closest('tr').find('.size-' + indexlast.replace(/[_\W]+/g, "-") + '-input').val(Math.round(getValue));
      ownClass.closest('tr').find('.additionsize-' + indexlast.replace(/[_\W]+/g, "-") + '-input').val(vall);
      getValSum += Math.round(getValue);
    });
    ownClass.closest('tr').find('.row-totalqty-input').val(Math.round(getValSum));
    ownClass.closest('tr').find('.row-garmentsqtyextra-input').val(Math.round(getValSum));
    rowSumCommon('row-garmentsqtyextra-input', 'garmentsextragrandtotal', tableClass);
    // rowSumCommon('garments-qty-input', 'garmentsgrandtotal', tableClass);   
  } else {
    var getValSum = 0;
    $.each(tempSizeSum, function (indexlast, vall) {
      ownClass.closest('tr').find('.size-' + indexlast.replace(/[_\W]+/g, "-") + '-input').val(vall);
      ownClass.closest('tr').find('.additionsize-' + indexlast.replace(/[_\W]+/g, "-") + '-input').val(vall);
      getValSum += parseInt(vall);
    });
    ownClass.closest('tr').find('.row-totalqty-input').val(parseInt(getValSum));
  }
  if ($('.' + tableClass).find('.row-convertion-input').length > 0) {
    convertionCalculate(tableClass, ownClass);
  }
  var totalSum = 0;
  $('.' + tableClass).find('.csaddition-qty-hidden').each(function () {
    totalSum += parseInt($(this).val());
  });
  $('.' + tableClass).find('.garmentsgrandtotal').val(totalSum);
  rowSum(tableClass);

  $('.' + tableClass).find('.maingrid-rowspan').attr('rowspan', $('.' + tableClass).find('.data-row').length);
  $('.' + tableClass).find('.pn-no-cell').attr('rowspan', $('.' + tableClass).find('.data-row').length);
  $('.' + tableClass).find('.order-no-cell').attr('rowspan', $('.' + tableClass).find('.data-row').length);
  $('.' + tableClass).find('.symbol-cell').attr('rowspan', $('.' + tableClass).find('.data-row').length);
}

function kimballColorWiseQty(ownClass, classIndex, tableClass) {
  var getUniqueId = $('.' + tableClass).data('uniqueid');
  if (ownClass.is(':checked')) {
    $('.' + tableClass).find('.kcolorname' + classIndex).prop('disabled', true);
    $('.' + tableClass).find('.kcolornameDiv' + classIndex).css({ 'background': '#aeaeae', 'color': '#808080', 'font-weight': '' });
    $('.' + tableClass).find('.kcolornameDiv' + classIndex + ' input[type="checkbox"]').css('cursor', '');
    $('.' + tableClass).find('.kcolornameDiv' + classIndex + ' label').css('cursor', '');
    ownClass.prop('disabled', false);
    ownClass.parent('div').css({ 'background': '#006d77', 'color': '#fff', 'font-weight': 'bold' });
    ownClass.parent('div').find('input[type="checkbox"]').css('cursor', 'pointer');
    ownClass.parent('div').find('label').css('cursor', 'pointer');
    ownClass.closest('.' + tableClass).find('.data-row').each(function (index) {
      if ($(this).find('input[type="checkbox"]:disabled').length == $('.addrowdisabler').val() && index > 0) {
        $(this).remove();
      }
    });
  } else {
    $('.' + tableClass).find('.kcolorname' + classIndex).prop('disabled', false);
    $('.' + tableClass).find('.kcolornameDiv' + classIndex).css({ 'background': '', 'color': '', 'font-weight': '' });
    $('.' + tableClass).find('.kcolornameDiv' + classIndex + ' input[type="checkbox"]').css('cursor', 'pointer');
    $('.' + tableClass).find('.kcolornameDiv' + classIndex + ' label').css('cursor', 'pointer');
  }
  var totalQty = 0;
  var tempKimball = [];
  var tempLot = [];
  var tempColorName = [];
  ownClass.closest('td').find('.kcolorwiseqty').each(function (index, el) {
    if ($(this).is(':checked')) {
      tempColorName.push($(this).val());
      tempKimball.push($(this).data('kimball'));
      tempLot.push($(this).data('lot'));
      totalQty += $(this).data('qty');
    }
  });
  ownClass.closest('td').find('.data-content').text(unique(tempColorName).join(', '));
  ownClass.closest('tr').find('.kimball-cell').text(unique(tempKimball).join(', '));
  ownClass.closest('tr').find('.lot-cell').text(unique(tempLot).join(', '));
  ownClass.closest('tr').find('.garments-qty-input').val(parseInt(totalQty));
  ownClass.closest('tr').find('.addition-qty-hidden').val(parseInt(totalQty));
  if ($('#' + tableClass).find('.addition' + getUniqueId).length > 0) {
    additionalQty(ownClass.closest('tr').find('.row-garmentsqtyextra-input').data('additionval'), ownClass.closest('tr').find('.row-garmentsqtyextra-input').data('additiontype'), ownClass.closest('tr').find('.addition-qty-hidden').val(), ownClass);
  } else {
    ownClass.closest('tr').find('.row-totalqty-input').val(parseInt(totalQty));
  }
  rowSum(tableClass);
  rowSumCommon('garments-qty-input', 'garmentsgrandtotal', tableClass);
  if ($('.' + tableClass).find('.row-convertion-input').length > 0) {
    convertionCalculate(tableClass, ownClass);
  }
  $('.' + tableClass).find('.maingrid-rowspan').attr('rowspan', $('.' + tableClass).find('.data-row').length);
  $('.' + tableClass).find('.pn-no-cell').attr('rowspan', $('.' + tableClass).find('.data-row').length);
  $('.' + tableClass).find('.order-no-cell').attr('rowspan', $('.' + tableClass).find('.data-row').length);
  $('.' + tableClass).find('.symbol-cell').attr('rowspan', $('.' + tableClass).find('.data-row').length);
}

function sizeWiseQty(ownClass, classIndex, tableClass) {
  var getUniqueId = $('.' + tableClass).data('uniqueid');
  if (ownClass.is(':checked')) {
    $('.' + tableClass).find('.sizename' + classIndex).prop('disabled', true);
    $('.' + tableClass).find('.sizenameDiv' + classIndex).css({ 'background': '#aeaeae', 'color': '#808080', 'font-weight': '' });
    $('.' + tableClass).find('.sizenameDiv' + classIndex + ' input[type="checkbox"]').css('cursor', '');
    $('.' + tableClass).find('.sizenameDiv' + classIndex + ' label').css('cursor', '');
    ownClass.prop('disabled', false);
    ownClass.parent('div').css({ 'background': '#006d77', 'color': '#fff', 'font-weight': 'bold' });
    ownClass.parent('div').find('input[type="checkbox"]').css('cursor', 'pointer');
    ownClass.parent('div').find('label').css('cursor', 'pointer');
    ownClass.closest('.' + tableClass).find('.data-row').each(function (index) {
      if ($(this).find('input[type="checkbox"]:disabled').length == $('.addrowdisabler').val() && index > 0) {
        $(this).remove();
      }
    });
  } else {
    $('.' + tableClass).find('.sizename' + classIndex).prop('disabled', false);
    $('.' + tableClass).find('.sizenameDiv' + classIndex).css({ 'background': '', 'color': '', 'font-weight': '' });
    $('.' + tableClass).find('.sizenameDiv' + classIndex + ' input[type="checkbox"]').css('cursor', 'pointer');
    $('.' + tableClass).find('.sizenameDiv' + classIndex + ' label').css('cursor', 'pointer');
  }
  var totalQty = 0;
  var tempSizeName = [];
  ownClass.closest('td').find('.sizewiseqty').each(function (index, el) {
    if ($(this).is(':checked')) {
      tempSizeName.push($(this).val());
      totalQty += $(this).data('qty');
    }
  });
  ownClass.closest('td').find('.data-content').text(unique(tempSizeName).join(', '));
  ownClass.closest('tr').find('.garments-qty-input').val(parseInt(totalQty));
  ownClass.closest('tr').find('.addition-qty-hidden').val(parseInt(totalQty));
  if ($('#' + tableClass).find('.addition' + getUniqueId).length > 0) {
    additionalQty(ownClass.closest('tr').find('.row-garmentsqtyextra-input').data('additionval'), ownClass.closest('tr').find('.row-garmentsqtyextra-input').data('additiontype'), ownClass.closest('tr').find('.addition-qty-hidden').val(), ownClass);
  } else {
    ownClass.closest('tr').find('.row-totalqty-input').val(parseInt(totalQty));
  }
  rowSum(tableClass);
  rowSumCommon('garments-qty-input', 'garmentsgrandtotal', tableClass);
  if ($('.' + tableClass).find('.row-convertion-input').length > 0) {
    convertionCalculate(tableClass, ownClass);
  }
  $('.' + tableClass).find('.maingrid-rowspan').attr('rowspan', $('.' + tableClass).find('.data-row').length);
  $('.' + tableClass).find('.pn-no-cell').attr('rowspan', $('.' + tableClass).find('.data-row').length);
  $('.' + tableClass).find('.order-no-cell').attr('rowspan', $('.' + tableClass).find('.data-row').length);
  $('.' + tableClass).find('.symbol-cell').attr('rowspan', $('.' + tableClass).find('.data-row').length);
}


function additionalQty(additionVal, additionType, garmentsQty, ownClass) {
  var getValue = 0;
  if (additionType == 'parcent') {
    var getValueInitial = (parseFloat(garmentsQty) / 100) * parseFloat(additionVal);
    getValue = parseFloat(garmentsQty) + parseFloat(getValueInitial);
  }
  if (additionType == 'qty') {
    getValue = parseFloat(garmentsQty) + parseFloat(additionVal);
  }
  ownClass.closest('tr').find('.row-garmentsqtyextra-input').val(Math.round(getValue));
  ownClass.closest('tr').find('.row-totalqty-input').val(Math.round(getValue));
  var tableClass = ownClass.closest('.workorder-table-main').attr('id');
  rowSum(tableClass);
  rowSumCommon('row-garmentsqtyextra-input', 'garmentsextragrandtotal', tableClass);
  // if($('.'+tableClass).find('.row-convertion-input').length > 0){
  //   convertionCalculate(tableClass, ownClass);
  //   rowSum(tableClass);
  // }
}

function convertionCalculate(tableClass, ownClass) {
  // var getType = ownClass.closest('tr').find('.row-convertion-input').data('convertionval');
  var getVal = ownClass.closest('tr').find('.row-convertion-input').val();
  var getUniqueId = $('.' + tableClass).data('uniqueid');
  var qtyUnit = $('.' + tableClass).find('.grandunit').text().toLowerCase();
  var garmentsQty = ownClass.closest('tr').find('.garments-qty-input').val();
  if (garmentsQty < 1) {
    accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Garments qty is required.', 'alert');
  } else {
    if ($('.addition' + getUniqueId).length > 0) {
      var garmentsQty = ownClass.closest('tr').find('.row-garmentsqtyextra-input').val();
    } else {
      if ($('.' + tableClass).find('.colorsizeqty-header').length > 0) {
        var getValSum = 0;
        ownClass.closest('tr').find('.csize-input').each(function (indexx) {
          getValSum += parseInt($(this).val());
        });
        var garmentsQty = getValSum;
      } else {
        var garmentsQty = ownClass.closest('tr').find('.garments-qty-input').val();
      }
    }
    if (ownClass.closest('tr').find('.row-convertion-input').data('convertionval') == undefined) {
      if (ownClass.closest('tr').find('.row-convertion-input').data('calinputtype') == 'divided') {
        var totalQty = parseFloat(garmentsQty) / parseFloat(getVal);
      } else {
        var totalQty = parseFloat(garmentsQty) * parseFloat(getVal);
      }

    } else {
      if (ownClass.closest('tr').find('.row-convertion-input').data('calinputtype') == 'divided') {
        var totalQty = parseFloat(garmentsQty) / parseFloat(getVal);
      } else {
        var totalQty = parseFloat(garmentsQty) * parseFloat(getVal);
      }
      totalQty = totalQty / parseFloat(ownClass.closest('tr').find('.row-convertion-input').data('convertionval'));
    }
    //alert(ownClass.data('convertionval'));
    if (qtyUnit == 'yrds' || qtyUnit == 'gg') {
      ownClass.closest('tr').find('.row-totalqty-input').val(precise_round(totalQty, 2));
    } else {
      ownClass.closest('tr').find('.row-totalqty-input').val(Math.round(totalQty));
    }
    rowSum(tableClass);
  }
}


function inputEnable(ownClass) {
  ownClass.prop('readonly', false);
}

function precise_round(value, decPlaces) {
  var val = value * Math.pow(10, decPlaces);
  var fraction = (Math.round((val - parseInt(val)) * 10) / 10);

  //this line is for consistency with .NET Decimal.Round behavior
  // -342.055 => -342.06
  if (fraction == -0.5) fraction = -0.6;

  val = Math.round(parseInt(val) + fraction) / Math.pow(10, decPlaces);
  return val;
}

function manualGarmentsQty(tableClass, ownClass) {
  var qty = ownClass.val();
  var getUniqueId = $('.' + tableClass).data('uniqueid');
  
  if (ownClass.hasClass('csize-input')) {
    //alert();
    ownClass.closest('.cell').find('.csaddition-qty-hidden').val(parseInt(qty));
    var qtySp = 0;
    ownClass.closest('td').find('.csaddition-qty-hidden').each(function (index, el) {
      qtySp += parseInt($(this).val());
    });
    // if($('#'+tableClass).find('.addition'+getUniqueId).length > 0){
    //   additionalQty(ownClass.closest('tr').find('.row-garmentsqtyextra-input').data('additionval'), ownClass.closest('tr').find('.row-garmentsqtyextra-input').data('additiontype'), qtySp, ownClass);
    // }else{
    var qtyCp = 0;
    ownClass.closest('td').find('.csize-input').each(function (index, el) {
      qtyCp += parseInt($(this).val());
    });
    ownClass.closest('tr').find('.row-totalqty-input').val(parseInt(qtyCp));
    //qtyCp = 0;
    // }
    rowSum(tableClass);
    rowSumCommon('colorsizeqty', 'garmentsgrandtotal', tableClass, 'csaddition-qty-hidden');
    if ($('.' + tableClass).find('.row-convertion-input').length > 0) {
      convertionCalculate(tableClass, ownClass);
    }
  } else {
    ownClass.closest('tr').find('.addition-qty-hidden').val(parseInt(qty));
    if ($('#' + tableClass).find('.addition' + getUniqueId).length > 0) {
      additionalQty(ownClass.closest('tr').find('.row-garmentsqtyextra-input').data('additionval'), ownClass.closest('tr').find('.row-garmentsqtyextra-input').data('additiontype'), ownClass.closest('tr').find('.addition-qty-hidden').val(), ownClass);
    } else {
      ownClass.closest('tr').find('.row-totalqty-input').val(parseInt(qty));
    }
    rowSum(tableClass);
    rowSumCommon('garments-qty-input', 'garmentsgrandtotal', tableClass);

    if ($('.' + tableClass).find('.row-convertion-input').length > 0) {
      convertionCalculate(tableClass, ownClass);
    }
  }
}

function manualExtraGarmentsQty(tableClass, ownClass) {
  var qty = ownClass.val();
  var getUniqueId = $('.' + tableClass).data('uniqueid');
  ownClass.closest('tr').find('.row-totalqty-input').val(parseInt(qty));
  rowSum(tableClass);
  rowSumCommon("row-garmentsqtyextra-input", "garmentsextragrandtotal", tableClass);
  if ($('.' + tableClass).find('.row-convertion-input').length > 0) {
    convertionCalculate(tableClass, ownClass);
  }
}

function colorPicker(ownClass, tableClass) {
  $('.right-popup').css('display', 'none');
  ownClass.closest('td').find('.right-popup').css('display', 'block');
}

function closePicker() {
  $('.right-popup').css('display', 'none');
}

function allCheck(tableClass, ownClass) {
  if (ownClass.is(':checked')) {
    $('.' + tableClass).find('.single-check input').each(function (index) {
      $(this).prop('checked', true);
    });
  } else {
    $('.' + tableClass).find('.single-check input').each(function (index) {
      $(this).prop('checked', false);
    });
  }
}

function arraySum($array) {
  var sum = 0;
  $.each($array, function (index, val) {
    sum += val;
  });
  return sum;
}

function singleCheck(tableClass, ownClass, event) {
  event.stopPropagation();
  event.preventDefault();
  var tempChecked = [];
  if (ownClass.is(':checked')) {
    $('.' + tableClass).find('.single-check input').each(function (index) {
      if ($(this).is(':checked') == false) {
        tempChecked.push('notchecked');
      }
    });
    if (tempChecked.length == 0) {
      $('.' + tableClass).find('.all-checked input').prop('checked', true);
    } else {
      $('.' + tableClass).find('.all-checked input').prop('checked', false);
    }
  } else {
    $('.' + tableClass).find('.all-checked input').prop('checked', false);
  }
}

function columnRemove(tableClass, columnClass) {
  $('.' + tableClass).find('.' + columnClass + '-header').remove();
  $('.' + tableClass).find('.' + columnClass).remove();
}

function workorderViewOperation(table, operationType, redirectlink = '') {
  preloaderStart();
  $('.success-notification').css('display', 'none');
  var dataId = [];
  $('.' + table).find('.single-check input').each(function (index) {
    if ($(this).is(':checked')) {
      dataId.push($(this).val());
    }
  });
  if (dataId.length > 0) {
    $.ajax({
      type: 'POST',
      url: 'action/workorder-action.php',
      dataType: 'json',
      data: { 'formName': operationType, 'workorderid': dataId.join(','), 'type': 'bulk' },
      success: function (getResponse) {
        if (getResponse['status'] == 'success') {
          preloaderClose();
          $('.' + table).find('.single-check input').each(function (index) {
            if ($(this).is(':checked')) {
              $(this).closest('tr').remove();
            }
          });
          var notifyDiv = '';
          notifyDiv += '<p style="font-size: 15px;"><span class="mif-done_all mif-lg"></span> Workorder ' + operationType + ' successfully.<p>';
          notifyDiv += '<div class="d-flex flex-justify-center">';
          if (redirectlink.length > 0) {
            notifyDiv += '<a href="' + redirectlink + '" class="image-button info fg-white icon-right" style="height: 30px;"><span class="mif-arrow-right icon" style="height: 30px; line-height: 30px; font-size: .9rem; width: 32px;"></span><span class="caption">View ' + operationType + ' workorder</span></a>';
          }
          notifyDiv += '</div>';
          accessoriesNotify(notifyDiv, '', 350);
        }
      }
    });
  } else {
    preloaderClose();
    accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! You did not select any workorder.', 'alert');
  }
}

function workorderManualUpdate(operationType, id) {
  $.ajax({
    type: 'POST',
    url: 'action/workorder-action.php',
    dataType: 'json',
    data: { 'formName': operationType, 'workorderid': id, 'type': 'single' },
    success: function (getResponse) {
      if (getResponse['status'] == 'success') {
        // preloaderClose();
        window.location.href = "workorder.php?page=details&id=" + getResponse['id'];
      }
    }
  });


}

function preloaderClose() {
  $('.preloader').css('display', 'none');
}
function preloaderStart() {
  $('.preloader').css('display', 'block');
}

function postData(ownClass, actionUrl) {
  event.preventDefault();
  preloaderStart();
  $('.success-notification').css('display', 'none');
  $('.invalid_feedback').css('display', 'none');
  var form = $('.' + ownClass);
  var error = [];
  $('.invalid_feedback').css('display', 'none');

  form.find('.required-field input').each(function (index, el) {
    if ($(this).val().length == 0) {
      error.push('1');
      $(this).closest('.form-group').find('.invalid_feedback').css('display', 'block');
    }
  });

  form.find('.required-field-select select').each(function (index, el) {
    if ($(this).val() == '0') {
      error.push('1');
      $(this).closest('.form-group').find('.invalid_feedback').css('display', 'block');
    }
  });

  if (error.length == 0) {
    if (confirm('Are you sure to insert this data?')) {
      var formData = form.serializeArray();
      $.ajax({
        type: 'POST',
        url: actionUrl,
        data: formData,
        dataType: 'json',
        success: function (getresponse) {
          preloaderClose();
          if (getresponse['status'] == 'success') {
            $('.success-notification').css('display', 'block');
            var notifyDiv = '';
            notifyDiv += '<p style="font-size: 15px;"><span class="mif-done_all mif-lg"></span> ' + getresponse['successmsg'] + '<p>';
            notifyDiv += '<div class="d-flex flex-justify-between">';
            if (getresponse['viewUrl'] != '') {
              notifyDiv += '<a href="' + getresponse['viewUrl'] + '" class="image-button dark fg-white" style="height: 30px;"><span class="mif-eye icon" style="height: 30px; line-height: 30px; font-size: .9rem; width: 32px;"></span><span class="caption">View Now</span></a>';
            }
            notifyDiv += '<a href="' + getresponse['createUrl'] + '" class="image-button success" style="height: 30px;"><span class="mif-plus icon" style="height: 30px; line-height: 30px; font-size: .9rem; width: 32px;"></span><span class="caption">New</span></a>';
            notifyDiv += '</div>';
            accessoriesNotify(notifyDiv, 'success-common', 350);
          } else if (getresponse['status'] == 'errors') {
            if (getresponse['value'] == 1) {
              accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! CSRF Token verification faild. Refresh your browser and try again.<p>', 'alert');
            } else if (getresponse['value'] == 2) {
              accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! data is already exists.<p>', 'alert');
            } else {
              accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Something went wrong. Refresh your browser and try again.<p>', 'alert');
            }
          } else {
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Something went wrong. Refresh your browser and try again.<p>', 'alert');
          }
        }
      });
    } else {
      preloaderClose();
    }
  } else {
    preloaderClose();
    accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! One or more errors found.</p>', 'alert');
  }
}


function updateData(ownClass, actionUrl, redirecturl) {
  event.preventDefault();
  preloaderStart();
  $('.invalid_feedback').css('display', 'none');
  var form = ownClass;
  var error = [];
  $('.invalid_feedback').css('display', 'none');

  form.find('.required-field input').each(function (index, el) {
    if ($(this).val().length == 0) {
      error.push('1');
      $(this).closest('.form-group').find('.invalid_feedback').css('display', 'block');
    }
  });

  form.find('.required-field-select select').each(function (index, el) {
    if ($(this).val() == '0') {
      error.push('1');
      $(this).closest('.form-group').find('.invalid_feedback').css('display', 'block');
    }
  });

  if (error.length == 0) {
    if (confirm('Are you sure to update?')) {
      var formData = form.serializeArray();
      $.ajax({
        type: 'POST',
        url: 'action/' + actionUrl + '-action.php',
        data: formData,
        dataType: 'json',
        success: function (getresponse) {
          preloaderClose();
          if (getresponse['status'] == 'success') {
            alert(getresponse['successmsg']);
            window.location.href = redirecturl;
          } else if (getresponse['status'] == 'errors') {
            if (getresponse['value'] == 1) {
              accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! CSRF Token verification faild. Refresh your browser and try again.<p>', 'alert');
            } else {
              accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Something went wrong. Refresh your browser and try again.<p>', 'alert');
            }
          } else {
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Something went wrong. Refresh your browser and try again.<p>', 'alert');
          }
        }
      });
    } else {
      preloaderClose();
    }
  } else {
    preloaderClose();
    accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! One or more errors found.</p>', 'alert');
  }
}


function changePassword(ownClass, actionUrl) {
  event.preventDefault();
  preloaderStart();
  var form = $('.' + ownClass);
  var error = [];
  $('.invalid_feedback').css('display', 'none');
  form.find('.required-field').each(function (index, el) {
    if ($(this).val().length == 0) {
      error.push('1');
      $(this).closest('.required-cell').find('.invalid_feedback').css('display', 'block');
    }
  });
  if (error.length == 0) {
    if (confirm('Are you sure to update password?')) {
      var formData = form.serializeArray();
      $.ajax({
        type: 'POST',
        url: 'action/' + actionUrl + '-action.php',
        data: formData,
        dataType: 'json',
        success: function (getresponse) {
          preloaderClose();
          if (getresponse['status'] == 'success') {
            var notifyDiv = '';
            notifyDiv += '<p style="font-size: 15px;"><span class="mif-done_all mif-lg"></span> Password updated successfully.<p>';
            accessoriesNotify(notifyDiv, 'success-common', 350);
            $('.required-field').val('');
          } else if (getresponse['status'] == 'errors') {
            if (getresponse['value'] == 1) {
              accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! CSRF Token verification faild. Refresh your browser and try again.<p>', 'alert');
            } else if (getresponse['value'] == 2) {
              accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Your current password is worong.<p>', 'alert');
            } else if (getresponse['value'] == 3) {
              accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! New password and confirm password did not matched.<p>', 'alert');
            } else {
              accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Something went wrong. Refresh your browser and try again.<p>', 'alert');
            }
          } else {
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Something went wrong. Refresh your browser and try again.<p>', 'alert');
          }
        }
      });
    } else {
      preloaderClose();
    }
  } else {
    preloaderClose();
    accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! One or more errors found.</p>', 'alert');
  }
}

function deleteRow(id, form, action, ownClass, previousPage = '') {
  preloaderStart();
  if (confirm('Are you sure to delete this row?')) {
    $.ajax({
      type: 'POST',
      url: "action/" + action + "-action.php",
      data: { 'formName': 'delete-' + form, 'id': id },
      dataType: 'json',
      success: function (getresponse) {
        preloaderClose();
        if (getresponse['status'] == 'success') {
          if (previousPage.length == 0) {
            var notifyDiv = '';
            notifyDiv += '<p style="font-size: 15px;"><span class="mif-done_all mif-lg"></span> ' + getresponse['successmsg'] + '<p>';
            accessoriesNotify(notifyDiv, '', 350);
            ownClass.closest('tr').fadeOut('fast');
          } else {

            alert('Work order has been deleted successfully.');
            window.location.href = previousPage;
          }
        } else if (getresponse['status'] == 'errors') {
          if (getresponse['value'] == 1) {
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> ' + getresponse['errormsg'] + '<p>', 'alert');
          } else if (getresponse['value'] == 2) {
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Invalid row id.<p>', 'alert');
          } else {
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Something went wrong. Refresh your browser and try again.<p>', 'alert');
          }
        } else {
          accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Something went wrong. Refresh your browser and try again.<p>', 'alert');
        }
      }
    });
  } else {
    preloaderClose();
  }
}

function rowRollBack(id, form, action, ownClass, reloadpage = '') {
  preloaderStart();
  if (confirm('Are you sure to move this row?')) {
    $.ajax({
      type: 'POST',
      url: "action/" + action + "-action.php",
      data: { 'formName': 'rollback-' + form, 'id': id },
      dataType: 'json',
      success: function (getresponse) {
        preloaderClose();
        if (getresponse['status'] == 'success') {
          if (reloadpage.length == 0) {
            var notifyDiv = '';
            notifyDiv += '<p style="font-size: 15px;"><span class="mif-done_all mif-lg"></span> ' + getresponse['successmsg'] + '<p>';
            accessoriesNotify(notifyDiv, '', 350);
            ownClass.closest('tr').fadeOut('fast');
          } else {
            alert('Work order moved successfully.');
            window.location.href = reloadpage;
          }
        } else if (getresponse['status'] == 'errors') {
          if (getresponse['value'] == 1) {
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> ' + getresponse['errormsg'] + '<p>', 'alert');
          } else if (getresponse['value'] == 2) {
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Invalid row id.<p>', 'alert');
          } else {
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Something went wrong. Refresh your browser and try again.<p>', 'alert');
          }
        } else {
          accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Something went wrong. Refresh your browser and try again.<p>', 'alert');
        }
      }
    });
  } else {
    preloaderClose();
  }
}

function userActiveInactive(id, form, action, operate) {
  preloaderStart();
  if (confirm('Are you sure to change user status?')) {
    $.ajax({
      type: 'POST',
      url: "action/" + action + "-action.php",
      data: { 'formName': 'activeinactive-' + form, 'id': id, 'operate': operate },
      dataType: 'json',
      success: function (getresponse) {
        preloaderClose();
        if (getresponse['status'] == 'success') {
          window.location.href = 'user-permission.php?page=all-users';
        }
      }
    });
  } else {
    preloaderClose();
  }

}


function workorderCopy(numberType, workOrderId, csrf) {
  var ordernumber = prompt("Enter New " + numberType, '');
  if (ordernumber == '') {
    accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! ' + numberType.toLowerCase() + ' is empty.<p>', 'alert');
  } else {
    if ($.trim(ordernumber).toLowerCase() == 'block' || $.trim(ordernumber).toLowerCase() == 'store') {
      window.open("workorder.php?page=copy&to=" + workOrderId + "&from=" + $.trim(ordernumber).toLowerCase(), "_blank");
    } else {
      $.ajax({
        type: 'POST',
        url: 'action/workorder-action.php',
        dataType: 'json',
        data: { 'formName': 'ordersearchforcopy', 'ordertype': numberType, 'ordernumber': ordernumber, 'csrf': csrf, 'workorderid': workOrderId },
        success: function (getResponse) {
          if (getResponse['status'] == 'notfound') {
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! ' + numberType.toLowerCase() + ' <strong>(' + ordernumber + ')</strong> is invalid or already shipped.<p>', 'alert');
          } else if (getResponse['status'] == 'success') {
            window.open("workorder.php?page=copy&to=" + workOrderId + "&from=" + ordernumber, "_blank");
          } else if (getResponse['status'] == 'csrfmissing') {
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! CSRF Token verification faild. Refresh your browser and try again.<p>', 'alert');
          } else if (getResponse['status'] == 'excelfile') {
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! You can not copy excel uploaded workorder.<p>', 'alert');
          } else {
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Something went wrong! Refresh your browser and try again.<p>', 'alert');
          }
        }
      });
    }
  }
}


function drmEntryPrePopup(diNo, csrf) {
  Metro.dialog.create({
    title: "Enter P.O. Number",
    clsDialog: 'drmentrypre-popup',
    content: "<div><input type='text' data-role='input' class='input-small ponumberFinder' oninput='poFinder($(this))' placeholder='Enter P.O. Number' data-autocomplete=''></div>",
    closeButton: true,
    actions: [
      {
        caption: "Submit",
        cls: "success",
        onclick: function () {
          if ($('.ponumberFinder input').val().length > 0) {
            drmEntry(diNo, csrf, $('.ponumberFinder input').val());
          } else {
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! P.O. Number is required.<p>', 'alert');
          }
        }
      },
      {
        caption: "Close",
        cls: "js-dialog-close",
        onclick: function () {
        }
      }
    ]
  });
}

function poFinder(ownClass) {
  var val = $.trim(ownClass.val());
  $('.ponumberFinder').find('.autocomplete-list').empty();
  $('.ponumberFinder input').attr('data-autocomplete', '');
  if (val.length > 0) {
    $.ajax({
      type: 'POST',
      url: 'action/daily-receive-materials-action.php',
      dataType: 'json',
      data: { 'formName': 'posearch', 'ponumber': val },
      success: function (getResponse) {
        if (getResponse['status'] == 'success') {
          var appender = '';
          $.each(getResponse['data'], function (index, vall) {
            appender += '<div class="item" data-autocomplete-value="' + vall + '"><strong></strong>' + vall + '</div>';
          });
          $('.ponumberFinder').find('.autocomplete-list').append(appender);
          $('.ponumberFinder').find('.autocomplete-list').css('display', 'block');
          $('.ponumberFinder input').attr('data-autocomplete', getResponse['data'].join(','));
        }
      }
    });
  }
}


function drmEntry(diNo, csrf, poNo) {
  if ($.trim(poNo).length == 0) {
    accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! P.O. Number is empty.<p>', 'alert');
  } else {
    $.ajax({
      type: 'POST',
      url: 'action/daily-receive-materials-action.php',
      dataType: 'json',
      data: { 'formName': 'drmentry', 'ponumber': $.trim(poNo), 'csrf': csrf, 'dino': diNo },
      success: function (getResponse) {
        if (getResponse['status'] == 'ponotfound') {
          accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! P.O. number <strong>(' + $.trim(poNo) + ')</strong> is invalid.<p>', 'alert');
        } else if (getResponse['status'] == 'dinotfound') {
          accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! DI id is invalid. Refresh your browser and try again.<p>', 'alert');
        } else if (getResponse['status'] == 'notaccepted') {
          accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! Workorder not yet accepted by store.<p>', 'alert');
        } else if (getResponse['status'] == 'success') {
          window.location.href = "daily-receive-material.php?page=new-receive&di=" + diNo + "&po=" + $.trim(poNo);
        } else if (getResponse['status'] == 'csrfmissing') {
          accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Opps! CSRF Token verification faild. Refresh your browser and try again.<p>', 'alert');
        } else {
          accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-lg"></span> Something went wrong! Refresh your browser and try again.<p>', 'alert');
        }
      }
    });
  }
}

function drmInputCalculate(ownClass, tableClass) {
  var inputQty = parseFloat(ownClass.val());
  var requiredQty = parseFloat(ownClass.closest('tr').find('.wo-required-qty').text());
  var prevRcvQty = 0.00;
  if (ownClass.closest('tr').find('.prev-rcv-qty').length > 0) {
    ownClass.closest('tr').find('.prev-rcv-qty').each(function (index, el) {
      prevRcvQty += parseFloat($(this).text());
    });
  }
  var totalReceiveQty = prevRcvQty + inputQty;
  var recieveDue = requiredQty - totalReceiveQty;
  ownClass.closest('tr').find('.drm-row-total').text(totalReceiveQty);
  ownClass.closest('tr').find('.drm-row-due-total').text(recieveDue);
  rowSumDrm(tableClass);
  rowSumCommonDrm('drm-row-total', 'drm-row-grand-total', tableClass);
  rowSumCommonDrm('drm-row-due-total', 'drm-row-due-grand-total', tableClass);
}

function rowSumDrm(tableClass) {
  var rowQty = 0;
  $('.' + tableClass).find('tr .recive-qty').each(function (index) {
    var ownValue = parseFloat($(this).val() == '' || isNaN($(this).val()) ? 0 : $(this).val());
    rowQty += ownValue;
  });
  if ($('.' + tableClass).find('.grandunit').text().toLowerCase() == "con's" || $('.' + tableClass).find('.grandunit').text().toLowerCase() == "rolls" || $('.' + tableClass).find('.grandunit').text().toLowerCase() == 'pcs') {
    $('.' + tableClass).find('.recive-qty-grand').val(Math.round(rowQty));
  } else {
    $('.' + tableClass).find('.recive-qty-grand').val(precise_round(rowQty, 2));
  }
}

function rowSumCommonDrm(rowClass, sumOutputClass, tableClass, csize = '') {
  var rowQty = 0;
  $('.' + tableClass).find('.drm-data-row').each(function (index) {
    var ownVal = 0;
    ownVal = parseFloat($(this).find('.' + rowClass).text());
    rowQty += ownVal;
  });
  $('.' + tableClass).find('.' + sumOutputClass).text(rowQty);
}

function inputFieldAppend(ownClass, parentClass) {
  event.stopPropagation();
  if (ownClass.find('input').length == 0) {
    var inputField = '';
    var value = ownClass.text();
    inputField = "<input type='text' class='appended-input input-small' style='max-width:150px;' value='" + value + "' data-old='" + value + "' onblur='appendValue(\"" + parentClass + "\", $(this))'>";
    ownClass.closest('td').find('.' + parentClass).html('');
    ownClass.closest('td').find('.' + parentClass).append(inputField);
  }
}


function appendCommon(ownClass) {
  event.stopPropagation();
  if (ownClass.find('input').length == 0) {
    var text = ownClass.text();
    var inputField = '';
    inputField = "<input type='text' class='appended-input input-small' style='max-width:150px;' value='" + text + "' data-old='" + text + "' onblur='appendValue(\"unitallpaste\", $(this))'>";
    ownClass.html('');
    ownClass.append(inputField);
    ownClass.find('input').focus();
  }
}

function appendValue(parentClass, ownClass) {
  event.stopPropagation();
  var value = ownClass.val();
  var oldValue = ownClass.data('old');
  if (value.length > 0) {
    if (parentClass == 'unitallpaste') {
      $('.totalqty').find('span').html(value);
      $('.grandunit').html(value);
    } else {
      ownClass.parent().html(value);
    }
  } else {
    if (parentClass == 'unitallpaste') {
      $('.totalqty').find('span').html(oldValue);
      $('.grandunit').html(oldValue);
    } else {
      ownClass.parent().html(oldValue);
    }
  }
}

function receiveDetails(id) {
  var htmlData = '<table class="table striped table-border cell-border subcompact" data-id="' + id + '" style="font-size: 18px;">';
  $.ajax({
    type: 'POST',
    url: 'action/daily-receive-materials-action.php',
    dataType: 'html',
    data: { 'formName': 'deatilspopup', 'id': id },
    success: function (getResponse) {
      Metro.dialog.create({
        title: "Material Receive Details.",
        clsDialog: 'drm-details-popup',
        // width : 'auto',
        content: "<div>" + htmlData + getResponse + "</table></div>",
        closeButton: true,
      });
    }
  });
}

function copyDrm(tableClass) {
  $('.' + tableClass).find('tr .recive-qty').each(function (index, el) {
    $(this).val($(this).closest('tr').find('.wo-required-qty').text());
    drmInputCalculate($(this), tableClass);
  });
}

// ===================onclick insert=======================
function insertDropdown(id, table, id_field, name_field, sl_field) {
  var name = $('#' + id).val();
  var table = table;
  var id_field = id_field;
  var name_field = name_field;
  var sl_field = sl_field;
  if (name == null || name == '') {
    alert('Data Can\'t Submit Empty!');
  } else {
    $.ajax({
      type: 'POST',
      url: "costsheetSubmit.php?dropdown=insert",
      data: { 'name': name, 'table': table, 'id_field': id_field, 'name_field': name_field, 'sl_field': sl_field },
      // data: {'name':name},
      success: function (response) {
        // alert ("Successfully Inserted");
        accessoriesNotify('<p style="font-size: 15px;"><span class="mif-success mif-small"></span> Successfully Inserted.<p>', 'success');
        $('#' + id).val('');
      }
    });
  }
}
// ===================onclick Update=======================
function updateDropdown(id, table, id_field, name_field, update_id) {
  var name = $('#' + id).val();
  var table = table;
  var id_field = id_field;
  var name_field = name_field;
  var update_id = update_id;
  if (name == null || name == '') {
    alert('Data Can\'t Submit Empty!');
  } else {
    $.ajax({
      type: 'POST',
      url: "costsheetSubmit.php?dropdown=update",
      data: { 'name': name, 'table': table, 'id_field': id_field, 'name_field': name_field, 'update_id': update_id },
      // data: {'name':name},
      success: function (response) {
        // alert ("Successfully updated");
        accessoriesNotify('<p style="font-size: 15px;"><span class="mif-success mif-small"></span> Successfully Updated.<p>', 'success');
        $('#' + id).val('');
      }
    });
  }
}

function dependendFetchData(id, value, table, optionId, optionValue, buyer_id) {
  $.ajax({
    url: "costsheetSubmit.php?dependenddropdown=fetch&table=" + table + "&optionId=" + optionId + "&optionValue=" + optionValue + "&value=" + value + "&buyer_id=" + buyer_id,
    cache: false,
    success: function (response) {
      var res = JSON.parse(response);
      console.log(res);
      // $('#'+id).empty().append(res);
      // $('#'+id).html(response);

      // $('#'+id).empty();
      $('#' + id).append(res);
      // clearAndAppendContent(id,res);
    }
  });
}

function getDepartment(val, pageType) {
  var pageType = pageType;
  $.ajax({
    type: "POST",
    url: "costsheetSubmit.php?getDepartment=fetch&pageType=" + pageType,
    // data:{buyer_id:val},
    data: 'buyer_id=' + val,
    success: function (data) {
      if (pageType == 'edit') {
        $("#department").append(data);
      } else {
        $("#department").html(data);
      }
    }
  });
}

function getSeasson(val, pageType) {
  var pageType = pageType;
  $.ajax({
    type: "POST",
    url: "costsheetSubmit.php?getSeasson=fetch&pageType=" + pageType,
    // data:{buyer_id:val},
    data: 'buyer_id=' + val,
    success: function (data) {
      if (pageType == 'edit') {
        $("#seasson").append(data);
      } else {
        $("#seasson").html(data);
      }
    }
  });
}


function publishData(id) {
  // alert('Do you publish it!');
  // var result =  confirm('Are You Sure to Delete it?');
  var result = Swal.fire({
    title: "Are you sure to publish it?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, Published it!"
  }).then((result) => {
    if (result.isConfirmed) {
      //     Swal.fire({
      //       title: "Published!",
      //       text: "Your Cost sheet is published.",
      //       icon: "success"
      //     });
      //   }
      // });

      // if(result){
      var mpm_no = id;
      // alert(mpm_no);
      // if(mpm_no == null || mpm_no==''){
      //     alert('Data Can\'t Submit Empty!');
      // }else{
      $.ajax({
        type: 'POST',
        url: "costsheetSubmit.php?pageType=publish",
        data: { 'mpm_no': mpm_no },
        // data: {'name':name},
        success: function (response) {
          var res = JSON.parse(response);
          // alert ("Successfully Inserted");
          if (res.success == true) {
            // accessoriesNotify('<p style="font-size: 15px;"><span class="mif-success mif-small"></span> Successfully Published.<p>', 'success');
            $('#' + id).css('background-color', '#60a917');
            $('#' + id + 'edit').hide();
          }
          else {
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-small"></span> Failed to published.<p>', 'warning');
          }
        }
      });
      // }
      // }
      Swal.fire({
        title: "Published!",
        text: "Your Cost sheet is published.",
        icon: "success"
      });
    }
  });

}
function approvalData(id) {
  var result = Swal.fire({
    title: "Are you sure to approve it?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, Approve it!"
  }).then((result) => {
    if (result.isConfirmed) {
      var mpm_no = id;
      $.ajax({
        type: 'POST',
        url: "costsheetSubmit.php?pageType=approve",
        data: { 'mpm_no': mpm_no },
        success: function (response) {
          var res = JSON.parse(response);
          if (res.success == true) {
            $('#' + id).css('background-color', '#60a917');
            $('#' + id + 'edit').hide();
          }
          else {
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-small"></span> Failed to approve.<p>', 'warning');
          }
        }
      });
      Swal.fire({
        title: "Approved!",
        text: "Your Cost sheet is approved.",
        icon: "success"
      });
    }
  });
}


function acceptData(id) {
  // alert(id);
  var result = Swal.fire({
    title: "Are you sure to Accept it?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, Accept it!"
  }).then((result) => {
    if (result.isConfirmed) {
      var mpm_no = id;
      $.ajax({
        type: 'POST',
        url: "costsheetSubmit.php?pageType=approve_manager",
        data: { 'mpm_no': mpm_no },
        success: function (response) {
          var res = JSON.parse(response);
          if (res.success == true) {
            $('#' + id).css('background-color', '#60a917');
            $('#' + id + 'edit').hide();
          }
          else {
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-small"></span> Failed to approve.<p>', 'warning');
          }
        }
      });
      Swal.fire({
        title: "Accepted!",
        text: "Your Cost sheet is accepted.",
        icon: "success"
      });
    }
  });

}

function disableEnterSubmit(event) {
  if (event.key === 'Enter') {
    event.preventDefault();
  }
}

// ====================Gate Pass =============================
function headOfDepartmentData(id) {
  var result = Swal.fire({
    title: "Are you sure to Accept it?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, Accept!"
  }).then((result) => {
    if (result.isConfirmed) {
      var gp_master_id = id;
      $.ajax({
        type: 'POST',
        url: "gatepassController.php?pageType=gp_headofdepartment",
        data: { 'gp_master_id': gp_master_id },
        success: function (response) {
          var res = JSON.parse(response);
          if (res.success == true) {
            // $('#'+id).css('background-color','#004d6f');
            $('.vw-page').css('background-color', '#004d6f !important');
            $('#departmentalhead').css('color', 'green');
            $('.headofdepartment').hide();
          }
          else {
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-small"></span> Failed to published.<p>', 'warning');
          }
        }
      });
      Swal.fire({
        title: "Accepted By Head of Department!",
        text: "Your Gate Pass is Accepted.",
        icon: "success"
      });
    }
  });

}

function inventoryData(id) {
  var result = Swal.fire({
    title: "Are you sure to Receive it?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, Receive !"
  }).then((result) => {
    if (result.isConfirmed) {
      var gp_master_id = id;
      $.ajax({
        type: 'POST',
        url: "gatepassController.php?pageType=gp_inventory",
        data: { 'gp_master_id': gp_master_id },
        success: function (response) {
          var res = JSON.parse(response);
          if (res.success == true) {
            $('#' + id).css('background-color', '#004d6f');
            // $('.inventory-page').css('background-color','#004d6f !important');
            $('#inventory').css('color', 'green');
            // $('#'+id).hide();
            $('.inventory').hide();
          }
          else {
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-small"></span> Failed to published.<p>', 'warning');
          }
        }
      });
      Swal.fire({
        title: "Received By Inventory!",
        text: "Your Gate Pass is Received.",
        icon: "success"
      });
    }
  });

}

function securitypassData(id) {
  var result = Swal.fire({
    title: "Are you sure to Approve it?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, Approve!"
  }).then((result) => {
    if (result.isConfirmed) {
      var gp_master_id = id;
      $.ajax({
        type: 'POST',
        url: "gatepassController.php?pageType=gp_security_pass",
        data: { 'gp_master_id': gp_master_id },
        success: function (response) {
          var res = JSON.parse(response);
          if (res.success == true) {
            // $('#'+id).css('background-color','#004d6f !important');
            $('.security-page').css('background-color', '#004d6f !important');
            $('#security').css('color', 'green');
            // $('#'+id).hide();
            $('.security').hide();
          }
          else {
            accessoriesNotify('<p style="font-size: 15px;"><span class="mif-warning mif-small"></span> Failed to published.<p>', 'warning');
          }
        }
      });
      Swal.fire({
        title: "Approved!",
        text: "Your Gate Pass is Approved.",
        icon: "success"
      });
    }
  });

}
// ================Gate Pass=============================

