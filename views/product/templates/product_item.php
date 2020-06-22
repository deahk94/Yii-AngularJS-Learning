<!-- <hr>
<div class="row">
	<div class="row col-md-12">
		<span class="col-md-6"><label>Code</label> {{ctrl_product_item.product.code}}</span>
		<span class="col-md-6"><label>Quantity</label> {{ctrl_product_item.product.quantity}}</span>
	</div>
	<div class="row col-md-12">
		<span class="col-md-6"><label>Name</label> {{ctrl_product_item.product.name}}</span>
		<span class="col-md-6"><label>Price</label> {{ctrl_product_item.product.price}}</span>
	</div>
	<div class="col-md-12">
		<span class="col-md-12"><button class="btn btn-primary" ng-click="ctrl_product_item.purchase_modal(ctrl_product_item.product)">Purchase</button></span>
	</div>
</div>
<hr>
 -->
<tr>
	<td><label>Code</label> {{ctrl_product_item.product.code}}</td>
	<td><label>Name</label> {{ctrl_product_item.product.name}}</td>
	<td><label>Quantity</label> {{ctrl_product_item.product.quantity}}</td>
	<td><label>Price</label> {{ctrl_product_item.product.price}}</td>
	<td><button class="btn btn-primary" ng-click="ctrl_product_item.purchase_modal(ctrl_product_item.product)">Purchase</button></td>
	<hr>
</tr>