document.getElementById('topbar').innerHTML =
	"<nav class='light-blue lighten-1' role='navigation'>" +
	    "<div class='nav-wrapper container'><a id='logo-container' href='#' class='brand-logo'>Nutrinfo</a>" +
	      "<ul class='right hide-on-med-and-down'>"+
	        "<li><a href='http://dining.duke.edu'>Menus</a></li>" +
	        "<li><a href='#'>Log In</a></li>" +
	      "</ul>" +

	      "<ul id='nav-mobile' class='side-nav' style='transform: translateX(-100%);'>" +
	        "<li><a href='registration.php'>Register</a></li>" +
	        "<li><a href='login.php'>Log In</a></li>" +
	        "<li><a href='#'>We Out Here</a></li>" +
	        "<li><a href='#'>Get Fit</a></li>" +
	      "</ul>" +
	      "<a href='#' data-activates='nav-mobile' class='button-collapse'><i class='material-icons'>menu</i></a>" +
	    "</div>" +
  	"</nav>";

document.getElementById('bottombar').innerHTML =
	"<footer class='page-footer orange'>" +
	    "<div class='footer-copyright'>" +
	      "<div class='container'>" +
	      "</div>" +
	    "</div>" +
  	"</footer>";