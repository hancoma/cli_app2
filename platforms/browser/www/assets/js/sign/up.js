(function(){
    var validateForm = (function(d) {
        var form = d.getElementById('signup_form'),
            button = d.getElementById('btn_signup'),
            name = d.getElementById('inputName'),
            email = d.getElementById('inputEmail'),
            password = d.getElementById('inputPassword'),
            repeat = d.getElementById('inputRepeat'),
            policy1 = d.getElementById('signup_policy1'),
            policy2 = d.getElementById('signup_policy2'),
            coupon = d.getElementById('couponButton'),
            couponClose = d.getElementById('couponCloseButton'),
            couponForm = d.getElementById('form_coupon');

        function initialize() {
            coupon.addEventListener('click', couponButtonClickHandler);
            couponClose.addEventListener('click', couponCloseButtonClickHandler);
            form.addEventListener('keyup', inputKeyUpHandler);
            form.addEventListener('click', formClickHandler);
            button.addEventListener('click', signButtonClickHandler);
        }

        function couponButtonClickHandler() {
            coupon.style.display = 'none';
            couponForm.style.display = 'block';
        }

        function couponCloseButtonClickHandler() {
            couponForm.style.display = 'none';
            coupon.style.display = 'block';
        }

        function inputKeyUpHandler(e) {
            var target = e.target,
                nodeName = target.nodeName.toLowerCase();

            nodeName === 'input' && !function(){
                var tipId = target.getAttribute('aria-describedby'),
                    tipElement = document.getElementById(tipId);

                tipElement !== null && !function(){
                    $(target).parent().removeClass("has-error");
                    $(target).siblings("span").removeClass("fa-warning");

                    tipElement.parentNode.removeChild(tipElement);
                    tooltip.remove(target);
                }();
            }();
        }

        function formClickHandler(e) {
            var target = e.target,
                nodeName = target.nodeName.toLowerCase();

            target.className === 'popover-content' && !function(){
                var parent = target.parentElement,
                    popover = document.getElementsByClassName('popover');

                for(var i = 0, length = popover.length; i < length; i++) {
                    popover[i].style.zIndex = '1060';
                }

                parent.style.zIndex = '1070';
            }();
        }

        function signButtonClickHandler() {
            if(name.value === '') {
                tooltip.make(name.parentElement, name, 'right', c2ms('in_input_empty_1'), true);
            } else if(!verifyName(name.value)) {
                tooltip.make(name.parentElement, name, 'right', c2ms('in_input_name_verify'), true);
            } else {
                tooltip.remove(name);
            }

            if(email.value === '') {
                tooltip.make(email.parentElement, email, 'right', c2ms('in_input_empty_2'), true);
            } else if(!verifyEmail(email.value)) {
                tooltip.make(email.parentElement, email, 'right', c2ms('in_input_empty_5'), true);
            } else {
                tooltip.remove(email);
            }

            if(password.value === '') {
                tooltip.make(password.parentElement, password, 'right', c2ms('in_input_empty_3'), true);
            } else if(!verifyPassword(password.value)) {
                tooltip.make(password.parentElement, password, 'right', c2ms('in_input_empty_7'), true);
            } else {
                tooltip.remove(password);
            }

            if(repeat.value === '') {
                tooltip.make(repeat.parentElement, repeat, 'right', c2ms('in_input_empty_4'), true);
            } else if(password.value !== repeat.value) {
                tooltip.make(repeat.parentElement, repeat, 'right', c2ms('in_input_empty_8'), true);
            } else {
                tooltip.remove(repeat);
            }

            if(!policy1.checked) {
                tooltip.make(policy1.parentElement, policy1, 'right', c2ms('in_input_empty_9'), true);
            } else {
                tooltip.remove(policy1);
            }

            if(!policy2.checked) {
                tooltip.make(policy2.parentElement, policy2, 'right', c2ms('in_input_empty_9'), true);
            } else {
                tooltip.remove(policy2);
            }
        }

        return {
            init: initialize
        };
    }(document));

    var tooltip = (function($){
        function setPopover(wrap, el, position, text, visible) {
            var popover = $(el).popover({
                container: wrap,
                placement: position,
                content: text,
                trigger: 'manual'
            });

            visible && popover.popover("show");
        }

        function destroyPopover(el) {
            $(el).popover('destroy');
        }

        return {
            make: setPopover,
            remove: destroyPopover
        };
    }(jQuery));

    validateForm.init();
}());