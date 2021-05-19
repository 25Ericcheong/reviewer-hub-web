<!-- this is for when users are trying to search for reviews -->
<?php echo form_open('SearchedReview/search', array(
    'class' => 'container-sm', 
    'id' => 'search_form',
    'autocomplete' => 'off'
)); ?>
    <div class="row">
        <h3 class="col-sm-12 border-0 p-0">Our Database</h3>
        <input class="form-control col-sm-10 shadow bg-white round" type="search" placeholder="Search" aria-label="Search" name="searchTitle" id="search_box">
        <button class="btn btn-primary bg-dark col-sm-2" type="submit">Search</button>
        <div class="col-sm-10 shadow bg-white round" id="suggestion_box"></div>
    </div>
<?php echo form_close(); ?>

<!-- this would be use to display charts -->
<div id="stats_section">
    <h3 class="p-0">Our Numbers</h3>
    <div class="row no-gutters d-flex justify-content-around pb-4" id="charts_section">
        <div class="col-sm-6" id="review_bar_chart"></div>
        <div class="col-sm-5" id="user_line_chart"></div>
    </div>

</div>

<!-- this displays reviews for user to view -->
<div id="reviews_body">
    <h3>Our Reviews</h3>
    <div id="reviews"></div>
</div>


<!-- loading indicator will be shown when more reviews are being displayed -->
<div class="d-flex flex-column justify-content-center" id="display_reviews_messages">
    <div class="row">
        <div class="mb-5 col-sm-12">
            <div id="display_reviews_loading"></div>
        </div>
        <div class="mb-5 col-sm-12 text-center" id="display_reviews_messsage"></div>
    </div>
</div>

<script>
$(document).ready(function() {
    $("#search_box").keyup(function(){
        $.ajax({
        method: "POST",
        url: "<?php echo base_url() ?>Home/suggest_reviews",
        data:{data : $(this).val()},
        success: function(response){
            $("#suggestion_box").show();
            $("#suggestion_box").html(response);
            $("#search_box").css("background","white");
        }
        });
    })

    // when a suggest review title is clicked, hide suggestion box shown
    $(document).on("click", "li", function() {
        $("#search_box").val($(this).text());
        $("#suggestion_box").hide();
    });

    // this is for when user wants to scroll past suggested reviews instead.
    $(function() {
        $(document).on('click', function(e) {
            if (e.target.id === 'suggestion_box') {
                return true;
            } else {
                $('#suggestion_box').hide();
            }

        })
    });

    // this is for continuous data loading
    var limit = 3;
    var start = 0;
    var action = 'inactive';
    var username = "<?php echo $this->session->userdata('username'); ?>";

    function load_reviews(limit, start) {
        $.ajax({
            url: "<?php echo base_url(); ?>Home/fetch_reviews",
            method: "POST",
            data: {limit:limit, start:start, username:username},
            cache: false,
            success: function(data) {
                if (!data) {
                    $("#display_reviews_messsage").html("All reviews have been displayed");
                    $("#display_reviews_loading").hide();
                    action = 'active';

                } else {
                    $("#reviews").append(data);
                    $("#display_reviews_loading").hide();
                    action = 'inactive';
                }
            }
        });
    }

    // load some reviews when page loads for the first time
    if (action == 'inactive') {
        action = 'active';
        load_reviews(limit, start);
    }

    // if user continues to scroll to the bottom of window, load more reviews
    $(window).scroll(function() {

        if ($(window).scrollTop() + $(window).height() > $("#reviews").height() && action == 'inactive') {
            $("#display_reviews_loading").show();
            action = 'active';
            start = start + limit;
            setTimeout(function() {
                load_reviews(limit, start);
            }, 2000);
        }
    });


    // used for when a user clicks on agree or disagree button and it updates overall rating on the page as well
    $(document).on("click", ".rate", function() {
        var rating_clicked = $(this).data("value");
        var review_title = $(this).closest('.review').find('.review_title').data("value");
        var overall_rating = $(this).closest('.review').find('.overall_rating').data("value");

        // ajax will send request to server to update db
        $.ajax({
            url: "<?php echo base_url(); ?>Home/review_rated_wishlisted",
            method: "POST",
            data: { rating_clicked:rating_clicked, review_title:review_title },
        });

        if ($(this).hasClass('agree')) {

            if ($(this).hasClass('btn-dark')) {

                // adds overall rating
                if ($(this).closest('.review').find('.overall_rating').hasClass('rated_before')) {
                    overall_rating+=2;
                } else {
                    $(this).closest('.review').find('.overall_rating').addClass('rated_before');
                    overall_rating++;
                }
                
                $(this).closest('.review').find('.disagree').removeClass('btn-danger');
                $(this).closest('.review').find('.disagree').addClass('btn-dark'); 

                $(this).removeClass('btn-dark');
                $(this).addClass("btn-success");
            }

        } else if ($(this).hasClass('disagree')) {

            if ($(this).hasClass('btn-dark')) {
                // subtracts overall rating
                if ($(this).closest('.review').find('.overall_rating').hasClass('rated_before')) {
                    overall_rating-=2;
                } else {
                    $(this).closest('.review').find('.overall_rating').addClass('rated_before');
                    overall_rating--;
                }

                $(this).closest('.review').find('.agree').removeClass('btn-success');
                $(this).closest('.review').find('.agree').addClass('btn-dark'); 

                $(this).removeClass('btn-dark');
                $(this).addClass("btn-danger");
            }
        }

        // ensure rating is updated on view and data values are updated as well
        $(this).closest('.review').find('.overall_rating').text('Overall rating : ' + overall_rating);
        $(this).closest('.review').find('.overall_rating').data('value', overall_rating)

    });

    // used for when a user clicks on agree or disagree button and it updates overall rating on the page as well
    $(document).on("click", ".wishlist", function() {
        var review_title = $(this).closest('.review').find('.review_title').data("value");
        var review_wishlisted = $(this).data("value");

        // ajax will send request to server to update db
        $.ajax({
            url: "<?php echo base_url(); ?>Home/review_rated_wishlisted",
            method: "POST",
            data: { review_wishlisted:review_wishlisted, review_title:review_title },
        });

        if ($(this).hasClass('favorited')) {

            $(this).removeClass('favorited');
            $(this).removeClass('btn-success');
            $(this).addClass("btn-dark");
            $(this).addClass("not_favorited");
            $(this).text("Add to Wishlist");

        } else if ($(this).hasClass('not_favorited')) {

                $(this).removeClass('not_favorited');
                $(this).removeClass('btn-dark');
                $(this).addClass("btn-success");
                $(this).addClass("favorited");
                $(this).text("Wishlisted");
        }
    });

    // code to call google api chart based on https://developers.google.com/chart/interactive/docs/quick_start
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    // Callback that creates and populates a data table,
    // instantiates the chart, passes in the data and
    // draws it.
    function drawChart() {
        // before loading json file, check if file exists
        function doesFileExist(urlToFile) {
            var xhr = new XMLHttpRequest();
            xhr.open('HEAD', urlToFile, false);
            xhr.send();
            
            if (xhr.status == "404") {
                return false;
            } else {
                return true;
            }
        }

        // whenever function is called, will check if there is a json file for data collection and if there is no file, will create a new file
        // if there is a file, then processing json will begin to be used as inputs for google charts
        if (doesFileExist("<?php echo base_url(); ?>uploads/data_charts/data.json")) {

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "<?php echo base_url(); ?>uploads/data_charts/data.json");
            xhr.addEventListener('load', processJSON);
            xhr.send();

            function processJSON(event) {
                var json = this.responseText;
                var data = JSON.parse(json);
                var flag = false;
                var reviews_data = [];
                var users_joined_data = [];

                var raw_review_data = data['reviews_made_chart'];
                var raw_rated_data = data['reviews_rated_chart'];
                var raw_users_data = data['users_chart'];

                for([date_point, users_point] of Object.entries(data['users_chart'])) {
                    var review_temp = new Array();
                    var users_temp = new Array();
                    var num_reviews = parseInt(raw_review_data[date_point][0]['review_count']);
                    var num_rated_reviews = parseInt(raw_rated_data[date_point][0]['review_rated_count']);
                    var num_users = parseInt(users_point[0]['user_count']);

                    // for review column graph data points
                    review_temp.push(new Date(date_point));
                    review_temp.push(num_reviews);
                    review_temp.push(num_rated_reviews);

                    // for number of users joining review hub line graph data points
                    users_temp.push(new Date(date_point));
                    users_temp.push(num_users);

                    // these will be used for charts to be displayed on home page for all users to see
                    reviews_data.push(review_temp);
                    users_joined_data.push(users_temp);
                }

                // need to check if data is most current

                var today_date = new Date();
                today_date.setHours(0, 0, 0, 0);
                var latest_date_data = reviews_data[6][0];
                latest_date_data.setHours(0, 0, 0, 0);

                // days are not equal, will need to rewrite json file
                console.log(today_date.toDateString());
                console.log(latest_date_data.toDateString());
                if (today_date.toDateString() !== latest_date_data.toDateString()) {
                    flag = true;
                } 

                // this will cause script to be repeated to update json file
                if (flag) {
                    $.ajax({
                        method: "POST",
                        url: "<?php echo base_url() ?>Home/gather_data_for_chart",
                        data:{days_required : 6},
                        success: function(response) {
                            location.reload(true);
                        }
                    });
                }

                // since data is most current, will display relevant charts
                create_column_graph(reviews_data);
                create_line_graph(users_joined_data);
            }

        } else {
            // since file doesn't exist, will need to write file then and refresh page to load most up to date page
            $.ajax({
                method: "POST",
                url: "<?php echo base_url() ?>UserProfileStats/gather_data_for_chart",
                data:{days_required : 6},
                success: function() {
                    location.reload(true);
                }
            });
        }

        // Create the data table.
        // this creates the line chart
        function create_line_graph(data_display) {
            var data = new google.visualization.DataTable();
            data.addColumn('date', 'Date');
            data.addColumn('number', 'Number of Users');
            data.addRows(data_display);

            var options = {
                title: 'Review Hub Total Users',
                curveType: 'function',
                colors: ['darkslategray'],
                legend: { 
                    position: 'bottom',
                    alignment: 'center',
                },
                width: 600,
                height: 400,
                hAxis: {
                    format: 'd/M/yy',
                    gridlines: {color: 'none'},
                    title: 'Date',
                    textStyle: {
                        color: 'black',
                        italic: false
                    },
                    titleTextStyle: {
                        color: '#black',
                        italic: false
                    },
                },
                vAxis: {
                    minValue: 0,
                    title: 'Number of Users',
                    textStyle: {
                        color: 'black',
                        italic: false
                    },
                    titleTextStyle: {
                        color: '#black',
                        italic: false
                    },
                },
            };

            var chart = new google.visualization.LineChart(document.getElementById('user_line_chart'));

            chart.draw(data, options);
        }

        // Create the data table.
        // this creates the column chart
        function create_column_graph(display_data) {
            var data = new google.visualization.DataTable();
            data.addColumn('date', 'Date');
            data.addColumn('number', 'Reviews Made');
            data.addColumn('number', 'Reviews Rated');

            data.addRows(display_data);

            var options = {
                legend: { 
                    position: 'bottom',
                    alignment: 'center',
                    },
                title: 'Review Hub Reviews Posted and Rated',
                focusTarget: 'category',
                colors: ['darkslategray', 'gray'],
                width: 650,
                height: 400,
                hAxis: {
                    title: 'Date',
                    format: 'd/M/yy',
                    textStyle: {
                        color: 'black',
                        italic: false
                    },
                    titleTextStyle: {
                        color: '#black',
                        italic: false
                    },
                    gridlines: {
                        color : 'none'
                    },
                },
                vAxis: {
                title: 'Number of Reviews',
                textStyle: {
                    color: '#black',
                    italic: false
                    },
                titleTextStyle: {
                    color: '#black',
                    italic: false
                    }
                }
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('review_bar_chart'));
            chart.draw(data, options);
        }

    }
});
</script>