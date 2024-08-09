$(document).ready(function() {
    var inputBuffer = ''; // Buffer to store the accumulated input characters
    var bufferTimeout;

    $(document).on('keyup', '.nice-select', function(event) {
        var $dropdown = $(this);
        var $list = $dropdown.find('ul.list');
        var $items = $list.find('li');
        var key = String.fromCharCode(event.which).toUpperCase();

        // If the key pressed is a letter
        if (key.match(/[A-Z]/)) {
            inputBuffer += key; // Accumulate the key in the buffer
            
            // Clear the previous timeout if it exists
            clearTimeout(bufferTimeout);

            // Set a timeout to clear the buffer after a period of inactivity
            bufferTimeout = setTimeout(function() {
                inputBuffer = '';
            }, 1000); // Adjust timeout duration as needed

            // Iterate through list items to find a match
            $items.each(function() {
                var $item = $(this);
                var liText = $item.text().replace(/\s+/g, '').toUpperCase();
                if (liText.indexOf(inputBuffer) === 0) {
                    $dropdown.find('.focus').removeClass('focus');
                    $item.addClass('focus');

                    // Scroll to make the focused item visible
                    var itemOffsetTop = $item.position().top;
                    var itemHeight = $item.outerHeight();
                    var listHeight = $list.height();
                    var scrollTop = $list.scrollTop();

                    if (itemOffsetTop < scrollTop) {
                        // Item is above the visible area, scroll up
                        $list.scrollTop(itemOffsetTop);
                    } else if (itemOffsetTop + itemHeight > scrollTop + listHeight) {
                        // Item is below the visible area, scroll down
                        $list.scrollTop(itemOffsetTop + itemHeight - listHeight);
                    }
                    
                    return false; // Exit the each loop
                }
            });
        } else {
            // Reset the buffer if the key is not a letter
            inputBuffer = '';
        }
    });
});