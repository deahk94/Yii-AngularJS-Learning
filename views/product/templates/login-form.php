<br><h3>{{ login.title }}</h3>
<form class="form-inline"  name="thisForm" novalidate>
	<div class="form-group" ng-class="{ 'has-error' : thisForm.username.$invalid && !thisForm.username.$pristine, 'has-success' : !thisForm.username.$invalid && !thisForm.username.$pristine }">
		<label class="control-label">Username</label>
		<input type="text" name="username" class="form-control" ng-model="login.username" required>
	</div>
	<div class="form-group" ng-class="{ 'has-error' : thisForm.password.$invalid && !thisForm.password.$pristine, 'has-success' : !thisForm.password.$invalid && !thisForm.password.$pristine }">
		<label class="control-label">Password</label>
		<input type="text" name="password" class="form-control" ng-model="login.password" required>
	</div>
	<button type="submit" class="btn btn-warning" href="javascript:void(0)" ng-click="login.login()" ng-disabled="thisForm.$invalid">Login</button>
	<br>
	<div class="form-group" ng-class="{ 'has-error' : thisForm.username.$invalid && !thisForm.username.$pristine }">
		<p ng-show="thisForm.username.$error.required" class="help-block">Username is required.</p>
	</div>
	<br>
	<div class="form-group" ng-class="{ 'has-error' : thisForm.password.$invalid && !thisForm.password.$pristine }">
		<p ng-show="thisForm.password.$error.required" class="help-block">Password is required.</p>
	</div>
	<!-- <div class="col-md-6 col-md-offset-3">
		<span class="col-md-2">{{ loginForm | json }}</span>
		<span class="col-md-2">{{ loginResponse | json }}</span>
	</div> -->
</form>
