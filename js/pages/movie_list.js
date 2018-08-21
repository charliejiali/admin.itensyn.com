/**
 * Created by ewen on 2016/5/12.
 */
var win = {};

$(document).ready(function () {
    onResize();
    $(window).bind('resize', onResize);

    function onResize() {
        win.w = $(window).width();
        win.h = $(window).height();
    }

    $('.form-eval select').each(function (i, e) {
        selectbox(this);
    });

    $("#table-head").html($("#table-data").html());
    $(".table-list").mCustomScrollbar({
        axis: "x",
        theme: "dark",
        // scrollInertia: 160,
        mouseWheel: {enable: false}
    });


});

var urlBase = 'ajax/';