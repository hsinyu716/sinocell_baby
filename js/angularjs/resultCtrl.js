var app = angular.module("myApp", ['ui.bootstrap', 'angularFileUpload', 'dialogs']);

app.factory('myData', function() {
	return {
		get: function(data, offset, limit) {
			return data.slice(offset, offset + limit);
		},
		count: function(data) {
			return data.length;
		}
	};
});

app.factory('myHttp', function($rootScope) {
	return {
		set: function(url, params, func, obj) {
			$rootScope.ajaxv = [];
			$rootScope.ajaxv.func = func;
			$rootScope.ajaxv.obj = obj;
			$rootScope.$broadcast('proccess');
			// _show($('#loading'));
			// $http({
			// 	method: 'POST',
			// 	url: url,
			// 	headers: {
			// 		'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
			// 	},
			// 	data: params
			// }).success(function(data) {
			// 	$rootScope.ajax.Data = data;
			// 	$rootScope.$broadcast('proccess');
			// });
		}
	};
});


/**
 * [結果頁]
 * @param  {[type]} $scope [description]
 * @param  {[type]} $http  [description]
 * @return {[type]}        [description]
 */
app.controller("resultCtrl", function($scope, $http, myData, myHttp, $timeout, $upload, $dialogs, myHttp) {
	$scope.user = user;
	$scope.msg = msg;
	$scope.rank = rank;

	/**
	 * ajax 回應處理
	 * @return {[type]} [description]
	 */
	$scope.$on('proccess', function() {
		data = $scope.ajaxv.data;
		o = $scope.ajaxv.obj;
		switch ($scope.ajaxv.func) {
			case 'joint':
				if (data.success) {
					show_toastr('toast-top-right', 'success', '邀請成功！', '');
				}
				break;
			case 'edit_':
				if (data.success) {
					show_toastr('toast-top-right', 'success', '編輯成功！', '');
				}
				user.is_update = 'Y';
				break;
			case 'add_friend':
				if (data.success) {
					show_toastr('toast-top-right', 'success', '加入成功！', '');
					$scope.is_friend = 'true';
					$scope.friends = data.friends;
					$scope.lists = data.friends;
				}
				break;
			case 'open_2':
			case 'open_3':
				$scope.lists = data.rank;
				$scope.pageinit();
				_show($('#list_div'));
				break;
			case 'search_':
				$scope.lists = data.rank;
				$scope.pageinit();
				break;
			case 'set_message':
				$scope.msg = data.msg;
				$scope.message = '';
				break;
			case 'del_message':
				$scope.msg.splice(o, 1);
				show_toastr('toast-top-right', 'success', '刪除成功！', '');
				break;
			case 'pick_pic':
				$scope.setmypic(o);
				break;
		}
		_show($('#loading'));
	});

	/**
	 * [sexchange 更改性別處理]
	 * @param  {[type]} o [description]
	 * @return {[type]}   [description]
	 */
	$scope.sexchange = function(o) {
		$scope.user.sex = o;
	}
	/**
	 * [req_joint 邀請共同經營]
	 * @return {[type]} [description]
	 */
	$scope.req_joint = function() {
		$scope.apprequests(2);
	}
	/**
	 * [edit_check 編輯驗證]
	 * @return {[type]} [description]
	 */
	$scope.edit_check = function() {

		if ($scope.user.daddy == undefined) {
			show_toastr('toast-top-right', 'error', '請輸入爸爸名字！', '');
			return;
		}
		if ($scope.user.mom == undefined) {
			show_toastr('toast-top-right', 'error', '請輸入媽媽名字！', '');
			return;
		}
		if ($scope.user.babybirthday == undefined) {
			show_toastr('toast-top-right', 'error', '請選擇寶寶生日！', '');
			return;
		}
		if ($scope.user.sex == undefined) {
			show_toastr('toast-top-right', 'error', '請選擇寶寶性別！', '');
			return;
		}
		if (user.is_update == 'N') {
			$scope.apprequests(1);
		} else {
			$scope.edit_();
		}
	}
	/**
	 * [apprequests FB apprequests]
	 * @param  {[type]} o [1:編輯後邀請朋友,2:共同經營,3:邀請朋友]
	 * @return {[type]}   [description]
	 */
	$scope.apprequests = function(o) {
		var message = '跟其他爸爸媽媽說一起讓寶寶做朋友';
		var limit = 50;
		if (o == 2) {
			message = '邀請您的另一半共同經營';
			limit = 1;
		}
		FB.ui({
			method: 'apprequests',
			message: message,
			max_recipients: limit,
		}, requestCallback);

		function requestCallback(response) {
			if (o == 1) {
				$scope.edit_();
			} else if (o == 2 && response.request && response.to) {
				params = 'serial_id=' + user.serial_id + '&tofbid=' + response.to;
				myHttp.set(jointurl, params, 'joint');
				// $http({
				// 	method: 'POST',
				// 	url: jointurl,
				// 	headers: {
				// 		'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
				// 	},
				// 	data: 
				// }).success(function(data) {
				// 	if (data.success) {
				// 		show_toastr('toast-top-right', 'success', '邀請成功！', '');
				// 	}
				// 	_show($('#loading'));
				// });
			}
		}
	}

	/**
	 * [edit_ 編輯處理]
	 * @return {[type]} [description]
	 */
	$scope.edit_ = function() {
		params = $('#userForm').serialize();
		myHttp.set(editurl, params, 'edit_');
		// _show($('#loading'));
		// $http({
		// 	method: 'POST',
		// 	url: editurl,
		// 	headers: {
		// 		'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
		// 	},
		// 	data: $('#userForm').serialize()
		// }).success(function(data) {
		// 	if (data.success) {
		// 		show_toastr('toast-top-right', 'success', '編輯成功！', '');
		// 	}
		// 	user.is_update = 'Y';
		// 	_show($('#loading'));
		// });
	}

	$scope.is_view = is_view;
	$scope.is_friend = is_friend;
	$scope.friends = friends;
	$scope.lists = friends;

	/**
	 * [add_friend description]
	 * @param {[type]} o [description]
	 */
	$scope.add_friend = function(o) {
		params = 'serial_id=' + o + '&is_view=' + $scope.is_view;
		myHttp.set(editurl, params, 'add_friend');
		// _show($('#loading'));
		// $http({
		// 	method: 'POST',
		// 	url: addfriendurl,
		// 	headers: {
		// 		'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
		// 	},
		// 	data: 'serial_id=' + o + '&is_view=' + $scope.is_view
		// }).success(function(data) {
		// 	if (data.success) {
		// 		show_toastr('toast-top-right', 'success', '加入成功！', '');
		// 		$scope.is_friend = 'true';
		// 		$scope.friends = data.friends;
		// 		$scope.lists = data.friends;
		// 	}
		// 	_show($('#loading'));
		// });
	}

	$scope.list_o = 0;
	/**
	 * [open_ 寶寶列表]
	 * @param  {[type]} o [1:好友,2:所有,3:排行]
	 * @return {[type]}   [description]
	 */
	$scope.open_ = function(o) {
		$scope.list_o = o;
		if (o == 1) {
			$scope.lists = $scope.friends;
			_show($('#list_div'));
			$scope.pageinit();
		} else if (o == 2) {
			params = [];
			myHttp.set(moreurl + '/' + o, params, 'open_' + o);
			// _show($('#loading'));
			// $http({
			// 	method: 'POST',
			// 	url: moreurl + '/2',
			// 	headers: {
			// 		'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
			// 	},
			// 	data: $('#userForm').serialize()
			// }).success(function(data) {
			// 	_show($('#loading'));
			// 	$scope.lists = data.rank;
			// 	$scope.pageinit();
			// 	_show($('#list_div'));
			// });
		} else if (o == 3) {
			params = [];
			myHttp.set(moreurl + '/' + o, params, 'open_' + o);
			// $http({
			// 	method: 'POST',
			// 	url: moreurl + '/3',
			// 	headers: {
			// 		'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
			// 	},
			// 	data: $('#userForm').serialize()
			// }).success(function(data) {
			// 	$scope.lists = data.rank;
			// 	$scope.pageinit();
			// 	_show($('#list_div'));
			// });
		}
	}

	/**
	 * [search_ 搜尋]
	 * @return {[type]} [description]
	 */
	$scope.search_ = function() {
		params = 'search=' + $scope.searchText;
		myHttp.set(moreurl + '/' + o, params, 'search_');
		// _show($('#loading'));
		// $http({
		// 	method: 'POST',
		// 	url: moreurl + '/' + $scope.list_o,
		// 	headers: {
		// 		'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
		// 	},
		// 	data: 'search=' + $scope.searchText
		// }).success(function(data) {
		// 	$scope.lists = data.rank;
		// 	$scope.pageinit();
		// 	_show($('#loading'));
		// });
	}

	// datepicker
	$scope.dateOptions = {
		'year-format': "'yy'",
		'starting-day': 1,
		'show-weeks': false
	};
	$scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'shortDate'];
	$scope.format = $scope.formats[1];

	/**
	 * [pageChanged 頁碼]
	 * @param  {[type]} pageNo [description]
	 * @return {[type]}        [description]
	 */
	$scope.pageChanged = function(pageNo) {
		$scope.bigCurrentPage = pageNo;
		$scope.lists = myData.get($scope.pageList, ($scope.bigCurrentPage - 1) * $scope.numPerPage, $scope.numPerPage);
	};

	/**
	 * [pageinit 初始化]
	 * @return {[type]} [description]
	 */
	$scope.pageinit = function() {
		$scope.maxSize = 5;
		$scope.bigTotalItems = $scope.lists.length;
		$scope.bigCurrentPage = 1;
		$scope.numPerPage = 1;
		$scope.pageList = $scope.lists;
		$scope.pageChanged(1);
	}
	$scope.pageinit();

	$scope.my_view = function() {
		location.href = resulturl;
	}

	/**
	 * [set_message 留言]
	 */
	$scope.set_message = function() {
		if ($scope.message == undefined || $scope.message == '') {
			show_toastr('toast-top-right', 'error', '請輸入留言！', '');
			return;
		}
		params = 'message=' + $scope.message + '&serial_id=' + user.serial_id;
		myHttp.set(msgurl, params, 'set_message');
		// _show($('#loading'));
		// $http({
		// 	method: 'POST',
		// 	url: msgurl,
		// 	headers: {
		// 		'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
		// 	},
		// 	data: 'message=' + $scope.message + '&serial_id=' + user.serial_id
		// }).success(function(data) {
		// 	_show($('#loading'));
		// 	$scope.msg = data.msg;
		// 	$scope.message = '';
		// });
	}

	$scope.del_message = function(o) {
		params = 'serial_id=' + user.serial_id;
		myHttp.set(dmsgurl, params, 'del_message');
		// _show($('#loading'));
		// $http({
		// 	method: 'POST',
		// 	url: dmsgurl,
		// 	headers: {
		// 		'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
		// 	},
		// 	data: 'serial_id=' + user.serial_id
		// }).success(function(data) {
		// 	_show($('#loading'));
		// 	$scope.msg.splice(o, 1);
		// 	show_toastr('toast-top-right', 'success', '刪除成功！', '');
		// });
	}

	/**
	 * [gallery 圖庫上傳]
	 * @return {[type]} [description]
	 */
	$scope.gallery = function() {
		_show($('#gallery_div'));
	}

	/**
	 * [pick_pic 選圓庫]
	 * @param  {[type]} o [description]
	 * @return {[type]}   [description]
	 */
	$scope.pick_pic = function(o) {
		_show($('#gallery_div'));
		params = 'path=' + o + '&serial_id=' + user.serial_id;
		myHttp.set(setpicurl, params, 'pick_pic');
		// _show($('#loading'));
		// $http({
		// 	method: 'POST',
		// 	url: setpicurl,
		// 	headers: {
		// 		'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
		// 	},
		// 	data: 'path=' + o + '&serial_id=' + user.serial_id
		// }).success(function(data) {
		// 	_show($('#loading'));
		// 	$scope.setmypic(o);
		// });
	}

	/**
	 * [setmypic 更新圖片]
	 * @param  {[type]} o [路徑]
	 * @return {[type]}   [description]
	 */
	$scope.setmypic = function(o) {
		$scope.mypic = o;
	}
	/**
	 * [onFileSelect 照片上傳]
	 * @param  {[type]} $files [description]
	 * @return {[type]}        [description]
	 */
	$scope.onFileSelect = function($files) {
		var file = $files[0];
		_show($('#loading'));
		$scope.upload = $upload.upload({
			url: uploadurl, //upload.php script, node.js route, or servlet url
			// method: POST or PUT,
			// headers: {'headerKey': 'headerValue'},
			// withCredentials: true,
			data: {
				serial_id: user.serial_id
			},
			file: file,
			// file: $files, //upload multiple files, this feature only works in HTML5 FromData browsers
			/* set file formData name for 'Content-Desposition' header. Default: 'file' */
			fileFormDataName: 'fileToUpload', //OR for HTML5 multiple upload only a list: ['name1', 'name2', ...]
			/* customize how data is added to formData. See #40#issuecomment-28612000 for example */
			//formDataAppender: function(formData, key, val){} //#40#issuecomment-28612000
		}).progress(function(evt) {
			console.log('percent: ' + parseInt(100.0 * evt.loaded / evt.total));
		}).success(function(data, status, headers, config) {
			// file is uploaded successfully
			_show($('#loading'));
			$scope.mypic = data.src;

		});
	}

	$scope.confirm = function(msg, func, val) {
		var dlg = $dialogs.confirm('Please Confirm', msg);
		dlg.result.then(function(btn) {
			if (func == 'powall') {
				show_toastr('toast-top-right', 'success', '分享成功！', ''); //$scope.powall();	
			} else {
				show_toastr('toast-top-right', 'success', '刪除成功！', ''); //$scope.del_message(val);	
			}
		}, function(btn) {});
	}

	$scope.powall = function() {
		_show($('#loading'));
		$http({
			method: 'POST',
			url: posturl,
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
			},
			data: "type=share"
		}).success(function(data) {
			_show($('#loading'));
			show_toastr('toast-top-right', 'success', '分享成功！', '');
		});
	}
});