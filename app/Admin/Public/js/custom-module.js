// Radio 单选框
function createTabComponent({containerId, tabs, defaultSelected, onTabChange, size = {padding: '5px 10px'}}) {
    // 创建并插入样式
    const style = document.createElement('style');
    style.textContent = `
        .tab-container {
            display: inline-flex;
            border-radius: 4px;
            overflow: hidden;
            width: fit-content;
            border: 1px solid #ddd;
        }
        .tab-item {
            padding: ${size.padding};
            cursor: pointer;
            background-color: #f7f7f7;
            transition: background-color 0.3s ease, color 0.3s ease;
            border-right: 1px solid #ddd;
            margin: 0;
        }
        .tab-item:last-child {
            border-right: none;
        }
        .tab-item.active {
            background-color: #4a90e2;
            color: #fff;
        }
        .tab-item:not(.active):hover {
            color: #4a90e2;
        }
        .tab-item:first-child {
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }
        .tab-item:last-child {
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }
    `;
    document.head.appendChild(style);

    // 获取容器
    const container = document.getElementById(containerId);
    container.classList.add('tab-container');
    $(container).data('selectedValue', defaultSelected);

    // 创建每个选项
    tabs.forEach(tab => {
        const tabItem = document.createElement('div');
        tabItem.classList.add('tab-item');
        if (tab.value === defaultSelected) {
            tabItem.classList.add('active');
        }
        tabItem.textContent = tab.title;
        tabItem.dataset.value = tab.value;

        tabItem.addEventListener('click', function () {
            // 移除所有 tab 的 active 类
            const allTabs = container.querySelectorAll('.tab-item');
            allTabs.forEach(t => t.classList.remove('active'));
            // 为当前点击的 tab 添加 active 类
            this.classList.add('active');
            // 获取选中的值并更新容器属性
            const value = this.getAttribute('data-value');
            $(container).data('selectedValue', value);
            // 触发回调函数
            if (onTabChange) {
                onTabChange(value);
            }
        });

        container.appendChild(tabItem);
    });
}