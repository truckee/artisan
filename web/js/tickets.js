/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
var $currInput;
var $collectionHolder;
var addticketLink = $('<div class="col-sm-6"><a href="#" class="add_ticket_link btn-info btn-sm">Add ticket</a></div>');
var $newLinkLi = $('<div class="row" style="margin-bottom: 5px;"></div>').append(addticketLink);

jQuery(document).ready(function () {
    $collectionHolder = $('div.tickets');
    $collectionHolder.append($newLinkLi);
    $collectionHolder.data('index', Math.floor(($collectionHolder.find(':input').length)/3));
    addticketLink.on('click', function (e) {
        e.preventDefault();
        addticketForm($collectionHolder, $newLinkLi);
    });
    //get ticket and artist location in form
    $(document).on("focus", 'input[name$="[ticket]"]' + '', function () {
        $rowId = $collectionHolder.data('index') - 1;
        $inputId = "receipt_tickets_" + $rowId + "_ticket";
        $artistId = "receipt_tickets_" + $rowId + "_artist";
        $currInput = $('input[id=' + $inputId + ']');
    });
    //use ticket number to identify artist, or not found
    $(document).on("blur", 'input[name$="[ticket]"]' + '', function () {
        $ticket = $currInput.val();
        nowAt = $(location).attr('pathname');
        receiptAt = nowAt.indexOf('/receipt');
        url = nowAt.slice(0, receiptAt) + '/receipt/findTicket/' + $ticket;
        $.get(url, function (data) {
            $('#' + $artistId).val(data);
        });
    });

    $('input[id$="ticket"]').each(function (key, value) {
        $ticket = $(value).val()
        $rowId = $(this).attr('id').replace('receipt_tickets_', '').replace('_ticket', '');
        $artistId = "receipt_tickets_" + $rowId + "_artist";
        nowAt = $(location).attr('pathname');
        receiptAt = nowAt.indexOf('/receipt/new');
        url = nowAt.slice(0, receiptAt) + '/receipt/findTicket/' + $ticket;
        $.get(url, function (data) {
            $('#' + $artistId).val(data);
        });
    });

    $('a.btn-warning').on('click', function() {
        goahead = confirm('Click OK to confirm deletion');
        if (true === goahead) {
            $(this).parent().parent().remove();
        }
        return false;
    });
});

function addticketForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);
    $collectionHolder.data('index', index + 1);
    var $newFormLi = $('<div class="row"></div>').append(newForm);
    $newLinkLi.before($newFormLi);
    addticketFormDeleteLink($newFormLi);
}

function addticketFormDeleteLink($ticketFormLi) {
    var $removeFormA = $('<div class="col-sm-6" style="margin-bottom:6px;"><a class="btn-sm btn-info" href="#">Delete this ticket</a></div>');
    $ticketFormLi.append($removeFormA);
    $removeFormA.on('click', function (e) {
        e.preventDefault();
        $ticketFormLi.remove();
    });
}