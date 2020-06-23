<div class="panel panel-default">
	<div class="panel-heading">Record Details</div>
	<div class="panel-body">
<!-- 		<label>Record ID</label> {{recordItemCtrl.record.id}}
		<label>Quantity</label> {{recordItemCtrl.record.quantity}}
		<label>Price</label> {{recordItemCtrl.record.price}}
		<label>Total Price</label> {{recordItemCtrl.record.total_price}} -->

		<table class="table table-responsive">
			<thead>
				<tr>
					<th><label>Product Name (CODE)</label></th>
					<th><label>Quantity</label></th>
					<th><label>Price</label></th>
					<th><label>Total Price</label></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>{{recordItemCtrl.record.product_name}} ({{recordItemCtrl.record.product_code}})</td>
					<td>{{recordItemCtrl.record.quantity}}</td>
					<td>{{recordItemCtrl.record.price | currency : 'MYR ' : 2 }}</td>
					<td>{{recordItemCtrl.record.total_price | currency : 'MYR ' : 2 }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
