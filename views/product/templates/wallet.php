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
				<li class="list-group-item" ng-repeat="record in walletCtrl.record_list">
					<a ui-sref-active="active" ui-sref="wallet.record({ recordId : record.id })">
						{{record.created_at}}
					</a>
				</li>
			</ul>
			<div class="panel-footer">
				<!-- <nav aria-label="Page navigation">
					<ul class="pagination">
						<li>
							<a href="#" aria-label="Previous">
								<span aria-hidden="true">&laquo;</span>
							</a>
						</li>
						<li><a href="javascript:void(0);">1</a></li>
						<li><a href="javascript:void(0);">2</a></li>
						<li><a href="javascript:void(0);">3</a></li>
						<li><a href="javascript:void(0);">4</a></li>
						<li><a href="javascript:void(0);">5</a></li>
						<li>
							<a href="#" aria-label="Next">
								<span aria-hidden="true">&raquo;</span>
							</a>
						</li>
					</ul>
				</nav> -->
				<ul uib-pagination total-items="$walletCtrl.record_total" ng-model="{{$walletCtrl.currentPage}}" class="pagination-sm" boundary-link-numbers="true" rotate="false"></ul>
			</div>
		</div>
	</div>
	
	<!-- viewport for child view -->
	<ui-view class="col-md-4"></ui-view>
</div>