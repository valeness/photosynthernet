function getRegisterInfo(){
    var retval = {};
    var error = '';
    var required = ['username', 'email', 'pass', 'confirm_pass', '_token'];
    retval = getInput(required);

    if(error.length > 0) {
        alert(error);
        return false;
    } else {
        return retval;
    }
}

function getInput(required){
    const token_id = '_token';
    var retval = {};
    var error = '';
    required.push(token_id);

    $.each(required, function(ind, val) {
        const key = '#' + val;
        retval[val] = $(key).val();
        if(retval[val].length <= 0) {
            error += val + ' is required! \n';
        }
    });

    return retval;
}

function getLoginInfo() {
    var required = ['username', 'pass'];
    var retval = getInput(required);
    return retval;
}

function register_submit() {
    var data = getRegisterInfo();
    $.ajax({
        url : '/boars/register/create',
        type : 'post',
        data : data,
        success : function(r) {
            if(r['error'] != '') {
                alert(r['error']);
            }
        }
    });
}

function login_submit() {
    var data = getLoginInfo();
    $.ajax({
        url : '/boars/login_api',
        type : 'post',
        data : data,
        success : function(r) {
            r = JSON.parse(r);
            if(r['validated']) {
                window.location.href = '/boars/bookmarks';
            } else {
                toast.display('Invalid Username or Password');
            }
        }
    })
}

$('#login_submit').on('click', function() {
    login_submit();
});

$('#register_submit').on('click', function() {
    register_submit();
});

// Delete Bookmark
$('.del-bookmark').on('click', function(){
    var id = $(this).attr('data-id');
    var parent = $(this).parent().parent();
    $.ajax({
        url : '/boars/delete',
        type : 'post',
        data : {id : id},
        success : function(r) {
            parent.slideUp();
        }
    })
});

var toast_timeout;

var toast = {
    display : function(msg) {
        var toast = $('.toaster-template');
        toast.empty();
        toast.text(msg);
        toast.show();
        toast.animate({
            top : '0'
        }, 1000, function() {
            // Callback
            if(typeof toast_timeout != 'undefined') {
                window.clearTimeout(toast_timeout);
            }
            toast_timeout = setTimeout(function() {
                toast.animate({
                    top : '-100'
                }, 1000, function(){
                    toast.hide();
                })
            }, 4000)
        });
    }
};