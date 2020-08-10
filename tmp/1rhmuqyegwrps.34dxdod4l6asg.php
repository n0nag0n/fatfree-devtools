<h1>Config Files</h1>
<p>These are config files in the <code>config/</code> directory.</p>
<form method="POST" action="/init-environment/update">
	<input type="hidden" name="next_page" value="choose-database">
	<div class="form-group">
		<label for="general_config">General Config</label>
		<input type="text" name="general_config" class="form-control" id="general_config" value="config.ini" required>
		<small class="form-text text-muted">You'll put things in here that are used in pretty much all requests of your project: database connection information, the name of your project, etc.</small>
	</div>
	<div class="form-group">
		<label for="routes">Routes</label>
		<input type="text" name="routes" class="form-control" id="routes" value="routes.ini" required>
		<small class="form-text text-muted">You'll define your routes (what happens when you type in a URL such as <code>/about-us</code> and what to do with it.</small>
	</div>
	<div class="form-group">
		<label for="cli_routes">CLI Routes</label>
		<input type="text" name="cli_routes" class="form-control" id="cli_routes" value="cli_routes.ini" required>
		<small class="form-text text-muted">This file will hold routes for scripts you want to use for crons, one off scripts, etc. Ex: <code>app/bin/cli generate list --force</code>.</small>
	</div>
	<div class="form-group">
		<label for="services">Services</label>
		<input type="text" name="services" class="form-control" id="services" value="services.php" required>
	</div>
	<a href="/init-environment/directory-setup" type="submit" class="btn btn-primary mb-5">Back</a>
	<button type="submit" class="btn btn-primary mb-5">Next</button>
</form>