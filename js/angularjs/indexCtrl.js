var app = angular.module("myApp", []);

/**
 * [indexCtrl]
 * @param  {[type]} $scope [description]
 * @param  {[type]} $http  [description]
 * @return {[type]}        [description]
 */
app.controller("indexCtrl", function($scope, $http, $timeout) {
	/**
	 * [login 登入驗證]
	 * @param  {[type]} o [0:開始製作,1:直接登入]
	 * @return {[type]}   [description]
	 */
	$scope.login = function(o) {
		if (o == 0) {
			if ($scope.babyname == undefined || $scope.babyname == '') {
				show_toastr('toast-top-right', 'warning', '請輸入寶寶名稱！', '');
				return;
			}
			check_FB('result');
		} else if (o == 1) {
			check_FB('check');
		}
	}

	/**
	 * [check_FB description]
	 * @param  {[type]} o [description]
	 * @return {[type]}   [description]
	 */
	function check_FB(o) {
		func = o;
		if (typeof FB != 'undefined') {
			// $timeout(fb_login(fb_scope), 1000);
			setTimeout('fb_login("' + fb_scope + '")', 1000);
		} else {
			$timeout($scope.check_FB(o), 2000);
		}
	}

	/**
	 * [check_user description]
	 * @return {[type]} [description]
	 */
	$scope.check_user = function() {
		_show($('#loading'));
		$http({
			method: 'POST',
			url: checkuserurl,
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
			},
			data: 'fbid=' + fbid
		}).success(function(res) {
			if (func == 'result') {
				if (res.success) {
					show_toastr('toast-top-right', 'warning', '您已製作過！系統自動導向！', '');
					$timeout(function() {
						location.href = resulturl;
					}, 3000);
				} else {
					$('#baby_form').submit();
				}
			} else if (func == 'check') {
				if (res.success) {
					location.href = resulturl;
				} else {
					show_toastr('toast-top-right', 'error', '您尚未製作過！', '');
					_show($('#loading'));
				}
			}
		});
	}
});