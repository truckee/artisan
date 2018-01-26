/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

var urlArray = [];
var ticketDialog;
var nontaxDialog;
var addAmend;
var ticketAddParams = {};
var ticketEditParams = {};
var nontaxAddParams = {};
var nontaxEditParams = {};

$(document).ready(function () {
    //initialize parameters
    ticketDialog = $("#ticketDialog");
    nontaxDialog = $("#nontaxDialog");
    addAmend = $('#tickets');
    nontaxAddParams = {title: 'Add nontaxable item(s)', success: 'Nontaxable added!'};
    nontaxEditParams = {title: 'Edit nontaxable item(s)', success: 'Nontaxable updated!', update: $('#nontax_amount')};
    ticketAddParams = {title: 'Add ticket', success: 'Ticket added!'};
    ticketEditParams = {title: 'Edit ticket', update: $('#amount'), success: 'Ticket updated!'};

    $flashError = $("div.alert");
    if ($.trim($flashError.text()) === '') {
        $flashError.remove();
    }

//  a hack to remove bogus HTML on validation errors
    $('.control-label').each(function () {
        if ($.isNumeric(($(this).html()))) {
            $(this).parent().parent().remove();
        }
    });

    $('.js-datepicker').datepicker({
        format: 'mm/dd/yyyy'
    });

    $('#menuToggle').click(function () {
        window.print();
    });

    $(document).on('click', '#addTicket', function () {
        urlArray = dialogUrl(this.id);
        receiptEdit(ticketAddParams, ticketDialog, 'add');
    });

    $(document).on('click', '[id^=editTicket]', function () {
        url = dialogUrl(this.id);
        receiptEdit(ticketEditParams, ticketDialog, 'edit');
    });

    $(document).on('click', '#addNontaxItem', function () {
        url = dialogUrl(this.id);
        receiptEdit(nontaxAddParams, nontaxDialog, 'add');
    });

    $(document).on('click', '#editNontaxItem', function () {
        url = dialogUrl(this.id);
        receiptEdit(nontaxEditParams, nontaxDialog, 'edit');
    });

    nontaxDialog.keydown(function(event){
    if(event.keyCode === 13) {
      event.preventDefault();
      return false;
    }
  });


//prevent adding more than one nontax item
    nontaxDialog.dialog({
        autoOpen: false,
        close: function () {
            if (0 < parseInt($('#' + nontaxEditParams.update.prop('id')).text())) {
                $("#addNontaxItem").hide();
            }
            if (0 === parseInt($('#' + nontaxEditParams.update.prop('id')).text())) {
                $("#nonTaxEditRow").hide();
                $("#addNontaxItem").show();
            }
        }
    }
    );
    if ($('#nonTaxEditRow').length > 0) {
        $('#addNontaxItem').hide();
    } else {
        $('#addNontaxItem').show();
    }

//use ticket number to identify artist, or not found
    $(document).on("blur", 'input[name$="[ticket]"]' + '', function () {
        $ticket = $('#ticket_ticket').val();
        nowAt = $(location).attr('pathname');
        receiptAt = nowAt.indexOf('/receipt');
        findTicketUrl = nowAt.slice(0, receiptAt) + '/ticket/findTicket/' + $ticket;
        $.get(findTicketUrl, function (data) {
            $('#ticket_artist').val(data);
        });
    });
});

function dialogUrl(id) {
    entityId = $('#' + id).data('entityid');
    currLoc = $(location).attr('href');
    receiptAt = currLoc.indexOf('/receipt');
    prefix = currLoc.substr(0, receiptAt);
    //use substring to avoid entity id suffix
    switch (id.substr(0, 7)) {
        case 'addTick':
            urlArray['url'] = prefix + '/ticket/add/' + entityId;
            break;
        case 'editTic':
            urlArray['url'] = prefix + '/ticket/edit/' + entityId;
            urlArray['entityId'] = entityId;
            break;
        case 'addNont':
            urlArray['url'] = prefix + '/nontax/addAmount/' + entityId;
            urlArray['entityId'] = entityId;
            break;
        case 'editNon':
            urlArray['url'] = prefix + '/nontax/editAmount/' + entityId;
            urlArray['entityId'] = entityId;
            break;
        default:
            break;
    }

    return urlArray;
}

function receiptEdit(params, addDialog, action) {
    if ('Edit ticket' === params.title) {
        params.update = $('#amount' + urlArray['entityId']);
    }
    $.get(urlArray['url'], function (data) {
        addDialog.dialog({
            resizable: true,
            modal: true,
            width: '40%',
            title: params.title,
            buttons: [
                {
                    text: 'Submit',
                    id: "submit",
                    class: "btn-xs btn-primary",
                    click: function () {
                        var formData = $("form").serialize();
                        $.post(urlArray['url'], formData, function (response) {
                            //if validation error:
                            if (response.indexOf('<form') === 0) {
                                addDialog.html(response);
                                return;
                            }
                            //return ticket
                            if ('add' === action) {
                                ticket = $.parseJSON(response);
                                addAmend.append($.trim(ticket.replace(/[\t\n]+/g, ' ')));
                            }
                            if ('edit' === action) {
                                params.update.text(response);
                            }
                            addDialog.html(params.success);
                            $("#submit").hide();
                        });
                    }
                },
                {
                    text: 'Close',
                    id: "close",
                    class: "btn-xs btn-primary",
                    click: function () {
                        $(this).dialog("close");
                    }
                }
            ]
        });
        addDialog.html(data);
        addDialog.dialog('open');
    });
}

