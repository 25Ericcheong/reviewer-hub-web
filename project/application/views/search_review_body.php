<!-- this is for when users are trying to search for reviews -->
<?php echo form_open('SearchedReview/search', array(
    'class' => 'container-sm shadow bg-white rounded', 
    'id' => 'search_form',
    'autocomplete' => 'off'
)); ?>
    <div class="row">
        <input class="form-control col-sm-10" type="search" placeholder="Search" aria-label="Search" name="searchTitle" id="search_box" value="<?php 
            if ($searched) {
                echo $searched;
            }
         ?>">
        <button class="btn btn-primary bg-dark col-sm-2" type="submit">Search</button>
        <div class="col-sm-10" id="suggestion_box"></div>
    </div>
<?php echo form_close(); ?>

<div id="search_results" class="container-sm shadow bg-white rounded">
    <div class="row review_searched">
        <h3 class="col-sm-12">Search Results</h3>
        <h5 class="col-sm-12">You have searched for: <?php if ($searched) { echo $searched; } ?></h5>
        <?php if (!$exists) {
            echo '<p class="col-sm-12" style="height: 25vh">Review searched is not found or there is no review being displayed currently.</p>';
        } ?>

        <?php if ($exists) {
            // for title of review and creator of review
            echo '
                <div class="col-sm-12 d-flex justify-content-between">
                    <p class="m-0 review_title" id="title" data-value="'.$ReviewTitle.'">'.$ReviewTitle.'</p><p class="text-sm-right m-0">By: '.$created_by.'</p>
                </div>';

            if ($created_by != 'Anonymous') {
                if ($verified) {
                    echo '<div class="col-sm-12 d-flex justify-content-end"><p class=" text-success">
                    Verified <i class="fa fa-check-circle ml-1" style="font-size:24px; color:green"></i>
                    </p></div>';
                } else {
                    echo '<div class="col-sm-12 d-flex justify-content-end"><p class=" text-danger">
                    Not Verified <i class="fa fa-times-circle ml-1" style="font-size:24px; color:red"></i>
                    </p></div>';
                }
            }

            // description of review
            echo '<p class="col-sm-12">'.$Description.'</p>';

            // include ratings given by reviewer as well, will split into 3 columns
            if (!$UsabilityComment) {
                $UsabilityComment = 'No comment';
            }

            if (!$PricingComment) {
                $PricingComment = 'No comment';
            }

            if (!$QualityComment) {
                $QualityComment = 'No comment';
            }

            $usability_percent = $UsabilityRating/5 * 100;
            $quality_percent = $QualityRating/5 * 100;
            $pricing_percent = $PricingRating/5 * 100;

            echo '
                <div class="col-sm-12 mb-3">
                    <div class="row no-gutterrs">
                        <div class="col-sm-4 no-gutters">
                            <p class="col-sm-12 text-center mb-1">Usability: '.$UsabilityRating.'/5</p>
                            <div class="progress col-sm-12">
                                <div class="progress-bar bg-dark" role="progressbar" style="width: '.$usability_percent.'%" aria-valuenow="'.$usability_percent.'" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="col-sm-12 m-0 text-break">'.$UsabilityComment.'</p>
                        </div>
                        <div class="col-sm-4 no-gutters">
                            <p class="col-sm-12 text-center mb-1">Quality: '.$QualityRating.'/5</p>
                            <div class="progress col-sm-12">
                                <div class="progress-bar bg-dark" role="progressbar" style="width: '.$quality_percent.'%" aria-valuenow="'.$quality_percent.'" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="col-sm-12 m-0 text-break">'.$QualityComment.'</p>
                        </div>
                        <div class="col-sm-4 no-gutters">
                            <p class="col-sm-12 text-center mb-1">Pricing: '.$PricingRating.'/5</p>
                            <div class="progress col-sm-12">
                                <div class="progress-bar bg-dark" role="progressbar" style="width: '.$pricing_percent.'%" aria-valuenow="'.$pricing_percent.'" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="col-sm-12 m-0 text-break">'.$PricingComment.'</p>
                        </div>
                    </div>
                </div>';

            // // images of review
            $img_num = count($images);
            $img_size = 12 / count($images);
            $img_view = "<img class='col-sm-%d' style='max-height: 400px; max-width: 400px' src='%s'>";

            if ($img_num > 1) {
                echo '<div class="row no-gutters d-flex w-100 justify-content-around" style="padding-left: 15px; padding-right: 15px">';
            } else {
                echo '<div class="row no-gutters d-flex w-100 justify-content-center" style="padding-left: 15px; padding-right: 15px">';
            }

            for ($i = 0; $i < $img_num; $i++) {
                echo sprintf($img_view, $img_size, $images[$i]['Path']);
            }

            echo '</div>';

            // recommendation
            echo '<blockquote class="col-sm-12 blockquote text-center">
            <p class="mb-0">Recommendation : '.ucwords($Recommendation).'</p>
            <footer class="blockquote-footer">'.$RecommendationComment.'</footer>
            </blockquote>';

            // this will only be displayed if user has created an account
            if ($this->session->userdata('logged_in')) {
                echo '<div class="col-sm-12 p-3 rating">
                        <div class="row no-gutters">';

                // this is how wishlist button will be displayed
				if ($favorited == 'yes') {
					echo '
                        <div class="col-sm-4">
                            <button class="btn btn-success wishlist favorited" data-value="no">Wishlisted</button>
                        </div>';
				} else if (!$favorited || $favorited == 'no') {
					echo '
                        <div class="col-sm-4">
                            <button class="btn btn-dark wishlist not_favorited" data-value="yes">Add to Wishlist</button>
                        </div>';
                }
                
                // overall rating for specific review
				echo '
                    <div class="col-sm-4 d-flex align-items-center justify-content-center">
                        <p class="m-0 text-center overall_rating" data-value='.$overall_rating.'>Overall rating : '.$overall_rating.'</p>
                    </div>';

                echo '
                    <div class="col-sm-4">
                        <div class="row no-gutters">';

                // display which rated button was clicked by user
				if ($rating) {
					if ($rating == 'agree') {
						echo '
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button class="btn btn-success rate agree mr-1" data-value="agree">Agree</button>
                                <button class="btn btn-dark rate disagree" data-value="disagree">Disagree</button>
                            </div>';
									
					} else if ($rating == 'disagree') {
						echo '
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button class="btn btn-dark rate mr-1 agree" data-value="agree">Agree</button>
                                <button class="btn btn-danger rate disagree" data-value="disagree">Disagree</button>
                            </div>';
					}
				} else {
					echo '
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button class="btn btn-dark rate mr-1 agree" data-value="agree">Agree</button>
                            <button class="btn btn-dark rate disagree" data-value="disagree">Disagree</button>
                        </div>';
				}
                echo '
                                    </div>
                                </div>
                            </div>
                        </div>';
                
            }

            // load all comments from users found in database
            echo '<h3 class="col-sm-12 mt-3">Comments</h3>';
            if (!$comments) {
                echo '<p class="col-sm-12">No comments can be found for this review. Be the first! (create an account first)</p>';
            } else {
                // display all comments found
                $comment_num = count($comments);
                $comment_view = "<p class='col-sm-6'>Posted by %s</p>
                <p class='col-sm-6 text-sm-right'>Posted on %s</p>
                <p class='col-sm-12 text-break'>%s</p>
                ";

                for ($i = 0; $i < $comment_num; $i++) {

                    $comment_creator = $comments[$i]['Username'];
                    $comment_created_date = date('j M Y', strtotime($comments[$i]['DateComment']));
                    $comment_content = $comments[$i]['Comments'];

                    echo sprintf($comment_view, $comment_creator, $comment_created_date, $comment_content);
                }
            }

            // load functionality for user to post a comment. For now, only users with accounts can post a comment
            if($this->session->userdata('logged_in')) {
                echo '<h3 class="col-sm-12">Post a new or edit existing comment</h3>';
                echo '<p class="col-sm-12 mb-1">Your comment</p>';
                
                echo '<div class="col-sm-12 mb-1"><textarea name="text_comment" id="text_comment"style="resize:none; width:100%;">'.$user_comment['Comments'].'</textarea></div>';

                echo '<div class="col-sm-12 d-flex justify-content-end mt-2"><button type="button" class="btn btn-primary bg-dark" id="post_comment" name="post_comment">Post/Edit Comment</button></div>';

            }
        } 
        ?>
    </div>
</div>
<script>
    $(document).ready(function(){
        // for posting or editing a comment
        $('#post_comment').click(function() {
            event.preventDefault();
            var comment = $('#text_comment').val();
            var reviewTitle = $('#title').text();

            if (comment) {
                $.ajax({ 
                    url: "<?php echo base_url() ?>SearchedReview/post_comment",
                    method: "POST",
                    data: { comment:comment, reviewTitle:reviewTitle },
                    success: function(response) {
                        location.reload();
                    }
                });

            } else {
                $('#emptyComment').remove();

                // if feedback exists already, do not add feedback again
                if (!$('#invalidComment').length) { 
                    $('#text_comment').addClass("is-invalid");
                    $('<div id="emptyComment" class="invalid-feedback">Please fill in the textarea.</div>').insertAfter("#text_comment");
                }
            }


        });

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

        // used for when a user clicks on agree or disagree button and it updates overall rating on the page as well
        $(document).on("click", ".rate", function() {
            var rating_clicked = $(this).data("value");
            var review_title = $(this).closest('.review_searched').find('.review_title').data("value");
            var overall_rating = $(this).closest('.review_searched').find('.overall_rating').data("value");

            console.log(rating_clicked+review_title+overall_rating);

            // ajax will send request to server to update db
            $.ajax({
                url: "<?php echo base_url(); ?>Home/review_rated_wishlisted",
                method: "POST",
                data: { rating_clicked:rating_clicked, review_title:review_title },
                success: function(response) {
                    console.log(response);
                }
            });

            if ($(this).hasClass('agree')) {

                if ($(this).hasClass('btn-dark')) {

                    // adds overall rating
                    if ($(this).closest('.review_searched').find('.overall_rating').hasClass('rated_before')) {
                        overall_rating+=2;
                    } else {
                        $(this).closest('.review_searched').find('.overall_rating').addClass('rated_before');
                        overall_rating++;
                    }
                    
                    $(this).closest('.review_searched').find('.disagree').removeClass('btn-danger');
                    $(this).closest('.review_searched').find('.disagree').addClass('btn-dark'); 

                    $(this).removeClass('btn-dark');
                    $(this).addClass("btn-success");
                }

            } else if ($(this).hasClass('disagree')) {

                if ($(this).hasClass('btn-dark')) {
                    // subtracts overall rating
                    if ($(this).closest('.review_searched').find('.overall_rating').hasClass('rated_before')) {
                        overall_rating-=2;
                    } else {
                        $(this).closest('.review_searched').find('.overall_rating').addClass('rated_before');
                        overall_rating--;
                    }

                    $(this).closest('.review_searched').find('.agree').removeClass('btn-success');
                    $(this).closest('.review_searched').find('.agree').addClass('btn-dark'); 

                    $(this).removeClass('btn-dark');
                    $(this).addClass("btn-danger");
                }
            }

            // ensure rating is updated on view and data values are updated as well
            $(this).closest('.review_searched').find('.overall_rating').text('Overall rating : ' + overall_rating);
            $(this).closest('.review_searched').find('.overall_rating').data('value', overall_rating)

        });

        // used for when a user clicks on agree or disagree button and it updates overall rating on the page as well
        $(document).on("click", ".wishlist", function() {
            var review_title = $(this).closest('.review_searched').find('.review_title').data("value");
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
    });
</script>