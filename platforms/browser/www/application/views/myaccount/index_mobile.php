<template>
    <div class="page" data-name="myaccount">
        <div class="navbar">
            <div class="navbar-inner sliding">
                <div class="title">My Account</div>
            </div>
        </div>
        <div class="page-content">
            <div class="list">
                <ul>
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-title">E-mail Address : <?=$user_info['user_id']?></div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="block">
                <div class="row">
                    <button class="col button button-fill color-gray" @click="confirmLogOut">Log Out</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    var handler = {
        /*data: function() {
            var data = {
                dd: function() {
                    var data = this.$route.context.sites;

                    return data;
                }
            };

            return data;
        },*/
        methods: {
            confirmLogOut: function () {
                var app = this.$app;

                app.dialog.confirm('Are you sure you want to log out?', 'Confirm', function(){
                    app.preloader.show();
                    window.location.href = '/sign/out';
                });
            }
        }
    };

    return handler;
</script>