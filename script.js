$ = jQuery;
 
var mafs = $("#my-ajax-filter-search"); 
var mafsForm = mafs.find("form"); 
 
mafsForm.submit(function(e){
    e.preventDefault(); 
 
    if(mafsForm.find("#search").val().length !== 0) {
        var search = mafsForm.find("#search").val();
    }
    var data = {
        action : "my_ajax_filter_search",
        search : search
    }

    $.ajax({
        url : ajax_info.ajax_url,
        data : data,
        success : function(response) {
            mafs.find("ul").empty();
            if(response) {
                for(var i = 0 ;  i < response.length ; i++) {
                     var html  = "<li id='movie-" + response[i].id + "'>";
                         html += "  <a href='" + response[i].permalink + "' title='" + response[i].title + "'>";
                         html += "      <img src='" + response[i].poster + "' alt='" + response[i].title + "' />";
                         html += "      <div class='movie-info'>";
                         html += "          <h4>" + response[i].title + "</h4>";
                         html += "      </div>";
                         html += "  </a>";
                         html += "</li>";
                     mafs.find("ul").append(html);
                }
            } else {
                var html  = "<li class='no-result'>No matching movies found. Try a different filter or search keyword</li>";
                mafs.find("ul").append(html);
            }
        } 
    });
 
// we will add codes above this line later
});