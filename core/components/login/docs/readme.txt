--------------------
Snippet: Login
--------------------
Version: 1.7
Since: June 21, 2010
Author: Jason Coward <jason@modx.com>
        Shaun McCormick <shaun+login@modx.com>

This component loads a simple login and logout form. It also comes packaged
with ForgotPassword and ResetPassword snippets, which allow the user to put
in their username or email to receive a confirmation email which will reset
their password. 

Example for Login:
[[!Login]]

You can also specify the template:

[[!Login? &tpl=`myLoginChunk`]]

See the snippet properties for more options.

--------------
ForgotPassword
--------------

To use the password retrieval functionality, first create the Resource the
user will log in to should they click on the confirmation email, and put
the Reset Password snippet in. Tell it what Resource the Login snippet is
in - or where you'd like it to provide a link back to:

[[ResetPassword? &loginResourceId=`72`]]  

Then create another resource with the Forgot Password snippet, and tell it
what Resource the Reset snippet is in:

[[!ForgotPassword? &resetResourceId=`123`]]

--------
Register
--------
To use the Register snippet, simply place the Snippet in the Resource where
your HTML register form is. (A default one called lgnRegisterFormTpl has
been provided.). This snippet also requires Activation by the User, so they
will get an email in their inbox regarding their signup.

In your form field names, you can use validation filters to validate your
fields. They are separated with the colon : symbol. Example:

<input type="password" name="password:required:minLength=6" id="password"
 value="[[+password]]" />

will require that the password field not be empty, and have a minimum
length of 6 chars. You can encapsulate validator params (6 here) with ` if
the param has spaces in the name. The default validators provided are:

required
blank
email
password_confirm=`nameOfPasswordField`
minLength=`123`
maxLength=`123`
minValue=`123`
maxValue=`123`

You can also do custom validators by creating a Snippet and using that as
the validator name. Example: We create a Snippet called 'equalTo' and
on our field, we set:

<input type="text" name="field:equalTo=`123`" id="field" />

Now, in our snippet, our code would look like so:

<?php
if ($scriptProperties['value'] !== $scriptProperties['param']) {
    return 'Value not equal to: '.$scriptProperties['param'];
}
return true;
?>

Returning true will make the field valid. Any other return value will
be the error message. Snippets get passed the following parameters:

- key: The name of the field.
- value: The value of the field.
- param: The parameter, if applicable, passed to the validator.
- type: The name of the validator.
- validator: A reference to the lgnValidator instance.

See the Snippet Properties for more options.

Thanks,
Jason Coward & Shaun McCormick
MODX, LLC