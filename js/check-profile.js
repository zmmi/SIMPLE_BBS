$(function() {
    // Enter Keyでさぶみっとしない
    $(document).on("keypress", "input:not(.allow_submit)", function(event) {
        return event.which !== 13;
    });
    $("button[name='submit']").click(function(){
        // アラート内容の記載
        var message = '';
        var submit = $("button[name='submit']").val();
        if(  submit == 'confirm' ) {
            // PL名のチェック
            if ($("input[name='pl_name']").val() == '') {
                message += 'PL名が未入力です。\n';
            }
            // パスワードのチェック
            if ($("input[name='password']").val() == '') {
                message += 'パスワードが未入力です。\n';
            }else{
                if( !$.isNumeric( $("input[name='password']").val() ) ) {
                    message += 'パスワードは4桁の数字で入力してください。\n';
                }
            }
            // メールアドレスのチェック
            if ($("input[name='mail_address']").val() == '') {
                message += 'メールアドレスが未入力です。\n';
            }
            // PC名のチェック
            if ($("input[name='pc_name']").val() == '') {
                message += 'PC名が未入力です。\n';
            }
            // スペルのチェック
            if ($("input[name='pc_name_spell']").val() == '') {
                message += 'スペルが未入力です。\n';
            }
            // サイドのチェック
            if ($("input[name='side']").val() == '') {
                message += 'サイドが未選択です。\n';
            }
            // カラーのチェック
            if ($("input[name='color_name']").val() == '') {
                message += 'カラーが未入力です。\n';
            }
            // カラーコードのチェック
            if ($("input[name='color_code']").val() == '') {
                message += 'カラーコードが未入力です。\n';
            }
            // 性格備考のチェック
            if ($("input[name='profile']").val() == '') {
                message += '性格備考が未入力です。\n';
            }
        }else if( submit == 'main' || submit == 'sub' || submit == 'update' ) {
            // タイトルのチェック
            if ($("input[name='title']").val() == '') {
                message += 'タイトルが未入力です。\n';
            }
            // 本文のチェック
            if ($("input[name='content']").val() == '') {
                message += '本文が未入力です。\n';
            }
            // パスワードのチェック
            if ($("input[name='password']").val() == '') {
                message += 'パスワードが未入力です。\n';
            }
        }
        if( message !== '' ) {
            window.alert( message );
            return false;
        }else{
            return true;
        }
    })
});