<h1>{$node.name|wash()}</h1>

<div id="FlippingBookGallery">
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/ flash/swflash.cab#version=6,0,29,0" width="{$node.data_map.xml_width.content}" height="{$node.data_map.xml_height.content}">
 
<param name="movie" value={"images/flippingbook.swf"|ezdesign}> 

<param name="quality" value="high"> 
<param name="scale" value="noscale"> 
<param name="bgcolor" value="{$node.data_map.xml_page_back.content}"> 
<param name="flashVars" value="xmlConfig={concat( "flippingbookxml/getGallery/", $node.node_id )|ezroot(no)}">

<embed src={"images/flippingbook.swf"|ezdesign} width="{$node.data_map.xml_width.content}" height="{$node.data_map.xml_height.content}" flashvars="xmlConfig={concat( "flippingbookxml/getGallery/", $node.node_id )|ezroot(no)}" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" scale="noscale" bgcolor="{$node.data_map.xml_page_back.content}"></embed>
</object>

</div>
 
