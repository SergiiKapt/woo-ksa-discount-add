(function ($) {
    $(document).ready(function () {
        if (+ksaSwa.outDiscount) {
            var popup = '<div class="exitblock">';
            popup += '<div class="fon"></div>';
            popup += '<div class="modaltext">';
            popup += 'Stay on the site and get a ' + ksaSwa.outDiscount + '% discount.';
            popup += '<span id="out__discount" class="discount__btn">ok</span>?';
            popup += '</div><div class="closeblock"> </div></div>';
            $('body').append(popup);

            $(document).mouseleave(function (e) {
                if (e.clientY < 10) {
                    $(".exitblock").fadeIn("fast");
                }
            });

            $(document).click(function (e) {
                if (($(".exitblock").is(':visible')) && (!$(e.target).closest(".exitblock .modaltext").length)) {
                    $(".exitblock").fadeOut();
                }
            });

            $('#out__discount').on('click', function (e) {
                var data = {
                    action: 'process_reservation',
                    nonce: ksaSwa.nonce,
                };

                $.post(ksaSwa.url, data, function (response) {
                    if (response.data == 'success') {
                        location.reload()
                    }
                });
            });
        }

    });
})(jQuery);