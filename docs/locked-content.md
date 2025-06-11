---
layout: psource-theme
title: "PS-eNewsletter Locked Content"
---

<h2 align="center" style="color:#38c2bb;">📚 PS-eNewsletter Locked Content</h2>

<div class="menu">
  <a href="https://github.com/cp-psource/e-newsletter/discussions" style="color:#38c2bb;">💬 Forum</a>
  <a href="https://github.com/cp-psource/e-newsletter/releases" style="color:#38c2bb;">📝 Download</a>
</div>

Locked Content
Locked Content is a powerful free addon for Newsletter made to hide specific blog contents for users that are not subscribed (and, of course, unlock them for subscribed users). This technique can be applied to any content of your blog you consider “premium content” something that worth a subscription to be read.

To lock a content you should simply surround the part of the post you want to hide with a short code:

[newsletter_lock]
 ...content...
[/newsletter_lock]
When a visitor is reading a post with those short codes, the content inside them is replaced with a message you can configure on Newsletter main configuration panel. That message, other than explain why the content is hidden, should invite the user to subscriber to the newsletter.

For example the message could look like the one below which uses the minimal form. But you can even use the full form if you prefer.

To read the full article subscriber to our newsletter.
[newsletter_form type="minimal"]
If the message is left empty, a full subscription form is automatically shown.

You can even lock out a full set of posts just indicating on configuration a tag or category name or id: all posts within that tag or category will be completely hidden, leaving visible only the title and the replacement message.

How is the content unlocked?
Note: the “hidden” content is anyway shown when the page is visited by a logged in user with publishing privileges like the administrator, an editor or an author (or other user rles enabled to publish).

When the subscription is confirmed (immediately with single opt-in mode and after the confirmation with double opt-in mode) a cookie tracks the subscription and you can send the user directly to the premium content. You can link the content in the welcome page and/or in the welcome email.

For example, if you’re locking out all articles in a specific category, let me name it “premium”, you can link that category in the welcome email and the blog will list all the premium content that will be fully available to the subscriber (until he remains confirmed).

The old {unlock_url}
The {unlock_url} tag is no more required but kept for compatibility. The destination URL is the one set on Locked Content configuration panel.

Styling
The subscription box which replaces the hidden content is minimally styled with some padding and a thin border. If you want to style it on your own, you can override the default styles adding your custom rules to the extra CSS configuration of Newsletter. For example if you want add a dashed border to the box:

.tnp-lock {
border: 3px dashed #888;
}
Caches
Cache plugins can wrongly cache the locked or unlocked pages. The extension tries to block the cache for those pages, anyway you can force the cache to not serve cached page to who has the “newsletter” cookie.