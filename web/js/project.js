/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
$(document).ready(function () {
    $ticketAdd = $("#ticketAddDialog");
    $flashError = $("div.alert");
    if (false === $flashError.text().includes("ERROR")) {
        $flashError.remove();
    }

    $ticketAdd.dialog({
        autoOpen: false,
        resizable: true,
        modal: true,
        width: '40%'
    });

    $('#menuToggle').click(function () {
        window.print();
    });

    $('#addTicket').on('click', function () {
        nowAt = $(location).attr('pathname');
        n = (nowAt.match(/\//g) || []).length;
        urlArray = nowAt.split("/");
        receiptNo = parseInt(urlArray[n], 10);
        receiptAt = nowAt.indexOf('/receipt');
        ticketAddUrl = nowAt.slice(0, receiptAt) + '/ticket/add/' + receiptNo;
        $.get(ticketAddUrl, function (data) {
            $("#ticketAddDialog").dialog({
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
                                    $("#ticketAddDialog").html(response);
                                    return;
                                }
                                //return ticket
                                ticket = $.parseJSON(response);
                                //alert($.trim(str.replace(/[\t\n]+/g,' ')));
                                $('#tickets').append($.trim(ticket.replace(/[\t\n]+/g,' ')));
                                $("#ticketAddDialog").html('Ticket added!');
                                $("#submit").hide();
                            });

                        },
                    },
                    {
                        text: 'Close',
                        id: "close",
                        class: "btn-xs btn-primary",
                        click: function () {
                            $(this).dialog("close");
                        }
                    }
                ],
            });
            $("#ticketAddDialog").html(data);
            $("#ticketAddDialog").dialog('open');
        });

    });

    //use ticket number to identify artist, or not found
    $(document).on("blur", 'input[name$="[ticket]"]' + '', function () {
        $ticket = $('#ticket_ticket').val();
        nowAt = $(location).attr('pathname');
        receiptAt = nowAt.indexOf('/receipt');
        findTicketUrl = nowAt.slice(0, receiptAt) + '/receipt/findTicket/' + $ticket;
        $.get(findTicketUrl, function (data) {
            $('#ticket_artist').val(data);
        });
    });

});
