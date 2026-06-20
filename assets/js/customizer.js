(function () {
    var l10n = window.bcCustomizer || {};
    var styleChoices = l10n.styles || { premium: "Premium" };

    function escapeHtml(value) {
        return String(value == null ? "" : value).replace(/[&<>"']/g, function (c) {
            return { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c];
        });
    }

    function initControl(el) {
        var pages = [];
        try {
            pages = JSON.parse(el.getAttribute("data-pages") || "[]");
        } catch (e) {
            pages = [];
        }

        var hidden = el.querySelector(".bc-buttons-value");
        var list = el.querySelector(".bc-buttons-list");
        var addBtn = el.querySelector(".bc-add-button");

        var rows = [];
        try {
            rows = JSON.parse(hidden.value || "[]");
        } catch (e) {
            rows = [];
        }
        if (!Array.isArray(rows)) {
            rows = [];
        }

        function pageOptions(selected) {
            var html = '<option value="0">' + escapeHtml(l10n.selectPage || "— Select a page —") + "</option>";
            return html + pages
                .map(function (p) {
                    return '<option value="' + p.id + '"' + (parseInt(p.id, 10) === parseInt(selected, 10) ? " selected" : "") + ">" + escapeHtml(p.title) + "</option>";
                })
                .join("");
        }

        function styleOptions(selected) {
            return Object.keys(styleChoices)
                .map(function (key) {
                    return '<option value="' + key + '"' + (key === selected ? " selected" : "") + ">" + escapeHtml(styleChoices[key]) + "</option>";
                })
                .join("");
        }

        function serialize() {
            var data = [];
            list.querySelectorAll(".bc-button-row").forEach(function (row) {
                data.push({
                    text: row.querySelector(".bc-field-text").value,
                    page: parseInt(row.querySelector(".bc-field-page").value, 10) || 0,
                    style: row.querySelector(".bc-field-style").value,
                    new_tab: row.querySelector(".bc-field-newtab").checked ? 1 : 0,
                });
            });
            hidden.value = JSON.stringify(data);
            hidden.dispatchEvent(new Event("change", { bubbles: true }));
        }

        function addRow(item) {
            item = item || {};
            var li = document.createElement("li");
            li.className = "bc-button-row";
            li.innerHTML =
                '<div class="bc-row-head"><strong>' + escapeHtml(l10n.button || "Button") + "</strong>" +
                '<a class="bc-remove" role="button">' + escapeHtml(l10n.remove || "Remove") + "</a></div>" +
                '<input type="text" class="bc-field-text widefat" placeholder="' + escapeHtml(l10n.buttonText || "Button text") + '" value="' + escapeHtml(item.text) + '" />' +
                '<select class="bc-field-page widefat">' + pageOptions(item.page) + "</select>" +
                '<label class="bc-field-style-label">' + escapeHtml(l10n.styleLabel || "Style") +
                '<select class="bc-field-style widefat">' + styleOptions(item.style || "premium") + "</select></label>" +
                '<label class="bc-field-newtab-label"><input type="checkbox" class="bc-field-newtab"' + (item.new_tab ? " checked" : "") + " /> " + escapeHtml(l10n.newTab || "Open in new tab") + "</label>";
            list.appendChild(li);

            li.addEventListener("input", serialize);
            li.addEventListener("change", serialize);
            li.querySelector(".bc-remove").addEventListener("click", function () {
                li.parentNode.removeChild(li);
                serialize();
            });
        }

        rows.forEach(addRow);
        addBtn.addEventListener("click", function () {
            addRow();
            serialize();
        });
    }

    function initOnce(el) {
        if (el && !el.dataset.bcInit) {
            el.dataset.bcInit = "1";
            initControl(el);
        }
    }

    if (window.wp && wp.customize && wp.customize.control) {
        wp.customize.control("header_buttons", function (control) {
            control.deferred.embedded.done(function () {
                initOnce(control.container[0].querySelector(".bc-header-buttons-control"));
            });
        });
    } else {
        document.querySelectorAll(".bc-header-buttons-control").forEach(initOnce);
    }
})();
