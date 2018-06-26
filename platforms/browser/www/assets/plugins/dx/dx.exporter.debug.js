/*! 
* DevExtreme Exporter
* Version: 15.2.3
* Build date: Dec 2, 2015
*
* Copyright (c) 2012 - 2015 Developer Express Inc. ALL RIGHTS RESERVED
* EULA: https://www.devexpress.com/Support/EULAs/DevExtreme.xml
*/

"use strict";

if (!window.DevExpress || !DevExpress.MOD_TMP_WIDGETS_FOR_EXPORTER) {
    /*! Module tmp-widgets-for-exporter, file ui.menuBase.js */
    (function($, DX, undefined) {
        var ui = DX.ui,
            commonUtils = DX.require("/utils/utils.common"),
            inkRipple = DX.require("/utils/utils.inkRipple"),
            HierarchicalCollectionWidget = DX.require("/ui/hierarchicalCollectionWidget/ui.hierarchicalCollectionWidget"),
            devices = DX.require("/devices");
        var DX_MENU_CLASS = "dx-menu",
            DX_MENU_BASE_CLASS = "dx-menu-base",
            ITEM_CLASS = DX_MENU_CLASS + "-item",
            DX_MENU_SELECTED_ITEM_CLASS = ITEM_CLASS + "-selected",
            DX_MENU_ITEM_WRAPPER_CLASS = ITEM_CLASS + "-wrapper",
            DX_MENU_ITEMS_CONTAINER_CLASS = DX_MENU_CLASS + "-items-container",
            DX_MENU_ITEM_EXPANDED_CLASS = ITEM_CLASS + "-expanded",
            DX_MENU_SEPARATOR_CLASS = DX_MENU_CLASS + "-separator",
            DX_MENU_ITEM_LAST_GROUP_ITEM = DX_MENU_CLASS + "-last-group-item",
            DX_ITEM_HAS_TEXT = ITEM_CLASS + "-has-text",
            DX_ITEM_HAS_ICON = ITEM_CLASS + "-has-icon",
            DX_ITEM_HAS_SUBMENU = ITEM_CLASS + "-has-submenu",
            DX_MENU_ITEM_POPOUT_CLASS = ITEM_CLASS + "-popout",
            DX_MENU_ITEM_POPOUT_CONTAINER_CLASS = DX_MENU_ITEM_POPOUT_CLASS + "-container",
            DX_MENU_ITEM_CAPTION_CLASS = ITEM_CLASS + "-text",
            SINGLE_SELECTION_MODE = "single",
            DEFAULT_DELAY = {
                show: 50,
                hide: 300
            };
        var dxMenuBase = HierarchicalCollectionWidget.inherit({
                _getDefaultOptions: function() {
                    return $.extend(this.callBase(), {
                            items: [],
                            cssClass: "",
                            activeStateEnabled: true,
                            showSubmenuMode: {
                                name: "onHover",
                                delay: {
                                    show: 50,
                                    hide: 300
                                }
                            },
                            animation: {
                                show: {
                                    type: "fade",
                                    from: 0,
                                    to: 1,
                                    duration: 100
                                },
                                hide: {
                                    type: "fade",
                                    from: 1,
                                    to: 0,
                                    duration: 100
                                }
                            },
                            selectionByClick: false,
                            focusOnSelectedItem: false,
                            _remoteSelectionSync: false,
                            _itemAttributes: {role: "menuitem"},
                            useInkRipple: false
                        })
                },
                _defaultOptionsRules: function() {
                    return this.callBase().concat([{
                                device: {platform: "android"},
                                options: {useInkRipple: true}
                            }])
                },
                _activeStateUnit: "." + ITEM_CLASS,
                _itemDataKey: function() {
                    return "dxMenuItemDataKey"
                },
                _itemClass: function() {
                    return ITEM_CLASS
                },
                _setAriaSelected: $.noop,
                _selectedItemClass: function() {
                    return DX_MENU_SELECTED_ITEM_CLASS
                },
                _widgetClass: function() {
                    return DX_MENU_BASE_CLASS
                },
                _focusTarget: function() {
                    return this._itemContainer()
                },
                _eventBindingTarget: function() {
                    return this._itemContainer()
                },
                _supportedKeys: function() {
                    var selectItem = function(e) {
                            var $item = this.option("focusedElement");
                            if (!$item || !this._isSelectionEnabled())
                                return;
                            this.selectItem($item[0])
                        };
                    return $.extend(this.callBase(), {
                            space: selectItem,
                            pageUp: $.noop,
                            pageDown: $.noop
                        })
                },
                _isSelectionEnabled: function() {
                    return this.option("selectionMode") === SINGLE_SELECTION_MODE
                },
                _init: function() {
                    this.callBase();
                    this._renderSelectedItem();
                    this._initActions()
                },
                _useCustomExpressions: function() {
                    return this.callBase() || this.option("itemsExpr") !== "items"
                },
                _getTextContainer: function(itemData) {
                    var itemText = this._displayGetter(itemData),
                        $itemContainer = $('<span>').addClass(DX_MENU_ITEM_CAPTION_CLASS),
                        itemContent = $.isPlainObject(itemData) ? itemText : String(itemData);
                    return itemText && $itemContainer.html(itemContent)
                },
                _getPopuotContainer: function(itemData) {
                    var items = this._itemsGetter(itemData),
                        $popOutContainer;
                    if (items && items.length) {
                        var $popOutImage = $('<div>').addClass(DX_MENU_ITEM_POPOUT_CLASS);
                        $popOutContainer = $('<span>').addClass(DX_MENU_ITEM_POPOUT_CONTAINER_CLASS).append($popOutImage)
                    }
                    return $popOutContainer
                },
                _getDataAdapterOptions: function() {
                    return {
                            rootValue: 0,
                            multipleSelection: false,
                            recursiveSelection: false,
                            recursiveExpansion: false,
                            searchValue: ""
                        }
                },
                _selectByItem: function(selectedItem) {
                    if (!selectedItem)
                        return;
                    var nodeToSelect = this._dataAdapter.getNodeByItem(selectedItem);
                    this._dataAdapter.toggleSelection(nodeToSelect.internalFields.key, true)
                },
                _renderSelectedItem: function() {
                    var selectedKeys = this._dataAdapter.getSelectedNodesKeys(),
                        selectedKey = selectedKeys.length && selectedKeys[0],
                        selectedItem = this.option("selectedItem");
                    if (!selectedKey) {
                        this._selectByItem(selectedItem);
                        return
                    }
                    var node = this._dataAdapter.getNodeByKey(selectedKey);
                    if (node.selectable === false)
                        return;
                    if (!selectedItem) {
                        this.option("selectedItem", node.internalFields.item);
                        return
                    }
                    if (selectedItem !== node.internalFields.item) {
                        this._dataAdapter.toggleSelection(selectedKey, false);
                        this._selectByItem(selectedItem)
                    }
                },
                _initActions: $.noop,
                _render: function() {
                    this.callBase();
                    this._addCustomCssClass(this.element());
                    this.option("useInkRipple") && this._renderInkRipple()
                },
                _renderInkRipple: function() {
                    this._inkRipple = inkRipple.render()
                },
                _toggleActiveState: function($element, value, e) {
                    this.callBase.apply(this, arguments);
                    if (!this._inkRipple)
                        return;
                    var config = {
                            element: $element,
                            jQueryEvent: e
                        };
                    if (value)
                        this._inkRipple.renderWave(config);
                    else
                        this._inkRipple.removeWave(config)
                },
                _getShowSubmenuMode: function() {
                    var defaultValue = "onClick",
                        optionValue = this.option("showSubmenuMode");
                    optionValue = commonUtils.isObject(optionValue) ? optionValue.name : optionValue;
                    return this._isDesktopDevice() ? optionValue : defaultValue
                },
                _initSelectedItems: $.noop,
                _isDesktopDevice: function() {
                    return devices.real().deviceType === "desktop"
                },
                _initEditStrategy: function() {
                    var strategy = ui.CollectionWidget.MenuBaseEditStrategy;
                    this._editStrategy = new strategy(this)
                },
                _addCustomCssClass: function($element) {
                    $element.addClass(this.option("cssClass"))
                },
                _itemWrapperSelector: function() {
                    return "." + DX_MENU_ITEM_WRAPPER_CLASS
                },
                _hoverStartHandler: function(e) {
                    var that = this,
                        $itemElement = that._getItemElementByEventArgs(e);
                    if (!$itemElement || that._isItemDisabled($itemElement))
                        return;
                    e.stopPropagation();
                    that.option("focusedElement", $itemElement);
                    if (that._getShowSubmenuMode() === "onHover")
                        this._showSubmenusTimeout = setTimeout($.proxy(that._showSubmenu, that, $itemElement), that._getSubmenuDelay("show"))
                },
                _isItemDisabled: function($item) {
                    return this._disabledGetter($item.data(this._itemDataKey()))
                },
                _showSubmenu: function($itemElement) {
                    clearTimeout(this._showSubmenusTimeout);
                    if (this._hasFocusClass($itemElement))
                        this._addExpandedClass($itemElement)
                },
                _addExpandedClass: function($itemElement) {
                    $itemElement.addClass(DX_MENU_ITEM_EXPANDED_CLASS)
                },
                _getSubmenuDelay: function(action) {
                    var delay = this.option("showSubmenuMode").delay;
                    if (!delay)
                        return DEFAULT_DELAY[action];
                    return commonUtils.isObject(delay) ? delay[action] : delay
                },
                _getItemElementByEventArgs: function(eventArgs) {
                    var $target = $(eventArgs.target);
                    if ($target.hasClass(this._itemClass()) || $target.get(0) === eventArgs.currentTarget)
                        return $target;
                    while (!$target.hasClass(this._itemClass())) {
                        $target = $target.parent();
                        if ($target.hasClass("dx-submenu"))
                            return null
                    }
                    return $target
                },
                _hoverEndHandler: $.noop,
                _hasSubmenu: function(node) {
                    return node.internalFields.childrenKeys.length
                },
                _renderContentImpl: function() {
                    this._renderItems(this._dataAdapter.getRootNodes())
                },
                _renderItems: function(nodes, submenuContainer) {
                    var that = this,
                        $nodeContainer;
                    if (nodes.length) {
                        $nodeContainer = this._renderContainer(this.element(), submenuContainer);
                        $.each(nodes, function(index, node) {
                            that._renderItem(index, node, $nodeContainer)
                        })
                    }
                },
                _renderContainer: function($wrapper) {
                    return $("<ul>").appendTo($wrapper).addClass(DX_MENU_ITEMS_CONTAINER_CLASS)
                },
                _createDOMElement: function($nodeContainer) {
                    var $node = $("<li>").appendTo($nodeContainer).addClass(DX_MENU_ITEM_WRAPPER_CLASS);
                    return $node
                },
                _renderItem: function(index, node, $nodeContainer) {
                    var items = this.option("items"),
                        $itemFrame;
                    this._renderSeparator(node, index, $nodeContainer);
                    if (node.internalFields.item.visible === false)
                        return;
                    var $node = this._createDOMElement($nodeContainer);
                    if (items[index + 1] && items[index + 1].beginGroup)
                        $node.addClass(DX_MENU_ITEM_LAST_GROUP_ITEM);
                    $itemFrame = this.callBase(index, node.internalFields.item, $node);
                    if (node.internalFields.item === this.option("selectedItem"))
                        $itemFrame.addClass(DX_MENU_SELECTED_ITEM_CLASS);
                    this._addContentClasses(node, $itemFrame);
                    if (this._hasSubmenu(node))
                        this.setAria("haspopup", "true", $itemFrame)
                },
                _addContentClasses: function(node, $itemFrame) {
                    if (this._displayGetter(node))
                        $itemFrame.addClass(DX_ITEM_HAS_TEXT);
                    if (node.icon || node.iconSrc)
                        $itemFrame.addClass(DX_ITEM_HAS_ICON);
                    if (this._hasSubmenu(node))
                        $itemFrame.addClass(DX_ITEM_HAS_SUBMENU)
                },
                _postprocessRenderItem: function(args) {
                    var $itemElement = $(args.itemElement),
                        selectedIndex = this._dataAdapter.getSelectedNodesKeys(),
                        node;
                    if (!selectedIndex.length || !this._selectedGetter(args.itemData) || !this._isItemSelectable(args.itemData)) {
                        this._setAriaSelected($itemElement, "false");
                        return
                    }
                    node = this._dataAdapter.getNodeByItem(args.itemData);
                    if (node.internalFields.key === selectedIndex[0]) {
                        $itemElement.addClass(this._selectedItemClass());
                        this._setAriaSelected($itemElement, "true")
                    }
                    else
                        this._setAriaSelected($itemElement, "false")
                },
                _isItemSelectable: function(item) {
                    return item.selectable !== false
                },
                _renderSeparator: function(node, index, $itemsContainer) {
                    if (node.beginGroup && index > 0)
                        this._needSeparate = true;
                    if (node.visible !== false && this._needSeparate) {
                        $("<li>").appendTo($itemsContainer).addClass(DX_MENU_SEPARATOR_CLASS);
                        this._needSeparate = false
                    }
                },
                _itemClickHandler: function(e) {
                    var itemClickActionHandler = this._createAction($.proxy(this._updateSubmenuVisibilityOnClick, this));
                    this._itemJQueryEventHandler(e, "onItemClick", {}, {afterExecute: $.proxy(itemClickActionHandler, this)})
                },
                _updateSubmenuVisibilityOnClick: function(actionArgs) {
                    this._updateSelectedItemOnClick(actionArgs);
                    if (this._getShowSubmenuMode() === "onClick")
                        this._addExpandedClass(actionArgs.args[0].itemElement)
                },
                _updateSelectedItemOnClick: function(actionArgs) {
                    var args = actionArgs.args ? actionArgs.args[0] : actionArgs,
                        selectedItemKey;
                    if (!this._isItemSelectionAllowed(args.itemData))
                        return;
                    selectedItemKey = this._dataAdapter.getSelectedNodesKeys();
                    var selectedNode = selectedItemKey.length && this._dataAdapter.getNodeByKey(selectedItemKey[0]);
                    if (selectedNode)
                        this._toggleItemSelection(selectedNode, false);
                    if (!selectedNode || selectedNode.internalFields.item !== args.itemData)
                        this.selectItem(args.itemData);
                    else {
                        this._fireSelectionChangeEvent(null, this.option("selectedItem"));
                        this._setOptionSilent("selectedItem", null)
                    }
                },
                _isItemSelectionAllowed: function(item) {
                    var isSelectionByClickEnabled = this._isSelectionEnabled() && this.option("selectionByClick");
                    return this._itemContainer() && isSelectionByClickEnabled && this._isItemSelectable(item) && !this._itemsGetter(item)
                },
                _syncSelectionOptions: $.noop,
                _optionChanged: function(args) {
                    if (this._cancelOptionChange)
                        return;
                    switch (args.name) {
                        case"showSubmenuMode":
                            break;
                        case"selectedItem":
                            var itemData = args.value,
                                node = this._dataAdapter.getNodeByItem(itemData),
                                selectedKey = this._dataAdapter.getSelectedNodesKeys()[0];
                            if (node && node.internalFields.key !== selectedKey) {
                                if (node.selectable === false)
                                    break;
                                if (selectedKey)
                                    this._toggleItemSelection(this._dataAdapter.getNodeByKey(selectedKey), false);
                                this._toggleItemSelection(node, true);
                                this._updateSelectedItems()
                            }
                            break;
                        case"_remoteSelectionSync":
                        case"cssClass":
                        case"position":
                        case"selectionByClick":
                        case"animation":
                        case"useInkRipple":
                            this._invalidate();
                            break;
                        default:
                            this.callBase(args)
                    }
                },
                _toggleItemSelection: function(node, value) {
                    var itemElement = this._getElementByItem(node.internalFields.item);
                    itemElement && $(itemElement).toggleClass(DX_MENU_SELECTED_ITEM_CLASS);
                    this._dataAdapter.toggleSelection(node.internalFields.key, value)
                },
                _getElementByItem: function(itemData) {
                    var that = this,
                        result;
                    $.each(this._itemContainer().find("." + ITEM_CLASS), function(_, itemElement) {
                        if ($(itemElement).data(that._itemDataKey()) !== itemData)
                            return true;
                        result = itemElement;
                        return false
                    });
                    return result
                },
                _updateSelectedItems: function(oldSelection, newSelection) {
                    if (oldSelection || newSelection) {
                        this._updateSelection(newSelection, oldSelection);
                        this._fireSelectionChangeEvent(newSelection, oldSelection)
                    }
                },
                _fireSelectionChangeEvent: function(addedSelection, removedSelection) {
                    this._createActionByOption("onSelectionChanged", {excludeValidators: ["disabled", "readOnly"]})({
                        addedItems: [addedSelection],
                        removedItems: [removedSelection]
                    })
                },
                selectItem: function(itemElement) {
                    var itemData = itemElement.nodeType ? this._getItemData(itemElement) : itemElement,
                        node = this._dataAdapter.getNodeByItem(itemData),
                        selectedKey = this._dataAdapter.getSelectedNodesKeys()[0],
                        selectedItem = this.option("selectedItem");
                    if (node.internalFields.key !== selectedKey) {
                        if (selectedKey)
                            this._toggleItemSelection(this._dataAdapter.getNodeByKey(selectedKey), false);
                        this._toggleItemSelection(node, true);
                        this._updateSelectedItems(selectedItem, itemData);
                        this._setOptionSilent("selectedItem", itemData)
                    }
                },
                unselectItem: function(itemElement) {
                    var itemData = itemElement.nodeType ? this._getItemData(itemElement) : itemElement,
                        node = this._dataAdapter.getNodeByItem(itemData),
                        selectedItem = this.option("selectedItem");
                    if (node.internalFields.selected) {
                        this._toggleItemSelection(node, false);
                        this._updateSelectedItems(selectedItem, null);
                        this._setOptionSilent("selectedItem", null)
                    }
                }
            });
        dxMenuBase.publicName("dxMenuBase");
        ui.dxMenuBase = dxMenuBase
    })(jQuery, DevExpress);
    /*! Module tmp-widgets-for-exporter, file ui.menuBase.edit.strategy.js */
    (function($, DX, undefined) {
        var ui = DX.ui,
            errors = DevExpress.require("/ui/ui.errors");
        ui.CollectionWidget.MenuBaseEditStrategy = ui.CollectionWidget.PlainEditStrategy.inherit({
            _getPlainItems: function() {
                return $.map(this._collectionWidget.option("items"), function getMenuItems(item) {
                        return item.items ? [item].concat($.map(item.items, getMenuItems)) : item
                    })
            },
            _stringifyItem: function(item) {
                var that = this;
                return JSON.stringify(item, function(key, value) {
                        if (key === "template")
                            return that._getTemplateString(value);
                        return value
                    })
            },
            _getTemplateString: function(template) {
                var result;
                if (typeof template === "object")
                    result = $(template).text();
                else
                    result = template.toString();
                return result
            },
            selectedItemIndices: function() {
                var selectedIndices = [],
                    dataAdapter = this._collectionWidget._dataAdapter,
                    items = dataAdapter.getData(),
                    selectedItem = dataAdapter.getNodeByKey(dataAdapter.getSelectedNodesKeys()[0]);
                if (selectedItem) {
                    var index = $.inArray(selectedItem, items);
                    if (index !== -1)
                        selectedIndices.push(index);
                    else
                        errors.log("W1002", selectedItem)
                }
                return selectedIndices
            },
            fetchSelectedItems: function(indices) {
                indices = indices || this._collectionWidget._selectedItemIndices;
                var items = this._getPlainItems(),
                    selectedItems = [];
                $.each(indices, function(_, index) {
                    selectedItems.push(items[index])
                });
                return selectedItems
            }
        })
    })(jQuery, DevExpress);
    /*! Module tmp-widgets-for-exporter, file ui.contextMenu.js */
    (function($, DX, undefined) {
        var ui = DX.ui,
            fx = DX.fx,
            positionUtils = DX.require("/utils/utils.position"),
            commonUtils = DX.require("/utils/utils.common"),
            devices = DX.require("/devices"),
            registerComponent = DX.require("/componentRegistrator"),
            eventUtils = DX.require("/ui/events/ui.events.utils");
        var DX_MENU_CLASS = "dx-menu",
            DX_MENU_ITEM_CLASS = DX_MENU_CLASS + "-item",
            DX_MENU_ITEM_EXPANDED_CLASS = DX_MENU_ITEM_CLASS + "-expanded",
            DX_MENU_PHONE_CLASS = "dx-menu-phone-overlay",
            DX_MENU_ITEMS_CONTAINER_CLASS = DX_MENU_CLASS + "-items-container",
            DX_MENU_ITEM_WRAPPER_CLASS = DX_MENU_ITEM_CLASS + "-wrapper",
            DX_SUBMENU_CLASS = "dx-submenu",
            DX_CONTEXT_MENU_CLASS = "dx-context-menu",
            DX_HAS_CONTEXT_MENU_CLASS = "dx-has-context-menu",
            DX_STATE_DISABLED_CLASS = "dx-state-disabled",
            FOCUS_UP = "up",
            FOCUS_DOWN = "down",
            FOCUS_LEFT = "left",
            FOCUS_RIGHT = "right",
            FOCUS_FIRST = "first",
            FOCUS_LAST = "last",
            ACTIONS = ["onShowing", "onShown", "onHiding", "onHidden", "onPositioning", "onLeftFirstItem", "onLeftLastItem", "onCloseRootSubmenu", "onExpandLastSubmenu"],
            LOCAL_SUBMENU_DIRECTIONS = [FOCUS_UP, FOCUS_DOWN, FOCUS_FIRST, FOCUS_LAST];
        registerComponent("dxContextMenu", ui, ui.dxMenuBase.inherit({
            _getDefaultOptions: function() {
                return $.extend(this.callBase(), {
                        alternativeInvocationMode: {
                            enabled: false,
                            invokingElement: null
                        },
                        position: {
                            at: "top left",
                            my: "top left"
                        },
                        onShowing: null,
                        onShown: null,
                        onHiding: null,
                        onHidden: null,
                        onPositioning: null,
                        submenuDirection: "auto",
                        visible: false,
                        target: window,
                        onLeftFirstItem: null,
                        onLeftLastItem: null,
                        onCloseRootSubmenu: null,
                        onExpandLastSubmenu: null
                    })
            },
            _initActions: function() {
                this._actions = {};
                $.each(ACTIONS, $.proxy(function(index, action) {
                    this._actions[action] = this._createActionByOption(action) || $.noop
                }, this))
            },
            _setOptionsByReference: function() {
                this.callBase();
                $.extend(this._optionsByReference, {
                    animation: true,
                    position: true,
                    selectedItem: true
                })
            },
            _itemContainer: function() {
                return this._overlay && this._overlay.content()
            },
            _supportedKeys: function() {
                var selectItem = function(e) {
                        var $item = this.option("focusedElement");
                        this.hide();
                        if (!$item || !this._isSelectionEnabled())
                            return;
                        this.selectItem($item[0])
                    };
                return $.extend(this.callBase(), {
                        space: selectItem,
                        esc: this.hide
                    })
            },
            _moveFocus: function(location) {
                var $items = this._getItemsByLocation(location),
                    $oldTarget = this._getActiveItem(true),
                    $newTarget,
                    $focusedItem = this.option("focusedElement");
                switch (location) {
                    case FOCUS_UP:
                        $newTarget = $focusedItem ? this._prevItem($items) : $items.last();
                        if ($oldTarget.is($items.first()))
                            this._actions.onLeftFirstItem($oldTarget);
                        break;
                    case FOCUS_DOWN:
                        $newTarget = $focusedItem ? this._nextItem($items) : $items.first();
                        if ($oldTarget.is($items.last()))
                            this._actions.onLeftLastItem($oldTarget);
                        break;
                    case FOCUS_RIGHT:
                        $newTarget = this.option("rtlEnabled") ? this._hideSubmenuHandler($items) : this._expandSubmenuHandler($items, location);
                        break;
                    case FOCUS_LEFT:
                        $newTarget = this.option("rtlEnabled") ? this._expandSubmenuHandler($items, location) : this._hideSubmenuHandler($items);
                        break;
                    case FOCUS_FIRST:
                        $newTarget = $items.first();
                        break;
                    case FOCUS_LAST:
                        $newTarget = $items.last();
                        break;
                    default:
                        return this.callBase(location)
                }
                if ($newTarget.length !== 0)
                    this.option("focusedElement", $newTarget)
            },
            _getItemsByLocation: function(location) {
                var $items,
                    $activeItem = this._getActiveItem(true),
                    expandedLocation = this.option("rtlEnabled") ? FOCUS_LEFT : FOCUS_RIGHT;
                if ($.inArray(location, LOCAL_SUBMENU_DIRECTIONS) >= 0)
                    $items = $activeItem.closest("." + DX_MENU_ITEMS_CONTAINER_CLASS).children().children();
                else {
                    $items = this._itemElements();
                    if (location !== expandedLocation)
                        $items = $items.filter(":visible")
                }
                return $items
            },
            _getAriaTarget: function() {
                return this.element()
            },
            _refreshActiveDescendant: function() {
                var $target = this._overlay.content(),
                    id = this.getFocusedItemId();
                if (!$target)
                    return this.callBase();
                this.setAria("activedescendant", "", $target);
                this.setAria("activedescendant", id, $target)
            },
            _hideSubmenuHandler: function($items) {
                var $curItem = this._getActiveItem(true),
                    $parentItem = $curItem.parents("." + DX_MENU_ITEM_EXPANDED_CLASS).first();
                if ($parentItem.length) {
                    this._hideSubmenusOnSameLevel($parentItem);
                    return $parentItem
                }
                this._actions.onCloseRootSubmenu($curItem);
                return $curItem
            },
            _expandSubmenuHandler: function($items, location) {
                var $curItem = this._getActiveItem(true),
                    node = this._dataAdapter.getNodeByItem(this._getItemData($curItem)),
                    isItemHasSubmenu = this._hasSubmenu(node),
                    $submenu = $curItem.children("." + DX_SUBMENU_CLASS);
                if (isItemHasSubmenu && !$curItem.hasClass(DX_STATE_DISABLED_CLASS)) {
                    if (!$submenu.length || $submenu.css("visibility") === "hidden")
                        this._showSubmenu($curItem);
                    return this._nextItem(this._getItemsByLocation(location))
                }
                this._actions.onExpandLastSubmenu($curItem);
                return $curItem
            },
            _render: function() {
                this.element().addClass(DX_HAS_CONTEXT_MENU_CLASS);
                this.callBase();
                this.setAria("role", "menu")
            },
            _renderContentImpl: function() {
                this._renderContextMenuOverlay();
                this._detachShowContextMenuEvents(this.option("target"));
                this._attachShowContextMenuEvents();
                this._attachInvokeContextMenuEvents();
                this.callBase()
            },
            _renderContextMenuOverlay: function() {
                var overlayOptions = this._getOverlayOptions(),
                    $overlayElement = $("<div>"),
                    $overlayContent;
                this._overlay = this._createComponent($overlayElement.appendTo(this._$element), "dxOverlay", overlayOptions);
                $overlayContent = this._overlay.content();
                $overlayContent.addClass(DX_CONTEXT_MENU_CLASS);
                this._addCustomCssClass($overlayContent);
                this._addPlatformDependentClass($overlayContent);
                if (this._overlay.option("visible"))
                    this._show()
            },
            _addPlatformDependentClass: function($element) {
                if (devices.current().phone)
                    $element.addClass(DX_MENU_PHONE_CLASS)
            },
            _detachShowContextMenuEvents: function(target) {
                var eventName = eventUtils.addNamespace("dxcontextmenu", this.NAME);
                $(target).off(eventName);
                this._overlay && this._overlay.content().off(eventName)
            },
            _attachShowContextMenuEvents: function() {
                var that = this,
                    eventName = eventUtils.addNamespace("dxcontextmenu", this.NAME),
                    contextMenuAction = this._createAction($.proxy(function(e) {
                        if (!that.option("alternativeInvocationMode").enabled)
                            that._show(e.jQueryEvent)
                    }, this), {validatingTargetName: "target"});
                contextMenuAction = this._createAction(contextMenuAction);
                this._overlay && this._overlay.content().on(eventName, $.noop);
                $(this.option("target")).on(eventName, $.proxy(function(e) {
                    contextMenuAction({
                        jQueryEvent: e,
                        target: $(e.currentTarget)
                    })
                }, this))
            },
            _attachInvokeContextMenuEvents: function() {
                var that = this,
                    eventName = eventUtils.addNamespace("dxclick", this.NAME),
                    contextMenuAction = this._createAction($.proxy(function() {
                        that.toggle()
                    }, this));
                if (this.option("alternativeInvocationMode").enabled && this._getInvokeTarget())
                    $(this._getInvokeTarget()).off(eventName).on(eventName, $.proxy(function(e) {
                        contextMenuAction({jQueryEvent: e})
                    }, this))
            },
            _getInvokeTarget: function() {
                return this.option("alternativeInvocationMode").invokingElement
            },
            _hoverEndHandler: function(e) {
                e.stopPropagation()
            },
            _renderDimensions: $.noop,
            _renderContainer: function($wrapper, submenuContainer) {
                var $itemsContainer,
                    $holder = submenuContainer || this._itemContainer();
                $wrapper = $("<div>");
                $wrapper.appendTo($holder).addClass(DX_SUBMENU_CLASS).css("visibility", submenuContainer ? "hidden" : "visible");
                $itemsContainer = this.callBase($wrapper);
                if (submenuContainer)
                    return $itemsContainer;
                if (this.option("width"))
                    return $itemsContainer.css("min-width", this.option("width"));
                if (this.option("height"))
                    return $itemsContainer.css("min-height", this.option("height"));
                return $itemsContainer
            },
            _renderSubmenuItems: function(node, $itemFrame) {
                this._renderItems(this._getChildNodes(node), $itemFrame)
            },
            _getOverlayOptions: function() {
                var position = this.option("position"),
                    overlayAnimation = this.option("animation"),
                    overlayOptions = {
                        focusStateEnabled: this.option("focusStateEnabled"),
                        animation: overlayAnimation,
                        closeOnOutsideClick: $.proxy(this._closeOnOutsideClickHandler, this),
                        closeOnTargetScroll: true,
                        deferRendering: false,
                        position: {
                            at: position.at,
                            my: position.my,
                            of: this.option("target"),
                            collision: "fit"
                        },
                        shading: false,
                        showTitle: false,
                        height: "auto",
                        width: "auto",
                        onShowing: $.proxy(this._overlayShowingActionHandler, this),
                        onShown: $.proxy(this._overlayShownActionHandler, this),
                        onHiding: $.proxy(this._overlayHidingActionHandler, this),
                        onHidden: $.proxy(this._overlayHiddenActionHandler, this),
                        onPositioned: $.proxy(this._overlayPositionedActionHandler, this),
                        onPositioning: $.proxy(this._overlayPositioningActionHandler, this)
                    };
                return overlayOptions
            },
            _overlayShowingActionHandler: function(arg) {
                this._actions.onShowing(arg);
                this._clearFocusedItem()
            },
            _overlayShownActionHandler: function(arg) {
                this._actions.onShown(arg);
                this._clearFocusedItem()
            },
            _clearFocusedItem: function() {
                this._removeFocusedItem();
                this.option("focusedElement", null)
            },
            _overlayHidingActionHandler: function(arg) {
                this._actions.onHiding(arg);
                if (!arg.cancel)
                    this._hideAllShownSubmenus()
            },
            _overlayHiddenActionHandler: function(arg) {
                this._actions.onHidden(arg);
                this._setOptionSilent("visible", false)
            },
            _overlayPositionedActionHandler: $.noop,
            _overlayPositioningActionHandler: $.noop,
            _closeOnOutsideClickHandler: function(e) {
                var $clickedItem,
                    $activeItemContainer,
                    $itemContainers,
                    $rootItem,
                    isRootItemClicked,
                    isInnerOverlayClicked,
                    isInvokeTarget = $(e.target).closest(this._getInvokeTarget());
                if (e.target === document)
                    return true;
                if (isInvokeTarget && isInvokeTarget.length)
                    return false;
                $activeItemContainer = this._getActiveItemsContainer(e.target);
                $itemContainers = this._getItemsContainers();
                $clickedItem = this._searchActiveItem(e.target);
                $rootItem = this.element().parents("." + DX_MENU_ITEM_CLASS);
                isRootItemClicked = $clickedItem[0] === $rootItem[0] && $clickedItem.length && $rootItem.length;
                isInnerOverlayClicked = this._isIncludeOverlay($activeItemContainer, $itemContainers) && $clickedItem.length;
                if (isInnerOverlayClicked || isRootItemClicked) {
                    if (this._getShowSubmenuMode() === "onClick")
                        this._hideAllShownChildSubmenus($clickedItem);
                    return false
                }
                return true
            },
            _getActiveItemsContainer: function(target) {
                return $(target).closest("." + DX_MENU_ITEMS_CONTAINER_CLASS)
            },
            _getItemsContainers: function() {
                return this._overlay._$content.find("." + DX_MENU_ITEMS_CONTAINER_CLASS)
            },
            _searchActiveItem: function(target) {
                return $(target).closest("." + DX_MENU_ITEM_CLASS).eq(0)
            },
            _isIncludeOverlay: function($activeOverlay, $allOverlays) {
                var isSame = false;
                $.each($allOverlays, function(index, $overlay) {
                    if ($activeOverlay.is($overlay) && !isSame)
                        isSame = true
                });
                return isSame
            },
            _hideAllShownChildSubmenus: function($clickedItem) {
                var that = this,
                    $submenuElements = $clickedItem.find("." + DX_SUBMENU_CLASS),
                    shownSubmenus = $.extend([], this._shownSubmenus),
                    $context;
                if ($submenuElements.length > 0)
                    $.each(shownSubmenus, function(index, $submenu) {
                        $context = that._searchActiveItem($submenu.context).parent();
                        if ($context.parent().is($clickedItem.parent().parent()) && !$context.is($clickedItem.parent()))
                            that._hideSubmenu($submenu)
                    })
            },
            _showSubmenu: function($item) {
                var node = this._dataAdapter.getNodeByItem(this._getItemData($item)),
                    isItemHasSubmenu = this._hasSubmenu(node),
                    isSubmenuVisible;
                this._hideSubmenusOnSameLevel($item);
                if (isItemHasSubmenu) {
                    this.callBase($item);
                    $item.closest("." + DX_MENU_ITEMS_CONTAINER_CLASS).find("." + DX_MENU_ITEM_EXPANDED_CLASS).removeClass(DX_MENU_ITEM_EXPANDED_CLASS);
                    if (!$item.children("." + DX_SUBMENU_CLASS).length)
                        this._renderSubmenuItems(node, $item, 2);
                    isSubmenuVisible = this._isSubmenuVisible($item.children("." + DX_SUBMENU_CLASS));
                    if (!isSubmenuVisible) {
                        $item.addClass(DX_MENU_ITEM_EXPANDED_CLASS);
                        this._drawSubmenu($item)
                    }
                }
            },
            _hideSubmenusOnSameLevel: function($item) {
                var $expandedItems = $item.closest("." + DX_MENU_ITEMS_CONTAINER_CLASS).find("." + DX_MENU_ITEM_EXPANDED_CLASS);
                if ($expandedItems.length) {
                    $expandedItems.removeClass(DX_MENU_ITEM_EXPANDED_CLASS);
                    this._hideSubmenu($expandedItems.find("." + DX_SUBMENU_CLASS))
                }
            },
            _hideSubmenuGroup: function($submenu) {
                if (this._isSubmenuVisible($submenu))
                    this._hideSubmenuCore($submenu)
            },
            _isSubmenuVisible: function($submenu) {
                return $submenu.css("visibility") === "visible"
            },
            _drawSubmenu: function($itemElement) {
                var animation = this.option("animation") ? this.option("animation").show : {},
                    $submenu = $itemElement.children("." + DX_SUBMENU_CLASS);
                if (this._overlay && this._overlay.option("visible")) {
                    if (!commonUtils.isDefined(this._shownSubmenus))
                        this._shownSubmenus = [];
                    if ($.inArray($submenu, this._shownSubmenus))
                        this._shownSubmenus.push($submenu);
                    positionUtils.setup($submenu, this._getSubmenuPosition($itemElement));
                    animation && this._animate($submenu, animation);
                    $submenu.css("visibility", "visible")
                }
            },
            _animate: function($container, options) {
                fx.animate($container, options)
            },
            _getSubmenuPosition: function($rootItem) {
                var submenuDirection = this.option("submenuDirection").toLowerCase(),
                    $rootItemWrapper = $rootItem.parent("." + DX_MENU_ITEM_WRAPPER_CLASS),
                    position = {
                        collision: "flip",
                        of: $rootItemWrapper,
                        offset: {
                            h: 0,
                            v: -1
                        }
                    };
                switch (submenuDirection) {
                    case"left":
                        position.at = "left top";
                        position.my = "right top";
                        break;
                    case"right":
                        position.at = "right top";
                        position.my = "left top";
                        break;
                    default:
                        if (this.option("rtlEnabled")) {
                            position.at = "left top";
                            position.my = "right top"
                        }
                        else {
                            position.at = "right top";
                            position.my = "left top"
                        }
                        break
                }
                return position
            },
            _updateSubmenuVisibilityOnClick: function(actionArgs) {
                var $itemElement,
                    itemData,
                    node,
                    submenu,
                    $submenuElement,
                    notCloseMenuOnItemClick;
                if (actionArgs.args.length && actionArgs.args[0]) {
                    actionArgs.args[0].jQueryEvent.stopPropagation();
                    $itemElement = actionArgs.args[0].itemElement;
                    itemData = actionArgs.args[0].itemData;
                    node = this._dataAdapter.getNodeByItem(itemData);
                    if (!node)
                        return;
                    if ($itemElement.find("." + DX_CONTEXT_MENU_CLASS).length)
                        submenu = this._getSubmenuInstanceByRootElement($itemElement);
                    else
                        submenu = node.internalFields.childrenKeys.length && this._renderSubmenuItems(node, $itemElement, 2);
                    notCloseMenuOnItemClick = itemData && itemData.closeMenuOnClick === false;
                    $submenuElement = $itemElement.children("." + DX_SUBMENU_CLASS);
                    if ($itemElement.context === $submenuElement.context && $submenuElement.css("visibility") === "visible")
                        return;
                    if (!itemData || itemData.disabled || notCloseMenuOnItemClick)
                        return;
                    this._updateSelectedItemOnClick(actionArgs);
                    if ($submenuElement.length === 0) {
                        var $prevSubmenu = $($itemElement.parents("." + DX_SUBMENU_CLASS)[0]);
                        this._hideSubmenu($prevSubmenu);
                        if (!actionArgs.canceled && this._overlay && this._overlay.option("visible"))
                            this.option("visible", false)
                    }
                    else {
                        if (this._shownSubmenus && this._shownSubmenus.length > 0)
                            if (this._shownSubmenus[0].is($submenuElement) || this._shownSubmenus[0].has($submenuElement).length === 1)
                                this._hideSubmenu($submenuElement);
                            else
                                this._hideAllShownSubmenus();
                        this._showSubmenu($itemElement)
                    }
                }
            },
            _hideSubmenu: function($curSubmenu) {
                var that = this,
                    shownSubmenus = $.extend([], that._shownSubmenus);
                $.each(shownSubmenus, function(index, $submenu) {
                    if ($curSubmenu.is($submenu) || $curSubmenu.has($submenu).length) {
                        $submenu.parent().removeClass(DX_MENU_ITEM_EXPANDED_CLASS);
                        that._hideSubmenuCore($submenu)
                    }
                })
            },
            _hideSubmenuCore: function($submenu) {
                var index = $.inArray($submenu, this._shownSubmenus),
                    animation = this.option("animation") ? this.option("animation").hide : null;
                if (index >= 0)
                    this._shownSubmenus.splice(index, 1);
                this._stopAnimate($submenu);
                animation && this._animate($submenu, animation);
                $submenu.css("visibility", "hidden")
            },
            _stopAnimate: function($container) {
                fx.stop($container, true)
            },
            _hideAllShownSubmenus: function() {
                var that = this,
                    shownSubmenus = $.extend([], that._shownSubmenus),
                    $expandedItems = this._overlay.content().find("." + DX_MENU_ITEM_EXPANDED_CLASS);
                $expandedItems.removeClass(DX_MENU_ITEM_EXPANDED_CLASS);
                $.each(shownSubmenus, function(_, $submenu) {
                    that._hideSubmenuCore($submenu)
                })
            },
            _visibilityChanged: function(visible) {
                if (visible) {
                    this._detachShowContextMenuEvents(this.option("target"));
                    this._attachShowContextMenuEvents();
                    this._attachInvokeContextMenuEvents()
                }
            },
            _optionChanged: function(args) {
                if (this._cancelOptionChange)
                    return;
                if ($.inArray(args.name, ACTIONS) > -1) {
                    this._initActions();
                    return
                }
                switch (args.name) {
                    case"visible":
                        this._toggleVisibility(args.value);
                        break;
                    case"alternativeInvocationMode":
                        this._invalidate();
                        break;
                    case"items":
                        if (this._overlay.option("visible"))
                            this._overlay.hide();
                        this.callBase(args);
                        break;
                    case"position":
                    case"submenuDirection":
                        this._invalidate();
                        break;
                    case"target":
                        args.previousValue && this._detachShowContextMenuEvents($(args.previousValue));
                        this._invalidate();
                        break;
                    case"focusedElement":
                        this.callBase(args);
                        break;
                    default:
                        if (this._overlay)
                            if (this._overlay.option("visible"))
                                this._overlay.hide();
                        this.callBase(args)
                }
            },
            _toggleVisibility: function(showing) {
                showing ? this._show() : this._hide()
            },
            _show: function(jQEvent) {
                var canShowMenu = !(this._overlay && this._positionContextMenu(jQEvent)),
                    id = new DevExpress.data.Guid,
                    promise;
                if (canShowMenu && this._overlay) {
                    this.option("focusedElement", this._itemElements().first());
                    promise = this._overlay.show();
                    this._overlay.content().attr({
                        id: id,
                        role: "menu"
                    });
                    this.setAria("owns", id);
                    this._setOptionSilent("visible", true)
                }
                return promise || $.Deferred().reject().promise()
            },
            _positionContextMenu: function(jQEvent) {
                var position = this.option("position"),
                    positioningAction = this._createActionByOption("onPositioning", actionArgs),
                    actionArgs;
                if (jQEvent && jQEvent.preventDefault)
                    position = {
                        at: "top left",
                        my: "top left",
                        of: jQEvent
                    };
                if (!position.of)
                    position.of = this.option("target");
                actionArgs = {
                    position: position,
                    jQueryEvent: jQEvent
                };
                positioningAction(actionArgs);
                if (!actionArgs.canceled && this._overlay)
                    position && this._overlay.option("position", position);
                else
                    actionArgs.jQueryEvent.cancel = true;
                return actionArgs.canceled
            },
            _hide: function() {
                var promise;
                if (this._overlay) {
                    this._overlay.content().removeAttr("id");
                    promise = this._overlay.hide();
                    this._setOptionSilent("visible", false)
                }
                this.setAria("owns", undefined);
                return promise || $.Deferred().reject().promise()
            },
            _clean: function() {
                if (this._overlay) {
                    this._overlay.element().remove();
                    this._overlay = null
                }
                this._detachShowContextMenuEvents($(this.option("target")))
            },
            toggle: function(showing) {
                var visible = this.option("visible");
                showing = showing === undefined ? !visible : showing;
                return showing ? this._show() : this._hide()
            },
            show: function() {
                return this.toggle(true)
            },
            hide: function() {
                return this.toggle(false)
            }
        }))
    })(jQuery, DevExpress);
    /*! Module tmp-widgets-for-exporter, file ui.menu.js */
    (function($, DX, undefined) {
        var ui = DX.ui,
            positionUtils = DX.require("/utils/utils.position"),
            commonUtils = DX.require("/utils/utils.common"),
            registerComponent = DX.require("/componentRegistrator"),
            eventUtils = DX.require("/ui/events/ui.events.utils"),
            pointerEvents = DX.require("/ui/events/pointer/ui.events.pointer"),
            hoverEvents = DX.require("/ui/events/ui.events.hover");
        var DX_MENU_CLASS = "dx-menu",
            DX_MENU_VERTICAL_CLASS = DX_MENU_CLASS + "-vertical",
            DX_MENU_HORIZONTAL_CLASS = DX_MENU_CLASS + "-horizontal",
            DX_MENU_ITEM_CLASS = DX_MENU_CLASS + "-item",
            DX_MENU_ITEMS_CONTAINER_CLASS = DX_MENU_CLASS + "-items-container",
            DX_MENU_ITEM_EXPANDED_CLASS = DX_MENU_ITEM_CLASS + "-expanded",
            DX_CONTEXT_MENU_CLASS = "dx-context-menu",
            DX_CONTEXT_MENU_CONTAINER_BORDER_CLASS = DX_CONTEXT_MENU_CLASS + "-container-border",
            DX_CONTEXT_MENU_CONTENT_DELIMITER_CLASS = "dx-context-menu-content-delimiter",
            DX_SUBMENU_CLASS = "dx-submenu",
            DX_STATE_DISABLED_CLASS = "dx-state-disabled",
            DX_STATE_FOCUSED_CLASS = "dx-state-focused",
            DX_STATE_HOVER_CLASS = "dx-state-hover",
            FOCUS_UP = "up",
            FOCUS_DOWN = "down",
            FOCUS_LEFT = "left",
            FOCUS_RIGHT = "right",
            SHOW_SUBMENU_OPERATION = "showSubmenu",
            NEXTITEM_OPERATION = "nextItem",
            PREVITEM_OPERATION = "prevItem",
            DEFAULT_SHOW_DELAY = 50,
            DEFAULT_HIDE_DELAY = 300,
            ACTIONS = ["onSubmenuShowing", "onSubmenuShown", "onSubmenuHiding", "onSubmenuHidden"],
            dxSubmenu = ui.dxContextMenu.inherit({
                _getDefaultOptions: function() {
                    return $.extend(this.callBase(), {orientation: "horizontal"})
                },
                _initDataAdapter: function() {
                    this._dataAdapter = this.option("_dataAdapter");
                    if (this._dataAdapter)
                        this._dataAdapter.options.rooValue = this.option("_parentKey");
                    else
                        this.callBase()
                },
                _renderContentImpl: function() {
                    this._renderContextMenuOverlay();
                    this._detachShowContextMenuEvents(this.option("target"));
                    this._attachShowContextMenuEvents();
                    this._attachInvokeContextMenuEvents();
                    var node = this._dataAdapter.getNodeByKey(this.option("_parentKey"));
                    node && this._renderItems(this._getChildNodes(node));
                    if (!this.option("_hideDelimiter"))
                        this._renderDelimiter()
                },
                _renderDelimiter: function() {
                    this.$contentDelimiter = $("<div>").appendTo(this._itemContainer()).addClass(DX_CONTEXT_MENU_CONTENT_DELIMITER_CLASS)
                },
                _overlayPositionedActionHandler: function(arg) {
                    this._showDelimiter(arg)
                },
                _hoverEndHandler: function(e) {
                    this._toggleFocusClass(false, e.currentTarget)
                },
                _hoverStartHandler: function(e) {
                    this.callBase(e);
                    this._toggleFocusClass(true, e.currentTarget)
                },
                _showDelimiter: function(arg) {
                    var $submenu = this._itemContainer().children("." + DX_SUBMENU_CLASS).eq(0),
                        $rootItem = this.option("position").of,
                        position = {of: $submenu},
                        containerOffset,
                        rootOffset;
                    if (this.$contentDelimiter) {
                        containerOffset = arg.position;
                        rootOffset = $rootItem.offset();
                        this.$contentDelimiter.css("display", "block");
                        if (this.option("orientation") === "horizontal") {
                            this.$contentDelimiter.width($rootItem.width() < $submenu.width() ? $rootItem.width() - 2 : $submenu.width() - 2);
                            this.$contentDelimiter.height(2);
                            if (containerOffset.v.location > rootOffset.top)
                                if (Math.round(containerOffset.h.location) === Math.round(rootOffset.left)) {
                                    position.offset = "1 -1";
                                    position.at = "left top";
                                    position.my = "left top"
                                }
                                else {
                                    position.offset = "-1 -1";
                                    position.at = "right top";
                                    position.my = "right top"
                                }
                            else {
                                this.$contentDelimiter.height(5);
                                if (Math.round(containerOffset.h.location) === Math.round(rootOffset.left)) {
                                    position.offset = "1 4";
                                    position.at = "left bottom";
                                    position.my = "left bottom"
                                }
                                else {
                                    position.offset = "-1 2";
                                    position.at = "right bottom";
                                    position.my = "right bottom"
                                }
                            }
                        }
                        else if (this.option("orientation") === "vertical") {
                            this.$contentDelimiter.width(2);
                            this.$contentDelimiter.height($rootItem.height() < $submenu.height() ? $rootItem.height() - 2 : $submenu.height() - 2);
                            if (containerOffset.h.location > rootOffset.left)
                                if (Math.round(containerOffset.v.location) === Math.round(rootOffset.top)) {
                                    position.offset = "-1 1";
                                    position.at = "left top";
                                    position.my = "left top"
                                }
                                else {
                                    position.offset = "-1 -1";
                                    position.at = "left bottom";
                                    position.my = "left bottom"
                                }
                            else if (Math.round(containerOffset.v.location) === Math.round(rootOffset.top)) {
                                position.offset = "1 1";
                                position.at = "right top";
                                position.my = "right top"
                            }
                            else {
                                position.offset = "1 -1";
                                position.at = "right bottom";
                                position.my = "right bottom"
                            }
                        }
                        positionUtils.setup(this.$contentDelimiter, position)
                    }
                }
            });
        registerComponent("dxMenu", ui, ui.dxMenuBase.inherit({
            _getDefaultOptions: function() {
                return $.extend(this.callBase(), {
                        orientation: "horizontal",
                        submenuDirection: "auto",
                        showFirstSubmenuMode: {
                            name: "onClick",
                            delay: {
                                show: 50,
                                hide: 300
                            }
                        },
                        hideSubmenuOnMouseLeave: false,
                        onSubmenuShowing: null,
                        onSubmenuShown: null,
                        onSubmenuHiding: null,
                        onSubmenuHidden: null
                    })
            },
            _setOptionsByReference: function() {
                this.callBase();
                $.extend(this._optionsByReference, {
                    animation: true,
                    selectedItem: true
                })
            },
            _focusTarget: function() {
                return this.element()
            },
            _eventBindingTarget: function() {
                return this.element()
            },
            _getOrientation: function() {
                return this.option("orientation") === "vertical" ? "vertical" : "horizontal"
            },
            _isMenuHorizontal: function() {
                return this._getOrientation() === "horizontal"
            },
            _moveFocus: function(location) {
                var $items = this._itemElements().filter(":visible"),
                    isMenuHorizontal = this._isMenuHorizontal(),
                    argument,
                    $activeItem = this._getActiveItem(true),
                    operation,
                    navigationAction,
                    $newTarget;
                switch (location) {
                    case FOCUS_UP:
                        operation = isMenuHorizontal ? SHOW_SUBMENU_OPERATION : this._getItemsNavigationOperation(PREVITEM_OPERATION);
                        argument = isMenuHorizontal ? $activeItem : $items;
                        navigationAction = this._getKeyboardNavigationAction(operation, argument);
                        $newTarget = navigationAction();
                        break;
                    case FOCUS_DOWN:
                        operation = isMenuHorizontal ? SHOW_SUBMENU_OPERATION : this._getItemsNavigationOperation(NEXTITEM_OPERATION);
                        argument = isMenuHorizontal ? $activeItem : $items;
                        navigationAction = this._getKeyboardNavigationAction(operation, argument);
                        $newTarget = navigationAction();
                        break;
                    case FOCUS_RIGHT:
                        operation = isMenuHorizontal ? this._getItemsNavigationOperation(NEXTITEM_OPERATION) : SHOW_SUBMENU_OPERATION;
                        argument = isMenuHorizontal ? $items : $activeItem;
                        navigationAction = this._getKeyboardNavigationAction(operation, argument);
                        $newTarget = navigationAction();
                        break;
                    case FOCUS_LEFT:
                        operation = isMenuHorizontal ? this._getItemsNavigationOperation(PREVITEM_OPERATION) : SHOW_SUBMENU_OPERATION;
                        argument = isMenuHorizontal ? $items : $activeItem;
                        navigationAction = this._getKeyboardNavigationAction(operation, argument);
                        $newTarget = navigationAction();
                        break;
                    default:
                        return this.callBase(location)
                }
                if ($newTarget && $newTarget.length !== 0)
                    this.option("focusedElement", $newTarget)
            },
            _getItemsNavigationOperation: function(operation) {
                var navOperation = operation;
                if (this.option("rtlEnabled"))
                    navOperation = operation === PREVITEM_OPERATION ? NEXTITEM_OPERATION : PREVITEM_OPERATION;
                return navOperation
            },
            _getKeyboardNavigationAction: function(operation, argument) {
                var action = $.noop;
                switch (operation) {
                    case SHOW_SUBMENU_OPERATION:
                        if (!argument.hasClass(DX_STATE_DISABLED_CLASS))
                            action = $.proxy(this._showSubmenu, this, argument);
                        break;
                    case NEXTITEM_OPERATION:
                        action = $.proxy(this._nextItem, this, argument);
                        break;
                    case PREVITEM_OPERATION:
                        action = $.proxy(this._prevItem, this, argument);
                        break
                }
                return action
            },
            _init: function() {
                this.callBase();
                this._submenus = []
            },
            _initActions: function() {
                this._actions = {};
                $.each(ACTIONS, $.proxy(function(index, action) {
                    this._actions[action] = this._createActionByOption(action) || $.noop
                }, this))
            },
            _render: function() {
                this._visibleSubmenu = null;
                this.callBase();
                this.element().addClass(DX_MENU_CLASS);
                this.setAria("role", "menubar")
            },
            _getHideDelay: function() {
                var delay = this.option("showFirstSubmenuMode").delay;
                if (delay)
                    return commonUtils.isObject(delay) ? delay.hide : delay;
                else
                    return DEFAULT_HIDE_DELAY
            },
            _getShowDelay: function() {
                var delay = this.option("showFirstSubmenuMode").delay,
                    resultDelay;
                if (delay)
                    resultDelay = commonUtils.isObject(delay) ? delay.show : delay;
                else
                    resultDelay = DEFAULT_SHOW_DELAY;
                return resultDelay
            },
            _renderContainer: function() {
                var isVerticalMenu = this.option("orientation") === "vertical",
                    $wrapper = $("<div>");
                $wrapper.appendTo(this.element()).addClass(isVerticalMenu ? DX_MENU_VERTICAL_CLASS : DX_MENU_HORIZONTAL_CLASS);
                return this.callBase($wrapper).css("min-height", this._getValueHeight($wrapper))
            },
            _getValueHeight: function($root) {
                var $div = $("<div>").html("Jj").css({
                        width: "auto",
                        position: "fixed",
                        top: "-3000px",
                        left: "-3000px"
                    }).appendTo($root),
                    height = $div.height();
                $div.remove();
                return height
            },
            _renderSubmenuItems: function(node, $itemFrame) {
                var submenu = this._createSubmenu(node, $itemFrame);
                this._submenus.push(submenu);
                this._renderBorderElement($itemFrame);
                return submenu
            },
            _createSubmenu: function(node, $rootItem) {
                var $submenuContainer = $("<div>").addClass(DX_CONTEXT_MENU_CLASS).appendTo($rootItem);
                var items = this._getChildNodes(node),
                    result = this._createComponent($submenuContainer, "dxSubmenu", $.extend(this._getSubmenuOptions(), {
                        _dataAdapter: this._dataAdapter,
                        _parentKey: node.internalFields.key,
                        items: items,
                        position: this.getSubmenuPosition($rootItem)
                    }));
                this._attachSubmenuHandlers($rootItem, result);
                return result
            },
            _getSubmenuOptions: function() {
                var $submenuTarget = $("<div>"),
                    isMenuHorizontal = this._isMenuHorizontal();
                return {
                        itemTemplate: this.option("itemTemplate"),
                        templateProvider: this.option("templateProvider"),
                        target: $submenuTarget,
                        orientation: this.option("orientation"),
                        selectionMode: this.option("selectionMode"),
                        selectionByClick: this.option("selectionByClick"),
                        cssClass: this.option("cssClass"),
                        hoverStateEnabled: this.option("hoverStateEnabled"),
                        activeStateEnabled: this.option("activeStateEnabled"),
                        focusStateEnabled: this.option("focusStateEnabled"),
                        animation: this.option("animation"),
                        showSubmenuMode: this.option("showSubmenuMode"),
                        displayExpr: this.option("displayExpr"),
                        disabledExpr: this.option("disabledExpr"),
                        selectedExpr: this.option("selectedExpr"),
                        itemsExpr: this.option("itemsExpr"),
                        onSelectionChanged: $.proxy(this._nestedItemOnSelectionChangedHandler, this),
                        onItemClick: $.proxy(this._nestedItemOnItemClickHandler, this),
                        onItemRendered: this.option("onItemRendered"),
                        onLeftFirstItem: isMenuHorizontal ? null : $.proxy(this._moveMainMenuFocus, this, PREVITEM_OPERATION),
                        onLeftLastItem: isMenuHorizontal ? null : $.proxy(this._moveMainMenuFocus, this, NEXTITEM_OPERATION),
                        onCloseRootSubmenu: isMenuHorizontal ? $.proxy(this._moveMainMenuFocus, this, PREVITEM_OPERATION) : null,
                        onExpandLastSubmenu: isMenuHorizontal ? $.proxy(this._moveMainMenuFocus, this, NEXTITEM_OPERATION) : null,
                        _remoteSelectionSync: true,
                        _hideDelimiter: this.option("_hideDelimiter")
                    }
            },
            _getShowFirstSubmenuMode: function() {
                var modeName;
                if (commonUtils.isObject(this.option("showFirstSubmenuMode")))
                    modeName = this._isDesktopDevice() ? this.option("showFirstSubmenuMode").name : "onClick";
                else
                    modeName = this._isDesktopDevice() ? this.option("showFirstSubmenuMode") : "onClick";
                return modeName
            },
            _moveMainMenuFocus: function(direction) {
                var $expandedItem = this.element().find("." + DX_MENU_ITEM_EXPANDED_CLASS).first(),
                    $newItem;
                switch (direction) {
                    case PREVITEM_OPERATION:
                        $newItem = $expandedItem.parent().prev();
                        if (!$newItem.length)
                            $newItem = $expandedItem.parent().siblings().last();
                        $newItem = $newItem.children();
                        break;
                    case NEXTITEM_OPERATION:
                        $newItem = $expandedItem.parent().next();
                        if (!$newItem.length)
                            $newItem = $expandedItem.parent().siblings().first();
                        $newItem = $newItem.children();
                        break
                }
                this._visibleSubmenu && this._hideSubmenu(this._visibleSubmenu);
                this.focus();
                this.option("focusedElement", $newItem)
            },
            _nestedItemOnSelectionChangedHandler: function(args) {
                var selectedItem = args.addedItems.length && args.addedItems[0],
                    submenu = args.element.dxSubmenu("instance"),
                    onSelectionChanged = this._createActionByOption("onSelectionChanged", {});
                onSelectionChanged(args);
                selectedItem && this._clearSelectionInSubmenus(selectedItem[0], submenu);
                this._clearRootSelection();
                this._setOptionSilent("selectedItem", selectedItem)
            },
            _clearSelectionInSubmenus: function(item, targetSubmenu) {
                var that = this,
                    cleanAllSubmenus = !arguments.length;
                $.each(this._submenus, function(index, submenu) {
                    var $submenu = submenu._itemContainer(),
                        isOtherItem = !$submenu.is(targetSubmenu && targetSubmenu._itemContainer()),
                        $selectedItem = $submenu.find("." + that._selectedItemClass());
                    if (isOtherItem && $selectedItem.length || cleanAllSubmenus) {
                        var selectedItemData;
                        $selectedItem.removeClass(that._selectedItemClass());
                        selectedItemData = that._getItemData($selectedItem);
                        if (selectedItemData)
                            selectedItemData.selected = false;
                        submenu._clearSelectedItems()
                    }
                })
            },
            _clearRootSelection: function() {
                var $prevSelectedItem = this.element().find("." + DX_MENU_ITEMS_CONTAINER_CLASS).first().children().children().filter("." + this._selectedItemClass());
                if ($prevSelectedItem.length) {
                    var prevSelectedItemData;
                    prevSelectedItemData = this._getItemData($prevSelectedItem);
                    prevSelectedItemData.selected = false;
                    $prevSelectedItem.removeClass(this._selectedItemClass())
                }
            },
            _nestedItemOnItemClickHandler: function(arg) {
                var onItemClick = this._createActionByOption("onItemClick", {});
                onItemClick(arg)
            },
            _attachSubmenuHandlers: function($rootItem, submenu) {
                var that = this,
                    $submenuOverlayContent = submenu._overlay.content(),
                    submenus = $submenuOverlayContent.find("." + DX_SUBMENU_CLASS),
                    submenuMouseLeaveName = eventUtils.addNamespace(hoverEvents.end, this.NAME + "_submenu");
                submenu.option({
                    onShowing: $.proxy(this._submenuOnShowingHandler, this, $rootItem, submenu),
                    onShown: $.proxy(this._submenuOnShownHandler, this, $rootItem, submenu),
                    onHiding: $.proxy(this._submenuOnHidingHandler, this, $rootItem, submenu),
                    onHidden: $.proxy(this._submenuOnHiddenHandler, this, $rootItem, submenu)
                });
                $.each(submenus, function(index, submenu) {
                    $(submenu).off(submenuMouseLeaveName).on(submenuMouseLeaveName, null, $.proxy(that._submenuMouseLeaveHandler, that, $rootItem))
                })
            },
            _submenuOnShowingHandler: function($rootItem, submenu) {
                var $border = $rootItem.children("." + DX_CONTEXT_MENU_CONTAINER_BORDER_CLASS);
                this._actions.onSubmenuShowing({
                    rootItem: $rootItem,
                    submenu: submenu
                });
                if (this._options.width !== undefined)
                    if (this._options.rtlEnabled)
                        $border.css("width", this._$element.width() - $rootItem.position().right);
                    else
                        $border.css("width", this._$element.width() - $rootItem.position().left);
                $border.show();
                $rootItem.addClass(DX_MENU_ITEM_EXPANDED_CLASS)
            },
            _submenuOnShownHandler: function($rootItem, submenu) {
                this._actions.onSubmenuShown({
                    rootItem: $rootItem,
                    submenu: submenu
                })
            },
            _submenuOnHidingHandler: function($rootItem, submenu, eventArgs) {
                var $border = $rootItem.children("." + DX_CONTEXT_MENU_CONTAINER_BORDER_CLASS),
                    args = eventArgs;
                args.rootItem = $rootItem;
                args.submenu = submenu;
                this._actions.onSubmenuHiding(args);
                eventArgs = args;
                if (!eventArgs.cancel) {
                    $border.hide();
                    $rootItem.removeClass(DX_MENU_ITEM_EXPANDED_CLASS)
                }
            },
            _submenuOnHiddenHandler: function($rootItem, submenu) {
                this._actions.onSubmenuHidden({
                    rootItem: $rootItem,
                    submenu: submenu
                })
            },
            _submenuMouseLeaveHandler: function($rootItem, eventArgs) {
                var that = this,
                    target = $(eventArgs.relatedTarget).parents("." + DX_CONTEXT_MENU_CLASS)[0],
                    contextmenu = this._getSubmenuElementByRootElement($rootItem).dxSubmenu("instance")._overlay.content()[0];
                if (this.option("hideSubmenuOnMouseLeave") && target !== contextmenu) {
                    clearTimeout(this._showSubmenuTimer);
                    clearTimeout(this._hideSubmenuTimer);
                    setTimeout($.proxy(this._hideSubmenuAfterTimeout, this), that._getHideDelay(), $rootItem)
                }
            },
            _hideSubmenuAfterTimeout: function($rootItem) {
                var $submenu = this._getSubmenuElementByRootElement($rootItem),
                    hasVisibleSubmenu = this._visibleSubmenu,
                    isRootItemHovered = hasVisibleSubmenu && $(this._visibleSubmenu.context).hasClass(DX_STATE_HOVER_CLASS);
                if (hasVisibleSubmenu && !this._getFocusedSubmenuItem(this._visibleSubmenu).length && !isRootItemHovered)
                    $submenu.length && this._getSubmenuInstance($submenu).hide()
            },
            _getFocusedSubmenuItem: function($submenu) {
                return $submenu._overlay.content().find("." + DX_STATE_FOCUSED_CLASS)
            },
            _getSubmenuInstance: function($submenu) {
                return $submenu.data().dxSubmenu
            },
            _getSubmenuElementByRootElement: function($rootItem) {
                return $rootItem && $rootItem.children("." + DX_CONTEXT_MENU_CLASS)
            },
            _getSubmenuInstanceByRootElement: function($rootItem) {
                var $submenu = this._getSubmenuElementByRootElement($rootItem);
                return $submenu.length && this._getSubmenuInstance($submenu)
            },
            getSubmenuPosition: function($rootItem) {
                var isVerticalMenu = this.option("orientation").toLowerCase() === "vertical",
                    submenuDirection = this.option("submenuDirection").toLowerCase(),
                    rtlEnabled = this.option("rtlEnabled"),
                    submenuPosition = {
                        collision: "flip",
                        of: $rootItem
                    };
                switch (submenuDirection) {
                    case"leftortop":
                        submenuPosition.at = isVerticalMenu ? "left top" : "left top";
                        submenuPosition.my = isVerticalMenu ? "right top" : "left bottom";
                        break;
                    case"rightorbottom":
                        submenuPosition.at = isVerticalMenu ? "right top" : "left bottom";
                        submenuPosition.my = isVerticalMenu ? "left top" : "left top";
                        break;
                    default:
                        if (isVerticalMenu) {
                            submenuPosition.at = rtlEnabled ? "left top" : "right top";
                            submenuPosition.my = rtlEnabled ? "right top" : "left top"
                        }
                        else {
                            submenuPosition.at = rtlEnabled ? "right bottom" : "left bottom";
                            submenuPosition.my = rtlEnabled ? "right top" : "left top"
                        }
                        break
                }
                return submenuPosition
            },
            _renderBorderElement: function($item) {
                $("<div>").appendTo($item).addClass(DX_CONTEXT_MENU_CONTAINER_BORDER_CLASS).hide()
            },
            _hoverStartHandler: function(e) {
                var mouseMoveEventName = eventUtils.addNamespace(pointerEvents.move, this.NAME),
                    $item = this._getItemElementByEventArgs(e),
                    node = this._dataAdapter.getNodeByItem(this._getItemData($item)),
                    isSelectionActive = commonUtils.isDefined(e.buttons) && e.buttons === 1 || !commonUtils.isDefined(e.buttons) && e.which === 1;
                if (this._isItemDisabled($item))
                    return;
                $item.off(mouseMoveEventName);
                if (this._getShowFirstSubmenuMode() === "onHover" && node.internalFields.childrenKeys.length && !isSelectionActive) {
                    var submenu;
                    if ($item.find("." + DX_CONTEXT_MENU_CLASS).length)
                        submenu = this._getSubmenuInstanceByRootElement($item);
                    else
                        submenu = node.internalFields.childrenKeys.length && this._renderSubmenuItems(node, $item);
                    clearTimeout(this._hideSubmenuTimer);
                    clearTimeout(this._showSubmenuTimer);
                    if (!submenu._overlay.option("visible")) {
                        $item.on(mouseMoveEventName, $.proxy(this._itemMouseMoveHandler, this));
                        this._showSubmenuTimer = this._getHideDelay()
                    }
                    if (submenu === this._visibleSubmenu)
                        this._hideSubmenu(this._visibleSubmenu)
                }
            },
            _hoverEndHandler: function(eventArg) {
                var that = this,
                    $item = this._getItemElementByEventArgs(eventArg);
                if (this._isItemDisabled($item))
                    return;
                if ($(eventArg.relatedTarget).hasClass(DX_CONTEXT_MENU_CONTENT_DELIMITER_CLASS))
                    return;
                if (this.option("hideSubmenuOnMouseLeave") && !$(eventArg.relatedTarget).hasClass(DX_MENU_ITEMS_CONTAINER_CLASS)) {
                    clearTimeout(this._showSubmenuTimer);
                    clearTimeout(this._hideSubmenuTimer);
                    this._hideSubmenuTimer = setTimeout(function() {
                        that._hideSubmenuAfterTimeout($item)
                    }, that._getHideDelay())
                }
            },
            _showSubmenu: function($itemElement) {
                var node = this._dataAdapter.getNodeByItem(this._getItemData($itemElement)),
                    submenu;
                if ($itemElement.find("." + DX_CONTEXT_MENU_CLASS).length)
                    submenu = this._getSubmenuInstanceByRootElement($itemElement);
                else
                    submenu = node.internalFields.childrenKeys.length && this._renderSubmenuItems(node, $itemElement);
                if (this._visibleSubmenu && this._visibleSubmenu !== submenu)
                    this._hideSubmenu(this._visibleSubmenu);
                submenu && submenu.show();
                this._visibleSubmenu = submenu;
                this._hoveredRootItem = $itemElement
            },
            _hideSubmenu: function(submenu) {
                submenu && submenu.hide();
                if (this._visibleSubmenu && this._visibleSubmenu === submenu)
                    this._visibleSubmenu = null;
                this._hoveredRootItem = null
            },
            _itemMouseMoveHandler: function(e) {
                if (e.pointers && e.pointers.length)
                    return;
                var that = this,
                    $item = $(e.currentTarget);
                if (!this._showSubmenuTimer)
                    return;
                clearTimeout(this._hideSubmenuTimer);
                clearTimeout(this._showSubmenuTimer);
                this._showSubmenuTimer = setTimeout(function() {
                    var node = that._dataAdapter.getNodeByItem(that._getItemData($item)),
                        submenu;
                    if ($item.find("." + DX_CONTEXT_MENU_CLASS).length)
                        submenu = that._getSubmenuInstanceByRootElement($item);
                    else
                        submenu = node.internalFields.childrenKeys.length && that._renderSubmenuItems(node, $item);
                    if (submenu && !submenu._overlay.option("visible"))
                        that._showSubmenu($item)
                }, that._getShowDelay())
            },
            _updateSubmenuVisibilityOnClick: function(actionArgs) {
                var $itemFrame,
                    node,
                    submenu,
                    args = actionArgs.args.length && actionArgs.args[0];
                if (args) {
                    args.jQueryEvent.stopPropagation();
                    node = this._dataAdapter.getNodeByItem(args.itemData);
                    $itemFrame = args.itemElement;
                    if (node.disabled)
                        return;
                    if ($itemFrame.find("." + DX_CONTEXT_MENU_CLASS).length)
                        submenu = this._getSubmenuInstanceByRootElement($itemFrame);
                    else
                        submenu = node.internalFields.childrenKeys.length && this._renderSubmenuItems(node, $itemFrame);
                    this._updateSelectedItemOnClick(actionArgs);
                    if (submenu)
                        if (submenu._overlay.option("visible")) {
                            if (this._getShowFirstSubmenuMode() === "onClick")
                                this._hideSubmenu(submenu)
                        }
                        else
                            this._showSubmenu($itemFrame);
                    else if (this._visibleSubmenu)
                        this._hideSubmenu(this._visibleSubmenu)
                }
            },
            _optionChanged: function(args) {
                if (this._cancelOptionChange)
                    return;
                this._hideShownSubmenuOnOptionChange(args.name);
                switch (args.name) {
                    case"orientation":
                    case"submenuDirection":
                    case"hideSubmenuOnMouseLeave":
                    case"showFirstSubmenuMode":
                        this._invalidate();
                        break;
                    case"showSubmenuMode":
                        this._changeSubmenusOption(args.name, args.value);
                        break;
                    case"onSubmenuShowing":
                    case"onSubmenuShown":
                    case"onSubmenuHiding":
                    case"onSubmenuHidden":
                        this._initActions();
                        break;
                    default:
                        this.callBase(args)
                }
            },
            _hideShownSubmenuOnOptionChange: function(optionName) {
                if (optionName !== "focusedElement" && this._visibleSubmenu)
                    this._hideSubmenu(this._visibleSubmenu)
            },
            _changeSubmenusOption: function(name, value) {
                $.each(this._submenus, function(index, submenu) {
                    submenu.option(name, value)
                })
            },
            selectItem: function(itemElement) {
                this._hideSubmenu(this._visibleSubmenu);
                this.callBase(itemElement)
            },
            unselectItem: function(itemElement) {
                this._hideSubmenu(this._visibleSubmenu);
                this.callBase(itemElement)
            }
        }));
        registerComponent("dxSubmenu", ui.dxMenu, dxSubmenu);
        ui.dxMenu.__internals = {}
    })(jQuery, DevExpress);
    /*! Module tmp-widgets-for-exporter, file ui.overlay.js */
    (function($, DX, undefined) {
        var ui = DX.ui,
            fx = DX.fx,
            translator = DX.require("/utils/utils.translator"),
            compareVersions = DX.require("/utils/utils.version").compare,
            viewPortUtils = DX.require("/utils/utils.viewPort"),
            viewPort = viewPortUtils.value,
            viewPortChanged = viewPortUtils.changeCallback,
            hideTopOverlayCallback = DX.require("/utils/utils.topOverlay").hideCallback,
            positionUtils = DX.require("/utils/utils.position"),
            fitIntoRange = DX.require("/utils/utils.math").fitIntoRange,
            domUtils = DX.require("/utils/utils.dom"),
            commonUtils = DX.require("/utils/utils.common"),
            devices = DX.require("/devices"),
            registerComponent = DX.require("/componentRegistrator"),
            Widget = DX.require("/ui/ui.widget"),
            KeyboardProcessor = DX.require("/ui/ui.keyboardProcessor"),
            selectors = DX.require("/integration/jquery/jquery.selectors"),
            eventUtils = DX.require("/ui/events/ui.events.utils"),
            pointerEvents = DX.require("/ui/events/pointer/ui.events.pointer");
        var OVERLAY_CLASS = "dx-overlay",
            OVERLAY_WRAPPER_CLASS = "dx-overlay-wrapper",
            OVERLAY_CONTENT_CLASS = "dx-overlay-content",
            OVERLAY_SHADER_CLASS = "dx-overlay-shader",
            OVERLAY_MODAL_CLASS = "dx-overlay-modal",
            INVISIBLE_STATE_CLASS = "dx-state-invisible",
            ANONYMOUS_TEMPLATE_NAME = "content",
            RTL_DIRECTION_CLASS = "dx-rtl",
            ACTIONS = ["onShowing", "onShown", "onHiding", "onHidden", "onPositioning", "onPositioned", "onResizeStart", "onResize", "onResizeEnd"],
            FIRST_Z_INDEX = 1000,
            Z_INDEX_STACK = [],
            DISABLED_STATE_CLASS = "dx-state-disabled",
            TAB_KEY = 9;
        var realDevice = devices.real(),
            realVersion = realDevice.version,
            iOS = realDevice.platform === "ios",
            iOS7_0andBelow = iOS && compareVersions(realVersion, [7, 1]) < 0,
            android4_0nativeBrowser = realDevice.platform === "android" && compareVersions(realVersion, [4, 0], 2) === 0 && navigator.userAgent.indexOf("Chrome") === -1;
        var forceRepaint = function($element) {
                if (iOS7_0andBelow)
                    $element.width();
                if (android4_0nativeBrowser) {
                    var $parents = $element.parents(),
                        inScrollView = $parents.is(".dx-scrollable-native");
                    if (!inScrollView) {
                        $parents.css("backface-visibility", "hidden");
                        $parents.css("backface-visibility");
                        $parents.css("backface-visibility", "visible")
                    }
                }
            };
        var getElement = function(value) {
                return value && $(value instanceof $.Event ? value.target : value)
            };
        registerComponent("dxOverlay", ui, Widget.inherit({
            _supportedKeys: function() {
                var offsetSize = 5,
                    move = function(top, left, e) {
                        if (!this.option("dragEnabled"))
                            return;
                        e.preventDefault();
                        e.stopPropagation();
                        var allowedOffsets = this._allowedOffsets();
                        var offset = {
                                top: fitIntoRange(top, -allowedOffsets.top, allowedOffsets.bottom),
                                left: fitIntoRange(left, -allowedOffsets.left, allowedOffsets.right)
                            };
                        this._changePosition(offset)
                    };
                return $.extend(this.callBase(), {
                        escape: function(e) {
                            this.hide()
                        },
                        upArrow: $.proxy(move, this, -offsetSize, 0),
                        downArrow: $.proxy(move, this, offsetSize, 0),
                        leftArrow: $.proxy(move, this, 0, -offsetSize),
                        rightArrow: $.proxy(move, this, 0, offsetSize)
                    })
            },
            _getDefaultOptions: function() {
                return $.extend(this.callBase(), {
                        activeStateEnabled: false,
                        visible: false,
                        deferRendering: true,
                        shading: true,
                        shadingColor: "",
                        position: {
                            my: "center",
                            at: "center"
                        },
                        width: function() {
                            return $(window).width() * 0.8
                        },
                        minWidth: null,
                        maxWidth: null,
                        height: function() {
                            return $(window).height() * 0.8
                        },
                        minHeight: null,
                        maxHeight: null,
                        animation: {
                            show: {
                                type: "pop",
                                duration: 300,
                                from: {scale: 0.55}
                            },
                            hide: {
                                type: "pop",
                                duration: 300,
                                to: {
                                    opacity: 0,
                                    scale: 0.55
                                },
                                from: {
                                    opacity: 1,
                                    scale: 1
                                }
                            }
                        },
                        closeOnOutsideClick: false,
                        closeOnBackButton: true,
                        onShowing: null,
                        onShown: null,
                        onHiding: null,
                        onHidden: null,
                        contentTemplate: "content",
                        dragEnabled: false,
                        resizeEnabled: false,
                        onResizeStart: null,
                        onResize: null,
                        onResizeEnd: null,
                        target: undefined,
                        container: undefined,
                        hideTopOverlayHandler: undefined,
                        closeOnTargetScroll: false,
                        onPositioning: null,
                        onPositioned: null,
                        boundaryOffset: {
                            h: 0,
                            v: 0
                        }
                    })
            },
            _defaultOptionsRules: function() {
                return this.callBase().concat([{
                            device: function(device) {
                                var realDevice = devices.real(),
                                    realPlatform = realDevice.platform,
                                    realVersion = realDevice.version;
                                return realPlatform === "android" && compareVersions(realVersion, [4, 2]) < 0
                            },
                            options: {animation: {
                                    show: {
                                        type: "fade",
                                        duration: 400
                                    },
                                    hide: {
                                        type: "fade",
                                        duration: 400,
                                        to: {opacity: 0},
                                        from: {opacity: 1}
                                    }
                                }}
                        }])
            },
            _setOptionsByReference: function() {
                this.callBase();
                $.extend(this._optionsByReference, {animation: true})
            },
            _getAnonymousTemplateName: function() {
                return ANONYMOUS_TEMPLATE_NAME
            },
            _wrapper: function() {
                return this._$wrapper
            },
            _container: function() {
                return this._$content
            },
            _eventBindingTarget: function() {
                return this._$content
            },
            _init: function() {
                this.callBase();
                this._initActions();
                this._initCloseOnOutsideClickHandler();
                this._$wrapper = $("<div>").addClass(OVERLAY_WRAPPER_CLASS);
                this._$content = $("<div>").addClass(OVERLAY_CONTENT_CLASS);
                var $element = this.element();
                this._$wrapper.addClass($element.attr("class"));
                $element.addClass(OVERLAY_CLASS);
                this._$wrapper.attr("data-bind", "dxControlsDescendantBindings: true");
                this._$wrapper.on("MSPointerDown", $.noop);
                this._toggleViewPortSubscriptiion(true)
            },
            _initOptions: function(options) {
                this._initTarget(options.target);
                this._initContainer(options.container);
                this._initHideTopOverlayHandler(options.hideTopOverlayHandler);
                this.callBase(options)
            },
            _initTarget: function(target) {
                if (!commonUtils.isDefined(target))
                    return;
                var options = this.option();
                $.each(["position.of", "animation.show.from.position.of", "animation.show.to.position.of", "animation.hide.from.position.of", "animation.hide.to.position.of"], function(_, path) {
                    var pathParts = path.split(".");
                    var option = options;
                    while (option)
                        if (pathParts.length === 1) {
                            if ($.isPlainObject(option))
                                option[pathParts.shift()] = target;
                            break
                        }
                        else
                            option = option[pathParts.shift()]
                })
            },
            _initContainer: function(container) {
                container = container === undefined ? viewPort() : container;
                var $element = this.element(),
                    $container = $element.closest(container);
                if (!$container.length)
                    $container = $(container).first();
                this._$container = $container.length ? $container : $element.parent()
            },
            _initHideTopOverlayHandler: function(handler) {
                this._hideTopOverlayHandler = handler !== undefined ? handler : $.proxy(this._defaultHideTopOverlayHandler, this)
            },
            _defaultHideTopOverlayHandler: function() {
                this.hide()
            },
            _initActions: function() {
                this._actions = {};
                $.each(ACTIONS, $.proxy(function(_, action) {
                    this._actions[action] = this._createActionByOption(action, {excludeValidators: ["disabled", "readOnly"]}) || $.noop
                }, this))
            },
            _initCloseOnOutsideClickHandler: function() {
                this._proxiedDocumentDownHandler = $.proxy(function() {
                    this._documentDownHandler.apply(this, arguments)
                }, this)
            },
            _documentDownHandler: function(e) {
                if (!this._isTopOverlay())
                    return;
                if (this._showAnimationProcessing) {
                    this._stopAnimation();
                    return
                }
                var closeOnOutsideClick = this.option("closeOnOutsideClick");
                if ($.isFunction(closeOnOutsideClick))
                    closeOnOutsideClick = closeOnOutsideClick(e);
                if (closeOnOutsideClick) {
                    var $container = this._$content,
                        outsideClick = !$container.is(e.target) && !$.contains($container.get(0), e.target);
                    if (outsideClick) {
                        if (this.option("shading"))
                            e.preventDefault();
                        this.hide()
                    }
                }
            },
            _isTopOverlay: function() {
                var zIndexStack = this._zIndexStack();
                return zIndexStack[zIndexStack.length - 1] === this._zIndex
            },
            _zIndexStack: function() {
                return Z_INDEX_STACK
            },
            _zIndexInitValue: function() {
                return FIRST_Z_INDEX
            },
            _toggleViewPortSubscriptiion: function(toggle) {
                viewPortChanged.remove(this._viewPortChangeHandle);
                if (toggle) {
                    this._viewPortChangeHandle = $.proxy(this._viewPortChangeHandler, this);
                    viewPortChanged.add(this._viewPortChangeHandle)
                }
            },
            _viewPortChangeHandler: function() {
                this._initContainer(this.option("container"));
                this._refresh()
            },
            _renderVisibilityAnimate: function(visible) {
                this._stopAnimation();
                return visible ? this._show() : this._hide()
            },
            _normalizePosition: function() {
                this._position = this.option("position")
            },
            _show: function() {
                if (this._isParentHidden())
                    return $.Deferred().resolve();
                if (this._currentVisible)
                    return $.when().promise();
                this._currentVisible = true;
                this._normalizePosition();
                var that = this,
                    deferred = $.Deferred(),
                    animation = that.option("animation") || {},
                    showAnimation = this._normalizeAnimation(animation.show, "to"),
                    startShowAnimation = showAnimation && showAnimation.start || $.noop,
                    completeShowAnimation = showAnimation && showAnimation.complete || $.noop;
                if (this._isHidingActionCancelled) {
                    delete this._isHidingActionCancelled;
                    deferred.resolve()
                }
                else {
                    this._renderVisibility(true);
                    this._animate(showAnimation, function() {
                        if (that.option("focusStateEnabled"))
                            that._focusTarget().focus();
                        completeShowAnimation.apply(this, arguments);
                        that._showAnimationProcessing = false;
                        that._actions.onShown();
                        deferred.resolve()
                    }, function() {
                        startShowAnimation.apply(this, arguments);
                        that._showAnimationProcessing = true
                    })
                }
                return deferred.promise()
            },
            _normalizeAnimation: function(animation, prop) {
                if (animation && animation[prop]) {
                    animation = $.extend({type: "slide"}, animation);
                    $.extend(animation[prop], {position: this._position})
                }
                return animation
            },
            _hide: function() {
                if (!this._currentVisible)
                    return $.when().promise();
                this._currentVisible = false;
                var that = this,
                    deferred = $.Deferred(),
                    animation = this.option("animation") || {},
                    hideAnimation = this._normalizeAnimation(animation.hide, "from"),
                    completeHideAnimation = hideAnimation && hideAnimation.complete || $.noop,
                    hidingArgs = {cancel: false};
                this._actions.onHiding(hidingArgs);
                if (hidingArgs.cancel) {
                    this._isHidingActionCancelled = true;
                    this.option("visible", true);
                    deferred.resolve()
                }
                else {
                    this._toggleShading(false);
                    this._animate(hideAnimation, function() {
                        that._renderVisibility(false);
                        completeHideAnimation.apply(this, arguments);
                        that._actions.onHidden();
                        deferred.resolve()
                    })
                }
                return deferred.promise()
            },
            _animate: function(animation, completeCallback, startCallback) {
                if (animation) {
                    startCallback = startCallback || animation.start || $.noop;
                    var $content = this._$content;
                    fx.animate(this._$content, $.extend({}, animation, {
                        start: function() {
                            $content.css("pointer-events", "none");
                            startCallback.apply(this, arguments)
                        },
                        complete: function() {
                            $content.css("pointer-events", "");
                            completeCallback.apply(this, arguments)
                        }
                    }))
                }
                else
                    completeCallback()
            },
            _stopAnimation: function() {
                fx.stop(this._$content, true)
            },
            _renderVisibility: function(visible) {
                if (visible && this._isParentHidden())
                    return;
                this._currentVisible = visible;
                this._stopAnimation();
                if (!visible)
                    domUtils.triggerHidingEvent(this._$content);
                this._toggleVisibility(visible);
                this._$content.toggleClass(INVISIBLE_STATE_CLASS, !visible);
                this._updateZIndexStackPosition(visible);
                if (visible) {
                    this._renderContent();
                    this._actions.onShowing();
                    this._moveToContainer();
                    this._renderGeometry();
                    domUtils.triggerShownEvent(this._$content);
                    domUtils.triggerResizeEvent(this._$content)
                }
                else
                    this._moveFromContainer();
                this._toggleShading(visible);
                this._toggleSubscriptions(visible)
            },
            _updateZIndexStackPosition: function(pushToStack) {
                var zIndexStack = this._zIndexStack();
                if (pushToStack) {
                    if (!this._zIndex) {
                        var length = zIndexStack.length;
                        this._zIndex = (length ? zIndexStack[length - 1] : this._zIndexInitValue()) + 1;
                        zIndexStack.push(this._zIndex)
                    }
                    this._$wrapper.css("z-index", this._zIndex);
                    this._$content.css("z-index", this._zIndex)
                }
                else if (this._zIndex) {
                    var index = $.inArray(this._zIndex, zIndexStack);
                    zIndexStack.splice(index, 1);
                    delete this._zIndex
                }
            },
            _toggleShading: function(visible) {
                this._$wrapper.toggleClass(OVERLAY_MODAL_CLASS, this.option("shading") && !this.option("container"));
                this._$wrapper.toggleClass(OVERLAY_SHADER_CLASS, visible && this.option("shading"));
                this._$wrapper.css("background-color", this.option("shading") ? this.option("shadingColor") : "");
                this._toggleTabTerminator(visible && this.option("shading"))
            },
            _toggleTabTerminator: function(enabled) {
                var eventName = eventUtils.addNamespace("keydown", this.NAME);
                if (enabled)
                    $(document).on(eventName, $.proxy(this._tabKeyHandler, this));
                else
                    $(document).off(eventName)
            },
            _tabKeyHandler: function(e) {
                if (e.keyCode !== TAB_KEY)
                    return;
                var tabbableElements = this._$wrapper.find(selectors.tabbable),
                    $firstTabbable = tabbableElements.first(),
                    $lastTabbable = tabbableElements.last(),
                    isTabOnLast = !e.shiftKey && e.target === $lastTabbable.get(0),
                    isShiftTabOnFirst = e.shiftKey && e.target === $firstTabbable.get(0),
                    isEmptyTabList = tabbableElements.length === 0,
                    isOutsideTarget = $.inArray(e.target, tabbableElements) === -1;
                if (isTabOnLast || isShiftTabOnFirst || isEmptyTabList || isOutsideTarget) {
                    e.preventDefault();
                    (e.shiftKey ? $lastTabbable : $firstTabbable).focusin().focus()
                }
            },
            _toggleSubscriptions: function(enabled) {
                this._toggleHideTopOverlayCallback(enabled);
                this._toggleDocumentDownHandler(enabled);
                this._toggleParentsScrollSubscription(enabled)
            },
            _toggleHideTopOverlayCallback: function(subscribe) {
                if (!this._hideTopOverlayHandler)
                    return;
                if (subscribe && this.option("closeOnBackButton"))
                    hideTopOverlayCallback.add(this._hideTopOverlayHandler);
                else
                    hideTopOverlayCallback.remove(this._hideTopOverlayHandler)
            },
            _toggleDocumentDownHandler: function(enabled) {
                var eventName = eventUtils.addNamespace(pointerEvents.down, this.NAME);
                if (enabled)
                    $(document).on(eventName, this._proxiedDocumentDownHandler);
                else
                    $(document).off(eventName, this._proxiedDocumentDownHandler)
            },
            _toggleParentsScrollSubscription: function(subscribe) {
                if (!this._position)
                    return;
                var target = this._position.of || $(),
                    closeOnScroll = this.option("closeOnTargetScroll"),
                    $parents = getElement(target).parents(),
                    scrollEvents = eventUtils.addNamespace("scroll", this.NAME);
                if (devices.real().platform === "generic")
                    $parents = $parents.add(window);
                this._proxiedTargetParentsScrollHandler = this._proxiedTargetParentsScrollHandler || $.proxy(function(e) {
                    this._targetParentsScrollHandler(e)
                }, this);
                $().add(this._$prevTargetParents).off(scrollEvents, this._proxiedTargetParentsScrollHandler);
                if (subscribe && closeOnScroll) {
                    $parents.on(scrollEvents, this._proxiedTargetParentsScrollHandler);
                    this._$prevTargetParents = $parents
                }
            },
            _targetParentsScrollHandler: function(e) {
                var closeHandled = false,
                    closeOnScroll = this.option("closeOnTargetScroll");
                if ($.isFunction(closeOnScroll))
                    closeHandled = closeOnScroll(e);
                if (!closeHandled)
                    this.hide()
            },
            _render: function() {
                this.callBase();
                this._renderVisibility(this.option("visible"))
            },
            _renderContent: function() {
                var shouldDeferRendering = !this.option("visible") && this.option("deferRendering");
                var isParentHidden = this.option("visible") && this._isParentHidden();
                if (this._contentAlreadyRendered || shouldDeferRendering || isParentHidden)
                    return;
                this._contentAlreadyRendered = true;
                this.callBase()
            },
            _isParentHidden: function() {
                var $parent = this.element().parent();
                if ($parent.is(":visible"))
                    return false;
                var isHidden = false;
                $parent.add($parent.parents()).each(function() {
                    var $element = $(this);
                    if ($element.css("display") === "none") {
                        isHidden = true;
                        return false
                    }
                });
                return isHidden || !$.contains(document, $parent.get(0))
            },
            _renderContentImpl: function() {
                var $element = this.element();
                this._$content.appendTo($element);
                var contentTemplate = this._getTemplate(this.option("contentTemplate"));
                contentTemplate && contentTemplate.render(this.content());
                this._renderDrag();
                this._renderResize();
                this._renderScrollTerminator()
            },
            _renderDrag: function() {
                var $dragTarget = this._getDragTarget();
                if (!$dragTarget)
                    return;
                var startEventName = eventUtils.addNamespace("dxdragstart", this.NAME),
                    updateEventName = eventUtils.addNamespace("dxdrag", this.NAME);
                $dragTarget.off(startEventName).off(updateEventName);
                if (!this.option("dragEnabled"))
                    return;
                $dragTarget.on(startEventName, $.proxy(this._dragStartHandler, this)).on(updateEventName, $.proxy(this._dragUpdateHandler, this))
            },
            _renderResize: function() {
                this._createComponent(this._$content, "dxResizable", {
                    handles: this.option("resizeEnabled") ? "all" : "none",
                    onResizeEnd: $.proxy(this._resizeEndHandler, this),
                    onResize: $.proxy(this._actions.onResize, this),
                    onResizeStart: $.proxy(this._actions.onResizeStart, this),
                    minHeight: 100,
                    minWidth: 100,
                    area: this._$container
                })
            },
            _resizeEndHandler: function() {
                this._geometryChangeHandled = true;
                this._actions.onResizeEnd()
            },
            _renderScrollTerminator: function() {
                var $scrollTerminator = this._wrapper();
                var scrollEventName = eventUtils.addNamespace("dxscroll", this.NAME);
                $scrollTerminator.off(scrollEventName).on(scrollEventName, {
                    validate: function() {
                        return true
                    },
                    getDirection: function() {
                        return "both"
                    },
                    _toggleGestureCover: $.noop,
                    _clearSelection: $.noop,
                    isNative: true
                }, function(e) {
                    if (e.originalEvent.originalEvent.type !== "mousemove")
                        e.preventDefault()
                })
            },
            _getDragTarget: function() {
                return this.content()
            },
            _dragStartHandler: function(e) {
                e.targetElements = [];
                this._prevOffset = {
                    x: 0,
                    y: 0
                };
                var allowedOffsets = this._allowedOffsets();
                e.maxTopOffset = allowedOffsets.top;
                e.maxBottomOffset = allowedOffsets.bottom;
                e.maxLeftOffset = allowedOffsets.left;
                e.maxRightOffset = allowedOffsets.right
            },
            _deltaSize: function() {
                var $content = this._$content,
                    $container = this._$container;
                var contentWidth = $content.outerWidth(),
                    contentHeight = $content.outerHeight(),
                    containerWidth = $container.width(),
                    containerHeight = $container.height();
                return {
                        width: containerWidth - contentWidth,
                        height: containerHeight - contentHeight
                    }
            },
            _dragUpdateHandler: function(e) {
                var offset = e.offset,
                    prevOffset = this._prevOffset,
                    targetOffset = {
                        top: offset.y - prevOffset.y,
                        left: offset.x - prevOffset.x
                    };
                this._changePosition(targetOffset);
                this._prevOffset = offset
            },
            _changePosition: function(offset) {
                var position = translator.locate(this._$content);
                translator.move(this._$content, {
                    left: position.left + offset.left,
                    top: position.top + offset.top
                });
                this._geometryChangeHandled = true
            },
            _allowedOffsets: function() {
                var position = translator.locate(this._$content),
                    deltaSize = this._deltaSize(),
                    isAllowedDrag = deltaSize.height >= 0 && deltaSize.width >= 0,
                    boundaryOffset = this.option("boundaryOffset");
                return {
                        top: isAllowedDrag ? position.top + boundaryOffset.v : 0,
                        bottom: isAllowedDrag ? -position.top + deltaSize.height - boundaryOffset.v : 0,
                        left: isAllowedDrag ? position.left + boundaryOffset.h : 0,
                        right: isAllowedDrag ? -position.left + deltaSize.width - boundaryOffset.h : 0
                    }
            },
            _fireContentReadyAction: function() {
                if (this.option("visible"))
                    this._moveToContainer();
                this.callBase.apply(this, arguments)
            },
            _moveFromContainer: function() {
                this._$content.appendTo(this.element());
                this._detachWrapperToContainer()
            },
            _detachWrapperToContainer: function() {
                this._$wrapper.detach()
            },
            _moveToContainer: function() {
                this._attachWrapperToContainer();
                this._$content.appendTo(this._$wrapper)
            },
            _attachWrapperToContainer: function() {
                var $element = this.element();
                if (this._$container && this._$container[0] !== $element.parent()[0])
                    this._$wrapper.appendTo(this._$container);
                else
                    this._$wrapper.appendTo($element)
            },
            _renderGeometry: function() {
                if (this.option("visible"))
                    this._renderGeometryImpl()
            },
            _renderGeometryImpl: function() {
                this._stopAnimation();
                this._normalizePosition();
                this._renderShading();
                this._renderDimensions();
                this._renderPosition()
            },
            _renderShading: function() {
                var $wrapper = this._$wrapper,
                    $container = this._getContainer();
                $wrapper.css("position", this._isWindow($container) && !iOS ? "fixed" : "absolute");
                if (this.option("shading"))
                    $wrapper.show();
                this._renderShadingDimensions();
                this._renderShadingPosition()
            },
            _renderShadingPosition: function() {
                if (this.option("shading")) {
                    var $container = this._getContainer();
                    positionUtils.setup(this._$wrapper, {
                        my: "top left",
                        at: "top left",
                        of: $container
                    })
                }
            },
            _renderShadingDimensions: function() {
                if (this.option("shading")) {
                    var $container = this._getContainer(),
                        wrapperWidth = this._isWindow($container) ? "100%" : $container.outerWidth(),
                        wrapperHeight = this._isWindow($container) ? "100%" : $container.outerHeight();
                    this._$wrapper.css({
                        width: wrapperWidth,
                        height: wrapperHeight
                    })
                }
            },
            _isWindow: function($element) {
                return !!$element && $.isWindow($element.get(0))
            },
            _getContainer: function() {
                var position = this._position,
                    container = this.option("container"),
                    positionOf = position ? position.of || window : null;
                return getElement(container || positionOf)
            },
            _renderDimensions: function() {
                this._$content.css({
                    minWidth: this.option("minWidth"),
                    maxWidth: this.option("maxWidth"),
                    minHeight: this.option("minHeight"),
                    maxHeight: this.option("maxHeight")
                });
                if (this._geometryChangeHandled) {
                    var $content = this._$content,
                        $container = this._$container;
                    $content.outerWidth(Math.min($content.outerWidth(), $container.width())).outerHeight(Math.min($content.outerHeight(), $container.height()))
                }
                else
                    this._$content.outerWidth(this.option("width")).outerHeight(this.option("height"))
            },
            _renderPosition: function() {
                var position,
                    containerPosition,
                    allowedOffsets,
                    $content;
                if (this._geometryChangeHandled) {
                    $content = this._$content;
                    position = translator.locate($content);
                    allowedOffsets = this._allowedOffsets();
                    this._changePosition({
                        top: fitIntoRange(0, -allowedOffsets.top, allowedOffsets.bottom),
                        left: fitIntoRange(0, -allowedOffsets.left, allowedOffsets.right)
                    })
                }
                else {
                    translator.resetPosition(this._$content);
                    this._renderOverlayBoundaryOffset();
                    position = this._position;
                    containerPosition = positionUtils.calculate(this._$content, position);
                    this._actions.onPositioning({position: containerPosition});
                    var resultPosition = positionUtils.setup(this._$content, containerPosition);
                    this._actions.onPositioned({position: resultPosition});
                    forceRepaint(this._$content)
                }
            },
            _renderOverlayBoundaryOffset: function() {
                var boundaryOffset = this.option("boundaryOffset");
                this._$content.css("margin", boundaryOffset.v + "px " + boundaryOffset.h + "px")
            },
            _focusTarget: function() {
                return this._$content
            },
            _attachKeyboardEvents: function() {
                this._keyboardProcessor = new KeyboardProcessor({
                    element: this._$content,
                    handler: this._keyboardHandler,
                    context: this
                })
            },
            _keyboardHandler: function(options) {
                var e = options.originalEvent,
                    $target = $(e.target);
                if ($target.is(this._$content))
                    this.callBase.apply(this, arguments)
            },
            _isVisible: function() {
                if (this.content().parent().length)
                    return this.content().is(":visible") || this.element().is(".dx-state-invisible");
                else
                    return this.callBase()
            },
            _visibilityChanged: function(visible) {
                if (visible) {
                    if (this.option("visible"))
                        this._renderVisibilityAnimate(visible)
                }
                else
                    this._renderVisibilityAnimate(visible)
            },
            _dimensionChanged: function() {
                this._renderGeometry()
            },
            _clean: function() {
                if (!this._contentAlreadyRendered)
                    this.content().empty();
                this._cleanFocusState()
            },
            _dispose: function() {
                DX.fx.stop(this._$content, false);
                this._toggleViewPortSubscriptiion(false);
                this._toggleSubscriptions(false);
                this._updateZIndexStackPosition(false);
                this._actions = null;
                this.callBase();
                this._$wrapper.remove();
                this._$content.remove()
            },
            _toggleDisabledState: function(value) {
                this.callBase.apply(this, arguments);
                this._$content.toggleClass(DISABLED_STATE_CLASS, Boolean(value))
            },
            _toggleRTLDirection: function(rtl) {
                this._$content.toggleClass(RTL_DIRECTION_CLASS, rtl)
            },
            _optionChanged: function(args) {
                var value = args.value;
                if ($.inArray(args.name, ACTIONS) > -1) {
                    this._initActions();
                    return
                }
                switch (args.name) {
                    case"dragEnabled":
                        this._renderDrag();
                        this._renderGeometry();
                        break;
                    case"resizeEnabled":
                        this._renderResize();
                        this._renderGeometry();
                        break;
                    case"shading":
                    case"shadingColor":
                        this._toggleShading(this.option("visible"));
                        break;
                    case"width":
                    case"minWidth":
                    case"maxWidth":
                    case"height":
                    case"minHeight":
                    case"maxHeight":
                    case"position":
                    case"boundaryOffset":
                        this._renderGeometry();
                        break;
                    case"visible":
                        this._renderVisibilityAnimate(value).done($.proxy(function() {
                            if (!this._animateDeferred)
                                return;
                            this._animateDeferred.resolveWith(this)
                        }, this));
                        break;
                    case"target":
                        this._initTarget(value);
                        this._invalidate();
                        break;
                    case"container":
                        this._initContainer(value);
                        this._invalidate();
                        break;
                    case"deferRendering":
                    case"contentTemplate":
                        this._contentAlreadyRendered = false;
                        this._invalidate();
                        break;
                    case"closeOnBackButton":
                        this._toggleHideTopOverlayCallback(this.option("visible"));
                        break;
                    case"closeOnOutsideClick":
                        this._toggleDocumentDownHandler(this.option("visible"));
                        break;
                    case"closeOnTargetScroll":
                        this._toggleParentsScrollSubscription(this.option("visible"));
                        break;
                    case"animation":
                        break;
                    default:
                        this.callBase(args)
                }
            },
            toggle: function(showing) {
                showing = showing === undefined ? !this.option("visible") : showing;
                if (showing === this.option("visible"))
                    return $.Deferred().resolve().promise();
                var animateDeferred = $.Deferred();
                this._animateDeferred = animateDeferred;
                this.option("visible", showing);
                return animateDeferred.promise().done($.proxy(function() {
                        delete this._animateDeferred
                    }, this))
            },
            show: function() {
                return this.toggle(true)
            },
            hide: function() {
                return this.toggle(false)
            },
            content: function() {
                return this._$content
            },
            repaint: function() {
                this._renderGeometry()
            }
        }));
        DevExpress.ui.dxOverlay.baseZIndex = function(zIndex) {
            FIRST_Z_INDEX = zIndex
        };
        ui.dxOverlay.__internals = {
            OVERLAY_CLASS: OVERLAY_CLASS,
            OVERLAY_WRAPPER_CLASS: OVERLAY_WRAPPER_CLASS,
            OVERLAY_CONTENT_CLASS: OVERLAY_CONTENT_CLASS,
            OVERLAY_SHADER_CLASS: OVERLAY_SHADER_CLASS,
            OVERLAY_MODAL_CLASS: OVERLAY_MODAL_CLASS
        }
    })(jQuery, DevExpress);
    /*! Module tmp-widgets-for-exporter, file ui.resizable.js */
    (function($, DX, undefined) {
        var ui = DX.ui,
            translator = DX.require("/utils/utils.translator"),
            stringUtils = DX.require("/utils/utils.string"),
            fitIntoRange = DX.require("/utils/utils.math").fitIntoRange,
            registerComponent = DX.require("/componentRegistrator"),
            DOMComponent = DX.require("/domComponent"),
            eventUtils = DX.require("/ui/events/ui.events.utils"),
            RESIZABLE = "dxResizable",
            RESIZABLE_CLASS = "dx-resizable",
            RESIZABLE_RESIZING_CLASS = "dx-resizable-resizing",
            RESIZABLE_HANDLE_CLASS = "dx-resizable-handle",
            RESIZABLE_HANDLE_TOP_CLASS = "dx-resizable-handle-top",
            RESIZABLE_HANDLE_BOTTOM_CLASS = "dx-resizable-handle-bottom",
            RESIZABLE_HANDLE_LEFT_CLASS = "dx-resizable-handle-left",
            RESIZABLE_HANDLE_RIGHT_CLASS = "dx-resizable-handle-right",
            RESIZABLE_HANDLE_CORNER_CLASS = "dx-resizable-handle-corner",
            DRAGSTART_START_EVENT_NAME = eventUtils.addNamespace("dxdragstart", RESIZABLE),
            DRAGSTART_EVENT_NAME = eventUtils.addNamespace("dxdrag", RESIZABLE),
            DRAGSTART_END_EVENT_NAME = eventUtils.addNamespace("dxdragend", RESIZABLE);
        registerComponent(RESIZABLE, ui, DOMComponent.inherit({
            _getDefaultOptions: function() {
                return $.extend(this.callBase(), {
                        handles: "all",
                        step: "1",
                        area: undefined,
                        minWidth: 30,
                        maxWidth: Infinity,
                        minHeight: 30,
                        maxHeight: Infinity,
                        onResizeStart: null,
                        onResize: null,
                        onResizeEnd: null
                    })
            },
            _init: function() {
                this.callBase();
                this.element().addClass(RESIZABLE_CLASS)
            },
            _render: function() {
                this.callBase();
                this._renderActions();
                this._renderHandles()
            },
            _renderActions: function() {
                this._resizeStartAction = this._createActionByOption("onResizeStart");
                this._resizeEndAction = this._createActionByOption("onResizeEnd");
                this._resizeAction = this._createActionByOption("onResize")
            },
            _renderHandles: function() {
                var handles = this.option("handles");
                if (handles === "none")
                    return;
                var directions = handles === "all" ? ['top', 'bottom', 'left', 'right'] : handles.split(" ");
                $.each(directions, $.proxy(function(index, handleName) {
                    this._renderHandle(handleName)
                }, this));
                $.inArray('bottom', directions) + 1 && $.inArray('right', directions) + 1 && this._renderHandle("corner-bottom-right");
                $.inArray('bottom', directions) + 1 && $.inArray('left', directions) + 1 && this._renderHandle("corner-bottom-left");
                $.inArray('top', directions) + 1 && $.inArray('right', directions) + 1 && this._renderHandle("corner-top-right");
                $.inArray('top', directions) + 1 && $.inArray('left', directions) + 1 && this._renderHandle("corner-top-left")
            },
            _renderHandle: function(handleName) {
                var $element = this.element(),
                    $handle = $("<div>");
                $handle.addClass(RESIZABLE_HANDLE_CLASS).addClass(RESIZABLE_HANDLE_CLASS + "-" + handleName).appendTo($element);
                this._attachEventHandlers($handle)
            },
            _attachEventHandlers: function($handle) {
                if (this.option("disabled"))
                    return;
                var handlers = {};
                handlers[DRAGSTART_START_EVENT_NAME] = $.proxy(this._dragStartHandler, this);
                handlers[DRAGSTART_EVENT_NAME] = $.proxy(this._dragHandler, this);
                handlers[DRAGSTART_END_EVENT_NAME] = $.proxy(this._dragEndHandler, this);
                $handle.on(handlers, {
                    direction: "both",
                    immediate: true
                })
            },
            _dragStartHandler: function(e) {
                var $element = this.element();
                if ($element.is(".dx-state-disabled, .dx-state-disabled *")) {
                    e.cancel = true;
                    return
                }
                this._toggleResizingClass(true);
                this._movingSides = this._getMovingSides(e);
                this._elementLocation = translator.locate($element);
                this._elementSize = {
                    width: $element.outerWidth(),
                    height: $element.outerHeight()
                };
                this._renderDragOffsets(e);
                this._resizeStartAction({
                    jQueryEvent: e,
                    width: this._elementSize.width,
                    height: this._elementSize.height,
                    handles: this._movingSides
                });
                e.targetElements = null
            },
            _toggleResizingClass: function(value) {
                this.element().toggleClass(RESIZABLE_RESIZING_CLASS, value)
            },
            _renderDragOffsets: function(e) {
                var area = this._getArea();
                if (!area)
                    return;
                var $handle = $(e.target).closest("." + RESIZABLE_HANDLE_CLASS),
                    handleWidth = $handle.outerWidth(),
                    handleHeight = $handle.outerHeight(),
                    handleOffset = $handle.offset(),
                    areaOffset = area.offset;
                e.maxLeftOffset = handleOffset.left - areaOffset.left;
                e.maxRightOffset = areaOffset.left + area.width - handleOffset.left - handleWidth;
                e.maxTopOffset = handleOffset.top - areaOffset.top;
                e.maxBottomOffset = areaOffset.top + area.height - handleOffset.top - handleHeight
            },
            _getBorderWidth: function($element, direction) {
                var borderWidth = $element.css("border-" + direction + "-width");
                return parseInt(borderWidth) || 0
            },
            _dragHandler: function(e) {
                var $element = this.element(),
                    offset = this._getOffset(e),
                    sides = this._movingSides;
                var location = this._elementLocation,
                    size = this._elementSize;
                var width = size.width + offset.x * (sides.left ? -1 : 1),
                    height = size.height + offset.y * (sides.top ? -1 : 1);
                this._renderSize(width, height);
                var offsetTop = offset.y - (this.option("height") - height),
                    offsetLeft = offset.x - (this.option("width") - width);
                translator.move($element, {
                    top: location.top + (sides.top ? offsetTop : 0),
                    left: location.left + (sides.left ? offsetLeft : 0)
                });
                this._resizeAction({
                    jQueryEvent: e,
                    width: width,
                    height: height,
                    handles: this._movingSides
                })
            },
            _getOffset: function(e) {
                var offset = e.offset,
                    steps = stringUtils.pairToObject(this.option("step")),
                    sides = this._getMovingSides(e);
                if (!sides.left && !sides.right)
                    offset.x = 0;
                if (!sides.top && !sides.bottom)
                    offset.y = 0;
                return {
                        x: offset.x - offset.x % steps.h,
                        y: offset.y - offset.y % steps.v
                    }
            },
            _getMovingSides: function(e) {
                var $target = $(e.target);
                return {
                        top: $target.hasClass(RESIZABLE_HANDLE_TOP_CLASS) || $target.hasClass(RESIZABLE_HANDLE_CORNER_CLASS + "-top-left") || $target.hasClass(RESIZABLE_HANDLE_CORNER_CLASS + "-top-right"),
                        left: $target.hasClass(RESIZABLE_HANDLE_LEFT_CLASS) || $target.hasClass(RESIZABLE_HANDLE_CORNER_CLASS + "-top-left") || $target.hasClass(RESIZABLE_HANDLE_CORNER_CLASS + "-bottom-left"),
                        bottom: $target.hasClass(RESIZABLE_HANDLE_BOTTOM_CLASS) || $target.hasClass(RESIZABLE_HANDLE_CORNER_CLASS + "-bottom-left") || $target.hasClass(RESIZABLE_HANDLE_CORNER_CLASS + "-bottom-right"),
                        right: $target.hasClass(RESIZABLE_HANDLE_RIGHT_CLASS) || $target.hasClass(RESIZABLE_HANDLE_CORNER_CLASS + "-top-right") || $target.hasClass(RESIZABLE_HANDLE_CORNER_CLASS + "-bottom-right")
                    }
            },
            _getArea: function() {
                var area = this.option("area");
                if ($.isFunction(area))
                    area = area.call(this);
                if ($.isPlainObject(area))
                    return this._getAreaFromObject(area);
                return this._getAreaFromElement(area)
            },
            _getAreaFromObject: function(area) {
                var result = {
                        width: area.right - area.left,
                        height: area.bottom - area.top,
                        offset: {
                            left: area.left,
                            top: area.top
                        }
                    };
                this._correctAreaGeometry(result);
                return result
            },
            _getAreaFromElement: function(area) {
                var $area = $(area),
                    result;
                if ($area.length) {
                    result = {};
                    result.width = $area.innerWidth();
                    result.height = $area.innerHeight();
                    result.offset = $area.offset();
                    this._correctAreaGeometry(result, $area)
                }
                return result
            },
            _correctAreaGeometry: function(result, $area) {
                var areaBorderLeft = $area ? this._getBorderWidth($area, "left") : 0,
                    areaBorderTop = $area ? this._getBorderWidth($area, "top") : 0;
                result.offset.left += areaBorderLeft + this._getBorderWidth(this.element(), "left");
                result.offset.top += areaBorderTop + this._getBorderWidth(this.element(), "top");
                result.width -= this.element().outerWidth() - this.element().innerWidth();
                result.height -= this.element().outerHeight() - this.element().innerHeight()
            },
            _dragEndHandler: function(e) {
                var $element = this.element();
                this._resizeEndAction({
                    jQueryEvent: e,
                    width: $element.outerWidth(),
                    height: $element.outerHeight(),
                    handles: this._movingSides
                });
                this._toggleResizingClass(false)
            },
            _renderSize: function(width, height) {
                this.option("width", fitIntoRange(width, this.option("minWidth"), this.option("maxWidth")));
                this.option("height", fitIntoRange(height, this.option("minHeight"), this.option("maxHeight")))
            },
            _optionChanged: function(args) {
                switch (args.name) {
                    case"disabled":
                    case"handles":
                        this._invalidate();
                        break;
                    case"minWidth":
                    case"maxWidth":
                    case"minHeight":
                    case"maxHeight":
                        this._renderSize(this.element().outerWidth(), this.element().outerHeight());
                        break;
                    case"onResize":
                    case"onResizeStart":
                    case"onResizeEnd":
                        this._renderActions();
                        break;
                    case"gridStepHorizontal":
                    case"gridStepVertical":
                    case"area":
                    case"step":
                        break;
                    default:
                        this.callBase(args);
                        break
                }
            },
            _clean: function() {
                this.element().find("." + RESIZABLE_HANDLE_CLASS).remove()
            }
        }))
    })(jQuery, DevExpress);
    DevExpress.MOD_TMP_WIDGETS_FOR_EXPORTER = true
}
if (!window.DevExpress || !DevExpress.MOD_TMP_EXPORTER) {
    /*! Module tmp-exporter, file exporter.js */
    (function(DX, $) {
        var viz = DX.viz,
            registerComponent = DX.require("/componentRegistrator"),
            DOMComponent = DX.require("/domComponent"),
            FILE = "file",
            ICON_TO = "exportTo",
            ICON_PRINT = "print",
            FORMATS_EXPORT = ["PDF", "PNG", "SVG"],
            FORMATS_SUPPORTS = ["JPEG", "GIF"].concat(FORMATS_EXPORT);
        var Exporter = DOMComponent.inherit({
                _normalizeHtml: viz.BaseWidget.prototype._normalizeHtml,
                _killTracker: viz.BaseWidget.prototype._killTracker,
                _getSvgElements: function() {
                    var that = this,
                        svgArray = [];
                    $(that.getSourceContainer()).find("svg").each(function(i) {
                        svgArray[i] = that._normalizeHtml($(this).clone().wrap("<div></div>").parent().html())
                    });
                    return JSON.stringify(svgArray)
                },
                _appendTextArea: function(name, value, rootElement) {
                    $("<textarea/>", {
                        id: name,
                        name: name,
                        val: value
                    }).appendTo(rootElement)
                },
                _formSubmit: function(form) {
                    form.submit();
                    form.remove();
                    return form.submit()
                },
                _getDefaultOptions: function() {
                    return $.extend(this.callBase(), {
                            redrawOnResize: false,
                            menuAlign: 'right',
                            exportFormat: FORMATS_EXPORT,
                            printingEnabled: true,
                            fileName: FILE,
                            showMenu: true
                        })
                },
                _createWindow: function() {
                    return window.open('', 'printDiv', '')
                },
                _createExportItems: function(exportFormat) {
                    var that = this;
                    return $.map(exportFormat, function(value) {
                            value = value.toUpperCase();
                            if (that.getSourceContainer().find("svg").length > 1 && value === "SVG")
                                return null;
                            if ($.inArray(value.toUpperCase(), FORMATS_SUPPORTS) === -1)
                                return null;
                            return {
                                    name: value,
                                    text: value + ' ' + FILE
                                }
                        })
                },
                _render: function() {
                    var that = this,
                        fileName = that.option('fileName'),
                        exportItems = that._createExportItems(that.option('exportFormat')),
                        container = $('<div />'),
                        rootItems = [{
                                name: 'export',
                                icon: ICON_TO,
                                items: exportItems
                            }],
                        options = {
                            items: rootItems,
                            onItemClick: function(properties) {
                                switch (properties.itemData.name) {
                                    case'print':
                                        that.print();
                                        break;
                                    case'export':
                                        break;
                                    default:
                                        that.exportTo(fileName, properties.itemData.name)
                                }
                            }
                        };
                    if (that.option('showMenu')) {
                        that.option('printingEnabled') && rootItems.push({
                            icon: ICON_PRINT,
                            name: 'print',
                            click: function() {
                                that.print()
                            }
                        });
                        container.dxMenu(options);
                        that._$element.empty();
                        that._$element.append(container);
                        return options
                    }
                },
                _exportSVG: function(fileName, format, $sourceContainer) {
                    var form = $("<form/>", {
                            method: "POST",
                            action: this.option('serverUrl'),
                            enctype: "application/x-www-form-urlencoded",
                            target: "_self",
                            css: {
                                display: "none",
                                visibility: "hidden"
                            }
                        }),
                        svgElements = this._getSvgElements();
                    this._appendTextArea("exportContent", $sourceContainer.clone().wrap("<div></div>").parent().html(), form);
                    this._appendTextArea("svgElements", svgElements, form);
                    this._appendTextArea("fileName", fileName, form);
                    this._appendTextArea("format", format.toLowerCase(), form);
                    this._appendTextArea("width", $sourceContainer.width(), form);
                    this._appendTextArea("height", $sourceContainer.height(), form);
                    this._appendTextArea("url", window.location.host, form);
                    $(document.body).append(form);
                    this._testForm = form;
                    this._formSubmit(form)
                },
                getSourceContainer: function() {
                    return $(this.option('sourceContainer'))
                },
                print: function() {
                    var $sourceContainer = this.getSourceContainer().html(),
                        printWindow = this._createWindow();
                    if (!printWindow)
                        return;
                    $(printWindow.document.body).html($sourceContainer);
                    printWindow.document.close();
                    printWindow.focus();
                    printWindow.print();
                    printWindow.close()
                },
                exportTo: function(fileName, format) {
                    var that = this,
                        $sourceContainer = that.getSourceContainer();
                    if ($sourceContainer.find("svg").length)
                        that._exportSVG(fileName, format, $sourceContainer)
                }
            });
        $.extend(true, DX, {exporter: {Exporter: Exporter}});
        registerComponent("dxExporter", Exporter)
    })(DevExpress, jQuery);
    DevExpress.MOD_TMP_EXPORTER = true
}
