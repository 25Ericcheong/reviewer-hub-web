<?php echo form_open_multipart('UserReview/post_review'); ?>
<div id="user_post_review_form" class="container">
  <h3>Post a Review</h3>
  <div class="row form-group">
    <label for="review_title" class="col-sm-2 col-form-label">
      Title of review
    </label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="review_title" value="<?php if ($title) {
        echo $title;
      }?>" name="review_title">
    </div>
    <div class="col-sm-12">
        <?php echo $title_error; ?>
    </div>
  </div>

  <div class="row form-group">
    <div class="col-sm form">
      <div class="row form-group">
        <label for="title_for_upload" class="col-sm-12 form-label">
          Upload one or multiple images for review
        </label>
        <div class="col-sm-12">
          <div class="form-group row">
            <div class="col-sm-12" id="choose_images_upload">
              <input id="input_multi_files" type="file" name="files[]" multiple="multiple" class="col-sum">
            </div>
            <div class="col-sm-12">
              <?php echo $upload_images_error."<br>"; ?>
              <?php echo $resize_images_error; ?>
            </div>
          </div>

          <div class="row form-group mb-0">
            <label class="col-sm-12 form-label" for="text_description">
              Description (min 10 words)
            </label>
            <div class="col-sm-12">
              <textarea name="review_description" class="form-control col-sm-12" id="text_description" style='padding:1%; resize:none;'><?php
              if ($description) {
                echo $description;
              }
              ?></textarea>
            </div>
            <div class="col-sm-12">
              <?php echo $description_error; ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm form">
      <div class="row form-group">
        <label for="country_reviewed_in" class="col-sm-4 col-form-label">
          Product Reviewed In
        </label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="country_reviewed_in" value="<?php if ($country) {
            echo $country;
          }?>" name="country_reviewed_in">
        </div>
        <div class="col-sm-12">
              <?php echo $country_error; ?>
            </div>
      </div>

      <div class="row form-group">
        <label for="brand_review" class="col-sm-4 col-form-label">
          Brand
        </label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="brand_review" value="<?php if ($brand) {
            echo $brand;
          }?>" name="review_brand">
        </div>
        <div class="col-sm-12">
          <?php echo $brand_error; ?>
        </div>
      </div>

      <div class="row form-group">
        <label for="rating_type" class="col-sm-2 col-form-label">
          Rate
        </label>
        <label for="rating" class="col-sm-2 col-form-label d-flex justify-content-center">
          Rating
        </label>
        <label for="reason" class="col-sm-8 col-form-label">
          Reason
        </label>

        <!-- for quality ratingg row -->
        <label for="rating_type" class="col-sm-2 col-form-label">
          Quality
        </label>
        <div class="col-sm-2">
          <select class="custom-select" id="quality_rating" name="quality_rating">
            <option value="0" <?php if ($quality_rating == "0") {echo "selected";} ?>>0</option>
            <option value="1" <?php if ($quality_rating == "1") {echo "selected";} ?>>1</option>
            <option value="2" <?php if ($quality_rating == "2") {echo "selected";} ?>>2</option>
            <option value="3" <?php if ($quality_rating == "3") {echo "selected";} ?>>3</option>
            <option value="4" <?php if ($quality_rating == "4") {echo "selected";} ?>>4</option>
            <option value="5" <?php if ($quality_rating == "5") {echo "selected";} ?>>5</option>
          </select>
        </div>
        <div class="col-sm-8 mb-3">
          <input type="text" class="form-control" id="quality_comment" value="<?php if ($quality_comment) {
              echo $quality_comment;
          }?>" name="review_quality_comment">
        </div>

        <!-- for usability rating row -->
        <label for="rating_type" class="col-sm-2 col-form-label">
          Usability
        </label>
        <div class="col-sm-2">
          <select class="custom-select" id="usability_rating" name="usability_rating">
            <option value="0" <?php if ($usability_rating == "0") {echo "selected";} ?>>0</option>
            <option value="1" <?php if ($usability_rating == "1") {echo "selected";} ?>>1</option>
            <option value="2" <?php if ($usability_rating == "2") {echo "selected";} ?>>2</option>
            <option value="3" <?php if ($usability_rating == "3") {echo "selected";} ?>>3</option>
            <option value="4" <?php if ($usability_rating == "4") {echo "selected";} ?>>4</option>
            <option value="5" <?php if ($usability_rating == "5") {echo "selected";} ?>>5</option>
          </select>
        </div>
        <div class="col-sm-8">
          <input type="text" class="form-control mb-3" id="usability_comment" value="<?php if ($usability_comment) {
              echo $usability_comment;
          }?>" name="review_usability_comment">
        </div>

        <!-- for usability rating row -->
        <label for="rating_type" class="col-sm-2 col-form-label">
          Price
        </label>
        <div class="col-sm-2">
          <select class="custom-select" id="price_rating" name="price_rating">
            <option value="0" <?php if ($price_rating == "0") {echo "selected";} ?>>0</option>
            <option value="1" <?php if ($price_rating == "1") {echo "selected";} ?>>1</option>
            <option value="2" <?php if ($price_rating == "2") {echo "selected";} ?>>2</option>
            <option value="3" <?php if ($price_rating == "3") {echo "selected";} ?>>3</option>
            <option value="4" <?php if ($price_rating == "4") {echo "selected";} ?>>4</option>
            <option value="5" <?php if ($price_rating == "5") {echo "selected";} ?>>5</option>
          </select>
        </div>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="price_comment" value="<?php if ($price_comment) {
              echo $price_comment;
          }?>" name="price_comment">
        </div>

      </div>
    </div>
  </div>

  <!-- recommendation and summary -->
  <div class="row form-group">
    <div class="col-sm-6">
      <div class="row">
        <label for="recommendation" class="col-sm-3 col-form-label">
          Recommendation
        </label>
        <div class="col-sm-9 d-flex align-items-center">
          <div class="custom-control custom-radio custom-control-inline col-sm-3">
            <input type="radio" id="yes_recommend" name="recommendation" class="custom-control-input" value="yes" <?php if ($recommendation == 'yes') { echo "checked"; } ?>>
            <label class="custom-control-label" for="yes_recommend">Yes</label>
          </div>
          <div class="custom-control custom-radio custom-control-inline col-sm-3">
            <input type="radio" id="no_recommend" name="recommendation" class="custom-control-input" value="no" <?php if ($recommendation == 'no') { echo "checked"; } ?>>
            <label class="custom-control-label" for="no_recommend">No</label>
          </div>
          <div class="custom-control custom-radio custom-control-inline col-sm-3">
            <input type="radio" id="not_sure_recommend" name="recommendation" class="custom-control-input" value="not sure" <?php if ($recommendation == 'not sure') { echo "checked"; } ?>>
            <label class="custom-control-label" for="not_sure_recommend">Not Sure</label>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="row">
        <label for="summary" class="col-sm-2 col-form-label">
          Summary
        </label>
        <div class="col-sm-10">   
          <input type="text" class="form-control" id="summary" value="<?php if ($summary) {
                echo $summary;
            }?>" name="summary">
        </div>
      </div>
    </div>
    <div class="col-sm-12">
      <?php echo $recommendation_error; ?>
    </div>
  </div>

  <!-- users can choose to post anonymously -->
  <div class="row form-group">
    <div class="col-sm-6">
      <div class="row">
        <label for="anonymous" class="col-sm-3 col-form-label">
          Post Anonymously
        </label>
        <div class="col-sm-9 d-flex align-items-center">
          <div class="custom-control custom-radio custom-control-inline col-sm-4">
            <input type="radio" id="yes_anon" name="anonymous" class="custom-control-input" value="yes" <?php if ($anonymous == 'yes') { echo "checked"; } ?>>
            <label class="custom-control-label" for="yes_anon">Yes</label>
          </div>
          <div class="custom-control custom-radio custom-control-inline col-sm-4">
            <input type="radio" id="no_anon" name="anonymous" class="custom-control-input" value="no" <?php if ($anonymous == 'no') { echo "checked"; } ?>>
            <label class="custom-control-label" for="no_anon">No</label>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-12">
      <?php echo $anonymous_error; ?>
    </div>
  </div>

  <div class="row form-group">
    <div class="col-sm-12 d-flex justify-content-end" id="post_review_button">
      <!-- <input type="submit" class="bg-dark" id="preview_images_button" name="preview_images_button" value="Post Review" style="color:white; padding: 0.375rem 0.75rem; border-radius: 0.25rem"> -->

      <button type="submit" class="bg-dark" id="preview_images_button" name="preview_images_button" style="color:white; padding: 0.375rem 0.75rem; border-radius: 0.25rem">Post Review</button>

    </div>
  </div>
  <?php echo $test; ?>
  <?php echo $success; ?>
</div>
<?php echo form_close(); ?>
