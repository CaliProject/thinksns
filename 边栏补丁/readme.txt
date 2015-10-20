将文件覆盖到addons/theme/stv1/_static/js/plugins/内

要想显示正常必须将以下 css 加入自定义 css 中，可通过 UI 美化插件后台 CSS 自定义增加，也可以手动添加到 global.css 内

/*
* Tooltip
*/
#message-taskbar .message-list li {overflow: visible !important}
.tooltip {
    letter-spacing: 0;
    position: relative;
    display: inline-block
}

.tooltip:before,
.tooltip:after {
    position: absolute;
    visibility: hidden;
    opacity: 0;
    z-index: 999999;
    -webkit-transform: translate3d(0, 0, 0);
    -moz-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0)
}

.tooltip:before {
    content: '';
    border: 6px solid transparent;
}

.tooltip:after {
    height: 22px;
    padding: 11px 11px 0 11px;
    font-size: 13px;
    line-height: 11px;
    content: attr(data-tooltip);
    white-space: nowrap;
    background-color: rgba(0, 0, 0, 0.5)!important;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    font-family: Avenir, Microsoft Yahei, Hiragino Sans GB, Microsoft Sans Serif, WenQuanYi Micro Hei, sans-serif;
    font-weight: normal;
    color: #ecf0f1;
    -webkit-transform: translate(-50%, 0px);
    -moz-transform: translate(-50%, 0px);
    -ms-transform: translate(-50%, 0px);
    -o-transform: translate(-50%, 0px);
    transform: translate(-50%, 0px)
}

.tooltip.tip-top:before,
.tooltip.tip-top:after,
.tooltip.tip-bottom:before,
.tooltip.tip-bottom:after,
.tooltip.tip-left:before,
.tooltip.tip-left:after,
.tooltip.tip-right:before,
.tooltip.tip-right:after {
    -webkit-transition: all 0.2s ease-in-out;
    -moz-transition: all 0.2s ease-in-out;
    -o-transition: all 0.2s ease-in-out;
    -ms-transition: all 0.2s ease-in-out;
    transition: all 0.2s ease-in-out
}

.tooltip.tip-top:before {
    border-top-color: rgba(0, 0, 0, 0.5)
}

.tooltip.tip-bottom:before {
    border-bottom-color: rgba(0, 0, 0, 0.5)
}

.tooltip.tip-left:before {
    border-left-color: rgba(0, 0, 0, 0.5);
    -webkit-transform: translate(-100%, 0px);
    -moz-transform: translate(-100%, 0px);
    -ms-transform: translate(-100%, 0px);
    -o-transform: translate(-100%, 0px);
    transform: translate(-100%, 0px)
}

.tooltip.tip-right:before {
    border-right-color: rgba(0, 0, 0, 0.5)
}

.tooltip:hover,
.tooltip:focus {
    background-color: transparent
}

.tooltip:hover .tip-left:before,
.tooltip:hover .tip-left:after,
.tooltip:focus .tip-left:before,
.tooltip:focus .tip-left:after {
    opacity: 1;
    visibility: visible;
    -webkit-transform: translate(0%, 0px);
    -moz-transform: translate(0%, 0px);
    -ms-transform: translate(0%, 0px);
    -o-transform: translate(0%, 0px);
    transform: translate(0%, 0px)
}

.tooltip:hover:before,
.tooltip:hover:after,
.tooltip:focus:before,
.tooltip:focus:after {
    opacity: 1;
    visibility: visible;
    -webkit-transform: translate(0%, 0px);
    -moz-transform: translate(0%, 0px);
    -ms-transform: translate(0%, 0px);
    -o-transform: translate(0%, 0px);
    transform: translate(0%, 0px)
}

.tip-right:before,
.tip-left:before,
.tip-right:after,
.tip-left:after {
    bottom: 50%
}

.tip-right:before,
.tip-left:before {
    margin-bottom: -5px
}

.tip-right:after,
.tip-left:after {
    margin-bottom: -14.66667px
}

.tip-right:before,
.tip-right:after {
    left: 100%
}

.tip-right:before {
    margin-left: -2px
}

.tip-right:after {
    margin-left: 10px
}

.tip-left:before,
.tip-left:after {
    right: 100%
}

.tip-left:before {
    margin-right: -2px
}

.tip-left:after {
    margin-right: 10px
}

.tip-bottom:before,
.tip-top:before,
.tip-bottom:after,
.tip-top:after {
    left: 50%;
    -webkit-transform: translateX(-50%);
    -moz-transform: translateX(-50%);
    -ms-transform: translateX(-50%);
    -o-transform: translateX(-50%);
    transform: translateX(-50%)
}

.tip-bottom:after,
.tip-top:after {
    width: auto
}

.tip-bottom:before,
.tip-bottom:after {
    top: 100%
}

.tip-bottom:before {
    margin-top: -5px
}

.tip-bottom:after {
    margin-top: 7px
}

.tip-bottom:hover:before,
.tip-bottom:hover:after {
    -webkit-transform: translate(-50%, 0);
    -moz-transform: translate(-50%, 0);
    -ms-transform: translate(-50%, 0);
    -o-transform: translate(-50%, 0);
    transform: translate(-50%, 0)
}

.tip-top:before,
.tip-top:after {
    bottom: 100%
}

.tip-top:before {
    margin-bottom: -5px
}

.tip-top:after {
    margin-bottom: 7px
}

.tip-top:hover:before,
.tip-top:hover:after {
    -webkit-transform: translate(-50%, 0px);
    -moz-transform: translate(-50%, 0px);
    -ms-transform: translate(-50%, 0px);
    -o-transform: translate(-50%, 0px);
    transform: translate(-50%, 0px)
}