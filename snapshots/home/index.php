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

   <?php
      require_once('../../php/db_connect.php');
      require_once('../../php/utilities.php');
      /*require_once('underscore.php');*/

      @ $mysqli = conect_db();
      @ session_start();

      $respond = array();

      if (mysqli_connect_errno()) {
         $respond['status'] = 'error';
         $respond['msg'] = 'imposible conect to DB';
         echo json_encode($respond);   
      }

      require_once('../../php/classes/bookmarks.php');
      require_once('../../php/classes/tags.php');         
      
      $tag = new Tags($mysqli);
      $bookmark = new Bookmarks($mysqli);
      $infoBookmark = $bookmark->getRecent(40);         

      if($infoBookmark['status'] == 'ok') {
         foreach ($infoBookmark['data'] as $key => $value) {               
            $bookmarkTag = $tag->getTagsByBookmarkId($infoBookmark['data'][$key]['id']);
            if($bookmarkTag['status'] == 'ok'){
               $infoBookmark['data'][$key]['tags'] = $bookmarkTag['data'];
            } else {
               $infoBookmark['data'][$key]['tags'] = array();
            }
         }
      }

      foreach ($infoBookmark['data'] as $key => $value) {      

            echo '<div class="bookmarkItem row ">
               <div class="col-sm-10 noPaddingBootstrap">
                   <div>
                     <a href="http://unitedbookmark.com/#!/detail?id='.$infoBookmark['data'][$key]['id'].'" 
                     class="titleBookMark">';
                        echo $infoBookmark['data'][$key]['title'];
            echo     '</a>
                   </div>
                   <div class="row tagsContainer">
                     <span class="bookmarkTag label label-info">
                        '.$infoBookmark['data'][$key]['tags'][0]['name'].'
                     </span>
                   </div>
                   <div class="row">
                     <small>
                         <em>';
            echo            'By '.$infoBookmark['data'][$key]['name'];
            echo            '<span>'.$infoBookmark['data'][$key]['created'].'</span>';
            echo         '</em>
                     </small>
                   </div>
               </div>
               <div class="col-sm-2 indicatorsBookmark noPaddingBootstrap">
                  <span class="label label-default">Links: '.$infoBookmark['data'][$key]['linksCount'].'</span>
               </div>
            </div>';

      }
      
      $tags = $tag->getRecent();

      echo '<ul>';
      foreach ($tags['data'] as $key => $value) {
         echo '<li>
                  <span>
                     '.$tags['data'][$key]['name'].'
                  </span>
               </li>';
      }
      echo '</ul>';

   ?>
    
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
