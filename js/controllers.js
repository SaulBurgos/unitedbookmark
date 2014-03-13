'use strict';

/* Controllers */

angular.module('myApp.controllers', [])

	.controller('mainCrtl', function($scope,$rootScope,$q,$location,$templateCache,Auth,Bookmarks,Tags,Links) {

      $scope.showLoadingAjax = false;
  		$scope.userServices = Auth;	
  		$scope.bookmarkServices = Bookmarks;	
      $scope.tagsServices = Tags;
  		$scope.linksServices = Links;
  		$scope.userActive = {
  			userName : 'Login/Register',
  		}; 
      $scope.tagsFilters = [];

       //create a event
      $scope.$on('startAjax', function() {
          $scope.showLoadingAjax = true;
      });

       //create a event
      $scope.$on('stopAjax', function() {
          $scope.showLoadingAjax = false;
      });

  		//create a event
		$scope.$on('authLoaded', function() {
			console.log('A session was loaded');
		});

		//create a event
		$scope.$on('authRemoved', function() {
			console.log('A session was destroyed');
			//clear cache used for partial view, but appear a weird error when you change of route
			//$templateCache.removeAll();
		});		

  		$scope.loadSession = function() {
			$scope.userServices.getSession().success(function (respond) {
				if(respond.status == 'ok') {
					$scope.userActive = respond.data;
					//trigger event to notify somthing import has occurred
					$scope.$broadcast('authLoaded');
				}
			}).error(function (data, status) {
				console.log(data);
			});  			
  		};

  		$scope.logOut =  function() {
  			$scope.userServices.logout().success(function (data) {
				if(data.status == 'ok') {
					$scope.userActive = { userName : 'Login/Register' };
					$scope.$broadcast("authRemoved");
				}
			}).error(function (data, status) {e,
				console.log(data);
			}); 
  		};
  		//global validator based on ngModelController used in all form
  		//return the name class used to show error on input 
  		$scope.isValid = function(ngModelContoller) {
			return {
				'has-error': ngModelContoller.$invalid && ngModelContoller.$dirty
			};
		};

  		$scope.isThereSession = function() {
  			if( _.size($scope.userActive) > 1 ) {
  				return true;
  			} else {
  				return false;
  			}
  		};

  		//global event that only happens on $rootScope when some promises are rejected on routerurl
  		$rootScope.$on("$routeChangeError", function(event, current, previous, rejection) {
			console.log(rejection);
    	});
  		$scope.loadSession();
  	})
   /*####################################################################################*/
   .controller('homeBookmark', function($scope) {
      $scope.recentTags = [];
      $scope.bookmarksArray = [];

      $scope.getRecentTags = function() {
         $scope.tagsServices.getRecent().success(function (respond) {
               if(respond.status == 'ok') {
                  $scope.recentTags = respond.data;
               }               
         }).error(function (data, status) {
            console.log('Error getting recent tags'); 
         });
      };

      $scope.getRecentBookmarks = function() {
         $scope.$emit("startAjax");
         $scope.bookmarkServices.getRecent().success(function (respond) {
            if(respond.status == 'ok') {
               $scope.bookmarksArray = respond.data;                  
            } else {
               console.log('Error getting recent bookmarks');       
            }              
            $scope.$emit("stopAjax");
         }).error(function (data, status) {
            console.log('Error getting recent bookmark from server'); 
         });
      };

      $scope.loadMore = function() {
         $scope.$emit("startAjax");
         var lastId = $scope.bookmarksArray[$scope.bookmarksArray.length -1].id;
         $scope.bookmarkServices.getMore(lastId).success(function (respond) {
               if(respond.status == 'ok') {
                     var temp  = $scope.bookmarksArray.concat(respond.data);
                     $scope.bookmarksArray = temp;
               } else {
                  console.log('Error getting more bookmarks');       
               }    
               $scope.$emit("stopAjax");          
         }).error(function (data, status) {
            console.log('Error getting more bookmark from server'); 
         });
      };

      $scope.getRecentBookmarks();
      $scope.getRecentTags();
   })
   /*####################################################################################*/
	.controller('header', function($scope) {
  		//console.log($scope.saul);
  	})
   /*####################################################################################*/
   .controller('searchBookmark', function($scope,$routeParams) {

      /*I dont know whay but with this controller if I definied properties, are isoled from child controller 
      this is weird*/

      $scope.searchByTitle = function() {
         var searchData = {
            term: $routeParams.term
         };
      
         $scope.$emit("startAjax");
         $scope.bookmarkServices.searchByTitle(searchData).success(function (respond) {
            if(respond.status == 'ok') {

               if(respond.data.length > 0) {
                  /*here assign directly to this model without definied in this controller, I dont know why but if I define this
                  property in this scope the ng-repeat create another scope with his own value and when I assign
                  the respond ajax only affect the scope parent, the scope ng.repeat is isolated*/
                  $scope.bookmarksArray = respond.data;
                  //update viewmore data to use later
                  $scope.viewMoreData = {
                     lastId: respond.data[respond.data.length-1].id,
                     term: $routeParams.term,
                     tags: []
                  };
                  $scope.noResultFound = false;
               } else {
                  $scope.noResultFound = true;
               }
                  
            } else {
               console.log('Error searching bookmarks');       
            }    
            $scope.$emit("stopAjax");          
         }).error(function (data, status) {
            console.log('Error searching bookmark from server'); 
         });
      };

      $scope.searchByTitleTags = function() {
         var searchData = {
            term: $routeParams.term,
            tags: $scope.tagsFilters.toString().replace(/,/g , " ") 
         };
      
         $scope.$emit("startAjax");
         $scope.bookmarkServices.searchByTitleTags(searchData).success(function (respond) {            
             if(respond.data.length > 0) {
               /*here assign directly to this model without definied in this controller, I dont know why but if I define this
               property in this scope the ng-repeat create another scope with his own value and when I assign
               the respond ajax only affect the scope parent, the scope ng.repeat is isolated*/
               $scope.bookmarksArray = respond.data;
               //update viewmore data to use later
               $scope.viewMoreData = {
                  lastId: respond.data[respond.data.length-1].id,
                  term: $routeParams.term,
                  tags: $scope.tagsFilters
               };
               $scope.noResultFound = false;
            } else {
                $scope.noResultFound = true;
            }


            $scope.$emit("stopAjax");          
         }).error(function (data, status) {
            console.log('Error searching bookmark from server'); 
         });
      };

      $scope.loadMore = function() {

         //only title
         if($scope.viewMoreData.tags.length < 1) {

               $scope.$emit("startAjax");
               $scope.bookmarkServices.searchByTitleViewMore($scope.viewMoreData).success(function (respond) {
                  if(respond.status == 'ok' && respond.data != '') {
                     $scope.bookmarksArray = $scope.bookmarksArray.concat(respond.data);
                     $scope.viewMoreData = {
                        lastId: respond.data[respond.data.length-1].id,
                        term: $routeParams.term,
                        tags: []
                     };
                  }    
                  $scope.$emit("stopAjax");          
               }).error(function (data, status) {
                  console.log('Error searching bookmark from server'); 
               });
         }

         //title and tags
         if($scope.viewMoreData.tags.length > 0) {

               $scope.viewMoreData.tags = $scope.tagsFilters.toString().replace(/,/g , " ");
               $scope.$emit("startAjax");

               $scope.bookmarkServices.searchByTitleTagsViewMore($scope.viewMoreData).success(function (respond) {
                  if(respond.status == 'ok' && respond.data != '') {
                     $scope.bookmarksArray = $scope.bookmarksArray.concat(respond.data);
                     $scope.viewMoreData = {
                        lastId: respond.data[respond.data.length-1].id,
                        term: $routeParams.term,
                        tags: $scope.tagsFilters
                     };
                  }   
                  $scope.$emit("stopAjax");          
               }).error(function (data, status) {
                  console.log('Error searching bookmark from server'); 
               });
         }

      };
      //until the view has changed
      $scope.$on('$viewContentLoaded', function() {
         
         if($scope.tagsFilters.length == 0) {
            $scope.searchByTitle();
         }

         if($scope.tagsFilters.length > 0) {
            $scope.searchByTitleTags();
         }

      }); 

   })
   /*####################################################################################*/
  	.controller('browserBookmark', function($scope,$q,$location) {   		
      $scope.termToSearch = '';
  		$scope.cancelerSearchTag;

  		$scope.getTags = function(queryTag) {
  			/*Basically, you can use any promise as the timeout parameter. If it is resolved before the request 
  			completes, then the request will be aborted and the $http promise will be rejected.*/
  			if( typeof $scope.cancelerSearchTag !== 'undefined') {
  				$scope.cancelerSearchTag.resolve();
  			}
  			$scope.cancelerSearchTag = $q.defer();
  			return $scope.tagsServices.searchTags(queryTag,$scope.cancelerSearchTag);
  		};

      $scope.searchBookmark = function() {
         if($scope.searchBookmarkForm.$valid) {
            /*the adicional paramerter is only to fire the $viewContentLoaded event on search because if 
            it does not detect a new value in the paramater the view does not change, angular is very clever to know this
            and does not reload a url if the paramter in the url is the same*/
            $location.path("/search/" + $scope.searchBookmarkForm.term.$viewValue)
            .search({q: _.uniqueId() }); 
         }
      };

  	})
   /*####################################################################################*/
  	.controller('detailBookmark', function($scope,$routeParams,$modal,$timeout,$q) {
      
      $scope.bookmarkInfo;
      $scope.modalInstance;
      $scope.cancelerSearchLinkShare;
      $scope.cancelerWatchBookmark;
      $scope.cancelerTimoutSearchLinkShare;
      $scope.modalContent = {};
      $scope.linkDuplicate = {
         showMsg : false,
         bookmarkid : 0
      };
      $scope.ajaxProgress =  false;

      $scope.linkData = {
         link:'',
         linkComment:'',
         bookmarkId: $routeParams.id
      };
      $scope.linkDataInitialValues = angular.copy($scope.linkData);

      $scope.msgStatus = {
         text: '',
         success : false,
         error : false
      };

      $scope.popoverWatch = {
         title: 'Watching',
         content: 'Receive notification when links will be added.',
         alreadyWatch: false
      };

      $scope.modalCommentOptions = {
         templateUrl: 'partials/modals/comment.html',
         windowClass:'modalHack',
         scope: $scope
      };

      $scope.modalReportLinkOptions = {
         templateUrl: 'partials/modals/reportLink.html',
         windowClass:'modalHack',
         scope: $scope
      };

      $scope.modalAddLinkOptions = {
         templateUrl: 'partials/modals/addLinkToBookmark.html',
         windowClass:'modalHack',
         scope: $scope
      };

      $scope.getBoomarkInfo = function() {
         $scope.$emit("startAjax");
         $scope.bookmarkServices.getBookmarkById($routeParams.id).success(function (respond) {
            if(respond.status == 'ok') {
               
               $scope.bookmarkInfo = respond.data; 
               if($scope.bookmarkInfo.alreadyWatch == 'true') {
                  $scope.popoverWatch.content =  'Stop notification when links will be added.';
                  $scope.popoverWatch.alreadyWatch =  true;
               } else {
                  $scope.popoverWatch.content =  'Receive notification when links will be added.';
                  $scope.popoverWatch.alreadyWatch =  false;
               } 

            } 
            $scope.$emit("stopAjax");
         }).error(function (data, status) {
            console.log('Error getting bookmark info.'); 
         });
      };

      $scope.watchBookmark = function() {
         if($scope.isThereSession()) {
            
            if(!$scope.popoverWatch.alreadyWatch) {
               $scope.popoverWatch.content =  'Stop notification when links will be added.';
               $scope.popoverWatch.alreadyWatch =  true;
            } else {
               $scope.popoverWatch.content =  'Receive notification when links will be added.';
               $scope.popoverWatch.alreadyWatch =  false;
            } 

            if( typeof $scope.cancelerWatchBookmark !== 'undefined') {
               $scope.cancelerWatchBookmark.resolve();
            }
            $scope.cancelerWatchBookmark = $q.defer();

            var dataWatch = {
               watching: $scope.popoverWatch.alreadyWatch,
               bookmarkId: $scope.bookmarkInfo.id
            }

            $scope.bookmarkServices.watchBookmark(dataWatch,$scope.cancelerWatchBookmark).success(function (respond) {
               console.log(respond);
               if(respond.status == 'ok') {
                  
               } 
            });           
         }

      };

      $scope.voteLink = function(userVote,linkId,index) {

         if($scope.isThereSession()) {

            if(userVote == 1) {
                //increase vote to simulate responsive
               $scope.bookmarkInfo.links[index].likes = parseInt($scope.bookmarkInfo.links[index].likes) + 1;
            } else {
               //increase vote to simulate responsive
               $scope.bookmarkInfo.links[index].noLikes = parseInt($scope.bookmarkInfo.links[index].noLikes) + 1;
            }
            
            var voteData = {
               vote: userVote,
               linkId: linkId,
               bookmarkId: $scope.bookmarkInfo.id
            }

            $scope.linksServices.setVote(voteData).success(function (respond) {
               if(respond.status == 'ok') {
                  alert('You already voted in this link');
                  if(userVote == 1) {
                     $scope.bookmarkInfo.links[index].likes = parseInt($scope.bookmarkInfo.links[index].likes) - 1;
                  } else {
                     $scope.bookmarkInfo.links[index].noLikes = parseInt($scope.bookmarkInfo.links[index].noLikes) - 1;
                  }
               } 
            });
         } 
      };
      
      $scope.linkInUsed = function(ngModelContoller) {
         if(ngModelContoller.$valid) {
            //cancel previous timer
            $timeout.cancel($scope.cancelerTimoutSearchLinkShare); 
            //set new timer to look for link
            $scope.cancelerTimoutSearchLinkShare = $timeout(function(){
               if( typeof $scope.cancelerSearchLinkShare !== 'undefined') {
                  $scope.cancelerSearchLinkShare.resolve();
               }
               $scope.cancelerSearchLinkShare = $q.defer();
               $scope.linksServices.isInUsed(ngModelContoller.$viewValue,$scope.cancelerSearchLinkShare)
               .success(function (respond) {
                  if(respond.status == 'true'){
                     $scope.linkDuplicate.showMsg = true;
                     $scope.linkDuplicate.bookmarkid = respond.data.bookmarkId
                  }
               }); 
            },2000);
         }
      }

      $scope.addLinkToBookmark = function() {
         if($scope.isThereSession()) {
            //here i am not using validator angular, becasue the form is not inside a controller 
            //and does not have ngFormController object
            if(addlinkToBookmarkForm.checkValidity()) {
               $scope.$emit("startAjax");
               $scope.ajaxProgress =  true;               
               $scope.bookmarkServices.addLink($scope.linkData).success(function (respond) {
                  if(respond.status == 'ok') {
                     $scope.getBoomarkInfo();
                     $scope.closeModal();
                     //reset form
                     $scope.linkData = angular.copy($scope.linkDataInitialValues);
                     $scope.updateMsgStatus('',false,false);
                     $scope.ajaxProgress =  false;
                  } else {
                     $scope.updateMsgStatus(respond.msg,true,false);
                  }
                  $scope.$emit("stopAjax");
               }).error(function (data, status) {
                  $scope.updateMsgStatus('Error adding link, please try again',true,false); 
               });

            } else {
               $scope.updateMsgStatus('Pleae fill of data',true,false);              
            }
         } else {
            $scope.updateMsgStatus('You need to be logged to use this option',true,false); 
         }
      };

      $scope.reportLink = function() {

         if($scope.isThereSession() && $scope.modalContent.commentReportLink != '') {
            var data = {
               comment: $scope.modalContent.commentReportLink,
               bookmarkid: $routeParams.id,
               linkid:  $scope.modalContent.linkidToReport
            };         
            $scope.linksServices.report(data).success(function (respond) {
               $scope.closeModal();
            }).error(function (data, status) {
               console.log('error sending report to server');
            }); 
         }                 
      };

      $scope.updateMsgStatus = function(msg,error,success) {
         $scope.msgStatus.text = msg;
         $scope.msgStatus.error = error;
         $scope.msgStatus.success = success;
      };

      $scope.showAddLinkForm = function() {
         $scope.linkDuplicate.showMsg = false;
         $scope.modalInstance = $modal.open($scope.modalAddLinkOptions);
      };

      $scope.showCommentLink = function(index) {
         $scope.modalContent.title = 'Comment link';
         $scope.modalContent.content =  $scope.bookmarkInfo.links[index].comment;
         $scope.modalInstance = $modal.open($scope.modalCommentOptions);
      }

      $scope.showDescriptionBookmark = function() {
         $scope.modalContent.title = 'Description bookmark';
         $scope.modalContent.content =  $scope.bookmarkInfo.description;
         $scope.modalInstance = $modal.open($scope.modalCommentOptions);
      };

      $scope.showReportLink = function(index) {
         $scope.modalContent.title = 'Report link broken';
         $scope.modalContent.linkidToReport =  $scope.bookmarkInfo.links[index].id;
         $scope.modalInstance = $modal.open($scope.modalReportLinkOptions);
      };

      $scope.closeModal = function() {
         $scope.modalInstance.close();
      };

      //until the view has changed
      $scope.$on('$viewContentLoaded', function() {
         $scope.getBoomarkInfo();
      });    

  	})
   /*####################################################################################*/
   .controller('notifications', function($scope) {

      $scope.recentTags = [];
      $scope.noResult = false;
      $scope.bookmarksNewLinks = [];

      $scope.getRecentTags = function() {
         $scope.tagsServices.getRecent().success(function (respond) {
               if(respond.status == 'ok') {
                  $scope.recentTags = respond.data;
               }               
         }).error(function (data, status) {
            console.log('Error getting recent tags'); 
         });
      };

      $scope.loadNotifications = function() {
         $scope.$emit("startAjax");
         $scope.bookmarkServices.getNotifications().success(function (respond) {
            if(respond.status == 'ok') {
               if(respond.data.length > 0) {  
                  $scope.bookmarksNewLinks = respond.data;
                  $scope.resetNotification(respond.data);
                  $scope.noResult = false;
               } else {
                  $scope.noResult = true;
               }                  
            } else {
               console.log('Error getting notifications');       
            }    
            $scope.$emit("stopAjax");          
         }).error(function (data, status) {
            console.log('Error getting notifications from server'); 
         });

      };

      $scope.resetNotification = function(data) {
         var bookmarkIds = _.pluck(data,'id');
         $scope.bookmarkServices.resetNotifications(bookmarkIds).success(function (respond) {
            if(respond.status == 'ok') {
                $scope.userActive.qtyBookmarkWithNewLinks = '';             
            }              
         });
      };

      //until the view has changed
      $scope.$on('$viewContentLoaded', function() {
         $scope.loadNotifications();
         $scope.getRecentTags();
      });
      
   })
   /*####################################################################################*/
	.controller('dashboard',function($scope,$location,$anchorScroll,$window,$modal,$q) { 
      $scope.cancelerSearchTagShare;
      $scope.modalInstance;
      $scope.modalContent = { 
         title: 'Edit bookmark',
         newTitleBookmark: 'input title',
         newDescription: 'new description',
         newTags : ['newTag'],
         showErrorMsg:false
      };
      $scope.bookmarkSelected = {
         title:'empty',
         id: 0
      };

      $scope.msg = {
         text: '',
         success : false,
         error : false
      };

      $scope.modalEditBookmarkOptions = {
         templateUrl: 'partials/modals/editBookmark.html',
         windowClass:'modalHack',
         scope: $scope
      };

      $scope.bookmarkDataSource = new kendo.data.DataSource({         
         transport: {
            read: {
               url: "php/kendoServices.php",
               dataType: "json",
               data: {
                  action: "getBookmarkUser"
               }
            }
         },
         schema: {
            model: {
                  fields: {
                     id: { type: "number" },
                     title: { type: "string" },
                     created: { type: "string" },
                     links: { type: "number" }
                  }
            }
         },
         pageSize: 15
      });

     $scope.showEditBookmark = function() {
          $scope.modalContent.showErrorMsg = false;
         $scope.modalInstance = $modal.open($scope.modalEditBookmarkOptions);
      };

      $scope.closeModal = function() {
         $scope.modalInstance.close();
      };

      $scope.updateBookmark = function() {

         /*because the problem with the fucking scope of directive*/
         if(angular.element('form[name=editBookmarkForm] input[name=titleBookmark]').val().length < 20) {
            return;
         }

         var data = {
            newTitle:  $scope.modalContent.newTitleBookmark,
            newDescription: $scope.modalContent.newDescription,
            bookmarkId: $scope.bookmarkSelected.id,
            newTags: $scope.modalContent.newTags
         };
         $scope.$emit("startAjax");
         $scope.bookmarkServices.updateBookmark(data).success(function (respond) {
            if(respond.status == 'ok') {
               $scope.closeModal();
               $scope.bookmarkDataSource.read();
            } else {
               $scope.modalContent.showErrorMsg = true;
            }
            $scope.$emit("stopAjax");
         }).error(function (data, status) {
            console.log('Error deleting link');
         });

      };

      $scope.getTags = function(queryTag) {
         /*Basically, you can use any promise as the timeout parameter. If it is resolved before the request 
         completes, then the request will be aborted and the $http promise will be rejected.*/
         if( typeof $scope.cancelerSearchTagShare !== 'undefined') {
            $scope.cancelerSearchTagShare.resolve();
         }
         $scope.cancelerSearchTagShare = $q.defer();
         return $scope.tagsServices.searchTags(queryTag,$scope.cancelerSearchTagShare);
      };

      $scope.loadBookmarks = function() {

         var bookmarkGrid = angular.element("#bookmarkGrid");
         
         if( typeof bookmarkGrid.data("kendoGrid") === 'undefined') {
            bookmarkGrid.kendoGrid({
               columns: [ 
                  { title: "Title", field: "title" },
                  { title: "Links", field: "links",width: 60, attributes: { style: "text-align: center;" } },
                  { title: "Created", field: "created",width: 80} 
               ],
               height: 500,
               selectable: "row",
               sortable: true,
               navigatable: true,
               pageable: true,
               mobile: true,
               filterable: {
                  extra: false
               },
               toolbar: [
                  {
                     template: '<button id="bookmarkGrid-viewLinks" type="button" class="btn btn-default btn-xs">' + 
                     '<span class="glyphicon glyphicon-link"></span> View links</button>'
                  },
                  {
                     template: ' <button id="bookmarkGrid-visitBookmark" type="button" class="btn btn-default btn-xs">' + 
                     '<span class="glyphicon glyphicon-bookmark"></span> Visit bookmark</button>'
                  },
                  {
                     template: ' <button id="bookmarkGrid-editBookmark" type="button" class="btn btn-default btn-xs">' + 
                     '<span class="glyphicon glyphicon-pencil"></span> Edit bookmark</button>'
                  },
                  {
                     template: ' <button id="bookmarkGrid-deleteBookmark" type="button" class="btn btn-default btn-xs">' + 
                     '<span class="glyphicon glyphicon-remove"></span> Delete bookmark</button>'
                  }
               ],
               dataSource:$scope.bookmarkDataSource
            });

            angular.element('#bookmarkGrid-viewLinks').on('click',function(){
               if(bookmarkGrid.data("kendoGrid").select().length > 0 ) {
                  var itemSelected = bookmarkGrid.data("kendoGrid").select();
                  $scope.bookmarkSelected.id = bookmarkGrid.data("kendoGrid").dataItem(itemSelected).id;
                  $scope.bookmarkSelected.title = bookmarkGrid.data("kendoGrid").dataItem(itemSelected).title;
                  $scope.loadLinks();
               } else {
                  alert('Please select one item');
               }
            });

            angular.element('#bookmarkGrid-visitBookmark').on('click',function(){
               if(bookmarkGrid.data("kendoGrid").select().length > 0 ) {
                  var itemSelected = bookmarkGrid.data("kendoGrid").select();
                  var bookmarkId = bookmarkGrid.data("kendoGrid").dataItem(itemSelected).id;                  
                  var path = $location.path();
                  var url = $location.absUrl();
                  var start = url.indexOf(path);
                  var newUrl = url.slice(0,start);
                  window.open(newUrl + 'detail?id=' + bookmarkId ,'_blank');
                  /*$apply() is used to execute an expression in angular from outside of the angular framework. 
                  that is why this event is not executing inside angular framework*/
                  //$location.path("/detail/" + bookmarkGrid.data("kendoGrid").dataItem(itemSelected).id);
                  //$scope.$apply(); 
               } else {
                  alert('Please select one item');
               }
            });

            angular.element('#bookmarkGrid-editBookmark').on('click',function(){
               if(bookmarkGrid.data("kendoGrid").select().length > 0 ) {
                  var itemSelected = bookmarkGrid.data("kendoGrid").select();
                  $scope.bookmarkSelected.id = bookmarkGrid.data("kendoGrid").dataItem(itemSelected).id;
                  $scope.bookmarkSelected.title = bookmarkGrid.data("kendoGrid").dataItem(itemSelected).title;
                  $scope.modalContent.newTitleBookmark = bookmarkGrid.data("kendoGrid").dataItem(itemSelected).title;
                  $scope.modalContent.newDescription = bookmarkGrid.data("kendoGrid").dataItem(itemSelected).description;
                  $scope.modalContent.newTags = bookmarkGrid.data("kendoGrid").dataItem(itemSelected).tags.split(",");
                  $scope.showEditBookmark();
               } else {
                  alert('Please select one item');
               }
            });

            angular.element('#bookmarkGrid-deleteBookmark').on('click',function(){
               if(bookmarkGrid.data("kendoGrid").select().length > 0 ) {
                  var itemSelected = bookmarkGrid.data("kendoGrid").select();
                  $scope.deleteBookmark(bookmarkGrid.data("kendoGrid").dataItem(itemSelected).id);
                  bookmarkGrid.data("kendoGrid").removeRow(itemSelected);
               } else {
                  alert('Please select one item');
               }
            });

         }
      };

      $scope.loadLinks = function() {
         /*sorry here I used this crapy method to assign the title :( , the mother fucker
         directives or angular I dont know who , they are creating other scope child and 
         dont let me using it*/
         angular.element('#titleBookmark').html('<span class="glyphicon glyphicon-bookmark"></span> ' +
          $scope.bookmarkSelected.title);
         $location.hash('titleBookmark');//to scroll down the page
         $anchorScroll();

         var linkGrid = angular.element("#linkGrid");
         linkGrid.kendoGrid({
            columns: [ 
               { title: "Title link", field: "title" },
               { title: "User", field: "name",width: 100},
               { title: "Created", field: "created",width: 80} 
            ],
            height: 350,
            selectable: "row",
            sortable: true,
            navigatable: true,
            pageable: true,
            mobile: true,
            filterable: {
               extra: false
            },
            toolbar: [
               {
                  template: '<button id="linkGrid-removeLink" type="button" class="btn btn-default btn-xs">' + 
                  '<span class="glyphicon glyphicon-remove"></span> Remove</button>'
               },
               {
                  template: ' <button id="linkGrid-visitLink" type="button" class="btn btn-default btn-xs">' + 
                  '<span class="glyphicon glyphicon-arrow-right"></span>Visit link</button>'
               }
            ],
            dataSource: {
               transport: {
                  read: {
                     url: "php/kendoServices.php",
                     dataType: "json",
                     data: {
                        action: "getLinksByBookmark",
                        bookmarkId : $scope.bookmarkSelected.id
                     }
                  }
               },
               schema: {
                  model: {
                        fields: {
                           id: { type: "number" },
                           title: { type: "string" },
                           name: { type: "string" },
                           created: { type: "string" }
                        }
                  }
               },
               pageSize: 15
            }
         });
         //remove previous event because it was triggered several times
         angular.element('#linkGrid-visitLink').off('click');
         angular.element('#linkGrid-visitLink').on('click',function(){
            if(linkGrid.data("kendoGrid").select().length > 0 ) {
                  var itemSelected = linkGrid.data("kendoGrid").select();
                  window.open(linkGrid.data("kendoGrid").dataItem(itemSelected).link,'_blank');
            } else {
               alert('Please select one item');
            }
         });

         //remove previous event because it was triggered several times
         angular.element('#linkGrid-removeLink').off('click');
         angular.element('#linkGrid-removeLink').on('click',function(){
            if(linkGrid.data("kendoGrid").select().length > 0 ) {
               var itemSelected = linkGrid.data("kendoGrid").select();
               var linkId = linkGrid.data("kendoGrid").dataItem(itemSelected).linkid;
               linkGrid.data("kendoGrid").removeRow(itemSelected);                  
               $scope.deleteLink(linkId);
            } else {
               alert('Please select one item');
            }
         });

      };

      $scope.deleteBookmark = function(bookmarkid) {
         var data = {
            'bookmarkId': bookmarkid
         };
         $scope.bookmarkServices.deleteBookmark(data).success(function (respond) {
            if(respond.status == 'error'){
               $scope.updateMsgStatus(respond.msg,true,false);
            }
         }).error(function (data, status) {
            console.log('Error deleting bookmark');
         }); 
      };

      $scope.deleteLink = function(linkId){
         var data = {
            'linkId': linkId,
            'bookmarkId': $scope.bookmarkSelected.id
         };
         $scope.bookmarkServices.deleteLink(data).success(function (respond) {
            console.log(respond);
         }).error(function (data, status) {
            console.log('Error deleting link');
         });   
      };

      $scope.updateMsgStatus = function(msg,error,success) {
         $scope.msg.text = msg;
         $scope.msg.error = error;
         $scope.msg.success = success;
      };

      //until the view has changed
      $scope.$on('$viewContentLoaded', function() {
         $scope.loadBookmarks();
      });

  	})
   /*####################################################################################*/
  	.controller('shareBookmark',function($scope,$q,$timeout) {
      $scope.cancelerSearchTagShare;  
  		$scope.cancelerSearchLinkShare;
      $scope.cancelerTimoutSearchLinkShare;  
      $scope.bookmarkSaved = false;	
      $scope.ajaxProgress = false;
      $scope.linkDuplicate = {
         showMsg : false,
         bookmarkid : 0
      };
  		$scope.msg = {
  			text: '',
  			success : false,
  			error : false
  		};

  		$scope.bookmark = {
  			title: '',
  			description: '',
  			tags: [],
         link: '',
         linkComment : '',
         userId : $scope.userActive.userId
  		};
      $scope.bookmarkInitialValues = angular.copy($scope.bookmark);

  		$scope.saveBookmark = function() {

         if($scope.bookmark.tags.length == 0) {
            $scope.updateMsgStatus('Please fill all your data correctly',true,false);
            return;
         } 
  			//if form is valid and was modified by user
  			if($scope.shareBookmarkForm.$valid && $scope.shareBookmarkForm.$dirty) {
            $scope.$emit("startAjax");
            $scope.ajaxProgress = true;
  				$scope.bookmarkServices.save($scope.bookmark).success(function (respond) {
					if(respond.status == 'ok') {
                  $scope.bookmarkSaved = true;
						$scope.updateMsgStatus(respond.msg,false,true);
					} else {
						$scope.updateMsgStatus(respond.msg,true,false);
					}
               $scope.$emit("stopAjax");
				}).error(function (data, status) {
					$scope.updateMsgStatus('Error saving bookmark, please try again',true,false);	
				});
  			} else {
  				$scope.updateMsgStatus('Please fill all your data correctly',true,false);
  			}
  		};

      $scope.resetForm = function() {
         $scope.shareBookmarkForm.$setDirty();
         $scope.shareBookmarkForm.$setPristine();
         $scope.bookmark = angular.copy($scope.bookmarkInitialValues);
         $scope.bookmarkSaved = false;
         $scope.ajaxProgress = false;
         $scope.linkDuplicate.showMsg = false;
         $scope.updateMsgStatus('',false,false);
      };

  		$scope.updateMsgStatus = function(msg,error,success) {
  			$scope.msg.text = msg;
  			$scope.msg.error = error;
  			$scope.msg.success = success;
  		};

  		$scope.getTags = function(queryTag) {
  			/*Basically, you can use any promise as the timeout parameter. If it is resolved before the request 
  			completes, then the request will be aborted and the $http promise will be rejected.*/
  			if( typeof $scope.cancelerSearchTagShare !== 'undefined') {
  				$scope.cancelerSearchTagShare.resolve();
  			}
  			$scope.cancelerSearchTagShare = $q.defer();
  			return $scope.tagsServices.searchTags(queryTag,$scope.cancelerSearchTagShare);
  		};

      $scope.linkInUsed = function(ngModelContoller) {
         if(ngModelContoller.$valid) {
            //cancel previous timer
            $timeout.cancel($scope.cancelerTimoutSearchLinkShare); 
            //set new timer to look for link
            $scope.cancelerTimoutSearchLinkShare = $timeout(function(){
               if( typeof $scope.cancelerSearchLinkShare !== 'undefined') {
                  $scope.cancelerSearchLinkShare.resolve();
               }
               $scope.cancelerSearchLinkShare = $q.defer();
               $scope.linksServices.isInUsed(ngModelContoller.$viewValue,$scope.cancelerSearchLinkShare)
               .success(function (respond) {
                  if(respond.status == 'true'){
                     $scope.linkDuplicate.showMsg = true;
                     $scope.linkDuplicate.bookmarkid = respond.data.bookmarkId
                  }
               }); 
            },2000);
         }
      }

  	})
   /*####################################################################################*/
  	.controller('registerForm',function($scope,$location){
  		
  		$scope.msg = {
  			text: '',
  			success : false,
  			error : false
  		};
  		$scope.userData = {
  			name : '',
  			email: '',
  			pass1: '',
  			pass2 : ''
  		};
  		$scope.loginData = {
  			email:'',
  			pass:''
  		};

  		$scope.registerUser = function () {

         /*if($scope.registerForm.pass1.$viewValue != $scope.registerForm.pass2.$viewValue ) {*/
  			if(angular.element('#registerForm input[name=pass1]').val() != angular.element('#registerForm input[name=pass2]').val() ) {
  				$scope.updateMsgStatus('Passwords must be the same.',true,false);
  				return;
  			}
  			//if form is valid and was modified by user
         /*bootstrap ui 0.9 tab create a isolated scope, so we dont have access to the ngformcontroller in this scope
         check on future version on bootstrap ui if this is fixed*/
  			/*if($scope.registerForm.$valid && $scope.registerForm.$dirty) {*/
         if(document.getElementById('registerForm').checkValidity()) {
            $scope.$emit("startAjax");
  				$scope.userServices.register($scope.userData).success(function (respond) {
					if(respond.status == 'ok') {
						$scope.updateMsgStatus('',false,false);
						$scope.loadSession();
						$location.path("/home");
					} else {
						$scope.updateMsgStatus(respond.msg,true,false);
					}
               $scope.$emit("stopAjax");
				}).error(function (data, status) {
					$scope.updateMsgStatus('Error trying to register you, please try again',true,false);	
				});

  			} else {
  				$scope.updateMsgStatus('Please fill all your data correctly',true,false);
  			}
  		};

  		$scope.loginUser = function() {
  			//if form is valid and was modified by user
         /*bootstrap ui 0.9 tab create a isolated scope, so we dont have access to the ngformcontroller in this scope
         check on future version of bootstrap ui if this is fixed*/
         /*if($scope.loginForm.$valid && $scope.loginForm.$dirty) {*/ 
  			if(document.getElementById('loginForm').checkValidity()) {
            $scope.$emit("startAjax");
  				$scope.userServices.login($scope.loginData).success(function (respond) {
					if(respond.status == 'ok') {
						$scope.updateMsgStatus('',false,false);
						$scope.loadSession();
						$location.path("/home");
					} else {
						$scope.updateMsgStatus(respond.msg,true,false);
					}
               $scope.$emit("stopAjax");
				}).error(function (data, status) {
					$scope.updateMsgStatus('Error trying to login you, please try again',true,false);	
				});
  			} else {
  				$scope.updateMsgStatus('Please fill all your data correctly',true,false);
  			}
  		};

  		$scope.updateMsgStatus = function(msg,error,success) {
  			$scope.msg.text = msg;
  			$scope.msg.error = error;
  			$scope.msg.success = success;
  		};
  		
		//return the name class used to show error on input 
  		$scope.isSamePass = function(pass1,pass2 ) {
			return {
				'has-error': pass1.$viewValue != pass2.$viewValue 
			};
		};

  	});