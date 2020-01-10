<?php
	
class Readability {
    
    // Set variables
	var $text;
	var $words;
	var $sentences;
    var $gulpeaseIndex;

	/* Initializing the text to be analyzed. The following functions are used to minimize the memory needed for the text
    strip_tags() removes HTML and PHP tags from the text
    trim() removes the spaces at the text's beginning and ending
    strtolower() makes the text lowercase
    */
	function setText($text) {
		$this->text = strtolower(trim(strip_tags($text)));
		$this->words = -1; 
		$this->sentences = -1;
        $this->gulpeaseIndex = -1;
	}
	
	// Useful for tests
	function getText() {
        
		return($this->text);
	}
    
    //Function that counts the letters (only a-z and 0-9 symbols) in the text
    
	function getLetters() {
        $onlyText = $this->text; //sort of difensive copie
        $onlyText = preg_replace('/[\W\s]/', '', $onlyText);        
        
        
	    return strlen($onlyText);
	}
    
    //Function that counts the words in the text
	
	function getWords() {
		if ($this->text == '') 
            return(0); 
        
        
		if ($this->words != -1)  
            return($this->words); 
        
        //str_word_count() counts the words in a text
        
		$count = str_word_count($this->text);
        
		if ($count <= 0) 
			$count = 1; // This prevents division by zero in the Gulpease calculation
		
		$this->words = $count;
		return($count);
	}
    
	
	function getSentences() {
		if ($this->text == '')  
            return(0); 
        
        
		if ($this->sentences != -1)  
            return($this->sentences); 
        
        //preg_split() creates an array. Each element is a sentence of the text  
        $count = count(preg_split('/(!|\.|\?|;|:|\.\.\.)/', $this->text, -1, PREG_SPLIT_NO_EMPTY));
        
		if ($count <= 0) 
			$count = 1;
		
        
		$this->sentences = $count;
		return($count);
	}
	
    // The Gulpease index (higher is easier to undertand the text)
    
    function getGulpeaseIndex() {
		if ($this->text == '')  
            return(0); 
        
		$LP = $this->getLetters();
        $nF = $this->getSentences();
        $nP = $this->getWords();
        
		$gulpease = (89 - (10 * $LP / $nP ) + (300 * $nF / $nP));
        
        // return with parameters check
        
        if ($gulpease > 100){
            $this->gulpeaseIndex = 100;
            return(100);
        }
            
        elseif ($gulpease < 0){
            $this->gulpeaseIndex = 0;
            return(0);
        }
        else{
            $this->gulpeaseIndex = $gulpease;
            return $gulpease;
        }
            
	}
    
    function getSuggestions(){
        $easy = '<hr />Livello di leggibilità: <span style="color:red;"><strong>FACILE</strong></span><br>
        <i>I testi facili sono facilmente comprensibili anche da persone con un basso livello di istruzione.</i>';
        
        $average = '<hr />Livello di leggibilità: <span style="color:red;"><strong>MEDIO</strong></span><br>
        <i>Il testo rientra nella norma. Un buon testo dovrebbe appartenere a questa categoria.</i>';
        
        $hard = '<hr />Livello di leggibilità: <span style="color:red;"><strong>DIFFICILE</strong></span><br>
        <i>Questo testo contiene frasi e/o parole troppo lunghe e/o poco comuni.<br>
        Si consiglia di controllare la scelta delle parole e la struttura delle frasi per rendere il testo più leggibile.</i>';
            
        if($this->gulpeaseIndex == -1)
            return('');
        if($this->gulpeaseIndex >= 80)
            return($easy);
        elseif($this->gulpeaseIndex <= 40) 
            return($hard);
        else
            return($average);
    }
    

    
	
}

?>