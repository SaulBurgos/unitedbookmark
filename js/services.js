'use strict';

/* Services */


// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('myApp.services', [])
.factory('Auth', function($http,$q){
	return {
		register: function(userData) {
			var user = {
				action : 'register',
				parameters: userData
			};
			return $http.post('php/bookmark.php',user);
		},
		login: function(userData) {
			var user = {
				action : 'login',
				parameters: userData
			};
			return $http.post('php/bookmark.php',user);
		},
		logout: function() {
			var user = {
				action : 'logout',
				parameters: {}
			};
			return $http.post('php/bookmark.php',user);
		},
		//load data user registered
		getSession: function() {
			var user = {
				action : 'getSession',
				parameters: {}
			};
			return $http.post('php/bookmark.php',user);
		}
	}
}).factory('Bookmarks', function($http,$q){
	return {
		save: function(bookMarkData) {
			var bookmark = {
				action : 'saveBookmark',
				parameters: bookMarkData
			};
			return $http.post('php/bookmark.php',bookmark);
		},
		getRecent: function() {
			var bookmark = {
				action : 'getRecentBookmarks',
				parameters: { 'empty' : ''}
			};
			return $http.post('php/bookmark.php',bookmark,{timeout: 10000});
		},
		getMore: function(lastId) {
			var bookmark = {
				action : 'getMoreBookmarks',
				parameters: { 'id' : lastId}
			};
			return $http.post('php/bookmark.php',bookmark,{timeout: 10000});
		},
		getBookmarkById: function(id) {
			var bookmark = {
				action : 'getBookmarkById',
				parameters: { 'id' : id}
			};
			return $http.post('php/bookmark.php',bookmark,{timeout: 10000});
		},
		addLink: function(data) {
			var bookmark = {
				action : 'addLinkTobookmark',
				parameters: data
			};
			return $http.post('php/bookmark.php',bookmark);
		},
		searchByTitle: function(data) {
			var bookmark = {
				action : 'searchBookmarkByTitle',
				parameters: data
			};
			return $http.post('php/bookmark.php',bookmark);
		},
		searchByTitleViewMore: function(data) {
			var bookmark = {
				action : 'searchBookmarkByTitleViewMore',
				parameters: data
			};
			return $http.post('php/bookmark.php',bookmark);
		},
		searchByTitleTags: function(data) {
			var bookmark = {
				action : 'searchBookmarkByTitleTags',
				parameters: data
			};
			return $http.post('php/bookmark.php',bookmark);
		},
		searchByTitleTagsViewMore: function(data) {
			var bookmark = {
				action : 'searchBookmarkByTitleTagsViewMore',
				parameters: data
			};
			return $http.post('php/bookmark.php',bookmark);
		},
		deleteLink: function(data) {
			var bookmarkData = {
				action : 'deleteLinkFromBookmark',
				parameters: data
			};
			return $http.post('php/bookmark.php',bookmarkData);
		},
		updateBookmark: function(data) {
			var bookmarkData = {
				action : 'updateBookmark',
				parameters: data
			};
			return $http.post('php/bookmark.php',bookmarkData);
		},
		getNotifications: function(data) {
			var bookmarkData = {
				action : 'getNotifications',
				parameters: { 'empty' : ''}
			};
			return $http.post('php/bookmark.php',bookmarkData);
		},
		resetNotifications: function(data) {
			var bookmarkData = {
				action : 'resetNotifications',
				parameters: { 'bookmarkIds': data}
			};
			return $http.post('php/bookmark.php',bookmarkData);
		},
		deleteBookmark: function(data) {
			var bookmarkData = {
				action : 'deleteBookmark',
				parameters: data
			};
			return $http.post('php/bookmark.php',bookmarkData);
		},
		watchBookmark: function(data,promiseCanceler) {
			var bookmarkData = {
				action : 'watchBookmark',
				parameters: data
			};
			return $http.post('php/bookmark.php',bookmarkData,{timeout: promiseCanceler});
		}
	}
}).factory('Tags', function($http,$q){
	return {
		searchTags: function(termToSearch,promiseCanceler) {
			var tag = {
				action : 'searchTags',
				parameters: { 'term' : termToSearch}
			};
			return $http.post('php/bookmark.php',tag,{timeout: promiseCanceler});
		},
		getRecent: function() {
			var tag = {
				action : 'getRecentTags',
				parameters: { 'empty' : ''}
			};
			return $http.post('php/bookmark.php',tag,{timeout: 10000});
		}
	}
}).factory('Links', function($http,$q){
	return {
		isInUsed: function(url,promiseCanceler) {
			var link = {
				action : 'checkLinkInUse',
				parameters: { 'link' : url}
			};
			return $http.post('php/bookmark.php',link,{timeout: promiseCanceler});
		},
		setVote: function(voteData) {
			var link = {
				action : 'voteLink',
				parameters: voteData
			};
			return $http.post('php/bookmark.php',link);
		},
		report: function(data) {
			var link = {
				action : 'reportLink',
				parameters: data
			};
			return $http.post('php/bookmark.php',link);
		}
	}
}).value('version', '0.2');
