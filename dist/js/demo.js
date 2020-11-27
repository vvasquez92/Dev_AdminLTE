/**
 * AdminLTE Demo Menu
 * ------------------
 * You should not use this file in production.
 * This file is for demo purposes only.
 */
(function($) {
    'use strict'

    var $sidebar = $('.control-sidebar')
    var $container = $('<div />', {
        class: 'p-3 control-sidebar-content'
    })

    $sidebar.append($container)


    $container.append(
        '<a href="login.php"><i class="fas fa-power-off"></i><span>  Salir</span></a>'
    )

    $('.product-image-thumb').on('click', function() {
        const image_element = $(this).find('img');
        $('.product-image').prop('src', $(image_element).attr('src'))
        $('.product-image-thumb.active').removeClass('active');
        $(this).addClass('active');
    });
})(jQuery)