// Mobile menu
var toggle = document.querySelector('.nav-toggle');
var nav = document.getElementById('main-nav');
if (toggle && nav) {
  toggle.addEventListener('click', function () {
    var open = nav.classList.toggle('open');
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  });
}

// Signup: check that both passwords match before sending the form
var signup = document.getElementById('signup-form');
if (signup) {
  signup.addEventListener('submit', function (e) {
    var pw = document.getElementById('password').value;
    var cf = document.getElementById('confirm').value;
    if (pw.length < 8 || pw !== cf) {
      e.preventDefault();
      alert(pw.length < 8
        ? 'Use a password with at least 8 characters.'
        : 'The two passwords do not match.');
    }
  });
}
