=== API Addon for The Newsletter Plugin ===
Tested up to: 6.7.2

REST API for Newsletter

== Changelog ==

= 2.5.1 =

* WP 6.7.2 check
* Added default page value to the "subscribers" endpoint

= 2.5.0 =

* WP 6.7.1 check

= 2.4.9 =

* WP 6.6 check

= 2.4.8 =

* WP 6.5.5 check
* Fixed repeated subscriptions

= 2.4.7 =

* Improved validation on old endpoints

= 2.4.6 =

* Missing authorization fix

= 2.4.5 =

* WP 6.5.4 check

= 2.4.4 =

* Added token to the subscriber details

= 2.4.3 =

* Fixed the extra fields endpoint
* WP 6.5.3 check

= 2.4.2 =

* Fix lists for the subscriptions endpoint

= 2.4.1 =

* Fix: debug notice with PHP 8.2

= 2.4.0 =

* WP 6.5.2 check

= 2.3.9 =

* Warning fix

= 2.3.8 =

* New logs panel
* New debug logs

= 2.3.7 =

* WP 6.4.1 compatibility check

= 2.3.6 =

* Fix PUT method (please check the updated documentation)

= 2.3.5 =

* Fix

= 2.3.4 =

* Fix

= 2.3.3 =

* WP 6.3 compatibility check
* Fixed "deprecated" notice with WP 8.2

= 2.3.2 =

* Private lists can be added with /subscriptions endpoint IF authenticated

= 2.3.1 =

* Fix warning by WP

= 2.3.0 =

* Fixed error with version 7.8+

= 2.2.9 =

* Fixed error with version 7.8+

= 2.2.8 =

* Fixed "rest_route" not found warning

= 2.2.7 =

* Improved the authenticaton method
* Code cleanup
* Removed contraint on SSL (you should be free to use the API without HTTPS, right?)
* Temporarily removed contraints on API key associated user (was meant for a fine control actually not implemented)

= 2.2.6 =

* Fix extra field check generating an error for a missing extra field

= 2.2.5 =

* Fix check on private lists

= 2.2.4 =

* Fix activation and welcome emails

= 2.2.3 =

* Fixed lists bug when passed as objects

= 2.2.2 =

* Removed a var_export creating a loop on a few installations

= 2.2.1 =

* New opt-in option for subscriptions
* New optional lists format
* Added list_N parameters

= 2.1.9 =

* WP 5.8.3 compatibility check

= 2.1.8 =

* Improved debug notice

= 2.1.7 =

* Fixed debug notice

= 2.1.6 =

* Possible fix for authentication conflict with wp application password

= 2.1.5 =

* Fixed handling of 'status' field

= 2.1.4 =

* Added logging to the authentication process
* Changed returned messages on authentication failure

= 2.1.3 =

* Fixed 404 "rest_no_route" response if email contains '_' character

= 2.1.2 =

* Fixed debug notices

= 2.1.1 =

* New addon format
* Fix link to documentation
* Removed obsolete code
* Improved startup speed

= 2.1.0 =

* New API version 2-beta introduced

= 2.0.3 =

* WordPress 5.4.2 compatibility

= 2.0.2 =

* Added subscribers list
* Added newsletters list

= 2.0.1 =

* Fix class require for old versions

= 2.0.0 =

* New JSON API for Newsletter

= 1.0.0 =

* First Release

