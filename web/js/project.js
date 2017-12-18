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
    if (false === $flashError.text().includes("ERROR")) {
        $flashError.remove();
    }
})
