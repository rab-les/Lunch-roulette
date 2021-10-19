//Settiamo gli eventListener per i tre bottoni che ne hanno bisogno.
window.onload = function(){
  document.getElementById('newRestaurant').addEventListener("click", showForm, true);
  document.getElementById('hide_form').addEventListener("click", hideForm, true);
  document.getElementById('post_res_data').addEventListener("click", postData, true);
};
//Mostra il form usato per inserire i dati del ristorante e nasconde due bottoni
//per migliorare la leggibilità dell'insieme.
function showForm() {
  document.getElementById('form_container').style.display = "block";
  document.getElementById('newRestaurant').style.visibility = "hidden";
  document.getElementById('signout').style.visibility = "hidden";
};
//Nasconde il form e restituisce la visibilità ai due bottoni nascosti.
function hideForm() {
  document.getElementById('form_container').style.display = "none";
  document.getElementById('newRestaurant').style.visibility = "visible";
  document.getElementById('signout').style.visibility = "visible";
};
//Nasconde il form, mostra il testo e restituisce la visibilità solo al bottone di logout.
function postData() {
  document.getElementById('form_container').style.display = "none";
  document.getElementById('text_container').style.display = "block";
  document.getElementById('signout').style.visibility = "visible";
};
