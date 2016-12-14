document.getElementById('topbar').innerHTML =
	"<nav class='light-blue lighten-1' role='navigation'>" +
	    "<div class='nav-wrapper container'><a id='logo-container' href='index.html' class='brand-logo'>Cameron Hungries</a>" +
	      "<ul class='right hide-on-med-and-down'>"+
	      "<li><a href='login.php'>Log In</a></li>" +
	        "<li><a href='./menu-n.php'>Menus</a></li>" +
	      "</ul>" +

	      "<ul id='nav-mobile' class='side-nav' style='transform: translateX(-100%);'>" +
	        "<li><a href='menu-n.php'>Menus</a></li>" +
	        "<li><a href='all-restaurants.php'>Add Food</a></li>" +
	        "<li><a href='recommendation.php'>Recommendations</a></li>" +
	        "<li><a href='profile.php'>My Profile</a></li>" +
	        "<li><a href='logout.php'>Log Out</a></li>" +
	      "</ul>" +
	      "<a href='#' data-activates='nav-mobile' class='button-collapse'><i class='material-icons'>menu</i></a>" +
	    "</div>" +
  	"</nav>";

document.getElementById('bottombar').innerHTML =
	"<footer class='page-footer orange'>" +
	    "<div class='footer-copyright'>" +
	      "<div class='container'>" +
	      "We are amazing" +
	      "</div>" +
	    "</div>" +
  	"</footer>";