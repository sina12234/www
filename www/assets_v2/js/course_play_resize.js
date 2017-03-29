/**
 * course_play.resize
 * @authors liangchangqing
 * @date    2015-08-21 14:10:21
 */
function playResize(){
    var window_h = $(window).height();
    var window_w = $(window).width();
    var _left_player= $('#left-player');
    var _right_player= $('#right-player');
    var _player_box= $('#main');
    player_box();
    left_player();
    right_player();
    function left_player(){
        var students=$('#students_display').find('.Student-list');
        var free_list=$('#free_list');
        free_list.height(window_h - 80 + 'px');
        students.height(window_h - 120 + 'px');
    };
    function player_box(){
        var _player_flash_box=$('#box');
        var _handle_area=$('#handle-area');
        var _player_box_w=window_w-_left_player.width()-_right_player.width()-20;
        var _player_box_h;
        var _handle_area_h;
        _player_box.width(_player_box_w + 'px');
        _player_box.height(window_h + 'px');
        _player_flash_box.height(_player_flash_box.width()*9/16);
        _handle_area_h=window_h-_player_flash_box.height()-50;
        if(_handle_area_h<200){
            _handle_area_h=200;
            _player_flash_box.height(window_h-250);
            _player_flash_box.width(_player_flash_box.height()*16/9);
            _player_flash_box.css('margin-left',(_player_box_w-_player_flash_box.width())/2);
        }
        _handle_area.height(_handle_area_h);
        // if(_handle_area_h<200){
        //     _handle_area_h=200;
        //     _player_box_h=window_h-_handle_area_h;
        // }
    };
    function right_player(){
        var chat=$('#chat');
        var chat_list=$('#chat').find('ul');
        chat.height(window_h - 205 + 'px');
        chat_list.height(window_h - 205 + 'px');
    };
}
