var delay = (function(){
    var timer = 0;
    return function(callback, ms) {
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();

document.querySelector('#fuzzy_tables_search_input').addEventListener('keyup', function(e) {
    if (e.keyCode === 13) { // enter
        // Open selected link
        url = document.querySelector('#menu #fuzzy_tables_search_result span.selected > a');
        if (e.shiftKey) {
            window.open(url, '_blank').focus();
        } else {
            window.location = url;
        }
    } else if (e.keyCode === 40) { // down
        moveSelection(true);
    } else if (e.keyCode === 38) { // up
        moveSelection(false);
    } else if (e.keyCode === 27) { // ESC
        closeResults();
    } else {
        delay(function() {
            tablesFilter(document.querySelector('#fuzzy_tables_search_input').value.replace(/ /g,''));
        }, 200);
    }
}, false);