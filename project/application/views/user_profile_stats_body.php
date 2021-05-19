<div id="user_profile_heading" class="container-sm shadow bg-white rounded" >
    <div class="row">
        <!-- for display pic -->
        <div class="col-sm-4 d-flex align-items-center justify-content-center">

            <a id="display_pic" class="d-flex align-items-center justify-content-center" href="<?php base_url(); ?>UserProfile">
                <img id="user_display_pic" class="img-fluid rounded-circle" src="<?php
                    if (!$image_dp) {
                        echo base_url()."assets/img/placeholder.PNG";
                    } else {
                        echo $image_dp;
                    }
                ?>" alt="Placeholder picture for users to upload their own">
                <div class="overlay">
                    <h6 class="text">Upload a Picture</h6>
                </div>
            </a>
        </div>

        <!-- for username and email -->
        <div class="col-sm-4 d-flex flex-column justify-content-center">
        <!-- align-items-center for mobile later on -->
            <h3 id="username"><?php echo $username; ?></h3>
            <h6 id="email"><?php echo $email;?></h6>
        </div>

        <!-- number of reviews made -->
        <!-- require further development -->
        <div class="col-sm-2 d-flex flex-column align-items-center justify-content-center">
            <h3 id="num_of_reviews"><?php echo $reviews_num; ?></h3>
            <h6>Reviews Made</h6>
        </div>

        <!-- reputation of user -->
        <!-- require further development -->
        <div class="col-sm-2 d-flex flex-column align-items-center justify-content-center">
            <h3 id="reputation"><?php echo $user_reputation; ?></h3>
            <h6>Reputation</h6>
        </div>
    </div>

    <div class="row bg-dark">
        <!-- for users to toggle between statistics and profile of users -->
        <nav id="secondary-nav" class="navbar navbar-expand-md navbar-dark d-flex justify-content-between">

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a id="details" class="nav-link" href="<?php base_url(); ?>UserProfile">Profile</a>
                </li>
                <li class="nav-item active">
                    <a id="statistics" class="nav-link" href="#">Statistics</a>
                </li>
            </ul>

            <!-- user can update / edit profile as needed -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?php base_url(); ?>UserProfile">Edit Profile</a>
                </li>
            </ul>
        </nav>

    </div>
</div>

<div id="user_details" class="container-sm" >
    <div class="row d-flex">
        <!-- stats information -->
        <div id="details_section" class="col-sm-12 shadow bg-white rounded">
            <div class="row">
                <h3 class="col-sm-12">Statistics</h3>

                <div class="col-sm-12 pt-5">
                    <div class="row no-gutters justify-content-center">
                        <div class="col-sm-9" id="review_heatmap_chart"></div>
                    </div>
                </div>

                <div class="col-sm-12 pb-5">
                    <div class="row no-gutters justify-content-center">
                        <div class="col-sm-10" id="rating_area_chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    // code to call google api chart based on https://developers.google.com/chart/interactive/docs/quick_start
    google.charts.load('current', {'packages':['calendar', 'corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var heatmap_data = [];
        var area_data = [];
        area_data.push(['Date', 'Upvote', 'Downvote'])
        // will need to extract data for input to create graphs
        var username = "<?php echo $this->session->userdata('username'); ?>";

        // request server to provide information for heatmap chart
        $.ajax({
            method: "POST",
            url: "<?php echo base_url() ?>UserProfileStats/gather_user_specific_chart",
            data: {
                username : username,
                heatmap : true
            },
            success: function(data) {
                console.log(data);
                var parsed_data = JSON.parse(data);
                var heatmap_prepare = {};

                // acquired relevant data, will need to process data and restructure to use as data inputs for heatmap
                for (var key in parsed_data) {
                    var myDate = new Date(parsed_data[key][0].DateReviewed);
                    var mm = myDate.getMonth() + 1;
                    var dd = myDate.getDate();
                    var yyyy = myDate.getFullYear();
                    var formated_date = yyyy + "/" + mm + "/" + dd;

                    if (!(formated_date in heatmap_prepare)) {
                        heatmap_prepare[formated_date] = 1;
                    } else {
                        heatmap_prepare[formated_date] += 1;
                    }
                }

                for (var time in heatmap_prepare) {
                    let temp = [];
                    reviews_number = heatmap_prepare[time];
                    temp.push(new Date(time));
                    temp.push(reviews_number);
                    heatmap_data.push(temp);
                }

                // use processed data to display them as calender graph
                create_heatmap_graph(heatmap_data)
            }
        })

        // request server to provide information for area chart
         $.ajax({
             method: "POST",
             url: "<?php echo base_url(); ?>UserProfileStats/gather_user_specific_chart",
             data: {
                username : username,
                area : true
            },
            success: function(data) {

                // successfully gotten data, will now process data to be used as inputs for visualization

                var parsed_data = JSON.parse(data);

                for (var time in parsed_data) {
                    let temp = [];
                    let good_rating = parsed_data[time]['good_ratings'];
                    let bad_rating = parsed_data[time]['bad_ratings'] 
                    temp.push(time, good_rating, bad_rating);
                    area_data.push(temp);
                }

                // use processed data to display them as area chart
                create_area_graph(area_data)
            }
         });

        // display heatmap chart that informs users when his/her reviews were made
        function create_heatmap_graph(display_data) {
            var dataTable = new google.visualization.DataTable();
            dataTable.addColumn({ type: 'date', id: 'Date' });
            dataTable.addColumn({ type: 'number', id: 'Reviews' });
            dataTable.addRows(display_data);

            var chart = new google.visualization.Calendar(document.getElementById('review_heatmap_chart'));

            var options = {
                colorAxis: {minValue: 0},
                title: "Daily Reviews Posted",
                height: 250,
                width: 1040,
                calendar: {
                    cellSize: 18,
                    monthOutlineColor: {
                        stroke: '#343a40',
                        strokeOpacity: 0.8,
                        strokeWidth: 2,
                    },
                    unusedMonthOutlineColor: {
                        strokeOpacity: 0.8,
                        strokeWidth: 1
                    },
                    yearLabel: {
                        fontName: 'Arial',
                        fontSize: 30,
                        color: 'black',
                        bold: false,
                        italic: false
                    },
                    monthLabel: {
                        fontName: 'Arial',
                        color: 'black',
                        bold: false,
                        italic: false
                    },
                    dayOfWeekLabel: {
                        fontName: 'Arial',
                        color: 'black',
                        bold: true,
                        italic: true,
                    },
                    dayOfWeekRightSpace: 10,
                },
            };

            chart.draw(dataTable, options);
        }

        function create_area_graph(display_data) {
        // this is for area graph that displays total ratings across all reviews
            var data = google.visualization.arrayToDataTable(display_data);

            var options = {
                legend: {position: 'bottom'},
                title: 'Ratings Overview',
                titleTextStyle: {
                    color: 'black',
                    fontName: 'Arial',
                    fontSize: 40,
                    bold: false,
                    italic: false },
                hAxis: {
                    title: 'Date',  
                    titleTextStyle: 
                    {
                        color: 'black',
                        bold: false,
                        italic: false,
                    }
                },
                vAxis: {
                    title: 'Total Ratings Received',
                    minValue: 0,
                    titleTextStyle: 
                    {
                        color: 'black',
                        bold: false,
                        italic: false,
                    }
                    },
                    
                height: 400,
                width: 1200
            };

            var chart = new google.visualization.AreaChart(document.getElementById('rating_area_chart'));
            chart.draw(data, options);
        }
   }
});
</script>