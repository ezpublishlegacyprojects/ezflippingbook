<?php
//
// Created on: <19-Sep-2002 16:45:08 kk>
//
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.0
// BUILD VERSION: 23234
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//

$Module = $Params['Module'];

/**
 *  Convert a phrase from the lower case and underscored form
 *  to the camel case form
 *
 *  @param string $lower_case_and_underscored_word  Phrase to
 *                                                   convert
 *  @return string  Camel case form of the phrase
 */
function camelize($lower_case_and_underscored_word) {
    $i = str_replace(" ","",ucwords(str_replace("_"," ",$lower_case_and_underscored_word)));
    return strtolower( substr( $i, 0, 1 ) ) . substr( $i, 1 );
}

if ( !isset ( $Params['NodeID'] ) )
{
    eZDebug::writeError( 'No node specified' );
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$nodeID = $Params['NodeID'];
$galleryNode = eZContentObjectTreeNode::fetch( $nodeID );

// Get and check if RSS Feed exists
if ( !$galleryNode )
{
    eZDebug::writeError( 'Could not find gallery : ' . $Params['NodeID'] );
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$childs = "";

foreach( $galleryNode->children() as $child )
{
	$contentObject = eZContentObject::fetch( $child->ContentObject->ID );
	$attributes = $contentObject->fetchDataMap();
	$image = new eZImageAliasHandler( $attributes['image'] );
	$needed_image = $image->attribute( 'flippingbook' );
	$childs .= '      <page>/' . $needed_image['full_path'] . "</page>\n";
}

$attributes = $galleryNode->dataMap();

$xmlOutput="<FlippingBook>\n";
$identifier = '/^xml_/';

foreach( $attributes as $key => $attribute )
{
	if( preg_match( $identifier, $key ) && $attribute->hasContent() )
	{
		$tag = camelize( preg_replace( $identifier, '', $key ) );
		switch( $attribute->isA() )
		{
			case "ezboolean":
				$value = ( $attribute->toString() == "0" ? 'false' : 'true' );
				break;
			case "ezbinaryfile":
				$file = explode( '|', $attribute->toString() );
				$value = '/' . $file[0];
				break;
			default:
				$value = $attribute->toString();
				break;
		}

		$xmlOutput.="   <$tag>" . $value . "</$tag>\n";
	}
}

/*
$xmlOutput.="   <width>" . $attributes['xml_width']->DataInt . "</width>\n";
$xmlOutput.="   <height>" . $attributes['xml_height']->DataInt . "</height>\n";

$xmlOutput.="   <scaleContent>" . $attributes['xml_scale_content']->DataInt . "</scaleContent>\n";
$xmlOutput.="   <firstPage>" . $attributes['xml_first_page']->DataInt . "</firstPage>\n";
$xmlOutput.="   <alwaysOpened>" . 'true' . "</alwaysOpened>\n";
$xmlOutput.="   <autoFlip>" . $attributes['xml_auto_flip']->DataInt . "</autoFlip>\n";

$xmlOutput.="   <flipOnClick>" . $attributes['xml_flip_on_click']->DataInt . "</flipOnClick>\n";
$xmlOutput.="   <staticShadowsDepth>" . $attributes['xml_static_shadows_depth']->DataInt . "</staticShadowsDepth>\n";
$xmlOutput.="   <dynamicShadowsDepth>" . $attributes['xml_dinamic_shadows_depth']->DataInt . "</dynamicShadowsDepth>\n";

$xmlOutput.="   <moveSpeed>" . $attributes['xml_move_speed']->DataInt . "</moveSpeed>\n";
$xmlOutput.="   <closeSpeed>" . $attributes['xml_close_speed']->DataInt . "</closeSpeed>\n";
$xmlOutput.="   <flipSound>01.mp3</flipSound>\n";

$xmlOutput.="   <pageBack>" . $attributes['xml_page_back']->DataInt . "</pageBack>\n";
$xmlOutput.="   <loadOnDemand>" . $attributes['xml_load_on_demand']->DataInt . "</loadOnDemand>\n";
$xmlOutput.="   <cachePages>" . $attributes['xml_cache_pages']->DataInt . "</cachePages>\n";

$xmlOutput.="   <cacheSize>" . $attributes['xml_cache_size']->DataInt . "</cacheSize>\n";
$xmlOutput.="   <preloaderType>Round</preloaderType>\n";
*/

$xmlOutput.="   <pages>\n";

$xmlOutput .= $childs;

$xmlOutput .= "   </pages>\n";
$xmlOutput .= "</FlippingBook>";

// Set header settings
$httpCharset = eZTextCodec::httpCharset();
header( 'Content-Type: text/xml; charset=' . $httpCharset );
header( 'Content-Length: '.strlen($xmlOutput) );
header( 'X-Powered-By: eZ Publish' );

while ( @ob_end_clean() );

echo $xmlOutput;

eZExecution::cleanExit();

?>
