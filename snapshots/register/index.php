<!DOCTYPE html>
<html lang="en">
<head>
   <meta name="fragment" content="!" />
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register User - United Bookmarks</title>
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

      <div class="center-block" style="max-width:400px;">

   <div ng-hide="isThereSession()">
      <tabset>
         <tab heading="Login">
            <form id="loginForm" role="form" name="loginForm" ng-submit="loginUser()" class="well well-sm">
               <div class="form-group" ng-class="isValid(loginForm.email)">
                  <label>Email address</label>
                  <input type="email" name="email" class="form-control" 
                  placeholder="Enter email" ng-model="loginData.email" required/>
               </div>
               <div class="form-group" ng-class="isValid(loginForm.pass)">
                  <label>Password</label>
                  <input type="password" name="pass" class="form-control" 
                  placeholder="Password" ng-model="loginData.pass" required />
               </div>
               <button type="submit" class="btn btn-default">
                     Submit
               </button>
            </form>
         </tab>
         <tab heading="Register">
            <form id="registerForm" role="form" name ="registerForm" ng-submit="registerUser()" class="well well-sm">
               <div class="form-group" ng-class="isValid(registerForm.name)">
                  <label>Username</label>
                  <input type="text" name="name" class="form-control" 
                  placeholder="userName" ng-model="userData.name" required/>
               </div>
               <div class="form-group" ng-class="isValid(registerForm.email)">
                  <label>Email address</label>
                  <input type="email" name="email" class="form-control" 
                  placeholder="Enter email" ng-model="userData.email" required/>
               </div>
               <div class="form-group" ng-class="isSamePass(registerForm.pass1,registerForm.pass2)">
                  <label>Password</label>
                  <input type="password" name="pass1" class="form-control" 
                  placeholder="Password" ng-model="userData.pass1" required />
               </div>
               <div class="form-group" ng-class="isSamePass(registerForm.pass1,registerForm.pass2)">
                  <label>Repeat password</label>
                  <input type="password" name="pass2" class="form-control" 
                  placeholder="Repeat password" ng-model="userData.pass2" required/>
               </div>
               <div class="checkbox">
                  <label>
                    <input type="checkbox" name="human" ng-model="human" required> Are you human ?
                  </label>
               </div>
               <button type="submit" class="btn btn-default">
                     Submit
               </button>
            </form>
         </tab>
      </tabset>
      <div ng-class="{'alert alert-danger' : msg.error , 'alert alert-success' : msg.success }">{{ msg.text }}</div>
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
