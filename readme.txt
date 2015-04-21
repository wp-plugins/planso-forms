=== PlanSo Forms ===
Contributors: Stephan Helbig, PabloFernandezGarcia
Tags: form,forms,contact form,form builder,form creator,build forms,create forms,manage forms,form manager,submit form,signup forms,application forms,form maker
Requires at least: 3.9
Tested up to: 4.2
Stable tag: trunk

This form builder makes it easy to create professional forms. Build forms and manage forms in a new intuitive way and customize fields with icons, etc

== Description ==

PlanSo Forms makes it easy to create professional forms. Build forms and manage forms in a new intuitive way and customize form fields with icons, labels, placeholders and the like.

[youtube http://www.youtube.com/watch?v=pKlgQH5VCck]

[Check out PlanSo Forms Pro and Expert](http://forms.planso.de/?utm_campaign=psfb-wpdir&utm_source=wordpress.org-plugins&utm_medium=link "PlanSo Forms - Professional forms easy as 1-2-3")

= Overview =

**Responsive Forms**  
PlanSo Forms are responsive and adjust to your user's screen size. All forms look equally awesome when visited from a tablet, a desktop pc, a smartphone and even displayed on TV.

**Quick & easy**  
PlanSo Forms are easy to create. It just takes a few seconds to build your own form.

**Flexible use cases**  
PlanSo Forms are highly flexible. Create any kind of form for infinite use cases with PlanSo form builder.

**Mobile first**  
Forms created with PlanSo Forms comply to mobile-first development style as each form is based on Twitter's Bootstrap form syntax.

**Themes**  
PlanSo Forms is tested with many different themes and has options to inject unobtrusive styles to look awesome with allmost any theme.

**Bootstrap Forms**  
PlanSo Forms are based on Twitter's Bootstrap and allow flexible columns and rows.

**No coding**  
Thanks to the Drag-and-Drop functionality PlanSo Forms do not require any coding skills.

= Features =
* HTML5 form fields
* Multiple form columns
* Mixed column counts
* Multilingual form builder
* Form field icons
* Intuitive drag-n-drop
* Form builder with HTML blocks
* Autoresponder emails after form submission
* Email attachments submitted via a form
* File submissions within forms
* Integrated SPAM protection WITHOUT captchas
* Bootstrap date- and timepicker included
* Unlimited combination of form fields
* Custom CSS classes and styles per form field
* Individual labels, placeholders and help-texts
* Batch edit, copy and paste label value pairs
* Multiple form layouts
* Multi file uploads
* Font-Awesome Icons for input groups
* Select from different datepickers within form builder
* Multiple form recipients of autoresponder email
* Horizontal and vertical alignment of radio and checkbox form fields
* Easy to use form builder
* Multiple bcc recipients
* Works with any theme

= Example use cases =
* Simple Contact forms
* Complex Contact forms
* Registration forms
* Invitation forms
* Calendar forms
* Booking forms
* Payment forms
* Contest forms
* Subscription forms
* Complaint forms
* Appointment forms
* Donation forms
* Quiz forms
* Order forms
* Test forms
* Mobile forms
* Survey forms
* Application forms
* Tax forms
* And many, many more

> #### PlanSo Forms Pro
> PlanSo Forms Pro comes with the following features.<br />
>
> HTML-Emails<br />
> Conditional Logic<br />
> PayPal Payment Forms<br />
> No reference to PlanSo Forms in emails<br />
> Predefined attachments for autoresponder mails<br />
> Google Analytics Integration<br />
> Pushover.net Integration<br />
> Zapier.com Integration<br />
> Priority Support<br />
>
> [Learn more about PlanSo Forms Pro >>](http://forms.planso.de/?utm_campaign=psfb-wpdir&utm_source=wordpress.org-plugins&utm_medium=link)


== Installation ==

There are three easy ways to install PlanSo Forms:

**Uploading .zip file via WordPress**  
Download the [planso-forms.zip](https://downloads.wordpress.org/plugin/planso-forms.zip) file. Log on to your WordPress admin account. Select Plugins on the left hand options, click Add New and Upload Plugin. Either drag the downloaded .zip file into the upload area or choose the .zip file from the download directory and click Install Now. After the installation click Activate Plugin and after a few seconds you will find a new option on the left hand menu named Planso Forms.

**Uploading the plugin via FTP**  
Download the [planso-forms.zip](https://downloads.wordpress.org/plugin/planso-forms.zip) file and extract its contents to a local folder. Go to your FTP client and browse on your hosting server to /your_wp_installation/wp-content/plugins/. Upload the extracted planso-forms folder into this directory. Afterwards log on to your WordPress admin account. Select Plugins on the left hand options, click Installed Plugins and Activate the PlanSo Forms Plugin. After a few seconds you will find a new option on the left hand menu named Planso Forms.

**Installing from the WordPress directory**  
Log on to your WordPress admin account. Select Plugins on the left hand options and click Add New. Search for PlanSo Forms in the search bar and click  Install Now. After the installation click Activate Plugin and after a few seconds you will find a new option on the left hand menu named Planso Forms.

= ATTENTION IIS/AZURE USERS =
If you are using a windows server or azure websites for your WordPress host you might have to enable "smtp mail" in order for autoresponder emails to work. You can install a plugin like easy smtp to make your life easier.

== Frequently Asked Questions ==

= How do I get in touch for support? =

Please use the [Support tab](https://wordpress.org/support/plugin/planso-forms) in the WordPress plugin directory or use our [support form](http://www.planso.de/en/support/) to report bugs and to receive help.

== Screenshots ==
1. Easily create forms with multiple columns using drag-n-drop with PlanSo Forms builder.

2. Preview forms created while editing - PlanSo forms acts like a wysiwyg form creator.

3. Edit form field details fast and intuitively within comfortable modal dialogs.

4. PlanSo Forms offers you a wide variety of form fields to choose from. Just drag them into the stage and you'll see the resulting form instantly.

5. Create forms for different use cases: signup forms, newsletter registration forms, contact forms, application forms, quizzes, order forms, donation forms, subscription forms, and so on. The sky is the limit.

6. Easily customize the autoresponder emails sent upon form submission. You can add each field of the form into the email text by simply clicking a button.

7. Customize form fields with your own css classes and styles. This way you can alter the design to suit very special needs.

8. Integrate your forms by inserting the generated shortcode anywhere within your site.

== Changelog ==

= 1.3.3 =
* IMPORTANT fix after the change of the post parameter to psfbid

= 1.3.2 =
* Changed post parameter to psfbid because of incompatibility with certain themes
* Checked for compatability with WordPress 4.2
* Changed the dynamic date and time variables to obey the WordPress settings
* Updated locales

= 1.3.1 =
* Added new dynamic variables for use in autoresponder email body ([psfb_current_date], [psfb_current_datetime], [psfb_current_time])
* Added new debug option for cases where the form breaks (add &psfb_debug=1 to the url of your form in edit mode and submit the JSON to the forums or to our support form for help)
* Added possibility to define icons for multiline text fields and selectboxes
* Updated translations

= 1.3.0 =
* Optimized shortcut handling with predefined values

= 1.2.9 =
* Added option to allow form fields to be prefilled with a value using $_GET or $_POST
* Added option to allow form fields to be prefilled with a value using shortcode attributes. The following values will be replaced intelligently: CURRENT_DATE(),CURRENT_DATETIME() and CURRENT_TIME()

= 1.2.8 =
* Updated locales and translations

= 1.2.7 =
* Extended side by side layout options to allow more flexible widths
* Fixed validation of checkbox form fields
* Improved extensibility

= 1.2.6 =
* Fixed an error that could break form submission when more than one bcc recipients were entered
* Improved attachment handling
* Improved shortcode selection

= 1.2.5 =
* Fixed a problem that incorrectly handled the saving of the side by side label placement option

= 1.2.4 =
* Added new option to allow labels to to be placed in line with fields

= 1.2.3 =
* Fixed an issue that could lead to unwanted output above form if no referer was detected

= 1.2.2 =
* Added a new option to save all attachments on your server
* Corrected sorting of forms in form builder overview

= 1.2.1 =
* Removed unnecessary spaces in css classes and fixed adding of spaces upon update

= 1.2.0 =
* Fixed an issue where the form could output a php error message under certain server configurations

= 1.1.9 =
* Fixed an issue with Black Studio TinyMCE Widget Plugin
* Fixed a bug that caused an error when copying forms with html content

= 1.1.8 =
* Improved localizations
* Improved JavaScript Antispam Protection

= 1.1.7 =
* New option to manually switch date format of datepicker fields
* Fixed a bug where fields with the same label where treated as one

= 1.1.6 =
* New option added for radio and checkbox fields that allows you to choose the orientation of these form fields
* New input mode added for select values of checkbox,radio,select and multiselect form fields. This allows you to batch edit and copy value and label pairs
* Changed admin form builder style sheet to not interfer with the wordpress admin styles

= 1.1.5 =
* Fixed bug where checkboxes lost their check state after an unsuccessful form submission

= 1.1.4 =
* Fixed bug where radio and checkboxes could not be marked as required
* Fixed bug where radio and checkbox labels could not be hidden
* Fixed bug that caused field values not to be transfered in emails when the field name was left blank

= 1.1.2 =
* Fixed a bug in the form builder that could lead to regular select dropdowns beeing displayed as multiselect dropdowns

= 1.1.1 =
* Bug that caused a problem loading conditional logic of PlanSo Forms Pro fixed

= 1.1.0 =
* New Feature: Drag HTML blocks into your forms as easy as adding form fields
* Improved translations

= 1.0.9 =
* Fixed issue with [PlanSo Forms Pro](http://forms.planso.de/?utm_campaign=psfb-wpdir&utm_source=wordpress.org-plugins&utm_medium=link) extension
* Added CSS Class to mandatory field marks, which enables custom styling of marks

= 1.0.8 =
* Tested and updated for WordPress 4.1.1

= 1.0.7 =
* Fixed translation errors

= 1.0.6 =
* Changed form builder forms edit page to tabbed layout

= 1.0.5 =
* Fixed additional styling issues

= 1.0.4 =
* Fixed styling issues with several Themes, especially with the Genesis Framework

= 1.0.3 =
* Added drop down menu to WordPress RichTextEditor for easier integration of forms
* Added option to choose from 3 different Datepicker Frameworks

== Upgrade Notice ==

= 1.3.3 =
Please update because of an error that caused you to be redirected to a wrong url aufter form saving and updateing