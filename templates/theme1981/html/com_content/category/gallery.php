<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$app = JFactory::getApplication('site');
$template = $app->getTemplate(true);
include_once(JPATH_BASE.'/templates/'. $template->template .'/includes/functions.php');
$document = JFactory::getDocument();
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
JHtml::_('behavior.caption');
function special_chars_replace($string){
  $result = preg_replace("/[&%\$#@'\*:\/\"\[\]\{\};\(\)\|\\\=!\^\?`~.,\+-]/", "", $string);
  return $result;
}
if ($this->params->get('user_hover')){
  $hover_active = 'hover_true';
  switch ($this->params->get('hover_style')) {
    case 'style1':
      $item_hover_style = "style1";
      break;
        case 'style1':
      $item_hover_style = "style1";
      break;
        case 'style2':
      $item_hover_style = "style2";
      break;
        case 'style3':
      $item_hover_style = "style3";
      break;
        case 'style4':
      $item_hover_style = "style4";
      break;
        case 'style5':
      $item_hover_style = "style5";
      break;
        case 'style6':
      $item_hover_style = "style6";
      break;
        case 'style7':
      $item_hover_style = "style7";
      break;
    
    default:
      $item_hover_style = "style1";
      break;
  }
  $document->addStyleSheet(JURI::base().'templates/'. $template->template .'/css/hover_styles/'.$item_hover_style.'.css');
} else {
  $item_hover_style = "";
  $hover_active = "hover_false";
}
  //$document->addScript(JURI::base().'templates/'.$template->template.'/js/isotope.pkgd.min.js');
  $document->addScript(JURI::base().'templates/'.$template->template.'/js/jquery.mixitup.min.js');
?>
<div class="note"></div>
<section class="page-gallery page-gallery__<?php echo $this->pageclass_sfx;?>">
  <?php if ($this->params->get('show_page_heading', 1)) : ?>
  <header class="page_header">
    <?php echo wrap_with_tag(wrap_with_span($this->escape($this->params->get('page_heading'))), $template->params->get('categoryPageHeading')); ?>
  </header>
  <?php endif;
  if ($this->params->get('show_category_title', 1) or $this->params->get('page_subheading')) : ?>
  <header class="category_title">
    <h2> <?php echo $this->escape($this->params->get('page_subheading'));
      if ($this->params->get('show_category_title')) : ?>
      <span class="subheading-category"><?php echo $this->category->title;?></span>
      <?php endif; ?>
    </h2>
  </header>
  <?php endif;
  if ($this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
  <div class="category_desc">
    <?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
    <img src="<?php echo $this->category->getParams()->get('image'); ?>"/>
    <?php endif;
    if ($this->params->get('show_description') && $this->category->description) :
    echo JHtml::_('content.prepare', $this->category->description, '', 'com_content.category');
    endif; ?>
    <div class="clr"></div>
  </div>
  <?php endif;
  $galleryCategories = array();
  foreach ($this->intro_items as $key => &$item){
      $categoryTitle = $item->category_title;
      $galleryCategories[] = $categoryTitle;
  };
  $galleryCategories = array_unique($galleryCategories);
  if((!empty($this->lead_items) || (!empty($this->intro_items)))): ?>
  <!-- Filter -->
  <?php if($this->params->get('show_layout_mode')): ?>
  <div class="layout-mode">
    <b><?php echo JText::_('TPL_COM_CONTENT_LAYOUT_MODE'); ?></b>
    <ul id="grid-list" class="btn-group">
      <li><a class="btn btn-info toGrid active"><i class="fa fa-th-large"></i></a></li>
      <li><a class="btn btn-info toList"><i class="fa fa-bars"></i></a></li>
    </ul>
  </div>
  <?php endif;
  if($this->params->get('show_filter')): ?>
  <div class="filters">
    <b><?php echo JText::_('TPL_COM_CONTENT_GALLERY_FILTER_BY_CATEGORY'); ?></b>
    <ul id="filters" class="btn-group">
      <li><a class="filter active btn btn-info" data-filter="all"><?php echo JText::_('TPL_COM_CONTENT_GALLERY_FILTER_SHOW_ALL'); ?></a></li>
      <?php foreach ($galleryCategories as $key => $value) : ?>
      <li><a class="filter btn btn-info" data-filter="<?php echo special_chars_replace(mb_strtolower(str_replace(" ","",$value))); ?>"><?php echo $value; ?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php endif;
  if($this->params->get('show_sort')): ?>
  <div class="sorting">
    <b><?php echo JText::_('TPL_COM_CONTENT_GALLERY_SORT'); ?></b>
    <ul id="sort" class="btn-group">
      <li><a class="sort desc btn btn-info" data-sort="data-name" data-order="desc"><?php echo JText::_('TPL_COM_CONTENT_GALLERY_FILTER_NAME'); ?> <i class="fa fa-arrow-circle-down"></i></a></li>
      <li><a class="sort asc btn btn-info" data-sort="data-name" data-order="asc"><?php echo JText::_('TPL_COM_CONTENT_GALLERY_FILTER_NAME'); ?> <i class="fa fa-arrow-circle-up"></i></a></li>
      <li><a class="sort desc btn btn-info" data-sort="data-date" data-order="desc"><?php echo JText::_('TPL_COM_CONTENT_GALLERY_FILTER_DATE'); ?> <i class="fa fa-arrow-circle-down"></i></a></li>
      <li><a class="sort asc btn btn-info" data-sort="data-date" data-order="asc"><?php echo JText::_('TPL_COM_CONTENT_GALLERY_FILTER_DATE'); ?> <i class="fa fa-arrow-circle-up"></i></a></li>
      <li><a class="sort asc btn btn-info" data-sort="data-popularity" data-order="asc"><?php echo JText::_('TPL_COM_CONTENT_GALLERY_FILTER_POPULARITY'); ?> <i class="fa fa-arrow-circle-down"></i></a></li>
      <li><a class="sort desc btn btn-info" data-sort="data-popularity" data-order="desc"><?php echo JText::_('TPL_COM_CONTENT_GALLERY_FILTER_POPULARITY'); ?> <i class="fa fa-arrow-circle-up"></i></a></li>
      <!-- <li><a class="sort btn btn-info" data-sort="random"><?php echo JText::_('TPL_COM_CONTENT_GALLERY_FILTER_RANDOM'); ?></a></li> -->
    </ul>
  </div>
  <?php endif;
  endif;
  $leadingcount = 0;
  if (!empty($this->lead_items)) : ?>
  <div class="items-leading">
    <?php foreach ($this->lead_items as &$item) : ?>
    <article class="leading-<?php echo $leadingcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>">
      <?php
      $this->item = &$item;
      echo $this->loadTemplate('item');
      ?>
    </article>
    <div class="clearfix"></div>
    <?php $leadingcount++;
    endforeach; ?>
  </div><!-- end items-leading -->
  <div class="clearfix"></div>
  <?php endif;


    $introcount = (count($this->intro_items));
    $counter = 0;

  if (!empty($this->intro_items)) :
  $row = $counter / $this->columns; ?>
  <ul id="isotopeContainer" class="gallery items-row cols-<?php echo (int) $this->columns;?> <?php echo $hover_active; ?>">
    <?php foreach ($this->intro_items as $key => &$item) :
    $rowcount = (((int) $key) % (int) $this->columns) + 1;

    if ($rowcount == 1) :  
    endif;
  
    $this->item = &$item; ?>
    <li class="gallery-item mix mix_all gallery-grid <?php echo $item_hover_style; ?> <?php echo special_chars_replace(mb_strtolower(str_replace(" ","",$item->category_title))); ?>" data-date="<?php echo JHtml::_('date', $this->item->publish_up, 'YmdHis'); ?>" data-name="<?php echo $this->item->title; ?>" data-popularity="<?php echo $this->item->hits; ?>">
      <?php
      $this->item = &$item;
      echo $this->loadTemplate('item');
      $counter++; ?>
      <div class="clearfix"></div>
    </li>
    <?php if (($rowcount == $this->columns) or ($counter == $introcount)):    
      
    endif;
  
    endforeach; ?>
    <li class="gap"></li>
    <li class="gap"></li>
    <li class="gap"></li>
  </ul>
  <?php endif;
  
  if (!empty($this->link_items)) : ?>
  <div class="items-more">
    <?php echo $this->loadTemplate('links'); ?>
  </div>
  <?php endif;


  if (!empty($this->children[$this->category->id])&& $this->maxLevel != 0) : ?>
  <div class="category_children">
    <div class="row">
      <!-- <h3> <?php /*echo JTEXT::_('JGLOBAL_SUBCATEGORIES');*/ ?> </h3> -->
      <?php echo $this->loadTemplate('children'); ?>
    </div>
  </div>
  <?php endif;

  if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>  
  <div class="pagination">
    <?php  if ($this->params->def('show_pagination_results', 1)) : ?>
    <p class="counter"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
    <?php endif;
    echo $this->pagination->getPagesLinks(); ?> </div>
  <?php  endif; ?>
</section>

<script>
  jQuery(document).ready(function($) {
    $(window).load(function(){

      var $cols = <?php echo $this->columns; ?>;
      var $container = $('#isotopeContainer');

      $item = $('.gallery-item')

      $container.mixitup({
        targetSelector: '.gallery-item',
        filterSelector: '.filter',
        sortSelector: '.sort',
        buttonEvent: 'click',
        effects: [<?php echo  $this->params->get('sort_effects');?>],
        listEffects: null,
        easing: 'smooth',
        layoutMode: 'grid',
        targetDisplayGrid: 'inline-block',
        targetDisplayList: 'block',
        gridClass: 'grid',
        listClass: 'list',
        transitionSpeed: 600,
        showOnLoad: 'all',
        sortOnLoad: false,
        multiFilter: false,
        filterLogic: 'or',
        resizeContainer: true,
        minHeight: 0,
        failClass: 'fail',
        perspectiveDistance: '3000',
        perspectiveOrigin: '50% 50%',
        animateGridList: true,
        onMixLoad: function(){
          magnificGridInit();
        },
        onMixStart: function(config){
          if(config.layoutMode == 'list'){
            config.effects = ['fade','scale']
          }
          else{
            config.effects = [<?php echo  $this->params->get('sort_effects');?>]
            $container.find('.mix').removeClass('gallery-list').addClass('gallery-grid');
            magnificGridInit();
          }
        },
        onMixEnd: function(config){
          if(config.layoutMode == 'list'){
            $container.find('.mix').addClass('gallery-list').removeClass('gallery-grid');
            magnificListInit();
          }
        }
      });

      $('.toGrid').click(function(){
        $('.layout-mode a').removeClass('active');
        $(this).addClass('active');
        $container.mixitup('toGrid')
      })

      $('.toList').click(function(){
        $('.layout-mode a').removeClass('active');
        $(this).addClass('active');
        $container.mixitup('toList');

      })

      /*$container.isotope({
        layoutMode: 'masonry',
          containerClass : 'isotope',
          containerStyle: {
            position: 'relative',
            overflow: 'hidden'
          },
          getSortData: {
            name: '.item_title',
            date: '[data-date]'
          },
          itemSelector : '.gallery-item',
          resizable: true,
          resizesContainer : true,
          transformsEnabled: !$.browser.opera // disable transforms in Opera
      },magnificInit());

      if($container.width() <= '767'){
        $item.outerWidth($container.width());
        $item.addClass('straightDown');
        $container.isotope({
          layoutMode: 'vertical'
        });
      } else {
        $item.removeClass('straightDown');
        $container.isotope({
          layoutMode: 'masonry'
        });
      }

      $(window).resize(function(){
        $item.outerWidth(Math.floor($container.width() / $cols));
        if($container.width() <= '767'){
          $item.outerWidth($container.width());
          $item.addClass('straightDown');
          $container.isotope({
            layoutMode: 'vertical'
          });
        } else {
          $item.outerWidth(Math.floor($container.width() / $cols));
          $item.removeClass('straightDown');
          $container.isotope({
            layoutMode: 'masonry'
          });
        }
      });

      <?php if($this->params->get('show_filter')): ?>

      // filter items when filter link is clicked
      $('#filters a').click(function(){
        var sortValue = $(this).attr('data-sort-value');
        var filterValue = $(this).attr('data-filter-value');
        $container.isotope({
          sortBy: sortValue,
          filter: filterValue
        });
        return false;
      });

      <?php endif; ?>
      */

      function magnificGridInit(){
        jQuery('ul#isotopeContainer.hover_true').magnificPopup({
          delegate: 'a.galleryZoom:visible',
          type: 'image',
          tLoading: 'Loading #%curr%...',
          mainClass: 'mfp-with-zoom',
          gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0,1] // Will preload 0 - before current, and 1 after the current image
          },
          image: {
            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
            tCounter: '%curr% / %total%',
            titleSrc: function(item) {
              return item.el.parents('.gallery-item').find('.item_header').html()+item.el.parents('.gallery-item').find('.item_introtext').html();
            }
          },
          zoom: {
            enabled: true,
            duration: 300,
            easing: 'ease-in-out',
            opener: function(openerElement) {
              return openerElement.is('img') ? openerElement : openerElement.parents('.gallery-item').find('img');
            }
          }
        });
        jQuery('ul#isotopeContainer.hover_false').magnificPopup({
          delegate: 'a.galleryZoomIcon:visible',
          type: 'image',
          tLoading: 'Loading #%curr%...',
          mainClass: 'mfp-with-zoom',
          gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0,1] // Will preload 0 - before current, and 1 after the current image
          },
          image: {
            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
            tCounter: '%curr% / %total%',
            titleSrc: function(item) {
              return item.el.parents('.gallery-item').find('.item_header').html()+item.el.parents('.gallery-item').find('.item_introtext').html();
            }
          },
          zoom: {
            enabled: true,
            duration: 300,
            easing: 'ease-in-out',
            opener: function(openerElement) {
              return openerElement.is('img') ? openerElement : openerElement.find('img');
            }
          }
        });
      }
      function magnificListInit(){
        jQuery('ul#isotopeContainer.hover_true').magnificPopup({
          delegate: 'a.galleryZoomIcon:visible',
          type: 'image',
          tLoading: 'Loading #%curr%...',
          mainClass: 'mfp-with-zoom',
          gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0,1] // Will preload 0 - before current, and 1 after the current image
          },
          image: {
            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
            tCounter: '%curr% / %total%',
            titleSrc: function(item) {
              return item.el.parents('.gallery-item').find('.item_header').html()+item.el.parents('.gallery-item').find('.item_introtext').html();
            }
          },
          zoom: {
            enabled: true,
            duration: 300,
            easing: 'ease-in-out',
            opener: function(openerElement) {
              return openerElement.is('img') ? openerElement : openerElement.find('img');
            }
          }
        });
      }
   });
}); 
</script>