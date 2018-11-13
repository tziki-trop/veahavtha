<?php
namespace Donations\Email;
use WP_Query;
 class TZT_Email {
 	/**
 	 * Array or String of emails where to send
 	 * @var mixed
 	 */
     public function __construct($emails, $dynamicData, $template , $local = "he_IL
"){
         //en_US
        $this->local = $local;
		$this->emails = $emails;
		$this->pid = $this->set_mail_type($template);
         if($this->pid != false){
        $this->title = get_the_title($this->pid);
		$this->dynamicData = $dynamicData;
		$this->prepareTemplate();
		$this->send();
         }
	}
    private function set_mail_type($template){
                $args=array(
				'post_type' => 'mail_temp',
                'posts_per_page' => 1,
                'meta_query' => array(
                        'relation' => 'AND',
                        array(
                           'key' => 'mail_type',
                           'value' => $template,
                           'compare' => '=',
                        ),
                        array(
                           'key' => 'mail_lang',
                           'value' => $this->local,
                           'compare' => '=',
                        ),
                    
                )
		 );
		  $my_query =  new WP_Query($args);
                  if( $my_query->have_posts() ){
                      while ($my_query->have_posts()) : $my_query->the_post(); 
                      return get_the_ID();
                      endwhile;
                  }
        return false;
    }
    private function prepareTemplate(){
		$template = $this->getTemplate();
        $title = $this->title;
		
		foreach ($this->dynamicData as $placeholder => $value) {
			$securePlaceholder = strtoupper( $placeholder );
			$preparedPlaceholder = "{{" . $securePlaceholder . "}}";
			$template = str_replace( $preparedPlaceholder, $value, $template );
            $title = str_replace( $preparedPlaceholder, $value, $title );
		
		}
      //  $template = var_export($this->dynamicData);
		$this->outputTemplate = $template;
        $this->title = $title;
	}
	private function getTemplate(){
        $content_post = get_post($this->pid);
        $content = $content_post->post_content;
        return apply_filters('the_content', $content);
	}
	private function send(){
        $headers = array('Content-Type: text/html; charset=UTF-8;', 'From: '.get_bloginfo( "name").' <veahavtha@veahavtha.com>');
		wp_mail( $this->emails, $this->title, $this->outputTemplate , $headers );
	}
     protected $local;
    protected $pid;
 	protected $emails;
 	/**
 	 * Subject of email
 	 * @var string
 	 */
 	protected $title;
 	/**
 	 * Associative Array of dynamic data
 	 * @var array
 	 */
 	protected $dynamicData = array();
 	/**
 	 * Template used to send data
 	 * @var string
 	 */
 	protected $template;
 	/**
 	 * Prepared template with real data instead of placeholders
 	 * @var string
 	 */
 	protected $outputTemplate;
 	
 	// ...
 	
 }
