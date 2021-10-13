// Get the modal
var modal = document.getElementById('log_form');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
		location.href="./homepage.html";
    }
}
