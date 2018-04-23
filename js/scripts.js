$(function() {
  $('[data-toggle="tooltip"]').tooltip()
});

$(function() {
  $(".bi-phone-number").mask("+7 (999) 999-99-99");
});

function toggleCollapse() {
  if (document.documentElement.clientWidth >= 768) {
    $('#sort').addClass('show');
    $('.sidebar').addClass('sticky-top');
  }
  else {
    $('#sort').removeClass('show');
    $('.sidebar').removeClass('sticky-top');
  }
}

$(window).load( toggleCollapse() );
$(document).ready(function() {
    toggleCollapse();
    $(window).resize(toggleCollapse);
});
