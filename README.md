# NextDeveloper Blogs

A Laravel library for managing blog accounts and posts. It provides a multi-tenant blogging backend where each IAM account owns its own blog space, with posts, perspectives for enriched queries, and a full REST API.

## Features

- [x] Multi-tenant blog accounts — each IAM account has its own blog space
- [x] Post management — full CRUD lifecycle for blog posts
- [x] Accounts perspective — enriched account view with stats
- [x] Posts perspective — enriched post list with account and author context
- [x] Role-based access control — scoped to account ownership
- [ ] Categories and tags
- [ ] Post scheduling
- [ ] Comment threads
- [ ] RSS / Atom feed generation

## Core Models

| Model | Description |
|---|---|
| `Accounts` | Blog account linked to an IAM account |
| `Posts` | Blog post records with title, content, and status |
| `AccountsPerspective` | Read-only view with enriched account data |
| `PostsPerspective` | Read-only view with enriched post data including author info |

## Installation

```bash
composer require nextdeveloper/blogs
```

Register the service provider in `config/app.php` if not using auto-discovery:

```php
NextDeveloper\Blogs\BlogsServiceProvider::class,
```

## Commercial Support

Please let us know if you need any commercial support. We will be happy to help you on your project and/or applying this library in your project.

support@plusclouds.com

---

## Our Libraries

This library is part of the **NextDeveloper / PlusClouds open-source ecosystem**. Browse all available libraries and find the right building blocks for your next project:

[https://plusclouds.com/us/solutions/libraries](https://plusclouds.com/us/solutions/libraries)

---

## Join the Community

We believe great software is built together. The PlusClouds developer community is a place where engineers share ideas, ask questions, showcase what they have built, and help shape the direction of these libraries. Whether you are integrating a single package or building an entire platform on top of our stack, you are very welcome here.

Come and join us — we would love to see what you build:

[https://plusclouds.com/us/community](https://plusclouds.com/us/community)
