/**
 * jQuery.ScrollTo - Easy element scrolling using jQuery.
 * Copyright (c) 2007-2009 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogspot.com
 * Dual licensed under MIT and GPL.
 * Date: 5/25/2009
 * @author Ariel Flesler
 * @version 1.4.2
 *
 * http://flesler.blogspot.com/2007/10/jqueryscrollto.html
 */
;(function (d) {
    var k = d.scrollTo = function (a, i, e) {
        d(window).scrollTo(a, i, e)
    };
    k.defaults = {axis: 'xy', duration: parseFloat(d.fn.jquery) >= 1.3 ? 0 : 1};
    k.window = function (a) {
        return d(window)._scrollable()
    };
    d.fn._scrollable = function () {
        return this.map(function () {
            var a = this,
                i = !a.nodeName || d.inArray(a.nodeName.toLowerCase(), ['iframe', '#document', 'html', 'body']) != -1;
            if (!i) return a;
            var e = (a.contentWindow || a).document || a.ownerDocument || a;
            return d.browser.safari || e.compatMode == 'BackCompat' ? e.body : e.documentElement
        })
    };
    d.fn.scrollTo = function (n, j, b) {
        if (typeof j == 'object') {
            b = j;
            j = 0
        }
        if (typeof b == 'function') b = {onAfter: b};
        if (n == 'max') n = 9e9;
        b = d.extend({}, k.defaults, b);
        j = j || b.speed || b.duration;
        b.queue = b.queue && b.axis.length > 1;
        if (b.queue) j /= 2;
        b.offset = p(b.offset);
        b.over = p(b.over);
        return this._scrollable().each(function () {
            var q = this, r = d(q), f = n, s, g = {}, u = r.is('html,body');
            switch (typeof f) {
                case'number':
                case'string':
                    if (/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(f)) {
                        f = p(f);
                        break
                    }
                    f = d(f, this);
                case'object':
                    if (f.is || f.style) s = (f = d(f)).offset()
            }
            d.each(b.axis.split(''), function (a, i) {
                var e = i == 'x' ? 'Left' : 'Top', h = e.toLowerCase(), c = 'scroll' + e, l = q[c], m = k.max(q, i);
                if (s) {
                    g[c] = s[h] + (u ? 0 : l - r.offset()[h]);
                    if (b.margin) {
                        g[c] -= parseInt(f.css('margin' + e)) || 0;
                        g[c] -= parseInt(f.css('border' + e + 'Width')) || 0
                    }
                    g[c] += b.offset[h] || 0;
                    if (b.over[h]) g[c] += f[i == 'x' ? 'width' : 'height']() * b.over[h]
                } else {
                    var o = f[h];
                    g[c] = o.slice && o.slice(-1) == '%' ? parseFloat(o) / 100 * m : o
                }
                if (/^\d+$/.test(g[c])) g[c] = g[c] <= 0 ? 0 : Math.min(g[c], m);
                if (!a && b.queue) {
                    if (l != g[c]) t(b.onAfterFirst);
                    delete g[c]
                }
            });
            t(b.onAfter);

            function t(a) {
                r.animate(g, j, b.easing, a && function () {
                    a.call(this, n, b)
                })
            }
        }).end()
    };
    k.max = function (a, i) {
        var e = i == 'x' ? 'Width' : 'Height', h = 'scroll' + e;
        if (!d(a).is('html,body')) return a[h] - d(a)[e.toLowerCase()]();
        var c = 'client' + e, l = a.ownerDocument.documentElement, m = a.ownerDocument.body;
        return Math.max(l[h], m[h]) - Math.min(l[c], m[c])
    };

    function p(a) {
        return typeof a == 'object' ? a : {top: a, left: a}
    }
})(jQuery);


(function ($) {

    $(function () {

        /* toggle links */
        $('.toggle-link').click(function (e) {

            var target = $($(this).attr('href')).toggleClass('hidden');

            $.scrollTo(target);

            e.preventDefault();

        });

    });

})(this.jQuery);

//DEMO2

function uuid() {
    var s = [];
    var hexDigits = "0123456789abcdef";
    for (var i = 0; i < 36; i++) {
        s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1);
    }
    s[14] = "4";  // bits 12-15 of the time_hi_and_version field to 0010
    s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1);  // bits 6-7 of the clock_seq_hi_and_reserved to 01
    s[8] = s[13] = s[18] = s[23] = "-";

    var uuid = s.join("");
    return uuid;
}

function accountNUmber() {
    var s = (Math.floor(Math.random() * 10000) % 9 + 1).toString();
    for (var i = 0; i < 8; i++)
        s = s + (Math.floor(Math.random() * 10000) % 10).toString();
    return s;
}

function cardNUmber() {
    var s = (Math.floor(Math.random() * 10000) % 9 + 1).toString();
    for (var i = 0; i < 15; i++)
        s = s + (Math.floor(Math.random() * 10000) % 10).toString();
    return s;
}

function delRow(r) {
    var i = r.parentNode.parentNode.rowIndex;
    document.getElementById('account').deleteRow(i);
}

function del(r) {
    var i = r.parentNode.parentNode.rowIndex;
    document.getElementById('card').deleteRow(i);
}

var accRowLength = document.getElementById('account').rows.length;

function add() {
    var tr = document.createElement('tr');
    var td1 = document.createElement('td');
    var td2 = document.createElement('td');
    var td3 = document.createElement('td');
    var td4 = document.createElement('td');
    var td5 = document.createElement('td');
    var td6 = document.createElement('td');
    var td7 = document.createElement('td');


    td1.innerHTML = accountNUmber();
    td2.innerHTML = document.getElementById('fName').value;
    td3.innerHTML = document.getElementById('lName').value;
    td7.innerHTML = document.getElementById('type').value;
    td4.innerHTML = document.getElementById('balance').value;
    td5.innerHTML = document.getElementById('limit').value;
    td6.innerHTML = "<a href='#' class='delete-link' onclick='editAccount(this,accRowLength)'>edit</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' class='delete-link' data-toggle='modal' data-target='.bs-example-modal-sm'>view</a> &nbsp;&nbsp;&nbsp;<a href='#' class='delete-link' onclick='delRow(this)' >delete</a> ";


    var myTab = document.getElementById('account');
    myTab.appendChild(tr);
    tr.appendChild(td1);
    tr.appendChild(td2);
    tr.appendChild(td3);
    tr.appendChild(td7);
    tr.appendChild(td4);
    tr.appendChild(td5);
    tr.appendChild(td6);



}

function cancel() {
    document.getElementById('fName').value = "";
    document.getElementById('lName').value = "";
    document.getElementById('limit').value = "";
    document.getElementById('type').value = "";
    document.getElementById('balance').value = "";
    document.getElementById('date').value = "";
    document.getElementById('cvv').value = "";
}

function clear() {
    document.getElementById('accountNum').value = "";
    document.getElementById('cvv').value = "";
    document.getElementById('date').value = "";
}

// var tab = document.getElementById('card');
// var row = tab.rows.length;
function createCard() {

    var tr = document.createElement('tr');
    var td1 = document.createElement('td');
    var td2 = document.createElement('td');
    var td3 = document.createElement('td');
    var td4 = document.createElement('td');
    var td5 = document.createElement('td');



    td1.innerHTML = cardNUmber();
    td2.innerHTML = document.getElementById('accountNum').value;
    td5.innerHTML = document.getElementById('cvv').value;
    td3.innerHTML = document.getElementById('date').value;
    td4.innerHTML = "<a href='#' class='delete-link' onclick='editCard(this,5)'>edit</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' class='delete-link' onclick='del(this)' >delete</a> ";


    var myTab = document.getElementById('card');
    myTab.appendChild(tr);
    tr.appendChild(td1);
    tr.appendChild(td2);
    tr.appendChild(td5);
    tr.appendChild(td3);
    tr.appendChild(td4);
}

function editAccount(obj,x){
    var table = document.getElementById("account");
    for(var i=0;i<table.rows[x].cells.length-1;i++){
        var text = table.rows[x].cells[i].innerHTML;
        table.rows[x].cells[i].innerHTML = '<input class="input" name="input'+ x + '" type="text" value="" />';
        var input = document.getElementsByName("input" + x);
        input[i].value = text;
        input[0].focus();
        input[0].select();
    }
    obj.innerHTML = "confirm";
    obj.onclick = function onclick(event) {
        update_success(this,x)
    };
}
function update_success(obj,x){
    var arr = [];
    var table = document.getElementById("account");
    var input = document.getElementsByName("input" + x);
    for(var i=0;i<table.rows[x].cells.length-1;i++){
        var text = input[i].value;
        arr.push(text);
    }
    //把值赋值给表格，不能在取值的时候给，会打乱input的个数
    for(var j=0;j<arr.length;j++){
        table.rows[x].cells[j].innerHTML = arr[j];
    }
    //回到原来状态
    obj.innerHTML = "edit";
    obj.onclick = function onclick(event) {
        editAccount(this,x)
    };
    alert("edit is done");
}

function editCard(obj,y){
    var table = document.getElementById('card');
    for(var i=0;i<table.rows[y].cells.length-1;i++){
        var text = table.rows[y].cells[i].innerHTML;
        table.rows[y].cells[i].innerHTML = '<input class="input" name="input'+ y + '" type="text" value=""/>';
        var input = document.getElementsByName("input" + y);
        input[i].value = text;
        input[0].focus();
        input[0].select();
    }
    obj.innerHTML = "confirm";
    obj.onclick = function onclick(event) {
        updateSuccess(this,y)
    };
}
function updateSuccess(obj,y){
    var arr = [];
    var table = document.getElementById("card");
    var input = document.getElementsByName("input" + y);
    for(var i=0;i<table.rows[y].cells.length-1;i++){
        var text = input[i].value;
        arr.push(text);
    }
    //把值赋值给表格，不能在取值的时候给，会打乱input的个数
    for(var j=0;j<arr.length;j++){
        table.rows[y].cells[j].innerHTML = arr[j];
    }
    //回到原来状态
    obj.innerHTML = "edit";
    obj.onclick = function onclick(event) {
        editCard(this,y)
    };
    alert("edit is done");
}
