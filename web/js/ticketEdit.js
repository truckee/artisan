/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
$(document).ready(function () {
    $("#ticket_ticket").attr('disabled', true);

    $("#ticket_save").on('click', function(e) {
        $("#ticket_ticket").removeAttr("disabled")
    });
});

