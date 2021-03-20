<h2><?php echo $title; ?></h2>
<?php echo validation_errors(); ?>
<?php echo form_open('papers/create'); ?>
	<label for="title">Title</label> 
	<input type="input" name="title"/><br/> 
	<label for="year">Year</label>
	<input type="input" name="year"/><br/>
	<label for="authors">Authors</label>
	<textarea name="authors"></textarea><br />
	<input type="submit" name="submit" value="Create paper item" /> 
</form> 
