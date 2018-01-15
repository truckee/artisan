/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
$(document).ready(function () {

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

    $("#ticketDialog").dialog({
        autoOpen: false,
        resizable: true,
        modal: true,
        width: '40%'
    });

    $("#nontaxDialog").dialog({
        autoOpen: false,
        resizable: true,
        modal: true,
        width: '40%',
        close: function () {
            nonTax = $('#nontax_amount');
            if (0 < parseInt(nonTax.text())) {
                $("#nontaxItemAdd").hide();
            }
            if (0 === parseInt(nonTax.text())) {
                $("#nonTaxEditRow").hide();
                $("#nontaxItemAdd").show();
            }
        }
    }
    );

    if ($('#nonTaxEditRow').length > 0) {
        $('#nontaxItemAdd').hide();
    } else {
        $('#nontaxItemAdd').show();
    }

    $('#addTicket').on('click', function () {
        receiptAt = receiptUrl($(this));
        ticketAddUrl = nowAt.slice(0, receiptAt) + '/ticket/add/' + receiptNo;
        $.get(ticketAddUrl, function (data) {
            $("#ticketDialog").dialog({
                title: 'Add ticket',
                buttons: [
                    {
                        text: "Submit",
                        id: "submit",
                        class: "btn-xs btn-primary",
                        click: function () {
                            var formData = $("form").serialize();
                            $.post(ticketAddUrl, formData, function (response) {
                                //if validation error:
                                if (response.indexOf('<form') === 0) {
                                    $("#ticketDialog").html(response);
                                    return;
                                }
                                //return ticket
                                ticket = $.parseJSON(response);
                                $('#tickets').append($.trim(ticket.replace(/[\t\n]+/g, ' ')));
                                $("#ticketDialog").html('Ticket added!');
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
            $("#ticketDialog").html(data);
            $("#ticketDialog").dialog('open');
        });
    });

    $('#nontaxItemAdd').on('click', function () {
        receiptAt = receiptUrl($(this));
        nontaxAddUrl = nowAt.slice(0, receiptAt) + '/nontax/addAmount/' + receiptNo;
        nonTax = $('#nontaxable_nontaxable');
        $.get(nontaxAddUrl, function (data) {
            $("#nontaxDialog").dialog({
                title: 'Add nontaxable item(s)',
                buttons: [
                    {
                        text: "Submit",
                        id: "submit",
                        class: "btn-xs btn-primary",
                        click: function () {
                            var formData = $("form").serialize();
                            $.post(nontaxAddUrl, formData, function (response) {
                                //if validation error:
                                if (response.indexOf('<form') === 0) {
                                    $("#nontaxDialog").html(response);
                                    return;
                                }
                                taxfree = $.parseJSON(response);
                                $('#tickets').append($.trim(taxfree.replace(/[\t\n]+/g, ' ')));
                                $("#nontaxDialog").html('Nontaxable added!');
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
            $("#nontaxDialog").html(data);
            $("#nontaxDialog").dialog('open');
        });
    });

    $(document).on('click', '#nontaxItemEdit',  function () {
        nontaxId = $(this).data('nontax');
        receiptAt = receiptUrl($(this));
        amount = $(this).parent().parent().find($('#amount'));
        nontaxEditUrl = nowAt.slice(0, receiptAt) + '/nontax/editAmount/' + nontaxId;
        $.get(nontaxEditUrl, function (data) {
            $("#nontaxDialog").dialog({
                title: 'Edit nontaxable item(s)',
                buttons: [
                    {
                        text: "Submit",
                        id: "submit",
                        class: "btn-xs btn-primary",
                        click: function () {
                            var formData = $("form").serialize();
                            $.post(nontaxEditUrl, formData, function (response) {
                                //if validation error:
                                if (response.indexOf('<form') === 0) {
                                    $("#nontaxDialog").html(response);
                                    return;
                                }
                                $('#nontax_amount').text(response);
                                $("#nontaxDialog").html('Nontaxable updated!');
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
            $("#nontaxDialog").html(data);
            $("#nontaxDialog").dialog('open');
        });
    });

    $(document).on('click', '[id^=editTicket]',  function () {
        ticketId = $(this).data('ticket');
        amount = $(this).parent().parent().find($('#amount' + ticketId));
        nowAt = $(location).attr('pathname');
        receiptAt = nowAt.indexOf('/receipt');
        ticketEditUrl = nowAt.slice(0, receiptAt) + '/ticket/edit/' + ticketId;
        $.get(ticketEditUrl, function (data) {
            $("#ticketDialog").dialog({
                title: 'Edit ticket',
                buttons: [
                    {
                        text: "Submit",
                        id: "submit",
                        class: "btn-xs btn-primary",
                        click: function () {
                            var formData = $("form").serialize();
                            $.post(ticketEditUrl, formData, function (response) {
                                //if validation error:
                                if (response.indexOf('<form') === 0) {
                                    $("#ticketDialog").html(response);
                                    return;
                                }
                                //return ticket
                                amount.text(response);
                                $("#ticketDialog").html('Ticket updated!');
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
            $("#ticketDialog").html(data);
            $("#ticketDialog").dialog('open');
        });
    });

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

    function receiptUrl(clickedId) {
        receiptNo = $(clickedId).data('receipt');
        currLoc = $(location).attr('pathname');
        //remove add paramter if exists
        nowAt = ('/1' === currLoc.substring(currLoc.length - 2, currLoc.length)) ? currLoc.substring(0, currLoc.length - 2) : currLoc;

        return nowAt.indexOf('/receipt');
    }

});
