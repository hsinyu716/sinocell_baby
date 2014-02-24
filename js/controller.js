var app = angular.module("myApp", []);

app.controller("indexCtrl", function($scope, $http) {
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
});

app.controller("resultCtrl", function($scope, $http) {
	$scope.user = user;

	$scope.sexchange = function(o) {
		$scope.user.sex = o;
	}

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
			FB.ui({
				method: 'apprequests',
				message: '跟其他爸爸媽媽說一起讓寶寶做朋友',
				max_recipients: 3,
			}, requestCallback);

			function requestCallback(response) {
				if (response.request && response.to) {
					$scope.edit_();
				}
			}
		}else{
			$scope.edit_();
		}
	}

	$scope.edit_ = function(){
		_show($('#loading'));
		$http({
			method: 'POST',
			url: editurl,
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
			},
			data: $('#baby_form').serialize()
		}).success(function(data) {
			if (data.success) {
				show_toastr('toast-top-right', 'success', '編輯成功！', '');
			}
			_show($('#loading'));
		});
	}

	$scope.exchange = function(pid, p) {
		if (eval($scope.point) < eval(p)) {
			show_toastr('toast-top-right', 'error', '您的點數不足於兌換！', '');
			return;
		}
		_show($('#loading'));
		$http({
			method: 'POST',
			url: exchangeurl,
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
			},
			data: 'prize_serial=' + pid + '&point=' + p
		}).success(function(data) {
			if (data.success) {
				show_toastr('toast-top-right', 'success', '兌換成功！', '');
			} else {
				show_toastr('toast-top-right', 'error', '兌換失敗！', '');
			}
			$scope.point = data.point;
			_show($('#loading'));
		});
	}
	$scope.more = function() {
		location.href = moreurl;
	}
	$scope.refresh = function() {
		_show($('#loading'));
		$http({
			method: 'POST',
			url: refreshurl,
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
			}
		}).success(function(data) {
			$scope.point = data;
			_show($('#loading'));
		});
	}
	$scope.share = function() {
		_show($('#loading'));
		$http({
			method: 'POST',
			url: shareurl,
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
			}
		}).success(function(data) {
			if (data.success) {
				show_toastr('toast-top-right', 'success', '分享完成！', '');
			} else {
				show_toastr('toast-top-right', 'warning', '您已分享過！', '');
			}
			$scope.point = data.point;
			_show($('#loading'));
		});
	}
	$scope.submit_ = function() {
		if (!checkform()) {
			return;
		}
		$.ajax({
			url: setDataurl,
			cache: false,
			type: 'post',
			data: $('#data_form').serialize(),
			dataType: 'json',
			beforeSend: function(html) {
				record('share_record');
				_show($('#loading'));
			},
			error: function(e) {
				//alert("error:"+e.responseText);
			},
			success: function(html) {
				_show($('#data_'));
				show_toastr('toast-top-right', 'success', '恭喜參加抽獎！', '');
			},
			complete: function() {
				_show($('#loading'));
			}
		});
	}
	$scope.powall = function() {
		if (confirm('快告訴大家你的超完美強棒應援團，和他們一起贏得人生中重要的比賽吧！')) {
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
				if (count == 1) {
					_show($('#data_'));
				}
			});
		}
	}
});
app.controller("moreController", function($scope, $http) {
	$scope.articles = articles;
	$scope.back_ = function() {
		location.href = backurl;
	}
});
app.controller("msgController", function($scope, $http) {
	$scope.submit_ = function(o) {
		if ($('#message').val() == '') {
			//bootbox.alert('請輸入留言！');
			show_toastr('toast-top-right', 'error', '請輸入留言！', '');
			return;
		}
		_show($('#loading'));
		$http({
			method: 'POST',
			url: msgurl,
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
			},
			data: "fbid=" + o_fbid + "&tofbid=" + fbid + "&message=" + $('#message').val()
		}).success(function(data) {
			show_toastr('toast-top-right', 'success', '留言成功！', '');
			setTimeout(function() {
				location.href = indexurl;
			}, 3000);
		});
	}
});
var backend = angular.module("myBackend", []).directive('numbersOnly', function() {
	return {
		require: 'ngModel',
		link: function(scope, element, attrs, modelCtrl) {
			modelCtrl.$parsers.push(function(inputValue) {
				// this next if is necessary for when using ng-required on your input. 
				// In such cases, when a letter is typed first, this parser will be called
				// again, and the 2nd time, the value will be undefined
				if (inputValue == undefined) return ''
				var transformedInput = inputValue.replace(/[^0-9]/g, '');
				if (transformedInput != inputValue) {
					modelCtrl.$setViewValue(transformedInput);
					modelCtrl.$render();
				}
				return transformedInput;
			});
		}
	};
});
backend.controller("prizeController", function($scope, $http) {
	$scope.prizes = prizes;
	$scope.point = point;
	$scope.back_ = function() {
		location.href = prizeUrl;
	}
	$scope.create_ = function() {
		location.href = createUrl;
	}
	$scope.edit_ = function(o) {
		location.href = editUrl + '/' + o;
	}
	$scope.submit_ = function(o) {
		if ($('#title').val() == '') {
			//bootbox.alert('請輸入留言！');
			show_toastr('toast-top-left', 'error', '請輸入獎品標題！', '');
			return;
		}
		if ($('#img').val() == '') {
			//bootbox.alert('請輸入留言！');
			show_toastr('toast-top-left', 'error', '請上傳獎品圖片！', '');
			return;
		}
		if ($('#point').val() == '') {
			//bootbox.alert('請輸入留言！');
			show_toastr('toast-top-left', 'error', '請輸入兌換點數！', '');
			return;
		}
		_show($('#loading'));
		$http({
			method: 'POST',
			url: saveUrl,
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
			},
			data: $('#form').serialize()
		}).success(function(data) {
			show_toastr('toast-top-right', 'success', '新增成功！', '');
			setTimeout(function() {
				$scope.back_();
			}, 3000);
		});
	}
	$scope.dragStart = function(e, ui) {
		ui.item.data('start', ui.item.index());
	}
	$scope.dragEnd = function(e, ui) {
		var start = ui.item.data('start'),
			end = ui.item.index();
		$scope.prizes.splice(end, 0, $scope.prizes.splice(start, 1)[0]);
		$scope.$apply();
	}
	sortableEle = $('#sortable').sortable({
		start: $scope.dragStart,
		update: $scope.dragEnd
	});
});
backend.controller("articleController", function($scope, $http) {
	$scope.articles = articles;
	if (article != null) {
		$scope.post_id = article.post_id;
		$scope.title = article.title;
		$scope.start_time = article.start_time;
		$scope.end_time = article.end_time;
	}
	$scope.back_ = function() {
		location.href = articleUrl;
	}
	$scope.create_ = function() {
		location.href = createUrl;
	}
	$scope.submit_ = function(o) {
		if ($('#post_id').val() == '') {
			show_toastr('toast-top-left', 'error', '請輸入文章id！', '');
			return;
		}
		if ($('#title').val() == '') {
			show_toastr('toast-top-left', 'error', '請輸入文章標題！', '');
			return;
		}
		if ($('#start_time').val() == '') {
			show_toastr('toast-top-left', 'error', '請選擇開始日期！', '');
			return;
		}
		if ($('#end_time').val() == '') {
			show_toastr('toast-top-left', 'error', '請選擇結束日期！', '');
			return;
		}
		_show($('#loading'));
		$http({
			method: 'POST',
			url: saveUrl,
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
			},
			data: $('#form').serialize()
		}).success(function(data) {
			show_toastr('toast-top-right', 'success', '新增成功！', '');
			setTimeout(function() {
				$scope.back_();
			}, 3000);
		});
	}
});