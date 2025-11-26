jQuery(document).ready(function($) {
    // مدیریت فرم لاگین
    $('#login-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'ajax_login');
        formData.append('security', ajax_object.login_nonce);

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    window.location.href = response.data.redirect;
                } else {
                    $('#login-error').text(response.data.message).show();
                }
            },
            error: function() {
                $('#login-error').text('خطا در ارتباط با سرور').show();
            }
        });
    });

    // مدیریت نمایش/پنهان فرم ویرایش
    $('#editInfoBtn').on('click', function() {
        $('#infoDisplay').addClass('hidden');
        $('#editForm').removeClass('hidden');
        $(this).text('در حال ویرایش...').prop('disabled', true);
    });

    $('#cancelEditBtn').on('click', function() {
        $('#editForm').addClass('hidden');
        $('#infoDisplay').removeClass('hidden');
        $('#editInfoBtn').text('ویرایش اطلاعات').prop('disabled', false);
    });

    // مدیریت فرم ویرایش
    $('#gameNetInfoForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'update_game_net_info');
        formData.append('security', ajax_object.update_nonce);

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                }
            },
            error: function() {
            }
        });
    });

    // نمایش/پنهان کردن پسورد
    $('#toggle-password').on('click', function() {
        const input = $('.passwordinput');
        input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
    });
});

