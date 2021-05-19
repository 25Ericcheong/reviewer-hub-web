<div class="row no-gutters container-sm" id="wishlist">
    <div class="col-sm-12 mb-1">
        <h3>Your Wishlist</h3>
    </div>

    <div class="card-deck col-sm-12 d-flex flex-row flex-nowrap overflow-auto ml-1">
    <?php
    if ($reviews) {
        $i = 0;
        $len = count($reviews);
        // each review wishlisted by user will create a new card
        foreach($reviews as $review) {
            $title = $review['reviewTitle']; 
            $description = $review['description'];
            $date = $review['reviewDate']; 
            $image_path = $review['reviewImagePath'];

            if ($i == 0) {
                echo 
                '
                <div class="card ml-0 mb-3 review_card" style="min-width: 300px; max-width: 300px;">
                    <img src="'.$image_path.'" class="card-img-top" alt="Image related to review wishlisted should be displayed here">
                    <div class="card-body">
                        <a class="m-0 p-0 text-body" href="'.base_url().'SearchedReview/search/'.$title.'"><h5 class="card-title review_wishlisted_title text-truncate text-body" data-value="'.$title.'">'.$title.'</h5></a>
                        <p class="card-text">'.$description.'</p>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center" >
                        <small>Reviewed on '.date('j M Y', strtotime($date)).'</small>
                        <button class="btn btn-danger remove_wishlist" data-value="no">Remove</button>
                    </div>
                </div>';
            } else {
                echo 
                '
                <div class="card mb-3 review_card" style="min-width: 300px; max-width: 300px;">
                    <img src="'.$image_path.'" class="card-img-top" alt="Image related to review wishlisted should be displayed here">
                    <div class="card-body">
                        <a class="m-0 p-0 text-body" href="'.base_url().'SearchedReview/search/'.$title.'"><h5 class="card-title review_wishlisted_title text-truncate text-body" data-value="'.$title.'">'.$title.'</h5></a>
                        <p class="card-text">'.$description.'</p>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center" >
                        <small>Reviewed on '.date('j M Y', strtotime($date)).'</small>
                        <button class="btn btn-danger remove_wishlist" data-value="no">Remove</button>
                    </div>
                </div>';
            }
            $i++;
        }
    } else {
        echo 
        '
        <div class="card ml-0 mb-3" style="min-width: 300px; max-width: 300px;">
            <div class="card-body" style="height: 35vh">
                <h5 class="card-title review_wishlisted_title text-truncate text-body" >No Reviews Found</h5>
                <p class="card-text">You have not added any reviews into your wishlist!</p>
            </div>
            <div class="card-footer" >
                <small>Add any reviews to your wishlist to remember</small>
            </div>
        </div>';
    }
    ?>
    </div>
</div>
<script>
    $(document).ready(function() {

        $(document).on("click", ".remove_wishlist", function () {
            var review_title = $(this).closest('.review_card').find('.review_wishlisted_title').data("value");
            var review_wishlisted = $(this).data("value");

            // ajax will send request to server to update db
            $.ajax({
                url: "<?php echo base_url(); ?>Home/review_rated_wishlisted",
                method: "POST",
                data: { review_wishlisted:review_wishlisted, review_title:review_title },
            });

            $(this).closest('.review_card').remove();
        })

    });
</script>