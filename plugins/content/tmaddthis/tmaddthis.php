<?php 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class plgContentTmAddThis extends JPlugin{

  public function onContentAfterDisplay($context, &$article, &$params)
  {
    $app = JFactory::getApplication();
    $view = $app->input->getCmd('view', '');
    $layout   = $app->input->getCmd('layout', '');
    $scope =  $app->scope;

    if($view == 'article' && $scope == 'com_content' && $layout !== 'edit')
    {
      $html = '<div class="addthis_toolbox addthis_default_style ">';

      // Google Plus
      if($this->params->get('google_plus')){
        $html .= '<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>';
      }
      // Facebook Like
      if($this->params->get('facebook_like')){
        $html .= '<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>';
      }
      // Tweet
      if($this->params->get('tweet')){
        $html .= '<a class="addthis_button_tweet"></a>';
      }
      // Pinterest
      if($this->params->get('pinterest')){
        $html .= '<a class="addthis_button_pinterest_pinit" pi:pinit:layout="horizontal"></a>';
      }
      // Linked in Share
      if($this->params->get('linkedin')){
        $html .= '<a class="addthis_button_linkedin_counter"></a>';
      }
      // StumbleUpon
      if($this->params->get('stumbleupon')){
        $html .= '<a class="addthis_button_stumbleupon_badge"></a>';
      }
      // Foursquare
      if($this->params->get('foursquare')){
        $html .= '<a class="addthis_button_foursquare"></a> ';
      }
      // Addthis Share
      if($this->params->get('addthis_share')){
        $html .= '<a class="addthis_counter addthis_pill_style"></a>';
      }    

      $html .= '</div>
      <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=';
	  $html .= $this->params->get('addthis_id');
	  $html .= '"></script>';

      return $html;
    }
  return false;
  }  
}
