(function() {
    var requestModal = (function () {
        var modal = $('#txtCheckRequestModal'),
            txt_record_added_button = document.getElementById('btn_txt_record_added'),
            close_button = document.getElementById('closeButton');

        function initialize() {

            txt_record_added_button.addEventListener('click', txt_record_check());
            close_button.addEventListener('click', hideModal);
            //resetButton.addEventListener('click', reload);
        }

        function hideModal() {

            return modal.modal('hide');
        }

        function showModal() {
            //modal.modal({'keyboard': true}).modal('show');
            return modal.modal('show');
        }


        function txt_record_check(){

            var $step = $("input[name='step']").val();
            var data = {
                "step": $step,
            }

            $.ajax({
                type: "POST",
                url: "/addsite/ssl_token_check",
                data: data,
                dataType: "json",
                success: function (res) {

                    console.log(res);

                    if(res.result == 'error'){
                        showModal();

                        //makeModalAlert('Info', c2ms('TXT record is not added. Please try again.'));
                        //window.location.href = "/mysites";
                        return;
                    }else{
                        makeModalAlert('Info', res.message);
                        window.location.href = "/addsite/dns";

                    }

                },
                complete: function () {
                },
                error: function (e) {
                    console.log(e);
                }
            });



        }

        return {
            init: initialize
        }

    }());
    return requestModal.init();
}());

