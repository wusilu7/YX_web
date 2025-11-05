(function ($) {
    'use strict';

    // 扩展默认配置
    $.extend($.fn.bootstrapTable.defaults, {
        paginationShowPageGo: true,     // 默认显示跳转到指定页码的组件
        contentType: 'application/x-www-form-urlencoded'
    });

    // 保存原有的 BootstrapTable 构造函数
    var BootstrapTable = $.fn.bootstrapTable.Constructor,
        _initPagination = BootstrapTable.prototype.initPagination;

    /* 1.分页新增页码跳转扩展 */
    BootstrapTable.prototype.initPagination = function () {
        _initPagination.apply(this, Array.prototype.slice.apply(arguments));
        // 判断是否显示跳转到指定页码的组件
        if (this.options.pagination && this.options.paginationShowPageGo) {
            var html = [];
            // 渲染跳转到指定页的元素
            html.push(
                '<div class="input-group jin-skip ">' +
                '<input type="text" class="form-control page-input" placeholder="页">' +
                '<span class="input-group-btn"><button class="btn btn-default page-go" type="button">跳转</button>' +
                '</span>' +
                '</div>'
            );
            // 放到原先的分页组件后面
            this.$pagination.find('ul.pagination').after(html.join(''));
            // 点击按钮触发跳转到指定页函数
            this.$pagination.find('.page-go').off('click').on('click', $.proxy(this.onPageGo, this));
            // 手动输入页码校验，只允许输入正整数
            this.$pagination.find('.page-input').off('keyup').on('keyup', function () {
                this.value = this.value.length == 1 ? this.value.replace(/[^1-9]/g, '') : this.value.replace(/\D/g, '');
            });
            // 支持回车键跳转
            this.$pagination.find('.page-input').off('keypress').on('keypress', $.proxy(function (event) {
                if (event.which === 13) {
                    this.onPageGo(event);
                }
            }, this));
        }
    };
    // 自定义跳转到某页的函数
    BootstrapTable.prototype.onPageGo = function (event) {
        // 获取手动输入的要跳转到的页码元素
        var $toPage = this.$pagination.find('.page-input');
        // 当前页不做处理
        if (this.options.pageNumber === +$toPage.val()) {
            return false;
        }
        // 调用官方的函数
        this.selectPage(+$toPage.val());
        return false;
    };
})(jQuery);