jQuery(document).ready(function($) {

	selector = $( document ).find( '.tb-field-container:visible' );

	$( selector ).each(function(){		
		var el = $( this );
		var parent = el;		

        el.find( '.tb-sorter' ).each(function(){
        	var id = $( this ).attr( 'id' );        	

        	el.find( '#' + id ).find( 'ul' ).sortable({
        		items: 'li',
        		placeholder: "placeholder",
        		connectWith: '.sortlist_' + id,
        		opacity: 0.8,
        		scroll: false,
        		out: function( event, ui ) {
                    if ( !ui.helper ) return;
                    if ( ui.offset.top > 0 ) {
                        scroll = 'down';
                    } else {
                        scroll = 'up';
                    }                    
                },
                over: function( event, ui ) {
                    scroll = '';
                },                
                deactivate: function( event, ui ) {
                    scroll = '';
                },

                update: function( event, ui ) {                    
                    $( this ).find( '.position' ).each(function() {
                        var listID = $( this ).parent().attr( 'data-id' );
                        var parentID = $( this ).parent().parent().attr( 'data-group-id' );                            
                        var optionID = $( this ).parent().parent().parent().attr( 'id' );                        

                        $( this ).prop(
                            "name",
                            optionID + '[' + parentID + '][' + listID + ']'
                        );
                    });
                }                
        	});
			$( ".tb-sorter" ).disableSelection();
        });
	});

});