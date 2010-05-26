<div class="loginFPErrors">[[+loginfp.errors]]</div>
<div class="loginFP">
    <form class="loginFPForm" action="[[~[[*id]]]]" method="post">
        <fieldset class="loginFPFieldset">
            <legend class="loginFPLegend">[[%login.forgot_password]]</legend>
            <label class="loginFPUsernameLabel">[[%login.username]]
                <input class="loginFPUsername" type="text" name="username" value="[[+loginfp.post.username]]" />
            </label>
            
            <p>[[%login.or_forgot_username]]</p>
            
            <label class="loginFPEmailLabel">[[%login.email]]
                <input class="loginFPEmail" type="text" name="email" value="[[+loginfp.post.email]]" />
            </label>
            
            <input class="returnUrl" type="hidden" name="returnUrl" value="[[+loginfp.request_uri]]" />
            
            <input class="loginFPService" type="hidden" name="login_fp_service" value="forgotpassword" />
            <span class="loginFPButton"><input type="submit" name="login_fp" value="[[%login.reset_password]]" /></span>
        </fieldset>
    </form>
</div>