import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */

$('#js-edit-view').on('click',function () {
    $('#js-edit-view').css('display','none');
    $('#to_do_list_title').attr('disabled',false);
    $('#to_do_list_description').attr('disabled',false);
    $('#js-edit-btn').css('display','block');;
});

$('.table_row').on('click',function (e) {
    let rowID = e.currentTarget.getAttribute('data-id');
    window.location.href = "http://sylesson.lc/view/" + rowID;
})
