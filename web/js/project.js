/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
$(document).ready(function () {

    $("#select_show_save").click(function () {
        $id = $("#select_show_show option:selected").val();
        nowAt = $(location).attr('pathname');
        showPathAt = nowAt.indexOf('/show/select');
        url = nowAt.slice(0, showPathAt) + '/show/edit/' + $id;
        window.location.href = url
        return false;
    });

    $("#select_artist_save").click(function () {
        $id = $("#select_artist_artist option:selected").val();
        nowAt = $(location).attr('pathname');
        artistPathAt = nowAt.indexOf('/artist/select');
        labelClass = $(".control-label").text();
        if (labelClass.includes('edit')) {
            newPath = '/artist/edit/' + $id;
        }
        if (labelClass.includes('block')) {
            newPath = '/block/new/' + $id;
        }
        if (labelClass.includes('block edit')) {
            newPath = '/block/edit/' + $id;
        }
        if (labelClass.includes('tickets')) {
            newPath = '/artist/tickets/' + $id;
        }
        url = nowAt.slice(0, artistPathAt) + newPath;
        window.location.href = url
        return false;
    });

    $("#select_block_save").click(function () {
        $id = $("#select_block_block option:selected").val();
        nowAt = $(location).attr('pathname');
        url = nowAt.replace('select', 'edit');
        window.location.href = url
        return false;
    });

    $("#select_receipt_save").click(function () {
        $id = $("#select_receipt_receipt option:selected").val();
        nowAt = $(location).attr('pathname');
        url = nowAt.replace('select', 'edit/' + $id);
        window.location.href = url
        return false;
    });

    $flashError = $("div.alert");
    if (false === $flashError.text().includes("ERROR")) {
        $flashError.remove();
    }
})
