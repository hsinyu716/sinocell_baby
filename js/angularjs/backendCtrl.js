
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