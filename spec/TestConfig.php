<?php

namespace Elephantly\AmpConverterBundle\spec;

/**
* primary @author purplebabar(lalung.alexandre@gmail.com)
*/
class TestConfig
{
    public static $options = array(
        'img' => array('amp_anim' => true),
        'illegal_tags' => array('script', 'div#fb-root'),
        'illegal_tag_attributes' => array('a' => array('shape'), 'p' => array('type')),
        'illegal_attributes' => array('allowfullscreen', 'frameborder'),
        'pinterest' => array('follow_label' => 'Follow us'),
        'remove_incorrect_tags' => true
    );

    public static $converters = array('iframe[src*="dailymotion.com"]' => 'Elephantly\AmpConverterBundle\Converter\Media\AmpDailymotionIframeConverter',
                                    'iframe' => 'Elephantly\AmpConverterBundle\Converter\Layout\AmpIframeConverter',
                                    'img' => 'Elephantly\AmpConverterBundle\Converter\Media\AmpImgConverter',
                                    'blockquote.twitter-tweet' => 'Elephantly\AmpConverterBundle\Converter\Social\AmpTwitterConverter');

}
