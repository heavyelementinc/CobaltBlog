CobaltBlog
==========
A simple, flexible markdown blog plugin for [Cobalt Engine](https://github.com/heavyelementinc/cobalt-core).

This plugin creates six routes:

| context | method | route                        | description                        |
| ------- | :----- | :--------------------------- | :--------------------------------- |
| web     | GET    | `/blog/`                     | The main public index              |
| web     | GET    | `/blog/read/{post_name}`     | An individual post                 |
| admin   | GET    | `/admin/blog/`               | The admin index                    |
| admin   | GET    | `/admin/blog/edit/{post}`    | Edit post content                  |
| apiv1   | PUT    | `/api/v1/blog/update/{post}` | API endpoint for modifying content |
| apiv1   | DELETE | `/api/v1/blog/{post}`        | API endpoint for modifying content |

Both the routes `/blog` can be overridden by adding the following settings to your app's `settings.json`:

```json
{
    "CobaltBlog_public_path": "/posts",
    "CobaltBlog_admin_path": "/posts",
    "CobaltBlog_update_path": "/posts"
}
```

Which would result in `/blog/read/{post_name}` becoming `/posts/read/{post_name}` and so forth.

---
## More customization
You can also customize the behavior of the app by overriding other settings in your app's `settings.json` file:

```json
{
    "CobaltBlog_main_index_title": "Blog Home", // The value of the <title/> tag
    "CobaltBlog_index_limit": 5, // The maximum number of posts to be displayed on the main index per page
}
```

&copy;2021 Gardiner Bryant - Heavy Element, Inc.