{
    navigation_callback: (matches) => {
        let form = document.querySelector("#CobaltBlog--form");
        if (!matches) form.addEventListener("requestSuccess", data => {
            window.location = window.location + data.detail.url_safe_name
        });
    }
}