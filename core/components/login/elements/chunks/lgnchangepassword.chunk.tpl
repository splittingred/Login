[[!+logcp.successMessage:notempty=`<p style="color: red;">[[+logcp.successMessage]]</p>`]]

<form class="form inline" action="[[~[[*id]]]]" method="post">
    <input type="hidden" name="nospam:blank" value="" />

    <div class="ff">
        <label for="password_old">[[!%login.password_old? &namespace=`login` &topic=`changepassword`]]
            <span class="error">[[+logcp.error.password_old]]</span>
        </label>
        <input type="password" name="password_old:required" id="password_old" value="[[+logcp.password_old]]" />
    </div>

    <div class="ff">
        <label for="password_new">[[!%login.password_new]]
            <span class="error">[[+logcp.error.password_new]]</span>
        </label>
        <input type="password" name="password_new:required" id="password_new" value="[[+logcp.password_new]]" />
    </div>

    <div class="ff">
        <label for="password_new_confirm">[[!%login.password_new_confirm]]
            <span class="error">[[+logcp.error.password_new_confirm]]</span>
        </label>
        <input type="password" name="password_new_confirm:required" id="password_new_confirm" value="[[+logcp.password_new_confirm]]" />
    </div>

    <br class="clear" />

    <div class="form-buttons">
        <input type="submit" name="logcp-submit" value="[[!%login.change_password]]" />
    </div>
</form>