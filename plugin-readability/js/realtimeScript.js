/* In Wordpress abbiamo due tipi di editor di testo: 
- "text", ovvero quello con i tag html visibili.
- "visual", quello usato più frequentemente. Questo è un editor TinyMCE, che quindi avrà delle funzioni specifiche.
*/

// La text mode non può essere aggiornata in tempo reale nell'editor classico, quindi andrà ogni voltà riaggiornata la pagina
// o aggiornato l'articolo/pagina

jQuery(document).ready(function ($) {
    console.log('script is here');
    // Crea il 'keyup_event' tinymce plugin per la visual mode per gestire il keyup event
    tinymce.PluginManager.add('keyup_event', function(editor, url) {

        // Che è fatto così: quando nell'editor avviene l'evento "keyup", si attiva la funzione "updateMetabox()"
        editor.on('keyup', function(e) {
            console.log('Keyup event in Classic Visual Editor')
            updateMetabox();
        });
    });
    
    /*
    //Gestisce il keyup event nella text mode
    $('#content').on('keyup', function(e) {
        console.log('Keyup event in text editor')
        updateMetabox();
    });
    
    */
    
    $('#editor').on('keyup', function(e) {
        console.log('Keyup event in Gutenberg')
        updateMetabox();
    })
                                        
    

    //Funzione che permette al metabox di aggiornarsi in tempo reale
    function updateMetabox(content) {
        
        if ( document.body.classList.contains( 'block-editor-page' ) ) {
            if (tinymce.activeEditor != null && tinymce.activeEditor.isHidden() == false){
            
            console.log('We\'re in Classic Editor into the Gutenberg Editor!)');
            var data = $(tinymce.activeEditor.getContent()).text();

            }
            
            else if($('.editor-post-text-editor').text() != ''){
                
                console.log('We\'re in Gutenberg Text Editor!');   
                var data = $('.editor-post-text-editor').text();
            } else{
                
                console.log('We\'re in Gutenberg Visual Editor!');   
                var data = $('.editor-rich-text.block-editor-rich-text > p').text();
                
            }
        
        }
    
        // test per vedere se l'editor visuale è disponibile.
        else if (tinymce.activeEditor != null && tinymce.activeEditor.isHidden() == false){
            
            console.log('We\'re in Classic Visual mode!)');
            var data = $(tinymce.activeEditor.getContent()).text(); // visual mode

        }
        else{          
             //Siamo entrati nell'if quindi siamo in text mode
            console.log('We\'re in Classic Text mode! Update to see the Readability Analysis');
        }
        
		var message = {
			'action': 'realtime',
			'post_content': data
		};
        
		console.log(message); // potrebbe non servire
        
        /*Richiesta HTTP (al server) di tipo post: 
          al server viene inviato un message con 'post_content' e viene ricevuto lo stesso oggetto ma agggiornato alla situazione
          "realtime"        
        
        - "ajax_objext.ajax_url" è l'url a cui è inviata la richiesta (il server)
        - "message" viene inviato al server insieme alla richiesta. E' di tipo "PlainObject".
        - "function(response)" è la funzione di callback eseguita se la richiesta ha avuto successo. Il parametro "response"
          è dello stesso tipo di "message" al campo precedente (è il message aggiornato dal server, grazie all'action 'realtime' specificata). 
        */
		jQuery.post(ajax_object.ajax_url, message, function(response) {
            
            //Viene sostituito il campo html identificato dall'id "readability" e dalla classe "inside" con il contenuto di "response"
			jQuery('#readability > .inside').html(response);	
		});
    }
});