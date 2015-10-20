1.将public_header.html覆盖到addons/theme/stv1/内
2.然后将ico-post.png 放到 addons/theme/stv1/_static/image/内

要想显示正常必须将以下 css 加入自定义 css 中，可通过 UI 美化插件后台 CSS 自定义增加，也可以手动添加到 global.css 内

#header .head-bd .person ul li{
    padding: 0 10px !important
}