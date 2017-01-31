// var isBootstrapEvent = false;
// if (window.jQuery) {  
//   jQuery('*').on('hide.bs.dropdown', function( event ) {
//     isBootstrapEvent = true;
//   });
//   jQuery('*').on('hide.bs.collapse', function( event ) {
//     isBootstrapEvent = true;
//   });
//   jQuery('*').on('hide.bs.modal', function( event ) {
//     isBootstrapEvent = true;
//   });
// }

//   hide: function(element){ 
//     if(isBootstrapEevent){        // Added by RJCP eCommerce Consulting
//         isBootstrapEevent = false;  // Added by RJCP eCommerce Consulting
//         return;                     // Added by RJCP eCommerce Consulting
//       }
//       return Prototype.Selector.select(selector, element || document); // Added by RJCP eCommerce Consulting
//   }


  // Start New code
// RJCP Prototype.js Hack


(function() {
    var isBootstrapEvent = false;
    if (window.jQuery) {
        var all = jQuery('.dropdown');
        jQuery.each(['hide.bs.dropdown'], function(index, eventName) {
            all.on(eventName, function( event ) {
                isBootstrapEvent = true;
            });
        });
    }
    var originalHide = Element.hide;
    Element.addMethods({
        hide: function(element) {
            if(isBootstrapEvent) {
                isBootstrapEvent = false;
                return element;
            }
            return originalHide(element);
        }
    });
})();


// If push comes to shove

// (function() {
//     var isBootstrapEvent = false;
//     if (window.jQuery) {
//         var all = jQuery('*');
//         jQuery.each(['hide.bs.dropdown', 
//             'hide.bs.collapse', 
//             'hide.bs.modal', 
//             'hide.bs.tooltip',
//             'hide.bs.popover'], function(index, eventName) {
//             all.on(eventName, function( event ) {
//                 isBootstrapEvent = true;
//             });
//         });
//     }
//     var originalHide = Element.hide;
//     Element.addMethods({
//         hide: function(element) {
//             if(isBootstrapEvent) {
//                 isBootstrapEvent = false;
//                 return element;
//             }
//             return originalHide(element);
//         }
//     });
// })();
