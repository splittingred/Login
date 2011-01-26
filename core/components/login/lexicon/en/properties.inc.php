<?php
/**
 * @package login
 * @subpackage lexicon
 */
/* ForgotPassword snippet */
$_lang['prop_forgotpassword.emailtpl_desc'] = 'The confirmation email message tpl.';
$_lang['prop_forgotpassword.emailtpltype_desc'] = 'The type of tpl being provided for the emailTpl property. Defaults to a Chunk.';
$_lang['prop_forgotpassword.senttpl_desc'] = 'The message tpl to show when an email was successfully sent.';
$_lang['prop_forgotpassword.senttpltype_desc'] = 'The type of tpl being provided for the sentTpl property. Defaults to a Chunk.';
$_lang['prop_forgotpassword.tpl_desc'] = 'The forgot password form tpl.';
$_lang['prop_forgotpassword.tpltype_desc'] = 'The type of tpl being provided for the tpl property. Defaults to a Chunk.';
$_lang['prop_forgotpassword.emailsubject_desc'] = 'The subject of the forgot password email.';
$_lang['prop_forgotpassword.resetresourceid_desc'] = 'The resource to direct users to in the confirmation email, where the ResetPassword snippet call is.';

/* Login snippet */
$_lang['prop_login.actionkey_desc'] = 'The REQUEST variable that indicates what action to take.';
$_lang['prop_login.loginkey_desc'] = 'The login action key.';
$_lang['prop_login.logoutkey_desc'] = 'The logout action key.';
$_lang['prop_login.tpltype_desc'] = 'The type of tpls being provided for the login and logout forms.';
$_lang['prop_login.logintpl_desc'] = 'The login form tpl.';
$_lang['prop_login.logouttpl_desc'] = 'The logout tpl.';
$_lang['prop_login.prehooks_desc'] = 'What scripts to fire, if any, before the user is logged in or out. This can be a comma-separated list of hooks, and if the first fails, the proceeding ones will not fire. A hook can also be a Snippet name that will execute that Snippet.';
$_lang['prop_login.posthooks_desc'] = 'What scripts to fire, if any, after the user has been logged in or out. This can be a comma-separated list of hooks, and if the first fails, the proceeding ones will not fire. A hook can also be a Snippet name that will execute that Snippet.';
$_lang['prop_login.errtpl_desc'] = 'The error tpl.';
$_lang['prop_login.errtpltype_desc'] = 'The type of error tpl.';
$_lang['prop_login.loginresourceid_desc'] = 'The resource to direct users to on successful login. 0 will redirect to self.';
$_lang['prop_login.loginresourceparams_desc'] = 'A JSON object of parameters to append to the login redirection URL. Ex: {"test":123}';
$_lang['prop_login.logoutresourceid_desc'] = 'Resource ID to redirect to on successful logout. 0 will redirect to self.';
$_lang['prop_login.logoutresourceparams_desc'] = 'A JSON object of parameters to append to the logout redirection URL. Ex: {"test":123}';
$_lang['prop_login.loginmsg_desc'] = 'Optional label message for login action. If blank, will default to lexicon string for Login.';
$_lang['prop_login.logoutmsg_desc'] = 'Optional label message for logout action. If blank, will default to lexicon string for Logout.';
$_lang['prop_login.redirecttoprior_desc'] = 'If true, will redirect to the referring page (HTTP_REFERER) on successful login.';
$_lang['prop_login.rememberme_desc'] = 'Optional. The field name of the Remember Me toggle to preserve login state. Defaults to `rememberme`.';
$_lang['prop_login.contexts_desc'] = '(Experimental) A comma-separated list of contexts to log in to. Defaults to the current context if not explicitly set.';
$_lang['prop_login.toplaceholder_desc'] = 'If set, will set the output of the login snippet to a placeholder of this name rather than directly outputting the return contents.';

/* Profile snippet */
$_lang['prop_profile.prefix_desc'] = 'A string to prefix all placeholders for fields that will be set by this Snippet.';
$_lang['prop_profile.user_desc'] = 'Optional. Either a user ID or username. If set, will use this user rather than the currently logged in one.';
$_lang['prop_profile.useextended_desc'] = 'Whether or not to set any extra fields in the form to the Profiles extended field. This can be useful for storing extra user fields.';

/* Register snippet */
$_lang['prop_register.submitvar_desc'] = 'The var to check for to load the Register functionality. If empty or set to false, Register will process the form on all POST requests.';
$_lang['prop_register.usergroups_desc'] = 'Optional. A comma-separated list of User Group names or IDs to add the newly-registered User to.';
$_lang['prop_register.submittedresourceid_desc'] = 'If set, will redirect to the specified Resource after the User submits the registration form.';
$_lang['prop_register.usernamefield_desc'] = 'The name of the field to use for the new User&apos;s username.';
$_lang['prop_register.passwordfield_desc'] = 'The name of the field to use for the new User&apos;s password.';
$_lang['prop_register.emailfield_desc'] = 'The name of the field to use for the new User&apos;s email address.';
$_lang['prop_register.successmsg_desc'] = 'Optional. If not redirecting using the submittedResourceId parameter, will display this message instead.';
$_lang['prop_register.persistparams_desc'] = 'Optional. A JSON object of parameters to persist across the register process. Useful when using redirect on ConfirmRegister to redirect to another page (eg, for shopping carts).';
$_lang['prop_register.prehooks_desc'] = 'What scripts to fire, if any, before the form passes validation. This can be a comma-separated list of hooks, and if the first fails, the proceeding ones will not fire. A hook can also be a Snippet name that will execute that Snippet.';
$_lang['prop_register.posthooks_desc'] = 'What scripts to fire, if any, after the user has been registered. This can be a comma-separated list of hooks, and if the first fails, the proceeding ones will not fire. A hook can also be a Snippet name that will execute that Snippet.';
$_lang['prop_register.useextended_desc'] = 'Whether or not to set any extra fields in the form to the Profiles extended field. This can be useful for storing extra user fields.';
$_lang['prop_register.excludeextended_desc'] = 'A comma-separated list of fields to exclude from setting as extended fields.';
$_lang['prop_register.activation_desc'] = 'Whether or not to require activation for proper registration. If true, users will not be marked active until they have activated their account. Defaults to true. Will only work if the registration form passes an email field.';
$_lang['prop_register.activationttl_desc'] = 'Number of minutes until the activation email expires. Defaults to 3 hours.';
$_lang['prop_register.activationresourceid_desc'] = 'The Resource ID where the ConfirmRegister snippet for activation is located.';
$_lang['prop_register.activationemail_desc'] = 'If set, will sent activation emails to this address instead of the newly registered user&apos;s address.';
$_lang['prop_register.activationemailsubject_desc'] = 'The subject of the activation email.';
$_lang['prop_register.activationemailtpltype_desc'] = 'The type of tpls being provided for the activation email.';
$_lang['prop_register.activationemailtpl_desc'] = 'The activation email tpl.';
$_lang['prop_register.moderatedresourceid_desc'] = 'If a prehook sets the user as moderated, then send to this Resource instead of the submittedResourceId. Leave blank to bypass.';
$_lang['prop_register.placeholderprefix_desc'] = 'The prefix to use for all placeholders set by this snippet.';
$_lang['prop_register.recaptchaheight_desc'] = 'If `recaptcha` is set as a preHook, this will select the height for the reCaptcha widget.';
$_lang['prop_register.recaptchatheme_desc'] = 'If `recaptcha` is set as a preHook, this will select a theme for the reCaptcha widget.';
$_lang['prop_register.recaptchawidth_desc'] = 'If `recaptcha` is set as a preHook, this will set the width for the reCaptcha widget.';
$_lang['prop_register.mathminrange_desc'] = 'If `math` is set as a preHook, the minimum range for each number in the equation.';
$_lang['prop_register.mathmaxrange_desc'] = 'If `math` is set as a preHook, the maximum range for each number in the equation.';
$_lang['prop_register.mathfield_desc'] = 'If `math` is set as a preHook, the name of the input field for the answer.';
$_lang['prop_register.mathop1field_desc'] = 'If `math` is set as a preHook, the name of the field for the 1st number in the equation.';
$_lang['prop_register.mathop2field_desc'] = 'If `math` is set as a preHook, the name of the field for the 2nd number in the equation.';
$_lang['prop_register.mathoperatorfield_desc'] = 'If `math` is set as a preHook, the name of the field for the operator in the equation.';
$_lang['opt_register.chunk'] = 'Chunk';
$_lang['opt_register.file'] = 'File';
$_lang['opt_register.inline'] = 'Inline';
$_lang['opt_register.embedded'] = 'Embedded';
$_lang['opt_register.blackglass'] = 'Black Glass';
$_lang['opt_register.clean'] = 'Clean';
$_lang['opt_register.red'] = 'Red';
$_lang['opt_register.white'] = 'White';

/* ConfirmRegister snippet */
$_lang['prop_confirmregister.redirectto_desc'] = 'Optional. After a successful confirmation, redirect to this Resource.';
$_lang['prop_confirmregister.redirectparams_desc'] = 'Optional. A JSON object of parameters to pass when redirecting using redirectTo.';
$_lang['prop_confirmregister.authenticate_desc'] = 'Authenticate and login the user to the current context after confirming registration. Defaults to true.';
$_lang['prop_confirmregister.authenticatecontexts_desc'] = 'Optional. A comma-separated list of contexts to authenticate to. Defaults to the current context.';

/* ResetPassword snippet */
$_lang['prop_resetpassword.tpl_desc'] = 'The reset password message tpl.';
$_lang['prop_resetpassword.tpltype_desc'] = 'The type of tpl being provided. Defaults to a Chunk.';
$_lang['prop_resetpassword.loginresourceid_desc'] = 'The resource to direct users to on successful confirmation.';

/* UpdateProfile snippet */
$_lang['prop_updateprofile.submitvar_desc'] = 'The var to check for to load the UpdateProfile functionality. If empty or set to false, UpdateProfile will process the form on all POST requests.';
$_lang['prop_updateprofile.redirecttologin_desc'] = 'If a user is not logged in and accesses this Resource, redirect them to the Unauthorized Page.';
$_lang['prop_updateprofile.reloadonsuccess_desc'] = 'If true, the page will redirect to itself with a GET parameter to prevent double-postbacks. If false, it will simply set a success placeholder.';
$_lang['prop_updateprofile.syncusername_desc'] = 'If set to a column name in the Profile, UpdateProfile will attempt to sync the username to this field after a successful save.';
$_lang['prop_updateprofile.emailfield_desc'] = 'The field name for the email field in the form.';
$_lang['prop_updateprofile.useextended_desc'] = 'Whether or not to set any extra fields in the form to the Profiles extended field. This can be useful for storing extra user fields.';
$_lang['prop_updateprofile.excludeextended_desc'] = 'A comma-separated list of fields to exclude from setting as extended fields.';
$_lang['prop_updateprofile.prehooks_desc'] = 'What scripts to fire, if any, before the form passes validation. This can be a comma-separated list of hooks, and if the first fails, the proceeding ones will not fire. A hook can also be a Snippet name that will execute that Snippet.';
$_lang['prop_updateprofile.posthooks_desc'] = 'What scripts to fire, if any, after the user has been registered. This can be a comma-separated list of hooks, and if the first fails, the proceeding ones will not fire. A hook can also be a Snippet name that will execute that Snippet.';
$_lang['prop_updateprofile.placeholderprefix_desc'] = 'The prefix to use for all placeholders set by this snippet.';

/* ChangePassword snippet */
$_lang['prop_changepassword.submitvar_desc'] = 'The var to check for to load the ChangePassword functionality. If empty or set to false, ChangePassword will process the form on all POST requests.';
$_lang['prop_changepassword.fieldoldpassword_desc'] = 'The field name of the old password field.';
$_lang['prop_changepassword.fieldnewpassword_desc'] = 'The field name of the new password field.';
$_lang['prop_changepassword.fieldconfirmnewpassword_desc'] = 'Optional. If set, the field name of the confirm password field, and will be checked against the new password field during submission.';
$_lang['prop_changepassword.prehooks_desc'] = 'What scripts to fire, if any, after the form passes validation but before save. This can be a comma-separated list of hooks, and if the first fails, the proceeding ones will not fire. A hook can also be a Snippet name that will execute that Snippet.';
$_lang['prop_changepassword.posthooks_desc'] = 'What scripts to fire, if any, after the user has been registered. This can be a comma-separated list of hooks, and if the first fails, the proceeding ones will not fire. A hook can also be a Snippet name that will execute that Snippet.';
$_lang['prop_changepassword.reloadonsuccess_desc'] = 'If true, the page will redirect to itself with a GET parameter to prevent double-postbacks. If false, it will simply set a success placeholder.';
$_lang['prop_changepassword.successmessage_desc'] = 'If reloadOnSuccess is set to false, output this message in the [prefix].successMessage placeholder.';
$_lang['prop_changepassword.placeholderprefix_desc'] = 'The prefix to use for all placeholders set by this snippet.';

/* isLoggedIn snippet */
$_lang['prop_isloggedin.contexts_desc'] = 'A comma-separated list of contexts to check authenticated status for. If not set, will default to current context.';
$_lang['prop_isloggedin.redirectto_desc'] = 'The ID of a Resource to redirect to should the user not be logged in. Will default to the unauthorized_page.';
$_lang['prop_isloggedin.redirectparams_desc'] = 'If using redirectTo, a JSON object of REQUEST params to send with the redirect.';