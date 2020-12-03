function moveSelection(down) {
    var selectedNode = document.querySelector('#fuzzy_tables_search_result span.selected');
    var newSelectedNode = null;
    if (down) {
        newSelectedNode = selectedNode.nextElementSibling;
        if (newSelectedNode === null) {
            // last one => first one
            newSelectedNode = document.querySelectorAll('#fuzzy_tables_search_result span')[0];
        };
    } else {
        // up
        newSelectedNode = selectedNode.previousElementSibling;
        if (newSelectedNode === null) {
            // first one => last one
            nodes = document.querySelectorAll('#fuzzy_tables_search_result span');
            newSelectedNode = nodes[nodes.length - 1];
        };
    };
    if (newSelectedNode != null) {
        selectedNode.classList.remove('selected');
        newSelectedNode.classList.add('selected');
    }
}

function closeResults() {
    document.querySelector('#menu #fuzzy_tables_search_result').style.display = 'none';
}

function tablesFilter(query) {
    if (query === '') {
        closeResults();
        return;
    }
    // Get spans containing tables names and links
    var tables = document.querySelectorAll('#tables li > a.select');
    var tablesData = new Array(tables.length);
    // tablesData is an array of objects with properties:
    //   - name: name of the table
    //   - score: fuzzysearch score
    //   - nodes: array containing the two links for opening table
    for (var i = 0; i < tables.length; i++) {
        var tableName = tables[i].nextSibling.nextSibling.text;
        var nodes = [tables[i], tables[i].nextSibling.nextSibling];
        var fuzzyData = fuzzy(tableName, query);
        tablesData[i] = {
            'name': tableName,
            'nodes': nodes,
            'fuzzyData': fuzzyData  // Object containing 'score', 'highlightedTerm', 'query' and 'term'
        };
    }
    // Sort by score and length
    tablesData.sort(function(m1, m2) {
        return (m2.fuzzyData.score - m1.fuzzyData.score != 0) ? m2.fuzzyData.score - m1.fuzzyData.score : m1.name.length - m2.name.length;
    });
    // console.log(tablesData.map(function(elem) {return String(elem.fuzzyData.score) + ' ' + elem.name}));
    // Add matches to results div
    maxResults = 15;
    resultsDiv = document.querySelector('#menu #fuzzy_tables_search_result');
    resultsDiv.style.display = 'inline';
    resultsDiv.innerHTML = '';
    for (var i = 0; i < maxResults; i++) {
        // debugger;
        spanNode = document.createElement('span');
        // link 1
        spanNode.appendChild(tablesData[i].nodes[0].cloneNode(true));
        spanNode.appendChild(document.createTextNode(' '));
        // link 2
        link_node = tablesData[i].nodes[1].cloneNode(true);
        link_node.innerHTML = tablesData[i].fuzzyData.highlightedTerm;
        spanNode.appendChild(link_node);
        spanNode.appendChild(document.createElement("br"));
        if (i == 0) {
            spanNode.classList.add('selected');
        };
        resultsDiv.appendChild(spanNode);
    }
    // debugger;
};
