<?php
/**
 * @package login
 * @subpackage lexicon
 */
/* ForgotPassword snippet */
$_lang['prop_forgotpassword.emailtpl_desc'] = 'Das Template für die Bestätigungsemail.';
$_lang['prop_forgotpassword.emailtpltype_desc'] = 'Templatetyp, der für die emailTpl-Eigenschaft zur Verfügung steht. Standard ist ein Chunk.';
$_lang['prop_forgotpassword.senttpl_desc'] = 'Das Nachrichtentemplate für eine erfolgreich verschickte E-mail.';
$_lang['prop_forgotpassword.senttpltype_desc'] = 'Templatetyp, der für die sendTpl-Eigenschaft zur Verfügung steht. Standard ist ein Chunk.';
$_lang['prop_forgotpassword.tpl_desc'] = 'Das Passwort-vergessen-Template.';
$_lang['prop_forgotpassword.tpltype_desc'] = 'Templatetyp, der für die tpl-Eigenschaft zur Verfügung steht. Standard ist ein Chunk';
$_lang['prop_forgotpassword.emailsubject_desc'] = 'Der Betreff für die Passwort-vergessen E-Mail.';
$_lang['prop_forgotpassword.resetresourceid_desc'] = 'Die Ressource-ID zu der Benutzer aus der Aktivierungsmail gleitet werden sollen, in der auch der ResetPassword Snippet Aufruf steht.';

/* Login snippet */
$_lang['prop_login.actionkey_desc'] = 'Die REQUEST Variable die indiziert, welche Aktion ausgeführt werden soll.';
$_lang['prop_login.loginkey_desc'] = 'Der login-Aktionsschlüssel.';
$_lang['prop_login.logoutkey_desc'] = 'Der logout-Aktionsschlüssel.';
$_lang['prop_login.tpltype_desc'] = 'Templatetyp für die login und logout Formulare.';
$_lang['prop_login.logintpl_desc'] = 'Das login Formulartemplate.';
$_lang['prop_login.logouttpl_desc'] = 'Das logout Formulartemplate.';
$_lang['prop_login.prehooks_desc'] = 'Scripts, die vor einem Benutzerlogin/-logout ausgeführt werden sollen. Es kann eine kommaseparierte Liste von Hooks angegeben werden. Sollte eine nicht vailidieren, werden weitere nicht ausgeführt. Ein Hook kann auch ein Snippetname sein, welches dann ausgeführt wird.';
$_lang['prop_login.posthooks_desc'] = 'Scripts, die nach einem Benutzerlogin/-logout ausgeführt werden sollen. Es kann eine kommaseparierte Liste von Hooks angegeben werden. Sollte eine nicht vailidieren, werden weitere nicht ausgeführt. Ein Hook kann auch ein Snippetname sein, welches dann ausgeführt wird.';
$_lang['prop_login.errtpl_desc'] = 'Das Fehlertemplate.';
$_lang['prop_login.errtpltype_desc'] = 'Der Fehlertemplatetyp.';
$_lang['prop_login.loginresourceid_desc'] = 'Eine Ressource, zu der Benutzer nach einem erfolgreichen Login weitergeleitet werden. 0 leitet auf die gleiche Seite zurück.';
$_lang['prop_login.loginresourceparams_desc'] = 'Ein JSON Objekt von Parametern, die zur login redirection URL angehängt werden. Bsp: {"test":123}';
$_lang['prop_login.logoutresourceid_desc'] = 'Ressource-ID zu der nach einem erfolgreichen Logout geleitet wird. 0 leitet auf die gleiche Seite zurück.';
$_lang['prop_login.logoutresourceparams_desc'] = 'Ein JSON Objekt von Parametern, die zur logout redirection URL angehängt werden. Ex: {"test":123}';
$_lang['prop_login.loginmsg_desc'] = 'Optionale label Nachricht für die login Aktion. Falls leer, wird der Standardstring des Lexicons verwendet.';
$_lang['prop_login.logoutmsg_desc'] = 'Optionale label Nachricht für die logout Aktion. Falls leer, wird der Standardstring des Lexicons verwendet.';
$_lang['prop_login.redirecttoprior_desc'] = 'Falls aktiv, wird der Nutzer nach erfolgreichem Login zur Referrer-Seite geleitet (HTTP_REFERER).';
$_lang['prop_login.rememberme_desc'] = 'Optional. Dieses Feld kann genutzt werden, um einen Loginstatus zu erhalten. Standard ist `merken`.';
$_lang['prop_login.contexts_desc'] = '(Experimentell) Eine kommaseparierte Liste von Kontexten, für die der Login gilt. Standardmäßig ist nur der aktuelle Kontext eingestellt.';
$_lang['prop_login.toplaceholder_desc'] = 'Falls gesetzt, wird die Ausgabe des login Snippets an einen Platzhalter dieses Namens übergeben statt die Inhalte direkt auszugeben.';

/* Profile snippet */
$_lang['prop_profile.prefix_desc'] = 'Ein vorangestellter String aller Platzhalter für Felder, die von diesem Snippet gesetzt werden.';
$_lang['prop_profile.user_desc'] = 'Optional. Entweder eine Benutzer-ID oder ein Benutzername. Falls gesetzt, wird dieser Wert genutzt statt der des aktuell eingeloggten Nutzers.';
$_lang['prop_profile.useextended_desc'] = 'Legt fest, ob erweiterte Felder zum Profile-extended-field benutzt werden sollen. Dies kann für erweiterte Benutzerfelder genutzt werden.';

/* Register snippet */
$_lang['prop_register.submitvar_desc'] = 'Die Prüfvariable für die Registrierungsfunktionalität. Falls leer oder nicht gesetzt, wird das Formular alle POST-Variablen im Formular verwenden.';
$_lang['prop_register.usergroups_desc'] = 'Optional. Eine kommaseparierte Liste von Benutzergruppennamen oder IDs um neu registrierte Benutzer zu diesen hinzuzufügen.';
$_lang['prop_register.submittedresourceid_desc'] = 'Falls gesetzt, wird der Nutzer zu dieser Ressource nach dem Absenden des Registrierungsformulars weitergeleitet.';
$_lang['prop_register.usernamefield_desc'] = 'Der Name des Feldes für den neuen Benutzernamen.';
$_lang['prop_register.passwordfield_desc'] = 'Der Name des Feldes für das Passwort des neuen Benutzers.';
$_lang['prop_register.emailfield_desc'] = 'Der Name des Feldes für die E-mail Adresse des neuen Benutzers.';
$_lang['prop_register.successmsg_desc'] = 'Optional. Falls Sie nicht mittels des submittedResourceId Parameters weiterleiten, wird diese Nachricht angezeigt.';
$_lang['prop_register.persistparams_desc'] = 'Optional. Ein JSON Objekt an Parametern, die über den Registrierungsprozess hinaus gespeichert werden sollen. Dies ist nütlich, wenn Sie die ConfirmRegister-Weiterleitung nutzen um auf eine andere Seite zu leiten (z.B. Einkaufswägen).';
$_lang['prop_register.prehooks_desc'] = 'Skripte, die vor einer Validation ausgeführt werden sollen. Dies kann eine kommaseparierte Liste von Hooks sein. Sollte eine nicht vailidieren, werden weitere nicht ausgeführt. Ein Hook kann auch ein Snippetname sein, welches dann ausgeführt wird.';
$_lang['prop_register.posthooks_desc'] = 'Skripte, die nach der Registrierung des Nutzers ausgeführt werden sollen. Dies kann eine kommaseparierte Liste von Hooks sein. Sollte eine nicht vailidieren, werden weitere nicht ausgeführt. Ein Hook kann auch ein Snippetname sein, welches dann ausgeführt wird.';
$_lang['prop_register.useextended_desc'] = 'Legt fest, ob erweiterte Felder zum Profile-extended-field benutzt werden sollen. Dies kann für erweiterte Benutzerfelder genutzt werden.';
$_lang['prop_register.excludeextended_desc'] = 'Eine kommaseparaierte Liste von Feldern, die nicht als extended-fields gekennzeichnet werden sollen.';
$_lang['prop_register.activation_desc'] = 'Legt fest, ob eine Aktivierung für eine Registrierung benötigt wird oder nicht. Falls ja, wirde der Benutzer nicht als aktiv gekennzeichnet, bis er seinen Account aktiviert hat. Standard ist ja. Funktioniert nur, wenn der Registreirungprozess ein E-Mail-Feld übergibt.';
$_lang['prop_register.activationttl_desc'] = 'Zeit in Minuten, nach der die Aktivierungsmail ungültig wird. Standard ist 3 Stunden.';
$_lang['prop_register.activationresourceid_desc'] = 'Due Ressource ID, in der das ConfirmRegister Snippet für die Aktivierung steht.';
$_lang['prop_register.activationemail_desc'] = 'Falls gesetzt, wird die Aktivierungsmail an diese E-Mail Adresse gesendet, statt an die des neu registrierten Benutzers.';
$_lang['prop_register.activationemailsubject_desc'] = 'Der Betreff der Aktivierungsmail.';
$_lang['prop_register.activationemailtpltype_desc'] = 'Der Templatetyp für die Aktivierungsmail.';
$_lang['prop_register.activationemailtpl_desc'] = 'Das Aktivierungsmailtemplate.';
$_lang['prop_register.moderatedresourceid_desc'] = 'Wenn ein prehook den Benutzer als zu moderieren kennzeichnet, leite auf diese Ressource weiter, statt zur submittedResourceId. Lassen Sie dieses Feld leer, um diese Funktion nicht zu nutzen.';
$_lang['prop_register.placeholderprefix_desc'] = 'Das prefix, das sie für alle Platzhalter dieses Snippets nutzen.';
$_lang['prop_register.recaptchaheight_desc'] = 'Falls `recaptcha` als preHook gesetzt ist, setzt dies die Höhe des reCaptcha Widgets.';
$_lang['prop_register.recaptchatheme_desc'] = 'Falls `recaptcha` als preHook gesetzt ist, setzt dies das Thema des reCaptcha Widgets.';
$_lang['prop_register.recaptchawidth_desc'] = 'Falls `recaptcha` als preHook gesetzt ist, setzt dies die Breite des reCaptcha Widgets.';
$_lang['prop_register.mathminrange_desc'] = 'Falls `math` als preHook gesetzt ist, geben Sie die minimalen Zahlenstellen für jede Nummer der Gleichung an.';
$_lang['prop_register.mathmaxrange_desc'] = 'Falls `math` als preHook gesetzt ist, geben Sie die maximalen Zahlenstellen für jede Nummer der Gleichung an.';
$_lang['prop_register.mathfield_desc'] = 'Falls `math` als preHook gesetzt ist, geben Sie den Namen des Antwort-input Felds an.';
$_lang['prop_register.mathop1field_desc'] = 'Falls `math` als preHook gesetzt ist, geben Sie den Namen des Feldes für die erste Zahl der Gleichung an.';
$_lang['prop_register.mathop2field_desc'] = 'Falls `math` als preHook gesetzt ist, geben Sie den Namen des for Feldes für die zweite Zahl der Gleichung an.';
$_lang['prop_register.mathoperatorfield_desc'] = 'Falls `math` als preHook gesetzt ist, geben Sie den Namen des Feldes für den Operator der Gleichung an.';
$_lang['opt_register.chunk'] = 'Chunk';
$_lang['opt_register.file'] = 'Datei';
$_lang['opt_register.inline'] = 'Inline';
$_lang['opt_register.embedded'] = 'Eingebettet';
$_lang['opt_register.blackglass'] = 'Schwarzes Glass';
$_lang['opt_register.clean'] = 'Klar';
$_lang['opt_register.red'] = 'Rot';
$_lang['opt_register.white'] = 'Weiss';

/* ConfirmRegister snippet */
$_lang['prop_confirmregister.redirectto_desc'] = 'Optional. Leitet nach einer erfolgreichen Aktivierung auf die angegebene Ressource.';
$_lang['prop_confirmregister.redirectparams_desc'] = 'Optional. Ein JSON Objekt an Parametern, die bei der Weiterleitung mittels redirectTo übergeben werden.';
$_lang['prop_confirmregister.authenticate_desc'] = 'Authentifiziere und logge den Benutzer automatisch für den aktuellen Kontext nach der Registrierung. Standard ist aktiv.';
$_lang['prop_confirmregister.authenticatecontexts_desc'] = 'Optional. Eine kommaseparierte Liste von Kontexten, für die der Nutzer authentifiziert werden soll. Standard ist der aktuelle Kontext.';
$_lang['prop_confirmregister.errorpage_desc'] = 'Optional. Falls gesetzt, wird der Nutzer zu einer benutzerdefinierten Fehlerseite geleitet, wenn dieser versucht nach der Aktivierung diese Seite erneut aufzurufen.';

/* ResetPassword snippet */
$_lang['prop_resetpassword.tpl_desc'] = 'Das Passwort-vergessen-Nachricht-Template.';
$_lang['prop_resetpassword.tpltype_desc'] = 'Der Templatetyp, der genutzt werden soll. Standard ist Chunk.';
$_lang['prop_resetpassword.loginresourceid_desc'] = 'Die Ressource, zu der Nutzer nach einer erfolgreichen Bestätigung geleitet werden sollen.';

/* UpdateProfile snippet */
$_lang['prop_updateprofile.submitvar_desc'] = 'Die Prüfvariable für die UpdateProfile Funktionalität. Falls nicht gesetzt oder leer, wird UpdateProfile das Formular auf alle POST-Anfragen prüfen.';
$_lang['prop_updateprofile.redirecttologin_desc'] = 'Wenn ein Benutzer nicht eingeloggt ist und diese Ressource aufruft, leite an die Unauthorized Seite weiter.';
$_lang['prop_updateprofile.reloadonsuccess_desc'] = 'Falls gesetzt, leitet die Seite an sich selbst mit einem GET-Parameter zurück, um Doppelsendungen zu vermeiden. Falls nicht gesetzt, wird lediglich ein Erfolg-Platzhalter angezeigt.';
$_lang['prop_updateprofile.syncusername_desc'] = 'Falls auf einen Spaltennamen im Profil gesetzt, wird UpdateProfile versuchen, den Benutzernamen mit diesem Feld nach erfolgreichem Speichern zu synchronisieren.';
$_lang['prop_updateprofile.emailfield_desc'] = 'Der Feldname für das E-mail Feld im Formular.';
$_lang['prop_updateprofile.useextended_desc'] = 'Legt fest, ob erweiterte Felder zum Profile-extended-field benutzt werden sollen. Dies kann für erweiterte Benutzerfelder genutzt werden.';
$_lang['prop_updateprofile.excludeextended_desc'] = 'Eine kommaseparierte Liste von Feldern, die nicht als extended-fields übernommen werden sollen.';
$_lang['prop_updateprofile.prehooks_desc'] = 'Skripte, die vor der Validierung des Formulars ausgeführt werden sollen. Dies kann eine kommaseparierte Liste von Hooks sein. Sollte eine nicht vailidieren, werden weitere nicht ausgeführt. Ein Hook kann auch ein Snippetname sein, welches dann ausgeführt wird.';
$_lang['prop_updateprofile.posthooks_desc'] = 'Skripte, die nach der Validierung des Formulars ausgeführt werden sollen. Dies kann eine kommaseparierte Liste von Hooks sein. Sollte eine nicht vailidieren, werden weitere nicht ausgeführt. Ein Hook kann auch ein Snippetname sein, welches dann ausgeführt wird.';
$_lang['prop_updateprofile.placeholderprefix_desc'] = 'Das Prefix, das für alle Platzhalter des Snippets genutzt werden soll.';

/* ChangePassword snippet */
$_lang['prop_changepassword.submitvar_desc'] = 'Die Prüfvariable für die ChangePassword Funktionalität. Falls nicht gesetzt oder leer, wird ChangePassword alle POST-Inhalte des Formulars auswerten.';
$_lang['prop_changepassword.fieldoldpassword_desc'] = 'Der Name des alten Passwort Feldes.';
$_lang['prop_changepassword.fieldnewpassword_desc'] = 'Der Name des neuen Passwort Feldes.';
$_lang['prop_changepassword.fieldconfirmnewpassword_desc'] = 'Optional. Falls gesetzt, Name des Feldes für die Überprüfung des neuen Passworts.';
$_lang['prop_changepassword.prehooks_desc'] = 'Skripte, die nach der Validierung aber noch vor dem Speichern des Formulars ausgeführt werden sollen. Dies kann eine kommaseparierte Liste von Hooks sein. Sollte eine nicht vailidieren, werden weitere nicht ausgeführt. Ein Hook kann auch ein Snippetname sein, welches dann ausgeführt wird.';
$_lang['prop_changepassword.posthooks_desc'] = 'Skripte, die nach der Registrierung eines Benutzers ausgeführt werden sollen. Dies kann eine kommaseparierte Liste von Hooks sein. Sollte eine nicht vailidieren, werden weitere nicht ausgeführt. Ein Hook kann auch ein Snippetname sein, welches dann ausgeführt wird.';
$_lang['prop_changepassword.reloadonsuccess_desc'] = 'Falls gesetzt, leitet die Seite an sich selbst mit einem GET-Parameter zurück, um Doppelsendungen zu vermeiden. Falls nicht gesetzt, wird lediglich ein Erfolg-Platzhalter angezeigt.';
$_lang['prop_changepassword.successmessage_desc'] = 'Falls reloadOnSuccess als false gekennzeichnet ist, wird diese Nachricht im [prefix].successMessage Platzhalter ausgegeben.';
$_lang['prop_changepassword.placeholderprefix_desc'] = 'Das Prefix, das für alle Platzhalter des Snippets genutzt werden soll.';

/* isLoggedIn snippet */
$_lang['prop_isloggedin.contexts_desc'] = 'Eine kommaseparierte Liste von Kontexten, für die der Autehntifizierungsstatus geprüft werden soll. Standard ist der aktuelle Kontext.';
$_lang['prop_isloggedin.redirectto_desc'] = 'Die ID einer Ressource, auf die weitergeleitet wird, wenn der Nutzer nicht eingeloggt ist. Standard ist die unauthorized_page.';
$_lang['prop_isloggedin.redirectparams_desc'] = 'Falls redirectTo genutzt wird, kann ein JSON Objekt eines REQUEST Parameters mit der Weiterleitung übergeben werden.';