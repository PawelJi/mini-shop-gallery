(function(){

    var $collectionHolder;

    var $addPhotoLink = $('<a class="btn btn-primary" href="#" class="add_photo_link">Add a photo</a>');
    var $newLinkLi = $('<div></div>').append($addPhotoLink);

    jQuery(document).ready(function() {
        // Get the ul that holds the collection of tags
        $collectionHolder = $('#collectionproduct_photos_form_group');

        // add the "add a tag" anchor and li to the tags ul
        $collectionHolder.append($newLinkLi);

        $collectionHolder.find('.collection-item').each(function() {
            addPhotoFormDeleteLink($(this));
        });

        // count the current form inputs we have (e.g. 2), use that as the new
        // index when inserting a new item (e.g. 2)
        $collectionHolder.data('index', $collectionHolder.find(':input').length);

        $addPhotoLink.on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            // add a new tag form (see next code block)
            addPhotoForm($collectionHolder, $newLinkLi);
        });
    });

    function addPhotoForm($collectionHolder, $newLinkLi) {
        // Get the data-prototype explained earlier
        var prototype = $collectionHolder.data('prototype');

        // get the new index
        var index = $collectionHolder.data('index');

        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        var newForm = prototype.replace(/__name__/g, index);

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);

        // Display the form in the page in an li, before the "Add a tag" link li
        var $newFormLi = $('<li></li>').append(newForm);
        $newLinkLi.before($newFormLi);
    }

    function addPhotoFormDeleteLink($photoFormLi) {
        var $removeFormA = $('<a class="btn btn-primary btn-danger" href="#">delete this product</a>');
        $photoFormLi.append($removeFormA);

        $removeFormA.on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            // remove the li for the tag form
            $photoFormLi.remove();
        });
    }

})();

