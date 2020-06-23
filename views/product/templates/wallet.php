<div>
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-default">
			<div class="panel-heading">Wallet Balance</div>
			<div class="panel-body">{{walletCtrl.walletBalance | currency : 'MYR ' : 2 }}</div>
		</div>
	</div>

	<div class="col-md-2 col-md-offset-3">
		<div class="panel panel-default">
			<div class="panel-heading">Record List</div>
			<div class="panel-body">Click the record date below to show more information.</div>
			<ul class="list-group">
				<li class="list-group-item" ng-repeat="record in walletCtrl.pageData">
					<a ui-sref-active="active" ui-sref="wallet.record({ recordId : record.id })">
						{{record.created_at}}
					</a>
				</li>
			</ul>
			<div class="panel-footer">
				<ul uib-pagination total-items="walletCtrl.record_total" ng-change="walletCtrl.setPagingData()" ng-model="walletCtrl.currentPage" class="pagination-sm" boundary-link-numbers="true" rotate="false"></ul>
			</div>
		</div>
	</div>
	
	<!-- viewport for child view -->
	<ui-view class="col-md-4"></ui-view>
</div>