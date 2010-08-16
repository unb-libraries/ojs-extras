(function() {

		var Widget = function(id) {
			// Create block 
			this.block = document.createElement("div");
			this.block.id = id;
			this.block.className = 'block';
			
			this.block.innerHTML = 
				"<span class='blockTitle'>Extract Article Ids</span>" +
				"<ul>" +
					"<li><a href='#'>List article ids</a></li>" +
					"<li><a href='#'>Hide this block</a></li>" +
				"</ul>";

 			var that = this;

			// First action: post to extractor function
  			this.block.lastChild.firstChild.lastChild.onclick = function() { 
				    var form = document.createElement("form");
				    form.setAttribute('method', 'post');
				    form.setAttribute('action', 'http://etcdev.hil.unb.ca/ojs2.3-devel/utils/php/extractor.php');
					form.setAttribute('target', '_blank');
				
				    var hiddenField = document.createElement('input');
			        hiddenField.setAttribute('type', 'hidden');
			        hiddenField.setAttribute('name', 'source');
			        hiddenField.setAttribute('value', document.getElementById('submissions').innerHTML);
			
					form.appendChild(hiddenField);
					
				    document.body.appendChild(form);
				    form.submit();
			}

			// Next action: make it go away
  			this.block.lastChild.lastChild.lastChild.onclick = function() { 
				that.block.style.display = "none"; 
			};
		};
 
		function main() {
  			var widgetId = "bookmarkletWidget";
  			var widget = new Widget(widgetId);
			document.getElementById('rightSidebar').insertBefore(widget.block, document.getElementById('sidebarUser'));
		}
 
		main();
})();





