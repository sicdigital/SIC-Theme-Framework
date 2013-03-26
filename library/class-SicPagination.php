<?php

class sicPagination{
		
	public $range;
	public $pages;
	private $count;
	public $content;
	
	
	function __construct($range = '3', $pages = ''){

		$this
		->setRange($range)
		->setPages($pages)
		->setCount()
		->setFirstPage()
		->setMaxPages();
		
	}
	
	private function setRange($range){
		
		$this->range = $range;
			
		return $this;
	}
	
	private function setPages($pages){
		
		$this->pages = $pages;
			
		return $this;
	}
	
	private function setCount(){
		$this->count = ($this->range * 2)+1;  
	    
		return $this;
	}
	
	
	private function setFirstPage(){
		/*Checks to see what page we're on, $paged is the wordpress global that tells us, it will be empty if we're on page 1*/
        global $paged;

        if(empty($paged)) {

		$paged = 1;
		
	}
	
		return $this;
	}
	
	
	function setMaxPages(){
		/*Checks to see how many pages we have, 
		if the user didn't set the number of pages, use the method max_num_pages to set the number of pages we have*/    
		 
		 if($this->pages == "") 
		     {
		         global $wp_query;
		         $this->pages = $wp_query->max_num_pages;
		         if(!$this->pages)
		         {
		             $this->pages = 1;
		         }
		     }   
			 return $this;
	}
	
	
	/*Everthing starts to ouput here, could .= with $content and return in output eventually*/
	
	function firstArrow(){
		global $paged;

		
		// This firs set of if's ads the arrows to the pagination
       // if there are more than 2 pages and the page is outside the range, before show the <<
		   
     if($paged > 2 && $paged > $this->range+1 && $this->count < $this->pages) 
		 {
			 $this->content .= "<li><a href='".get_pagenum_link($page)."'>&laquo;</a></li>";
		 }
				 
		
	}
	
	function listNums(){
		global $paged;
		
		 if($paged > 1 && $this->count < $this->pages) 
			 {
				 $this->content .=  "<li><a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a></li>";
			 }
				 
			/*Starts to write the links, sets a counter to 1,*/	
			for ($i=1; $i <= $this->pages; $i++)
			{
			/*This if statement checks if everything is within the range we want to display, if it is then show it*/     
			if (1 != $this->pages &&( !($i >= $paged+$this->range+1 || $i <= $paged-$this->range-1) || $this->pages <= $this->count ))
			{
			/*if the page we're on is the number being displayed, add this section */	
			if ($paged == $i){
				
			 $this->content .= 	"<li><a id=currentPage>".$i."</a></li>";
			}
			else{
			/*if the page being shown isnt the page we're on */
			 $this->content .= "<li><a href='".get_pagenum_link($i)."'>".$i."</a></li>";
			 }
            }
         }
			
	}
	
	function lastArrow(){
		global $paged;
		/*This checks if the number we're at is outside the range to show, if it is, then show the >> */
         if ($paged < $this->pages && $this->count < $this->pages) 
			 {
				  $this->content .=  "<li><a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a></li>"; 
			  }
	}
	
	function output(){
		global $paged;
		
	    if( 1 != $this->pages)
	    
		 {
	          $this->content .=  "<ul id='pagination_" . get_the_id() . "' class='pagination'>";
		
		
		   $this->firstArrow();
		   $this->listNums();
	       $this->lastArrow();
		
			 if ($paged < $this->pages-1 &&  $paged+$this->range-1 < $this->pages && $this->count < $this->pages) 
				 
			 {
				  $this->content .=  "<li><a href='".get_pagenum_link($this->pages)."'>&raquo;</a></li>";
		
				  $this->content .=  "</ul>\n";
		     }
		
			 return $this->content;
		
	}
}
	
	
	
}//class
