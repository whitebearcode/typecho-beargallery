$(document).ready(function() {
    "use strict";

    // 通用初始化函数
    const initFunctions = () => {
        handleVerticalMenu();
        handleSocialSharing();
        initBackToTop();
        initStickySidebar();
        handleMenuToggle();
        handleVerticalMenuHeight();
    };

    // 垂直菜单处理
    const handleVerticalMenu = () => {
        const $verticalMenu = $('.bgallery_fl_vertical_menu');
        const windowHeight = $(window).height();

        // 滚动条初始化
        $verticalMenu.find('.scrollable').css('height', windowHeight).niceScroll({
            touchbehavior: false,
            cursorwidth: 0,
            autohidemode: true,
            cursorborder: "0px solid #fff",
            horizrailenabled: false
        });

        // 菜单关闭按钮
        $verticalMenu.on('click', '.vertical_menu_closer a', function(e) {
            e.preventDefault();
            $verticalMenu.removeClass('opened');
            $('.bgallery_fl_menu_trigger').removeClass('opened');
        });
    };

    // 社交分享处理
    const handleSocialSharing = () => {
        $(document).on('click', '.bgallery_fl_gallery_single_in .img_list_nth .title a', function(e) {
            e.preventDefault();
            const $parent = $(this).closest('.title');
            const $social = $parent.next('.share_social');

            $('.title').not($parent).removeClass('opened')
                .find('a').removeClass('opened')
                .next('.share_social').removeClass('opened');

            $(this).toggleClass('opened');
            $parent.toggleClass('opened');
            $social.toggleClass('opened', $(this).hasClass('opened'));
        });
    };

    // 返回顶部功能
    const initBackToTop = () => {
        const $backToTop = $('a.totop');

        $(window).on('scroll', () => {
            $backToTop.toggleClass('opened', $(window).scrollTop() > 1000);
        });

        $backToTop.on('click', (e) => {
            e.preventDefault();
            $('html, body').animate({ scrollTop: 0 }, 600);
        });
    };

    // 粘性侧边栏
    const initStickySidebar = () => {
        $('.sticky_sidebar').theiaStickySidebar({
            additionalMarginTop: 20,
            minWidth: 1040
        });
    };

    // 菜单切换
    const handleMenuToggle = () => {
        $(document).off('click.menuToggle').on('click.menuToggle', '.bgallery_fl_menu_trigger a', function(e) {
            e.preventDefault();
            const $menu = $('.bgallery_fl_vertical_menu');
            const isOpening = !$menu.hasClass('opened');
            
            $(this).toggleClass('opened', isOpening);
            $menu.toggleClass('opened', isOpening);
            
            // 关闭时同步触发器状态
            if (!isOpening) {
                $('.bgallery_fl_menu_trigger').removeClass('opened');
            }
        });
    };

    // 垂直菜单高度调整
    const handleVerticalMenuHeight = () => {
        const adjustHeight = () => {
            $('.bgallery_fl_vertical_menu').height($(window).height());
        };
        adjustHeight();
        $(window).on('resize', debounce(adjustHeight, 150));
    };

    // PJAX 处理
    const initPjax = () => {
        $(document).pjax('a[target!=_blank][rel!=group][pjax!=no]', '#pjax', {
            fragment: '#pjax',
            timeout: 50000
        });

        $(document)
            .on('pjax:start', function() {
                // 清理旧状态
                $('.bgallery_fl_vertical_menu').removeClass('opened');
                $('.bgallery_fl_menu_trigger').removeClass('opened');
                NProgress.start();
            })
            .on('pjax:end', NProgress.done)
            .on('pjax:complete', function() {
                // 销毁旧滚动条实例
                $('.bgallery_fl_vertical_menu .scrollable').each(function() {
    const nsInstance = $(this).getNiceScroll();
    if (nsInstance && nsInstance.length) {
        nsInstance.remove();
    }
});
                // 重新初始化
                initFunctions();
                // 确保菜单状态重置
                $('.bgallery_fl_vertical_menu').removeClass('opened');
            });
    };

    // 防抖
    const debounce = (func, wait) => {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    };

    // 初始化
    initFunctions();
    initPjax();

    console.log("%c Beargallery v1.0.6 %c https://github.com/whitebearcode/typecho-beargallery",
        "border-radius:10px 0 0 10px;color: #fadfa3; background: #030307; padding:5px 0;",
        "border-radius:0 10px 10px 0;background: #fadfa3; padding:5px 0;");
});