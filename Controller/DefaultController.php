<?php

namespace Elephantly\AmpConverterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Elephantly\AmpConverterBundle\Converter\AmpConverter;

class DefaultController extends Controller
{
    public function indexAction()
    {
//         $html = '<!-- Note: this image is in the public domain. https://commons.wikimedia.org/wiki/File:"Birdcatcher"_with_jockey_up.jpg -->
// 
// <!-- should transform to amp-img -->
// <img src="https://upload.wikimedia.org/wikipedia/commons/e/ee/%22Birdcatcher%22_with_jockey_up.jpg">
// 
// <!-- should transform to amp-img with fixed layout, preserving the width and height -->
// <img src="https://upload.wikimedia.org/wikipedia/commons/e/ee/%22Birdcatcher%22_with_jockey_up.jpg" width="125" height="96">
// 
// <!-- nonexistent image, should refuse to convert to amp-img and keep it as it is -->
// <img src="https://upload.wikimedia.org/wikipedia/commons/e/ee/non-existent-image1234.jpg">
// 
// <!-- should provide layout and height width and make layout responsive -->
// <amp-img src="https://upload.wikimedia.org/wikipedia/commons/e/ee/%22Birdcatcher%22_with_jockey_up.jpg"></amp-img>
// 
// <!-- since only width exists, overwrite with height and width from original image -->
// <amp-img layout="responsive" src="https://upload.wikimedia.org/wikipedia/commons/e/ee/%22Birdcatcher%22_with_jockey_up.jpg" width="500"></amp-img>
// 
// <!-- since height is illegal, overwrite with height and width from original image -->
// <amp-img layout="responsive" src="https://upload.wikimedia.org/wikipedia/commons/e/ee/%22Birdcatcher%22_with_jockey_up.jpg" width="625" height="auto"></amp-img>
// 
// <!-- since units are inconsistent, overwrite with height+width from original image -->
// <amp-img layout="responsive" src="https://upload.wikimedia.org/wikipedia/commons/e/ee/%22Birdcatcher%22_with_jockey_up.jpg" width="625rem" height="480"></amp-img>
// 
// <!-- should transform to amp-pixel -->
// <img src="https://upload.wikimedia.org/wikipedia/commons/c/ce/Transparent.gif">
// 
// <!-- should transform to amp-img instead of amp-anim because of default option["use_amp_anim_tag"] = false -->
// <img src="https://upload.wikimedia.org/wikipedia/commons/b/bb/Quilt_design_as_46x46_uncompressed_GIF.gif">';

        
        $html = '<img src="https://upload.wikimedia.org/wikipedia/commons/e/ee/%22Birdcatcher%22_with_jockey_up.jpg">';
        $converter = new AmpConverter($html, array());
        $output = $converter->convert()->saveXML();
        echo $output;exit;
        return $this->render('ElephantlyAmpConverterBundle:Default:index.html.twig');
    }
}
