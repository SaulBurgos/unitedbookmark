CREATE DATABASE  IF NOT EXISTS `bookmark` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `bookmark`;
-- MySQL dump 10.13  Distrib 5.6.13, for Win32 (x86)
--
-- Host: 127.0.0.1    Database: bookmark
-- ------------------------------------------------------
-- Server version	5.6.12-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bm_bookmark`
--

DROP TABLE IF EXISTS `bm_bookmark`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bm_bookmark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `userid` int(11) NOT NULL,
  `created` date NOT NULL,
  `hasnewlinks` varchar(45) NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title_fulltextsearch` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=107 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bm_bookmark`
--

LOCK TABLES `bm_bookmark` WRITE;
/*!40000 ALTER TABLE `bm_bookmark` DISABLE KEYS */;
INSERT INTO `bm_bookmark` VALUES (73,'Sintax sql update web','With new description\ndsfffffffdssssssfdddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddm',21,'2014-01-05','no'),(97,'New title for this shit','description',24,'2014-02-12','no'),(106,'Kkkkkkkkkkkkkkkkkkkkkkkkk','lkkjkjkj',21,'2014-02-22','no'),(78,'The Vitamin Myth','Why We Think We Need Supplements',21,'2014-01-05','no'),(79,'how to win every argument','technique',21,'2014-01-05','no'),(81,'sign someone is lying you','learn how to identify this',21,'2014-01-05','no'),(82,'Link sanitize','lksdmnklsdmfksmd',21,'2014-01-05','no'),(83,'title shite','s&ntilde;dfsl&ntilde;mfls',21,'2014-01-10','no'),(84,'Google map book in spanish','Good book to learn goog map api',21,'2014-01-11','no'),(85,'Personal page saul burgos','',21,'2014-01-11','no'),(86,'Social network','',21,'2014-01-11','no'),(87,'Nothing here to see','.mfsmdfksdmfksmdfkmdsfksmdfksmfksdmfks knk',21,'2014-01-11','no'),(88,'Web page where I can see anime','a momento para cumplir con los est&aacute;ndares web de la actualid',21,'2014-01-11','no'),(89,'donde ver anime tuani','',21,'2014-01-11','no'),(90,'espa&ntilde;ol','dfsdfsdfsf',21,'2014-01-13','no'),(91,'How money moves around the banking system','Dfsdfsd',21,'2014-01-13','no'),(94,'Bookmark ligia to use',',m ,m,m,m',23,'2014-01-18','yes'),(98,'Angular js seo index google',',m,',21,'2014-02-21','no');
/*!40000 ALTER TABLE `bm_bookmark` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bm_bookmark_link`
--

DROP TABLE IF EXISTS `bm_bookmark_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bm_bookmark_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `linkid` int(11) NOT NULL,
  `comment` text NOT NULL,
  `userid` int(11) NOT NULL,
  `bookmarkid` int(11) NOT NULL,
  `dateDeleted` date DEFAULT NULL,
  `isdelete` varchar(5) NOT NULL DEFAULT 'no',
  `title` text,
  `favicon` text,
  `created` date NOT NULL,
  `seen` varchar(45) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bm_bookmark_link`
--

LOCK TABLES `bm_bookmark_link` WRITE;
/*!40000 ALTER TABLE `bm_bookmark_link` DISABLE KEYS */;
INSERT INTO `bm_bookmark_link` VALUES (12,12,'',21,73,NULL,'no','SQL UPDATE Statement',NULL,'0000-00-00','yes'),(17,14,'',21,78,NULL,'no','The Vitamin Myth: Why We Think We Need Supplements - Paul Offit - The Atlantic',NULL,'0000-00-00','yes'),(18,15,'nothing about to comment',21,79,NULL,'no','How to win every argument - The Week',NULL,'0000-00-00','yes'),(21,17,'comment',21,82,NULL,'no','Signs Someone Is Lying To You - Business Insider',NULL,'0000-00-00','yes'),(23,19,'',21,82,NULL,'no','translate.google.com.ni',NULL,'2014-01-10','yes'),(24,20,'',21,82,NULL,'no','PHP: DOMDocument - Manual',NULL,'2014-01-10','yes'),(27,22,'testting',21,83,NULL,'no','The Secret History Of The War On Public Drinking',NULL,'2014-01-10','yes'),(28,23,'google doc',21,83,NULL,'no','docs.google.com',NULL,'2014-01-10','yes'),(29,24,'df',21,83,NULL,'no','parsing - Find div with class using PHP Simple HTML DOM Parser - Stack Overflow',NULL,'2014-01-10','yes'),(30,25,'l',21,83,NULL,'no','PHP Simple HTML DOM Parser',NULL,'2014-01-10','yes'),(31,26,'xvsfsdfs',21,83,NULL,'no','AngularJS',NULL,'2014-01-10','yes'),(32,27,'Another good link',21,79,NULL,'no','4 Ways to Always Win an Argument - wikiHow',NULL,'2014-01-11','yes'),(33,28,'Hola mi nombre es Sa&uacute;l Burgos D&aacute;vila, soy ingeniero en computaci&oacute;n y trabajo como desarrollador de aplicaciones web, desde hace 4 a&ntilde;os a tiempo completo. En mi actual empleo en OOQIA ww.ooqia.com), desarrollo aplicaciones web que incorporan soluciones de mapas y la API de Google Maps es mi princi',21,84,NULL,'no','Libro de Google Maps',NULL,'2014-01-11','yes'),(34,29,'Hola mi nombre es Sa&uacute;l Burgos D&aacute;vila, soy desarrollador de aplicaciones web, desde hace 4 a&ntilde;os a tiempo completo. Me considero un entusiasta en todo lo relacionado con la web, emprendimiento y el uso de tecnolog&iacute;as libres, esforz&aacute;ndome cada momento para cumplir con los est&aacute;ndares web de la actualid',21,85,NULL,'no','Saul Burgos - Pagina personal',NULL,'2014-01-11','yes'),(35,30,'',21,86,NULL,'no','facebook.com',NULL,'2014-01-11','yes'),(36,31,'',21,87,NULL,'no','translate.google.com.ni',NULL,'2014-01-11','yes'),(38,32,'',21,89,NULL,'no','Naruto Shippuden 344 Sub Espa&Atilde;&plusmn;ol | Kuroko no Basuke 13 | Attack on Titan OVA | Shingeki no Kyojin OVA Sub Espa&Atilde;&plusmn;ol | Hajime no Ippo Rising 13| Log Horizon 14| Pupa Sub Espa&Atilde;&plusmn;ol| | Pok&Atilde;&copy;mon XY 12| KILL la KILL 14| Naruto Shippuden Online HD | Anime Online todos los cap&Atilde;&shy;tulo en HD AnimeID.com |',NULL,'2014-01-11','yes'),(39,33,'sdfssdfksfmksdf',21,88,NULL,'no','4 Ways to Always Win an Argument - wikiHow',NULL,'2014-01-12','yes'),(40,34,'xdsdfdfs',21,88,NULL,'no','AngularJS',NULL,'2014-01-12','yes'),(41,30,'facebook',21,88,NULL,'no','facebook.com',NULL,'2014-01-12','yes'),(42,30,'sdfsf',21,89,NULL,'no','facebook.com',NULL,'2014-01-12','yes'),(43,35,'dfsdfsd',21,90,NULL,'no','Turn off warnings and errors on php/mysql - Stack Overflow',NULL,'2014-01-13','yes'),(44,36,'xcvxcv',21,91,NULL,'no','A simple explanation of how money moves around the banking system | Richard Gendal Brown',NULL,'2014-01-13','yes'),(47,29,'test of link unseen',21,91,NULL,'no','Saul burgos - pagina personal',NULL,'2014-01-18','yes'),(48,38,'another',21,84,NULL,'no','Php - get title and meta tags of external site - stack overflow',NULL,'2014-01-18','yes'),(49,39,'',21,84,NULL,'no','Ensayos - paul graham en espa&ntilde;ol',NULL,'2014-01-18','yes'),(50,40,'',21,87,NULL,'no','Avaxhome',NULL,'2014-01-18','yes'),(51,41,'',21,90,NULL,'no','Byeink.com',NULL,'2014-01-18','yes'),(52,42,'',23,94,NULL,'no','Translate.google.com.ni',NULL,'2014-01-18','yes'),(53,43,'',21,86,NULL,'no','Choosing a responsive image solution | smashing mobile',NULL,'2014-01-18','yes'),(54,42,'',21,86,NULL,'no','Translate.google.com.ni',NULL,'2014-01-18','yes'),(57,46,'',21,94,NULL,'no','Php: excepciones - manual',NULL,'2014-01-18','yes'),(61,50,'',21,94,NULL,'no','Js: the right way',NULL,'2014-01-19','yes'),(62,52,'nothing',24,97,NULL,'no','Constraint validation: native client side validation for web forms - html5 rocks',NULL,'2014-02-12','yes'),(63,53,'',24,97,NULL,'no','Angularjs',NULL,'2014-02-12','yes'),(64,54,'test',21,78,NULL,'no','Php - delete an element from an array - stack overflow',NULL,'2014-02-16','yes'),(65,55,'s&ntilde;flmskfmsdkmfksdmfklmsdlkfmksdmflksdmflksdmflkmsdkfmsdlkfmlksdmflksdmflkmsdlkfmsdlkfmlksdmflksdmfklsdmfkmsdlkfmsdlkfmsldkmflksdmflksdf',21,98,NULL,'no','Html5 - how do search engines deal with angularjs applications? - stack overflow',NULL,'2014-02-21','yes'),(66,56,'test of site that not exist',21,98,NULL,'no','404 not found',NULL,'2014-02-22','yes'),(89,65,'',21,98,NULL,'no','Learn css layout','','2014-02-22','yes'),(90,65,'xvcvxcvvcx',21,106,NULL,'no','Learn css layout',NULL,'2014-02-22','yes'),(91,30,'',21,106,NULL,'no','Facebook.com',NULL,'2014-02-22','yes'),(92,66,'',21,98,NULL,'no','United bookmarks - share your bookmarks with the people',NULL,'2014-02-22','yes'),(93,67,'',21,88,NULL,'no','Github.com',NULL,'2014-02-22','yes'),(94,68,'',21,88,NULL,'no','components &middot; bootstrap',NULL,'2014-02-22','yes'),(95,64,'',21,88,NULL,'no','Digg - what the internet is talking about right now',NULL,'2014-02-22','yes'),(96,69,'',21,88,NULL,'no','La prensa. el diario de los nicarag&uuml;enses. nicaragua',NULL,'2014-02-22','yes'),(97,32,'',21,88,NULL,'no','Ver anime online gratis en hd y con subt&Atilde;&shy;tulos en espa&Atilde;&plusmn;ol - animeid',NULL,'2014-02-22','yes'),(98,70,'',21,88,NULL,'no','Bayonetta: bloody fate (pel&Atilde;&shy;cula) 1 online gratis hd sub espa&Atilde;&plusmn;ol - bayonetta: bloody fate (pel&Atilde;&shy;cula)',NULL,'2014-02-22','yes'),(99,71,'',21,88,NULL,'no','Shingeki no kyojin manga - leer shingeki no kyojin manga en espa&ntilde;ol online en mangahere.com',NULL,'2014-02-22','yes'),(100,29,'',21,88,NULL,'no','Saul burgos - pagina personal',NULL,'2014-02-22','yes'),(101,72,'',21,88,NULL,'no','Long url maker - generate obscenely long url redirects when tiny urls won&#039;t cut it.',NULL,'2014-02-22','yes'),(102,73,'',21,88,NULL,'no','Google.com.ni',NULL,'2014-02-22','yes'),(103,74,'',21,94,NULL,'no','Github.com',NULL,'2014-02-22','no');
/*!40000 ALTER TABLE `bm_bookmark_link` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bm_bookmark_link_vote`
--

DROP TABLE IF EXISTS `bm_bookmark_link_vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bm_bookmark_link_vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `linkid` int(11) NOT NULL,
  `bookmarkid` int(11) DEFAULT NULL,
  `useful` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bm_bookmark_link_vote`
--

LOCK TABLES `bm_bookmark_link_vote` WRITE;
/*!40000 ALTER TABLE `bm_bookmark_link_vote` DISABLE KEYS */;
INSERT INTO `bm_bookmark_link_vote` VALUES (23,23,21,82,1),(24,21,27,83,1),(25,21,28,83,0),(26,21,30,83,1),(27,21,38,89,0),(32,21,50,94,1),(33,24,52,97,1),(34,21,69,88,1);
/*!40000 ALTER TABLE `bm_bookmark_link_vote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bm_bookmark_tag`
--

DROP TABLE IF EXISTS `bm_bookmark_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bm_bookmark_tag` (
  `bookmarkid` int(11) NOT NULL,
  `tagid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bm_bookmark_tag`
--

LOCK TABLES `bm_bookmark_tag` WRITE;
/*!40000 ALTER TABLE `bm_bookmark_tag` DISABLE KEYS */;
INSERT INTO `bm_bookmark_tag` VALUES (78,144),(78,145),(79,146),(79,147),(81,149),(81,150),(82,151),(83,152),(83,153),(84,154),(85,155),(85,156),(86,157),(87,158),(88,159),(88,160),(89,160),(90,168),(91,165),(94,174),(94,175),(73,165),(97,182),(97,151),(97,172),(98,175),(106,190);
/*!40000 ALTER TABLE `bm_bookmark_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bm_links`
--

DROP TABLE IF EXISTS `bm_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bm_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` text NOT NULL,
  `userid` int(11) NOT NULL,
  `reused` int(11) NOT NULL DEFAULT '0',
  `created` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bm_links`
--

LOCK TABLES `bm_links` WRITE;
/*!40000 ALTER TABLE `bm_links` DISABLE KEYS */;
INSERT INTO `bm_links` VALUES (12,'http://www.w3schools.com/sql/sql_update.asp',21,0,'2014-01-05'),(13,'http://yuiblog.com/blog/2006/04/04/synchronous-v-asynchronous/',21,0,'2014-01-05'),(14,'http://www.theatlantic.com/health/archive/2013/07/the-vitamin-myth-why-we-think-we-need-supplements/277947/',21,0,'2014-01-05'),(15,'http://theweek.com/article/index/254240/how-to-win-every-argument',21,1,'2014-01-05'),(16,'http://www.businessinsider.com/signs-someone-is-lying-to-you-2013-11?utm_source=feedburner&amp;utm_medium=feed&amp;utm_campaign=Feed:+businessinsider+%28Business+Insider%29',21,1,'2014-01-05'),(17,'http://www.businessinsider.com/signs-someone-is-lying-to-you-2013-11?utm_source=feedburner&amp;amp;utm_medium=feed&amp;amp;utm_campaign=Feed:+businessinsider+%28Business+Insider%29',21,1,'2014-01-05'),(19,'https://translate.google.com.ni/?hl=es&amp;tab=wT#en/es/You%20need%20to%20be%20logged%20to%20use%20this%20option',21,0,'2014-01-10'),(20,'http://php.net/domdocument',21,0,'2014-01-10'),(21,'http://www.nytimes.com/interactive/2013/10/08/science/the-higgs-boson.html?_r=1&amp;#/?g=true&amp;higgs1_slide=1',21,0,'2014-01-10'),(22,'http://www.huffingtonpost.com/2013/12/14/public-drinking-laws_n_4312523.html',21,2,'2014-01-10'),(23,'https://docs.google.com/document/d/1oDWpnWBGvqMunPlHa6A1RitRRlNnmrUsj5NHBukzb7k/edit?pli=1',21,0,'2014-01-10'),(24,'http://stackoverflow.com/questions/15761115/find-div-with-class-using-php-simple-html-dom-parser',21,0,'2014-01-10'),(25,'http://simplehtmldom.sourceforge.net/',21,0,'2014-01-10'),(26,'http://docs.angularjs.org/api/ng.directive:ngDisabled',21,0,'2014-01-10'),(27,'http://www.wikihow.com/Always-Win-an-Argument',21,0,'2014-01-11'),(28,'http://saulburgos.com/books/googlemaps.html',21,1,'2014-01-11'),(29,'http://saulburgos.com/',21,3,'2014-01-11'),(30,'https://www.facebook.com/',21,3,'2014-01-11'),(31,'https://translate.google.com.ni/?hl=es&amp;tab=wT#en/es/isolated',21,1,'2014-01-11'),(32,'http://www.animeid.tv/',21,8,'2014-01-11'),(33,'http://www.wikihow.com/Always-Win-an-Argument',21,0,'2014-01-12'),(34,'http://docs.angularjs.org/api/ng.directive:ngDisabled',21,1,'2014-01-12'),(35,'http://stackoverflow.com/questions/1645661/turn-off-warnings-and-errors-on-php-mysql',21,1,'2014-01-13'),(36,'http://gendal.wordpress.com/2013/11/24/a-simple-explanation-of-how-money-moves-around-the-banking-system/',21,1,'2014-01-13'),(37,'http://www.php.net/manual/en/function.lcfirst.php',21,1,'2014-01-13'),(38,'http://stackoverflow.com/questions/3711357/get-title-and-meta-tags-of-external-site',21,1,'2014-01-18'),(39,'http://paulgraham.es/ensayos/',21,1,'2014-01-18'),(40,'http://avaxhome.ws/',21,1,'2014-01-18'),(41,'https://byeink.com/',21,1,'2014-01-18'),(42,'https://translate.google.com.ni/?hl=es&amp;tab=wT#en/es/',23,2,'2014-01-18'),(43,'http://mobile.smashingmagazine.com/2013/07/08/choosing-a-responsive-image-solution/',21,1,'2014-01-18'),(44,'http://www.pajarracos.es/',21,1,'2014-01-18'),(45,'http://www.orgasmatrix.com/',21,1,'2014-01-18'),(46,'http://php.net/manual/es/language.exceptions.php',21,1,'2014-01-18'),(47,'http://xhamster.com/',23,1,'2014-01-18'),(48,'http://www.youjizz.com/',23,1,'2014-01-18'),(49,'http://underscorejs.org/#pluck',23,1,'2014-01-18'),(50,'http://jstherightway.org/#js-code-style',21,1,'2014-01-19'),(51,'http://www.whatwg.org/specs/web-apps/current-work/#constraint-validation-api',24,0,'2014-02-12'),(52,'http://www.html5rocks.com/en/tutorials/forms/constraintvalidation/',24,1,'2014-02-12'),(53,'http://docs.angularjs.org/guide/directive#creating-custom-directives_demo_isolating-the-scope-of-a-directive',24,1,'2014-02-12'),(54,'http://stackoverflow.com/questions/369602/delete-an-element-from-an-array',21,1,'2014-02-16'),(55,'http://stackoverflow.com/questions/13499040/how-do-search-engines-deal-with-angularjs-applications',21,1,'2014-02-21'),(56,'http://saulburgos.com/school/hey',21,1,'2014-02-22'),(57,'http://www.youtube.com/',21,2,'2014-02-22'),(58,'http://stackoverflow.com/questions/5701593/how-to-get-a-websites-favicon-with-php',21,7,'2014-02-22'),(59,'http://docs.angularjs.org/api/ng/directive/ngIf',21,2,'2014-02-22'),(60,'http://www.smashingmagazine.com/',21,1,'2014-02-22'),(61,'https://www.google.com.ni/',21,1,'2014-02-22'),(62,'http://www.google.com.ni/',21,1,'2014-02-22'),(63,'http://getbootstrap.com/components/',21,1,'2014-02-22'),(64,'http://digg.com/',21,2,'2014-02-22'),(65,'http://learnlayout.com/',21,3,'2014-02-22'),(66,'http://unitedbookmark.com/#!/home',21,1,'2014-02-22'),(67,'https://github.com/lokesh/color-thief',21,1,'2014-02-22'),(68,'http://getbootstrap.com/components/#btn-groups',21,1,'2014-02-22'),(69,'http://www.laprensa.com.ni/',21,1,'2014-02-22'),(70,'http://www.animeid.tv/ver/bayonetta-bloody-fate-1',21,1,'2014-02-22'),(71,'http://es.mangahere.com/manga/shingeki_no_kyojin/',21,1,'2014-02-22'),(72,'http://www.longurlmaker.com/go?id=KYFLSZLXADBJFAZKTCAYKDWXKPQLJSLURRFJQRHRUIAZJAKDCKCMNYKYOAJXTUSKMYUCPBTKKTKTTUXVEZISXSRMSAJLVCWHAQJQSDADWLXQFULKTUDRMUDFUNRQPNYQEHHXLIAHTXXZSJJMDNDQHHWCVNTLBRCGZJDKRERLCPKUZUGDIKTPSQSNDLYFCALBKOLCSDNUTYPTTVWBGQQZGJMKULPXMAZWOLYHOMCIKRBDNYFUPWTVFGGARVXEVWBKHZSWMUFXLHBYFGTUCMQHSWIKSFOOCPZJPRGCMLZXSAWXGPSICJQUGYFYEUNGKMQZEWBQHAOZAKXHAQPDZGYGFDEJXSPHEFHIBJYJKNILYGSYWICWOACTDHDBZTJEZRNAAMKLZTWXZOWWWYSLYUFCCIEBBOGAFTBGFMREFNBFCXBYWTKVOPXQYCSAQYBVRDBXPTBVGDAIBBHXVSSKHQBGSUHISIEKMGHCZJXGNXOOZWMVOEFWVHCNBJWUSAEEHMHGWEMJCBYCXKXLPDHKKKXMUUGNVLSCYZIUDVEGWCIUNFGCJOMUYKGTENGAYYCWXLRAHVHEYPYLVENESAZRLGKQTQQROSOMDFNLBUPZJNKESYJLZICKONAIDQZSJOFNTSYUMNUWAFBTDKEDTGNITOQXEQQOEVBYNZTANNWOSXHWILABSNKMCAJHRAVVVWUJWNKJAHXTFEQNQQPIEAVGAFNRFINBEHKBUVKVCHPHMFVDVLLZLHFMMSDRBRSGYDHTYRPBZEIMKEPFPBFAILMUEQMFIELHHSAGKQHKVQWFUMLKOQLWBYRFODLWIWDQPEWZVEJQUGWPTHZHYKEZJVFXZQUHNYXCDUCZYLPTSLILTITRTXRCTXASOVABTXEXSGWQSMKLYTXRBQJVOAXHXYAMTANNYSKRYHIRTSCRLZJMQSHESFLQDMCXNQKMJVDHCLZVDBNOBWBROJVHPHXSUAPHRATAVWIXIHTLIGAKDCBRLWYAEWTYXJFOJZPFWXCEEWPNCQXFSZXEWWEATYZQIEFREUWARZEWVUKYKIEDHBHDXMEQKDHSIMKMGHMYHRVDLGBWOFAWHHZFTDVEGDXOPHBWPNVWFRARXBNLHOIOVHUPLQTSTRHIZIFOWALBSLTPNGBVVJJQREFCUZUNRCWQKBFHCRIUCBKQILLDVVUMZAOTAJHRLDHWFNDIEMCHONXXYJBTEVFDWUXWEEOPIWMNJQVNCYURLSOKBQDGMJKIEHFIMTYVPKIYBEMDDHVOAJZBADHMNRVRZAZMTYHJJPIKUUOXCJMCTMETPMGCEBTDBTPVSXEBNMLHHZFJJSMCEQWTCCWGDQKEJAABXEDKROSYOXIYQUAUKXONZKTCBEHKEHMBMQMDEECTBKRSFSMQPBDPMXRNBZYFHLHUBUYGYAZALRSRKFHAGKPSHHFJHEPOPWIQQGWPHWPTOHKZMRZTBOLJWRSDWHSLEBCVHZKPWAIKISJVKIOMXAVUSOXOVPAAQCVYCGOYHWJPPSKZBZLZAHTSVRHRGHRXKNWMUKLBHUQWMBWOBIOBPHTLYACFITDSHZFBKQCRKTOXVLMWUAXKIRVGRXMARPSYPXAZOCRZWFXRRJNLKKVSCQZTOLTGAMFQKFPZIGYEMVVEFIPQTKIVBHPPSJWTWBJGGZGOGETTAOXGXMWRXFNYMDOFMKZJLJQSIWGPAAIBOFHMREDPJQNWTBCGLCPXLGPUCWJDWRELXMXPRAEBRRXLTASFCHCONSIQOSTKKYVHKTWCUBDLTBXNBPSEWVSKNABCTUMDSIKDCHFWJIHCJFPLUIPRDICRJDTCXGFQPQTRXYNHHVJRAZCUISMLBOCKRWMOCSERIYIGWWNERXVSXYNFQZRRNUCEQOTSHYJPWSWTOKXGHSYFQLKHLBZYVBDLQWEXVONRGJLUTIBBBZGRLQZWSYVOZYAPVENQSAIZJTTCBUEDUKUFBUCUSXISVIIRNVHGVQFEJZHLULOPVJVXDXRWVZORIWIWRQCNGIRPHZBCKQRGZMEDJVZEVOVELEADUDQAL',21,1,'2014-02-22'),(73,'https://www.google.com.ni/search?q=css+arrange+elements+like+pinterest&amp;oq=css+arrange+elements+like+pinterest&amp;aqs=chrome..69i57j69i64.10708j0j4&amp;sourceid=chrome&amp;espv=210&amp;es_sm=122&amp;ie=UTF-8',21,1,'2014-02-22'),(74,'https://github.com/microweber/screen',21,1,'2014-02-22');
/*!40000 ALTER TABLE `bm_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bm_report_link`
--

DROP TABLE IF EXISTS `bm_report_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bm_report_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bookmarkid` int(11) NOT NULL,
  `linkid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bm_report_link`
--

LOCK TABLES `bm_report_link` WRITE;
/*!40000 ALTER TABLE `bm_report_link` DISABLE KEYS */;
INSERT INTO `bm_report_link` VALUES (4,94,50,21,'oye','2014-01-23'),(5,97,52,24,'test reporting','2014-02-12'),(6,88,68,21,'ok ok','2014-02-22');
/*!40000 ALTER TABLE `bm_report_link` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bm_tags`
--

DROP TABLE IF EXISTS `bm_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bm_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  FULLTEXT KEY `name_fulltextsearch` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=191 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bm_tags`
--

LOCK TABLES `bm_tags` WRITE;
/*!40000 ALTER TABLE `bm_tags` DISABLE KEYS */;
INSERT INTO `bm_tags` VALUES (114,'php','2014-01-05'),(115,'css','2014-01-05'),(116,'koko','2014-01-05'),(121,'&ntilde;mmclsm','2014-01-05'),(122,'laptopojojojojojojojojojojojojojo','2014-01-05'),(123,'joder','2014-01-05'),(134,'sql','2014-01-05'),(135,'sintax','2014-01-05'),(136,'update','2014-01-05'),(140,'ajax','2014-01-05'),(144,'vitamin','2014-01-05'),(145,'health','2014-01-05'),(146,'argument','2014-01-05'),(147,'technique','2014-01-05'),(149,'lie','2014-01-05'),(150,'person','2014-01-05'),(151,'link','2014-01-05'),(152,'nada','2014-01-10'),(153,'java','2014-01-10'),(154,'googlemaps','2014-01-11'),(155,'saul','2014-01-11'),(156,'burgos','2014-01-11'),(157,'facebook','2014-01-11'),(158,'tradutor','2014-01-11'),(159,'anime','2014-01-11'),(160,'animetv','2014-01-11'),(161,'newtag1','2014-01-13'),(162,'newtag2','2014-01-13'),(163,'beverage','2014-01-13'),(164,'coko','2014-01-13'),(165,'newtag','2014-01-13'),(166,'peluche','2014-01-13'),(167,'espa&ntilde;ol','2014-01-13'),(168,'espa&ntilde;oll','2014-01-13'),(169,'money','2014-01-13'),(170,'drinking','2014-01-14'),(171,'ocks','2014-01-18'),(172,'jodiendo','2014-01-18'),(173,'indio','2014-01-18'),(174,'traductor','2014-01-18'),(175,'google','2014-01-18'),(176,'porn','2014-01-18'),(177,'sites','2014-01-18'),(178,'good','2014-01-18'),(179,'newlib','2014-02-12'),(180,'form','2014-02-12'),(181,'validation','2014-02-12'),(182,'temp','2014-02-12'),(183,'perro','2014-02-22'),(184,'sandino','2014-02-22'),(185,'jojojo','2014-02-22'),(186,'test','2014-02-22'),(187,'stack','2014-02-22'),(188,'ojojojo','2014-02-22'),(189,'favicon','2014-02-22'),(190,'tag','2014-02-22');
/*!40000 ALTER TABLE `bm_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bm_users`
--

DROP TABLE IF EXISTS `bm_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bm_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `password` text NOT NULL,
  `registered` date NOT NULL,
  `email` text NOT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bm_users`
--

LOCK TABLES `bm_users` WRITE;
/*!40000 ALTER TABLE `bm_users` DISABLE KEYS */;
INSERT INTO `bm_users` VALUES (21,'Saul Burgos','193ed654a26439dbd64576b8a9c8cc67b8db9fb015787c1050238dcb649cbc64','2014-01-01','test@test.test',1),(23,'ligia','1f115f2d82f520a364fe31b716117edb6bcb7f6812b03e9230a8b68d6a3e6f74','2014-01-06','ligia@ligia.ligia',1),(24,'gilbert','5288ef45cc463fff9b2c864ee6b66156fd6a34c81fd10de4c4258421cf6475cb','2014-02-12','gilbert@gilbert.gilbert',1);
/*!40000 ALTER TABLE `bm_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bm_watch_bookmark`
--

DROP TABLE IF EXISTS `bm_watch_bookmark`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bm_watch_bookmark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bookmarkid` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `date_lastcheck` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bm_watch_bookmark`
--

LOCK TABLES `bm_watch_bookmark` WRITE;
/*!40000 ALTER TABLE `bm_watch_bookmark` DISABLE KEYS */;
INSERT INTO `bm_watch_bookmark` VALUES (15,106,21,'2014-02-22'),(14,105,21,'2014-02-22');
/*!40000 ALTER TABLE `bm_watch_bookmark` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-03-12 22:51:32
