<?php 

class PushData {

	private $id;
    //notification title
    private $title;

    //notification message 
    private $message;
    private $status;
    
    private $m_date;

    //notification image url 
    private $image;
    private $audio;
    private $video;

    //initializing values in this constructor
    function __construct($id,$title, $message,$date,$status, $image,$audio,$video) {
      	$this->id = $id;
         $this->title = $title;
         $this->status = $status;
         $this->message = $message;
  	$this->s_date = $date;
         $this->image = $image; 
         $this->audio = $audio; 
         $this->video = $video; 
     }
    
    //getting the push notification
    public function getPushData() {
        $res = array();
        $res['data']['id'] = $this->id;
        $res['data']['title'] = $this->title;
        $res['data']['message'] = $this->message;
        $res['data']['date'] = $this->s_date;
        $res['data']['status'] = $this->status;
        $res['data']['image'] = $this->image;
        $res['data']['audio'] = $this->audio;
        $res['data']['video'] = $this->video;
        return $res;
    }
 
}