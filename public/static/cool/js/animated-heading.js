(function (a) {
    "use strict";
 

        a(document).ready(function () {
           
            a(".tx-item--text").each(function () {
                var b = 0,
                    d = a(this).children().length,
                    c = d - 1;
                a(this)
                    .find("> .tx-text--slide > .wow")
                    .each(function (e, f) {
                        a(this).css("transition-delay", b + "ms"), c === e ? ((b = 0), (c = c + d)) : (b = b + 80);
                    });
            });
            
        });

})(jQuery);
