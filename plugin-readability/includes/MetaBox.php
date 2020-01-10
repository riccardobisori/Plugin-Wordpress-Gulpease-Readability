<?php
	
    
// Admin functions

/* From Wordpress Documentation at "wp_ajax_(action): 
"This hook allows you to handle your custom AJAX endpoints. The wp_ajax_ hooks follows the format "wp_ajax_$youraction", where $youraction is the 'action' field submitted to admin-ajax.php."

Nel nostro caso "$youraction" è "realtime", specificata nella variabile "message" nello script. 
*/

add_action("wp_ajax_realtime", "readabilityMetaBox");

// $post contains the data from the current post on wordpress, $post->post_content is the text (only paragraphs) in the post
function readabilityMetaBox() {
	global $post;
    
    /* Senza questo (doppio) if si azzera subito il metabox dopo la prima ripetizione.
     Infatti questo è l'aggiornamento di "$post->post content" che viene effettuato mediante AJAX. 
    
     Il primo if è utile all'apertura dell'articolo/pagina, quando ancora $_POST['post_content'] non esiste.
     'post_content' viene aggiornato real-time dallo script, attraverso messaggi HTTP di tipo post.
    */
    if(isset($_POST['post_content'])){
        if ($_POST['post_content'] != ''){
            
		    $post->post_content = $_POST['post_content'];
	   }
    }

	if ($post->post_content != '') {
		$readability = new Readability();
		$readability->setText($post->post_content);
		$template =
			'<table width="100%%"><tr>'
			.'<td align="left" width="25%%"><b>Frasi</b><br>%d</td> '
            .'<td align="left" width="25%%"><b>Parole</b><br>%d</td> '
            .'<td align="left" width="25%%"><b>Lettere</b><br>%d</td> '
			.'<td align="left" width="25%%" title="Indice di leggibilità da 0 a 100"><b>Indice Gulpease</b><br>%2.1f</td> '
			.'</tr></table>';
        ?>

        		
        <div class = 'metaBoxStyleAnalysis'><?php
                      
        echo sprintf($template, 
                     $readability->getSentences(), 
                     $readability->getWords(), 
                     $readability->getLetters(), 
                     $readability->getGulpeaseIndex());
        
        echo $readability->getSuggestions();
        
        ?>
        </div>

        <br>

        <div class = 'metaBoxStyleExplanation'>
            <p>Definito nel 1988 nell'ambito delle ricerche del <em>GULP</em> (Gruppo Universitario Linguistico Pedagogico)
                presso l'università di Roma "La Sapienza", l'<a href="https://it.wikipedia.org/wiki/Indice_Gulpease"><strong>indice Gulpease</strong></a> è un indice di leggibilità di un testo tarato sulla
        lingua italiana. <br> Esso considera due variabili linguistiche: la lunghezza della parola e la lunghezza della frase
                rispetto al numero di lettere. <br><br> La formula per calcolarlo è: </p>
            <div class = 'equation' align = center>
            89 + <div class = 'fraction'>
                <span class="fup">300 &#8729; &lpar;<i>numero delle frasi</i>&rpar; - 10 &#8729; &lpar;<i>numero delle lettere</i>&rpar;</span>
                <span class="bar">/</span>
                <span class="fdn"><i>numero delle parole</i></span>
                </div>
            </div>
        
            
            
        </div>

        <?php
	} 
}


?>
