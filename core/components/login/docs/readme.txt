--------------------
Snippet: Login
--------------------
Version: 1.0
Since: June 25, 2009
Author: Jason Coward <jason@collabpad.com>
        Shaun McCormick <shaun@collabpad.com>

This component loads a simple login and logout form. It also comes packaged
with ForgotPassword and ResetPassword snippets, which allow the user to put
in their username or email to receive a confirmation email which will reset
their password. 

Example for Login:
[[!Login]]


To use the password retrieval functionality, first create the Resource the
user will log in to should they click on the confirmation email, and put
the Reset Password snippet in. Tell it what Resource the Login snippet is
in - or where you'd like it to provide a link back to:

[[ResetPassword? &loginResourceId=`72`]]  

Then create another resource with the Forgot Password snippet, and tell it
what Resource the Reset snippet is in:

[[!ForgotPassword? &resetResourceId=`123`]]

And you're done!


Thanks,
Jason Coward & Shaun McCormick
MODx Foundation