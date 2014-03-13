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
      require_once('../../php/classes/links.php');

      $tag = new Tags($mysqli);
      $bookmark = new Bookmarks($mysqli);
      $link = new Links($mysqli);   

      $objData = new StdClass();
      $objData->id = $_GET['id'];

      $bookmarkInfo = $bookmark->getById($objData);

      if($bookmarkInfo['status'] == 'ok') {
         
         $bookmarkLinksInfo = $bookmark->getLinks($objData);

         foreach ($bookmarkLinksInfo['data'] as $key => $value) {
            //get like by linkId and bookmarkId
            $likesLink = $link->getLikesByLinkIdAndBookmarkId(
               $bookmarkLinksInfo['data'][$key]['id'],
               $bookmarkInfo['data']['id']
            );

            //get nolike by linkId and bookmarkId
            $nolikesLink = $link->getNoLikesByLinkIdAndBookmarkId(
               $bookmarkLinksInfo['data'][$key]['id'],
               $bookmarkInfo['data']['id']
            );
            //assign values to current link
            $bookmarkLinksInfo['data'][$key]['likes'] = $likesLink;
            $bookmarkLinksInfo['data'][$key]['noLikes'] = $nolikesLink;
         }

         /*check if user logged is watching this bookmark*/
         if(isset($_SESSION['userId'])) {
            $isWatch =  $bookmark->isAlreadyWatch($_SESSION['userId'],$bookmarkInfo['data']['id']);               
            $bookmarkInfo['data']['alreadyWatch'] =  $isWatch;
         } else {
            $bookmarkInfo['data']['alreadyWatch'] =  'false';
         }

         $bookmarkTagsInfo = $bookmark->getTags($objData);
         $bookmarkInfo['data']['links'] = $bookmarkLinksInfo['data'];            
         $bookmarkInfo['data']['tags'] = $bookmarkTagsInfo['data'];
      }

      echo '<div class="panel panel-default ">
            <div class="panel-body">
               <span class="glyphicon glyphicon-bookmark"></span>
               <span class="titleBookmark">
                  '.$bookmarkInfo['data']['title'].'
               </span>
               <br>
               <small>
                  <i>
                     By '.$bookmarkInfo['data']['name'].'
                     <span>'.$bookmarkInfo['data']['created'].'</span>
                  </i>
               </small>
            </div>
            <div class="panel-footer actionsBookmark">
               <div>
                  '.$bookmarkInfo['data']['description'].'
               </div>
               <button type="button" class="btn btn-default btn-xs">
                  <span class="glyphicon glyphicon-plus"></span>
                  Add link to bookmark
               </button>
               <button type="button" class="btn btn-default btn-xs" ng-if="bookmarkInfo.description != "  
                ng-click="showDescriptionBookmark()">
                 <span class="glyphicon glyphicon-comment"></span> 
                 About
               </button> 

               <button  popover="{{ popoverWatch.content }}" 
               popover-title="{{ popoverWatch.title }}" popover-trigger="mouseenter" 
               type="button" class="btn btn-default btn-xs" ng-click="watchBookmark()">
                  <span class="glyphicon"></span>
                  Watch                  
               </button>
            </div>
         </div>';

   foreach ($bookmarkInfo['data']['links'] as $key => $value) {

      $currentLink = $bookmarkInfo['data']['links'][$key];

      echo '<div class="row">
            <ul class="list-unstyled">
               <li class="itemLinkOnBookmarkGrid" ng-repeat="link in bookmarkInfo.links" 
                 set-color-link-grid>

                  <div class="titleLink">
                     <a href="'.$currentLink['link'].'" target="_blank">
                        '.$currentLink['title'].'
                     </a>
                  </div>   
                  
                  <div class="linkItself">
                     <span class="glyphicon glyphicon-link"></span>
                     '.$currentLink['link'].' - <em>
                         By '.$currentLink['userName'].'
                         <span>'.$currentLink['created'].'</span>
                       </em>    
                  </div> 
               
                  <div class="text-center">
                     <div class="btn-group btn-group-xs">
                        <button type="button" class="btn btn-default" ng-click="voteLink(1,link.id,$index)">
                           <span class="glyphicon glyphicon-thumbs-up"></span>
                           '.$currentLink['likes'].'
                        </button>
                        <button type="button" class="btn btn-default" ng-click="voteLink(0,link.id,$index)">
                           <span class="glyphicon glyphicon-thumbs-down"></span>
                           '.$currentLink['noLikes'].'
                        </button>
                        <div>
                           '.$currentLink['comment'].'
                        </div> 
                        <button type="button" ng-click="showReportLink($index)" class="btn btn-default">
                           Report
                        </button>
                     </div>
                  </div>              
               </li>
            </ul>
         </div>';
   }

   echo '<ul>';

   foreach ($bookmarkInfo['data']['tags'] as $key => $value) {
       echo '<li>
            <span class="bookmarkTag label label-info">
               '.$bookmarkInfo['data']['tags'][$key]['name'].'
            </span>
          </li>';
   }

   echo '</div>';

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
