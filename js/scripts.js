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

//$(window).load( toggleCollapse() );
$(document).ready(function() {
    toggleCollapse();
    $(window).resize(toggleCollapse);
});

$('#modal-confirm-bid').on('show.bs.modal', function (event) {
    let modal = $(this).find('.modal-content'),
        button = $(event.relatedTarget),
        bid = button.parents('.card'),
        bidId = button.data('id'),
        price = bid.find('.badge-warning').text();
    console.log(modal);
    if (button.data('action') === 'accept') {
        modal.html('<div class="modal-header"><h5 class="modal-title">Подтвердите действие</h5><button class="close" data-dismiss="modal"><span>&times;</span></button></div><div class="modal-body"><p>Вы уверены, что хотите принять заявку? С Вашего счёта будет списано ' + price + ' рублей.</p></div><div class="modal-footer"><a href="master.php?requestid=' + bidId + '&action=accept" class="btn">Принять</a></div>');
    }
    if (button.data('action') === 'decline') {
        modal.html('<div class="modal-header"><h5 class="modal-title">Подтвердите действие</h5><button class="close" data-dismiss="modal"><span>&times;</span></button></div><div class="modal-body"><p>Вы уверены, что хотите <span class="text-danger">отменить</span> заявку? Ваш рейтинг будет снижен.</p></div><div class="modal-footer"><a href="master.php?requestid=' + bidId + '&action=decline" class="btn text-danger">Отменить</a></div>');
    }
});