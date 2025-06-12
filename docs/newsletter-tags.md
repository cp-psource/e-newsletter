---
layout: psource-theme
title: "PS-eNewsletter Tags"
---

<h2 align="center" style="color:#38c2bb;">📚 PS-eNewsletter Tags</h2>

<div class="menu">
  <a href="https://github.com/cp-psource/e-newsletter/discussions" style="color:#38c2bb;">💬 Forum</a>
  <a href="https://github.com/cp-psource/e-newsletter/releases" style="color:#38c2bb;">📝 Download</a>
</div>

Tags. How To Insert Subscriber’s Data into Newsletters
The Newsletter plugin provides a number of tags or placeholders you can use to inject subscriber’s data, dynamic URLs, or forms on pages or newsletters. Examples are the subscriber’s name or the personal cancellation URL.

They’re written as {tagname}, like {name} (the subscriber’s first name) or {email_url} (the link to the newsletter online version).

Let’s start exploring them all!

What's inside
How to use URL-generating tags
General tags
Subscriber specific tags
About profile fields tags {profile_N}
Salutation according to gender
Subscription, cancellation, profile page URL tags
Company information tags
Forms
Tags can be used on messages, subjects, and on-page texts (configurable from the subscription panel). Of course, not all tags make sense in every place or every context. For example, a subscription confirmation tag has not much sense in the welcome message (sent when the subscription is already confirmed).

Newsletter tags can not be used on posts or pages, they don’t work! They work only when the text is manipulated by Newsletter, for example while creating the final email or the final message to be displayed to the subscriber.

Many tags are subscriber-linked so they need a subscriber set of data to be generated. Clearly the {name} tag needs a subscriber, but even the {subscription_confirm_url} needs it, since the generated URL contains the subscriber’s keys.

How to use URL-generating tags
Some tags generate an URL with the subscriber’s private token to access his profile or to start some kind of action like activation or cancellation. If you write directly the HTML code, the tag should be used in this way:

<a href="{unsubscription_url}">To unsubscribe click here</a>
if you use an editor, just select the word or phrase you to become a link, press the link tool and use the tag as URL.


General tags
{blog_url} – the blog URL, like https://www.thenewsletterplugin.com
{blog_title} – the blog title as configured on the WordPress general settings
{blog_description} – the blog description as configured on the WordPress general settings
{date} – the current date (not time) formatted as configured on the WordPress general settings
{date_NNN} – the current date formatted as specified by NNN which is a sequence of characters compatible with the PHP date() function specifications
{email_url} – the URL to see the current newsletter online
Subscriber specific tags
{id} – the subscriber’s unique ID
{name} – the subscriber’s name or first name, it depends on how you use that fields during the subscription
{surname}  – the subscriber’s last name
{title} – the subscriber’s title or salutation, like Mr or Mrs, can be configured on the subscription panel
{email} – the subscriber’s email
{profile_N} – the profile number N as configured on subscription form fields
{ip} – the IP from which the subscription has been started; there is who like to add it to the confirmation email
About profile fields tags {profile_N}
The {profile_N} tag must be used by changing the “N” to the number of the profile field you want to insert.

For example, if your profile field number 2 is the shoe number and you want to personalize the newsletter content by writing something like “See all our offers for shoes of number [subscriber show number]” you can write the sentence in this way: “See all our offers for shoes of number {profile_2}”.

Salutation according to gender
To start a newsletter with a different salutation by gender you can use a combination of tags {title} and {name}. For example

Good morning {title} {name},
there {title} is replaced with Mr. or Mrs. or the texts you set on Subscription>Form fields panel.

Subscription, cancellation, profile page URL tags
{subscription_confirm_url} – to confirm a subscription, to be used only on confirmation email when the double opt-in is used
{unsubscription_url} – to drive the user to the unsubscription page where he’s asked to confirm he wants to unsubscribe; should be used on every email even is a good alternative is to use the {profile_url} tag
{unsubscription_confirm_url} – to definitively unsubscribe; can be used on every email for the “one-click unsubscription” or on the unsubscription request page (the one references by the {unsubscription_url} tag)
{profile_url} – point directly to the profile editing page; I prefer to use this tag to offer the unsubscription feature, adding on the profile page the {unsubscription_confirm_url} so the subscriber can (eventually) review his profile instead of unsubscribe
Note: “unsubscription” is not an English word, that was an error from the first plugin’s days.

Company information tags
Company data can be set on Settings>Company Info panel.

{company_name} – the company name from the company info configuration
{company_address} – the company address from the company info configuration
{company_legal} – the legal text set on company info
Forms
Forms tags are of course specific and can be used only on some pages. They can have different behavior in different contexts.

{subscription_form} – generates the main subscription form and should be used only on the subscription page configurable on the subscription panel
{subscription_form_N} – can be used in place of the {subscription_form} to recall the custom form number N
{profile_form} – must be used on profile page text (configurable on subscription panel) and generated the form where a subscriber can review and edit his data; I use it even on the welcome page to let the subscriber complete the subscription adding more information
The {subscription_form}, when used on the widget, it is replaced by a different form, with the same field but a different layout (that better adapts to a sidebar).