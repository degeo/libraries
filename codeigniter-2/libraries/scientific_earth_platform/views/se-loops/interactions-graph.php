<div class="center" id="interaction-canvas" style="border:1px solid #DDD;padding:10px;"></div>
<script type="text/javascript">
var redraw, g, renderer;
$(document).ready(function() {

    var width = jQuery("#interaction-canvas").parents('.columns').width();
    var height = width * 0.7;

    g = new Graph();

	<?php
	$grouped_taxon_ids = group_interaction_taxon_ids( $dataset );

	foreach( $grouped_taxon_ids as $key => $interaction ):
		echo "g.addNode('{$key}',{ label:'{$interaction['taxon_name']}' });\r\n";
	endforeach;

	foreach( $dataset as $interaction ):
		switch( $interaction['interaction_name'] ):
			case 'Mutualism':
			case 'Commensalism':
				echo "g.addEdge('{$interaction['x_taxon']['taxon_id']}','{$interaction['y_taxon']['taxon_id']}', { label: '{$interaction['interaction_name']}', directed: false, stroke: '#0f0', fill: '#0f0' } )\r\n";
				break;
			case 'Predation':
			case 'Parasitism':
			case 'Amensalism':
			case 'Competition':
				echo "g.addEdge('{$interaction['x_taxon']['taxon_id']}','{$interaction['y_taxon']['taxon_id']}', { label: '{$interaction['interaction_name']}', directed: false, stroke: '#f00', fill: '#f00' } )\r\n";
				break;
			case 'Neutralism':
				echo "g.addEdge('{$interaction['x_taxon']['taxon_id']}','{$interaction['y_taxon']['taxon_id']}', { label: '{$interaction['interaction_name']}', directed: false, stroke: '#ff0', fill: '#ff0' } )\r\n";
				break;
			default:
				echo "g.addEdge('{$interaction['x_taxon']['taxon_id']}','{$interaction['y_taxon']['taxon_id']}', { label: '{$interaction['interaction_name']}', directed: false, stroke: '#2BA6CB', fill: '#2BA6CB' } )\r\n";
				break;
		endswitch;
	endforeach;
	?>

    var layouter = new Graph.Layout.Spring(g);

    renderer = new Graph.Renderer.Raphael('interaction-canvas', g, width, height);
	renderer.draw();
/*
    redraw = function() {
        layouter.layout();
        renderer.draw();
    };
    hide = function(id) {
        g.nodes[id].hide();
    };
    show = function(id) {
        g.nodes[id].show();
    };
*/			});
</script>