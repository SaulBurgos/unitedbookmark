<!DOCTYPE html>
<html lang="en">
<head>
   <meta name="fragment" content="!" />
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>United Bookmarks - share your bookmarks with the people</title>
   <meta name="description" content="Save your bookmarks in one place and share them with the world,
   You can share valuable links that you consider important with others users and help to keep the knowledge accessible">
   <meta name="keywords" content="bookmark,links,share bookmark">
</head>
<body>

   <div class="container" ng-controller="mainCrtl">
      <ajax-loading></ajax-loading>
      <header id="header" class=""  ng-controller="header">

        <div class="row">

          <div class="headerLogo col-xs-12 visible-xs noPaddingBootstrap" style="margin-bottom:15px;">
            <a href="http://unitedbookmark.com/#!/home" class="">
              <h3>UNITED BOOKMARK</h3>
            </a>
          </div>

          <div class="headerLogin col-md-4 col-sm-4 col-xs-4 noPaddingBootstrap">
              <div class="dropdown ">
                <span class="glyphicon glyphicon-user"></span>
                <a class="dropdown-toggle">
                  <span ng-bind="userActive.userName"></span>
                  <span class="badge" ng-bind="userActive.qtyBookmarkWithNewLinks"></span>
                  <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                  <li ng-if="!isThereSession()">
                     <a href="#!/register">Register/login</a>                
                  </li>
                  <li ng-if="isThereSession()">
                     <a href="#!/dashboard">Dashboard</a>                
                  </li>
                  <li ng-if="isThereSession()">
                     <a href="#!/notifications">
                        Notifications 
                        <span class="badge" ng-bind="userActive.qtyBookmarkWithNewLinks"></span>
                     </a>                
                  </li>
                  <li ng-if="isThereSession()">
                     <a href="#!" ng-click="logOut()">Log out</a>                
                  </li>
                </ul>
              </div>              
          </div>

          <div class="headerLogo col-md-4 col-sm-4 col-xs-12 hidden-xs noPaddingBootstrap">
            <a href="#!/home">
              <h3>UNITED BOOKMARK</h3>
            </a>
          </div>

          <div class="headerShare col-md-4 col-sm-4 col-xs-8 noPaddingBootstrap">
            <a class="btn btn-default btn-success" href="#!/share" role="button">
              Share a Bookmark!
            </a>
          </div>

        </div>
    </header>

   <div id="searchContainer" class="row"  ng-controller="browserBookmark">
      <div class="col-md-8 col-sm-8 col-xs-12 center-block ">
         <form role="form" name="searchBookmarkForm" ng-submit="searchBookmark()">
            <div class="input-group searchBox">
               <input type="text" class="form-control" name="term" ng-model="termToSearch" 
               placeholder="Write a title of bookmark" required>
               <span class="input-group-btn">
                 <button class="btn btn-default" type="submit">
                   <span class="glyphicon glyphicon-search"></span> 
                 </button>
               </span>
            </div>
            <tags-input ng-model="tagsFilters"  placeholder="Filter by tags" add-On-Space="true" 
            custom-class="tagsContainer">
               <auto-complete source="getTags($query)" debounce-Delay="500"></auto-complete>
            </tags-input>
         </form>
      </div>
   </div>
        
   <div ng-view>

    
      <div id="shareBookmarkContainer" class="row">
        
         <div  class="col-md-9 col-sm-9">
            <form role="form" name="shareBookmarkForm" ng-submit="saveBookmark()" ng-hide="bookmarkSaved">
               <div class="panel panel-primary">
                  <div class="panel-heading">
                      <span class="glyphicon glyphicon-bookmark"></span>
                      Your bookmark
                   </div>
                  <div class="panel-body">
                     
                     <div class="form-group" ng-class="isValid(shareBookmarkForm.titleBookmark)">
                        <label>Descriptive title at least 20 characters</label>
                        <input type="text" name="titleBookmark" class="form-control" 
                        placeholder="Title bookmark" ng-model="bookmark.title" required ng-minlength="20">
                     </div>

                     <div class="form-group">
                        <label>Some description (300 max) optional</label>
                        <textarea class="form-control" rows="3" name="descriptionBookmark" 
                         placeholder="Description" ng-maxlength="300" ng-model="bookmark.description" 
                         check-Limit-Characters onpaste="return false;" ></textarea>
                     </div>

                     <div class="form-group">
                        <label>Add Tags (categories of your bookmark)</label>
                        <tags-input ng-model="bookmark.tags"  placeholder="tags separated by commas" 
                         add-On-Space="true" max-Length="25">
                            <auto-complete source="getTags($query)" debounce-Delay="500"></auto-complete>
                        </tags-input>
                     </div>

                  </div>
               </div>

               <div class="panel panel-primary">
                   <div class="panel-heading">
                      <span class="glyphicon glyphicon-link"></span>
                      Add a link
                   </div>
                   <div class="panel-body">
                      <div class="form-group" ng-class="isValid(shareBookmarkForm.link)">
                         <label>Link for this bookmark - http://,https://, etc.</label>
                         <input type="url" class="form-control" ng-model="bookmark.link" name="link" 
                         placeholder="Write a link http://" ng-keyup="linkInUsed(shareBookmarkForm.link)" required>

                        <div class="alert alert-info" style="padding:4px;" ng-show="linkDuplicate.showMsg">
                                 <small>
                                    This link was used in another bookmark on this 
                                    <a ng-href="#/detail/{{linkDuplicate.bookmarkid}}" target="_blank">
                                       <b>link</b>
                                    </a>, but dont worry you can reuse it. ( ´ ▽ ` )ﾉ
                                 </small>
                              </div>
                              
                      </div>
                      <div class="form-group">
                           <label>Comment about this link (300 max) optional</label>
                           <textarea class="form-control" rows="3" ng-model="bookmark.linkComment" 
                          ng-maxlength="300" name="linkComment" placeholder="comment" check-Limit-Characters 
                           onpaste="return false;" ></textarea>
                      </div>
                   </div>
                </div>

               <div class="row text-center">
                  <div ng-if="!isThereSession()" class="alert alert-info">You must be logged to share a bookmark.</div>
                  <button ng-if="isThereSession()" ng-disabled="ajaxProgress" ng-hide="bookmarkSaved" type="submit" class="btn btn-success">
                     <span class="glyphicon glyphicon-cloud-upload"></span>
                     Share Bookmark
                  </button>
               </div>
            </form>

            <div class="row text-center">
               <div ng-class="{'alert alert-danger' : msg.error , 'alert alert-success' : msg.success }">
                  {{ msg.text }}
                  <button type="button" class="btn btn-default btn-sm" ng-show="bookmarkSaved" ng-click="resetForm()">
                     <span class="glyphicon glyphicon-plus-sign"></span>
                     Add another bookmark
                  </button>
               </div>
            </div>      

         </div>

        <div class="col-md-3 col-sm-3 hidden-xs noPaddingBootstrap">
            <div class="panel panel-default">
              <div class="panel-heading text-center">
                   <span class="glyphicon glyphicon-info-sign"></span>
                  Tips
               </div>
              <div class="panel-body" style="text-align:justify;">
                  <p>
                     <b>Bookmark title:</b> Try to write a title very descriptive, at least more than 2 words. The title should 
                     encourage others users to add links to your bookmark. Ex: if you have a title like "recipe of my grandmother to prepare a sandwich", that title doesn't allow others users to add links because you are being specific of your grandmother's recipe. Instead you can write a title like "recipes of tasty sandwiches", that title allow others 
                     user to add links about others recipes to your bookmark.

                  </p>
                  <p>
                     <b>Description:</b>
                     You can add a description trying to explain what is the reason of your bookmark or tell others users what links you want to be added.
                  </p>
                  <p>
                     <b>Tags:</b>
                     This helps to describe your bookmark and allows it to be found again by searching. Is very important that you assign tags according to bookmark's topic. <a href="http://en.wikipedia.org/wiki/Tag_(metadata)" target="_blank">Tags</a> allow us categorize your bookmark using simple keywords and allows visitors to choose these tags to filter bookmarks when they are doing a search.
                  </p>
                  <p>
                     <b>Link:</b>
                     Link should begin with: http://, https://, etc. You can't add a link like this: "www.google.com","google.com"
                  </p>
                  <p>
                     <b>Comment link:</b>
                     Any comment that you have about this link.
                  </p>
              </div>
            </div> 
        </div>
      </div>
    
   </div>

    <footer class="panel">
      <div class="row text-center">

        <ul class="list-inline">
          <li>
            © 2014 Saul Burgos
          </li>
          <li>
            <a href="http://unitedbookmark.com/#!/faq">
              Frequently Asked Questions
            </a>
          </li>
        </ul>
      </div>
    </footer>    
  </div>  


</body>
</html>
