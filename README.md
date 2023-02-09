# Disqus

## Features

-   Provides an Antlers tag that can be used in any frontend view
-   Easily provide a discussion thread id as a parameter

## How to Use

First of all, provide your Disqus forum's shortname via the .env file:

```bash
DISQUS_SHORTNAME="your_shortname"
```

In the Antlers template, you can now use 
```bash
{{ disqus:comments id="id" }}
```

If you want to provide the thread id dynamically, e.g. when the slug is the disqus thread id:

```bash
{{ disqus:comments :id="slug" }}
```

## How to Install

You can search for this addon in the `Tools > Addons` section of the Statamic control panel and click **install**, or run the following command from your project root:

```bash
composer require krautgortna/statamic-disqus
```
