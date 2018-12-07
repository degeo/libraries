<? if( !empty( $dataset ) ): ?>
<div class="recipes">
<? foreach( $dataset as $data ): ?>
<div class="recipes-content">
<h4><a href="<?=site_url("recipe/{$data['recipe_url']}")?>"><?=$data['recipe_name']?></a></h4>
</div>
<? endforeach; ?>

<? if( !empty( $pagination_html ) ): ?>
<? $this->load->view('degeo-foundation/pagination') ?>
<? endif; ?>
</div>
<? endif; ?>