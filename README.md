# Untitled

This yet-to-be-named project explores ways to blend websites, social media and collaboration on the Web, by implementing an [easy](https://gilest.org/indie-easy.html)-to-use website engine powered by various open web standards, such as [micropub](https://www.w3.org/TR/micropub/), [microformats](https://microformats.org/), [webmention](https://www.w3.org/TR/webmention/), [pingback](https://en.wikipedia.org/wiki/Pingback), and (eventually), [ActivityPub](https://www.w3.org/TR/activitypub/).

In the long term, it's supposed to replace [neopub](https://git.dupunkto.org/neopub), and potentially power my blog.

This project is very much an attempt at self-dogfooding.

## Features

- [x] Single tenant
- [x] Captioned images
- [x] Short posts
- [x] Captioned code (like gists)
- [x] Replies
- [x] Sending webmentions
- [x] Sending pingbacks
- [x] Receiving webmentions
- [x] Receiving pingbacks
- [x] RSS, Atom & JSON feeds
- [x] Comment section
- [x] IndieAuth
- [ ] ActivityPub
- [ ] It works
- [ ] CLI

### CMS

I'm also building my own custom CMS to power this website engine. The CMS will be similar to early versions of Blogger, and support a bunch of things:

- Asset management
- Micropub editor
- Moderating webmentions
- Basic statistics
- [@mentions](https://roblog.nl/blog/mentions)
- Email subscriptions (based on [Sub API](https://api.geheimesite.nl/sub))
- RSS only postings (won't be rendered on listings)

### CLI

    pub < post.txt
    pub -i image.png
    pub -i graph.png -n
    echo "caption" | pub -i another.png
    echo "caption" | pub -c main.c

Optional -n flag uploads the image but doesn't include it in the feeds, useful for including images in a post.
Optional -c flag copies the url to keyboard. otherwise, write to stdout.

### Eventually?

I'm also thinking about other things, but I've shelved them until I have a working site up-and-running:

- Git integration? I could add an optional git module that would version-manage the data store. I'm not sure whether this is possible using PHP, but it would definitely be cool.

- [#hashtags](https://personal-web.org). The difficulty is that some sort of central service is needed. I'm not sure how ActivityPub handles it, but might be interesting to look into when I build the ActivityPub functionality?

- Access control? Only allowing certain subscribers to see some posts. This would work by giving each subscriber an unique token, which would be stored in a cookie and appended to the query params of the RSS feed.

- Drafts and previewing, maybe implemented using the same foundation as access control?

## Goals

Easy publishing from my phone. Hit icon, type something, attach picture, post.

Easy publishing from my laptop. Writing post in Markdown in Neovim, pasting
images (automatically uploading to u.roblog.nl, with -n flag).

## Non-goals

**Tests**. I get it, they're important for ensuring code quality and confidence in codebases in professional settings. But this is a side-project that I'm building in my spare time, for fun. Don't ruin that for me please.

**Bookmarks, crossposts, likes**. I want to encourage conversation, collaboration and meaningful interaction. Mindlessly double tapping doesn't fit that vision.
