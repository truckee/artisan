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
        url = nowAt.slice(0, artistPathAt) + '/artist/edit/' + $id;
        window.location.href = url
        return false;
    });

    $flashError = $("div.alert");
    if (false === $flashError.text().includes("ERROR")) {
        $flashError.remove();
    }
})
