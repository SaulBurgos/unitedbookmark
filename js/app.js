'use strict';


// Declare app level module which depends on filters, and services
angular.module('myApp', [
   'ngRoute',
   'myApp.filters',
   'myApp.services',
   'myApp.directives',
   'myApp.controllers',
   'ui.bootstrap',
   'ngAnimate',
   'ngTagsInput'
   /*'kendo.directives',*/
]).
config(['$routeProvider','$locationProvider', function($routeProvider,$locationProvider) {

   //$locationProvider.html5Mode(true);
   $locationProvider.hashPrefix('!');
   
   $routeProvider.when('/home', { templateUrl: 'partials/home.html', controller: 'homeBookmark'});
   $routeProvider.when('/detail', { templateUrl: 'partials/detail.html', controller: 'detailBookmark'});
   $routeProvider.when('/search/:term', { templateUrl: 'partials/search.html', controller: 'searchBookmark'});
  
   $routeProvider.when('/dashboard', { 
      templateUrl: 'partials/dashboard.html',
      controller: 'dashboard',
      resolve: {
            //function that is execute before change url, if a promise is rejected, the redirection is cancel
            checkSession: function (Auth,$q) {
               return   Auth.getSession().then(function (response) {
                           var defer =  $q.defer();
                           if (response.data.status != 'ok') {
                              alert('Your must be logged to see this content, Please login again');
                              return $q.reject('There is not session active.');

                           }
                           return response.data
                        }, function (respond) {
                           var defer =  $q.defer();
                           return $q.reject('Something has happened in server meanwhile, I was getting session.');
                        });
            },
            //load kendo script first because has 1mb of size
            loadKendo: function($q) {
               var defer =  $q.defer();   
               // use global document since Angular's $document is weak
               var script = document.createElement('script'); 

               var loading = '<div id="loadingDashboard" class="loadingAjax">' +
                        '<span class="ouro ouro3">' +
                           '<span class="left">' +
                              '<span class="anim"></span>' +
                           '</span>' +
                           '<span class="right">' + 
                              '<span class="anim"></span>' +
                           '</span>' +
                        '</span>' +
                     '</div>';

               
               document.body.appendChild(script); 
               document.body.appendChild(angular.element(loading)[0]);

               angular.element(script).load(function() {
                  angular.element('#loadingDashboard').remove();
                  defer.resolve();
               }).attr('src','http://cdn.kendostatic.com/2013.2.716/js/kendo.all.min.js');        

               return defer.promise;
            }
      }
      
   });

   $routeProvider.when('/notifications', { 
      templateUrl: 'partials/notifications.html',
      controller:'notifications',
      resolve: {
            //function that is execute before change url, if a promise is rejected, the redirection is cancel
            checkSession: function (Auth,$q) {
               return   Auth.getSession().then(function (response) {
                           var defer =  $q.defer();
                           if (response.data.status != 'ok') {
                              alert('Your must be logged to see this content, Please login again');
                              return $q.reject('There is not session active.');

                           }
                           return response.data
                        }, function (respond) {
                           var defer =  $q.defer();
                           return $q.reject('Something has happened in server meanwhile, I was getting session.');
                        });
            }
      }
   });

   $routeProvider.when('/share', { templateUrl: 'partials/share.html',controller:'shareBookmark'});
   $routeProvider.when('/register', { templateUrl: 'partials/register.html',controller:'registerForm'});
   $routeProvider.when('/faq', { templateUrl: 'partials/faq.html',controller:'homeBookmark'});
   $routeProvider.otherwise({redirectTo: '/home'});

}]).run(function($rootScope) {//we can execute any post-instantiation logic
    
  
});
