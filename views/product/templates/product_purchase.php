<div class="modal-header">
	<button type="button" class="close" ng-click="ctrl_product_purchase.cancel()">&times;</button>
	<h3>Purchase Product</h3>
</div>
<div class="modal-body">
	<form name="thisForm" novalidate>
		<div class="form-group">
			<p class="help-block">Selected Product Details :</p>
			<label>Code</label>
			<input type="text" class="form-control" ng-model="ctrl_product_purchase.productData.name" readonly>
		</div>
		<div class="form-group">
			<label class="control-label">Name</label>
			<input type="text" class="form-control" ng-model="ctrl_product_purchase.productData.code" readonly>
		</div>
		<div class="form-group">
			<label class="control-label">Quantity</label>
			<input type="text" class="form-control" ng-model="ctrl_product_purchase.productData.quantity" readonly>
		</div>
		<div class="form-group">
			<label class="control-label">Price</label>
			<input type="text" class="form-control" ng-model="ctrl_product_purchase.productData.price | currency : 'MYR ' : 2" readonly>
		</div>
		<p class="help-block">Please fill out the following fields :</p>
		<div class="form-group" ng-class="{ 'has-error' : thisForm.purchase_quantity.$invalid && !thisForm.purchase_quantity.$pristine }">
			<label class="control-label">Purchase Quantity</label>
			<input name="purchase_quantity" type="number" class="form-control" ng-model="ctrl_product_purchase.input.quantity" min="1" max="{{ctrl_product_purchase.productData.quantity}}" required autofocus>
			<div ng-messages="thisForm.purchase_quantity.$error" ng-messages-multiple class="help-block" >
				<p ng-message="min,max">Quantity can only be between 1 and {{ctrl_product_purchase.productData.quantity}}!</p>
				<p ng-message="required">Quantity is required to purchase!</p>
			</div>
		</div>
		<!-- <pre>{{ thisForm.purchase_quantity.$error | json }}</pre> -->
		<div class="form-group">
			<label class="control-label">Total Price</label>
			<input type="text" class="form-control" value="{{ctrl_product_purchase.productData.price * ctrl_product_purchase.input.quantity | currency : 'MYR ' : 2}}" readonly>
		</div>
	</form>
</div>

<div class="modal-footer">
	<button type="submit" class="btn btn-success" ng-click="ctrl_product_purchase.purchase(thisForm)">Purchase</button>
</div>
