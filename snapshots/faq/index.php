<!DOCTYPE html>
<html lang="en">
<head>
   <meta name="fragment" content="!" />
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Frequently Asked Questions - United Bookmarks</title>
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

      
      <div class="row">  
        
         <div class="col-md-9 col-sm-9" style="text-align:justify;">

            <div class="row">
               <h3>
                  <span class="glyphicon glyphicon-globe"></span>
                   Frequently Asked Questions
               </h3>
            </div>
            
            <div class="row">
               <h4>
                  <span class="glyphicon glyphicon-chevron-right"></span>
                  What the hell is United Bookmark ?
               </h4>
               <p>
                 Hello stranger, well first you need to know something about the search engines like " Google, Yahoo and Bing ".
                 All these sites show you information based on an algorithm that decide what content is important or relevant to users.
               </p>
               <p>
               So these algorithms are secret and nobody know how work. For example: Each year, Google changes its search algorithm around 500–600 times. While most of these changes are minor, Google occasionally rolls out a "major" algorithmic update, that affects search results in significant ways. When this happens, all the results change and many sites are lost who know where. you can learn more in this <a href="http://moz.com/google-algorithm-change" target="_blank">link</a> , <a href="https://www.webduckdesigns.com/pages/website-resources/google-results.php" target="_blank">here</a> 
               and <a href="http://www.youtube.com/watch?v=KyCYyoGusqs" target="_blank">here</a>.
               </p>
               <p>            
                  There are a lot of techniques and rules that webmasters need to follow in order to appear Google's results and that  is a big task for webmasters. Normal people like me don´t know about these rules and others stuffs like 
                  <a href="http://en.wikipedia.org/wiki/Search_engine_optimization" target="_blank">SEO</a> or 
                  <a href="http://en.wikipedia.org/wiki/Search_engine_marketing" target="_blank">SEM</a>. I only want the content that I am searching.
               </p>
               <P>
                  Now that you know that,  imagine that you have found  a website with good information about how to prepare a super-duper sandwich but this site was created by a grandmother with help of her grandchild. The problem here is that either the grandmother and the grandchild don't know nothing about the rules of Google, they just write a blog to share their recipe.
               </p>
               <p>
                  So this website does not have chances to appear in the results against others sites that has paid  a lot money in SEO. Even when the grandmother´s website has the best recipe. If you add to this that Google remove from his results some websites that consider inappropriate,  you have a lot of information that is hidden from us. 
               </p>
               <p>
                 That is the problem that I want to fix. because I am the person that think that getting answers from people is very different from retrieving information with algorithms. No matter how sophisticated algorithms become, they are still no match for the experience, inventiveness, and creativity of the human mind. People want  answers from people searching the same thing. 
               </p>
               <p>
                  United Bookmark is my idea to help these  tiny websites not to be forgotten by an evil algorithm and the same time share my bookmarks with others users.
               </p>         
            </div>
            <div class="row">
               <h4>
                  <span class="glyphicon glyphicon-chevron-right"></span>
                  How does United Bookmark work ?
               </h4>
               <p>
                 Hello stranger, like you I also spend a lot time using search engines to find something on internet, for example Google. And sometimes I search page by page until find something that I consider useful to me. 
               </p>
               <p>
                 <i>What do I do when I found some website that was difficult to find ?</i>
               </p>
               <p>
                 well,  I save it like a bookmark,  because you know... the evil algorithm might forget this website in any moment and disappear from results.   
               </p>
               <p>
                  If another user is searching the same thing that I searched before, he will waste the same time that I lost looking up the same information or worst, he never won’t find the information because the evil algorithm might not consider that website important this time. We are doom to obey this algorithm.
               </p>
               <p>
                  The idea of United Bookmark is that you save all your bookmarks in one single place and share them with others users. 
               </p>
               <ul>
                  <li>Share all your links that were difficult to find.</li>
                  <li>Share all your links that you consider important.</li>
                  <li>Share all your links that might help others users to find their answers.</li>
               </ul>
               <p>
                  Users helping others users not an algorithm telling you what to see. Once you add a bookmark, others users can share their own links with you,  that they consider useful based on your bookmark description.  You may find yourself adding bookmarks as well as sharing links with others users. You can help friends, or friends-of-friends with their bookmarks and grow our collection of bookmark in order to help other people. It feels good to share…  :)
               </p>
               <p>
                  All users can vote on all links,  in that way you know what link is the best and discard links that may not have valuable content. Take for example the website of the previous question, the grandmother’s  recipe about sandwich. You can create a bookmark called : “Best recipes of sandwiches” and add the link of the grandmother’s website.
               </p>
               <p>
                  Once this bookmark is created other users can add others links with other recipes of sandwich that they found interesting.  In that way you will have valuable links about recipes of sandwiches that others users tested and consider important to share with you.
               </p>          
            </div>
            
            <div class="row">
               <h4>
                  <span class="glyphicon glyphicon-chevron-right"></span>
                  What kind of content is allowed to publish ?
               </h4>
               <p>
                  Hello stranger…. well you can publish any content that you consider important to share with others users. I am not going to remove links in this site, because I consider every link a piece of valuable content that need to be share with people, no matter the content or topic.
               </p>
               <p>
                  At least until some authority or company,  send me an email threatening me with jail or something like that. I don't want to be in jail so.. sorry. But I will fight first against these threats to defend the right to share information on internet.
               </p>
               <p>
                  In overall you can publish any content that you want.
               </p>
            </div>

            <div class="row">
               <h4>
                  <span class="glyphicon glyphicon-chevron-right"></span>
                  I can not find anything with the search tool, this site sucks !!
               </h4>
               <p>
                 Hello stranger…. there are some causes why you are not receiving results of this tool.
               </p>
               <ul>
                  <li>
                     It does not exist a bookmark with that title.      
                  </li>
                  <li>
                     This site is relatively new... so we don't have so much content to share.      
                  </li>
                  <li>
                     You must write a good term to search, it’s not going to work with simply “Dog”, you should write more than 2 words. It could work. Ex: “Dog running on the beach”.
                  </li>
                  <li>
                    Are you using the filter by tag ?  
                  </li>
                  <li>
                     I am not Google, I can't find results like them :(.
                  </li>
               </ul>
               <p>
                  I finally, I know that sometimes the search tool is not working well, I am working to fix that. It's a little complicate to do it, so I need some help with the SQL query. If you are an expert on SQL queries maybe you can help me to improve this search tool, send me a email to : <a href="mailto:info@saulburgos.com">info@saulburgos.com</a>.
               </p>
            </div>

            <div class="row">
               <h4>
                  <span class="glyphicon glyphicon-chevron-right"></span>
                  What is the state of United Bookmark ?
               </h4>
               <p>
                  United bookmark is in beta phase. That means :   
               </p>
               <ul>
                  <li>
                     Is an unfinished product.
                  </li>
                  <li>
                     Contain a lot of errors.      
                  </li>
                  <li>
                     I am working to improve some features.      
                  </li>
                  <li>
                     The interface and colors may change in the future.      
                  </li>
               </ul>
               <p>
                  If you find a error, I will be glad if you send me an email telling me about it to 
                  <a href="mailto:info@saulburgos.com">info@saulburgos.com</a>
               </p>
            </div>
            
         </div>

         <div class="col-md-3 col-sm-3 hidden-xs noPaddingBootstrap">
            <div class="row">
               <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
               <!-- unitedBookmark -->
               <ins class="adsbygoogle"
                    style="display:inline-block;width:300px;height:250px"
                    data-ad-client="ca-pub-5067790193708510"
                    data-ad-slot="3700204435"></ins>
               <script>
               (adsbygoogle = window.adsbygoogle || []).push({});
               </script>
            </div>
            <div class="row text-center">
               <h4>Recent tags</h4>
            </div>
            <div id="tagsContainer" class="row">
               <ul class="list-unstyled">
                  <li ng-repeat="tag in recentTags">
                     <span class="bookmarkTag label label-info">
                        {{tag.name}}
                     </span>
                  </li>
               </ul>
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
