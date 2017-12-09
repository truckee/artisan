/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
var $collectionHolder;

// setup an "add an artist" link
var addartistLink = $('<a href="#" class="add_artist_link">Add an artist</a>');
var $newLinkLi = $('<li></li>').append(addartistLink);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of artists
    $collectionHolder = $('ul.artists');

    // add the "add a artist" anchor and li to the artists ul
    $collectionHolder.append($newLinkLi);
//    $collectionHolder.find('li').each(function() {
//        addartistFormDeleteLink($(this));
//    });
    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    addartistLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new artist form (see next code block)
        addartistForm($collectionHolder, $newLinkLi);
    });
});

function addartistForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your artists field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a artist" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    // add a delete link to the new form
    addartistFormDeleteLink($newFormLi);
}

function addartistFormDeleteLink($artistFormLi) {
    var $removeFormA = $('<a href="#">Delete this artist</a>');
    $artistFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the artist form
        $artistFormLi.remove();
    });
}