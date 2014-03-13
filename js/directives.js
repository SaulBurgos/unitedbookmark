'use strict';

/* Directives */


angular.module('myApp.directives', [])
  
  .directive("ajaxLoading", function() { 
      return {
         restrict: 'E',
         template: '<div class="loadingAjax" ng-show="showLoadingAjax">' +
                        '<span class="ouro ouro3">' +
                           '<span class="left">' +
                              '<span class="anim"></span>' +
                           '</span>' +
                           '<span class="right">' + 
                              '<span class="anim"></span>' +
                           '</span>' +
                        '</span>' +
                     '</div>',
         replace: true
      };
   }).directive("checkLimitCharacters", function() { 
      return {
         restrict: 'A',
         link: function(scope, element, attrs) {
            element.on('keyup',function(){
                  var text = angular.element(this).val();
                  var limit = angular.element(this).attr('ng-maxlength');
                  if(text.length > limit) {
                     angular.element(this).val(text.substring(0,299));
                  }
            });
         }
      };
   }).directive("setColorLinkGrid", function() { //not in use for now
      return {
         restrict: 'A',
         link: function(scope, element, attrs) {
            element.find('.faviconLink').load(function() {
               var color = null;
               var colorThief = new ColorThief();
               try {
                  color = colorThief.getColor(angular.element(this).get(0));
               } catch(e) {};
               
               if (color !== null) {
                  element.css('border-top','14px solid ' + 'rgb(' + color.join(',') + ')');
               } else {
                  element.css('border-left','14px solid rgb(252, 172, 172)');
               }
            });
         }
      };
   });
