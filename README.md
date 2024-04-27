# Untitled

This yet-to-be-named project explores ways to blend websites, social media and collaboration on the Web, by implementing an [easy](https://gilest.org/indie-easy.html)-to-use website engine powered by various open web standards, such as [micropub](https://www.w3.org/TR/micropub/), [microformats](https://microformats.org/), [webmention](https://www.w3.org/TR/webmention/), [pingback](https://en.wikipedia.org/wiki/Pingback), and (eventually), [ActivityPub](https://www.w3.org/TR/activitypub/).

In the long term, it's supposed to replace [neopub](https://git.dupunkto.org/neopub), and potentially power my blog.

This project is very much an attempt at self-dogfooding.

## Features

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
- [ ] ActivityPub
- [ ] It works
- [ ] CLI

### CLI

      pub < post.txt
      pub -i image.png
      pub -i graph.png -n
      echo "caption" | pub -i another.png
      echo "caption" | pub -c main.c

Optional -n flag uploads the image but doesn't include it in the feeds, useful for including images in a post.
Optional -c flag copies the url to keyboard. otherwise, write to stdout.

## Goals

Easy publishing from my phone. Hit icon, type something, attach picture, post.

Easy publishing from my laptop. Writing post in Markdown in Neovim, pasting
images (automatically uploading to u.roblog.nl, with -n flag).

## Non-goals

Tests. I get it, their important for ensuring code quality and confidence in codebases in professional settings. But this is a side-project that I'm building in my spare time, for fun. Don't ruin that for me please.

Bookmarks, crossposts, likes. I want to encourage conversation, collaboration and meaningful interaction. Mindlessly double tapping doesn't fit that vision.
