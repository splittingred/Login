<?php
/**
 * Properties Dutch lexion entries
 * 
 * @package login
 * @subpackage lexicon
 * @author Bert Oost, bertoost85@gmail.com
 */
/* ForgotPassword snippet */
$_lang['prop_forgotpassword.emailtpl_desc'] = 'De bevestigingsemail template.';
$_lang['prop_forgotpassword.emailtpltype_desc'] = 'Het template type welke de emailTpl property bevat. Standaard een chunk.';
$_lang['prop_forgotpassword.senttpl_desc'] = 'De template met het bericht dat de e-mail is verstuurd.';
$_lang['prop_forgotpassword.senttpltype_desc'] = 'Het template type welke de sendTpl property bevat. Standaard een chunk.';
$_lang['prop_forgotpassword.tpl_desc'] = 'Het wachtwoord vergeten template.';
$_lang['prop_forgotpassword.tpltype_desc'] = 'Het template type welke de tpl property bevat. Standaard een chunk.';
$_lang['prop_forgotpassword.emailsubject_desc'] = 'Het onderwerp van de wachtwoord vergeten e-mail.';
$_lang['prop_forgotpassword.resetresourceid_desc'] = 'Het id van het document waar gebruikers in de bevestigingsemail naartoe gelinkt worden, daar waar de ResetPassword snippet in staat.';

/* Login snippet */
$_lang['prop_login.actionkey_desc'] = 'De REQUEST variabele waar de de action in zou moeten staan.';
$_lang['prop_login.loginkey_desc'] = 'De login action key.';
$_lang['prop_login.logoutkey_desc'] = 'De logout action key.';
$_lang['prop_login.tpltype_desc'] = 'Het type van templates voor de inlog en uitlog formulieren.';
$_lang['prop_login.logintpl_desc'] = 'De inlog template.';
$_lang['prop_login.logouttpl_desc'] = 'De uitloggen template.';
$_lang['prop_login.prehooks_desc'] = 'Het script dat uitgevoerd word, voordat een gebruiker is in- of uitgelogd. Dit kan een komma gescheiden lijst van hooks zijn en als de eerste faalt dan word de volgende niet uitgevoerd. Een hook kan ook een snippet naam zijn.';
$_lang['prop_login.posthooks_desc'] = 'Het script dat uitgevoerd word, nadat een gebruiker is in- of uitgelogd. Dit kan een komma gescheiden lijst van hooks zijn en als de eerste faalt dan word de volgende niet uitgevoerd. Een hook kan ook een snippet naam zijn.';
$_lang['prop_login.errtpl_desc'] = 'De error template.';
$_lang['prop_login.errtpltype_desc'] = 'Het type voor de error template.';
$_lang['prop_login.logoutresourceid_desc'] = 'Document ID waar de gebruiker na het uitloggen heen gestuurd word. 0 is voor zichzelf.';
$_lang['prop_login.loginresourceid_desc'] = 'Document ID waar de gebruiker na het inloggen heen gestuurd word. 0 is voor zichzelf.';
$_lang['prop_login.loginmsg_desc'] = 'Optioneel label bericht voor inlog actie. Indien leeg, dan de standaard lexicon waarde voor Login.';
$_lang['prop_login.logoutmsg_desc'] = 'Optioneel label bericht voor uitlog actie. Indien leeg, dan de standaard lexicon waarde voor Uitlog.';
$_lang['prop_login.redirecttoprior_desc'] = 'Indien waar, stuur naar de voorgaande pagina (HTTP_REFERER) door, indien succesvol ingelogd.';
$_lang['prop_login.rememberme_desc'] = 'Optioneel. De veldnaam van de Onthoud Mij checkbox om de inlogstatus te bepalen. Standaard `rememberme`.';
$_lang['prop_login.contexts_desc'] = '(Experimenteel) Een komma gescheiden lijst van contexts om op in te loggen. Standaard de huidige context indien niet nadrukkelijk gezet.';
$_lang['prop_login.toplaceholder_desc'] = 'Indien gezet, de login snippet output word dan in een placeholder met deze naam gezet in plaats van direct de output te tonen.';

/* Profile snippet */
$_lang['prop_profile.prefix_desc'] = 'Een string om alle placeholdes mee te beginnen, voor alle velden gezet door deze snippet.';
$_lang['prop_profile.user_desc'] = 'Optioneel. Ofwel een gebruikers ID of gebruikersnaam. Indien gezet, dan zal deze gebruiker gebruikt worden in plaats van de huidig ingelogde gebruiker.';
$_lang['prop_profile.useextended_desc'] = 'Al dan niet extra velden gebruiken in het profielformulier. Dit kan handig zijn voor het opslaan van extra gebruikersvelden.';

/* Register snippet */
$_lang['prop_register.submitvar_desc'] = 'De var om te controleren of de Register functonaleit word uigevoerd. Indien leeg of op onwaar gezet, Register zal op elke POST uitgevoerd worden.';
$_lang['prop_register.usergroups_desc'] = 'Optioneel. Een komma gescheiden lijst van gebruikersgroepnamen of IDs waar nieuwe gebruikers tot behoren.';
$_lang['prop_register.submittedresourceid_desc'] = 'Indien gezet, dan word na het versturen van het registratieformulier de gebruiker naar dit Document ID gestuurd.';
$_lang['prop_register.usernamefield_desc'] = 'De te gebruiken naam van het veld voor de gebruikersnaam.';
$_lang['prop_register.passwordfield_desc'] = 'De te gebruiken naam van het veld voor het wachtwoord.';
$_lang['prop_register.emailfield_desc'] = 'De te gebruiken naam van het veld voor het e-mailadres.';
$_lang['prop_register.successmsg_desc'] = 'Optioneel. Indien niet word doorgestuurd naar de submittedResourceId parameter, dan zal dit bericht getoond worden.';
$_lang['prop_register.prehooks_desc'] = 'Welke scripts worden uitgevoerd, voordat het formulier gevalideerd word. Dit kan een komma gescheiden lijst van hooks zijn en als de eerste faalt dan word de volgende niet uitgevoerd. Een hook kan ook een snippet naam zijn.';
$_lang['prop_register.posthooks_desc'] = 'Welke scripts worden uitgevoerd, nadat een gebruiker zich heeft geregistreerd. Dit kan een komma gescheiden lijst van hooks zijn en als de eerste faalt dan word de volgende niet uitgevoerd. Een hook kan ook een snippet naam zijn.';
$_lang['prop_register.useextended_desc'] = 'Wel of niet extra velden instellen in het Profiel formulier. Dit kan handig zijn voor het opslaan van extra gebruikersvelden.';
$_lang['prop_register.excludeextended_desc'] = 'Een komma gescheiden lijst van uit te sluiten velden in de extra velden.';
$_lang['prop_register.activation_desc'] = 'Al dan niet verplicht om eerst de registratie te activeren. Indien waar, gebruikers zijn eerst inactief totdat zij hun account geactiveerd hebben. Standaard waar. Zal alleen werken als het registratieformulier een e-mailadres veld bevat.';
$_lang['prop_register.activationttl_desc'] = 'Aantal minuten totdat de activatie e-mail verloopt. Standaard 3 uur.';
$_lang['prop_register.activationresourceid_desc'] = 'Het document ID waar de ConfirmRegister snippet voor activatie zich bevindt.';
$_lang['prop_register.activationemailsubject_desc'] = 'Het onderwerp van de activatie e-mail.';
$_lang['prop_register.activationemailtpltype_desc'] = 'Het template type voor de activatie e-mails';
$_lang['prop_register.activationemailtpl_desc'] = 'De activatie e-mail template.';
$_lang['prop_register.recaptchaheight_desc'] = 'Indien `recaptcha` is gezet als een preHook, selecteer je hiermee de hoogte van de reCaptcha widget.';
$_lang['prop_register.recaptchatheme_desc'] = 'Indien `recaptcha` is gezet als een preHook, selecteer je hiermee het thema van de reCaptcha widget.';
$_lang['prop_register.recaptchawidth_desc'] = 'Indien `recaptcha` is gezet als een preHook, selecteer je hiermee de breedte van de reCaptcha widget.';
$_lang['opt_register.chunk'] = 'Chunk';
$_lang['opt_register.file'] = 'Bestand';
$_lang['opt_register.inline'] = 'Inline';
$_lang['opt_register.embedded'] = 'Embedded';
$_lang['opt_register.blackglass'] = 'Zwart glas';
$_lang['opt_register.clean'] = 'Schoon';
$_lang['opt_register.red'] = 'Root';
$_lang['opt_register.white'] = 'Wit';

/* ResetPassword snippet */
$_lang['prop_resetpassword.tpl_desc'] = 'Het wachtwoord reset bericht template.';
$_lang['prop_resetpassword.tpltype_desc'] = 'Het type template. Standaard een Chunk.';
$_lang['prop_resetpassword.loginresourceid_desc'] = 'Het document waar gebruikers naar gestuurd worden na succesvolle bevestiging.';

/* UpdateProfile snippet */
$_lang['prop_updateprofile.submitvar_desc'] = 'De var voor het controleren of the Register functionaliteit geladen moet worden. Indien leeg of onwaar, Register zal voor elk POST verzoek geladen worden.';
$_lang['prop_updateprofile.redirecttologin_desc'] = 'Indien een gebruiker niet ingelogd is en dit document opent, stuur hem naar de Unauthorized pagina.';
$_lang['prop_updateprofile.reloadonsuccess_desc'] = 'Indien waar, de pagina zal zichzelf doorsturen met een GET parameter om dubbele posts te voorkomen. Indien false, dan zal hij een simpele placeholder maken.';
$_lang['prop_updateprofile.syncusername_desc'] = 'Indien gezet op een kolomnaam in het profiel, UpdateProfile zal een poging doen om de gebruikersnaam te synchroniseren met dit veld na een succesvolle opslag.';
$_lang['prop_updateprofile.useextended_desc'] = 'Al dan niet om extra velden in het formulier te zetten. Dit kan handig zijn voor het opslaan van extra gebruikersvelden.';
$_lang['prop_updateprofile.excludeextended_desc'] = 'Een komma gescheiden lijst van velden om uit te sluiten van instelling als extra velden.';
$_lang['prop_updateprofile.prehooks_desc'] = 'Welke scripts worden uitgevoerd, voordat het formulier gevalideerd word. Dit kan een komma gescheiden lijst van hooks zijn en als de eerste faalt dan word de volgende niet uitgevoerd. Een hook kan ook een snippet naam zijn.';
$_lang['prop_updateprofile.posthooks_desc'] = 'Welke scripts worden uitgevoed, nadat een gebruiker geregistreerd is. Dit kan een komma gescheiden lijst van hooks zijn en als de eerste faalt dan word de volgende niet uitgevoerd. Een hook kan ook een snippet naam zijn.';