![Statamic 3](https://img.shields.io/badge/Statamic-3.x-blueviolet)
[![Software License](https://img.shields.io/github/license/krautgortna/statamic-disqus)](LICENSE.md)
![Downloads](https://img.shields.io/packagist/dt/krautgortna/statamic-disqus)
[![Create Release](https://github.com/krautgortna/statamic-disqus/actions/workflows/create-release.yml/badge.svg)](https://github.com/krautgortna/statamic-disqus/actions/workflows/create-release.yml)

## Features

-   Provides Antlers tags that can be used in any frontend view
-   Easily provide a discussion thread id as a parameter
-   For API calls: decide if you want to make a server- or client-side API call

## Tags

- `{{ disqus:comments id="id" }}` to load the Disqus embed plugin for a given thread
- `{{ disqus:count id="id" }}` to show the number of comments ("posts") of a given thread
- `{{ disqus:likes id="id" }}` to show the number of likes of a given thread

## How to Use

First of all, provide your Disqus forum's shortname via the .env file:

```bash
DISQUS_SHORTNAME="your_shortname"
```

In the Antlers template, you can now use 
```bash
{{ disqus:comments id="id" }}
```

Provide a variable as the thread id, e.g. when the slug is the Disqus thread id:

```bash
{{ disqus:comments :id="slug" }}
```

## All .env Parameters

- `DISQUS_SHORTNAME="your_shortname"` to identify your account
- `DISQUS_SECRET` used for server-side API calls
- `DISQUS_API_KEY` used for client-side API calls
- `DISQUS_METHOD` choose between 'server' or 'client' API calls (note that the Disqus embed is always loaded on client side)

## How to Install

You can search for this addon in the `Tools > Addons` section of the Statamic control panel and click **install**, or run the following command from your project root:

```bash
composer require krautgortna/statamic-disqus
```
