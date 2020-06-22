<div style="display: block; text-align: center" ng-controller="main_controller as m">
<!-- 	<h2>{{model.input}} x {{model.multiplier}} = {{ model.input * model.multiplier | currency : 'MYR ' : 2 }}</h2>
	<input type="number" ng-model="model.input">
	<input type="number" ng-model="model.multiplier">
	<br><br><br>
	<a href="javascript:void(0)" ng-click="login.test()">Test</a>
	<a href="javascript:void(0)" ng-click="login.show()">Show</a> -->
	
	<ui-view></ui-view>

	<?php /*
	<login-form ng-if="!m.is_login" title="Login Form" username="default_username" password="default_password" on-sign-in="m.signInSuccess(token, userId, username)"></login-form>
	<!-- <p>Token : {{m.access_token}}</p> -->
	<product-list ng-if="m.is_login" token="m.getToken()" obtain-product-list="m.obtainProductList(list)" purchase-modal="m.openComponentModal(product)"></product-list>
	*/ ?>
</div>