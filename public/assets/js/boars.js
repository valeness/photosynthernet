function getRegisterInfo(){
    var retval = {};
    var error = '';
    var required = ['username', 'email', 'pass', 'confirm_pass', '_token'];
    $.each(required, function(ind, val) {
        const key = '#' + val;
        retval[val] = $(key).val();
        if(retval[val].length <= 0) {
            error += val + ' is required! \n';
        }
    });

    if(error.length > 0) {
        alert(error);
        return false;
    } else {
        return retval;
    }
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

$('#register_submit').on('click', function() {
    register_submit();
});

$('input').on('keypress', function(e) {
    if(e.keyCode == '13') {
        register_submit();
    }
});