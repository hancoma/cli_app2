(function(){
   var mysiteActions = (function(){
       function initialize() {
           domainSearch();
           guideAddSite();
           dnsStopBubble();
           setTooltip();
       }

       function domainSearch() {
           var searchInput = document.getElementById('input-search-text');

           searchInput !== null && (function(){
               var domainGroup = document.getElementsByClassName('domain-group'),
                   groupLength = domainGroup.length;

               searchInput.addEventListener('keyup', function(){
                   var keyword = this.value,
                       domainName = '';

                   for(var i = 0; i < groupLength; i++) {
                       domainName = domainGroup[i].getAttribute('data-domain-name');

                       domainName.indexOf(keyword) > -1 && (function(){
                           domainGroup[i].style.display = 'block';
                           return true;
                       }()) || (function(){
                           domainGroup[i].style.display = 'none';
                           return false;
                       }());
                   }
               });
           }());
       }

       function guideAddSite() {
           var addWebsitePanel = document.getElementById('addSite');

           addWebsitePanel !== null && (function(){
               addWebsitePanel.addEventListener('click', function(){
                   location.href = "/addsite/index";
                   return true;
               });
           }());
       }

       function dnsStopBubble() {
           var button = document.getElementsByClassName('dns_button'),
               buttonLength = button.length;

           for(var i = 0; i < buttonLength; i++) {
               button[i].addEventListener('click', function(e){
                   e.stopPropagation();
               });
           }
       }

       function setTooltip(){
           var statusText = document.getElementsByClassName('domain-status-date');

           for(var i = 0, length = statusText.length; i < length; i++) {
               makeTooltip($(statusText[i]), "bottom", "hide", statusText[i].getAttribute('data-date'));
           }
       }

      return {
          init: initialize
      }
   }());

   return mysiteActions.init();
}());