angular.module('app', ['ui.bootstrap', 'ngMessages', 'ui.router'])
.config(['testProvider', '$stateProvider', '$locationProvider', '$urlRouterProvider', function (testProvider, $stateProvider, $locationProvider, $urlRouterProvider) {
	testProvider.welcome = 'Hi';

	$stateProvider.state({
		name : 'login',
		url  : '/login',
		component : 'loginForm',
		resolve: {
			title : function () { return "Login Form" },
			username : function () { return "user1" },
			password : function () { return "user1" },
			// accessToken : ['$rootScope', function ($rootScope) {
			// 	return $rootScope.access_token;
			// }]
		},
	});

	$stateProvider.state({
		name : 'product',
		url  : '/products',
		component : 'productList',
	});

	$stateProvider.state({
		name : 'wallet',
		url  : '/wallet',
		component : 'walletPage',
	});

	$stateProvider.state({
		name : 'wallet.record',
		url  : '/{recordId}',
		component : 'recordItem',
	});

	$stateProvider.state({
		name : 'main-empty',
		url  : '/empty',
		component : 'empty',
	});

	$urlRouterProvider.otherwise('/login')

	$locationProvider.hashPrefix('');
}])
.provider('test', [function () {
	var that     = this;
	this.welcome = 'Hello';
	this.$get    = [function () {
		return function (name) {
			return [that.welcome, ', ', name].join('');
		}
	}];

	return this;
}])
.service('show_window', ['$window', function ($window) { //Testing
	return function (param) {
		return $window.alert("message : " + param);
	}
}])
.service('action_login' , ['$http', '$window', function ($http, $window) {
	return function (data) {
		return $http.post('http://localhost/basic/web/api/product/login', data)
		.then(
			function success(response) {
				//$window.alert("Login Success\nToken : " + response.data.token);
				return response.data;
			},
			function error(response) {
				$window.alert("Login Error : " + response.status + ", " + response.statusText);
				return response;
			}
			);
	}
}])
.service ('action_product_list', ['$http', '$window', function ($http, $window) {
	return function (data) {
		//console.log(data);
		return $http.get('http://localhost/basic/web/api/product/list-product', {
			params : data,
		})
		.then(
			function success(response) {
				//$window.alert("Product 1\nName : " + response.data[0].name);
				return response;
			},
			function error(response) {
				console.log(response);
				$window.alert("Product List Error : " + response.status + ", " + response.statusText);

				return response;
			}
			);
	}
}])
.service ('action_product_purchase' , ['$http', '$window', function ($http, $window) {
	return function (data) {
		var config = {
			params : {
				token : data.token,
			}
		};
		//console.log(config);
		return $http.post('http://localhost/basic/web/api/product/purchase-product', data, config)
		.then(
			function success(response) {
				//$window.alert("Record\n" + JSON.stringify(response.data));
				return response;
			},
			function error(response) {
				console.log(response);
				$window.alert("Purchase Error : " + response.status + ", " + response.statusText);

				return response;
			}
			);
	}
}])
.service ('action_wallet', ['$http', '$window', function ($http, $window) {
	return function (data) {
		var config = {
			params : data,
		};

		return $http.post('http://localhost/basic/web/api/product/wallet', data, config)
		.then(
			function success(response) {
				return response;
			},
			function error(response) {
				console.log(response, data, config);
				$window.alert("Wallet.get Error : " + response.status + ", " + response.statusText);

				return response.status;
			}
			);
	}
}])
.service ('action_record_list', ['$http', '$window', function ($http, $window) {
	return function (data) {
		//console.log(data);
		return $http.get('http://localhost/basic/web/api/product/list-record', {
			params : data,
		})
		.then(
			function success(response) {
				//$window.alert("Product 1\nName : " + response.data[0].name);
				return response;
			},
			function error(response) {
				console.log(response);
				$window.alert("Record List Error : " + response.status + ", " + response.statusText);

				return response;
			}
			);
	}
}])
.factory('localStorage', ['$window', function($window) {
	return {
		get: function(key) {
			return $window.localStorage.getItem(key);
		},
		set: function(key, value) {
			$window.localStorage.setItem(key, value);
		},
		remove: function(key) {
			$window.localStorage.removeItem(key);
		}
	}
}])
.component('empty', {
	templateUrl : 'http://localhost/basic/web/product/template?view=empty',
	controllerAs : 'empty',
	controller : ['show_window', function(show_window){
		var $ctrl = this;

		$ctrl.show = function()
		{
			show_window('Hello World');
		}
	}]
})
.component('loginForm', {
	templateUrl : 'http://localhost/basic/web/product/template?view=login-form',
	// template : '<h1>this is another way of defining template</h1>',

	bindings : {
		title    : '<',
		username : '<',
		password : '<',
	},
	controllerAs : 'login',
	controller   : ['$window', '$scope', '$state', 'action_login', 'test', 'localStorage', function($window, $scope, $state, action_login, test, localStorage) {
		var $ctrl = this;

		$ctrl.$onInit = function () {
			//console.log(test('John Doe')); //Testing the testProvider
			//console.log($state.current);

			$scope.loginForm = {
				username : $ctrl.username,
				password : $ctrl.password,
			}
		}

		$ctrl.login = function() {
			// console.log('Attempt to login');

			action_login($scope.loginForm)
			.then(function (data) {
				if(data.hasOwnProperty('token'))
				{
					localStorage.set('token', data.token);

					$state.go('product');
				} else {
					if (data.hasOwnProperty('error'))
						localStorage.remove('token', data.token);
				}
			});
		}
	}]
})
.component('productList', {
	templateUrl : 'http://localhost/basic/web/product/template?view=product_list',
	// bindings : {
	// 	// token : '<',
	// 	// obtainProductList : '&',
	// 	// purchaseModal : '&',
	// },
	controllerAs : 'productListCtrl',
	controller : ['$scope', '$state', '$uibModal', '$window', 'action_product_list', 'localStorage', 'action_wallet', function($scope, $state, $uibModal, $window, action_product_list, localStorage, action_wallet) {
		var $ctrl = this;
		var getQuery = {
			token : null,
		};

		$ctrl.$onInit = function () {
			//console.log($state.current);
			getQuery.token = localStorage.get('token');

			console.log(getQuery);

			if (getQuery.token)
			{
				action_product_list(getQuery)
				.then(function (response) {
					//console.log(response);

					if (response.status === 200) {
						//console.log('Product list obtained from response.');
						$ctrl.product_list = response.data;
					} else if (response.status === 401) {
						console.log('Removing token and redirect to login.');
						localStorage.remove('token');
						$state.go('login');
					}

					// $ctrl.obtainProductList({
					// 	list : data,
					// });
				});
			}
			else {
				console.log('Token not found!');
				$state.go('login');
			}
		}

		$ctrl.purchase_modal = function (product) {
			console.log("We\'re purchasing product with name : " + product.name + ", with id : " + product.id);
			$ctrl.openComponentModal(product);

			// $ctrl.purchaseModal({
			// 	product : product,
			// });
		}

		$ctrl.openComponentModal = function (productData) {
			var modalInstance = $uibModal.open({
				animation: true,
				component: 'productPurchase',
				resolve: {
					product : function () { return productData },
					token : function () { return $ctrl.token },
				}
			});

			//.result receive data from .close() in component's controller through bindings
			modalInstance.result
			.then(function (data) {
				$ctrl.purchase_record = data.data;

				$state.reload();

				//Display record content
				//$window.alert("From modal.result\npurchase_record : " + JSON.stringify($ctrl.purchase_record));

				$window.alert("Purchase success!");
			}, function () {
				console.log('modal-component dismissed at: ' + new Date());
			});
		};
	}]
})
// .component('productItem' , {
// 	templateUrl : 'http://localhost/basic/web/product/template?view=product_item',
// 	bindings : {
// 		product : '<',
// 		purchaseModal : '&',
// 	},
// 	controllerAs : 'ctrl_product_item',
// 	controller : ['$scope', function($scope) {
// 		var $ctrl = this;

// 		$ctrl.purchase_modal = function () {
// 			$ctrl.purchaseModal({
// 				product : $ctrl.product
// 			});
// 		}
// 	}]
// })
.component('productPurchase' , {
	templateUrl : 'http://localhost/basic/web/product/template?view=product_purchase',
	bindings : {
		resolve : '<',
		close : '&', // Controller(Non-Componenet's controller) assigned within $uibModal.open(), uses $uibModalInstance to represents the modal window instance
		dismiss : '&', // Close and dismiss bindings are from $uibModalInstance (Component's controller can access directly).
	},
	controllerAs : 'ctrl_product_purchase',
	controller : ['$scope', '$state', 'action_product_purchase', 'localStorage', function($scope, $state, action_product_purchase, localStorage) {
		var $ctrl = this;

		$ctrl.$onInit = function() {
			$ctrl.productData = {
				code : $ctrl.resolve.product.code,
				name : $ctrl.resolve.product.name,
				quantity : $ctrl.resolve.product.quantity,
				price : $ctrl.resolve.product.price,
			};

			$ctrl.input = {
				quantity : 1,
			};
		}

		$ctrl.purchase = function (form) {
			//console.log(form);

			$ctrl.apiParam = {
				token : $ctrl.resolve.token,
				id : $ctrl.resolve.product.id,
				quantity : $ctrl.input.quantity,
			};

			action_product_purchase($ctrl.apiParam)
			.then(function (response) {
				if (response.status === 200) {
					//.close will send the params to the instance of the modal's modal.result
					$ctrl.close({
						$value : response,
					});
				} else if (response.status === 401) {
					$ctrl.dismiss({
						$value : 'cancel',
					});

					console.log('Removing token and redirect to login.');
					localStorage.remove('token');
					$state.go('login');
				}				
			});
		}

		$ctrl.cancel = function () {
			$ctrl.dismiss({
				$value : 'cancel',
			});
		}
	}]
})
.component('walletPage' , {
	templateUrl : 'http://localhost/basic/web/product/template?view=wallet',
	controllerAs : 'walletCtrl',
	controller : ['$window', '$rootScope', '$state', 'action_record_list', 'action_wallet', 'localStorage', function($window, $rootScope, $state, action_record_list, action_wallet, localStorage) {
		var $ctrl = this;
		var getQuery = {
			token : null,
			isAdmin : false,
		};

		$ctrl.$onInit = function () {
			$ctrl.currentPage = 1;
			getQuery.token = localStorage.get('token');
			//console.log(getQuery);

			if (getQuery.token)
			{
				action_record_list(getQuery)
				.then(function (response) {
					//console.log(response);
					if (response.status === 200) {
						$ctrl.record_list = response.data; //Exposed to view
						$ctrl.record_total = Object.keys(response.data).length;
						$rootScope.records = $ctrl.record_list;
					} else if (response.status === 401) {
						localStorage.remove('token');
						$state.go('login');
					}
				});

				action_wallet(getQuery)
				.then(function (response) {
					//console.log(response);
					if (response.status === 200) {
						$ctrl.walletBalance = response.data.wallet.amount;
					} else if (response.status === 401) {
						console.log('Removing token and redirect to login.');
						localStorage.remove('token');
						$state.go('login');
					}
				});
			}
			else {
				console.log('Token not found!');
				$state.go('login');
			}
		}
	}]
})
.component('recordItem' , {
	templateUrl : 'http://localhost/basic/web/product/template?view=record-item',
	controllerAs : 'recordItemCtrl',
	controller : ['$window', '$rootScope', '$state', '$stateParams','$filter', 'action_record_list', 'localStorage', function($window, $rootScope, $state, $stateParams, $filter, action_record_list, localStorage) {
		var $ctrl = this;
		var getQuery = {
			token : null,
		};

		$ctrl.$onInit = function () {
			getQuery.token = localStorage.get('token');
			if (getQuery.token)
			{
				$ctrl.record =  $filter('filter')($rootScope.records, {'id': $stateParams.recordId})[0];
			}
			else {
				console.log('Token not found!');
				$state.go('login');
			}
		}

	}]
})
.controller('main_controller', ['$scope', '$rootScope', '$window', '$uibModal', '$state', 'localStorage', function ($scope, $rootScope, $window, $uibModal, $state, localStorage) {
	var $ctrl = this;

	$ctrl.$onInit = function () {
		//console.log($state.current);
		if (!localStorage.get('token')) {
			console.log('Redirect to login from main_controller.');

			$state.go('login');
		}
	}

//Non UI Router's structure
	// $ctrl.signInSuccess = function (access_token, uid, username) {
	// 	$rootScope.access_token = access_token;
	// 	$ctrl.is_login	   = true;
	// 	//console.log($ctrl.access_token, uid, username);
	// }

	// $ctrl.getToken = function () {
	// 	return $ctrl.access_token;
	// }

	// $ctrl.getProduct = function	() {
	// 	return $ctrl.product_choice;
	// }

	// $ctrl.obtainProductList = function (list) {
	// 	//console.log("List : " + JSON.stringify(list));
	// 	$ctrl.product_list = list;
	// }

	// $ctrl.purchaseRedirect = function (product) {
	// 	$ctrl.product_choice = product;
	// 	$ctrl.is_purchasing = true;
	// }

	// $ctrl.purchaseComplete = function (record) {
	// 	$ctrl.is_purchasing = false;
	// 	$ctrl.purchase_record = record;
	// }

	// $ctrl.openComponentModal = function (productData) {
	// 	var modalInstance = $uibModal.open({
	// 		animation: true,
	// 		component: 'productPurchase',
	// 		resolve: {
	// 			product : function () { return productData },
	// 			token : function () { return $ctrl.getToken() },
	// 		}
	// 	});

	// 	modalInstance.result.then(function (data) {
	// 		$ctrl.purchase_record = data;
	// 		$window.alert("From modal.result\npurchase_record : " + JSON.stringify($ctrl.purchase_record));
	// 	}, function () {
	// 		console.log('modal-component dismissed at: ' + new Date());
	// 	});
	// };
}])