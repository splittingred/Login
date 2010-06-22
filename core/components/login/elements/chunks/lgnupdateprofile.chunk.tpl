<div class="update-profile">
    <div class="updprof-error">[[+error.message]]</div>
    [[+login.update_success:if=`[[+login.update_success]]`:is=`1`:then=`[[%login.profile_updated? &namespace=`login` &topic=`updateprofile`]]`]]

    <form class="form" action="[[~[[*id]]]]" method="post">
        <input type="hidden" name="nospam:blank" value="" />

        <label for="fullname">[[!%login.fullname? &namespace=`login` &topic=`updateprofile`]]
            <span class="error">[[+error.fullname]]</span>
        </label>
        <input type="text" name="fullname" id="fullname" value="[[+fullname]]" />

        <label for="email">[[!%login.email]]
            <span class="error">[[+error.email]]</span>
        </label>
        <input type="text" name="email:required:email" id="email" value="[[+email]]" />

        <label for="phone">[[!%login.phone]]
            <span class="error">[[+error.phone]]</span>
        </label>
        <input type="text" name="phone" id="phone" value="[[+phone]]" />

        <label for="mobilephone">[[!%login.mobilephone]]
            <span class="error">[[+error.mobilephone]]</span>
        </label>
        <input type="text" name="mobilephone" id="mobilephone" value="[[+mobilephone]]" />

        <label for="fax">[[!%login.fax]]
            <span class="error">[[+error.fax]]</span>
        </label>
        <input type="text" name="fax" id="fax" value="[[+fax]]" />

        <label for="address">[[!%login.address]]
            <span class="error">[[+error.address]]</span>
        </label>
        <input type="text" name="address" id="address" value="[[+address]]" />

        <label for="country">[[!%login.country]]
            <span class="error">[[+error.country]]</span>
        </label>
        <input type="text" name="country" id="country" value="[[+country]]" />

        <label for="city">[[!%login.city]]
            <span class="error">[[+error.city]]</span>
        </label>
        <input type="text" name="city" id="city" value="[[+city]]" />

        <label for="state">[[!%login.state]]
            <span class="error">[[+error.state]]</span>
        </label>
        <input type="text" name="state" id="state" value="[[+state]]" />

        <label for="zip">[[!%login.zip]]
            <span class="error">[[+error.zip]]</span>
        </label>
        <input type="text" name="zip" id="zip" value="[[+zip]]" />

        <label for="website">[[!%login.website]]
            <span class="error">[[+error.website]]</span>
        </label>
        <input type="text" name="website" id="website" value="[[+website]]" />

        <br class="clear" />

        <div class="form-buttons">
            <input type="submit" name="login-updprof-btn" value="[[!%login.update_profile]]" />
        </div>
    </form>
</div>