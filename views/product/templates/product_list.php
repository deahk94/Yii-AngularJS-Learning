<!-- <div class="row col-md-10 col-md-offset-1">
	<product-item ng-repeat="product in productListCtrl.product_list" product="product" purchase-modal="productListCtrl.purchase_modal(product)" class="col-md-3"></product-item>
</div> -->
<div class="col-md-6 col-md-offset-3">
	<div class="panel panel-primary">
		<div class="panel-heading">Product List</div>
		<table class="table table-hover table-condensed">
			<thead>
				<tr>
					<th><label>No.</label></th>
					<th><label>Code</label></th>
					<th><label>Name</label></th>
					<th><label>Quantity</label></th>
					<th><label>Price</label></th>
					<th><label>Purchase</label></th>
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat="product in productListCtrl.product_list">
					<td><label>#{{$index + 1}}</label></td>
					<td>{{product.code}}</td>
					<td>{{product.name}}</td>
					<td>{{product.quantity}}</td>
					<td>{{product.price | currency : 'MYR ' : 2}}</td>
					<td><button class="glyphicon glyphicon-shopping-cart" ng-click="productListCtrl.purchase_modal(product)"></button></td>
				</tr>
			</tbody>
			<!-- <tr><product-item ng-repeat="product in productListCtrl.product_list" product="product" purchase-modal="productListCtrl.purchase_modal(product)"></product-item></tr> -->
		</table>
	</div>
</div>